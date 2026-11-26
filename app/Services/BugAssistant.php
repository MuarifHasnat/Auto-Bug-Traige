<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BugAssistant
{
    /**
     * Generate a grounded response to a user's question about a triaged bug.
     *
     * @param array<string, mixed> $triage
     * @param array<int, array{role: string, content: string}> $history
     * @return array{reply: string, used_llm: bool, fallback_reason: ?string}
     */
    public function reply(object $bug, array $triage, array $history, string $question): array
    {
        $context = $this->buildContext($bug, $triage);
        $apiKey = trim((string) config('services.groq.key', ''));

        if ($apiKey === '' || $apiKey === 'your_free_groq_api_key_here') {
            return [
                'reply' => $this->buildGroundedFallback($bug, $triage, $question),
                'used_llm' => false,
                'fallback_reason' => 'GROQ_API_KEY is not configured; answered from saved triage evidence.',
            ];
        }

        $model = (string) config('services.groq.assistant_model', config('services.groq.model', 'openai/gpt-oss-120b'));
        $timeout = max(5, (int) config('services.groq.assistant_timeout', 25));

        $systemPrompt = implode("\n", [
            "You are the AutoBugTriage Assistant answering questions about bug #ABT-{$bug->id}.",
            'RULES:',
            '1. Answer ONLY from the provided SAVED_BUG_CONTEXT JSON (report, triage evidence, suspected files, and execution trace).',
            '2. NEVER invent file paths, line numbers, stack traces, root causes, or confidence percentages that are not present in SAVED_BUG_CONTEXT.',
            '3. If a detail is missing from SAVED_BUG_CONTEXT, explicitly state that it was not recorded in the saved triage evidence and suggest what the engineer can inspect next.',
            '4. Keep answers concise, accurate, and easy to act on.',
            '',
            'SAVED_BUG_CONTEXT:',
            json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
        ]);

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach (array_slice($history, -8) as $entry) {
            $role = ($entry['role'] ?? '') === 'assistant' ? 'assistant' : 'user';
            $content = trim((string) ($entry['content'] ?? ''));
            if ($content !== '') {
                $messages[] = ['role' => $role, 'content' => mb_substr($content, 0, 1500)];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $question];

        try {
            $response = Http::withToken($apiKey)
                ->timeout($timeout)
                ->acceptJson()
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.1,
                    'max_tokens' => 600,
                ]);

            if ($response->successful()) {
                $text = trim((string) data_get($response->json(), 'choices.0.message.content', ''));
                if ($text !== '') {
                    return [
                        'reply' => $text,
                        'used_llm' => true,
                        'fallback_reason' => null,
                    ];
                }
            }

            return [
                'reply' => $this->buildGroundedFallback($bug, $triage, $question),
                'used_llm' => false,
                'fallback_reason' => 'Assistant LLM returned HTTP '.$response->status().'; answered from saved triage evidence.',
            ];
        } catch (\Throwable $e) {
            return [
                'reply' => $this->buildGroundedFallback($bug, $triage, $question),
                'used_llm' => false,
                'fallback_reason' => 'Assistant LLM request failed; answered from saved triage evidence.',
            ];
        }
    }

    /**
     * Build a safe, secret-free context payload from saved bug and triage records.
     *
     * @param array<string, mixed> $triage
     * @return array<string, mixed>
     */
    public function buildContext(object $bug, array $triage): array
    {
        $codeHelp = is_array($triage['code_help'] ?? null) ? $triage['code_help'] : [];
        $rawFiles = $codeHelp['suspected_files'] ?? ($triage['suspected_files'] ?? []);
        $suspectedFiles = [];
        if (is_array($rawFiles)) {
            foreach (array_slice($rawFiles, 0, 5) as $file) {
                if (is_array($file)) {
                    $suspectedFiles[] = [
                        'path' => (string) ($file['path'] ?? ''),
                        'branch' => $file['branch'] ?? null,
                        'confidence' => $file['confidence'] ?? null,
                        'reason' => $file['reason'] ?? null,
                    ];
                } elseif (is_string($file)) {
                    $suspectedFiles[] = ['path' => $file];
                }
            }
        }

        $trace = [];
        foreach (($triage['progress_trace'] ?? []) as $step) {
            if (!is_array($step)) {
                continue;
            }
            $trace[] = [
                'step' => $step['step'] ?? '',
                'label' => $step['label'] ?? '',
                'status' => $step['status'] ?? '',
                'message' => $step['message'] ?? '',
            ];
        }

        $vision = is_array($triage['screenshot_vision'] ?? null)
            ? $triage['screenshot_vision']
            : (is_array($codeHelp['screenshot_vision'] ?? null) ? $codeHelp['screenshot_vision'] : null);

        return [
            'bug_id' => $bug->id,
            'title' => (string) ($bug->title ?? ''),
            'description' => mb_substr((string) ($bug->description ?? ''), 0, 1500),
            'status' => (string) ($bug->status ?? ''),
            'priority' => (string) ($bug->priority ?? ''),
            'issue_type' => (string) ($bug->issue_type ?? 'Bug'),
            'assigned_to' => (string) ($bug->assigned_to ?? 'Unassigned'),
            'detected_feature' => (string) ($bug->detected_feature ?? ($triage['feature'] ?? 'General')),
            'is_duplicate' => (bool) ($bug->is_duplicate ?? false),
            'duplicate_of' => $bug->duplicate_of ?? null,
            'similarity_score' => $triage['similarity_score'] ?? null,
            'top_duplicate_matches' => array_slice((array) ($triage['top_duplicate_matches'] ?? ($codeHelp['top_duplicate_matches'] ?? [])), 0, 3),
            'feature_evidence' => $triage['feature_evidence'] ?? ($codeHelp['feature_evidence'] ?? null),
            'priority_evidence' => $triage['priority_evidence'] ?? ($codeHelp['priority_evidence'] ?? null),
            'assignment_reason' => $codeHelp['assignment_reason'] ?? ($triage['assignment_reason'] ?? null),
            'assignment_breakdown' => $triage['assignment_breakdown'] ?? ($codeHelp['assignment_breakdown'] ?? null),
            'needs_review' => !empty($triage['needs_review']) || ($bug->assigned_to === 'Unassigned'),
            'suspected_files' => $suspectedFiles,
            'syntax_status' => $codeHelp['syntax_status'] ?? 'not_run',
            'error_name' => $codeHelp['error_name'] ?? null,
            'fix_suggestions' => array_slice((array) ($codeHelp['fix_suggestions'] ?? []), 0, 3),
            'screenshot_summary' => $vision ? [
                'method' => $vision['method'] ?? null,
                'page_type' => $vision['page_type'] ?? null,
                'feature' => $vision['feature'] ?? null,
                'error_name' => $vision['error_name'] ?? null,
                'visual_summary' => $vision['visual_summary'] ?? null,
                'extracted_text' => mb_substr((string) ($vision['extracted_text'] ?? ''), 0, 600),
            ] : null,
            'execution_trace' => $trace,
        ];
    }

    /**
     * Deterministic answer using only saved triage facts when the LLM is unavailable.
     *
     * @param array<string, mixed> $triage
     */
    private function buildGroundedFallback(object $bug, array $triage, string $question): string
    {
        $ctx = $this->buildContext($bug, $triage);
        $q = mb_strtolower($question);
        $parts = [];

        if (str_contains($q, 'assign') || str_contains($q, 'developer') || str_contains($q, 'owner') || str_contains($q, 'who')) {
            $parts[] = "Assigned to: {$ctx['assigned_to']}."
                .(!empty($ctx['assignment_reason']) ? " Reason: {$ctx['assignment_reason']}" : '');
        }
        if (str_contains($q, 'priority') || str_contains($q, 'severity') || str_contains($q, 'high') || str_contains($q, 'urgent')) {
            $reason = $ctx['priority_evidence']['reason'] ?? 'No extra priority note recorded.';
            $parts[] = "Priority is {$ctx['priority']}. Evidence: {$reason}";
        }
        if (str_contains($q, 'duplicate') || str_contains($q, 'similar')) {
            $sim = $ctx['similarity_score'] !== null ? "{$ctx['similarity_score']}%" : 'unmeasured';
            $parts[] = $ctx['is_duplicate']
                ? "Flagged as duplicate of #ABT-{$ctx['duplicate_of']} ({$sim} similarity)."
                : "Not flagged as a duplicate (max similarity: {$sim}).";
        }
        if (str_contains($q, 'file') || str_contains($q, 'code') || str_contains($q, 'where') || str_contains($q, 'fix')) {
            if (!empty($ctx['suspected_files'])) {
                $paths = implode(', ', array_map(fn ($f) => $f['path'], $ctx['suspected_files']));
                $parts[] = "Suspected file(s): {$paths}.";
            } else {
                $parts[] = 'No suspected repository files were recorded for this report.';
            }
            if (!empty($ctx['fix_suggestions'])) {
                $firstFix = $ctx['fix_suggestions'][0];
                $parts[] = 'Top recorded guidance: '.($firstFix['title'] ?? 'Review').' — '.($firstFix['detail'] ?? '');
            }
        }

        if (empty($parts)) {
            $fileHint = !empty($ctx['suspected_files']) ? $ctx['suspected_files'][0]['path'] : 'none recorded';
            $parts[] = "#ABT-{$ctx['bug_id']} is categorized under {$ctx['detected_feature']} ({$ctx['priority']} priority, status: {$ctx['status']}, assigned to: {$ctx['assigned_to']}).";
            $parts[] = "Top suspected file: {$fileHint}.";
            if (!empty($ctx['feature_evidence']['reason'])) {
                $parts[] = "Module evidence: {$ctx['feature_evidence']['reason']}";
            }
        }

        return implode(' ', $parts);
    }

    /**
     * Generate evidence-grounded follow-up / clarification questions for a bug report.
     *
     * @param array<string, mixed> $triage
     * @return array{questions: array<int, array{question: string, reason: string, citation: string}>, citations: array<int, string>, summary_text: string, used_llm: bool, fallback_reason: ?string}
     */
    public function generateFollowUpQuestions(object $bug, array $triage): array
    {
        $ctx = $this->buildContext($bug, $triage);
        $fallback = $this->buildFollowUpFallback($ctx);
        $apiKey = trim((string) config('services.groq.key', ''));

        if ($apiKey === '' || $apiKey === 'your_free_groq_api_key_here') {
            return $fallback + [
                'used_llm' => false,
                'fallback_reason' => 'GROQ_API_KEY is not configured; generated follow-up questions from saved triage gaps.',
            ];
        }

        $model = (string) config('services.groq.assistant_model', config('services.groq.model', 'openai/gpt-oss-120b'));
        $timeout = max(5, (int) config('services.groq.assistant_timeout', 25));

        $systemPrompt = implode("\n", [
            "You are the AutoBugTriage Assistant generating follow-up clarification questions for bug #ABT-{$bug->id}.",
            'Output ONLY valid JSON: {"questions":[{"question":"...","reason":"...","citation":"..."}],"citations":["..."]}',
            'RULES:',
            '1. Base every question ONLY on gaps or evidence in SAVED_BUG_CONTEXT.',
            '2. Every item MUST include a "citation" referencing a real field/value in SAVED_BUG_CONTEXT (or stating that it was not recorded).',
            '3. Generate 2 to 4 concise, high-value clarification questions.',
            '',
            'SAVED_BUG_CONTEXT:',
            json_encode($ctx, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
        ]);

        try {
            $response = Http::withToken($apiKey)
                ->timeout($timeout)
                ->acceptJson()
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => 'Generate follow-up clarification questions with evidence citations.'],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.1,
                    'max_tokens' => 600,
                ]);

            if ($response->successful()) {
                $raw = trim((string) data_get($response->json(), 'choices.0.message.content', ''));
                $decoded = json_decode($raw, true);
                if (is_array($decoded) && !empty($decoded['questions']) && is_array($decoded['questions'])) {
                    $questions = [];
                    foreach (array_slice($decoded['questions'], 0, 4) as $q) {
                        if (!is_array($q) || empty($q['question'])) {
                            continue;
                        }
                        $questions[] = [
                            'question' => (string) $q['question'],
                            'reason' => (string) ($q['reason'] ?? 'Clarifies missing triage context.'),
                            'citation' => (string) ($q['citation'] ?? 'SAVED_BUG_CONTEXT'),
                        ];
                    }
                    if (!empty($questions)) {
                        $citations = array_values(array_filter(array_map('strval', (array) ($decoded['citations'] ?? []))));
                        if (empty($citations)) {
                            $citations = array_values(array_unique(array_column($questions, 'citation')));
                        }
                        return [
                            'questions' => $questions,
                            'citations' => $citations,
                            'summary_text' => $this->formatFollowUpSummary($questions),
                            'used_llm' => true,
                            'fallback_reason' => null,
                        ];
                    }
                }
            }

            return $fallback + [
                'used_llm' => false,
                'fallback_reason' => 'Assistant LLM returned HTTP '.$response->status().'; generated follow-up questions from saved triage gaps.',
            ];
        } catch (\Throwable) {
            return $fallback + [
                'used_llm' => false,
                'fallback_reason' => 'Assistant LLM request failed; generated follow-up questions from saved triage gaps.',
            ];
        }
    }

    /**
     * Generate an evidence-grounded reproduction and verification test plan.
     *
     * @param array<string, mixed> $triage
     * @return array{title: string, checklist: array<int, array{step: string, expected: string, citation: string}>, citations: array<int, string>, summary_text: string, used_llm: bool, fallback_reason: ?string}
     */
    public function generateTestPlan(object $bug, array $triage): array
    {
        $ctx = $this->buildContext($bug, $triage);
        $fallback = $this->buildTestPlanFallback($ctx);
        $apiKey = trim((string) config('services.groq.key', ''));

        if ($apiKey === '' || $apiKey === 'your_free_groq_api_key_here') {
            return $fallback + [
                'used_llm' => false,
                'fallback_reason' => 'GROQ_API_KEY is not configured; generated test plan from saved triage evidence.',
            ];
        }

        $model = (string) config('services.groq.assistant_model', config('services.groq.model', 'openai/gpt-oss-120b'));
        $timeout = max(5, (int) config('services.groq.assistant_timeout', 25));

        $systemPrompt = implode("\n", [
            "You are the AutoBugTriage Assistant generating a reproduction and verification test checklist for bug #ABT-{$bug->id}.",
            'Output ONLY valid JSON: {"title":"...","checklist":[{"step":"...","expected":"...","citation":"..."}],"citations":["..."]}',
            'RULES:',
            '1. Base every checklist item ONLY on SAVED_BUG_CONTEXT (description, suspected_files, fix_suggestions, screenshot_summary, priority).',
            '2. NEVER invent unrecorded endpoints, database columns, or test fixtures.',
            '3. Every checklist item MUST include a "citation" pointing to the saved evidence.',
            '',
            'SAVED_BUG_CONTEXT:',
            json_encode($ctx, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
        ]);

        try {
            $response = Http::withToken($apiKey)
                ->timeout($timeout)
                ->acceptJson()
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => 'Generate a concise verification test plan with evidence citations.'],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.1,
                    'max_tokens' => 700,
                ]);

            if ($response->successful()) {
                $raw = trim((string) data_get($response->json(), 'choices.0.message.content', ''));
                $decoded = json_decode($raw, true);
                if (is_array($decoded) && !empty($decoded['checklist']) && is_array($decoded['checklist'])) {
                    $checklist = [];
                    foreach (array_slice($decoded['checklist'], 0, 6) as $item) {
                        if (!is_array($item) || empty($item['step'])) {
                            continue;
                        }
                        $checklist[] = [
                            'step' => (string) $item['step'],
                            'expected' => (string) ($item['expected'] ?? 'Verify expected behavior without error.'),
                            'citation' => (string) ($item['citation'] ?? 'SAVED_BUG_CONTEXT'),
                        ];
                    }
                    if (!empty($checklist)) {
                        $title = (string) ($decoded['title'] ?? "Verification Test Plan for #ABT-{$bug->id}");
                        $citations = array_values(array_filter(array_map('strval', (array) ($decoded['citations'] ?? []))));
                        if (empty($citations)) {
                            $citations = array_values(array_unique(array_column($checklist, 'citation')));
                        }
                        return [
                            'title' => $title,
                            'checklist' => $checklist,
                            'citations' => $citations,
                            'summary_text' => $this->formatTestPlanSummary($title, $checklist),
                            'used_llm' => true,
                            'fallback_reason' => null,
                        ];
                    }
                }
            }

            return $fallback + [
                'used_llm' => false,
                'fallback_reason' => 'Assistant LLM returned HTTP '.$response->status().'; generated test plan from saved triage evidence.',
            ];
        } catch (\Throwable) {
            return $fallback + [
                'used_llm' => false,
                'fallback_reason' => 'Assistant LLM request failed; generated test plan from saved triage evidence.',
            ];
        }
    }

    /**
     * @param array<string, mixed> $ctx
     * @return array{questions: array<int, array{question: string, reason: string, citation: string}>, citations: array<int, string>, summary_text: string}
     */
    private function buildFollowUpFallback(array $ctx): array
    {
        $questions = [];

        if (($ctx['syntax_status'] ?? 'not_run') === 'not_run') {
            $questions[] = [
                'question' => 'Can you share the exact console error message, HTTP status code, or stack trace observed?',
                'reason' => 'Static syntax/AST diagnostics did not run because no code snippet or stack trace was attached.',
                'citation' => 'syntax_status: not_run',
            ];
        }

        if (empty($ctx['screenshot_summary'])) {
            $questions[] = [
                'question' => 'Can you attach a screenshot or screen recording showing the UI state when the issue occurs?',
                'reason' => 'No screenshot evidence was recorded during triage.',
                'citation' => 'screenshot_summary: not recorded',
            ];
        }

        if (!empty($ctx['needs_review'])) {
            $questions[] = [
                'question' => "Which exact page or module ({$ctx['detected_feature']}) and user role triggered this defect?",
                'reason' => 'Triage flagged this report as needing review due to limited keyword/skill match.',
                'citation' => 'needs_review: true; detected_feature: '.$ctx['detected_feature'],
            ];
        }

        if (empty($ctx['suspected_files'])) {
            $questions[] = [
                'question' => 'Which route URL or component name were you interacting with right before the failure?',
                'reason' => 'No suspected repository files were matched from the report text.',
                'citation' => 'suspected_files: none recorded',
            ];
        } else {
            $topPath = $ctx['suspected_files'][0]['path'];
            $questions[] = [
                'question' => "Does the issue reproduce consistently when exercising the flow handled by {$topPath}?",
                'reason' => 'Helps confirm whether the top localized candidate file matches the runtime path.',
                'citation' => 'suspected_files[0]: '.$topPath,
            ];
        }

        $questions = array_slice($questions, 0, 4);
        $citations = array_values(array_unique(array_column($questions, 'citation')));

        return [
            'questions' => $questions,
            'citations' => $citations,
            'summary_text' => $this->formatFollowUpSummary($questions),
        ];
    }

    /**
     * @param array<string, mixed> $ctx
     * @return array{title: string, checklist: array<int, array{step: string, expected: string, citation: string}>, citations: array<int, string>, summary_text: string}
     */
    private function buildTestPlanFallback(array $ctx): array
    {
        $checklist = [
            [
                'step' => "Reproduce the reported symptom for \"{$ctx['title']}\" using the saved report description.",
                'expected' => 'Confirm the failure state is reproducible before applying any code change.',
                'citation' => 'report: #ABT-'.$ctx['bug_id'].' description',
            ],
        ];

        if (!empty($ctx['suspected_files'])) {
            $topPath = $ctx['suspected_files'][0]['path'];
            $checklist[] = [
                'step' => "Inspect {$topPath} and verify inputs, error handling, and response payload.",
                'expected' => "No unhandled exception or invalid state in {$topPath}.",
                'citation' => 'suspected_files[0]: '.$topPath,
            ];
        } else {
            $checklist[] = [
                'step' => "Locate the {$ctx['detected_feature']} handler in the codebase (no suspected file was recorded).",
                'expected' => 'Identify the responsible route/component and verify error logs.',
                'citation' => 'suspected_files: none recorded; detected_feature: '.$ctx['detected_feature'],
            ];
        }

        if (!empty($ctx['fix_suggestions'])) {
            $fix = $ctx['fix_suggestions'][0];
            $checklist[] = [
                'step' => 'Verify fix guidance: '.($fix['title'] ?? 'Apply recommended fix'),
                'expected' => (string) ($fix['detail'] ?? 'Issue no longer reproduces after fix.'),
                'citation' => 'fix_suggestions[0]',
            ];
        }

        $checklist[] = [
            'step' => "Re-test {$ctx['detected_feature']} end-to-end and verify no regression on {$ctx['priority']} priority path.",
            'expected' => 'Expected workflow completes cleanly with zero console or server errors.',
            'citation' => "detected_feature: {$ctx['detected_feature']}; priority: {$ctx['priority']}",
        ];

        $title = "Verification Test Plan for #ABT-{$ctx['bug_id']}";
        $citations = array_values(array_unique(array_column($checklist, 'citation')));

        return [
            'title' => $title,
            'checklist' => $checklist,
            'citations' => $citations,
            'summary_text' => $this->formatTestPlanSummary($title, $checklist),
        ];
    }

    /**
     * @param array<int, array{question: string, reason: string, citation: string}> $questions
     */
    private function formatFollowUpSummary(array $questions): string
    {
        $lines = ['Suggested follow-up questions:'];
        foreach ($questions as $i => $q) {
            $lines[] = ($i + 1).'. '.$q['question'].' [Evidence: '.$q['citation'].']';
        }
        return implode("\n", $lines);
    }

    /**
     * @param array<int, array{step: string, expected: string, citation: string}> $checklist
     */
    private function formatTestPlanSummary(string $title, array $checklist): string
    {
        $lines = [$title.':'];
        foreach ($checklist as $i => $item) {
            $lines[] = ($i + 1).'. '.$item['step'].' — Expected: '.$item['expected'].' [Evidence: '.$item['citation'].']';
        }
        return implode("\n", $lines);
    }
}

