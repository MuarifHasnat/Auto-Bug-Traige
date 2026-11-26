<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Evaluation Lab · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] bg-dot-grid">
    <header class="sticky top-0 z-20 flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white/90 backdrop-blur-md px-5 lg:px-8">
        <div class="flex items-center gap-3">
            <a href="/dashboard" class="inline-flex items-center gap-1.5 rounded-xl border border-[#E2E8F0] bg-white px-3 py-1.5 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition shadow-2xs">
                ← Back to Dashboard
            </a>
            <span class="text-xs sm:text-sm font-extrabold text-[#0F172A] flex items-center gap-1.5">
                <span>🧪</span> AI Evaluation Lab
            </span>
        </div>
        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-[11px] font-mono font-bold text-indigo-700">
            Offline Deterministic Benchmark
        </span>
    </header>

    <main class="mx-auto max-w-[1280px] p-4 sm:p-6 space-y-5">
        <section class="rounded-2xl border border-[#E2E8F0] bg-white p-5 shadow-xs flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-lg font-extrabold text-[#0F172A]">Offline Agent Benchmark &amp; Baseline Comparison</h1>
                <p class="text-xs text-[#64748B] mt-0.5">
                    Reads the latest saved evaluation report without triggering live AI or external API calls.
                </p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-900 px-3.5 py-2 text-[11px] font-mono text-emerald-300">
                python scripts/evaluate_agent.py
            </div>
        </section>

        @if(!empty($report))
            @php
                $m = $report['metrics'] ?? [];
                $b = $report['baseline'] ?? [];
                $cases = $report['cases'] ?? [];
            @endphp

            <section class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                <div class="rounded-2xl border border-emerald-200 bg-white p-3.5 shadow-xs">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Duplicate F1</span>
                    <span class="text-xl font-extrabold text-emerald-700 mt-1 block">{{ number_format(((float) ($m['duplicate_f1'] ?? 0)) * 100, 1) }}%</span>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-xs">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Dup Precision</span>
                    <span class="text-xl font-extrabold text-slate-900 mt-1 block">{{ number_format(((float) ($m['duplicate_precision'] ?? 0)) * 100, 1) }}%</span>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-xs">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Dup Recall</span>
                    <span class="text-xl font-extrabold text-slate-900 mt-1 block">{{ number_format(((float) ($m['duplicate_recall'] ?? 0)) * 100, 1) }}%</span>
                </div>
                <div class="rounded-2xl border border-indigo-200 bg-white p-3.5 shadow-xs">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Priority Acc</span>
                    <span class="text-xl font-extrabold text-indigo-700 mt-1 block">{{ number_format(((float) ($m['priority_accuracy'] ?? 0)) * 100, 1) }}%</span>
                </div>
                <div class="rounded-2xl border border-indigo-200 bg-white p-3.5 shadow-xs">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Feature Acc</span>
                    <span class="text-xl font-extrabold text-indigo-700 mt-1 block">{{ number_format(((float) ($m['feature_accuracy'] ?? 0)) * 100, 1) }}%</span>
                </div>
                <div class="rounded-2xl border border-sky-200 bg-white p-3.5 shadow-xs">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Top-3 Files</span>
                    <span class="text-xl font-extrabold text-sky-700 mt-1 block">{{ number_format(((float) ($m['file_top3_rate'] ?? 0)) * 100, 1) }}%</span>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-xs">
                    <span class="text-[10px] font-bold uppercase text-slate-500 block">Guardrails</span>
                    <span class="text-xl font-extrabold {{ ((int) ($m['guardrail_violations'] ?? 0)) === 0 ? 'text-emerald-700' : 'text-rose-700' }} mt-1 block">
                        {{ ((int) ($m['guardrail_violations'] ?? 0)) === 0 ? '0 Violations' : ($m['guardrail_violations'] . ' Failed') }}
                    </span>
                </div>
            </section>

            <section class="rounded-2xl border border-[#E2E8F0] bg-white p-5 shadow-xs space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h2 class="text-sm font-extrabold text-[#0F172A]">Agent vs Naive Baseline Comparison</h2>
                    <span class="text-[11px] font-mono text-slate-500">
                        Dataset: {{ $report['dataset'] ?? 'tests/fixtures/triage_cases.json' }} · {{ $report['total_cases'] ?? count($cases) }} cases
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-[10px] uppercase text-slate-500">
                                <th class="py-2 pr-4">Metric</th>
                                <th class="py-2 pr-4">Naive Baseline</th>
                                <th class="py-2 pr-4">AutoBugTriage Agent</th>
                                <th class="py-2">Gain</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php
                                $rows = [
                                    ['Duplicate Detection F1', (float) ($b['duplicate_f1'] ?? 0), (float) ($m['duplicate_f1'] ?? 0)],
                                    ['Priority Classification Accuracy', (float) ($b['priority_accuracy'] ?? 0), (float) ($m['priority_accuracy'] ?? 0)],
                                    ['Feature Categorization Accuracy', (float) ($b['feature_accuracy'] ?? 0), (float) ($m['feature_accuracy'] ?? 0)],
                                    ['Top-3 File Localization Rate', (float) ($b['file_top3_rate'] ?? 0), (float) ($m['file_top3_rate'] ?? 0)],
                                ];
                            @endphp
                            @foreach($rows as [$label, $baseVal, $agentVal])
                                <tr>
                                    <td class="py-2.5 pr-4 font-semibold text-slate-800">{{ $label }}</td>
                                    <td class="py-2.5 pr-4 font-mono text-slate-500">{{ number_format($baseVal * 100, 1) }}%</td>
                                    <td class="py-2.5 pr-4 font-mono font-bold text-indigo-700">{{ number_format($agentVal * 100, 1) }}%</td>
                                    <td class="py-2.5 font-mono font-bold text-emerald-700">+{{ number_format(($agentVal - $baseVal) * 100, 1) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-2xl border border-[#E2E8F0] bg-white p-5 shadow-xs space-y-3">
                <h2 class="text-sm font-extrabold text-[#0F172A]">Benchmark Case Breakdown</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-[10px] uppercase text-slate-500">
                                <th class="py-2 pr-3">ID</th>
                                <th class="py-2 pr-3">Report Title</th>
                                <th class="py-2 pr-3">Feature</th>
                                <th class="py-2 pr-3">Priority</th>
                                <th class="py-2 pr-3">Duplicate</th>
                                <th class="py-2 pr-3">Top-3 File</th>
                                <th class="py-2">Assigned</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($cases as $c)
                                <tr>
                                    <td class="py-2 pr-3 font-mono font-bold text-slate-700">{{ $c['id'] ?? '' }}</td>
                                    <td class="py-2 pr-3 text-slate-800 max-w-xs truncate">{{ $c['title'] ?? '' }}</td>
                                    <td class="py-2 pr-3">
                                        <span class="rounded px-1.5 py-0.5 text-[10px] font-bold {{ ($c['expected_feature'] ?? '') === ($c['predicted_feature'] ?? '') ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ $c['predicted_feature'] ?? '' }}
                                        </span>
                                    </td>
                                    <td class="py-2 pr-3">
                                        <span class="rounded px-1.5 py-0.5 text-[10px] font-bold {{ ($c['expected_priority'] ?? '') === ($c['predicted_priority'] ?? '') ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ $c['predicted_priority'] ?? '' }}
                                        </span>
                                    </td>
                                    <td class="py-2 pr-3 font-mono text-[11px]">
                                        {{ !empty($c['predicted_duplicate']) ? 'Duplicate' : 'Unique' }} ({{ $c['similarity_score'] ?? 0 }}%)
                                    </td>
                                    <td class="py-2 pr-3 font-mono text-[11px] {{ !empty($c['top3_file_hit']) ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ !empty($c['top3_file_hit']) ? '✓ ' : '✕ ' }}{{ $c['top_file'] ?? 'None' }}
                                    </td>
                                    <td class="py-2 text-slate-700">{{ $c['assigned_to'] ?? 'Unassigned' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <section class="rounded-2xl border border-amber-200 bg-amber-50/70 p-6 text-center space-y-2">
                <p class="text-sm font-bold text-amber-900">No saved evaluation report found yet.</p>
                <p class="text-xs text-amber-800">Run <code class="font-mono bg-white px-2 py-0.5 rounded border border-amber-200">python scripts/evaluate_agent.py</code> in the terminal to generate <code class="font-mono">storage/app/ai_evaluation_report.json</code>.</p>
            </section>
        @endif
    </main>
</body>
</html>

