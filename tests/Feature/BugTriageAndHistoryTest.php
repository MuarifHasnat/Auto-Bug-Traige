<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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
            ->assertSessionHas('ai_result');

        $bug = DB::table('bugs')->where('project_id', $projectId)->where('title', 'Checkout fails')->first();
        $this->assertNotNull($bug);
        $this->assertSame('Dev One', $bug->assigned_to);
        $this->assertSame('High', $bug->priority);
        $this->assertSame('To Do', $bug->status);
        $this->assertSame('Bug', $bug->issue_type);
        $this->assertSame(['api', 'checkout'], json_decode($bug->labels, true));
        $this->assertSame(1, (int) DB::table('developers')->where('project_id', $projectId)->where('name', 'Dev One')->value('active_tasks'));
        $this->assertDatabaseHas('bug_activities', ['bug_id' => $bug->id, 'event_type' => 'created', 'actor_id' => $tester->id]);
        $this->assertDatabaseHas('bug_activities', ['bug_id' => $bug->id, 'event_type' => 'assignment_changed', 'to_value' => 'Dev One']);
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

        $this->actingAs($manager)->withSession(['project_id' => $projectId])
            ->post('/api/bugs/reassign', ['id' => $bugId, 'assigned_to' => 'Dev Two'])
            ->assertRedirect();

        $this->assertDatabaseHas('bugs', ['id' => $bugId, 'assigned_to' => 'Dev Two']);
        $this->assertDatabaseHas('developers', ['id' => $firstDeveloper, 'active_tasks' => 0]);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev Two', 'active_tasks' => 1]);
        $this->assertDatabaseHas('bug_activities', [
            'bug_id' => $bugId,
            'actor_id' => $manager->id,
            'event_type' => 'assignment_changed',
            'from_value' => 'Dev One',
            'to_value' => 'Dev Two',
        ]);

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'In Progress'])->assertRedirect();

        $this->assertDatabaseHas('bug_activities', [
            'bug_id' => $bugId,
            'actor_id' => $manager->id,
            'event_type' => 'status_changed',
            'from_value' => 'To Do',
            'to_value' => 'In Progress',
        ]);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev Two', 'active_tasks' => 1]);

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'Review'])->assertRedirect();

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'Done'])->assertRedirect();

        $this->assertDatabaseHas('bug_activities', [
            'bug_id' => $bugId,
            'actor_id' => $manager->id,
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

    public function test_manager_can_configure_workflow_and_unlisted_moves_are_rejected(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'Workflow project');
        $bugId = (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId, 'title' => 'Workflow ticket', 'description' => 'Move through configured states.',
            'priority' => 'Medium', 'status' => 'To Do', 'issue_type' => 'Task', 'assigned_to' => 'Unassigned',
            'is_duplicate' => false, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($manager)->withSession(['project_id' => $projectId])->post('/workflow', [
            'statuses' => "To Do\nBuild\nReview\nDone\nDuplicate",
            'done_statuses' => 'Done',
            'transitions' => "To Do -> Build\nBuild -> Review\nReview -> Done\nDone -> To Do",
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'Done'])->assertStatus(422);
        $this->assertDatabaseHas('bugs', ['id' => $bugId, 'status' => 'To Do']);

        $this->post('/api/bugs/override', ['id' => $bugId, 'status' => 'Build'])->assertRedirect();
        $this->assertDatabaseHas('bugs', ['id' => $bugId, 'status' => 'Build']);
        $this->assertDatabaseHas('project_workflow_transitions', [
            'project_id' => $projectId, 'from_status' => 'Review', 'to_status' => 'Done',
        ]);
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
