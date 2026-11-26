<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BugAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_project_member_cannot_chat_with_bug_assistant(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $outsider = User::factory()->create(['role' => 'developer']);
        $projectId = $this->createProject($manager, 'Private Project');
        $otherProjectId = $this->createProject($manager, 'Other Project');
        DB::table('project_members')->insert(['project_id' => $otherProjectId, 'user_id' => $outsider->id]);

        $bugId = $this->createTriagedBug($projectId);

        $this->postJson("/api/bugs/{$bugId}/assistant-chat", ['message' => 'Why is this high priority?'])
            ->assertUnauthorized();

        $this->actingAs($outsider)
            ->withSession(['project_id' => $otherProjectId])
            ->postJson("/api/bugs/{$bugId}/assistant-chat", ['message' => 'Why is this high priority?'])
            ->assertForbidden();

        $this->assertDatabaseCount('bug_assistant_messages', 0);
    }

    public function test_assistant_answers_with_grounded_context_and_persists_chat_history(): void
    {
        Config::set('services.groq.key', 'test-groq-key');
        Config::set('services.groq.assistant_model', 'openai/gpt-oss-120b');

        Http::fake([
            'https://api.groq.com/openai/v1/chat/completions' => Http::sequence()
                ->push([
                    'choices' => [[
                        'message' => ['content' => 'Priority is High due to the crash keyword, and CheckoutController.php was flagged.'],
                    ]],
                ], 200)
                ->push([
                    'choices' => [[
                        'message' => ['content' => 'Based on the saved trace, duplicate similarity was 18.2%.'],
                    ]],
                ], 200),
        ]);

        $manager = User::factory()->create(['role' => 'manager']);
        $developer = User::factory()->create(['name' => 'Dev One', 'role' => 'developer']);
        $projectId = $this->createProject($manager, 'Assistant Project');
        DB::table('project_members')->insert([
            ['project_id' => $projectId, 'user_id' => $manager->id],
            ['project_id' => $projectId, 'user_id' => $developer->id],
        ]);

        $bugId = $this->createTriagedBug($projectId);

        $first = $this->actingAs($developer)
            ->withSession(['project_id' => $projectId])
            ->postJson("/api/bugs/{$bugId}/assistant-chat", [
                'message' => 'Why was CheckoutController.php flagged?',
            ]);

        $first->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('used_llm', true)
            ->assertJsonPath('message.content', 'Priority is High due to the crash keyword, and CheckoutController.php was flagged.');

        // Follow-up turn verifies history is passed to the LLM
        $second = $this->actingAs($developer)
            ->withSession(['project_id' => $projectId])
            ->postJson("/api/bugs/{$bugId}/assistant-chat", [
                'message' => 'Is it a duplicate?',
            ]);

        $second->assertOk()
            ->assertJsonPath('used_llm', true);

        Http::assertSentCount(2);
        $recorded = Http::recorded();
        $firstSystemPrompt = $recorded[0][0]['messages'][0]['content'];
        $this->assertStringContainsString('NEVER invent file paths', $firstSystemPrompt);
        $this->assertStringContainsString('CheckoutController.php', $firstSystemPrompt);

        $secondMessages = $recorded[1][0]['messages'];
        $this->assertGreaterThanOrEqual(4, count($secondMessages));
        $this->assertSame('Why was CheckoutController.php flagged?', $secondMessages[1]['content']);

        // Saved separately from bug_comments
        $this->assertDatabaseCount('bug_assistant_messages', 4);
        $this->assertDatabaseCount('bug_comments', 0);

        $this->actingAs($developer)
            ->withSession(['project_id' => $projectId])
            ->get("/bugs/{$bugId}")
            ->assertOk()
            ->assertSee('Ask the Agent')
            ->assertSee('Why was CheckoutController.php flagged?')
            ->assertSee('Priority is High due to the crash keyword, and CheckoutController.php was flagged.');
    }

    public function test_assistant_falls_back_to_saved_evidence_when_api_fails(): void
    {
        Config::set('services.groq.key', 'test-groq-key');
        Http::fake([
            'https://api.groq.com/openai/v1/chat/completions' => Http::response(['error' => 'upstream down'], 500),
        ]);

        $manager = User::factory()->create(['role' => 'manager']);
        $projectId = $this->createProject($manager, 'Fallback Project');
        $bugId = $this->createTriagedBug($projectId);

        $response = $this->actingAs($manager)
            ->withSession(['project_id' => $projectId])
            ->postJson("/api/bugs/{$bugId}/assistant-chat", [
                'message' => 'Which file should I inspect?',
            ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('used_llm', false);

        $this->assertNotNull($response->json('fallback_reason'));
        $this->assertStringContainsString('CheckoutController.php', (string) $response->json('message.content'));
        $this->assertDatabaseCount('bug_assistant_messages', 2);
    }

    public function test_follow_up_and_test_plan_endpoints_enforce_access_and_clarification_flow(): void
    {
        Config::set('services.groq.key', '');

        $manager = User::factory()->create(['role' => 'manager']);
        $developer = User::factory()->create(['name' => 'Dev One', 'role' => 'developer']);
        $outsider = User::factory()->create(['role' => 'developer']);
        $projectId = $this->createProject($manager, 'Follow-up Project');
        $otherProjectId = $this->createProject($manager, 'Other Project');

        DB::table('project_members')->insert([
            ['project_id' => $projectId, 'user_id' => $manager->id],
            ['project_id' => $projectId, 'user_id' => $developer->id],
            ['project_id' => $otherProjectId, 'user_id' => $outsider->id],
        ]);

        $bugId = $this->createTriagedBug($projectId);

        // Outsider cannot call follow-up or test-plan
        $this->actingAs($outsider)
            ->withSession(['project_id' => $otherProjectId])
            ->postJson("/api/bugs/{$bugId}/assistant-follow-up")
            ->assertForbidden();
        $this->actingAs($outsider)
            ->withSession(['project_id' => $otherProjectId])
            ->postJson("/api/bugs/{$bugId}/assistant-test-plan")
            ->assertForbidden();

        // Assigned developer can generate follow-up questions and test plan
        $followUp = $this->actingAs($developer)
            ->withSession(['project_id' => $projectId])
            ->postJson("/api/bugs/{$bugId}/assistant-follow-up");

        $followUp->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('used_llm', false);
        $questions = $followUp->json('questions');
        $this->assertNotEmpty($questions);
        $this->assertNotEmpty($followUp->json('citations'));

        $testPlan = $this->actingAs($developer)
            ->withSession(['project_id' => $projectId])
            ->postJson("/api/bugs/{$bugId}/assistant-test-plan");

        $testPlan->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('used_llm', false);
        $this->assertNotEmpty($testPlan->json('checklist'));
        $this->assertNotEmpty($testPlan->json('citations'));

        // Assigned developer can send a generated follow-up question via existing clarification endpoint
        $this->actingAs($developer)
            ->withSession(['project_id' => $projectId])
            ->post('/api/bugs/clarifications', [
                'bug_id' => $bugId,
                'question' => $questions[0]['question'],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('bug_clarification_requests', [
            'project_id' => $projectId,
            'bug_id' => $bugId,
            'question' => $questions[0]['question'],
            'status' => 'open',
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

    private function createTriagedBug(int $projectId): int
    {
        $triage = [
            'feature' => 'Payments',
            'priority' => 'High',
            'is_duplicate' => false,
            'similarity_score' => 18.2,
            'assigned_to' => 'Dev One',
            'priority_evidence' => ['reason' => 'Detected severity signal(s): crash'],
            'feature_evidence' => ['reason' => 'Matched Payments keyword(s): checkout'],
            'suspected_files' => [
                ['path' => 'app/Http/Controllers/CheckoutController.php', 'confidence' => 88, 'reason' => 'Matched checkout keyword'],
            ],
            'code_help' => [
                'syntax_status' => 'not_run',
                'assignment_reason' => 'Dev One was selected for Payments work.',
                'suspected_files' => [
                    ['path' => 'app/Http/Controllers/CheckoutController.php', 'confidence' => 88, 'reason' => 'Matched checkout keyword'],
                ],
                'fix_suggestions' => [
                    ['title' => 'Check null guard', 'detail' => 'Verify checkout payload before charging.'],
                ],
            ],
            'progress_trace' => [
                ['step' => 'duplicate_check', 'label' => 'Duplicate Vector Check', 'status' => 'completed', 'message' => 'Unique issue (18.2%)'],
            ],
        ];

        return (int) DB::table('bugs')->insertGetId([
            'project_id' => $projectId,
            'title' => 'Checkout crashes on submit',
            'description' => 'Clicking pay throws 500.',
            'priority' => 'High',
            'status' => 'To Do',
            'issue_type' => 'Bug',
            'assigned_to' => 'Dev One',
            'detected_feature' => 'Payments',
            'is_duplicate' => false,
            'triage_result' => json_encode($triage, JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

