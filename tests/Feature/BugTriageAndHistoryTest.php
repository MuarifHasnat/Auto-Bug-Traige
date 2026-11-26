<?php

namespace Tests\Feature;

use App\Events\AgentProgressUpdated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Process;
use Tests\TestCase;

class BugTriageAndHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_tester_can_submit_a_bug_and_save_triage_and_activity(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $tester = User::factory()->create(['name' => 'Casey Tester', 'role' => 'tester']);
        $projectId = $this->createProject($manager, 'Triage project');
        $this->addMember($projectId, $tester);
        $this->addDeveloper($projectId, 'Dev One', 'php laravel', 0);

        Process::fake(['*' => json_encode([
            'is_duplicate' => false,
            'duplicate_of' => null,
            'similarity_score' => 0,
            'assigned_to' => 'Dev One',
            'priority' => 'High',
            'status' => 'Open',
            'code_help' => [
                'language' => 'PHP',
                'syntax_status' => 'passed',
                'syntax_checker' => 'php -l',
                'error_name' => 'PHP syntax check passed',
                'steps' => ['Review the failing flow.'],
                'analysis_scope' => 'Syntax only.',
                'fixed_code' => 'return true;',
                'diff' => '',
            ],
        ], JSON_THROW_ON_ERROR)]);

        $this->actingAs($tester)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/store', [
                'title' => 'Checkout fails',
                'description' => 'Checkout returns an error for valid cards.',
                'issue_type' => 'Bug',
                'labels' => 'api, checkout',
                'language' => 'PHP',
                'code_snippet' => '<?php echo "sample";',
            ])
            ->assertRedirect('/ai-agent')
            ->assertSessionHas('success');

        $bug = DB::table('bugs')->where('project_id', $projectId)->where('title', 'Checkout fails')->first();
        $this->assertNotNull($bug);
        $this->assertSame('Dev One', $bug->assigned_to);
        $this->assertSame('High', $bug->priority);
        $this->assertSame('To Do', $bug->status);
        $this->assertSame('Bug', $bug->issue_type);
        $this->assertSame('complete', $bug->triage_state);
        $this->assertSame(['api', 'checkout'], json_decode($bug->labels, true));
        $this->assertDatabaseHas('bug_triage_metrics', [
            'bug_id' => $bug->id,
            'predicted_duplicate' => false,
            'recommended_assignee' => 'Dev One',
        ]);
        $this->assertLessThan(5000, (int) DB::table('bug_triage_metrics')->where('bug_id', $bug->id)->value('triage_duration_ms'));
        $this->assertSame(1, (int) DB::table('developers')->where('project_id', $projectId)->where('name', 'Dev One')->value('active_tasks'));
        $this->assertDatabaseHas('bug_activities', ['bug_id' => $bug->id, 'event_type' => 'created', 'actor_id' => $tester->id]);
        $this->assertDatabaseHas('bug_activities', ['bug_id' => $bug->id, 'event_type' => 'assignment_changed', 'to_value' => 'Dev One']);

        $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/recommendation/accept', ['id' => $bug->id])->assertRedirect();
        $this->assertDatabaseHas('bug_triage_metrics', ['bug_id' => $bug->id, 'assignment_feedback' => 'accepted']);
        $this->get('/dashboard')->assertOk()->assertSee('Recommendation acceptance')->assertSee('100%');
    }

    public function test_manager_status_and_reassignment_changes_keep_history_and_workload_in_sync(): void
    {
        $manager = User::factory()->create(['name' => 'Morgan Manager', 'role' => 'manager']);
        $projectId = $this->createProject($manager, 'History project');
        $firstDeveloper = $this->addDeveloper($projectId, 'Dev One', 'php', 1);
        $this->addDeveloper($projectId, 'Dev Two', 'javascript', 0);
        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId,
            'title' => 'Old assignment',
            'description' => 'A bug that needs a new owner.',
            'priority' => 'Medium',
            'status' => 'To Do',
            'assigned_to' => 'Dev One',
            'is_duplicate' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('bug_triage_metrics')->insert([
            'project_id' => $projectId, 'bug_id' => $bugId, 'triage_duration_ms' => 20,
            'duplicate_similarity_score' => 12.5, 'predicted_duplicate' => false,
            'recommended_assignee' => 'Dev One', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/reassign', ['id' => $bugId, 'assigned_to' => 'Dev Two'])
            ->assertRedirect();

        $this->assertDatabaseHas('bugs', ['id' => $bugId, 'assigned_to' => 'Dev Two']);
        $this->assertDatabaseHas('bug_triage_metrics', ['bug_id' => $bugId, 'assignment_feedback' => 'rejected']);
        $this->assertDatabaseHas('developers', ['id' => $firstDeveloper, 'active_tasks' => 0]);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev Two', 'active_tasks' => 1]);
        $this->assertDatabaseHas('bug_activities', [
            'bug_id' => $bugId,
            'actor_id' => $manager->id,
            'event_type' => 'assignment_changed',
            'from_value' => 'Dev One',
            'to_value' => 'Dev Two',
        ]);

        $devTwoUser = User::factory()->create(['name' => 'Dev Two', 'role' => 'developer']);
        $this->addMember($projectId, $devTwoUser);

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'In Progress'])->assertForbidden();

        $this->actingAs($devTwoUser)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/override', ['id' => $bugId, 'status' => 'In Progress'])->assertRedirect();

        $this->assertDatabaseHas('bug_activities', [
            'bug_id' => $bugId,
            'actor_id' => $devTwoUser->id,
            'event_type' => 'status_changed',
            'from_value' => 'To Do',
            'to_value' => 'In Progress',
        ]);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev Two', 'active_tasks' => 1]);

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'Review'])->assertRedirect();

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'Done'])->assertRedirect();

        $this->assertDatabaseHas('bug_activities', [
            'bug_id' => $bugId,
            'actor_id' => $devTwoUser->id,
            'event_type' => 'status_changed',
            'from_value' => 'Review',
            'to_value' => 'Done',
        ]);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev Two', 'active_tasks' => 0]);
    }

    public function test_developer_cannot_create_triage_reports_or_reassign_bugs(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $developer = User::factory()->create(['role' => 'developer']);
        $projectId = $this->createProject($manager, 'Permissions project');
        $this->addMember($projectId, $developer);

        $this->actingAs($developer)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/store', [])->assertForbidden();
        $this->post('/api/bugs/reassign', [])->assertForbidden();
    }

    public function test_unlisted_default_workflow_moves_are_rejected(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $developer = User::factory()->create(['role' => 'developer']);
        $projectId = $this->createProject($manager, 'Workflow project');
        $this->addMember($projectId, $developer);
        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId, 'title' => 'Workflow ticket', 'description' => 'Move through default states.',
            'priority' => 'Medium', 'status' => 'To Do', 'issue_type' => 'Task', 'assigned_to' => 'Unassigned',
            'is_duplicate' => false, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($developer)->withSession(['project_id' => $projectId])->get('/workspace')->assertOk();

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'Done'])->assertStatus(422);
        $this->assertDatabaseHas('bugs', ['id' => $bugId, 'status' => 'To Do']);

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'In Progress'])->assertRedirect();
        $this->assertDatabaseHas('bugs', ['id' => $bugId, 'status' => 'In Progress']);
    }

    public function test_manager_duplicate_review_corrects_classification_and_tracks_the_original_prediction(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'Duplicate review project');
        $developer = $this->addDeveloper($projectId, 'Dev One', 'php', 1);
        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId, 'title' => 'Potential duplicate', 'description' => 'Review the classification.',
            'priority' => 'Medium', 'status' => 'To Do', 'assigned_to' => 'Dev One', 'is_duplicate' => false,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('bug_triage_metrics')->insert([
            'project_id' => $projectId, 'bug_id' => $bugId, 'triage_duration_ms' => 100,
            'duplicate_similarity_score' => 71.5, 'predicted_duplicate' => false,
            'recommended_assignee' => 'Dev One', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/duplicate-review', ['id' => $bugId, 'actual_duplicate' => '1'])->assertRedirect();
        $this->assertDatabaseHas('bugs', ['id' => $bugId, 'is_duplicate' => true, 'status' => 'Duplicate']);
        $this->assertDatabaseHas('bug_triage_metrics', ['bug_id' => $bugId, 'predicted_duplicate' => false, 'actual_duplicate' => true]);
        $this->assertDatabaseHas('developers', ['id' => $developer, 'active_tasks' => 0]);
    }

    public function test_assigned_developer_can_request_clarification_and_reporter_can_answer(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $tester = User::factory()->create(['name' => 'Report Owner', 'role' => 'tester']);
        $developer = User::factory()->create(['name' => 'Assigned Dev', 'role' => 'developer']);
        $projectId = $this->createProject($manager, 'Clarification project');
        $this->addMember($projectId, $tester);
        $this->addMember($projectId, $developer);
        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId, 'reporter_id' => $tester->id, 'title' => 'Unclear failure',
            'description' => 'It does not work.', 'priority' => 'Medium', 'status' => 'To Do',
            'assigned_to' => $developer->name, 'is_duplicate' => false, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($developer)->withSession(['project_id' => $projectId])
            ->post(route('bugs.clarifications.request'), ['bug_id' => $bugId, 'question' => 'Which steps reproduce this?'])
            ->assertRedirect();
        $requestId = (int) DB::table('bug_clarification_requests')->where('bug_id', $bugId)->value('id');
        $this->assertDatabaseHas('notifications', ['notifiable_id' => $tester->id, 'type' => 'App\\Notifications\\BugUpdateNotification']);

        $this->actingAs($tester)->withSession(['project_id' => $projectId])
            ->post(route('bugs.clarifications.answer'), ['request_id' => $requestId, 'answer' => 'Open Settings and press Save.'])
            ->assertRedirect();
        $this->assertDatabaseHas('bug_clarification_requests', ['id' => $requestId, 'status' => 'answered', 'answered_by' => $tester->id]);
        $this->assertDatabaseHas('bug_activities', ['bug_id' => $bugId, 'event_type' => 'clarification_requested']);
        $this->assertDatabaseHas('bug_activities', ['bug_id' => $bugId, 'event_type' => 'clarification_answered']);
    }

    public function test_project_member_can_print_a_bug_report_and_other_project_cannot_access_it(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $outsider = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'Printable project');
        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId, 'title' => 'Printable issue', 'description' => 'Details for the report.',
            'priority' => 'High', 'status' => 'To Do', 'is_duplicate' => false,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->get(route('bugs.report', $bugId))->assertOk()->assertSee('Printable issue')->assertSee('Print / Save as PDF');
        $otherProject = $this->createProject($outsider, 'Other project');
        $this->actingAs($outsider)->withSession(['project_id' => $otherProject])
            ->get(route('bugs.report', $bugId))->assertNotFound();
    }

    public function test_project_developer_can_open_dedicated_issue_details_page(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $developer = User::factory()->create(['name' => 'Alice Dev', 'role' => 'developer']);
        $outsider = User::factory()->create(['role' => 'developer']);

        $projectId = $this->createProject($manager, 'Dev Project');
        $this->addMember($projectId, $developer);
        $this->addDeveloper($projectId, 'Alice Dev', 'php laravel', 1);

        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId,
            'title' => 'Null pointer in payment checkout',
            'description' => 'Detailed reproduction steps for developer.',
            'priority' => 'High',
            'status' => 'In Progress',
            'assigned_to' => 'Alice Dev',
            'is_duplicate' => false,
            'detected_feature' => 'Payments',
            'triage_result' => json_encode([
                'priority' => 'High',
                'status' => 'In Progress',
                'code_help' => [
                    'feature' => 'Payments',
                    'error_name' => 'NullPointer check',
                    'syntax_status' => 'passed',
                    'steps' => ['Step 1: Check card token.'],
                ],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($developer)->withSession(['project_id' => $projectId])
            ->get("/bugs/{$bugId}");

        $response->assertOk();
        $response->assertSee('Null pointer in payment checkout');
        $response->assertSee('Detailed reproduction steps for developer');
        $response->assertSee('Alice Dev');
        $response->assertSee('Workflow State');

        $otherProject = $this->createProject($manager, 'Other Project');
        $this->addMember($otherProject, $outsider);
        $this->actingAs($outsider)->withSession(['project_id' => $otherProject])
            ->get("/bugs/{$bugId}")
            ->assertNotFound();
    }

    public function test_project_tester_can_submit_kotlin_bug(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $tester = User::factory()->create(['name' => 'Kotlin Tester', 'role' => 'tester']);
        $projectId = $this->createProject($manager, 'Kotlin Android Project');
        $this->addMember($projectId, $tester);
        $this->addDeveloper($projectId, 'Kotlin Dev', 'kotlin android', 0);

        Process::fake(['*' => json_encode([
            'is_duplicate' => false,
            'duplicate_of' => null,
            'similarity_score' => 0,
            'assigned_to' => 'Kotlin Dev',
            'priority' => 'High',
            'status' => 'Open',
            'code_help' => [
                'language' => 'Kotlin',
                'syntax_status' => 'passed',
                'syntax_checker' => 'kotlinc (Kotlin CLI compiler)',
                'error_name' => 'Kotlin syntax check passed',
                'steps' => ['Kotlin syntax valid.'],
                'suspected_files' => [
                    ['path' => 'app/src/main/java/com/example/app/MainActivity.kt', 'confidence' => 92, 'reason' => 'Matched Kotlin tokens: activity, main']
                ],
            ],
        ], JSON_THROW_ON_ERROR)]);

        $this->actingAs($tester)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/store', [
                'title' => 'MainActivity crashes on start in Kotlin app',
                'description' => 'Crash occurs when initializing viewmodel in MainActivity.kt',
                'issue_type' => 'Bug',
                'labels' => 'android, kotlin',
                'language' => 'Kotlin',
                'code_snippet' => "fun onCreate() { val x = 10 }",
            ])
            ->assertRedirect('/ai-agent')
            ->assertSessionHas('ai_result');

        $bug = DB::table('bugs')->where('project_id', $projectId)->where('title', 'MainActivity crashes on start in Kotlin app')->first();
        $this->assertNotNull($bug);
        $this->assertSame('Kotlin Dev', $bug->assigned_to);
    }

    public function test_developer_can_move_bug_status_via_ajax_kanban_override_and_manager_cannot(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $developer = User::factory()->create(['role' => 'developer']);
        $projectId = $this->createProject($manager, 'Kanban Project');
        $this->addMember($projectId, $manager);
        $this->addMember($projectId, $developer);

        $bugId = DB::table('bugs')->insertGetId([
            'project_id' => $projectId,
            'title' => 'Sample Drag Drop Bug',
            'description' => 'Testing AJAX Kanban override',
            'issue_type' => 'Bug',
            'status' => 'To Do',
            'priority' => 'Medium',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->postJson('/api/bugs/override', [
                'id' => $bugId,
                'status' => 'In Progress',
            ])->assertForbidden();

        $response = $this->actingAs($developer)->withSession(['project_id' => $projectId])
            ->postJson('/api/bugs/override', [
                'id' => $bugId,
                'status' => 'In Progress',
                'reason' => 'Dragged to In Progress',
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $updatedBug = DB::table('bugs')->where('id', $bugId)->first();
        $this->assertSame('In Progress', $updatedBug->status);
    }

    public function test_manager_can_sync_github_repository_tree(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'GitHub Sync Project');
        $this->addMember($projectId, $manager);

        Process::fake(['*' => json_encode([
            'success' => true,
            'repo' => 'owner/sample-repo',
            'branch' => 'main',
            'total_files' => 50,
            'primary_language' => 'Kotlin',
        ], JSON_THROW_ON_ERROR)]);

        $response = $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->post('/api/project/sync-github', [
                'github_repo' => 'https://github.com/owner/sample-repo',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $repo = DB::table('projects')->where('id', $projectId)->value('github_repo');
        $this->assertSame('https://github.com/owner/sample-repo', $repo);
    }

    public function test_bug_store_broadcasts_sanitized_agent_progress_and_persists_trace(): void
    {
        Event::fake([AgentProgressUpdated::class]);

        $manager = User::factory()->create(['role' => 'manager']);
        $tester = User::factory()->create(['name' => 'Trace Tester', 'role' => 'tester']);
        $projectId = $this->createProject($manager, 'Live Progress Project');
        $this->addMember($projectId, $tester);
        $this->addDeveloper($projectId, 'Dev One', 'php laravel', 0);

        $progressLines = implode("\n", [
            '::PROGRESS::'.json_encode([
                'step' => 'vision_ocr',
                'label' => 'Vision & OCR',
                'status' => 'skipped',
                'message' => 'No screenshot attached. gsk_test_secret_value_12345',
                'summary' => ['api_key' => 'SECRET_KEY_123'],
            ], JSON_THROW_ON_ERROR),
            '::PROGRESS::'.json_encode([
                'step' => 'duplicate_check',
                'label' => 'Duplicate Vector Check',
                'status' => 'completed',
                'message' => 'Unique issue (12.5% similarity)',
                'summary' => [
                    'similarity_score' => 12.5,
                    'is_duplicate' => false,
                    'code_snippet' => 'RAW_CODE_SHOULD_NOT_LEAK',
                ],
            ], JSON_THROW_ON_ERROR),
        ]);

        Process::fake([
            '*' => Process::result(
                output: json_encode([
                    'is_duplicate' => false,
                    'duplicate_of' => null,
                    'similarity_score' => 12.5,
                    'assigned_to' => 'Dev One',
                    'priority' => 'Medium',
                    'feature' => 'Authentication',
                    'status' => 'Open',
                    'code_help' => [
                        'language' => 'PHP',
                        'syntax_status' => 'passed',
                        'error_name' => 'No syntax errors',
                        'steps' => ['Inspect login handler.'],
                    ],
                ], JSON_THROW_ON_ERROR),
                errorOutput: $progressLines
            ),
        ]);

        $response = $this->actingAs($tester)->withSession(['project_id' => $projectId])
            ->postJson('/api/bugs/store', [
                'run_id' => 'run-test-123',
                'title' => 'Login button unresponsive',
                'description' => 'Clicking login does not submit the form.',
                'issue_type' => 'Bug',
            ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('run_id', 'run-test-123');

        Event::assertDispatched(AgentProgressUpdated::class, function (AgentProgressUpdated $event) use ($projectId) {
            return $event->projectId === $projectId
                && $event->runId === 'run-test-123'
                && $event->step === 'duplicate_check'
                && $event->status === 'completed'
                && ($event->summary['similarity_score'] ?? null) === 12.5
                && !array_key_exists('code_snippet', $event->summary);
        });

        Event::assertDispatched(AgentProgressUpdated::class, function (AgentProgressUpdated $event) {
            return $event->step === 'vision_ocr'
                && $event->status === 'skipped'
                && !array_key_exists('api_key', $event->summary)
                && !str_contains($event->message, 'gsk_test_secret_value_12345')
                && str_contains($event->message, '[redacted]');
        });

        $bugId = (int) $response->json('bug_id');
        $bug = DB::table('bugs')->where('id', $bugId)->first();
        $savedTriage = json_decode($bug->triage_result, true);
        $this->assertSame('run-test-123', $savedTriage['run_id']);
        $this->assertCount(2, $savedTriage['progress_trace']);

        $this->actingAs($tester)->withSession(['project_id' => $projectId])
            ->get("/bugs/{$bugId}")
            ->assertOk()
            ->assertSee('Agent Execution Trace')
            ->assertSee('Duplicate Vector Check')
            ->assertSee('Unique issue (12.5% similarity)');
    }

    private function createProject(User $manager, string $name): int
    {
        return (int) DB::table('projects')->insertGetId([
            'manager_id' => $manager->id,
            'name' => $name,
            'stack' => 'PHP',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function addMember(int $projectId, User $user): void
    {
        DB::table('project_members')->insert(['project_id' => $projectId, 'user_id' => $user->id]);
    }

    private function addDeveloper(int $projectId, string $name, string $skills, int $activeTasks): int
    {
        return (int) DB::table('developers')->insertGetId([
            'project_id' => $projectId,
            'name' => $name,
            'skills' => $skills,
            'active_tasks' => $activeTasks,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
