<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException('Demo data is for local development only.');
        }

        $manager = $this->user('demo-manager@example.test', 'Demo Manager', 'manager');
        $tester = $this->user('demo-tester@example.test', 'Demo Tester', 'tester');
        $alice = $this->user('demo.alice@example.test', 'Alice Demo', 'developer');
        $bob = $this->user('demo.bob@example.test', 'Bob Demo', 'developer');

        $now = now();
        DB::table('projects')->updateOrInsert(
            ['manager_id' => $manager->id, 'name' => 'Demo Bug Tracker'],
            ['stack' => 'Laravel, PHP, SQLite', 'updated_at' => $now, 'created_at' => $now],
        );
        $projectId = (int) DB::table('projects')
            ->where('manager_id', $manager->id)
            ->where('name', 'Demo Bug Tracker')
            ->value('id');

        $workflowStatuses = [['To Do', false], ['In Progress', false], ['Review', false], ['Done', true], ['Duplicate', false]];
        foreach ($workflowStatuses as $position => [$name, $isDone]) {
            DB::table('project_workflow_statuses')->insertOrIgnore([
                'project_id' => $projectId, 'name' => $name, 'position' => $position,
                'is_done' => $isDone, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        foreach ([
            ['To Do', 'In Progress'], ['In Progress', 'To Do'], ['In Progress', 'Review'],
            ['Review', 'In Progress'], ['Review', 'Done'], ['Done', 'To Do'],
        ] as [$from, $to]) {
            DB::table('project_workflow_transitions')->insertOrIgnore([
                'project_id' => $projectId, 'from_status' => $from, 'to_status' => $to,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach ([$tester, $alice, $bob] as $member) {
            DB::table('project_members')->updateOrInsert(
                ['project_id' => $projectId, 'user_id' => $member->id],
                [],
            );
        }

        foreach ([
            ['user' => $alice, 'skills' => 'php laravel api backend database'],
            ['user' => $bob, 'skills' => 'javascript ui css frontend testing'],
        ] as $developer) {
            DB::table('developers')->updateOrInsert(
                ['project_id' => $projectId, 'name' => $developer['user']->name],
                ['skills' => $developer['skills'], 'active_tasks' => 1, 'updated_at' => $now, 'created_at' => $now],
            );
        }

        $bugs = [
            [
                'title' => 'Login form rejects valid email addresses',
                'description' => 'A valid email with a plus sign is rejected on the sign-in page.',
                'priority' => 'High',
                'status' => 'In Progress',
                'assigned_to' => $alice->name,
                'language' => 'PHP',
                'code' => "if (!filter_var(\$email, FILTER_VALIDATE_EMAIL)) { return false; }",
            ],
            [
                'title' => 'Dashboard cards overflow on mobile',
                'description' => 'The summary cards extend beyond the viewport on small screens.',
                'priority' => 'Medium',
                'status' => 'To Do',
                'assigned_to' => $bob->name,
                'language' => 'JavaScript',
                'code' => 'const cards = document.querySelectorAll(".summary-card");',
            ],
        ];

        foreach ($bugs as $bugData) {
            $triage = [
                'language' => $bugData['language'],
                'error_name' => 'Demo triage result',
                'syntax_status' => 'passed',
                'syntax_checker' => $bugData['language'] === 'PHP' ? 'php -l' : 'Node.js --check',
                'steps' => ['Review the report and reproduce the issue.'],
                'analysis_scope' => 'Seeded sample result for local demonstration.',
                'fixed_code' => $bugData['code'],
                'diff' => '',
                'feature' => 'General',
                'assignment_reason' => 'Selected from the demo project team.',
                'attachment_note' => 'No attachment was provided.',
            ];
            $bugValues = [
                'project_id' => $projectId,
                'title' => $bugData['title'],
                'description' => $bugData['description'],
                'priority' => $bugData['priority'],
                'status' => $bugData['status'],
                'assigned_to' => $bugData['assigned_to'],
                'is_duplicate' => false,
                'duplicate_of' => null,
                'detected_feature' => 'General',
                'triage_result' => json_encode([
                    'priority' => $bugData['priority'],
                    'status' => $bugData['status'],
                    'assigned_to' => $bugData['assigned_to'],
                    'is_duplicate' => false,
                    'similarity_score' => 0,
                    'code_help' => $triage,
                ], JSON_THROW_ON_ERROR),
                'updated_at' => $now,
                'created_at' => $now,
            ];

            DB::table('bugs')->updateOrInsert(
                ['project_id' => $projectId, 'title' => $bugData['title']],
                $bugValues,
            );
            $bugId = (int) DB::table('bugs')->where('project_id', $projectId)->where('title', $bugData['title'])->value('id');

            if (!DB::table('bug_activities')->where('bug_id', $bugId)->exists()) {
                $activities = [[
                    'project_id' => $projectId,
                    'bug_id' => $bugId,
                    'actor_id' => $tester->id,
                    'actor_name' => $tester->name,
                    'event_type' => 'created',
                    'from_value' => null,
                    'to_value' => 'Status: '.$bugData['status'].'; Priority: '.$bugData['priority'],
                    'created_at' => $now->copy()->subHours(3),
                ], [
                    'project_id' => $projectId,
                    'bug_id' => $bugId,
                    'actor_id' => $manager->id,
                    'actor_name' => $manager->name,
                    'event_type' => 'assignment_changed',
                    'from_value' => 'Unassigned',
                    'to_value' => $bugData['assigned_to'],
                    'created_at' => $now->copy()->subHours(2),
                ]];

                if ($bugData['status'] !== 'To Do') {
                    $activities[] = [
                        'project_id' => $projectId,
                        'bug_id' => $bugId,
                        'actor_id' => $alice->id,
                        'actor_name' => $alice->name,
                        'event_type' => 'status_changed',
                        'from_value' => 'To Do',
                        'to_value' => $bugData['status'],
                        'created_at' => $now->copy()->subHour(),
                    ];
                }

                DB::table('bug_activities')->insert($activities);
            }
        }
    }

    private function user(string $email, string $name, string $role): User
    {
        return User::query()->updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'role' => $role, 'password' => 'demo-password'],
        );
    }
}
