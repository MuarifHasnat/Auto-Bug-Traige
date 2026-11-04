<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder_can_be_run_repeatedly_without_duplicate_demo_records(): void
    {
        Artisan::call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);
        Artisan::call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);

        $projectId = (int) DB::table('projects')->where('name', 'Demo Bug Tracker')->value('id');

        $this->assertSame(4, DB::table('users')->count());
        $this->assertSame(1, DB::table('projects')->where('name', 'Demo Bug Tracker')->count());
        $this->assertSame(2, DB::table('bugs')->where('project_id', $projectId)->count());
        $this->assertSame(5, DB::table('bug_activities')->where('project_id', $projectId)->count());
        $this->assertDatabaseHas('users', ['email' => 'demo-manager@example.test', 'role' => 'manager']);
        $this->assertDatabaseHas('project_members', [
            'project_id' => $projectId,
            'user_id' => DB::table('users')->where('email', 'demo-tester@example.test')->value('id'),
        ]);
    }
}
