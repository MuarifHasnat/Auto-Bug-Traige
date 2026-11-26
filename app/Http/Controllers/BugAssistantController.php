<?php

namespace App\Http\Controllers;

use App\Services\BugAssistant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BugAssistantController extends Controller
{
    public function chat(Request $request, int $id, BugAssistant $assistant): JsonResponse
    {
        [$projectId, $bug] = $this->authorizeBugForProject($id);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);
        $question = trim($data['message']);

        $history = DB::table('bug_assistant_messages')
            ->where('project_id', $projectId)
            ->where('bug_id', $id)
            ->orderBy('id')
            ->limit(16)
            ->get(['role', 'content'])
            ->map(fn ($row) => ['role' => (string) $row->role, 'content' => (string) $row->content])
            ->all();

        $now = now();
        DB::table('bug_assistant_messages')->insert([
            'project_id' => $projectId,
            'bug_id' => $id,
            'user_id' => Auth::id(),
            'role' => 'user',
            'content' => $question,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $triage = json_decode($bug->triage_result ?: '', true) ?: [];
        $result = $assistant->reply($bug, $triage, $history, $question);

        $replyTime = now();
        DB::table('bug_assistant_messages')->insert([
            'project_id' => $projectId,
            'bug_id' => $id,
            'user_id' => Auth::id(),
            'role' => 'assistant',
            'content' => $result['reply'],
            'created_at' => $replyTime,
            'updated_at' => $replyTime,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'role' => 'assistant',
                'content' => $result['reply'],
                'created_at' => $replyTime->toIso8601String(),
            ],
            'used_llm' => $result['used_llm'],
            'fallback_reason' => $result['fallback_reason'],
        ]);
    }

    public function followUp(int $id, BugAssistant $assistant): JsonResponse
    {
        [$projectId, $bug] = $this->authorizeBugForProject($id);
        $triage = json_decode($bug->triage_result ?: '', true) ?: [];
        $result = $assistant->generateFollowUpQuestions($bug, $triage);

        $now = now();
        DB::table('bug_assistant_messages')->insert([
            [
                'project_id' => $projectId,
                'bug_id' => $id,
                'user_id' => Auth::id(),
                'role' => 'user',
                'content' => 'Generate follow-up clarification questions.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'project_id' => $projectId,
                'bug_id' => $id,
                'user_id' => Auth::id(),
                'role' => 'assistant',
                'content' => $result['summary_text'],
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        return response()->json([
            'success' => true,
            'questions' => $result['questions'],
            'citations' => $result['citations'],
            'summary_text' => $result['summary_text'],
            'used_llm' => $result['used_llm'],
            'fallback_reason' => $result['fallback_reason'],
        ]);
    }

    public function testPlan(int $id, BugAssistant $assistant): JsonResponse
    {
        [$projectId, $bug] = $this->authorizeBugForProject($id);
        $triage = json_decode($bug->triage_result ?: '', true) ?: [];
        $result = $assistant->generateTestPlan($bug, $triage);

        $now = now();
        DB::table('bug_assistant_messages')->insert([
            [
                'project_id' => $projectId,
                'bug_id' => $id,
                'user_id' => Auth::id(),
                'role' => 'user',
                'content' => 'Generate reproduction & verification test plan.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'project_id' => $projectId,
                'bug_id' => $id,
                'user_id' => Auth::id(),
                'role' => 'assistant',
                'content' => $result['summary_text'],
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        return response()->json([
            'success' => true,
            'title' => $result['title'],
            'checklist' => $result['checklist'],
            'citations' => $result['citations'],
            'summary_text' => $result['summary_text'],
            'used_llm' => $result['used_llm'],
            'fallback_reason' => $result['fallback_reason'],
        ]);
    }

    /**
     * @return array{0: int, 1: object}
     */
    private function authorizeBugForProject(int $id): array
    {
        $user = Auth::user();
        abort_unless($user && in_array($user->role, ['manager', 'developer', 'tester'], true), 403);

        $bug = DB::table('bugs')->where('id', $id)->first();
        abort_unless($bug, 404);

        $projectId = (int) $bug->project_id;
        $sessionProjectId = (int) session('project_id');
        if ($sessionProjectId > 0 && $sessionProjectId !== $projectId) {
            abort(403, 'You do not have access to this bug report.');
        }

        $isMember = $user->role === 'manager'
            ? DB::table('projects')->where('id', $projectId)->where('manager_id', $user->id)->exists()
            : DB::table('project_members')->where('project_id', $projectId)->where('user_id', $user->id)->exists();

        abort_unless($isMember, 403, 'You do not have access to this bug report.');

        return [$projectId, $bug];
    }
}
