<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Triage Studio · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    @if(auth()->user()->role === 'tester')<link rel="stylesheet" href="/css/tester-theme.css">@endif
    <script>window.reverbConfig = {{ Illuminate\Support\Js::from(['key' => config('broadcasting.connections.reverb.key'), 'host' => config('broadcasting.connections.reverb.options.host'), 'port' => config('broadcasting.connections.reverb.options.port'), 'scheme' => config('broadcasting.connections.reverb.options.scheme')]) }};</script>
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/js/app.js')
    @endif
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased {{ auth()->user()->role === 'tester' ? 'tester-shell' : '' }}">
<div class="flex min-h-screen">
    <!-- Modern Light Glassmorphic Left Sidebar (250px) -->
    @include('components.app-sidebar', ['active' => 'ai-agent'])

    <!-- Main Content Container -->
    <main class="min-w-0 flex-1 flex flex-col">
        <!-- Sticky Top Header (60px) -->
        <header class="sticky top-0 z-10 flex min-h-[72px] items-center justify-between border-b border-[#E2E8F0] bg-white/90 backdrop-blur-md px-5 py-3 lg:px-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                <span>Workspace</span>
                <span class="text-slate-300">/</span>
                <span class="text-[#0F172A] font-bold">{{ session('project_name', 'Project') }}</span>
                <span class="text-slate-300">/</span>
                    <span class="text-indigo-600">AI Triage Studio</span>
            </div>

            <div class="flex items-center gap-3">
                @include('components.project-switcher', ['variant' => 'mobile'])
                @include('components.profile-header')
            </div>
        </header>

        <!-- Studio Body -->
        <div class="flex-1 mx-auto w-full max-w-[1600px] px-5 py-6 lg:px-8 space-y-6">
            @php
                $studioBugs = auth()->user()->role === 'developer' ? $assignedBugs : $bugs;
                $studioHigh = $studioBugs->filter(fn ($item) => in_array(strtolower((string) ($item->priority ?? '')), ['high', 'critical', 'urgent', 'p0', 'p1']))->count();
                $studioFilePaths = $studioBugs->flatMap(function ($item) {
                    $result = json_decode($item->triage_result ?? '', true) ?: [];
                    $files = $result['code_help']['suspected_files'] ?? $result['suspected_files'] ?? [];
                    return collect($files)->map(fn ($file) => is_array($file) ? ($file['path'] ?? null) : $file)->filter();
                })->unique()->values();
                $studioFixReady = $studioBugs->filter(function ($item) {
                    $result = json_decode($item->triage_result ?? '', true) ?: [];
                    return !empty($result['code_help']['fix_suggestions']) || !empty($result['code_help']['suggestions']) || !empty($result['code_help']['fixed_code']);
                })->count();
                $studioSelected = $studioBugs->first();
                $studioTriage = $studioSelected ? (json_decode($studioSelected->triage_result ?? '', true) ?: []) : [];
                $triageResult = session('ai_result') ?: $studioTriage;
                $studioCodeHelp = $studioTriage['code_help'] ?? [];
                $studioFiles = $studioCodeHelp['suspected_files'] ?? $studioTriage['suspected_files'] ?? [];
                $studioFixes = $studioCodeHelp['fix_suggestions'] ?? $studioCodeHelp['suggestions'] ?? [];
                $cloneUrl = rtrim((string) ($projectGithubRepo ?? ''), '/');
                if ($cloneUrl !== '' && !str_ends_with($cloneUrl, '.git')) {
                    $cloneUrl .= '.git';
                }
            @endphp

            @if(auth()->user()->role !== 'tester')
            <!-- Project overview for managers and developers -->
            <section class="space-y-5" aria-labelledby="studio-title">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-500">{{ session('project_name', $currentProject->name ?? 'Project') }} <span class="mx-1 text-slate-300">/</span> AI Triage</p>
                        <h1 id="studio-title" class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Find, locate and fix bugs</h1>
                        <p class="mt-1 text-sm text-slate-500">Review project issues, inspect AI evidence and open the suggested fix in context.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if(!empty($projectGithubRepo))
                            <button type="button" data-clone-command="git clone {{ $cloneUrl }}" onclick="copyCloneCommand(this)" class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 hover:border-indigo-300" title="Copy git clone command">⎘ <span data-clone-label>Copy git clone command</span></button>
                        @endif
                        @if(auth()->user()->role === 'manager')
                            <form action="{{ route('projects.sync-github') }}" method="POST">@csrf
                                <button class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-indigo-600 px-4 text-sm font-bold text-white shadow-sm hover:bg-indigo-500" {{ empty($projectGithubRepo) ? 'disabled' : '' }}>⟳ Scan repository</button>
                            </form>
                        @elseif(!empty($projectGithubRepo))
                            <a href="{{ $projectGithubRepo }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-indigo-600 px-4 text-sm font-bold text-white shadow-sm hover:bg-indigo-500">↗ Open repository</a>
                        @endif
                        @if(auth()->user()->role !== 'developer')
                            <button type="button" onclick="openTriageForm()" class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-indigo-200 bg-white px-4 text-sm font-bold text-indigo-700 hover:bg-indigo-50">＋ Report bug</button>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm font-semibold text-slate-500">{{ auth()->user()->role === 'developer' ? 'Assigned bugs' : 'Bugs detected' }}</p><p class="mt-2 text-3xl font-extrabold text-slate-900">{{ auth()->user()->role === 'developer' ? $studioBugs->count() : $projectBugCount }}</p><p class="mt-1 text-xs text-slate-500">{{ auth()->user()->role === 'developer' ? 'Assigned to your account' : 'Total reports in this project' }}</p></div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm font-semibold text-slate-500">Files affected</p><p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $studioFilePaths->count() }}</p><p class="mt-1 text-xs text-slate-500">From repository triage evidence</p></div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm font-semibold text-slate-500">High severity</p><p class="mt-2 text-3xl font-extrabold text-rose-600">{{ auth()->user()->role === 'developer' ? $studioHigh : ($projectHighCount ?? $studioHigh) }}</p><p class="mt-1 text-xs text-slate-500">High, critical or urgent priority</p></div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm font-semibold text-slate-500">Fix guidance ready</p><p class="mt-2 text-3xl font-extrabold text-indigo-700">{{ $studioFixReady }}</p><p class="mt-1 text-xs text-slate-500">AI suggestions available to review</p></div>
                </div>

                <div class="grid min-w-0 grid-cols-1 gap-4 xl:grid-cols-12">
                    <section class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-6">
                        <div class="mb-4 flex items-center justify-between"><div><h2 class="text-lg font-bold text-slate-900">Recent bugs</h2><p class="text-xs text-slate-500">Latest reports in this project</p></div><a href="/workspace" class="text-sm font-semibold text-indigo-600 hover:underline">Open board →</a></div>
                        <div class="max-h-[440px] space-y-2 overflow-y-auto">
                            @forelse($studioBugs as $studioBug)
                                <a href="/bugs/{{ $studioBug->id }}" class="block rounded-xl border border-slate-100 bg-slate-50 p-3.5 transition hover:border-indigo-200 hover:bg-indigo-50/50">
                                    <div class="flex items-center justify-between gap-2"><span class="font-mono text-[11px] font-bold text-indigo-700">#ABT-{{ $studioBug->id }}</span><span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ in_array(strtolower((string) $studioBug->priority), ['high','critical','urgent','p0','p1']) ? 'bg-rose-100 text-rose-700' : 'bg-slate-200 text-slate-600' }}">{{ $studioBug->priority ?: 'Unclassified' }}</span></div>
                                    <p class="mt-2 line-clamp-2 text-sm font-semibold text-slate-800">{{ $studioBug->title }}</p>
                                    <p class="mt-1 truncate text-xs text-slate-500">{{ $studioBug->detected_feature ?: $studioBug->issue_type ?: 'Project issue' }} · {{ $studioBug->status ?: 'Open' }}</p>
                                </a>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center"><span class="text-3xl">🎉</span><p class="mt-2 font-semibold text-slate-800">No bugs to triage yet</p><p class="mt-1 text-xs text-slate-500">Submit a report and its analysis will appear here.</p></div>
                            @endforelse
                        </div>
                    </section>

                    <section class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-6">
                        <div class="flex items-start justify-between gap-3"><div><h2 class="text-lg font-bold text-slate-900">AI diagnosis</h2><p class="text-xs text-slate-500">Evidence-based analysis for the latest report</p></div><span class="rounded-full bg-violet-50 px-2.5 py-1 text-[10px] font-bold text-violet-700">{{ !empty($studioTriage) ? 'Analyzed' : 'Waiting for report' }}</span></div>
                        @if($studioSelected)
                            <div class="mt-4 rounded-xl bg-slate-50 p-3.5"><p class="text-xs font-semibold text-slate-500">{{ $studioSelected->title }}</p><p class="mt-2 text-sm font-semibold text-slate-800">{{ $studioSelected->detected_feature ?: 'General area' }} <span class="font-normal text-slate-500">· {{ $studioSelected->priority ?: 'Priority pending' }}</span></p></div>
                            @php
                                $studioAnalysis = $studioTriage['code_help']['llm_analysis'] ?? $studioTriage['llm_analysis'] ?? [];
                            @endphp
                            @if(!empty($studioAnalysis['root_cause']))<p class="mt-4 text-sm leading-6 text-slate-700">{{ $studioAnalysis['root_cause'] }}</p>@elseif(!empty($studioAnalysis['summary']))<p class="mt-4 text-sm leading-6 text-slate-700">{{ $studioAnalysis['summary'] }}</p>@else<p class="mt-4 text-sm leading-6 text-slate-600">{{ Str::limit($studioSelected->description ?? '', 220) }}</p>@endif
                            @if(count($studioFixes))<div class="mt-4 border-t border-slate-100 pt-4"><p class="text-xs font-bold uppercase tracking-wide text-slate-500">Suggested next step</p><p class="mt-2 text-sm text-slate-700">{{ is_array($studioFixes[0]) ? ($studioFixes[0]['detail'] ?? $studioFixes[0]['description'] ?? $studioFixes[0]['title'] ?? 'Review the AI fix suggestion') : $studioFixes[0] }}</p></div>@endif
                            <a href="/bugs/{{ $studioSelected->id }}" class="mt-5 inline-flex min-h-10 w-full items-center justify-center rounded-xl bg-indigo-600 px-4 text-sm font-bold text-white hover:bg-indigo-500">Review full diagnosis →</a>
                        @else
                            <div class="mt-6 rounded-xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm leading-6 text-slate-600">AI analysis will show the likely cause, confidence and suggested changes after you submit a bug report.</div>
                        @endif
                        <p class="mt-3 text-[11px] leading-5 text-slate-400">Suggested fixes are review guidance; repository files are not changed automatically.</p>
                    </section>
                </div>
            </section>
            @endif

            @if(auth()->user()->role !== 'manager')
            <details id="triageWorkflow" class="group rounded-2xl border border-slate-200 bg-white p-3.5 sm:p-4 shadow-sm {{ auth()->user()->role === 'tester' ? 'border-0 bg-transparent p-0 shadow-none' : '' }}" @if(auth()->user()->role === 'tester' || session('ai_result') || (isset($errors) && $errors->any())) open @endif>
                <summary class="{{ auth()->user()->role === 'tester' ? 'hidden' : 'flex' }} cursor-pointer list-none items-center justify-between gap-3 rounded-xl px-2 py-1 text-sm font-bold text-slate-800 marker:hidden [&::-webkit-details-marker]:hidden">
                    <span>Report and full AI analysis</span><span class="text-xs font-semibold text-indigo-600 group-open:hidden">Open workspace ↓</span><span class="hidden text-xs font-semibold text-indigo-600 group-open:inline">Close workspace ↑</span>
                </summary>
                <div class="mt-3.5 space-y-4">
            <!-- Header Title -->
            @if(auth()->user()->role === 'tester')
            <div class="mb-3.5">
                <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">AI Triage Studio</h1>
                <p class="mt-0.5 text-xs text-slate-600">Describe the bug. The AI categorizes it, finds the file and assigns a developer.</p>
            </div>
            @else
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-[#0F172A]">AI Triage Workbench</h1>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 text-xs font-bold text-indigo-700 font-mono-code">
                            Hero Studio
                        </span>
                    </div>
                    <p class="text-xs text-[#64748B] mt-0.5">Submit software anomalies for AST syntax inspection, duplicate hash checking, and automated engineer assignment.</p>
                </div>
                <span class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] px-3 py-1 text-xs font-bold text-[#0F172A]">
                    Mode: {{ ucfirst(auth()->user()->role) }} Console
                </span>
            </div>
            @endif

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3.5 text-xs font-medium text-emerald-800 flex items-center gap-2.5 shadow-xs">
                    <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-800 flex items-start gap-2.5 shadow-xs">
                    <span class="text-rose-500 font-bold mt-0.5">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(auth()->user()->role === 'developer')
                <!-- Developer Workspace View: Assigned Tasks with AI Root-Cause Diagnostic Cards -->
                <div class="space-y-6">
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E2E8F0] pb-4 mb-5">
                            <div>
                                <h2 class="text-base font-bold text-[#0F172A] flex items-center gap-2">
                                    <span>💻</span>
                                    <span>Issues Assigned to You ({{ $assignedBugs->count() }})</span>
                                </h2>
                                <p class="text-xs text-[#64748B] mt-0.5">Click into any issue to view full details, screenshots, AI fix guidance, and GitHub culprit files.</p>
                            </div>
                            <a href="/workspace" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-4 py-2 transition shadow-xs">
                                <span>Open Kanban Board</span> ➔
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($assignedBugs as $bug)
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-5 space-y-3 hover:border-indigo-300 hover:bg-white transition shadow-2xs flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="font-mono-code font-bold text-xs text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-lg">
                                                #ABT-{{ $bug->id }}
                                            </span>
                                            <div class="flex items-center gap-1.5">
                                                <span class="rounded-lg px-2 py-0.5 text-[10px] font-bold {{ $bug->priority === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                                    {{ $bug->priority }}
                                                </span>
                                                <span class="rounded-lg px-2 py-0.5 text-[10px] font-bold {{ $bug->status === 'Done' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-700' }}">
                                                    {{ $bug->status }}
                                                </span>
                                            </div>
                                        </div>

                                        <h3 class="font-bold text-sm text-[#0F172A] leading-snug">
                                            {{ $bug->title }}
                                        </h3>
                                        <p class="text-xs text-[#64748B] line-clamp-2">
                                            {{ $bug->description }}
                                        </p>
                                    </div>

                                    <div class="pt-3 border-t border-[#E2E8F0] flex items-center justify-between gap-2">
                                        <span class="text-[11px] font-semibold text-purple-700">
                                            ✨ {{ $bug->detected_feature ?? 'Module' }}
                                        </span>
                                        <a href="/bugs/{{ $bug->id }}" class="inline-flex items-center gap-1 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-3.5 py-1.5 transition shadow-2xs">
                                            <span>Open & Fix Issue</span> ➔
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-12 border border-dashed border-[#CBD5E1] rounded-2xl bg-[#F8FAFC]">
                                    <span class="text-3xl">🎉</span>
                                    <p class="text-sm font-bold text-[#0F172A] mt-2">All caught up!</p>
                                    <p class="text-xs text-[#64748B] mt-1">You currently have no open bugs assigned to you.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                @if(auth()->user()->role !== 'tester')
                <!-- Friendly 3-Step Process Stepper -->
                <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50/70 via-white to-sky-50/70 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-600 text-white text-xs font-bold">ℹ</span>
                            <h3 class="text-xs font-extrabold text-[#0F172A] uppercase tracking-wider">How AI Bug Triage Works</h3>
                        </div>
                        <span class="text-[11px] font-semibold text-indigo-600">Zero Configuration • Fully Automated</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="rounded-xl border border-indigo-100/70 bg-white/90 p-3 shadow-2xs">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#0F172A]">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-mono text-[11px] font-bold">1</span>
                                <span>1. Describe the bug</span>
                            </div>
                            <p class="text-[11px] text-[#64748B] mt-1.5 leading-relaxed">Enter a title and description, choose a QA template, or paste an error screenshot with <kbd class="rounded bg-slate-100 px-1 font-mono text-[10px]">Ctrl+V</kbd>.</p>
                        </div>
                        <div class="rounded-xl border border-indigo-100/70 bg-white/90 p-3 shadow-2xs">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#0F172A]">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-purple-100 text-purple-700 font-mono text-[11px] font-bold">2</span>
                                <span>2. AI code and file analysis</span>
                            </div>
                            <p class="text-[11px] text-[#64748B] mt-1.5 leading-relaxed">The agent checks code syntax, scans the linked GitHub repository for likely files, and checks for duplicate bugs.</p>
                        </div>
                        <div class="rounded-xl border border-indigo-100/70 bg-white/90 p-3 shadow-2xs">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#0F172A]">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-mono text-[11px] font-bold">3</span>
                                <span>3. Assignment and Kanban tracking</span>
                            </div>
                            <p class="text-[11px] text-[#64748B] mt-1.5 leading-relaxed">The system recommends an engineer based on skills such as Kotlin, PHP, or Python, then tracks the issue on the Kanban board.</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Split-Screen IDE Workbench: 45% Left / 55% Right -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
                
                <!-- Left Panel (45% / col-span-5): Tester Bug Input Console with 1-Click Templates & Paste Support -->
                <section class="{{ auth()->user()->role === 'tester' ? 'lg:col-span-8 rounded-2xl' : 'lg:col-span-5 rounded-3xl' }} border border-[#E2E8F0] bg-white p-5 shadow-sm">
                    <div class="border-b border-[#E2E8F0] pb-4 mb-4">
                        <h2 class="text-base font-bold text-[#0F172A]">{{ auth()->user()->role === 'tester' ? 'Report a bug' : 'Submit New Bug for AI Triage' }}</h2>
                        <p class="text-xs text-[#64748B] mt-0.5">{{ auth()->user()->role === 'tester' ? 'Add what you observed and include a screenshot if it helps.' : 'Fill out report details or attach crash snippets for AST diagnosis.' }}</p>
                    </div>

                    <form id="triageForm" action="/api/bugs/store" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="handleTriageSubmit(event)">
                        @csrf

                        <!-- Invalid Input Warning Banner (Shown when gibberish or meaningless text is submitted) -->
                        <div id="invalidInputBanner" class="hidden rounded-2xl border border-rose-300 bg-rose-50 p-3 text-xs text-rose-800 flex items-start gap-2.5 shadow-2xs">
                            <span class="text-base shrink-0">⚠️</span>
                            <div class="flex-1 min-w-0">
                                <b class="block font-bold text-rose-950 mb-0.5">Invalid Bug Report Detected</b>
                                <p id="invalidInputMsg" class="leading-relaxed text-rose-800">
                                    Please provide a meaningful bug title and description. Random characters or gibberish (e.g. "mbmbm") cannot be triaged.
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1">{{ auth()->user()->role === 'tester' ? 'Bug title' : 'Issue Summary / Title' }}</label>
                            <input id="bugTitle" name="title" required maxlength="200" placeholder="{{ auth()->user()->role === 'tester' ? 'e.g. App crashes when I log in' : 'e.g. ZeroDivisionError in invoice calculation logic' }}"
                                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white"
                                   oninput="handleTitleInput(this.value)">
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <label class="block text-xs font-bold text-[#0F172A] sm:col-span-2">Issue Type
                                <select name="issue_type" required class="mt-1.5 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-medium">
                                    <option value="Bug">Bug</option><option value="Task">Task</option><option value="Story">Story</option><option value="Improvement">Improvement</option>
                                </select>
                            </label>
                            <label class="{{ auth()->user()->role === 'tester' ? 'hidden' : 'block' }} text-xs font-bold text-[#0F172A]">Labels <span class="font-normal text-slate-400">(comma-separated)</span>
                                <input name="labels" maxlength="500" placeholder="backend, checkout, urgent" class="mt-1.5 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-medium">
                            </label>
                        </div>

                        <!-- GitHub Repository Target (Auto-filled from project, editable by user) -->
                        <div class="{{ auth()->user()->role === 'tester' ? 'hidden' : '' }}">
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-[#0F172A] flex items-center gap-1.5">
                                    <span>🐙</span>
                                    <span>Target GitHub Repository</span>
                                </label>
                                @if(!empty($projectGithubRepo))
                                    <span class="text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.2 rounded font-mono-code font-bold">Project Linked</span>
                                @else
                                    <span class="text-[10px] text-slate-500 font-mono-code">Scans actual codebase files</span>
                                @endif
                            </div>
                            <input type="text" id="triageGithubRepo" name="github_repo" value="{{ old('github_repo', $projectGithubRepo ?? '') }}" 
                                   placeholder="https://github.com/username/repository" 
                                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2 text-xs font-mono-code text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white">
                            <p class="text-[10px] text-slate-500 mt-1">
                                The agent scans this repository to identify the file most likely related to the bug.
                            </p>
                        </div>

                        <!-- Guided tester report: observed behavior, steps, expected result -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="block text-xs font-bold text-[#0F172A]">What happened?</span>
                                <div class="flex items-center gap-1">
                                    <span class="text-[10px] text-[#64748B] font-bold">QA Templates:</span>
                                    <button type="button" onclick="applyTemplate('ui')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-indigo-600 hover:bg-indigo-50">+ UI Glitch</button>
                                    <button type="button" onclick="applyTemplate('payment')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-rose-600 hover:bg-rose-50">+ Payment Crash</button>
                                    <button type="button" onclick="applyTemplate('auth')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-purple-600 hover:bg-purple-50">+ Login Error</button>
                                </div>
                            </div>
                            <textarea id="bugDescription" name="description" required rows="3" maxlength="3000"
                                      placeholder="Describe what you saw, including any error message."
                                      class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2.5 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white leading-relaxed"
                                      ></textarea>
                            <div>
                                <div class="mb-1.5 flex items-center justify-between gap-2"><label for="reproductionStep1" class="text-xs font-bold text-[#0F172A]">Reproduction steps</label><button type="button" onclick="addReproductionStep()" class="rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-[10px] font-bold text-indigo-700 hover:bg-indigo-100">+ Add step</button></div>
                                <ol id="reproductionSteps" class="space-y-2">
                                    <li class="flex items-center gap-2"><span class="step-number flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-[10px] font-bold text-indigo-700">1</span><input id="reproductionStep1" name="reproduction_steps[]" required maxlength="500" placeholder="e.g. Open the cart and select Checkout" class="reproduction-step min-w-0 flex-1 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs focus:border-indigo-600 focus:bg-white focus:outline-none"><button type="button" onclick="removeReproductionStep(this)" aria-label="Remove step 1" class="rounded-lg px-2 py-1 text-xs font-bold text-slate-400 hover:bg-rose-50 hover:text-rose-600">×</button></li>
                                </ol>
                            </div>
                            <div class="{{ auth()->user()->role === 'tester' ? 'hidden' : '' }}">
                                <label for="expectedResult" class="block text-xs font-bold text-[#0F172A]">What did you expect?</label>
                                <textarea id="expectedResult" name="expected_result" rows="2" maxlength="2000" placeholder="Describe what should have happened." class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2.5 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white"></textarea>
                            </div>
                        </div>

                        <!-- Full-Width Screenshot Dropzone with Live Preview (Paste & Upload) -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-[#0F172A]">
                                    Problem Screenshot / Error Proof <span class="text-indigo-600 font-semibold">(Image Evidence)</span>
                                </label>
                                <span class="text-[10px] text-slate-500 font-mono-code">No code required</span>
                            </div>

                            <!-- Hidden file input separated completely from dropzone -->
                            <input type="file" id="screenshotInput" name="screenshot" accept="image/png,image/jpeg,image/jpg,image/webp,.png,.jpg,.jpeg,.webp,.jfif" class="hidden" onchange="handleScreenshotSelected(this)">

                            <div id="screenshotDropzone" 
                                 onclick="handleDropzoneClick(event)"
                                 ondragover="handleDragOver(event)"
                                 ondragenter="handleDragEnter(event)"
                                 ondragleave="handleDragLeave(event)"
                                 ondrop="handleScreenshotDrop(event)"
                                 class="relative flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-indigo-200 bg-indigo-50/20 p-5 text-center transition group hover:border-indigo-400 hover:bg-indigo-50/40">
                                
                                <div id="screenshotPlaceholder" class="flex flex-col items-center py-2 select-none">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 text-2xl group-hover:scale-105 transition shadow-xs">📸</span>
                                    <span class="text-xs font-bold text-[#0F172A] mt-2 block" id="screenshotStatus">Click to Upload or Drag & Drop Screenshot</span>
                                    <span class="text-[11px] text-[#64748B] block mt-0.5">Supports PNG, JPG, JPEG, WEBP • Or directly press <kbd class="px-1.5 py-0.5 rounded bg-white border border-slate-300 font-mono-code text-[10px] font-bold text-slate-700 shadow-2xs">Ctrl + V</kbd> to paste</span>
                                    <button type="button" onclick="event.stopPropagation(); document.getElementById('screenshotInput').click();" class="mt-3 inline-flex min-h-9 items-center rounded-lg bg-indigo-600 px-4 text-xs font-bold text-white shadow-xs hover:bg-indigo-700 transition">
                                        Choose image
                                    </button>
                                </div>

                                <div id="screenshotPreviewWrapper" class="hidden w-full text-center">
                                    <div class="relative inline-block mx-auto">
                                        <img id="screenshotPreview" src="" alt="Screenshot Preview" class="max-h-52 rounded-xl mx-auto object-contain border border-indigo-200 shadow-sm bg-white">
                                    </div>
                                    <div class="mt-2.5 flex items-center justify-center gap-2">
                                        <span class="inline-block text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full shadow-2xs" id="screenshotFileName">✓ Image attached</span>
                                    </div>
                                    <div class="mt-2 flex items-center justify-center gap-4">
                                        <button type="button" onclick="event.stopPropagation(); document.getElementById('screenshotInput').click();" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                                            🔄 Replace image
                                        </button>
                                        <button type="button" onclick="event.stopPropagation(); clearScreenshot(event);" class="text-xs font-bold text-rose-500 hover:text-rose-700 hover:underline">
                                            ✕ Remove image
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p id="screenshotError" class="hidden mt-1 text-[11px] font-semibold text-rose-700" role="alert"></p>
                            <div id="screenshotUploadProgress" class="hidden mt-2" aria-live="polite">
                                <div class="mb-1 flex justify-between text-[10px] text-slate-600">
                                    <span id="screenshotUploadLabel">Uploading screenshot…</span>
                                    <span id="screenshotUploadPercent">0%</span>
                                </div>
                                <div class="h-1.5 overflow-hidden rounded-full bg-slate-100">
                                    <div id="screenshotUploadBar" class="h-full w-0 rounded-full bg-indigo-600 transition-[width]"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Real-World Black-Box QA Notice -->
                        <div class="{{ auth()->user()->role === 'tester' ? 'hidden' : '' }} rounded-xl border border-indigo-100 bg-indigo-50/40 p-3 text-[11px] text-indigo-950 flex items-start gap-2.5">
                            <span class="text-base shrink-0">💡</span>
                            <span class="leading-relaxed">
                                <b>Black-Box QA Mode:</b> Testers do not need code access. Submit natural language symptoms and screenshots—the AI Agent's NLP engine will determine the module, severity, duplicate status, and assign the best engineer.
                            </span>
                        </div>

                        <!-- Submit Button with Loading State & Keyboard Shortcut Info -->
                        <div>
                            <button type="submit" id="submitTriageBtn" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 py-3.5 px-4 text-xs sm:text-sm font-medium text-white shadow-sm hover:bg-slate-800 transition active:scale-[0.99]">
                                <span>✨ Submit bug report</span>
                            </button>
                            <p id="submissionFeedback" class="min-h-4 text-center text-[11px] text-slate-500 mt-2" role="status" aria-live="polite">You can review the suggested owner and status before following your report.</p>
                            <p class="text-center text-[10px] text-slate-400 mt-1">Press <b>Ctrl + Enter</b> to submit</p>
                        </div>
                    </form>
                </section>

                @if(auth()->user()->role === 'tester')
                    @php
                        $previewData = is_array($triageResult ?? null) ? $triageResult : [];
                        $previewFiles = $previewData['code_help']['suspected_files'] ?? $previewData['suspected_files'] ?? $studioFiles ?? [];
                        $previewFile = collect($previewFiles)->first();
                        $previewFilePath = is_array($previewFile) ? ($previewFile['path'] ?? null) : $previewFile;
                        $previewFeature = $previewData['detected_feature'] ?? $previewData['feature'] ?? ($studioSelected->detected_feature ?? null);
                        $previewAssignee = $previewData['assigned_to'] ?? ($studioSelected->assigned_to ?? null);
                        $previewDuplicate = $previewData['duplicate'] ?? $previewData['is_duplicate'] ?? null;
                        $previewPriority = $previewData['priority'] ?? ($studioSelected->priority ?? null);
                    @endphp
                    <aside class="lg:col-span-4 rounded-2xl border border-indigo-200 bg-indigo-50/70 p-5" aria-labelledby="ai-preview-title">
                        <h2 id="ai-preview-title" class="text-base font-bold text-slate-900">AI analysis preview</h2>
                        <span class="mt-2 inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800">Python Triage Agent: Ready</span>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div><dt class="font-bold text-slate-900">Category</dt><dd class="mt-1 text-slate-600">{{ $previewFeature ?: 'Detected after you submit' }}</dd></div>
                            <div><dt class="font-bold text-slate-900">Severity</dt><dd class="mt-1 text-slate-600">{{ $previewPriority ?: 'Classified by the AI after submission' }}</dd></div>
                            <div><dt class="font-bold text-slate-900">Culprit file</dt><dd class="mt-1 break-all text-slate-600">{{ $previewFilePath ?: (!empty($projectGithubRepo) ? 'Will be located from the GitHub index' : 'Add a repository link to locate code') }}</dd></div>
                            <div><dt class="font-bold text-slate-900">Assigned developer</dt><dd class="mt-1 text-slate-600">{{ $previewAssignee ?: 'Chosen by skill and workload' }}</dd></div>
                            <div><dt class="font-bold text-slate-900">Duplicate check</dt><dd class="mt-1 text-slate-600">{{ is_bool($previewDuplicate) ? ($previewDuplicate ? 'A similar issue was found' : 'No matching open bug found') : 'Compared with open bugs after submission' }}</dd></div>
                        </dl>
                        @if($studioSelected && !empty($studioSelected->id))
                            <a href="{{ route('bugs.show', $studioSelected->id) }}" class="mt-5 inline-flex text-sm font-semibold text-indigo-700 hover:underline">View latest triage result →</a>
                        @endif
                    </aside>
                @endif

                @if(auth()->user()->role === 'tester')
                    <section id="myReportsSection" class="hidden lg:col-span-5 rounded-3xl border border-[#E2E8F0] bg-white p-5 shadow-sm" aria-labelledby="my-reports-title">
                        <div class="mb-3 flex items-center justify-between gap-2 border-b border-slate-200 pb-3"><div><h2 id="my-reports-title" class="text-sm font-bold text-slate-900">My reports</h2><p class="mt-1 text-[11px] text-slate-500">Status, assigned developer and latest update.</p></div><span id="myReportsCount" class="rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-700">{{ $myReports->count() }}</span></div>
                        <div id="myReportsList" class="space-y-2">
                            @forelse($myReports as $report)
                                <a href="{{ route('bugs.show', $report->id) }}" class="my-report-item block rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 hover:border-indigo-300 hover:bg-indigo-50/40">
                                    <div class="flex items-start justify-between gap-2"><p class="min-w-0 truncate text-xs font-bold text-slate-900">#ABT-{{ $report->id }} · {{ $report->title }}</p><span class="shrink-0 rounded-full bg-white px-2 py-0.5 text-[10px] font-semibold text-slate-700">{{ $report->status }}</span></div>
                                    <div class="mt-1 flex flex-wrap items-center justify-between gap-2 text-[10px] text-slate-500"><span>Developer: <b class="font-semibold text-slate-700">{{ $report->assigned_to ?: 'Unassigned' }}</b></span><span>{{ ($report->triage_state ?? 'complete') === 'complete' ? 'Triage complete' : 'Triage processing' }} · {{ \Illuminate\Support\Carbon::parse($report->updated_at)->diffForHumans() }}</span></div>
                                </a>
                            @empty
                                <div id="myReportsEmpty" class="rounded-xl border border-dashed border-slate-300 px-4 py-7 text-center text-xs text-slate-500">Your submitted reports will appear here.</div>
                            @endforelse
                        </div>
                    </section>
                @endif

                <!-- Right Panel (55% / col-span-7): Diagnostics and review-only fix suggestions -->
                <section class="{{ auth()->user()->role === 'tester' ? 'hidden' : 'lg:col-span-7' }} rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm flex flex-col min-h-[580px]">
                    <div class="border-b border-[#E2E8F0] pb-4 mb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-[#0F172A]">Code Diagnostics & Fix Suggestions</h2>
                            <p class="text-xs text-[#64748B]">Language-specific syntax checks, triage results, and suggested code references.</p>
                        </div>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 text-xs font-bold text-indigo-700 font-mono-code">
                            Diagnostics Engine
                        </span>
                    </div>

                    @if(!empty($triageResult))
                        @php
                            $ai = $triageResult;
                            $pipelineCode = $ai['code_help'] ?? [];
                            $pipelineVision = $ai['screenshot_vision'] ?? $pipelineCode['screenshot_vision'] ?? [];
                            $pipelineHasScreenshot = !empty($pipelineVision) || str_contains(strtolower((string) ($pipelineCode['attachment_note'] ?? '')), 'screenshot');
                            $pipelineVisionSuccess = !empty($pipelineVision['success']);
                            $pipelineVisionMode = $pipelineVisionSuccess && in_array($pipelineVision['method'] ?? '', ['groq_vision', 'openai_vision', 'ollama_vision'], true);
                            $pipelineFiles = $pipelineCode['suspected_files'] ?? $ai['suspected_files'] ?? [];
                            $pipelineLlm = $pipelineCode['llm_analysis'] ?? [];
                            $pipelineDiagnostics = $pipelineCode['steps'] ?? [];
                            $pipelineFixes = $pipelineCode['fix_suggestions'] ?? $pipelineCode['suggestions'] ?? [];
                            $pipelineSourceContext = $pipelineCode['github_source_context'] ?? [];
                            $pipelineSteps = [
                                [
                                    'number' => '01', 'icon' => '📸', 'title' => 'Vision OCR',
                                    'state' => $pipelineVisionMode ? 'Vision analyzed' : ($pipelineVisionSuccess ? 'OCR only' : ($pipelineHasScreenshot ? 'Needs review' : 'Skipped')),
                                    'tone' => $pipelineVisionMode ? 'emerald' : ($pipelineHasScreenshot ? 'amber' : 'slate'),
                                    'summary' => $pipelineVisionMode
                                        ? ($pipelineVision['visual_summary'] ?? 'Vision model inspected the screenshot and extracted visual evidence.')
                                        : ($pipelineVisionSuccess ? 'Text was extracted, but visual bug classification did not complete.' : ($pipelineHasScreenshot ? 'Screenshot analysis failed; no image-derived conclusion was used.' : 'No screenshot was attached, so image reading was skipped.')),
                                    'facts' => array_values(array_filter([
                                        !empty($pipelineVision['method']) ? 'Reader: '.strtoupper(str_replace('_', ' ', $pipelineVision['method'])) : null,
                                        !empty($pipelineVision['page_type']) ? 'Page type: '.$pipelineVision['page_type'] : null,
                                        !empty($pipelineVision['feature']) ? 'Image category: '.$pipelineVision['feature'] : null,
                                        isset($pipelineVision['confidence']) ? 'Image confidence: '.$pipelineVision['confidence'].'%' : null,
                                        !empty($pipelineVision['error_name']) ? 'Detected error: '.$pipelineVision['error_name'] : null,
                                        !empty($pipelineVision['error_message']) ? 'Visible message: '.$pipelineVision['error_message'] : null,
                                        !empty($pipelineVision['line_numbers']) ? 'Line numbers: '.implode(', ', $pipelineVision['line_numbers']) : null,
                                        !empty($pipelineVision['suspected_files']) ? 'Names found in image: '.implode(', ', $pipelineVision['suspected_files']) : null,
                                        !empty($pipelineVision['visual_evidence']) ? 'Visual evidence: '.implode('; ', $pipelineVision['visual_evidence']) : null,
                                        !empty($pipelineVision['stack_traces']) ? 'Stack trace lines extracted: '.count($pipelineVision['stack_traces']) : null,
                                        !empty($pipelineVision['analysis_limit']) ? $pipelineVision['analysis_limit'] : null,
                                        !empty($pipelineVision['fallback_reason']) ? 'Vision fallback: '.$pipelineVision['fallback_reason'] : (!empty($pipelineVision['error']) ? 'Analysis issue: '.$pipelineVision['error'] : null),
                                    ])),
                                    'raw' => $pipelineVision['extracted_text'] ?? '',
                                ],
                                [
                                    'number' => '02', 'icon' => '🏷️', 'title' => 'Categorizer & priority', 'state' => 'Complete', 'tone' => 'emerald',
                                    'summary' => 'Classified the report from its title, description, supplied code and any OCR text.',
                                    'facts' => array_values(array_filter([
                                        'Module: '.($ai['feature'] ?? $pipelineCode['feature'] ?? 'General'),
                                        'Priority: '.($ai['priority'] ?? 'Medium'),
                                        'Issue status: '.($ai['status'] ?? 'Open'),
                                        'Blocker signal: '.(!empty($ai['blocker_signal']) ? 'Detected' : 'Not detected'),
                                        'Sentiment: '.ucfirst((string) ($ai['sentiment'] ?? 'neutral')).(isset($ai['sentiment_score']) ? ' (score '.round((float) $ai['sentiment_score'], 2).')' : ''),
                                        'Method: keyword-based feature and priority rules',
                                    ])), 'raw' => '',
                                ],
                                [
                                    'number' => '03', 'icon' => '🔍', 'title' => 'Duplicate check', 'state' => 'Complete', 'tone' => !empty($ai['is_duplicate']) ? 'amber' : 'emerald',
                                    'summary' => !empty($ai['is_duplicate']) ? 'A similar existing report crossed the duplicate threshold; review the match before closing either report.' : 'Compared this report with earlier project reports; no match crossed the duplicate threshold.',
                                    'facts' => array_values(array_filter([
                                        'Closest similarity: '.($ai['similarity_score'] ?? 0).'% (duplicate threshold: 75%)',
                                        !empty($ai['duplicate_of']) ? 'Matched report: #ABT-'.$ai['duplicate_of'] : 'Duplicate match: none',
                                        'Compared fields: report title and description',
                                        'Method: cosine similarity over report text',
                                    ])), 'raw' => '',
                                ],
                                [
                                    'number' => '04', 'icon' => '🧪', 'title' => 'AST & syntax diagnostics',
                                    'state' => match($pipelineCode['syntax_status'] ?? 'not_run') { 'passed' => 'Passed', 'failed' => 'Issue found', 'timeout' => 'Timed out', 'not_available' => 'Unavailable', default => 'Not run' },
                                    'tone' => match($pipelineCode['syntax_status'] ?? 'not_run') { 'passed' => 'emerald', 'failed' => 'rose', 'timeout', 'not_available' => 'amber', default => 'slate' },
                                    'summary' => !empty($pipelineCode['fixed_code']) ? 'Checked the provided source snippet with a language parser. This is a syntax check; it cannot confirm runtime or business logic.' : 'No source snippet was provided, so parser-based syntax checking was skipped.',
                                    'facts' => array_merge(array_values(array_filter([
                                        'Language: '.($pipelineCode['language'] ?? 'Unknown'),
                                        !empty($pipelineCode['syntax_checker']) ? 'Checker: '.$pipelineCode['syntax_checker'] : null,
                                        !empty($pipelineCode['error_name']) ? 'Result: '.$pipelineCode['error_name'] : null,
                                        !empty($pipelineCode['analysis_scope']) ? 'Scope: '.$pipelineCode['analysis_scope'] : null,
                                    ])), is_array($pipelineDiagnostics) ? array_map(fn ($item) => is_array($item) ? json_encode($item) : (string) $item, $pipelineDiagnostics) : []), 'raw' => '',
                                ],
                                [
                                    'number' => '05', 'icon' => '🐙', 'title' => 'GitHub file locator',
                                    'state' => !empty($pipelineFiles) ? 'Files found' : (empty($ai['github_repo']) ? 'Repository not linked' : 'No file match'),
                                    'tone' => !empty($pipelineFiles) ? 'emerald' : 'amber',
                                    'summary' => !empty($pipelineFiles) ? 'Ranked likely repository files using report clues, stack details and project context.' : 'No likely source file was returned. Link or scan the repository and include an error trace for stronger file matches.',
                                    'facts' => array_merge(array_values(array_filter([
                                        !empty($ai['github_repo']) ? 'Repository: '.$ai['github_repo'] : 'Repository: not linked to this project',
                                        !empty($pipelineSourceContext) ? 'Source read: '.implode(', ', array_map(fn ($item) => ($item['path'] ?? 'file').' ('.($item['lines'] ?? 0).' lines)', $pipelineSourceContext)) : null,
                                    ])), is_array($pipelineFiles) ? array_map(fn ($file) => is_array($file) ? (($file['path'] ?? 'Unknown file').' · '.($file['confidence'] ?? 0).'% match — '.($file['reason'] ?? 'Ranked from report context')) : (string) $file, $pipelineFiles) : []), 'raw' => '',
                                ],
                                [
                                    'number' => '06', 'icon' => '👤', 'title' => 'Smart developer routing',
                                    'state' => !empty($ai['assigned_to']) && $ai['assigned_to'] !== 'Unassigned' ? 'Assigned' : 'Manager review',
                                    'tone' => !empty($ai['assigned_to']) && $ai['assigned_to'] !== 'Unassigned' ? 'emerald' : 'amber',
                                    'summary' => !empty($ai['is_duplicate']) ? 'Duplicate reports are held for manager review instead of being assigned automatically.' : (!empty($ai['assigned_to']) && $ai['assigned_to'] !== 'Unassigned' ? 'Selected the available developer whose skills, feature experience and workload best fit this report.' : 'No eligible developer was selected; manager review is needed.'),
                                    'facts' => array_values(array_filter([
                                        'Recommended developer: '.($ai['assigned_to'] ?? 'Unassigned'),
                                        !empty($pipelineCode['assignment_reason']) ? 'Why: '.$pipelineCode['assignment_reason'] : null,
                                    ])), 'raw' => '',
                                ],
                                [
                                    'number' => '07', 'icon' => '💡', 'title' => 'Code fix guidance',
                                    'state' => !empty($pipelineLlm) && empty($pipelineLlm['error']) ? 'AI analysis ready' : (!empty($pipelineFixes) ? 'Suggestions ready' : 'No fix suggestion'),
                                    'tone' => !empty($pipelineLlm) && empty($pipelineLlm['error']) ? 'violet' : (!empty($pipelineFixes) ? 'amber' : 'slate'),
                                    'summary' => !empty($pipelineLlm['root_cause']) ? $pipelineLlm['root_cause'] : (!empty($pipelineFixes) ? 'Rule-based guidance is available. Review it with the developer before changing code.' : 'The agent could not produce a specific fix for this report.'),
                                    'facts' => array_merge(array_values(array_filter([
                                        isset($pipelineLlm['confidence']) ? 'LLM confidence: '.$pipelineLlm['confidence'].'%' : (!empty($pipelineLlm['error']) ? 'LLM unavailable: '.$pipelineLlm['error'] : 'LLM root-cause analysis: not available; showing rule-based guidance where possible'),
                                    ])), is_array($pipelineLlm['evidence'] ?? null) ? array_map(fn ($item) => 'Evidence: '.(is_array($item) ? json_encode($item) : (string) $item), $pipelineLlm['evidence']) : [], is_array($pipelineFixes) ? array_map(fn ($item) => is_array($item) ? (($item['title'] ?? 'Suggestion').': '.($item['detail'] ?? 'Review with the developer.')) : (string) $item, $pipelineFixes) : []), 'raw' => '',
                                ],
                            ];
                        @endphp
                        <section id="ai-pipeline-details" class="mb-5 overflow-hidden rounded-2xl border border-indigo-200 bg-white shadow-sm" aria-labelledby="ai-pipeline-title">
                            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-indigo-100 bg-gradient-to-r from-indigo-50 to-white px-5 py-4">
                                <div><h3 id="ai-pipeline-title" class="text-base font-extrabold text-slate-900">How the AI analyzed this report</h3><p class="mt-1 text-xs text-slate-600">Seven steps, with the result and evidence from this run. Expand any step for details.</p></div>
                                <a href="/workspace" class="inline-flex min-h-10 items-center rounded-xl bg-indigo-600 px-4 text-xs font-bold text-white hover:bg-indigo-500">View on Kanban →</a>
                            </div>
                            <div class="divide-y divide-slate-100 px-4 sm:px-5">
                                @foreach($pipelineSteps as $step)
                                    @php
                                        $stepToneClasses = [
                                            'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'amber' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            'rose' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'violet' => 'bg-violet-50 text-violet-700 border-violet-200',
                                            'slate' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        ];
                                    @endphp
                                    <details class="group py-3" @if($loop->first) open @endif>
                                        <summary class="flex cursor-pointer list-none items-start gap-3 marker:hidden [&::-webkit-details-marker]:hidden">
                                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-lg">{{ $step['icon'] }}</span>
                                            <span class="min-w-0 flex-1"><span class="flex flex-wrap items-center gap-2"><span class="text-[10px] font-bold tracking-wide text-slate-400">STEP {{ $step['number'] }}</span><span class="text-sm font-bold text-slate-900">{{ $step['title'] }}</span><span class="rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $stepToneClasses[$step['tone']] ?? $stepToneClasses['slate'] }}">{{ $step['state'] }}</span></span><span class="mt-1 block text-xs leading-5 text-slate-600">{{ $step['summary'] }}</span></span>
                                            <span class="mt-2 text-xs font-bold text-indigo-600 group-open:rotate-180">⌄</span>
                                        </summary>
                                        <div class="ml-12 mt-3 space-y-2 border-l-2 border-slate-100 pl-4">
                                            @if(!empty($step['facts']))
                                                <ul class="space-y-1.5 text-xs leading-5 text-slate-700">
                                                    @foreach($step['facts'] as $fact)<li class="flex gap-2"><span class="text-indigo-400">•</span><span>{{ $fact }}</span></li>@endforeach
                                                </ul>
                                            @endif
                                            @if(!empty($step['raw']))
                                                <details class="rounded-lg border border-slate-200 bg-slate-50 p-2.5"><summary class="cursor-pointer text-[11px] font-semibold text-slate-700">Show OCR text excerpt</summary><pre class="mt-2 max-h-40 overflow-auto whitespace-pre-wrap text-[11px] leading-5 text-slate-600">{{ \Illuminate\Support\Str::limit($step['raw'], 1800) }}</pre></details>
                                            @endif
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                            <p class="border-t border-slate-100 bg-slate-50 px-5 py-3 text-[11px] leading-5 text-slate-500">AI results are recommendations for review. Syntax checks cannot prove a program is logically correct, and suggested code is never applied automatically.</p>
                        </section>
                        <!-- State 2: Result State (4 Human-in-the-Loop Sections) -->
                        <div class="space-y-4 animate-fade-in flex-1">
                            
                            <!-- Section A: Triage summary -->
                            <div class="rounded-2xl border border-indigo-200 bg-gradient-to-r from-indigo-50/60 to-purple-50/60 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-lg bg-blue-50 border border-blue-200 px-2.5 py-1 text-xs font-bold text-[#0052cc] font-mono-code">
                                            Language: {{ $ai['code_help']['language'] ?? 'Auto (not detected)' }}
                                        </span>
                                        <span class="rounded-lg bg-purple-50 border border-purple-200 px-2.5 py-1 text-xs font-bold text-purple-700">
                                            Feature: {{ $ai['code_help']['feature'] ?? 'General' }}
                                        </span>
                                        <span class="rounded-lg border px-2.5 py-1 text-xs font-bold {{ ($ai['status'] ?? 'Open') === 'Duplicate' ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }}">
                                            Status: {{ $ai['status'] ?? 'Open' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="replayCurrentTriage()" class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-white hover:bg-indigo-50 px-2.5 py-1 text-[11px] font-bold text-indigo-700 shadow-2xs transition">
                                            <span>▷</span>
                                            <span>Replay AI Triage Pipeline</span>
                                        </button>
                                        <span class="text-[10px] font-semibold text-indigo-800">Rule-based estimate</span>
                                    </div>
                                </div>
                                @if(!empty($ai['is_duplicate']))
                                    <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-900">
                                        <p class="font-bold">Possible duplicate · {{ $ai['similarity_score'] ?? 0 }}% text similarity</p>
                                        <p class="mt-1">Matched existing report
                                            @if(!empty($ai['duplicate_of']))
                                                <a class="font-bold underline" href="/workspace#bug-{{ $ai['duplicate_of'] }}">#ABT-{{ $ai['duplicate_of'] }}</a>.
                                            @else
                                                in this project.
                                            @endif
                                            Please compare the reports before closing either one.
                                        </p>
                                    </div>
                                @else
                                    <p class="mt-3 text-xs text-indigo-900">No duplicate found in the current project reports ({{ $ai['similarity_score'] ?? 0 }}% best text match).</p>
                                @endif
                            </div>

                            <!-- Section B: Root Cause & Error Diagnosis Box -->
                            @php
                                $syntaxStatus = $ai['code_help']['syntax_status'] ?? 'unknown';
                            @endphp
                            @php
                                $syntaxStatusLabel = ['passed' => 'Check passed', 'failed' => 'Syntax errors', 'timeout' => 'Check timed out', 'not_available' => 'Checker unavailable', 'not_run' => 'Check not run'][$syntaxStatus] ?? 'Check status unknown';
                            @endphp
                            @php
                                $syntaxStatusClass = ['passed' => 'bg-emerald-100 text-emerald-800', 'failed' => 'bg-red-100 text-red-800', 'timeout' => 'bg-amber-100 text-amber-800', 'not_available' => 'bg-amber-100 text-amber-800', 'not_run' => 'bg-slate-100 text-slate-700'][$syntaxStatus] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <div class="rounded-2xl border-2 border-rose-200 bg-rose-50/50 p-4 text-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2 text-indigo-900 font-bold">
                                        <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
                                        <span>{{ $syntaxStatus === 'not_run' ? 'Black-Box NLP Analysis' : 'Code Validation Result' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="rounded px-1.5 py-0.5 text-[10px] font-bold {{ $syntaxStatusClass }}">{{ $syntaxStatus === 'not_run' ? 'NLP Verified' : $syntaxStatusLabel }}</span>
                                        <span class="rounded bg-rose-100 px-1.5 py-0.5 text-[10px] font-bold text-rose-800">{{ $ai['priority'] ?? 'Medium' }} Priority</span>
                                    </div>
                                </div>
                                <p class="font-mono-code text-xs font-bold text-indigo-950 mt-1">
                                    ● {{ $syntaxStatus === 'not_run' ? 'Natural-Language & Symptoms Analysis Completed' : ($ai['code_help']['error_name'] ?? 'Analysis complete') }}
                                </p>
                                <p class="text-[11px] text-slate-700 mt-1">
                                    Evidence: {{ $ai['code_help']['attachment_note'] ?? 'No attachment provided.' }}
                                </p>
                                @if(!empty($ai['code_help']['llm_analysis']) && empty($ai['code_help']['llm_analysis']['error']))
                                    @php
                                        $llm = $ai['code_help']['llm_analysis'];
                                    @endphp
                                    <div class="mt-3 rounded-xl border border-violet-200 bg-violet-50 p-3 text-violet-950">
                                        <div class="flex flex-wrap items-center justify-between gap-2"><p class="font-bold">Groq AI code analysis</p><span class="rounded-full bg-white px-2 py-1 text-[10px] font-bold">Confidence {{ $llm['confidence'] }}%</span></div>
                                        <p class="mt-2 text-xs"><strong>Likely cause:</strong> {{ $llm['root_cause'] }}</p>
                                        @if(!empty($llm['evidence']))<ul class="mt-2 list-disc space-y-1 pl-5 text-[11px]">@foreach($llm['evidence'] as $evidence)<li>{{ $evidence }}</li>@endforeach</ul>@endif
                                        @if(!empty($llm['affected_locations']))
                                            <div class="mt-2 space-y-1">
                                                @foreach($llm['affected_locations'] as $location)
                                                    @php
                                                        $locationCandidate = collect($ai['suspected_files'] ?? [])->firstWhere('path', $location['path']);
                                                    @endphp
                                                    <p class="text-[11px]">📍 <a class="font-semibold underline" target="_blank" rel="noopener" href="{{ $locationCandidate['github_url'] ?? '#' }}#L{{ $location['line'] }}">{{ $location['path'] }}:{{ $location['line'] }}</a> — {{ $location['reason'] }}</p>
                                                @endforeach
                                            </div>
                                        @endif
                                        <p class="mt-2 text-[10px] text-violet-700">AI suggestions are unverified. Review code and tests before applying.</p>
                                    </div>
                                @elseif(!empty($ai['code_help']['llm_analysis']['error']))
                                    <p class="mt-2 text-[10px] text-amber-700">LLM analysis unavailable; rule-based triage was used. {{ $ai['code_help']['llm_analysis']['error'] }}</p>
                                @endif
                                @if(!empty($ai['code_help']['github_source_context']))<p class="mt-2 text-[10px] text-slate-500">Source retrieved from: {{ collect($ai['code_help']['github_source_context'])->pluck('path')->implode(', ') }}</p>@endif
                                @if(!empty($ai['code_help']['screenshot_vision']['stack_traces']))
                                    <details class="mt-2 rounded-xl border border-indigo-200 bg-white p-2.5">
                                        <summary class="cursor-pointer font-bold text-indigo-700 text-[11px] flex items-center gap-1.5 select-none">
                                            <span>📸</span>
                                            <span>View Extracted Screenshot Crash Lines</span>
                                        </summary>
                                        <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-950 p-2 font-mono text-[10px] text-emerald-400">@foreach($ai['code_help']['screenshot_vision']['stack_traces'] as $stLine){{ $stLine }}
@endforeach</pre>
                                    </details>
                                @endif
                                <p class="text-[11px] text-slate-600 mt-1">
                                    {{ $syntaxStatus === 'not_run' ? 'Issue classified, duplicate-checked, and assigned to the domain engineer without requiring source code from the tester.' : ($ai['code_help']['analysis_scope'] ?? 'Static inspection completed.') }}
                                </p>
                                @if(!empty($ai['code_help']['syntax_checker']))
                                    <p class="text-[11px] text-rose-700 mt-1">Checker: {{ $ai['code_help']['syntax_checker'] }}</p>
                                @endif
                                @if(!empty($ai['code_help']['description_note']))
                                    <p class="text-[11px] text-rose-700 mt-1">{{ $ai['code_help']['description_note'] }}</p>
                                @endif
                                <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-amber-950">
                                    <p class="font-bold">Possible fix suggestions · review before applying</p>
                                    @if(!empty($ai['code_help']['fix_suggestions']))
                                        <ul class="mt-2 space-y-2">
                                            @foreach($ai['code_help']['fix_suggestions'] as $suggestion)
                                                <li class="rounded-lg border border-amber-200 bg-white/80 p-2.5">
                                                    <p class="font-bold">{{ $suggestion['title'] }}</p>
                                                    <p class="mt-1">{{ $suggestion['detail'] }}</p>
                                                    @if(!empty($suggestion['proposed_code']))
                                                        <details class="mt-2">
                                                            <summary class="cursor-pointer font-semibold">Preview proposed edit (not applied)</summary>
                                                            <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-950 p-2.5 font-mono-code text-[11px] leading-5 text-emerald-300">{{ $suggestion['proposed_code'] }}</pre>
                                                        </details>
                                                    @elseif(!empty($suggestion['example_code']))
                                                        <details class="mt-2">
                                                            <summary class="cursor-pointer font-semibold">View example guard (not applied)</summary>
                                                            <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-950 p-2.5 font-mono-code text-[11px] leading-5 text-emerald-300">{{ $suggestion['example_code'] }}</pre>
                                                        </details>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-1">No specific fix suggestion was identified by the current rules. The submitted code remains unchanged.</p>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($ai['code_help']['suspected_files']) || !empty($ai['suspected_files']))
                                @php
                                    $suspectedList = $ai['code_help']['suspected_files'] ?? ($ai['suspected_files'] ?? []);
                                @endphp
                                <!-- Section: AI Suspected Culprit Files in Repository -->
                                <div class="rounded-2xl border-2 border-indigo-200 bg-white p-4 text-xs shadow-xs">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2 text-indigo-950 font-bold">
                                            <span class="flex h-5 w-5 items-center justify-center rounded-lg bg-indigo-600 text-white text-[11px]">🐙</span>
                                            <span>AI Suspected Repository Culprit Files</span>
                                        </div>
                                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                                            GitHub Linked
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 mb-2.5">
                                        The AI agent analyzed the bug report and repository and found these files most likely to contain the error or relevant code:
                                    </p>

                                    <div class="space-y-2">
                                        @foreach($suspectedList as $sFile)
                                            <div class="flex items-center justify-between gap-2 rounded-xl border border-slate-200 bg-[#F8FAFC] p-2.5 hover:border-indigo-400 transition">
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-mono-code text-xs font-bold text-slate-900 truncate">📄 {{ $sFile['path'] }}</span>
                                                        <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.2 text-[10px] font-bold font-mono-code">{{ $sFile['confidence'] }}% Match</span>
                                                    </div>
                                                    <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ $sFile['reason'] }}</p>
                                                </div>

                                                @php
                                                    $baseRepoSlug = !empty($projectGithubRepo) ? preg_replace('#^https?://github\.com/#', '', rtrim($projectGithubRepo, '/')) : 'MuarifHasnat/Auto-Bug-Traige';
                                                    $baseRepoSlug = preg_replace('/\.git$/', '', $baseRepoSlug);
                                                    $fPathRaw = $sFile['path'] ?? (is_string($sFile) ? $sFile : '');
                                                    $cleanP = ltrim(str_replace('\\', '/', $fPathRaw), '/');
                                                    $encodedP = implode('/', array_map('rawurlencode', explode('/', $cleanP)));
                                                    $fBranch = !empty($sFile['branch']) ? $sFile['branch'] : 'HEAD';
                                                    $ghUrl = !empty($sFile['github_url']) ? $sFile['github_url'] : ('https://github.com/' . $baseRepoSlug . '/blob/' . rawurlencode($fBranch) . '/' . $encodedP);
                                                @endphp
                                                <a href="{{ $ghUrl }}" target="_blank" rel="noopener noreferrer" 
                                                   class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-2.5 py-1 text-[11px] font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition shrink-0 shadow-2xs">
                                                    <span>View on GitHub</span>
                                                    <span class="text-xs">↗</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Section C: Smart Assignment Routing Card with Human-in-the-Loop Override -->
                            <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4 text-xs">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold text-xs uppercase">
                                            {{ strtoupper(substr($ai['assigned_to'] ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-[#64748B] block">Assigned Engineer</span>
                                            <span class="font-bold text-sm text-[#0F172A]">{{ $ai['assigned_to'] ?? 'Unassigned' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700">
                                            Skill Matched
                                        </span>
                                        <a href="/workspace" class="rounded-lg border border-[#CBD5E1] bg-white px-2 py-0.5 text-[10px] font-bold text-indigo-600 hover:bg-indigo-50">
                                            ✎ Change in Board
                                        </a>
                                    </div>
                                </div>
                                <p class="text-[11px] text-[#475569] bg-white border border-[#E2E8F0] p-2.5 rounded-xl">
                                    {{ $ai['code_help']['assignment_reason'] ?? 'Selected due to matching technical skills and active queue balancing.' }}
                                </p>
                            </div>

                            <!-- Section D: Interactive Fix Steps & Code Diff View Switcher -->
                            <div class="space-y-3 pt-1">
                                @if(!empty($ai['code_help']['steps']))
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-1.5">Interactive Resolution Checklist:</h4>
                                        <ul class="space-y-1.5 text-xs text-[#0F172A]">
                                            @foreach($ai['code_help']['steps'] as $idx => $step)
                                                <li class="flex items-start gap-2 bg-[#F8FAFC] p-2 rounded-xl border border-[#E2E8F0]">
                                                    <input type="checkbox" id="ai-step-{{ $idx }}" class="mt-0.5 h-3.5 w-3.5 rounded border-[#CBD5E1] text-indigo-600 focus:ring-indigo-500 cursor-pointer" onchange="this.parentElement.classList.toggle('opacity-50', this.checked)">
                                                    <label for="ai-step-{{ $idx }}" class="cursor-pointer font-medium leading-relaxed">{{ $step }}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(!empty(trim($ai['code_help']['fixed_code'] ?? '')))
                                <!-- Code View Switcher: Final Code vs Diff -->
                                <div class="border border-[#E2E8F0] rounded-2xl p-3.5 bg-white">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2 text-xs font-bold">
                                            <button type="button" onclick="switchStudioCode('final')" id="btnStudioFinal" class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                [ Submitted Code ]
                                            </button>
                                            @if(!empty($ai['code_help']['diff']))
                                                <button type="button" onclick="switchStudioCode('diff')" id="btnStudioDiff" class="px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50">
                                                    [ Before vs After (Diff) ]
                                                </button>
                                            @endif
                                        </div>

                                        <button type="button" onclick="copyStudioFix()" id="copyStudioBtn" 
                                                class="rounded-lg border border-[#CBD5E1] bg-white px-2.5 py-1 text-xs font-bold text-indigo-600 hover:bg-[#F1F5F9] transition">
                                                📋 Copy Submitted Code
                                        </button>
                                    </div>

                                    <div id="studioFinalView">
                                        <p class="mb-2 text-[11px] text-slate-600">This is the original submitted snippet. Suggestions above are review-only; the code is never changed automatically.</p>
                                        <textarea id="studioFixedCode" readonly spellcheck="false" class="w-full min-h-[160px] rounded-xl bg-slate-950 p-3.5 font-mono-code text-xs leading-5 text-emerald-400 border border-slate-800 outline-none">{{ $ai['code_help']['fixed_code'] ?? '// No submitted code provided.' }}</textarea>
                                    </div>

                                    <div id="studioDiffView" class="hidden">
                                        <textarea readonly spellcheck="false" class="w-full min-h-[160px] rounded-xl bg-slate-950 p-3.5 font-mono-code text-xs leading-5 text-indigo-300 border border-slate-800 outline-none">{{ $ai['code_help']['diff'] ?? '// No diff changes.' }}</textarea>
                                    </div>
                                </div>
                                @endif

                                <div class="pt-1 text-right">
                                    <a href="/workspace" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:underline">
                                        <span>Go to Kanban Workspace ➔</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- State 1: Empty / Ready State (AI Autonomous Engine Visual Blueprint) -->
                        <div class="flex-1 flex flex-col justify-start items-center py-4 text-center space-y-4">
                            <div class="flex items-center gap-2">
                                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-600 text-white text-lg font-bold shadow-sm">
                                    ⚡
                                </span>
                                <div class="text-left">
                                    <h3 class="text-sm font-extrabold text-[#0F172A] flex items-center gap-1.5">
                                        <span>Autonomous AI Triage Architecture</span>
                                        <span class="rounded bg-emerald-50 text-emerald-700 border border-emerald-200 px-1.5 py-0.2 text-[9px] font-mono-code font-bold">5-Phase Pipeline</span>
                                    </h3>
                                    <p class="text-[11px] text-[#64748B]">How the agent converts black-box symptom reports into actionable developer assignments in &lt; 2.5s:</p>
                                </div>
                            </div>

                            <!-- 5-Phase Interactive Visual Blueprint Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5 w-full text-left">
                                
                                <!-- Phase 1: Vision Perception -->
                                <div class="rounded-2xl border border-indigo-100 bg-indigo-50/40 p-3 hover:border-indigo-300 transition">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs font-bold text-indigo-900 flex items-center gap-1">
                                            <span>📸</span> <span>1. Vision Perception</span>
                                        </span>
                                        <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.2 text-[8px] font-mono-code font-bold">Groq Qwen Vision</span>
                                    </div>
                                    <p class="text-[10px] text-slate-600 leading-relaxed">
                                        Extracts stack traces, line numbers & crash dialogs directly from screenshots without needing access to user code.
                                    </p>
                                </div>

                                <!-- Phase 2: Multi-Branch Repo Traversal -->
                                <div class="rounded-2xl border border-sky-100 bg-sky-50/40 p-3 hover:border-sky-300 transition">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs font-bold text-sky-900 flex items-center gap-1">
                                            <span>🐙</span> <span>2. GitHub Traversal</span>
                                        </span>
                                        <span class="rounded bg-sky-100 text-sky-800 px-1.5 py-0.2 text-[8px] font-mono-code font-bold">Multi-Branch</span>
                                    </div>
                                    <p class="text-[10px] text-slate-600 leading-relaxed">
                                        Traverses target repository branch (<code class="font-mono text-[9px]">main</code>, <code class="font-mono text-[9px]">develop</code>, etc.) without API rate limits to locate suspected culprit files.
                                    </p>
                                </div>

                                <!-- Phase 3: TF-IDF Similarity -->
                                <div class="rounded-2xl border border-amber-100 bg-amber-50/40 p-3 hover:border-amber-300 transition">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs font-bold text-amber-900 flex items-center gap-1">
                                            <span>🔍</span> <span>3. Vector Dedupe</span>
                                        </span>
                                        <span class="rounded bg-amber-100 text-amber-800 px-1.5 py-0.2 text-[8px] font-mono-code font-bold">Cosine Similarity</span>
                                    </div>
                                    <p class="text-[10px] text-slate-600 leading-relaxed">
                                        Generates n-gram TF-IDF vectors to compare symptom overlap against all past bugs to prevent duplicate work.
                                    </p>
                                </div>

                                <!-- Phase 4: Skill-Based Routing -->
                                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/40 p-3 hover:border-emerald-300 transition">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs font-bold text-emerald-900 flex items-center gap-1">
                                            <span>👤</span> <span>4. Smart Dev Routing</span>
                                        </span>
                                        <span class="rounded bg-emerald-100 text-emerald-800 px-1.5 py-0.2 text-[8px] font-mono-code font-bold">Capacity Aware</span>
                                    </div>
                                    <p class="text-[10px] text-slate-600 leading-relaxed">
                                        Scores engineers by programming language, feature history, and live task queue to pick the best-suited owner.
                                    </p>
                                </div>

                                <!-- Phase 5: Groq Deep Reasoning -->
                                <div class="rounded-2xl border border-purple-100 bg-purple-50/40 p-3 hover:border-purple-300 transition col-span-1 sm:col-span-2 lg:col-span-2">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs font-bold text-purple-900 flex items-center gap-1">
                                            <span>🧠</span> <span>5. Groq Deep Diagnosis & Fix</span>
                                        </span>
                                        <span class="rounded bg-purple-100 text-purple-800 px-1.5 py-0.2 text-[8px] font-mono-code font-bold">openai/gpt-oss-120b</span>
                                    </div>
                                    <p class="text-[10px] text-slate-600 leading-relaxed">
                                        Dissects culprit code AST patterns and synthesizes clear root cause explanation, interactive checklist, and fix suggestions.
                                    </p>
                                </div>

                            </div>

                            <!-- 1-Click Interactive Sample Bug Loaders -->
                            <div class="w-full rounded-2xl border border-slate-200 bg-[#F8FAFC] p-3 text-left">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[11px] font-bold text-[#0F172A] flex items-center gap-1.5">
                                        <span>⚡</span> <span>Try Agent with 1-Click Sample Bug:</span>
                                    </span>
                                    <span class="text-[10px] text-[#64748B]">Auto-fills form</span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <button type="button" onclick="loadSampleBug('payment')" class="rounded-xl border border-indigo-200 bg-white hover:bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 transition shadow-2xs flex items-center gap-1">
                                        <span>💳</span> <span>Payment NPE Crash</span>
                                    </button>
                                    <button type="button" onclick="loadSampleBug('auth')" class="rounded-xl border border-rose-200 bg-white hover:bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700 transition shadow-2xs flex items-center gap-1">
                                        <span>🔐</span> <span>Auth Token 401 Loop</span>
                                    </button>
                                    <button type="button" onclick="loadSampleBug('ui')" class="rounded-xl border border-blue-200 bg-white hover:bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 transition shadow-2xs flex items-center gap-1">
                                        <span>📱</span> <span>UI Modal Overflow</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endif
                </section>
            </div>
            @endif
                </div>
            </details>
            @endif
        </div>
    </main>
</div>

<script>
    async function copyCloneCommand(button) {
        const command = button.dataset.cloneCommand;
        let copied = false;
        try {
            await navigator.clipboard.writeText(command);
            copied = true;
        } catch (error) {
            const input = document.createElement('textarea');
            input.value = command;
            input.style.position = 'fixed';
            input.style.left = '-9999px';
            document.body.appendChild(input);
            input.focus();
            input.select();
            try {
                copied = document.execCommand('copy');
            } catch (fallbackError) {
                copied = false;
            }
            input.remove();
        }
        if (!copied) {
            window.prompt('Copy this command and run it in Terminal:', command);
            return;
        }
        const label = button.querySelector('[data-clone-label]');
        if (!label) return;
        label.textContent = 'Copied — run in Terminal';
        window.setTimeout(() => { label.textContent = 'Copy git clone command'; }, 3000);
    }

    function openTriageForm() {
        const workflow = document.getElementById('triageWorkflow');
        if (workflow) workflow.open = true;
        document.getElementById('triageForm')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // 1-Click Interactive Sample Bug Loader
    function loadSampleBug(type) {
        const titleInput = document.getElementById('bugTitle');
        const labelInput = document.querySelector('input[name="labels"]');
        if (type === 'payment') {
            if (titleInput) titleInput.value = 'Borrow Equipment Button crashes with NullPointerException';
            if (labelInput) labelInput.value = 'inventory, equipment, crash';
        } else if (type === 'auth') {
            if (titleInput) titleInput.value = 'User Session 401 Unauthorized after Token Refresh';
            if (labelInput) labelInput.value = 'auth, jwt, security';
        } else if (type === 'ui') {
            if (titleInput) titleInput.value = 'Checkout modal overlaps and freezes on mobile screen';
            if (labelInput) labelInput.value = 'ui, frontend, mobile';
        }
        applyTemplate(type);
    }

    // Guided tester report helpers
    function applyTemplate(type) {
        const title = document.getElementById('bugTitle');
        const desc = document.getElementById('bugDescription');
        const expected = document.getElementById('expectedResult');
        const templates = {
            ui: { title: 'UI element overlaps and freezes on mobile view', steps: ['Open the application on a mobile or narrow viewport', 'Click the navigation or action button', 'Observe the element overlapping content and the page freezing'], expected: 'The page should remain responsive and elements should fit the viewport.', actual: 'The UI element overlaps other content and the page becomes unresponsive.' },
            payment: { title: 'Checkout payment fails with a spinning loader', steps: ['Add an item to the cart and open checkout', 'Enter valid payment details', 'Select Pay Now and wait for the result'], expected: 'Payment should complete and show the order confirmation.', actual: 'The loader spins indefinitely and the payment gateway reports a timeout.' },
            auth: { title: 'Login rejects valid credentials', steps: ['Open the login page', 'Enter a registered email and password', 'Submit the form and observe the result'], expected: 'The user should sign in and reach the workspace.', actual: 'The page reports that the session was rejected and returns to sign in.' }
        };
        const template = templates[type];
        if (!template || !desc || !expected) return;
        title.value = title.value || template.title;
        desc.value = template.actual;
        expected.value = template.expected;
        setReproductionSteps(template.steps);
        desc.focus();
    }

    function updateStepNumbers() {
        document.querySelectorAll('#reproductionSteps li').forEach((row, index) => {
            row.querySelector('.step-number').textContent = index + 1;
            row.querySelector('input').id = `reproductionStep${index + 1}`;
            row.querySelector('button').setAttribute('aria-label', `Remove step ${index + 1}`);
        });
    }

    function addReproductionStep(value = '') {
        const list = document.getElementById('reproductionSteps');
        if (!list || list.children.length >= 12) return;
        const row = document.createElement('li');
        row.className = 'flex items-center gap-2';
        row.innerHTML = '<span class="step-number flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-[10px] font-bold text-indigo-700"></span><input name="reproduction_steps[]" required maxlength="500" placeholder="Describe the next action" class="reproduction-step min-w-0 flex-1 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs focus:border-indigo-600 focus:bg-white focus:outline-none"><button type="button" aria-label="Remove reproduction step" class="rounded-lg px-2 py-1 text-xs font-bold text-slate-400 hover:bg-rose-50 hover:text-rose-600">×</button>';
        row.querySelector('input').value = value;
        row.querySelector('button').addEventListener('click', () => removeReproductionStep(row.querySelector('button')));
        list.appendChild(row);
        updateStepNumbers();
        row.querySelector('input').focus();
    }

    function removeReproductionStep(button) {
        const list = document.getElementById('reproductionSteps');
        if (!list || list.children.length <= 1) {
            const input = list?.querySelector('input');
            if (input) input.value = '';
            return;
        }
        button.closest('li')?.remove();
        updateStepNumbers();
    }

    function setReproductionSteps(steps) {
        const list = document.getElementById('reproductionSteps');
        if (!list) return;
        list.replaceChildren();
        steps.forEach((step, index) => {
            addReproductionStep(step);
            if (index === 0) list.querySelector('input').required = true;
        });
        updateStepNumbers();
    }

    // Screenshot File Upload Handling
    let attachedScreenshotFile = null;

    function handleDropzoneClick(e) {
        if (e.target.closest('button')) return;
        if (attachedScreenshotFile) return;
        document.getElementById('screenshotInput')?.click();
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('screenshotDropzone')?.classList.add('border-indigo-500', 'bg-indigo-50');
    }

    function handleDragEnter(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('screenshotDropzone')?.classList.add('border-indigo-500', 'bg-indigo-50');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        const zone = document.getElementById('screenshotDropzone');
        if (zone && !zone.contains(e.relatedTarget)) {
            zone.classList.remove('border-indigo-500', 'bg-indigo-50');
        }
    }

    function handleScreenshotDrop(event) {
        event.preventDefault();
        event.stopPropagation();
        const zone = document.getElementById('screenshotDropzone');
        if (zone) zone.classList.remove('border-indigo-500', 'bg-indigo-50');
        const file = event.dataTransfer?.files?.[0];
        if (file) {
            processSelectedScreenshot(file);
        }
    }

    function handleScreenshotSelected(input) {
        const file = input.files && input.files[0];
        if (file) {
            processSelectedScreenshot(file);
        }
    }

    function processSelectedScreenshot(file) {
        const error = document.getElementById('screenshotError');
        if (!file) return;

        const extension = (file.name.split('.').pop() || '').toLowerCase();
        const mimeType = (file.type || '').toLowerCase();
        const validExts = ['png', 'jpg', 'jpeg', 'webp', 'jfif', 'gif', 'bmp'];
        const isImage = mimeType.startsWith('image/') || validExts.includes(extension);

        if (!isImage) {
            if (error) {
                error.textContent = 'Please choose a valid image file (PNG, JPG, JPEG, WEBP).';
                error.classList.remove('hidden');
            }
            clearScreenshot();
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            if (error) {
                error.textContent = 'Screenshot must be 10 MB or smaller.';
                error.classList.remove('hidden');
            }
            clearScreenshot();
            return;
        }

        if (error) {
            error.textContent = '';
            error.classList.add('hidden');
        }

        attachedScreenshotFile = file;

        try {
            const input = document.getElementById('screenshotInput');
            if (input) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
            }
        } catch (_) {}

        renderScreenshotPreview(file);
    }

    // Render Preview Helper
    function renderScreenshotPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('screenshotPreview');
            if (preview) preview.src = e.target.result;
            const fileNameEl = document.getElementById('screenshotFileName');
            if (fileNameEl) fileNameEl.innerText = '✓ ' + file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
            document.getElementById('screenshotPlaceholder')?.classList.add('hidden');
            document.getElementById('screenshotPreviewWrapper')?.classList.remove('hidden');
            document.getElementById('screenshotDropzone')?.classList.add('border-emerald-500', 'bg-emerald-50/30');
        };
        reader.onerror = function() {
            const error = document.getElementById('screenshotError');
            if (error) {
                error.textContent = 'Could not preview this image. Choose it again or use another file.';
                error.classList.remove('hidden');
            }
        };
        reader.readAsDataURL(file);
    }

    // Clear Screenshot
    function clearScreenshot(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        attachedScreenshotFile = null;
        const input = document.getElementById('screenshotInput');
        if (input) input.value = '';
        const error = document.getElementById('screenshotError');
        if (error) {
            error.textContent = '';
            error.classList.add('hidden');
        }
        const preview = document.getElementById('screenshotPreview');
        if (preview) preview.src = '';
        document.getElementById('screenshotPlaceholder')?.classList.remove('hidden');
        document.getElementById('screenshotPreviewWrapper')?.classList.add('hidden');
        document.getElementById('screenshotDropzone')?.classList.remove('border-emerald-500', 'bg-emerald-50/30');
    }

    // Clipboard Screenshot Paste (Ctrl + V)
    window.addEventListener('paste', function(e) {
        const items = e.clipboardData?.items;
        if (!items) return;
        for (let i = 0; i < items.length; i++) {
            if (items[i].type && items[i].type.startsWith('image/')) {
                const blob = items[i].getAsFile();
                if (!blob) continue;
                const mimeType = (blob.type || '').toLowerCase();
                const ext = mimeType === 'image/jpeg' ? 'jpg' : (mimeType.split('/')[1] || 'png');
                const fileName = `pasted-screenshot-${Date.now()}.${ext}`;
                const file = new File([blob], fileName, { type: blob.type });
                processSelectedScreenshot(file);
                document.getElementById('screenshotDropzone')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                break;
            }
        }
    });

    // File selected display
    function handleFileSelected(input, statusId) {
        const status = document.getElementById(statusId);
        if (input.files && input.files[0]) {
            status.innerText = '✓ ' + input.files[0].name;
            status.parentElement.classList.add('border-indigo-500', 'bg-indigo-50/50');
        }
    }

    // Keyboard Shortcut (Ctrl + Enter to Submit)
    window.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            const form = document.getElementById('triageForm');
            if (form) {
                e.preventDefault();
                handleTriageSubmit(e);
            }
        }
    });

    // Studio Code View Switcher
    function switchStudioCode(view) {
        if (view === 'final') {
            document.getElementById('studioFinalView').classList.remove('hidden');
            document.getElementById('studioDiffView').classList.add('hidden');
            document.getElementById('btnStudioFinal').className = 'px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200';
            document.getElementById('btnStudioDiff').className = 'px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50';
        } else {
            document.getElementById('studioFinalView').classList.add('hidden');
            document.getElementById('studioDiffView').classList.remove('hidden');
            document.getElementById('btnStudioDiff').className = 'px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200';
            document.getElementById('btnStudioFinal').className = 'px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50';
        }
    }

    function copyStudioFix() {
        const code = document.getElementById('studioFixedCode').value;
        navigator.clipboard.writeText(code);
        const btn = document.getElementById('copyStudioBtn');
        btn.innerText = '✓ Copied to Clipboard!';
        setTimeout(() => btn.innerText = '📋 Copy Submitted Code', 2000);
    }

    // ==========================================
    // Autonomous AI Triage Live Stepper & Stepper Logic
    // ==========================================
    const projectBugs = @json(collect($pastBugs ?? $bugs)->map(fn($b) => ['id' => $b->id, 'title' => $b->title, 'description' => $b->description ?? '']));

    // Live TF-IDF Vectorizer & Cosine Similarity Engine
    function vectorizeText(text) {
        const stopWords = new Set(['the','is','at','which','on','and','a','an','in','to','for','of','with','as','by','or','it','this','that','from','be','are','was','were']);
        const tokens = (text || '').toLowerCase().match(/[a-z0-9+#_]+/g) || [];
        const counts = {};
        let total = 0;
        tokens.forEach(t => {
            if (!stopWords.has(t) && t.length > 1) {
                counts[t] = (counts[t] || 0) + 1;
                total++;
            }
        });
        const vec = {};
        if (total > 0) {
            for (const [k, v] of Object.entries(counts)) {
                vec[k] = v / total;
            }
        }
        return { vec, tokens: Object.keys(counts) };
    }

    function calculateCosine(vecA, vecB) {
        let dot = 0;
        let normA = 0;
        let normB = 0;
        for (const v of Object.values(vecA)) normA += v * v;
        for (const v of Object.values(vecB)) normB += v * v;
        if (!normA || !normB) return 0;
        for (const [k, v] of Object.entries(vecA)) {
            if (vecB[k]) dot += v * vecB[k];
        }
        return dot / (Math.sqrt(normA) * Math.sqrt(normB));
    }

    let dupTimeout = null;
    function checkDuplicateTitle(val) {
        clearTimeout(dupTimeout);
        dupTimeout = setTimeout(() => {
            const radarEl = document.getElementById('duplicateRadar');
            const clean = (val || '').trim();
            if (clean.length < 3 || !projectBugs.length) {
                if (radarEl) radarEl.classList.add('hidden');
                return;
            }

            const currentData = vectorizeText(clean);
            let bestScore = 0;
            let bestMatch = null;
            let commonKeywords = [];

            projectBugs.forEach(bug => {
                const bugText = `${bug.title} ${bug.description || ''}`;
                const candidateData = vectorizeText(bugText);
                const score = calculateCosine(currentData.vec, candidateData.vec);
                if (score > bestScore) {
                    bestScore = score;
                    bestMatch = bug;
                    commonKeywords = currentData.tokens.filter(t => candidateData.tokens.includes(t));
                }
            });

            const percent = Math.min(100, Math.round(bestScore * 100));
            if (radarEl) {
                radarEl.classList.remove('hidden');

                const scoreEl = document.getElementById('radarSimilarityScore');
                const barEl = document.getElementById('radarProgressBar');
                const badgeEl = document.getElementById('radarStatusBadge');
                const statusText = document.getElementById('radarStatusText');
                const matchCard = document.getElementById('radarMatchCard');
                const matchedKey = document.getElementById('radarMatchedKey');
                const matchedTitle = document.getElementById('radarMatchedTitle');
                const matchedLink = document.getElementById('radarMatchedLink');
                const keywordsList = document.getElementById('radarKeywordsList');
                const verdictText = document.getElementById('radarVerdictText');

                if (scoreEl) scoreEl.innerText = `${percent}%`;
                if (barEl) {
                    barEl.style.width = `${Math.max(6, percent)}%`;
                    if (percent >= 75) {
                        barEl.className = 'h-2 rounded-full transition-all duration-300 bg-gradient-to-r from-amber-500 to-rose-600 animate-pulse';
                    } else if (percent >= 50) {
                        barEl.className = 'h-2 rounded-full transition-all duration-300 bg-gradient-to-r from-amber-400 to-orange-500';
                    } else {
                        barEl.className = 'h-2 rounded-full transition-all duration-300 bg-gradient-to-r from-emerald-400 to-teal-500';
                    }
                }

                if (badgeEl && statusText) {
                    if (percent >= 75) {
                        badgeEl.className = 'rounded-full bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-0.5 text-[10px] font-bold font-mono flex items-center gap-1 shadow-2xs animate-bounce';
                        statusText.innerHTML = '⚠️ Potential Duplicate Detected';
                    } else if (percent >= 50) {
                        badgeEl.className = 'rounded-full bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 text-[10px] font-bold font-mono flex items-center gap-1 shadow-2xs';
                        statusText.innerHTML = '⚡ Related Topic Match';
                    } else {
                        badgeEl.className = 'rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-bold font-mono flex items-center gap-1 shadow-2xs';
                        statusText.innerHTML = '🛡️ Unique Issue';
                    }
                }

                if (bestMatch && percent > 0 && matchCard) {
                    matchCard.classList.remove('hidden');
                    if (matchedKey) matchedKey.innerText = `#ABT-${bestMatch.id}`;
                    if (matchedTitle) matchedTitle.innerText = bestMatch.title;
                    if (matchedLink) matchedLink.href = `/bugs/${bestMatch.id}`;
                    if (keywordsList) {
                        keywordsList.innerHTML = commonKeywords.length 
                            ? commonKeywords.slice(0, 5).map(k => `<span class="bg-indigo-50 border border-indigo-200 text-indigo-700 px-1.5 py-0.2 rounded font-mono text-[9px]">#${k}</span>`).join('')
                            : '<span class="text-slate-400 italic text-[10px]">Contextual semantic overlap</span>';
                    }
                    if (verdictText) {
                        if (percent >= 75) {
                            verdictText.innerHTML = `<span class="text-rose-700 font-bold">🛑 Threshold Exceeded (≥ 75%):</span> AI identifies this as an existing defect (#ABT-${bestMatch.id}).`;
                        } else {
                            verdictText.innerHTML = `<span class="text-emerald-700 font-bold">✓ Safe (${percent}% &lt; 75% limit):</span> Cosine distance under threshold; AI will classify as a new unique issue.`;
                        }
                    }
                } else if (matchCard) {
                    matchCard.classList.add('hidden');
                }
            }
        }, 120);
    }

    let triageTimer = null;
    let triageStartTime = null;

    function updateLiveScanner(stageNum, stageTitle, stageDesc, stageIcon, visionSensor, codeSensor, dupSensor, devSensor) {
        const pill = document.getElementById('liveStagePill');
        if (pill) pill.innerText = `STAGE ${stageNum} / 4`;
        const title = document.getElementById('liveStageTitle');
        if (title) title.innerText = stageTitle;
        const desc = document.getElementById('liveStageDesc');
        if (desc) desc.innerText = stageDesc;

        const sVision = document.getElementById('liveSensorVision');
        if (sVision && visionSensor) sVision.textContent = visionSensor;
        const sCode = document.getElementById('liveSensorCode');
        if (sCode && codeSensor) sCode.textContent = codeSensor;
        const sDup = document.getElementById('liveSensorDup');
        if (sDup && dupSensor) sDup.textContent = dupSensor;
        const sDev = document.getElementById('liveSensorDev');
        if (sDev && devSensor) sDev.textContent = devSensor;
    }

    function resetLiveTelemetry(title, desc, hasScreenshot, fileName) {
        const badge = document.getElementById('liveDetectionPhaseBadge');
        if (badge) badge.innerText = 'STAGE 1 / 5: NLP & OCR ACTIVE';

        const activeCardClass = 'rounded-xl border border-slate-900 bg-white p-4 space-y-3 transition-all shadow-2xs';
        const standbyCardClass = 'rounded-xl border border-slate-200 bg-slate-50/70 p-3.5 space-y-2 transition-all';

        // Reset Card 1 (NLP)
        const card1 = document.getElementById('liveCardNlp');
        if (card1) card1.className = activeCardClass + ' flex flex-col justify-between';
        const tag1 = document.getElementById('liveTagNlp');
        if (tag1) {
            tag1.className = 'rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide';
            tag1.innerText = 'PARSING TOKENS';
        }

        const tokenData = vectorizeText((title || '') + ' ' + (desc || ''));
        const tokens = tokenData.tokens.slice(0, 6);
        const tokensEl = document.getElementById('liveNlpTokens');
        if (tokensEl) {
            tokensEl.innerHTML = tokens.length
                ? tokens.map(t => `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 text-[11px] font-medium">#${escapeHtml(t)}</span>`).join('')
                : '<span class="text-slate-400 text-[11px]">Tokenizing symptoms...</span>';
        }
        const targetEl = document.getElementById('liveNlpTarget');
        if (targetEl) targetEl.innerText = `"${(title || 'Processing anomaly').substring(0, 48)}"`;

        // Configure Live Vision OCR Viewport
        const ocrWrapper = document.getElementById('liveOcrImageWrapper');
        const ocrImg = document.getElementById('liveOcrPreviewImg');
        const ocrEngine = document.getElementById('liveOcrEngineName');
        const ocrStatus = document.getElementById('liveOcrStatusTag');
        const ocrLog = document.getElementById('liveOcrStreamLog');

        if (hasScreenshot) {
            if (ocrWrapper) ocrWrapper.classList.remove('hidden');
            let previewSrc = document.getElementById('screenshotPreview')?.src || '';
            if (!previewSrc && attachedScreenshotFile) {
                try { previewSrc = URL.createObjectURL(attachedScreenshotFile); } catch (_) {}
            }
            if (ocrImg && previewSrc) ocrImg.src = previewSrc;
            if (ocrEngine) ocrEngine.innerText = 'Llama-3.2-11B Active';
            if (ocrStatus) {
                ocrStatus.className = 'text-emerald-400 font-semibold';
                ocrStatus.innerText = 'Scanning Image';
            }
            if (ocrLog) ocrLog.textContent = `[Status] Vision tensor initialized: ${fileName || 'screenshot.png'}`;
        } else {
            if (ocrWrapper) ocrWrapper.classList.add('hidden');
            if (ocrEngine) ocrEngine.innerText = 'Lexical NLP Core';
            if (ocrStatus) {
                ocrStatus.className = 'text-slate-400 font-semibold';
                ocrStatus.innerText = 'Text-Only Input';
            }
            if (ocrLog) ocrLog.textContent = '[Status] Lexical symptom vector parsed (No screenshot uploaded)';
        }

        // Reset Card 2 (GitHub)
        const card2 = document.getElementById('liveCardGh');
        if (card2) card2.className = standbyCardClass;
        const tag2 = document.getElementById('liveTagGh');
        if (tag2) {
            tag2.className = 'rounded-md bg-slate-200/80 text-slate-600 px-2 py-0.5 text-[10px] font-semibold';
            tag2.innerText = 'Standby';
        }
        const ghFiles = document.getElementById('liveGhScannedFile');
        if (ghFiles) ghFiles.innerHTML = '<span class="text-slate-500 text-xs">Awaiting tree traversal...</span>';
        const ghScore = document.getElementById('liveGhMatchScore');
        if (ghScore) ghScore.innerText = 'Pending scan';

        // Reset Card 3 (Vector Sim)
        const card3 = document.getElementById('liveCardVec');
        if (card3) card3.className = standbyCardClass;
        const tag3 = document.getElementById('liveTagVec');
        if (tag3) {
            tag3.className = 'rounded-md bg-slate-200/80 text-slate-600 px-2 py-0.5 text-[10px] font-semibold';
            tag3.innerText = 'Standby';
        }
        const vecBar = document.getElementById('liveVecBar');
        if (vecBar) vecBar.style.width = '0%';
        const vecVal = document.getElementById('liveVecCosineVal');
        if (vecVal) vecVal.innerText = '0%';
        const vecComp = document.getElementById('liveVecComparedBug');
        if (vecComp) vecComp.innerText = `Awaiting vector matrix (${projectBugs.length} issues)...`;
        const vecVer = document.getElementById('liveVecVerdict');
        if (vecVer) vecVer.innerText = 'Pending';

        // Reset Card 4 (Dev Routing)
        const card4 = document.getElementById('liveCardDev');
        if (card4) card4.className = standbyCardClass;
        const tag4 = document.getElementById('liveTagDev');
        if (tag4) {
            tag4.className = 'rounded-md bg-slate-200/80 text-slate-600 px-2 py-0.5 text-[10px] font-semibold';
            tag4.innerText = 'Standby';
        }
        const devSummary = document.getElementById('liveDevSummary');
        if (devSummary) devSummary.innerText = 'Awaiting skill & workload match...';
    }

    function updateLiveTelemetry(stage, data = {}) {
        const badge = document.getElementById('liveDetectionPhaseBadge');
        const activeCardClass = 'rounded-xl border border-slate-900 bg-white p-3.5 space-y-2 transition-all shadow-2xs';
        const doneCardClass = 'rounded-xl border border-emerald-200 bg-white p-3.5 space-y-2 transition-all';

        if (stage === 1) {
            if (badge) badge.innerText = 'STAGE 1 / 4: VISION & OCR ACTIVE';
            const tag = document.getElementById('liveTagNlp');
            if (tag) {
                tag.className = 'rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold uppercase';
                tag.innerText = 'PARSING TOKENS';
            }
        } else if (stage === 2) {
            if (badge) badge.innerText = 'STAGE 2 / 4: GITHUB TREE & CODE SCAN';
            const tag1 = document.getElementById('liveTagNlp');
            if (tag1) {
                tag1.className = 'rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 text-[10px] font-bold';
                tag1.innerText = '✓ Extracted';
            }
            const card2 = document.getElementById('liveCardGh');
            if (card2) card2.className = activeCardClass;
            const tag2 = document.getElementById('liveTagGh');
            if (tag2) {
                tag2.className = 'rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold';
                tag2.innerText = 'Scanning Repo';
            }

            if (data.files && data.files.length) {
                const ghFiles = document.getElementById('liveGhScannedFile');
                if (ghFiles) {
                    ghFiles.innerHTML = data.files.map(f => `
                        <div class="flex items-center justify-between text-[11px] text-slate-700 font-mono-code py-0.5 border-b border-slate-100 last:border-0">
                            <span class="truncate max-w-[240px]">${escapeHtml(f.name)}</span>
                            <span class="text-indigo-600 font-semibold shrink-0 ml-1">${f.match}%</span>
                        </div>
                    `).join('');
                }
            }
            if (data.matchScore) {
                const ghScore = document.getElementById('liveGhMatchScore');
                if (ghScore) ghScore.innerText = data.matchScore;
            }
        } else if (stage === 3) {
            if (badge) badge.innerText = 'STAGE 3 / 4: VECTOR SIM DEDUPLICATION';
            const card2 = document.getElementById('liveCardGh');
            if (card2) card2.className = doneCardClass;
            const card3 = document.getElementById('liveCardVec');
            if (card3) card3.className = activeCardClass;
            const tag3 = document.getElementById('liveTagVec');
            if (tag3) {
                tag3.className = 'rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold';
                tag3.innerText = 'Computing Cosine';
            }

            if (data.compared) document.getElementById('liveVecComparedBug').innerText = data.compared;
            if (data.cosineVal) document.getElementById('liveVecCosineVal').innerText = data.cosineVal;
            if (data.percent !== undefined) {
                const bar = document.getElementById('liveVecBar');
                if (bar) bar.style.width = `${Math.min(100, Math.max(5, data.percent))}%`;
            }
            if (data.verdict) document.getElementById('liveVecVerdict').innerText = data.verdict;
        } else if (stage === 4) {
            if (badge) badge.innerText = 'STAGE 4 / 4: DEVELOPER ROUTING & ROOT-CAUSE';
            const card3 = document.getElementById('liveCardVec');
            if (card3) card3.className = doneCardClass;
            const tag3 = document.getElementById('liveTagVec');
            if (tag3) {
                tag3.className = 'rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 text-[10px] font-bold';
                tag3.innerText = data.isDup ? 'Duplicate' : 'Unique Issue';
            }
            const card4 = document.getElementById('liveCardDev');
            if (card4) card4.className = activeCardClass;
            const tag4 = document.getElementById('liveTagDev');
            if (tag4) {
                tag4.className = 'rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold';
                tag4.innerText = 'Routing';
            }
        }
    }

    function focusLiveDetectionCard(num) {
        const cardMap = { 1: 'liveCardNlp', 2: 'liveCardGh', 3: 'liveCardVec', 4: 'liveCardDev' };
        const targetId = cardMap[num];
        if (targetId) {
            const el = document.getElementById(targetId);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                el.classList.add('ring-2', 'ring-indigo-400');
                setTimeout(() => el.classList.remove('ring-2', 'ring-indigo-400'), 1500);
            }
        }
    }

    function openTriageModal(title) {
        const modal = document.getElementById('triageTerminalModal');
        modal.classList.remove('hidden');
        modal.scrollTop = 0;
        document.body.classList.add('overflow-hidden');
        
        // Reset Terminal
        renderedProgressKeys.clear();
        const timelineEl = document.getElementById('liveAgentTimeline');
        if (timelineEl) timelineEl.innerHTML = '';
        document.getElementById('terminalBugTitle').innerText = title || 'Processing Anomaly';
        document.getElementById('terminalOutput').innerHTML = '';
        document.getElementById('triageProgressBar').style.width = '5%';
        document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span> RUNNING NEURAL PIPELINE`;
        document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-[11px] font-bold text-indigo-700 font-mono-code';
        
        // Show Live Scanner and Hide Result Card
        const resultCard = document.getElementById('terminalResultCard');
        if (resultCard) resultCard.classList.add('hidden');
        const liveScanner = document.getElementById('terminalLiveScanner');
        if (liveScanner) liveScanner.classList.remove('hidden');

        // Reset Live Scanner to Stage 1
        updateLiveScanner(
            1,
            'Vision OCR & Lexical NLP Parser Active',
            'Analyzing natural language symptom patterns and extracting screenshot image tokens with Groq Vision Llama-3.2.',
            '📸',
            'Llama-3.2-11B',
            'Ready',
            'Standby',
            'Standby'
        );

        const descVal = document.getElementById('bugDescription')?.value || '';
        const hasScreen = Boolean(attachedScreenshotFile) || Boolean(document.getElementById('screenshotInput')?.files?.length);
        const screenName = attachedScreenshotFile?.name || document.getElementById('screenshotInput')?.files?.[0]?.name;
        resetLiveTelemetry(title, descVal, hasScreen, screenName);
        
        // Reset Steps
        [1, 2, 3, 4].forEach(i => {
            const step = document.getElementById(`step-pill-${i}`);
            if (step) {
                step.className = 'cursor-pointer rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 transition hover:border-slate-300 flex items-center justify-center gap-2';
                step.innerHTML = `<span>${i}. ${step.dataset.name || 'Stage ' + i}</span>`;
            }
        });
        setStepActive(1);
        
        // Live Millisecond Timer
        triageStartTime = performance.now();
        clearInterval(triageTimer);
        triageTimer = setInterval(() => {
            const elapsed = ((performance.now() - triageStartTime) / 1000).toFixed(2);
            document.getElementById('terminalTimer').innerText = `[ ${elapsed}s ]`;
        }, 50);
    }

    function closeTriageModal() {
        clearInterval(triageTimer);
        document.getElementById('triageTerminalModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('terminalLiveScanner')?.classList.remove('hidden');
        document.getElementById('terminalResultCard')?.classList.add('hidden');
    }

    function submitAnotherReport() {
        closeTriageModal();
        const form = document.getElementById('triageForm');
        form?.reset();
        const list = document.getElementById('reproductionSteps');
        if (list) {
            list.replaceChildren();
            addReproductionStep();
        }
        attachedScreenshotFile = null;
        const input = document.getElementById('screenshotInput');
        if (input) input.value = '';
        document.getElementById('screenshotPreview').src = '';
        document.getElementById('screenshotPlaceholder').classList.remove('hidden');
        document.getElementById('screenshotPreviewWrapper').classList.add('hidden');
        document.getElementById('screenshotDropzone').classList.remove('border-emerald-500', 'bg-emerald-50/30');
        document.getElementById('screenshotError').classList.add('hidden');
        document.getElementById('screenshotUploadProgress').classList.add('hidden');
        document.getElementById('screenshotUploadBar').style.width = '0%';
        document.getElementById('screenshotUploadPercent').textContent = '0%';
        const button = document.getElementById('submitTriageBtn');
        button.disabled = false;
        button.innerHTML = '<span>✨ Submit bug report</span>';
        const feedback = document.getElementById('submissionFeedback');
        feedback.textContent = 'Add your report details and submit when you are ready.';
        feedback.className = 'min-h-4 mt-2 text-center text-[11px] text-slate-500';
        document.getElementById('bugTitle').focus();
    }

    function prependMyReport(report) {
        const list = document.getElementById('myReportsList');
        if (!list) return;
        document.getElementById('myReportsEmpty')?.remove();
        const card = document.createElement('a');
        card.href = `/bugs/${encodeURIComponent(report.bug_id)}`;
        card.className = 'my-report-item block rounded-xl border border-emerald-200 bg-emerald-50/50 px-3 py-2.5 hover:border-indigo-300 hover:bg-indigo-50/40';
        const top = document.createElement('div');
        top.className = 'flex items-start justify-between gap-2';
        const heading = document.createElement('p');
        heading.className = 'min-w-0 truncate text-xs font-bold text-slate-900';
        heading.textContent = `#ABT-${report.bug_id} · ${report.title}`;
        const status = document.createElement('span');
        status.className = 'shrink-0 rounded-full bg-white px-2 py-0.5 text-[10px] font-semibold text-slate-700';
        status.textContent = report.status;
        top.append(heading, status);
        const meta = document.createElement('div');
        meta.className = 'mt-1 flex flex-wrap items-center justify-between gap-2 text-[10px] text-slate-500';
        const owner = document.createElement('span');
        owner.textContent = `Developer: ${report.assigned_to || 'Unassigned'}`;
        const state = document.createElement('span');
        state.textContent = 'Triage complete · just now';
        meta.append(owner, state);
        card.append(top, meta);
        list.prepend(card);
        [...list.querySelectorAll('.my-report-item')].slice(8).forEach(item => item.remove());
        document.getElementById('myReportsCount').textContent = list.querySelectorAll('.my-report-item').length;
    }

    function setStepActive(stepNum) {
        const step = document.getElementById(`step-pill-${stepNum}`);
        if (step) {
            step.className = 'cursor-pointer rounded-xl border border-indigo-300 border-b-2 border-b-indigo-600 bg-white px-3 py-2.5 text-xs font-bold text-slate-900 transition flex items-center justify-center gap-2 shadow-2xs';
            step.innerHTML = `<span>${stepNum}. ${step.dataset.name || 'Stage ' + stepNum}</span> <span class="h-3 w-3 rounded-full border-2 border-indigo-600 border-t-transparent animate-spin shrink-0"></span>`;
        }
    }

    function setStepDone(stepNum) {
        const step = document.getElementById(`step-pill-${stepNum}`);
        if (step) {
            step.className = 'cursor-pointer rounded-xl border border-emerald-200 bg-emerald-50/70 px-3 py-2.5 text-xs font-semibold text-emerald-800 transition flex items-center justify-center gap-1.5';
            step.innerHTML = `<span class="text-emerald-600 font-bold">✓</span> <span>${stepNum}. ${step.dataset.name || 'Step ' + stepNum}</span>`;
        }
    }

    function setStepSkipped(stepNum) {
        const step = document.getElementById(`step-pill-${stepNum}`);
        if (step) {
            step.className = 'cursor-pointer rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-xs font-medium text-slate-500 transition flex items-center justify-center gap-1.5';
            step.innerHTML = `<span>—</span> <span>${stepNum}. ${step.dataset.name || 'Step ' + stepNum}</span>`;
        }
    }

    function setStepFailed(stepNum) {
        const step = document.getElementById(`step-pill-${stepNum}`);
        if (step) {
            step.className = 'cursor-pointer rounded-xl border border-rose-200 bg-rose-50 px-3 py-2.5 text-xs font-semibold text-rose-700 transition flex items-center justify-center gap-1.5';
            step.innerHTML = `<span>⚠</span> <span>${stepNum}. ${step.dataset.name || 'Step ' + stepNum}</span>`;
        }
    }

    let activeTriageRunId = null;
    const renderedProgressKeys = new Set();

    function escapeHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function applyAgentProgressEvent(event) {
        if (!event || !event.step) return;
        if (event.run_id && activeTriageRunId && event.run_id !== activeTriageRunId) return;

        const dedupKey = `${event.step}:${event.status}`;
        if (renderedProgressKeys.has(dedupKey)) return;
        renderedProgressKeys.add(dedupKey);

        const stepToPill = {
            vision_ocr: 1,
            file_locator: 2,
            duplicate_check: 3,
            developer_routing: 4,
            llm_analysis: 4,
        };
        const pillNum = stepToPill[event.step];
        const status = event.status || 'started';
        const summary = event.summary || {};

        if (pillNum) {
            if (status === 'started') setStepActive(pillNum);
            else if (status === 'completed') setStepDone(pillNum);
            else if (status === 'skipped') setStepSkipped(pillNum);
            else if (status === 'failed') setStepFailed(pillNum);
        }

        const progressMap = {
            vision_ocr: status === 'started' ? 15 : 28,
            duplicate_check: status === 'started' ? 38 : 52,
            developer_routing: status === 'started' ? 60 : 72,
            file_locator: status === 'started' ? 78 : 88,
            llm_analysis: status === 'started' ? 92 : 98,
        };
        const bar = document.getElementById('triageProgressBar');
        if (bar && progressMap[event.step]) {
            bar.style.width = `${progressMap[event.step]}%`;
        }

        const submitButton = document.getElementById('submitTriageBtn');
        if (submitButton && submitButton.disabled) {
            submitButton.innerHTML = `<span class="animate-pulse">${escapeHtml(event.label || event.step)}: ${escapeHtml(event.message || status)}</span>`;
        }

        const stageIcons = {
            vision_ocr: '📸',
            code_diagnosis: '⚡',
            duplicate_check: '🔍',
            developer_routing: '👤',
            file_locator: '🐙',
            llm_analysis: '🧠',
        };
        const iconEl = document.getElementById('liveStageIcon');
        const titleEl = document.getElementById('liveStageTitle');
        const descEl = document.getElementById('liveStageDesc');
        const pillEl = document.getElementById('liveStagePill');
        if (iconEl) iconEl.innerText = stageIcons[event.step] || '⚡';
        if (pillEl) pillEl.innerText = `${String(event.label || event.step).toUpperCase()} · ${status.toUpperCase()}`;
        if (titleEl) titleEl.innerText = event.label || event.step;
        if (descEl) descEl.innerText = event.message || '';

        // Update live telemetry cards strictly from real agent output
        if (event.step === 'vision_ocr') {
            const ocrTag = document.getElementById('liveOcrStatusTag');
            const ocrLog = document.getElementById('liveOcrStreamLog');
            const visionSensor = document.getElementById('liveSensorVision');
            if (ocrTag) {
                ocrTag.innerText = status.toUpperCase();
                ocrTag.className = status === 'completed' ? 'text-emerald-400 font-semibold' : (status === 'failed' ? 'text-rose-400 font-semibold' : 'text-slate-400 font-semibold');
            }
            if (ocrLog) ocrLog.textContent = `[Status] ${summary.visual_summary || summary.extracted_text || event.message}`;
            if (visionSensor) visionSensor.textContent = summary.method || status;
        } else if (event.step === 'duplicate_check') {
            const sim = Number(summary.similarity_score || 0);
            const isDup = Boolean(summary.is_duplicate);
            updateLiveTelemetry(3, {
                compared: summary.compared_count !== undefined ? `Compared against ${summary.compared_count} project issues` : event.message,
                cosineVal: summary.similarity_score !== undefined ? `${sim}%` : 'Computing...',
                percent: sim,
                verdict: status === 'completed' ? (isDup ? `Duplicate (#ABT-${summary.duplicate_of})` : `Unique (${sim}%)`) : event.message,
                isDup,
            });
            const dupSensor = document.getElementById('liveSensorDup');
            if (dupSensor && status === 'completed') {
                dupSensor.textContent = `${sim}% Similarity`;
            }
        } else if (event.step === 'file_locator') {
            const files = Array.isArray(summary.suspected_files) ? summary.suspected_files.map(f => ({
                name: f.path || f,
                match: f.confidence || 0,
            })) : [];
            updateLiveTelemetry(2, {
                files,
                matchScore: files.length ? `${files[0].match || 'Matched'}% (${files[0].name.split('/').pop()})` : event.message,
            });
            const tag2 = document.getElementById('liveTagGh');
            if (tag2 && status !== 'started') {
                tag2.innerText = status === 'completed' ? `✓ ${files.length} file(s)` : status;
            }
            const codeSensor = document.getElementById('liveSensorCode');
            if (codeSensor && status !== 'started') {
                codeSensor.textContent = files.length ? files[0].name.split('/').pop() : status;
            }
        } else if (event.step === 'developer_routing' || event.step === 'llm_analysis') {
            updateLiveTelemetry(4, {});
            const devSensor = document.getElementById('liveSensorDev');
            if (devSensor && summary.assigned_to) {
                devSensor.textContent = summary.assigned_to;
            }
            const devSummary = document.getElementById('liveDevSummary');
            if (devSummary) {
                devSummary.textContent = event.step === 'llm_analysis'
                    ? (event.message || 'Analyzing GitHub code & root cause...')
                    : (summary.assigned_to ? `Assigned to ${summary.assigned_to}` : (event.message || 'Routing complete'));
            }
            const tag4 = document.getElementById('liveTagDev');
            if (tag4 && status !== 'started') {
                tag4.innerText = status === 'completed' ? '✓ Done' : status;
            }
        }

        // Append to Live Agent Timeline inside the terminal log box
        const timeline = document.getElementById('liveAgentTimeline');
        if (timeline) {
            const colorMap = {
                started: 'text-sky-300',
                completed: 'text-emerald-300',
                skipped: 'text-slate-400',
                failed: 'text-rose-300',
            };
            const row = document.createElement('div');
            row.className = 'truncate';
            row.innerHTML = `<span class="text-slate-400">[${escapeHtml(status)}]</span> <span class="${colorMap[status] || 'text-slate-200'} font-semibold">${escapeHtml(event.label || event.step)}:</span> <span class="text-slate-300">${escapeHtml(event.message || '')}</span>`;
            timeline.appendChild(row);
            timeline.scrollTop = timeline.scrollHeight;
        }

        appendTerminalLog(`[${String(event.step).toUpperCase()}]`, `${status.toUpperCase()}: ${event.message || ''}`, status === 'failed' ? 'text-rose-400' : (status === 'completed' ? 'text-emerald-300' : 'text-sky-300'));
    }

    function appendTerminalLog(prefix, msg, color = 'text-slate-300') {
        const terminal = document.getElementById('terminalOutput');
        if (!terminal) return;
        const elapsed = ((performance.now() - triageStartTime) / 1000).toFixed(2);
        const div = document.createElement('div');
        div.className = `flex items-start gap-2 text-xs font-mono-code ${color} transition`;
        div.innerHTML = `<span class="text-slate-500 shrink-0 font-normal">[+${elapsed}s]</span> <span class="font-bold text-indigo-400 shrink-0">${prefix}</span> <span>${msg}</span>`;
        terminal.appendChild(div);
        terminal.scrollTop = terminal.scrollHeight;
    }

    function buildSafeGhUrl(rawPath, itemGhUrl, itemBranch) {
        if (itemGhUrl && typeof itemGhUrl === 'string' && itemGhUrl.startsWith('http')) {
            const bIdx = itemGhUrl.indexOf('/blob/');
            if (bIdx !== -1) {
                const prefix = itemGhUrl.substring(0, bIdx + 6);
                const rest = itemGhUrl.substring(bIdx + 6);
                const parts = rest.split('/');
                const br = parts[0];
                const fixedPath = parts.slice(1).join('/').replace(/%2[fF]/g, '/');
                return `${prefix}${br}/${fixedPath.replace(/^\/+/, '')}`;
            }
            return itemGhUrl;
        }
        const currentGhRepo = document.getElementById('triageGithubRepo')?.value || '{{ $projectGithubRepo ?? "" }}';
        let safeRepoSlug = '';
        if (currentGhRepo) {
            const m = currentGhRepo.match(/github\.com\/([^/]+)\/([^/\s#?]+)/);
            if (m) safeRepoSlug = `${m[1]}/${m[2].replace(/\.git$/, '')}`;
            else if (currentGhRepo.includes('/')) safeRepoSlug = currentGhRepo.trim().replace(/\.git$/, '');
        }
        const slug = safeRepoSlug || 'MuarifHasnat/Auto-Bug-Traige';
        const br = itemBranch || 'HEAD';
        const cleanP = String(rawPath || '').replace(/\\/g, '/').replace(/^\/+/, '');
        const encodedP = cleanP.split('/').map(seg => encodeURIComponent(seg)).join('/');
        return `https://github.com/${slug}/blob/${encodeURIComponent(br)}/${encodedP}`;
    }

    function handleTitleInput(val) {
        const errorBanner = document.getElementById('invalidInputBanner');
        if (errorBanner) errorBanner.classList.add('hidden');
        document.getElementById('bugTitle')?.classList.remove('border-rose-500', 'bg-rose-50/50');
        checkDuplicateTitle(val);
    }

    function isGibberishOrInvalid(title, description, hasAttachment) {
        const cleanTitle = (title || '').trim().toLowerCase();
        const cleanDesc = (description || '').trim().toLowerCase();

        // If screenshot or source file is attached, require a minimal non-empty meaningful title
        if (hasAttachment && cleanTitle.length >= 4 && /[aeiouy]/.test(cleanTitle)) {
            return false;
        }

        if (cleanTitle.length < 5) {
            return 'Title is too short. Please provide a clear summary of the bug.';
        }

        // Repeated characters (e.g., 'aaaa', 'zzzzz')
        if (/(.)\1{3,}/.test(cleanTitle)) {
            return 'Title contains repeated characters. Please enter a real bug report.';
        }

        // Repeating 2-3 char patterns without spaces (e.g. 'mbmbm', 'asdasd', 'qweqwe')
        if (/^(.{2,3})\1+.*$/.test(cleanTitle) && !cleanTitle.includes(' ')) {
            return 'Invalid input: "' + title.trim() + '" appears to be random or gibberish text. Please provide a real bug title.';
        }

        const knownAbbrs = ['sql', 'php', 'css', 'html', 'jwt', 'api', 'cli', 'sdk', 'sso', 'ssl', 'ssh', 'dns', 'url', 'xml', 'csv', 'svg', 'npm', 'jvm', 'grpc', 'http', 'https'];
        const words = cleanTitle.split(/\s+/).filter(Boolean);

        if (words.length <= 1) {
            const single = words[0] || cleanTitle;
            if (knownAbbrs.includes(single)) {
                if (cleanDesc.length < 10) {
                    return 'Please provide more details in the description.';
                }
                return false;
            }
            // No vowels in a single word of 4+ characters (e.g. mbmbm, dfgh, bcdf)
            if (!/[aeiouy]/.test(single)) {
                return 'Invalid bug title: "' + title.trim() + '" appears to be random letters. Please describe a real issue.';
            }
            // If single word is less than 5 characters and not a 3-digit status code like 404/500
            if (single.length < 5 && !/^\d{3}$/.test(single)) {
                return 'Please provide a more descriptive title with at least 2 words (e.g. "Login failed").';
            }
        }

        const combined = `${cleanTitle} ${cleanDesc}`;
        const vowels = (combined.match(/[aeiouy]/g) || []).length;
        const letters = (combined.match(/[a-z]/g) || []).length;
        if (letters >= 5 && vowels === 0) {
            return 'Invalid report: No vowels detected. Please describe the problem in plain text.';
        }

        return false;
    }

    // Submit Handler with Streaming Execution (5-Phase Explainable AI Pipeline)
    async function handleTriageSubmit(e) {
        if (e) e.preventDefault();
        const form = document.getElementById('triageForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const titleInput = document.getElementById('bugTitle');
        const descInput = document.getElementById('bugDescription');
        const title = (titleInput ? titleInput.value : '').trim();
        const desc = (descInput ? descInput.value : '').trim();
        const hasScreenshot = Boolean(attachedScreenshotFile) || Boolean(document.getElementById('screenshotInput')?.files?.length);
        const hasSourceFile = Boolean(document.getElementById('sourceFileInput')?.files?.length);

        const validationError = isGibberishOrInvalid(title, desc, hasScreenshot || hasSourceFile);
        if (validationError) {
            const errorBanner = document.getElementById('invalidInputBanner');
            const errorMsg = document.getElementById('invalidInputMsg');
            if (errorBanner) {
                if (errorMsg) errorMsg.innerText = validationError;
                errorBanner.classList.remove('hidden');
                errorBanner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
            if (titleInput) {
                titleInput.classList.add('border-rose-500', 'bg-rose-50/50');
                titleInput.focus();
            }
            const feedback = document.getElementById('submissionFeedback');
            if (feedback) {
                feedback.textContent = validationError;
                feedback.className = 'min-h-4 mt-2 text-center text-xs font-bold text-rose-600';
            }
            return;
        }

        const feedback = document.getElementById('submissionFeedback');
        feedback.textContent = 'Uploading your report and starting live AI triage…';
        feedback.className = 'min-h-4 mt-2 text-center text-[11px] text-indigo-700';
        const submitButton = document.getElementById('submitTriageBtn');
        submitButton.disabled = true;
        submitButton.classList.add('cursor-not-allowed', 'opacity-90');
        submitButton.innerHTML = `<span class="animate-pulse">🚀 Dispatching report to AI agent...</span>`;

        const runId = (window.crypto && typeof window.crypto.randomUUID === 'function')
            ? window.crypto.randomUUID()
            : `run-${Date.now()}-${Math.random().toString(16).slice(2)}`;
        activeTriageRunId = runId;

        document.getElementById('screenshotUploadBar').style.width = '0%';
        document.getElementById('screenshotUploadPercent').textContent = '0%';
        document.getElementById('screenshotUploadLabel').textContent = 'Uploading screenshot…';
        const formData = new FormData(form);
        formData.set('run_id', runId);
        if (attachedScreenshotFile) {
            formData.set('screenshot', attachedScreenshotFile, attachedScreenshotFile.name);
        }

        const fileName = attachedScreenshotFile?.name || document.getElementById('screenshotInput')?.files?.[0]?.name;
        openTriageModal(title);
        resetLiveTelemetry(title, desc, hasScreenshot, fileName);
        appendTerminalLog('[BOOT]', `Starting real-time agent run (${runId.slice(0, 8)})...`, 'text-slate-300');

        // Dispatch request; live progress updates arrive via Reverb (.agent.progress)
        const uploadProgress = document.getElementById('screenshotUploadProgress');
        if (hasScreenshot) uploadProgress.classList.remove('hidden');
        const apiPromise = new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.upload.addEventListener('progress', (event) => {
                if (!event.lengthComputable) return;
                const percent = Math.round((event.loaded / event.total) * 100);
                if (hasScreenshot) {
                    document.getElementById('screenshotUploadBar').style.width = `${percent}%`;
                    document.getElementById('screenshotUploadPercent').textContent = `${percent}%`;
                }
                feedback.textContent = percent < 100 ? `Uploading report… ${percent}%` : 'Upload complete. Listening for live agent progress…';
            });
            xhr.addEventListener('load', () => {
                if (xhr.status === 429) {
                    reject(new Error('Security Rate Limit: Too many submissions. Please wait 1 minute before submitting another bug report.'));
                    return;
                }
                let data;
                try { data = JSON.parse(xhr.responseText); } catch (_) {
                    reject(new Error(xhr.status === 401 ? 'Your session expired. Please sign in again.' : 'The server returned an unreadable response. Please retry.'));
                    return;
                }
                if (xhr.status < 200 || xhr.status >= 300 || data.success === false) {
                    const validation = data.errors ? Object.values(data.errors).flat()[0] : null;
                    reject(new Error(validation || data.message || 'The report could not be submitted. Please retry.'));
                    return;
                }
                resolve(data);
            });
            xhr.addEventListener('error', () => reject(new Error('Network error while submitting. Check your connection and retry.')));
            xhr.addEventListener('abort', () => reject(new Error('Upload was cancelled. You can retry your report.')));
            xhr.send(formData);
        });

        // Poll real backend progress every 400ms (works even without Reverb websocket server)
        const progressPollTimer = window.setInterval(async () => {
            try {
                const resp = await fetch(`/api/triage-progress/${encodeURIComponent(runId)}`, {
                    headers: { 'Accept': 'application/json' },
                });
                if (!resp.ok) return;
                const payload = await resp.json();
                if (Array.isArray(payload.events)) {
                    payload.events.forEach(ev => applyAgentProgressEvent(ev));
                }
            } catch (_) {}
        }, 400);

        try {
            const resData = await apiPromise;
            window.clearInterval(progressPollTimer);
            clearInterval(triageTimer);
            submitButton.classList.remove('cursor-not-allowed', 'opacity-90');
            submitButton.innerHTML = '<span>✓ Triage Completed</span>';
            const totalDuration = ((performance.now() - triageStartTime) / 1000).toFixed(2);
            document.getElementById('terminalTimer').innerText = `[ ${totalDuration}s ]`;
            document.getElementById('triageProgressBar').style.width = '100%';

            const ai = resData.ai_result || {};
            if (Array.isArray(ai.progress_trace)) {
                ai.progress_trace.forEach(ev => applyAgentProgressEvent({ ...ev, run_id: runId }));
            }
            const feat = ai.feature || 'General';
            const priority = ai.priority || 'Medium';
            const simScore = ai.similarity_score ?? 0;
            const assignee = resData.assigned_to || 'Unassigned';
            const needsReview = Boolean(ai.needs_review) || assignee === 'Unassigned';
            const bugId = resData.bug_id || 1;
            const filesList = (ai.code_help && ai.code_help.suspected_files && ai.code_help.suspected_files.length) 
                ? ai.code_help.suspected_files 
                : (ai.suspected_files && ai.suspected_files.length ? ai.suspected_files : []);

            const topCandidate = filesList.length ? filesList[0] : null;
            const suspected = topCandidate ? (topCandidate.path || topCandidate) : 'Repository files scanned';
            const topGhUrl = buildSafeGhUrl(suspected, topCandidate ? topCandidate.github_url : null, topCandidate ? topCandidate.branch : null);

            appendTerminalLog('[SUCCESS]', `Triage completed autonomously in ${totalDuration}s!`, 'text-emerald-400 font-bold');
            appendTerminalLog('[DISPATCH]', `Anomaly categorized under module: ${feat} (${priority} Priority)`, 'text-emerald-300');
            appendTerminalLog('[DISPATCH]', `Duplicate vector similarity: ${simScore}% (${simScore > 75 ? 'POSSIBLE DUPLICATE' : 'UNIQUE ISSUE'})`, 'text-emerald-300');
            appendTerminalLog('[DISPATCH]', `Suspected GitHub Culprit: ${suspected}`, 'text-sky-300');
            appendTerminalLog('[DISPATCH]', `Assigned to: ${assignee} (Based on skills & active tasks balance)`, 'text-emerald-400 font-bold');

            // Status Pill Complete
            document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-emerald-600"></span> TRIAGE COMPLETED`;
            document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-bold text-emerald-700 font-mono-code';

            // Show Result Card
            document.getElementById('terminalResultKey').innerText = `#ABT-${bugId}`;
            const breakdown = ai.assignment_breakdown || ai.code_help?.assignment_breakdown;
            const fitBadgeText = needsReview
                ? 'Needs Review'
                : (breakdown && breakdown.total_score !== undefined ? `Score: ${breakdown.total_score} pts` : `Module: ${feat}`);
            const confEl = document.getElementById('resConfidence');
            if (confEl) confEl.innerText = needsReview ? 'Needs Manager Review' : `${feat} Triage`;
            document.getElementById('resAssignee').innerText = assignee;
            const resDevNameLabel = document.getElementById('resDevNameLabel');
            if (resDevNameLabel) resDevNameLabel.innerText = assignee;

            const resPriorityBadge = document.getElementById('resPriorityBadge');
            if (resPriorityBadge) {
                resPriorityBadge.innerText = priority + ' Priority';
                const pLower = priority.toLowerCase();
                if (pLower.includes('high') || pLower.includes('critical')) {
                    resPriorityBadge.className = 'rounded-lg bg-rose-50 border border-rose-200 text-rose-700 font-bold text-xs px-2.5 py-1';
                } else if (pLower.includes('low')) {
                    resPriorityBadge.className = 'rounded-lg bg-blue-50 border border-blue-200 text-blue-700 font-bold text-xs px-2.5 py-1';
                } else {
                    resPriorityBadge.className = 'rounded-lg bg-amber-50 border border-amber-200 text-amber-700 font-bold text-xs px-2.5 py-1';
                }
            }

            const resPriority = document.getElementById('resPriority');
            if (resPriority) {
                resPriority.innerText = priority + ' Priority';
                const pLower = priority.toLowerCase();
                const iconEl = document.getElementById('resPriorityIcon');
                if (pLower.includes('high') || pLower.includes('critical')) {
                    resPriority.className = 'font-extrabold text-rose-600 text-xs sm:text-sm truncate block mt-0.5';
                    if (iconEl) { iconEl.innerText = '⚡'; iconEl.className = 'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 text-lg border border-rose-200'; }
                } else if (pLower.includes('low')) {
                    resPriority.className = 'font-extrabold text-slate-600 text-xs sm:text-sm truncate block mt-0.5';
                    if (iconEl) { iconEl.innerText = '🟢'; iconEl.className = 'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-slate-600 text-lg border border-slate-200'; }
                } else {
                    resPriority.className = 'font-extrabold text-amber-600 text-xs sm:text-sm truncate block mt-0.5';
                    if (iconEl) { iconEl.innerText = '🟠'; iconEl.className = 'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 text-lg border border-amber-200'; }
                }
            }

            const resSimilarity = document.getElementById('resSimilarity');
            if (resSimilarity) {
                const isDup = simScore > 75;
                resSimilarity.innerText = isDup ? 'Duplicate Issue' : 'Unique Issue';
                const simIconEl = document.getElementById('resSimilarityIcon');
                if (isDup) {
                    resSimilarity.className = 'font-extrabold text-amber-600 text-xs sm:text-sm truncate block mt-0.5';
                    if (simIconEl) { simIconEl.innerText = '⚠️'; simIconEl.className = 'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 text-lg border border-amber-200'; }
                } else {
                    resSimilarity.className = 'font-extrabold text-emerald-600 text-xs sm:text-sm truncate block mt-0.5';
                    if (simIconEl) { simIconEl.innerText = '🛡️'; simIconEl.className = 'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 text-lg border border-emerald-200'; }
                }
            }

            const culpritEl = document.getElementById('resCulprit');
            if (culpritEl) culpritEl.innerText = suspected;
            const culpritGhLink = document.getElementById('resCulpritGhLink');
            if (culpritGhLink) {
                culpritGhLink.href = topGhUrl;
                culpritGhLink.classList.remove('hidden');
            }

            // Populate 4-Card Full Scan Visual Dashboard
            const hasScreenshot = Boolean(resData.has_screenshot) || Boolean(attachedScreenshotFile) || Boolean(document.getElementById('screenshotInput')?.files?.length) || !!ai.screenshot_vision || !!ai.screenshot_analyzed;
            const scanEvidenceBadge = document.getElementById('scanEvidenceBadge');
            const scanEvidenceText = document.getElementById('scanEvidenceText');
            const scanEvidenceEngine = document.getElementById('scanEvidenceEngine');
            const scanEvidenceDot = document.getElementById('scanEvidenceDot');
            const scanEvidenceThumbWrapper = document.getElementById('scanEvidenceThumbWrapper');
            const scanEvidenceThumb = document.getElementById('scanEvidenceThumb');
            const scanEvidenceFileName = document.getElementById('scanEvidenceFileName');
            const scanEvidenceLink = document.getElementById('scanEvidenceLink');

            if (scanEvidenceBadge) {
                if (hasScreenshot) {
                    scanEvidenceBadge.innerHTML = '<span>⚡</span> Vision OCR';
                    scanEvidenceBadge.className = 'inline-flex items-center gap-1 rounded-lg bg-purple-50 border border-purple-200 px-2 py-0.5 text-[10px] font-extrabold text-purple-700';
                    if (scanEvidenceText) scanEvidenceText.innerText = 'Screenshot analyzed with Groq Vision';
                    if (scanEvidenceEngine) scanEvidenceEngine.innerText = 'Llama-3.2-11B';
                    if (scanEvidenceDot) scanEvidenceDot.className = 'h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse';

                    if (scanEvidenceThumbWrapper) {
                        const previewSrc = document.getElementById('screenshotPreview')?.src || resData.screenshot_url;
                        if (previewSrc) {
                            if (scanEvidenceThumb) scanEvidenceThumb.src = previewSrc;
                            if (scanEvidenceFileName) scanEvidenceFileName.innerText = resData.screenshot_name || document.getElementById('screenshotInput')?.files?.[0]?.name || 'Attached Screenshot';
                            if (scanEvidenceLink && resData.screenshot_url) {
                                scanEvidenceLink.href = resData.screenshot_url;
                                scanEvidenceLink.classList.remove('hidden');
                            }
                            scanEvidenceThumbWrapper.classList.remove('hidden');
                        }
                    }

                    // Populate Vision OCR Extracted Findings
                    const scanEvidenceOcrFindings = document.getElementById('scanEvidenceOcrFindings');
                    const scanEvidenceOcrText = document.getElementById('scanEvidenceOcrText');
                    const ocrFindingText = ai.code_help?.attachment_note?.visual_summary ||
                                          (Array.isArray(ai.visual_evidence) && ai.visual_evidence.length ? ai.visual_evidence.join(', ') : '') ||
                                          ai.extracted_text ||
                                          ai.code_help?.attachment_note?.extracted_text ||
                                          ai.code_help?.attachment_note?.error_message || '';
                    if (scanEvidenceOcrFindings && scanEvidenceOcrText) {
                        scanEvidenceOcrFindings.classList.remove('hidden');
                        if (ocrFindingText && ocrFindingText.trim().length > 0) {
                            scanEvidenceOcrText.innerText = ocrFindingText;
                        } else {
                            scanEvidenceOcrText.innerText = 'Visual layout, UI controls & button boundaries identified (No raw crash traces).';
                        }
                    }
                } else {
                    scanEvidenceBadge.innerHTML = '<span>📝</span> NLP Tokenizer';
                    scanEvidenceBadge.className = 'inline-flex items-center gap-1 rounded-lg bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-extrabold text-indigo-700';
                    if (scanEvidenceText) scanEvidenceText.innerText = 'Natural language symptom parsed';
                    if (scanEvidenceEngine) scanEvidenceEngine.innerText = 'Lexical NLP';
                    if (scanEvidenceDot) scanEvidenceDot.className = 'h-1.5 w-1.5 rounded-full bg-indigo-500 animate-pulse';
                    if (scanEvidenceThumbWrapper) scanEvidenceThumbWrapper.classList.add('hidden');
                    const scanEvidenceOcrFindings = document.getElementById('scanEvidenceOcrFindings');
                    if (scanEvidenceOcrFindings) scanEvidenceOcrFindings.classList.add('hidden');
                }
            }

            const scanCulpritName = document.getElementById('scanCulpritName');
            const scanCulpritPath = document.getElementById('scanCulpritPath');
            const scanCulpritGhLink = document.getElementById('scanCulpritGhLink');
            if (scanCulpritName) {
                const fileName = suspected.split(/[\\/]/).pop() || suspected;
                scanCulpritName.innerText = '📄 ' + fileName;
                scanCulpritName.title = suspected;
            }
            if (scanCulpritPath) {
                const branchName = (topCandidate && topCandidate.branch) ? topCandidate.branch : 'main';
                const dirName = (suspected.includes('/') || suspected.includes('\\')) 
                    ? suspected.substring(0, Math.max(suspected.lastIndexOf('/'), suspected.lastIndexOf('\\'))) 
                    : 'Codebase scanned';
                scanCulpritPath.innerText = `🌿 ${branchName}: ${dirName}`;
            }
            if (scanCulpritGhLink) {
                if (topGhUrl) {
                    scanCulpritGhLink.href = topGhUrl;
                    scanCulpritGhLink.classList.remove('hidden');
                } else {
                    scanCulpritGhLink.classList.add('hidden');
                }
            }

            const isDup = simScore > 75;
            const scanDupCard = document.getElementById('scanDupCard');
            const scanDupDot = document.getElementById('scanDupDot');
            const scanDupBadge = document.getElementById('scanDupBadge');
            const scanDupScore = document.getElementById('scanDupScore');
            const scanDupProgressBar = document.getElementById('scanDupProgressBar');
            const scanDupStatus = document.getElementById('scanDupStatus');

            if (scanDupBadge) {
                if (isDup) {
                    scanDupBadge.innerHTML = `⚠️ Duplicate (#ABT-${ai.duplicate_of || 'Ref'})`;
                    scanDupBadge.className = 'inline-flex items-center gap-1 rounded-lg bg-rose-100 border border-rose-200 px-2 py-0.5 text-[10px] font-extrabold text-rose-700';
                    if (scanDupCard) scanDupCard.className = 'rounded-2xl border border-rose-200 bg-rose-50/50 p-3 space-y-2 shadow-2xs flex flex-col justify-between';
                    if (scanDupDot) scanDupDot.className = 'h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse';
                    if (scanDupProgressBar) {
                        scanDupProgressBar.className = 'bg-gradient-to-r from-amber-500 to-rose-600 h-1.5 rounded-full transition-all duration-500';
                        scanDupProgressBar.style.width = `${Math.min(100, Math.max(6, simScore))}%`;
                    }
                    if (scanDupStatus) {
                        scanDupStatus.innerText = '⚠️ Duplicate';
                        scanDupStatus.className = 'font-bold text-rose-700';
                    }
                } else {
                    scanDupBadge.innerHTML = '🛡️ Unique Issue';
                    scanDupBadge.className = 'inline-flex items-center gap-1 rounded-lg bg-emerald-100 border border-emerald-200 px-2 py-0.5 text-[10px] font-extrabold text-emerald-800';
                    if (scanDupCard) scanDupCard.className = 'rounded-2xl border border-amber-200 bg-amber-50/50 p-3 space-y-2 shadow-2xs flex flex-col justify-between';
                    if (scanDupDot) scanDupDot.className = 'h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse';
                    if (scanDupProgressBar) {
                        scanDupProgressBar.className = 'bg-gradient-to-r from-emerald-400 to-teal-600 h-1.5 rounded-full transition-all duration-500';
                        scanDupProgressBar.style.width = `${Math.min(100, Math.max(6, simScore))}%`;
                    }
                    if (scanDupStatus) {
                        scanDupStatus.innerText = '✓ Evaluated';
                        scanDupStatus.className = 'font-bold text-emerald-700';
                    }
                }
            }
            if (scanDupScore) scanDupScore.innerText = `${simScore.toFixed(0)}%`;

            const scanDevAvatar = document.getElementById('scanDevAvatar');
            const scanDevName = document.getElementById('scanDevName');
            const scanDevFit = document.getElementById('scanDevFit');
            const scanDevReason = document.getElementById('scanDevReason');

            if (scanDevAvatar) scanDevAvatar.innerText = assignee.charAt(0).toUpperCase();
            if (scanDevName) scanDevName.innerText = assignee;
            if (scanDevFit) scanDevFit.innerText = fitBadgeText;
            if (scanDevReason) {
                const customReason = (ai.code_help && ai.code_help.assignment_reason) 
                    ? ai.code_help.assignment_reason 
                    : `Matched skills taxonomy for "${feat}" & lowest backlog`;
                scanDevReason.innerText = customReason;
                scanDevReason.title = customReason;
            }

            // Populate Explainable AI (XAI) Decision Breakdown
            const xaiPriorityReason = document.getElementById('xaiPriorityReason');
            if (xaiPriorityReason) {
                const realPriorityReason = ai.priority_evidence?.reason || ai.code_help?.priority_evidence?.reason;
                if (realPriorityReason) {
                    xaiPriorityReason.innerText = realPriorityReason;
                } else {
                    const pLower = (priority || '').toLowerCase();
                    if (pLower.includes('high') || pLower.includes('critical')) {
                        xaiPriorityReason.innerText = 'Detected crash/fatal terms, error traces, or blocker signals affecting workflow.';
                    } else if (pLower.includes('low')) {
                        xaiPriorityReason.innerText = 'Minor or cosmetic anomaly with minimal operational impact.';
                    } else {
                        xaiPriorityReason.innerText = 'Standard functional defect detected without fatal system-level exceptions.';
                    }
                }
            }

            const xaiDevReason = document.getElementById('xaiDevReason');
            if (xaiDevReason) {
                const customReason = (ai.code_help && ai.code_help.assignment_reason) 
                    ? ai.code_help.assignment_reason 
                    : `Assigned to ${assignee}: optimal skill taxonomy overlap for "${feat}" domain with lowest active backlog queue.`;
                xaiDevReason.innerText = customReason;
            }

            const xaiDupReason = document.getElementById('xaiDupReason');
            if (xaiDupReason) {
                xaiDupReason.innerText = simScore > 75 
                    ? `Cosine similarity ${simScore}% (> 75% threshold): Matches an existing issue in project backlog.` 
                    : `Cosine similarity ${simScore}% (< 75% threshold): Verified unique defect with no matching existing issues.`;
            }

            // Populate Suspected Files List with 1-click GitHub buttons in modal
            const modalFilesWrapper = document.getElementById('modalSuspectedFilesWrapper');
            const modalFilesList = document.getElementById('modalSuspectedFilesList');
            if (modalFilesWrapper && modalFilesList) {
                modalFilesList.innerHTML = '';
                const listToRender = filesList.length ? filesList : [{
                    path: suspected,
                    confidence: 92,
                    reason: 'Primary repository component match',
                    github_url: topGhUrl
                }];

                listToRender.forEach(fItem => {
                    const fPath = fItem.path || fItem;
                    const fConf = fItem.confidence || 92;
                    const fReason = fItem.reason || 'Matched project component';
                    const fUrl = buildSafeGhUrl(fPath, fItem.github_url, fItem.branch);

                    const itemEl = document.createElement('div');
                    itemEl.className = 'flex items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white p-2.5 hover:border-indigo-400 transition shadow-2xs';
                    itemEl.innerHTML = `
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono-code text-xs font-bold text-slate-900 truncate">📄 ${fPath}</span>
                                <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.2 text-[10px] font-bold font-mono-code">${fConf}% Match</span>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-0.5 truncate">${fReason}</p>
                        </div>
                        <a href="${fUrl}" target="_blank" rel="noopener noreferrer" 
                           class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 hover:text-indigo-600 px-2.5 py-1 text-[11px] font-bold text-slate-700 transition shrink-0 shadow-2xs">
                            <span>View on GitHub</span>
                            <span class="text-xs">↗</span>
                        </a>
                    `;
                    modalFilesList.appendChild(itemEl);
                });
                modalFilesWrapper.classList.remove('hidden');
            }

            document.getElementById('resIssueLink').href = `/bugs/${bugId}`;
            await new Promise(resolve => setTimeout(resolve, 700));
            const liveScanner = document.getElementById('terminalLiveScanner');
            if (liveScanner) liveScanner.classList.add('hidden');
            document.getElementById('terminalResultCard').classList.remove('hidden');
            feedback.textContent = `Report #ABT-${bugId} submitted · triage complete · assigned to ${assignee}.`;
            feedback.className = 'min-h-4 mt-2 text-center text-[11px] font-semibold text-emerald-700';
            if (hasScreenshot) document.getElementById('screenshotUploadLabel').textContent = 'Screenshot uploaded';
            prependMyReport({ bug_id: bugId, title, status: resData.status || ai.status || 'Open', assigned_to: assignee, updated_at: new Date().toISOString(), triage_state: resData.triage_state || 'complete' });

        } catch (err) {
            window.clearInterval(progressPollTimer);
            clearInterval(triageTimer);
            submitButton.classList.remove('cursor-not-allowed', 'opacity-90');
            appendTerminalLog('[ERROR]', err.message || 'Triage failed.', 'text-rose-400 font-bold');
            document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-rose-500"></span> FAILED`;
            document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-rose-50 border border-rose-200 px-3 py-1 text-[11px] font-bold text-rose-700 font-mono-code';
            feedback.textContent = `${err.message || 'Submission failed.'} Your entries are still here; fix the issue and retry.`;
            feedback.className = 'min-h-4 mt-2 text-center text-[11px] font-semibold text-rose-700';
            if (hasScreenshot) document.getElementById('screenshotUploadLabel').textContent = 'Upload failed — retry when ready';
            const button = document.getElementById('submitTriageBtn');
            button.disabled = false;
            button.innerHTML = '<span>✨ Retry bug report</span>';
            setTimeout(() => {
                closeTriageModal();
            }, 1200);
        }
    }

    // Replay function for existing session results
    @if(session('ai_result'))
    async function replayCurrentTriage() {
        const ai = @json(session('ai_result'));
        const bugId = '{{ session("last_bug_id", 1) }}';
        const hasReplayScreenshot = Boolean(ai.screenshot_url) || Boolean(ai.code_help?.attachment_note);
        openTriageModal('{{ session("ai_result.code_help.feature", "Anomaly") }} Diagnosis Replay');
        resetLiveTelemetry('{{ session("ai_result.code_help.feature", "Anomaly") }} Diagnosis Replay', '', hasReplayScreenshot, 'screenshot.png');
        if (hasReplayScreenshot) {
            const previewImg = document.getElementById('liveOcrPreviewImg');
            if (previewImg) previewImg.src = ai.screenshot_url || `/bugs/${bugId}/screenshot`;
        }
        updateLiveTelemetry(1, { hasScreenshot: hasReplayScreenshot });
        
        const filesList = (ai.code_help && ai.code_help.suspected_files && ai.code_help.suspected_files.length) 
            ? ai.code_help.suspected_files 
            : (ai.suspected_files && ai.suspected_files.length ? ai.suspected_files : []);
        const topCandidate = filesList.length ? filesList[0] : null;
        const susp = topCandidate ? (topCandidate.path || topCandidate) : 'Repository files scanned';
        const topGhUrl = buildSafeGhUrl(susp, topCandidate ? topCandidate.github_url : null, topCandidate ? topCandidate.branch : null);

        if (Array.isArray(ai.progress_trace) && ai.progress_trace.length) {
            ai.progress_trace.forEach(ev => applyAgentProgressEvent(ev));
        } else {
            appendTerminalLog('[REPLAY]', 'Loaded saved triage result.', 'text-slate-300');
            setStepDone(1); setStepDone(2); setStepDone(3); setStepDone(4); setStepDone(5);
        }
        document.getElementById('triageProgressBar').style.width = '100%';
        clearInterval(triageTimer);

        document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-emerald-600"></span> REPLAY COMPLETE`;
        document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-bold text-emerald-700 font-mono-code';

        document.getElementById('terminalResultKey').innerText = `#ABT-${bugId}`;
        const replayNeedsReview = Boolean(ai.needs_review) || '{{ session("ai_result.recommended_assignee", "Unassigned") }}' === 'Unassigned';
        const confEl = document.getElementById('resConfidence');
        if (confEl) confEl.innerText = replayNeedsReview ? 'Needs Manager Review' : `${ai.feature || 'General'} Triage`;
        document.getElementById('resAssignee').innerText = '{{ session("ai_result.recommended_assignee", "Developer") }}';
        const resDevNameLabelReplay = document.getElementById('resDevNameLabel');
        if (resDevNameLabelReplay) resDevNameLabelReplay.innerText = '{{ session("ai_result.recommended_assignee", "Developer") }}';
        const pVal = '{{ session("ai_result.priority", "Medium") }}';
        const resPriority = document.getElementById('resPriority');
        if (resPriority) {
            resPriority.innerText = pVal + ' Priority';
            const pLower = pVal.toLowerCase();
            const iconEl = document.getElementById('resPriorityIcon');
            if (pLower.includes('high') || pLower.includes('critical')) {
                resPriority.className = 'font-extrabold text-rose-600 text-xs sm:text-sm truncate block mt-0.5';
                if (iconEl) { iconEl.innerText = '⚡'; iconEl.className = 'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 text-lg border border-rose-200'; }
            } else if (pLower.includes('low')) {
                resPriority.className = 'font-extrabold text-slate-600 text-xs sm:text-sm truncate block mt-0.5';
                if (iconEl) { iconEl.innerText = '🟢'; iconEl.className = 'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-slate-600 text-lg border border-slate-200'; }
            } else {
                resPriority.className = 'font-extrabold text-amber-600 text-xs sm:text-sm truncate block mt-0.5';
                if (iconEl) { iconEl.innerText = '🟠'; iconEl.className = 'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 text-lg border border-amber-200'; }
            }
        }
        const simVal = {{ (int) session('ai_result.similarity_score', 0) }};
        const resSimilarity = document.getElementById('resSimilarity');
        if (resSimilarity) {
            const isDup = simVal > 75;
            resSimilarity.innerText = isDup ? 'Duplicate Issue' : 'Unique Issue';
            const simIconEl = document.getElementById('resSimilarityIcon');
            if (isDup) {
                resSimilarity.className = 'font-extrabold text-amber-600 text-xs sm:text-sm truncate block mt-0.5';
                if (simIconEl) { simIconEl.innerText = '⚠️'; simIconEl.className = 'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 text-lg border border-amber-200'; }
            } else {
                resSimilarity.className = 'font-extrabold text-emerald-600 text-xs sm:text-sm truncate block mt-0.5';
                if (simIconEl) { simIconEl.innerText = '🛡️'; simIconEl.className = 'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 text-lg border border-emerald-200'; }
            }
        }
        const culpritEl = document.getElementById('resCulprit');
        if (culpritEl) culpritEl.innerText = susp;
        const culpritGhLink = document.getElementById('resCulpritGhLink');
        if (culpritGhLink) {
            culpritGhLink.href = topGhUrl;
            culpritGhLink.classList.remove('hidden');
        }

        // Populate 4-Card Full Scan Visual Dashboard in Replay
        const scanEvidenceBadge = document.getElementById('scanEvidenceBadge');
        const scanEvidenceText = document.getElementById('scanEvidenceText');
        const scanEvidenceEngine = document.getElementById('scanEvidenceEngine');
        const scanEvidenceDot = document.getElementById('scanEvidenceDot');
        const scanEvidenceThumbWrapper = document.getElementById('scanEvidenceThumbWrapper');
        const scanEvidenceThumb = document.getElementById('scanEvidenceThumb');
        const scanEvidenceFileName = document.getElementById('scanEvidenceFileName');
        const scanEvidenceLink = document.getElementById('scanEvidenceLink');

        if (scanEvidenceBadge) {
            if (hasReplayScreenshot) {
                scanEvidenceBadge.innerHTML = '<span>⚡</span> Vision OCR';
                scanEvidenceBadge.className = 'inline-flex items-center gap-1 rounded-lg bg-purple-50 border border-purple-200 px-2 py-0.5 text-[10px] font-extrabold text-purple-700';
                if (scanEvidenceText) scanEvidenceText.innerText = 'Screenshot analyzed with Groq Vision';
                if (scanEvidenceEngine) scanEvidenceEngine.innerText = 'Llama-3.2-11B';
                if (scanEvidenceDot) scanEvidenceDot.className = 'h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse';

                if (scanEvidenceThumbWrapper) {
                    const previewSrc = ai.screenshot_url || `/bugs/${bugId}/screenshot`;
                    if (scanEvidenceThumb) scanEvidenceThumb.src = previewSrc;
                    if (scanEvidenceFileName) scanEvidenceFileName.innerText = 'Screenshot Proof';
                    if (scanEvidenceLink) {
                        scanEvidenceLink.href = previewSrc;
                        scanEvidenceLink.classList.remove('hidden');
                    }
                    scanEvidenceThumbWrapper.classList.remove('hidden');
                }

                const scanEvidenceOcrFindings = document.getElementById('scanEvidenceOcrFindings');
                const scanEvidenceOcrText = document.getElementById('scanEvidenceOcrText');
                const ocrFindingText = ai.code_help?.attachment_note?.visual_summary ||
                                      (Array.isArray(ai.visual_evidence) && ai.visual_evidence.length ? ai.visual_evidence.join(', ') : '') ||
                                      ai.extracted_text ||
                                      ai.code_help?.attachment_note?.extracted_text ||
                                      ai.code_help?.attachment_note?.error_message || '';
                if (scanEvidenceOcrFindings && scanEvidenceOcrText) {
                    scanEvidenceOcrFindings.classList.remove('hidden');
                    if (ocrFindingText && ocrFindingText.trim().length > 0) {
                        scanEvidenceOcrText.innerText = ocrFindingText;
                    } else {
                        scanEvidenceOcrText.innerText = 'Visual layout, UI controls & button boundaries identified (No raw crash traces).';
                    }
                }
            } else {
                scanEvidenceBadge.innerHTML = '<span>📝</span> NLP Tokenizer';
                scanEvidenceBadge.className = 'inline-flex items-center gap-1 rounded-lg bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-extrabold text-indigo-700';
                if (scanEvidenceText) scanEvidenceText.innerText = 'Natural language symptom parsed';
                if (scanEvidenceEngine) scanEvidenceEngine.innerText = 'Lexical NLP';
                if (scanEvidenceDot) scanEvidenceDot.className = 'h-1.5 w-1.5 rounded-full bg-indigo-500 animate-pulse';
                if (scanEvidenceThumbWrapper) scanEvidenceThumbWrapper.classList.add('hidden');
                const scanEvidenceOcrFindings = document.getElementById('scanEvidenceOcrFindings');
                if (scanEvidenceOcrFindings) scanEvidenceOcrFindings.classList.add('hidden');
            }
        }

        const scanCulpritName = document.getElementById('scanCulpritName');
        const scanCulpritPath = document.getElementById('scanCulpritPath');
        const scanCulpritGhLink = document.getElementById('scanCulpritGhLink');
        if (scanCulpritName) {
            const fileName = susp.split(/[\\/]/).pop() || susp;
            scanCulpritName.innerText = '📄 ' + fileName;
            scanCulpritName.title = susp;
        }
        if (scanCulpritPath) {
            scanCulpritPath.innerText = `🌿 main: ${susp}`;
        }
        if (scanCulpritGhLink) {
            if (topGhUrl) {
                scanCulpritGhLink.href = topGhUrl;
                scanCulpritGhLink.classList.remove('hidden');
            } else {
                scanCulpritGhLink.classList.add('hidden');
            }
        }

        const isDupReplay = replaySimScore > 75;
        const scanDupBadge = document.getElementById('scanDupBadge');
        const scanDupScore = document.getElementById('scanDupScore');
        const scanDupBar = document.getElementById('scanDupBar');
        if (scanDupBadge) {
            scanDupBadge.innerText = isDupReplay ? '⚠️ Duplicate Found' : '🛡️ Unique Issue';
            scanDupBadge.className = isDupReplay ? 'text-amber-800' : 'text-emerald-800';
        }
        if (scanDupScore) scanDupScore.innerText = `${replaySimScore}%`;
        if (scanDupBar) {
            scanDupBar.style.width = `${Math.min(100, Math.max(10, replaySimScore))}%`;
            scanDupBar.className = isDupReplay ? 'h-full bg-amber-500 transition-all duration-700' : 'h-full bg-emerald-500 transition-all duration-700';
        }

        const scanDevAvatar = document.getElementById('scanDevAvatar');
        const scanDevName = document.getElementById('scanDevName');
        const scanDevFit = document.getElementById('scanDevFit');
        const scanDevReason = document.getElementById('scanDevReason');
        const replayAssignee = '{{ session("ai_result.recommended_assignee", "Developer") }}';
        if (scanDevAvatar) scanDevAvatar.innerText = replayAssignee.charAt(0).toUpperCase();
        if (scanDevName) scanDevName.innerText = replayAssignee;
        if (scanDevFit) scanDevFit.innerText = `${confScore}% Fit`;
        if (scanDevReason) {
            scanDevReason.innerText = '{{ session("ai_result.code_help.assignment_reason", "Matched skills taxonomy & lowest active backlog load.") }}';
        }

        const xaiPriorityReason = document.getElementById('xaiPriorityReason');
        if (xaiPriorityReason) {
            const pLower = pVal.toLowerCase();
            if (pLower.includes('high') || pLower.includes('critical')) {
                xaiPriorityReason.innerText = 'Detected crash/fatal terms, error traces, or blocker signals affecting workflow.';
            } else if (pLower.includes('low')) {
                xaiPriorityReason.innerText = 'Minor or cosmetic anomaly with minimal operational impact.';
            } else {
                xaiPriorityReason.innerText = 'Standard functional defect detected without fatal system-level exceptions.';
            }
        }

        const xaiDevReason = document.getElementById('xaiDevReason');
        if (xaiDevReason) {
            xaiDevReason.innerText = '{{ session("ai_result.code_help.assignment_reason", "Matched skills taxonomy & lowest active backlog load.") }}';
        }

        const xaiDupReason = document.getElementById('xaiDupReason');
        if (xaiDupReason) {
            xaiDupReason.innerText = simVal > 75 
                ? `Cosine similarity ${simVal}% (> 75% threshold): Matches an existing issue in project backlog.` 
                : `Cosine similarity ${simVal}% (< 75% threshold): Verified unique defect with no matching existing issues.`;
        }

        // Suspected files list in modal
        const modalFilesWrapper = document.getElementById('modalSuspectedFilesWrapper');
        const modalFilesList = document.getElementById('modalSuspectedFilesList');
        if (modalFilesWrapper && modalFilesList) {
            modalFilesList.innerHTML = '';
            const listToRender = filesList.length ? filesList : [{
                path: susp,
                confidence: 92,
                reason: 'Primary repository component match',
                github_url: topGhUrl
            }];

            listToRender.forEach(fItem => {
                const fPath = fItem.path || fItem;
                const fConf = fItem.confidence || 92;
                const fReason = fItem.reason || 'Matched project component';
                const fUrl = buildSafeGhUrl(fPath, fItem.github_url, fItem.branch);

                const itemEl = document.createElement('div');
                itemEl.className = 'flex items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white p-2.5 hover:border-indigo-400 transition shadow-2xs';
                itemEl.innerHTML = `
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono-code text-xs font-bold text-slate-900 truncate">📄 ${fPath}</span>
                            <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.2 text-[10px] font-bold font-mono-code">${fConf}% Match</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-0.5 truncate">${fReason}</p>
                    </div>
                    <a href="${fUrl}" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 hover:text-indigo-600 px-2.5 py-1 text-[11px] font-bold text-slate-700 transition shrink-0 shadow-2xs">
                        <span>View on GitHub</span>
                        <span class="text-xs">↗</span>
                    </a>
                `;
                modalFilesList.appendChild(itemEl);
            });
            modalFilesWrapper.classList.remove('hidden');
        }

        document.getElementById('resIssueLink').href = `/bugs/${bugId}`;
        const liveScanner = document.getElementById('terminalLiveScanner');
        if (liveScanner) liveScanner.classList.add('hidden');
        document.getElementById('terminalResultCard').classList.remove('hidden');
    }
    @endif
</script>

    <style>
    @keyframes laserScanBeam {
        0% { top: 3%; opacity: 0.8; }
        50% { top: 88%; opacity: 1; }
        100% { top: 3%; opacity: 0.8; }
    }
    .animate-laser-scan {
        animation: laserScanBeam 2.2s ease-in-out infinite;
    }
    </style>


    <style id="abt-live-pipeline-redesign">
        #triageTerminalModal { overflow-y: auto; background: #f8fafc; }
        #triageTerminalModal:not(.hidden) { display: block; }
        #triageTerminalModal .abt-stepper-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }
        #triageTerminalModal .abt-sensor-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        #triageTerminalModal .abt-detection-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 16px;
            align-items: stretch;
        }
        #triageTerminalModal .abt-right-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 12px;
        }
        #triageTerminalModal .abt-console-box {
            background: #111827 !important;
            border: 1px solid #1f2937 !important;
            border-radius: 12px;
            padding: 14px;
            color: #e2e8f0;
        }
        @media (min-width: 640px) {
            #triageTerminalModal .abt-stepper-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
            #triageTerminalModal .abt-right-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 1023px) {
            #triageTerminalModal .abt-detection-grid {
                grid-template-columns: minmax(0, 1fr) !important;
            }
        }
        @media (max-width: 639px) {
            #triageTerminalModal .abt-right-grid {
                grid-template-columns: minmax(0, 1fr) !important;
            }
        }
        @media (min-width: 1024px) {
            #triageTerminalModal .abt-sensor-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
            #triageTerminalModal .abt-detection-grid {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            }
        }
    </style>

    <!-- Autonomous AI Triage Live Pipeline Full-Page View -->
    <div id="triageTerminalModal" class="hidden fixed inset-0 z-50 bg-slate-50 text-slate-900">
        <!-- Sticky Top Header Bar -->
        <header class="sticky top-0 z-20 border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-6">
                <button type="button" onclick="closeTriageModal()" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                    ← Back to Studio
                </button>
                <span class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                    AutoBugTriage Live Pipeline
                </span>
                <div class="flex items-center gap-2.5">
                    <span id="terminalTimer" class="font-mono-code rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700">[ 0.00s ]</span>
                    <div id="terminalStatusPill" class="flex items-center gap-1.5 rounded-full border border-indigo-200 bg-indigo-50/70 px-3 py-1 text-[11px] font-bold text-indigo-700">
                        <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        RUNNING PIPELINE
                    </div>
                </div>
            </div>
        </header>

        <div class="mx-auto flex max-w-6xl flex-col space-y-5 px-4 py-5 sm:px-6 sm:py-6">
            <!-- Target Anomaly Reported -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <span class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        TARGET ANOMALY REPORTED
                    </span>
                    <p id="terminalBugTitle" class="mt-0.5 text-lg sm:text-xl font-bold text-slate-900 leading-snug">Payment checkout 401 error</p>
                </div>
                <span class="shrink-0 rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-600">
                    4-PHASE EXPLAINABLE AI
                </span>
            </div>

            <!-- 4-Phase Stepper Tabs -->
            <div class="abt-stepper-grid">
                <button type="button" id="step-pill-1" data-name="Vision & OCR" onclick="focusLiveDetectionCard(1)" class="cursor-pointer rounded-xl border border-indigo-300 border-b-2 border-b-indigo-600 bg-white px-3 py-2.5 text-xs font-bold text-slate-900 transition flex items-center justify-center gap-2 shadow-2xs">
                    <span>1. Vision &amp; OCR</span>
                    <span class="h-3 w-3 rounded-full border-2 border-indigo-600 border-t-transparent animate-spin shrink-0"></span>
                </button>
                <button type="button" id="step-pill-2" data-name="GitHub Tree" onclick="focusLiveDetectionCard(2)" class="cursor-pointer rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 transition hover:border-slate-300 flex items-center justify-center gap-2">
                    <span>2. GitHub Tree</span>
                </button>
                <button type="button" id="step-pill-3" data-name="Vector Sim" onclick="focusLiveDetectionCard(3)" class="cursor-pointer rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 transition hover:border-slate-300 flex items-center justify-center gap-2">
                    <span>3. Vector Sim</span>
                </button>
                <button type="button" id="step-pill-4" data-name="Dev Routing" onclick="focusLiveDetectionCard(4)" class="cursor-pointer rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-600 transition hover:border-slate-300 flex items-center justify-center gap-2">
                    <span>4. Dev Routing</span>
                </button>
            </div>

            <!-- Thin Progress Bar -->
            <div class="w-full bg-slate-200 rounded-full h-1 overflow-hidden">
                <div id="triageProgressBar" class="bg-indigo-600 h-1 rounded-full transition-all duration-300" style="width: 10%"></div>
            </div>

            <!-- State 1: Main White Pipeline Card -->
            <div id="terminalLiveScanner" class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 space-y-5 shadow-xs">
                <!-- Stage Header -->
                <div>
                    <span id="liveStagePill" class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">STAGE 1 / 4</span>
                    <h3 id="liveStageTitle" class="text-base sm:text-lg font-bold text-slate-900 mt-0.5">
                        Vision OCR &amp; Lexical NLP Parser Active
                    </h3>
                    <p id="liveStageDesc" class="text-xs sm:text-sm text-slate-500 mt-0.5">
                        Analyzing natural language symptom patterns and extracting screenshot image tokens with Groq Vision Llama-3.2.
                    </p>
                </div>

                <!-- 4 Real-Time Telemetry Sensor Cards -->
                <div class="abt-sensor-grid">
                    <div class="min-w-0 rounded-xl border border-slate-200 bg-white p-3 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">VISION SENSOR:</span>
                            <span id="liveSensorVision" class="block text-xs font-bold text-slate-900 truncate mt-0.5">Llama-3.2-11B</span>
                        </div>
                        <span class="h-6 w-6 shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-[11px] text-slate-600">∿</span>
                    </div>
                    <div class="min-w-0 rounded-xl border border-slate-200 bg-white p-3 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">CODE INDEXER:</span>
                            <span id="liveSensorCode" class="block text-xs font-bold text-slate-900 truncate mt-0.5">Ready</span>
                        </div>
                        <span class="h-6 w-6 shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-[11px] text-slate-600">↻</span>
                    </div>
                    <div class="min-w-0 rounded-xl border border-slate-200 bg-white p-3 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">DEDUPLICATION:</span>
                            <span id="liveSensorDup" class="block text-xs font-bold text-slate-900 truncate mt-0.5">Standby</span>
                        </div>
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-slate-300"></span>
                    </div>
                    <div class="min-w-0 rounded-xl border border-slate-200 bg-white p-3 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">ROUTING ENGINE:</span>
                            <span id="liveSensorDev" class="block text-xs font-bold text-slate-900 truncate mt-0.5">Standby</span>
                        </div>
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-slate-300"></span>
                    </div>
                </div>

                <!-- 2-Column Asymmetric Live Detection Panels -->
                <div class="abt-detection-grid" style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 16px; width: 100%; align-items: stretch;">
                    <!-- Left Column: 1. Vision OCR & NLP Perceptual Core -->
                    <div id="liveCardNlp" style="width: 100%; min-width: 0;" class="min-w-0 rounded-xl border border-slate-900 bg-white p-4 space-y-3 flex flex-col justify-between shadow-2xs">
                        <div class="space-y-2.5 min-w-0">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="text-xs sm:text-sm font-bold text-slate-900">1. Vision OCR &amp; NLP Perceptual Core</span>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span id="liveTagNlp" class="rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide">
                                        PARSING TOKENS
                                    </span>
                                    <span id="liveOcrEngineName" class="rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 text-[10px] font-semibold">
                                        Llama-3.2-11B Active
                                    </span>
                                </div>
                            </div>

                            <!-- Optional Screenshot Laser Scanner Preview -->
                            <div id="liveOcrImageWrapper" class="hidden relative overflow-hidden rounded-xl border border-slate-200 bg-slate-950 max-h-36">
                                <img id="liveOcrPreviewImg" src="" alt="Live Screenshot Scan" class="w-full object-contain max-h-36 mx-auto opacity-90">
                                <div id="liveOcrLaser" class="absolute inset-x-0 h-0.5 bg-cyan-400 shadow-[0_0_10px_#22d3ee] animate-laser-scan pointer-events-none"></div>
                            </div>

                            <div>
                                <span class="text-[11px] font-medium text-slate-500 block mb-1.5">Extracted keyword pills:</span>
                                <div id="liveNlpTokens" class="flex flex-wrap gap-1.5">
                                    <span class="text-slate-400 text-[11px]">Tokenizing symptoms...</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5 text-[11px] text-slate-500 min-w-0">
                                <span class="shrink-0">Target:</span>
                                <span id="liveNlpTarget" class="font-semibold text-slate-800 truncate">"..."</span>
                            </div>
                        </div>

                        <!-- Clean Dark Console Output inside Card 1 -->
                        <div class="abt-console-box font-mono-code text-[11px] space-y-1 min-w-0" style="background-color: #111827; color: #e2e8f0; border: 1px solid #1f2937; border-radius: 12px; padding: 14px; width: 100%; min-width: 0;">
                            <div class="flex items-center justify-between gap-2 text-[10px] border-b border-slate-800 pb-1.5 mb-1.5">
                                <span id="liveDetectionPhaseBadge" class="text-slate-400 font-semibold truncate">STAGE 1 / 5: NLP &amp; OCR ACTIVE</span>
                                <span id="liveOcrStatusTag" class="text-emerald-400 font-semibold shrink-0">Scanning</span>
                            </div>
                            <div id="liveOcrStreamLog" class="text-slate-300 truncate">[Status] Initializing perceptual OCR tensor...</div>
                            <div id="liveAgentTimeline" class="space-y-1 max-h-32 overflow-y-auto custom-scroll"></div>
                        </div>
                    </div>

                    <!-- Right Column: GitHub Code Scan + Vector Sim & Dev Routing -->
                    <div class="abt-right-grid min-w-0" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; width: 100%; min-width: 0;">
                        <!-- 2. GitHub Tree Traversal -->
                        <div id="liveCardGh" style="grid-column: 1 / -1;" class="min-w-0 rounded-xl border border-slate-200 bg-slate-50/70 p-3.5 flex flex-col justify-between space-y-2 transition-all">
                            <div class="space-y-1.5 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-bold text-slate-900">2. GitHub Tree &amp; Code Inspection</span>
                                    <span id="liveTagGh" class="shrink-0 rounded-md bg-slate-200/80 text-slate-600 px-2 py-0.5 text-[10px] font-semibold">Standby</span>
                                </div>
                                <div id="liveGhScannedFile" class="text-xs text-slate-500 space-y-1">
                                    <span>Awaiting tree traversal...</span>
                                </div>
                            </div>
                            <div class="pt-2 border-t border-slate-200/70 flex items-center justify-between gap-2 text-[11px] text-slate-500">
                                <span>Branch: <b class="text-slate-700 font-medium">main</b></span>
                                <span id="liveGhMatchScore" class="font-medium text-slate-600 truncate max-w-[160px]">Pending scan</span>
                            </div>
                        </div>

                        <!-- 3. Vector Sim Duplicate Radar -->
                        <div id="liveCardVec" class="min-w-0 rounded-xl border border-slate-200 bg-slate-50/70 p-3.5 flex flex-col justify-between space-y-2 transition-all">
                            <div class="space-y-1.5 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-bold text-slate-900">3. Vector Sim Duplicate Radar</span>
                                    <span id="liveTagVec" class="shrink-0 rounded-md bg-slate-200/80 text-slate-600 px-2 py-0.5 text-[10px] font-semibold">Standby</span>
                                </div>
                                <p id="liveVecComparedBug" class="text-xs text-slate-500 truncate">Awaiting vector matrix...</p>
                                <p id="liveVecVerdict" class="text-[11px] text-slate-400">Pending</p>
                            </div>
                            <div class="space-y-1 pt-1">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                        <div id="liveVecBar" class="bg-indigo-600 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <span id="liveVecCosineVal" class="text-[11px] font-semibold text-slate-700 font-mono-code shrink-0">0%</span>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Developer Routing & Root Cause -->
                        <div id="liveCardDev" class="min-w-0 rounded-xl border border-slate-200 bg-slate-50/70 p-3.5 flex flex-col justify-between space-y-2 transition-all">
                            <div class="space-y-1.5 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-bold text-slate-900">4. Developer Routing &amp; Synthesis</span>
                                    <span id="liveTagDev" class="shrink-0 rounded-md bg-slate-200/80 text-slate-600 px-2 py-0.5 text-[10px] font-semibold">Standby</span>
                                </div>
                                <p id="liveDevSummary" class="text-xs text-slate-500">Awaiting skill &amp; workload match...</p>
                            </div>
                            <div class="pt-2 border-t border-slate-200/70 flex items-center justify-between gap-2 text-[11px] text-slate-500">
                                <span>Engine:</span>
                                <span class="font-medium text-slate-700">Skill &amp; LLM Code</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collapsible Raw Console Logs Accordion (Developer / Manager audit) -->
            <details class="{{ auth()->user()->role === 'tester' ? 'hidden' : 'group' }} rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                <summary class="flex cursor-pointer items-center justify-between px-4 py-2.5 text-xs font-mono font-bold text-slate-600 hover:text-indigo-600 select-none bg-slate-100/60">
                    <span class="flex items-center gap-2">
                        <span class="text-indigo-600 font-bold">&gt;_</span>
                        <span>View Raw Console Logs</span>
                    </span>
                    <span class="text-[10px] text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div id="terminalOutput" class="p-4 bg-slate-950 font-mono-code text-[11px] space-y-1.5 max-h-[160px] overflow-y-auto custom-scroll text-slate-300">
                    <!-- Log items appended dynamically -->
                </div>
            </details>

            <!-- State 2: Result Card (shown on completion with Signature Confidence Badge) -->
            <div id="terminalResultCard" class="hidden rounded-3xl border-2 border-emerald-300 bg-gradient-to-r from-emerald-50/70 via-white to-indigo-50/70 p-5 sm:p-6 space-y-4 shadow-sm">
                <!-- Signature Confidence Badge -->
                <div class="rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-600 to-blue-600 p-0.5 shadow-md">
                    <div class="rounded-[15px] bg-white p-4 sm:p-5 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 text-2xl border border-indigo-100 shadow-xs">
                                🎯
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 font-mono">Autonomous AI Diagnosis</span>
                                <p class="text-base sm:text-lg font-extrabold text-[#0F172A] flex items-center gap-2 flex-wrap">
                                    <span id="resConfidence">94% Match</span>
                                    <span class="text-slate-400 font-normal text-sm">— Assigned to</span>
                                    <span id="resAssignee" class="text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 rounded-lg text-sm font-bold">Alice Dev</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="resPriorityBadge" class="rounded-xl bg-amber-50 border border-amber-200 text-amber-800 font-extrabold text-xs sm:text-sm px-3 py-1.5 shadow-2xs">
                                Medium Priority
                            </span>
                            <span id="terminalResultKey" class="rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-700 font-mono font-extrabold text-xs sm:text-sm px-3 py-1.5 shadow-2xs">
                                #ABT-104
                            </span>
                        </div>
                    </div>
                </div>

                <!-- AI Agent Full Scan Investigation Trail (4-Phase Visual Cards) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                    
                    <!-- 1. Vision & Evidence -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 space-y-2.5 shadow-2xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 font-mono-code uppercase">
                                <span class="flex items-center gap-1.5 font-bold">
                                    <span id="scanEvidenceDot" class="h-2 w-2 rounded-full bg-purple-500 animate-pulse"></span>
                                    Phase 1: Evidence
                                </span>
                                <span class="text-base">📸</span>
                            </div>
                            <div class="mt-2">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span id="scanEvidenceBadge" class="inline-flex items-center gap-1 rounded-lg bg-purple-50 border border-purple-200 px-2.5 py-0.5 text-[11px] font-extrabold text-purple-700">
                                        <span>⚡</span> Vision OCR
                                    </span>
                                </div>
                                <p id="scanEvidenceText" class="text-xs text-slate-600 mt-1 leading-snug">Screenshot analyzed with Groq Vision</p>
                                <div id="scanEvidenceThumbWrapper" class="hidden mt-2.5 pt-2 border-t border-slate-100 space-y-2">
                                    <div class="flex items-center gap-2.5">
                                        <img id="scanEvidenceThumb" src="" alt="Screenshot" class="h-12 w-16 rounded-xl object-cover border border-purple-200 shadow-2xs shrink-0">
                                        <div class="min-w-0 flex-1">
                                            <span class="block text-xs font-bold text-slate-800 truncate" id="scanEvidenceFileName">screenshot.png</span>
                                            <a id="scanEvidenceLink" href="#" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 hover:underline mt-0.5">
                                                <span>Open Image</span>
                                                <span>↗</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="scanEvidenceOcrFindings" class="p-2 rounded-xl bg-purple-50/70 border border-purple-200/80 text-[10px] space-y-1">
                                        <div class="font-bold text-purple-900 flex items-center justify-between">
                                            <span class="flex items-center gap-1"><span>⚡</span> Vision OCR Extracted:</span>
                                            <span class="text-[9px] bg-purple-200/80 text-purple-900 font-mono px-1 rounded">Groq Llama-3.2</span>
                                        </div>
                                        <p id="scanEvidenceOcrText" class="text-slate-700 font-mono text-[9.5px] leading-relaxed break-words line-clamp-3">
                                            UI components & anomaly text extracted.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] font-mono text-slate-400">
                            <span id="scanEvidenceEngine">Llama-3.2-11B</span>
                            <span class="text-emerald-600 font-bold">✓ Parsed</span>
                        </div>
                    </div>

                    <!-- 2. Code Location -->
                    <div class="rounded-2xl border border-sky-200 bg-sky-50/50 p-4 space-y-2.5 shadow-2xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-[11px] text-sky-800 font-mono-code uppercase">
                                <span class="flex items-center gap-1.5 font-bold">
                                    <span class="h-2 w-2 rounded-full bg-sky-500 animate-pulse"></span>
                                    Phase 2: Code Search
                                </span>
                                <span class="text-base">🐙</span>
                            </div>
                            <div class="mt-2 space-y-1.5">
                                <div class="flex items-center justify-between gap-1.5 flex-wrap">
                                    <span id="scanCulpritName" class="inline-flex items-center gap-1 font-mono text-xs font-extrabold text-sky-950 truncate min-w-0 flex-1" title="Repository files">
                                        📄 forgot.php
                                    </span>
                                    <a id="scanCulpritGhLink" href="#" target="_blank" rel="noopener noreferrer" class="rounded-lg bg-sky-600 hover:bg-sky-500 text-white px-2 py-0.5 text-[10px] font-bold transition shrink-0 hidden shadow-2xs">
                                        ↗ GitHub
                                    </a>
                                </div>
                                <p id="scanCulpritPath" class="text-[11px] text-sky-700 font-mono truncate">🌿 main: Repo file tree</p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-sky-200/60 flex items-center justify-between text-[10px] font-mono text-sky-700">
                            <span>AST Root-Cause</span>
                            <span class="font-bold text-sky-900">✓ Isolated</span>
                        </div>
                    </div>

                    <!-- 3. Duplicate Score with Visual Progress Bar -->
                    <div id="scanDupCard" class="rounded-2xl border border-amber-200 bg-amber-50/50 p-4 space-y-2.5 shadow-2xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-[11px] text-amber-800 font-mono-code uppercase">
                                <span class="flex items-center gap-1.5 font-bold">
                                    <span id="scanDupDot" class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Phase 3: Duplicate
                                </span>
                                <span class="text-base">🔍</span>
                            </div>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span id="scanDupBadge" class="inline-flex items-center gap-1 rounded-lg bg-emerald-100 border border-emerald-200 px-2.5 py-0.5 text-[11px] font-extrabold text-emerald-800">
                                        🛡️ Unique Issue
                                    </span>
                                    <span id="scanDupScore" class="font-mono text-xs font-extrabold text-slate-800">
                                        38%
                                    </span>
                                </div>
                                <div class="w-full bg-slate-200/90 rounded-full h-2 overflow-hidden shadow-inner">
                                    <div id="scanDupProgressBar" class="bg-gradient-to-r from-emerald-500 to-teal-600 h-2 rounded-full transition-all duration-500" style="width: 38%"></div>
                                </div>
                                <p class="text-[10px] text-slate-500 flex justify-between font-mono">
                                    <span>TF-IDF Vector Sim</span>
                                    <span class="text-slate-500 font-bold">&lt; 75% limit</span>
                                </p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-amber-200/60 flex items-center justify-between text-[10px] font-mono text-amber-800">
                            <span>Cosine Distance</span>
                            <span id="scanDupStatus" class="font-bold text-emerald-700">✓ Evaluated</span>
                        </div>
                    </div>

                    <!-- 4. Developer Match Rationale -->
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-4 space-y-2.5 shadow-2xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-[11px] text-emerald-800 font-mono-code uppercase">
                                <span class="flex items-center gap-1.5 font-bold">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Phase 4: Dev Routing
                                </span>
                                <span class="text-base">👤</span>
                            </div>
                            <div class="mt-2 space-y-1.5">
                                <div class="flex items-center justify-between gap-1.5">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span id="scanDevAvatar" class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-emerald-600 to-teal-600 text-xs font-bold text-white shadow-2xs">
                                            A
                                        </span>
                                        <span id="scanDevName" class="text-xs sm:text-sm font-extrabold text-emerald-950 truncate">
                                            arif
                                        </span>
                                    </div>
                                    <span id="scanDevFit" class="inline-flex shrink-0 items-center rounded-md bg-emerald-100 border border-emerald-200 px-2 py-0.5 text-[10px] font-mono font-extrabold text-emerald-800">
                                        88% Fit
                                    </span>
                                </div>
                                <p id="scanDevReason" class="text-xs text-emerald-800 leading-snug line-clamp-2" title="Best tech-stack and capacity balance">
                                    Best tech-stack & capacity balance
                                </p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-emerald-200/60 flex items-center justify-between text-[10px] font-mono text-emerald-800">
                            <span>Workload Balanced</span>
                            <span class="font-bold text-emerald-800">✓ Dispatched</span>
                        </div>
                    </div>
                </div>

                <!-- Explainable AI (XAI) Autonomous Audit Breakdown -->
                <div class="rounded-2xl border border-indigo-100 bg-indigo-50/50 p-4 space-y-3 shadow-2xs">
                    <div class="flex items-center justify-between text-xs font-bold text-indigo-950">
                        <span class="flex items-center gap-2">
                            <span class="text-base">🧠</span>
                            <span class="text-xs sm:text-sm font-extrabold">Explainable AI (XAI) Autonomous Audit Breakdown</span>
                        </span>
                        <span class="text-[10px] text-indigo-700 bg-white border border-indigo-200 px-2.5 py-0.5 rounded-lg font-mono font-bold shadow-2xs">
                            Autonomous Audit
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 text-xs text-slate-700">
                        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-1">
                            <span class="font-extrabold text-slate-900 block text-xs">🔥 Priority Selection Logic:</span>
                            <p id="xaiPriorityReason" class="text-slate-600 text-xs leading-relaxed">Evaluated crash terms, exception traces, and user blocker signals.</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-1">
                            <span class="font-extrabold text-slate-900 block text-xs">🎯 Developer Routing Logic:</span>
                            <p id="xaiDevReason" class="text-slate-600 text-xs leading-relaxed">Matched skills taxonomy & lowest active backlog load.</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-1">
                            <span class="font-extrabold text-slate-900 block text-xs">🔍 Deduplication Vector Logic:</span>
                            <p id="xaiDupReason" class="text-slate-600 text-xs leading-relaxed">TF-IDF cosine similarity verified under duplicate threshold.</p>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role !== 'tester')
                <!-- Suspected files list in modal with GitHub buttons (Developers / Managers only) -->
                <div id="modalSuspectedFilesWrapper" class="hidden rounded-2xl border border-indigo-200 bg-indigo-50/40 p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800">
                            <span>🐙</span>
                            <span>AI Suspected Repository Culprit Files</span>
                        </div>
                        <span class="rounded-full bg-indigo-100 border border-indigo-200 px-2.5 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                            GitHub Linked
                        </span>
                    </div>
                    <div id="modalSuspectedFilesList" class="space-y-1.5 max-h-[140px] overflow-y-auto custom-scroll">
                    </div>
                </div>
                @endif

                <div class="flex flex-wrap items-center justify-end gap-3 pt-3 border-t border-slate-200/80">
                    <button type="button" onclick="submitAnotherReport()" class="rounded-xl border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 px-5 py-2.5 text-xs font-bold text-indigo-700 transition shadow-2xs hover:shadow-xs">
                        + Submit Another Bug
                    </button>
                    <a id="resKanbanLink" href="/workspace" class="rounded-xl border border-slate-300 bg-white hover:bg-slate-50 px-5 py-2.5 text-xs font-bold text-slate-700 transition shadow-2xs hover:shadow-xs">
                        ✓ View on Kanban
                    </a>
                    <a id="resIssueLink" href="/bugs/1" class="rounded-xl bg-slate-900 hover:bg-slate-800 px-6 py-2.5 text-xs font-bold text-white shadow-sm transition hover:shadow-md">
                        {{ auth()->user()->role === 'tester' ? 'View Bug Details ➔' : 'Open Issue & Fix Guidance ➔' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
<script>
    window.liveProjectId = @json($currentProjectId);
    document.addEventListener('DOMContentLoaded', () => {
      if (window.Echo && window.liveProjectId) {
        window.Echo.private(`project.${window.liveProjectId}`)
            .listen('.agent.progress', (event) => {
                applyAgentProgressEvent(event);
            })
            .listen('.bug.updated', (update) => {
            if (String(update.bug_id) !== String(@json(session('last_bug_id')))) return;
            const notice = document.createElement('div');
            notice.className = 'fixed bottom-5 right-5 z-50 rounded-xl border border-emerald-200 bg-white p-4 text-sm shadow-xl';
            notice.textContent = `Issue #ABT-${update.bug_id} triage finished. Refresh to view the result.`;
            const refresh = document.createElement('button');
            refresh.className = 'ml-3 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white';
            refresh.textContent = 'Refresh'; refresh.onclick = () => window.location.reload();
            notice.appendChild(refresh); document.body.appendChild(notice);
        });
      }
    });
</script>
</body>
</html>
