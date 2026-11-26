<?php

namespace Tests\Unit;

use App\Services\BugAssistant;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class BugAssistantTest extends TestCase
{
    public function test_generate_follow_up_questions_returns_grounded_citations(): void
    {
        Config::set('services.groq.key', '');

        $assistant = new BugAssistant();
        $bug = (object) [
            'id' => 77,
            'title' => 'Checkout 500 error',
            'description' => 'Fails when clicking pay',
            'status' => 'To Do',
            'priority' => 'High',
            'issue_type' => 'Bug',
            'assigned_to' => 'Unassigned',
            'detected_feature' => 'Payments',
            'is_duplicate' => false,
            'duplicate_of' => null,
        ];
        $triage = [
            'needs_review' => true,
            'code_help' => [
                'syntax_status' => 'not_run',
                'suspected_files' => [],
            ],
        ];

        $result = $assistant->generateFollowUpQuestions($bug, $triage);

        $this->assertFalse($result['used_llm']);
        $this->assertNotEmpty($result['questions']);
        $this->assertNotEmpty($result['citations']);
        $this->assertContains('syntax_status: not_run', $result['citations']);
        $this->assertContains('screenshot_summary: not recorded', $result['citations']);
    }

    public function test_generate_test_plan_returns_checklist_and_evidence_citations(): void
    {
        Config::set('services.groq.key', '');

        $assistant = new BugAssistant();
        $bug = (object) [
            'id' => 88,
            'title' => 'Profile photo fails to load',
            'description' => 'Avatar returns 404 after upload.',
            'status' => 'To Do',
            'priority' => 'Medium',
            'issue_type' => 'Bug',
            'assigned_to' => 'Dev One',
            'detected_feature' => 'Authentication',
            'is_duplicate' => false,
            'duplicate_of' => null,
        ];
        $triage = [
            'code_help' => [
                'syntax_status' => 'not_run',
                'suspected_files' => [
                    ['path' => 'app/Models/User.php', 'confidence' => 85, 'reason' => 'Profile avatar path'],
                ],
                'fix_suggestions' => [
                    ['title' => 'Check storage symlink', 'detail' => 'Verify public/storage link points to storage/app/public.'],
                ],
            ],
        ];

        $result = $assistant->generateTestPlan($bug, $triage);

        $this->assertFalse($result['used_llm']);
        $this->assertStringContainsString('#ABT-88', $result['title']);
        $this->assertGreaterThanOrEqual(3, count($result['checklist']));
        $this->assertContains('suspected_files[0]: app/Models/User.php', $result['citations']);
    }
}

