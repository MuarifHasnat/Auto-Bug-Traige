<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Tests\TestCase;

class EvaluationLabTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_managers_cannot_open_evaluation_lab(): void
    {
        $tester = User::factory()->create(['role' => 'tester']);
        $developer = User::factory()->create(['role' => 'developer']);

        $this->actingAs($tester)->get('/evaluation-lab')->assertForbidden();
        $this->actingAs($developer)->get('/evaluation-lab')->assertForbidden();
    }

    public function test_manager_can_view_saved_evaluation_report_without_running_live_ai(): void
    {
        Http::fake();
        Process::fake();

        $reportPath = storage_path('app/ai_evaluation_report.json');
        $hadExisting = is_file($reportPath);
        $previous = $hadExisting ? file_get_contents($reportPath) : null;

        if (!is_dir(dirname($reportPath))) {
            mkdir(dirname($reportPath), 0777, true);
        }

        file_put_contents($reportPath, json_encode([
            'generated_at' => '2026-11-26T20:00:00Z',
            'mode' => 'offline_deterministic',
            'dataset' => 'tests/fixtures/triage_cases.json',
            'total_cases' => 20,
            'metrics' => [
                'duplicate_precision' => 1.0,
                'duplicate_recall' => 1.0,
                'duplicate_f1' => 1.0,
                'priority_accuracy' => 0.95,
                'feature_accuracy' => 1.0,
                'file_top1_rate' => 1.0,
                'file_top3_rate' => 1.0,
                'guardrail_violations' => 0,
            ],
            'baseline' => [
                'duplicate_f1' => 0.0,
                'priority_accuracy' => 0.55,
                'feature_accuracy' => 0.1,
                'file_top3_rate' => 0.0,
            ],
            'cases' => [
                [
                    'id' => 'EVAL-01',
                    'title' => 'Login token rejected on valid session',
                    'expected_feature' => 'Authentication',
                    'predicted_feature' => 'Authentication',
                    'expected_priority' => 'Medium',
                    'predicted_priority' => 'Medium',
                    'expected_duplicate' => true,
                    'predicted_duplicate' => true,
                    'similarity_score' => 100.0,
                    'top3_file_hit' => true,
                    'top_file' => 'config/auth.php',
                    'assigned_to' => 'Unassigned',
                ],
            ],
        ], JSON_THROW_ON_ERROR));

        try {
            $manager = User::factory()->create(['role' => 'manager']);
            $this->actingAs($manager)
                ->get('/evaluation-lab')
                ->assertOk()
                ->assertSee('AI Evaluation Lab')
                ->assertSee('100.0%')
                ->assertSee('95.0%')
                ->assertSee('EVAL-01');

            Http::assertNothingSent();
            Process::assertNothingRan();
        } finally {
            if ($hadExisting && $previous !== false) {
                file_put_contents($reportPath, $previous);
            } elseif (!$hadExisting && is_file($reportPath)) {
                @unlink($reportPath);
            }
        }
    }
}

