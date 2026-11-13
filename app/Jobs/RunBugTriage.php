<?php

namespace App\Jobs;

use App\Models\User;
use App\Events\BugUpdated;
use App\Notifications\BugUpdateNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Throwable;

class RunBugTriage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;
    public int $tries = 3;

    public function __construct(
        public readonly int $bugId,
        public readonly int $projectId,
        public readonly int $reporterId,
        public readonly array $analysisInput,
    ) {}

    public function handle(): void
    {
        $bug = DB::table('bugs')->where('project_id', $this->projectId)->where('id', $this->bugId)->first();
        if (!$bug || $bug->triage_state === 'complete') return;
        $startedAt = microtime(true);
        $doneStatuses = DB::table('project_workflow_statuses')->where('project_id', $this->projectId)->where('is_done', true)->pluck('name')->all();
        $pastBugs = DB::table('bugs')->where('project_id', $this->projectId)->where('id', '!=', $this->bugId)->where('is_duplicate', false)
            ->where('triage_state', 'complete')->get(['id', 'title', 'description'])->map(fn ($row) => (array) $row)->all();
        $history = [];
        if ($doneStatuses) {
            foreach (DB::table('bugs')->where('project_id', $this->projectId)->whereIn('status', $doneStatuses)->where('is_duplicate', false)->whereNotNull('assigned_to')->get(['assigned_to', 'detected_feature', 'triage_result']) as $fixedBug) {
                $entry = $history[$fixedBug->assigned_to] ?? ['resolved_features' => [], 'resolved_languages' => []];
                if ($fixedBug->detected_feature) $entry['resolved_features'][] = strtolower($fixedBug->detected_feature);
                $old = json_decode($fixedBug->triage_result ?: '', true) ?: [];
                $language = strtolower($old['code_help']['language'] ?? '');
                if ($language && $language !== 'unknown') $entry['resolved_languages'][] = $language;
                $history[$fixedBug->assigned_to] = $entry;
            }
        }
        $developers = DB::table('developers')->where('project_id', $this->projectId)->get(['id', 'name', 'skills', 'active_tasks', 'capacity'])
            ->map(fn ($developer) => array_merge((array) $developer, $history[$developer->name] ?? ['resolved_features' => [], 'resolved_languages' => []]))->all();
        $input = [...$this->analysisInput, 'past_bugs' => $pastBugs, 'developers' => $developers];
        $payloadPath = tempnam(storage_path('app'), 'triage_payload_');
        if ($payloadPath === false) throw new \RuntimeException('Could not prepare triage payload.');
        try {
            file_put_contents($payloadPath, json_encode($input, JSON_THROW_ON_ERROR));
            $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
            $process = Process::timeout(110)->run([$python, base_path('agent/triage.py'), $payloadPath]);
        } finally {
            @unlink($payloadPath);
        }
        if (!$process->successful()) throw new \RuntimeException('Triage failed: '.mb_substr($process->errorOutput(), 0, 1000));
        $analysis = json_decode($process->output(), true);
        if (!is_array($analysis)) throw new \RuntimeException('Triage returned an invalid response.');
        $duration = (int) round((microtime(true) - $startedAt) * 1000);
        $isDuplicate = (bool) ($analysis['is_duplicate'] ?? false);
        $assignee = $analysis['assigned_to'] ?? 'Unassigned';
        $status = $isDuplicate ? 'Duplicate' : DB::table('project_workflow_statuses')->where('project_id', $this->projectId)->where('name', '!=', 'Duplicate')->orderBy('position')->value('name');
        $analysis['status'] = $status;

        DB::transaction(function () use ($bug, $analysis, $duration, $isDuplicate, $assignee, $status) {
            $locked = DB::table('bugs')->where('id', $this->bugId)->lockForUpdate()->first();
            if (!$locked || $locked->triage_state === 'complete') return;
            DB::table('bugs')->where('id', $this->bugId)->update([
                'priority' => $analysis['priority'] ?? 'Medium', 'status' => $status, 'assigned_to' => $assignee,
                'recommended_assignee' => $assignee, 'is_duplicate' => $isDuplicate,
                'duplicate_of' => $analysis['duplicate_of'] ?? null, 'detected_feature' => $analysis['feature'] ?? null,
                'sentiment' => $analysis['sentiment'] ?? 'neutral', 'sentiment_score' => $analysis['sentiment_score'] ?? 0,
                'blocker_signal' => (bool) ($analysis['blocker_signal'] ?? false),
                'triage_result' => json_encode($analysis, JSON_THROW_ON_ERROR), 'triage_state' => 'complete', 'updated_at' => now(),
            ]);
            DB::table('bug_triage_metrics')->insert([
                'project_id' => $this->projectId, 'bug_id' => $this->bugId, 'triage_duration_ms' => $duration,
                'duplicate_similarity_score' => $analysis['similarity_score'] ?? 0, 'predicted_duplicate' => $isDuplicate,
                'recommended_assignee' => $assignee, 'created_at' => now(), 'updated_at' => now(),
            ]);
            $this->activity('created', null, 'AI triage complete; status: '.$status.'; priority: '.($analysis['priority'] ?? 'Medium'));
            if ($assignee !== 'Unassigned') {
                $this->activity('assignment_changed', 'Unassigned', $assignee);
                DB::table('developers')->where('project_id', $this->projectId)->where('name', $assignee)->increment('active_tasks');
            }
        });
        $this->broadcastNotice($bug, $assignee);
    }

    public function failed(Throwable $exception): void
    {
        DB::table('bugs')->where('id', $this->bugId)->where('triage_state', 'queued')->update([
            'triage_state' => 'failed', 'triage_result' => json_encode(['error' => 'Triage failed. Please retry or contact the project manager.']), 'updated_at' => now(),
        ]);
        $this->activity('triage_failed', 'Queued', 'Failed');
    }

    private function activity(string $event, ?string $from, ?string $to): void
    {
        DB::table('bug_activities')->insert([
            'project_id' => $this->projectId, 'bug_id' => $this->bugId, 'actor_id' => $this->reporterId,
            'actor_name' => User::find($this->reporterId)?->name ?? 'Triage agent', 'event_type' => $event,
            'from_value' => $from, 'to_value' => $to, 'created_at' => now(),
        ]);
        event(new BugUpdated($this->projectId, $this->bugId, $event, (string) ($to ?: $event)));
    }

    private function broadcastNotice(object $bug, string $assignee): void
    {
        $userIds = array_filter(array_merge([$this->reporterId], DB::table('bug_watchers')->where('bug_id', $this->bugId)->pluck('user_id')->all()));
        if ($assignee !== 'Unassigned') {
            $userIds = array_merge($userIds, DB::table('project_members')->join('users', 'users.id', '=', 'project_members.user_id')
                ->where('project_members.project_id', $this->projectId)->where('users.name', $assignee)->where('users.role', 'developer')->pluck('users.id')->all());
        }
        $notification = new BugUpdateNotification($this->projectId, $this->bugId, 'triage_complete', 'Bug triage complete', 'AI triage finished and the issue is ready for review.', 'Triage agent');
        User::whereIn('id', array_unique($userIds))->get()->each(fn (User $user) => $user->notify($notification));
    }
}
