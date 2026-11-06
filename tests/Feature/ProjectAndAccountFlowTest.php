<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProjectAndAccountFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_create_project_and_project_scoped_team_accounts(): void
    {
        $manager = User::factory()->create(['name' => 'Project Manager', 'role' => 'manager']);

        $this->actingAs($manager)->post('/create-project', [
            'project_name' => 'Payments',
            'project_stack' => 'PHP, Laravel',
        ])->assertRedirect('/add-developers');

        $projectId = (int) session('project_id');
        $this->assertDatabaseHas('projects', ['id' => $projectId, 'manager_id' => $manager->id, 'name' => 'Payments']);

        $this->post('/team-members', [
            'name' => 'Taylor Tester',
            'email' => 'taylor@example.test',
            'role' => 'tester',
            'password' => 'password-123',
            'password_confirmation' => 'password-123',
        ])->assertSessionHasNoErrors();

        $this->post('/team-members', [
            'name' => 'Dev One',
            'email' => 'dev.one@example.test',
            'role' => 'developer',
            'password' => 'password-123',
            'password_confirmation' => 'password-123',
            'skills' => 'php laravel api',
        ])->assertSessionHasNoErrors();

        $testerId = (int) DB::table('users')->where('email', 'taylor@example.test')->value('id');
        $developerId = (int) DB::table('users')->where('email', 'dev.one@example.test')->value('id');

        $this->assertDatabaseHas('users', ['id' => $testerId, 'role' => 'tester']);
        $this->assertDatabaseHas('project_members', ['project_id' => $projectId, 'user_id' => $testerId]);
        $this->assertDatabaseHas('project_members', ['project_id' => $projectId, 'user_id' => $developerId]);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev One', 'skills' => 'php laravel api']);
        $this->assertDatabaseHas('developers', ['project_id' => $projectId, 'name' => 'Dev One', 'capacity' => 5]);

        $tester = User::findOrFail($testerId);
        $developer = User::findOrFail($developerId);
        $this->post('/logout')->assertRedirect('/login');
        $this->post('/login', ['email' => $tester->email, 'password' => 'password-123'])
            ->assertRedirect('/ai-agent');
        $this->assertAuthenticatedAs($tester);
        $this->post('/logout')->assertRedirect('/login');
        $this->post('/login', ['email' => $developer->email, 'password' => 'password-123'])
            ->assertRedirect('/workspace');
        $this->assertAuthenticatedAs($developer);
    }

    public function test_member_cannot_switch_to_a_project_they_do_not_belong_to(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $otherManager = User::factory()->create(['role' => 'manager']);
        $tester = User::factory()->create(['role' => 'tester']);
        $memberProject = $this->createProject($manager, 'Member project');
        $privateProject = $this->createProject($otherManager, 'Private project');
        DB::table('project_members')->insert(['project_id' => $memberProject, 'user_id' => $tester->id]);

        $this->actingAs($tester)->withSession(['project_id' => $memberProject])
            ->post('/project/switch', ['project_id' => $privateProject])
            ->assertForbidden();

        $this->get('/ai-agent')->assertOk();
        $this->assertSame($memberProject, (int) session('project_id'));
    }

    public function test_non_managers_cannot_open_manager_pages_or_create_team_accounts(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'Team project');
        $tester = User::factory()->create(['role' => 'tester']);
        DB::table('project_members')->insert(['project_id' => $projectId, 'user_id' => $tester->id]);

        $this->actingAs($tester)->withSession(['project_id' => $projectId])->get('/dashboard')->assertForbidden();
        $this->post('/team-members', [])->assertForbidden();
        $this->post('/api/bugs/reassign', [])->assertForbidden();
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
}
