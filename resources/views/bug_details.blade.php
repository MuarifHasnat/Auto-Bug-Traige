<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>#ABT-{{ $bug->id }}: {{ $bug->title }} · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/js/app.js')
    @endif
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] selection:bg-indigo-500 selection:text-white bg-dot-grid">
    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-20 flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white/90 backdrop-blur-md px-5 lg:px-8">
        <div class="flex items-center gap-3">
            <a href="/workspace" class="inline-flex items-center gap-1.5 rounded-xl border border-[#E2E8F0] bg-white px-3 py-1.5 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition shadow-2xs">
                ← Back to Kanban
            </a>
            <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                <span>{{ session('project_name', 'Workspace') }}</span>
                <span class="text-slate-300">/</span>
                <span class="font-mono-code font-bold text-indigo-600">#ABT-{{ $bug->id }}</span>
            </div>
            @include('components.project-switcher', ['variant' => 'light'])
        </div>

        <div class="flex items-center gap-3">
            <a href="/bugs/{{ $bug->id }}/report" target="_blank" class="inline-flex items-center gap-1 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition shadow-2xs">
                <span>📄 Export PDF</span>
            </a>
            <div class="flex items-center gap-2 border-l border-slate-200 pl-3">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-600 text-[11px] font-bold text-white uppercase font-mono-code">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <span class="hidden md:inline text-xs font-bold text-slate-800">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-5 lg:px-8 pt-4">
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3.5 text-xs font-medium text-emerald-800 flex items-center gap-2 shadow-xs">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="max-w-7xl mx-auto px-5 lg:px-8 pt-4">
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-800 flex items-center gap-2 shadow-xs">
                <span>⚠</span>
                <span>{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 space-y-4">
        <!-- Header Hero Card -->
        <div class="rounded-2xl border border-[#E2E8F0] bg-white p-4 sm:p-5 shadow-xs">
            <div class="flex flex-wrap items-center justify-between gap-2.5 mb-2">
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="font-mono-code font-bold text-xs text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-lg">
                        #ABT-{{ $bug->id }}
                    </span>
                    <span class="rounded-lg px-2 py-0.5 text-xs font-bold {{ $bug->priority === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($bug->priority === 'Low' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                        {{ $bug->priority === 'High' ? '🔥 High Priority' : ($bug->priority === 'Low' ? '▼ Low Priority' : '● Medium Priority') }}
                    </span>
                    <span class="rounded-lg px-2 py-0.5 text-xs font-bold {{ $bug->status === 'Done' || $bug->status === 'Resolved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                        Status: {{ $bug->status }}
                    </span>
                    @if($bug->detected_feature)
                        <span class="rounded-lg bg-purple-50 border border-purple-200 px-2 py-0.5 text-xs font-bold text-purple-700">
                            ✨ Module: {{ $bug->detected_feature }}
                        </span>
                    @endif
                    @if($bug->is_duplicate)
                        <span class="rounded-lg bg-amber-50 border border-amber-200 px-2 py-0.5 text-xs font-bold text-amber-800">
                            ⚠️ Duplicate of #ABT-{{ $bug->duplicate_of }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <form action="{{ route('bugs.watch', [], false) }}" method="POST">
                        @csrf
                        <input type="hidden" name="bug_id" value="{{ $bug->id }}">
                        <button type="submit" class="rounded-lg border border-slate-200 bg-white hover:bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-700 transition shadow-2xs">
                            {{ $isWatching ? '★ Watching' : '☆ Watch Issue' }}
                        </button>
                    </form>
                </div>
            </div>

            <h1 class="text-lg sm:text-xl font-extrabold text-[#0F172A] leading-snug">
                {{ $bug->title }}
            </h1>

            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-[#64748B]">
                <span>Reported {{ \Carbon\Carbon::parse($bug->created_at)->diffForHumans() }}</span>
                <span>•</span>
                <span>Assigned to <b class="text-slate-800 font-bold">{{ $bug->assigned_to ?: 'Unassigned' }}</b></span>
                @if($bug->issue_type)
                    <span>•</span>
                    <span>Type: <b class="text-slate-800">{{ $bug->issue_type }}</b></span>
                @endif
            </div>
        </div>

        <template>
        <!-- Legacy triage summary replaced by the saved execution trace below. -->
        <section class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50/60 via-white to-purple-50/50 p-3.5 sm:p-4 shadow-xs">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-2.5 border-b border-indigo-100 pb-2">
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-600 text-white font-bold text-xs shadow-xs">⚡</span>
                    <div>
                        <h2 class="text-xs sm:text-sm font-extrabold text-[#0F172A] flex items-center gap-1.5">
                            <span>AI Agent Investigation Trail</span>
                            <span class="rounded bg-emerald-50 text-emerald-700 border border-emerald-200 px-1.5 py-0.2 text-[9px] font-mono-code font-bold">5-Phase Autonomous Audit</span>
                        </h2>
                        <p class="text-[10px] text-[#64748B]">Transparent breakdown of how the AI agent analyzed and triaged issue #ABT-{{ $bug->id }}:</p>
                    </div>
                </div>
                <span class="text-[10px] font-mono-code text-indigo-700 font-bold bg-white px-2 py-0.5 rounded-lg border border-indigo-200 shadow-2xs">
                    Triage State: {{ ucfirst($bug->triage_state ?? 'Completed') }}
                </span>
            </div>

            @php
                $codeHelp = $bug->triage['code_help'] ?? [];
                $suspectedFiles = $bug->triage['code_help']['suspected_files'] ?? ($bug->triage['suspected_files'] ?? []);
                $topCulprit = !empty($suspectedFiles) ? (is_array($suspectedFiles[0]) ? $suspectedFiles[0] : ['path' => $suspectedFiles[0]]) : null;
                $topCulpritPath = $topCulprit['path'] ?? null;
                $culpritBaseName = $topCulpritPath ? basename(str_replace('\\', '/', $topCulpritPath)) : null;
                $activeBranch = !empty($topCulprit['branch']) ? $topCulprit['branch'] : 'main';
                
                $simScore = (float) ($bug->duplicate_similarity_score ?? ($triageMetric->duplicate_similarity_score ?? ($bug->triage['similarity_score'] ?? 0)));
                $isDuplicate = $bug->is_duplicate || $simScore >= 75;

                $devMatchScore = $bug->triage['triage_confidence'] ?? ($bug->triage['confidence'] ?? null);
                if (is_numeric($devMatchScore) && $devMatchScore <= 1.0) {
                    $devMatchScore = round($devMatchScore * 100);
                }
                $assignmentBreakdown = $bug->triage['assignment_breakdown'] ?? ($codeHelp['assignment_breakdown'] ?? null);
                $needsReview = !empty($bug->triage['needs_review']) || ($bug->assigned_to === 'Unassigned');
                $topDupMatches = $bug->triage['top_duplicate_matches'] ?? ($codeHelp['top_duplicate_matches'] ?? []);
                $featureEv = $bug->triage['feature_evidence'] ?? ($codeHelp['feature_evidence'] ?? null);
                $priorityEv = $bug->triage['priority_evidence'] ?? ($codeHelp['priority_evidence'] ?? null);

                $baseSlug = !empty($projectRepo) ? preg_replace('#^https?://github\.com/#', '', rtrim($projectRepo, '/')) : 'MuarifHasnat/Auto-Bug-Traige';
                $baseSlug = preg_replace('/\.git$/', '', $baseSlug);
                $ghTargetUrl = null;
                if ($topCulpritPath) {
                    $cleanP = ltrim(str_replace('\\', '/', $topCulpritPath), '/');
                    $encodedP = implode('/', array_map('rawurlencode', explode('/', $cleanP)));
                    $ghTargetUrl = !empty($topCulprit['github_url']) ? $topCulprit['github_url'] : ('https://github.com/' . $baseSlug . '/blob/' . rawurlencode($activeBranch) . '/' . $encodedP);
                }
            @endphp

            <!-- 4 Breadcrumb Forensic Trail Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 text-xs">
                
                <!-- 1. Vision & Evidence -->
                <div class="rounded-2xl border border-slate-200 bg-white p-3.5 space-y-2 shadow-2xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-[10px] text-slate-500 font-mono-code uppercase">
                            <span class="flex items-center gap-1 font-bold">
                                <span class="h-1.5 w-1.5 rounded-full {{ $bug->screenshot_path ? 'bg-purple-500 animate-pulse' : 'bg-indigo-500' }}"></span>
                                Phase 1: Evidence
                            </span>
                            <span>📸</span>
                        </div>
                        
                        <div class="mt-2">
                            @if($bug->screenshot_path)
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-purple-50 border border-purple-200 px-2 py-0.5 text-[10px] font-extrabold text-purple-700">
                                        <span>⚡</span> Vision OCR
                                    </span>
                                    <a href="/bugs/{{ $bug->id }}/screenshot" target="_blank" class="inline-flex items-center gap-0.5 rounded-lg bg-slate-100 hover:bg-slate-200 px-1.5 py-0.5 text-[10px] font-bold text-slate-700 transition">
                                        View Proof ↗
                                    </a>
                                </div>
                                <p class="text-[10px] text-slate-500 mt-1 leading-tight">Screenshot analyzed with Groq Vision</p>
                            @else
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-extrabold text-indigo-700">
                                        <span>📝</span> NLP Tokenizer
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-mono">Text only</span>
                                </div>
                                <p class="text-[10px] text-slate-500 mt-1 leading-tight">Natural language symptom parsed</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[9px] font-mono text-slate-400">
                        <span>{{ $bug->screenshot_path ? 'Llama-3.2-11B' : 'Lexical NLP' }}</span>
                        <span class="text-emerald-600 font-bold">✓ Parsed</span>
                    </div>
                </div>

                <!-- 2. Code Location -->
                <div class="rounded-2xl border border-sky-200 bg-sky-50/50 p-3.5 space-y-2 shadow-2xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-[10px] text-sky-700 font-mono-code uppercase">
                            <span class="flex items-center gap-1 font-bold">
                                <span class="h-1.5 w-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                                Phase 2: Code Search
                            </span>
                            <span>🐙</span>
                        </div>

                        <div class="mt-2">
                            @if(!empty($topCulpritPath))
                                <div class="flex items-center justify-between gap-1">
                                    <span class="inline-flex items-center gap-1 font-mono text-xs font-bold text-sky-950 truncate max-w-[130px]" title="{{ $topCulpritPath }}">
                                        📄 {{ $culpritBaseName }}
                                    </span>
                                    @if($ghTargetUrl)
                                        <a href="{{ $ghTargetUrl }}" target="_blank" rel="noreferrer" class="rounded bg-sky-600 hover:bg-sky-500 text-white px-1.5 py-0.5 text-[9px] font-bold transition shrink-0">
                                            ↗ GitHub
                                        </a>
                                    @elseif(!empty($topCulprit['confidence']))
                                        <span class="rounded bg-sky-100 text-sky-800 px-1.5 py-0.5 text-[9px] font-bold font-mono">
                                            {{ $topCulprit['confidence'] }}%
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-sky-700 mt-1 truncate font-mono">🌿 {{ $activeBranch }}: {{ dirname(str_replace('\\', '/', $topCulpritPath)) }}</p>
                            @else
                                <div class="flex items-center gap-1.5">
                                    <span class="rounded-lg bg-sky-100 border border-sky-200 px-2 py-0.5 text-[10px] font-mono font-bold text-sky-800">
                                        🌿 Branch: {{ $activeBranch }}
                                    </span>
                                </div>
                                <p class="text-[10px] text-sky-700 mt-1 leading-tight">Repo file tree traversed & indexed</p>
                            @endif
                        </div>
                    </div>

                    <div class="pt-2 border-t border-sky-200/60 flex items-center justify-between text-[9px] font-mono text-sky-600">
                        <span>AST Root-Cause</span>
                        <span class="font-bold text-sky-800">✓ Isolated</span>
                    </div>
                </div>

                <!-- 3. Duplicate Score with Visual Progress Bar -->
                <div class="rounded-2xl border {{ $isDuplicate ? 'border-rose-200 bg-rose-50/50' : 'border-amber-200 bg-amber-50/50' }} p-3.5 space-y-2 shadow-2xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-[10px] {{ $isDuplicate ? 'text-rose-700' : 'text-amber-800' }} font-mono-code uppercase">
                            <span class="flex items-center gap-1 font-bold">
                                <span class="h-1.5 w-1.5 rounded-full {{ $isDuplicate ? 'bg-rose-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                                Phase 3: Duplicate Check
                            </span>
                            <span>🔍</span>
                        </div>

                        <div class="mt-2 space-y-1.5">
                            <div class="flex items-center justify-between">
                                @if($isDuplicate)
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-rose-100 border border-rose-200 px-2 py-0.5 text-[10px] font-extrabold text-rose-700">
                                        ⚠️ Duplicate (#ABT-{{ $bug->duplicate_of ?: 'Ref' }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-100 border border-emerald-200 px-2 py-0.5 text-[10px] font-extrabold text-emerald-800">
                                        🛡️ Unique Issue
                                    </span>
                                @endif
                                <span class="font-mono text-[10px] font-extrabold {{ $isDuplicate ? 'text-rose-700' : 'text-slate-700' }}">
                                    {{ number_format($simScore, 0) }}%
                                </span>
                            </div>

                            <!-- Visual Progress Bar -->
                            <div class="w-full bg-slate-200/80 rounded-full h-1.5 overflow-hidden">
                                <div class="{{ $isDuplicate ? 'bg-gradient-to-r from-amber-500 to-rose-600' : 'bg-gradient-to-r from-emerald-400 to-teal-600' }} h-1.5 rounded-full transition-all duration-500" style="width: {{ min(100, max(6, $simScore)) }}%"></div>
                            </div>
                            <p class="text-[9px] text-slate-500 flex justify-between font-mono">
                                <span>TF-IDF Vector Sim</span>
                                <span class="text-slate-400">&lt; 75% limit</span>
                            </p>
                        </div>
                    </div>

                    <div class="pt-2 border-t {{ $isDuplicate ? 'border-rose-200/60 text-rose-600' : 'border-amber-200/60 text-amber-700' }} flex items-center justify-between text-[9px] font-mono">
                        <span>Cosine Matched</span>
                        <span class="font-bold {{ $isDuplicate ? 'text-rose-700' : 'text-emerald-700' }}">✓ Evaluated</span>
                    </div>
                </div>

                <!-- 4. Developer Match Rationale -->
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-3.5 space-y-2 shadow-2xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-[10px] text-emerald-700 font-mono-code uppercase">
                            <span class="flex items-center gap-1 font-bold">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Phase 4: Dev Routing
                            </span>
                            <span>👤</span>
                        </div>

                        <div class="mt-2 space-y-1.5">
                            <div class="flex items-center justify-between gap-1.5">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-emerald-600 to-teal-600 text-[10px] font-bold text-white shadow-2xs">
                                        {{ strtoupper(substr($bug->assigned_to ?: 'A', 0, 1)) }}
                                    </span>
                                    <span class="text-xs font-extrabold text-emerald-950 truncate">
                                        {{ $bug->assigned_to ?: 'Auto-Matched' }}
                                    </span>
                                </div>
                                <span class="inline-flex shrink-0 items-center rounded-md {{ $needsReview ? 'bg-amber-100 border-amber-200 text-amber-800' : 'bg-emerald-100 border-emerald-200 text-emerald-800' }} border px-1.5 py-0.5 text-[9px] font-mono font-bold">
                                    @if($needsReview)
                                        Needs Review
                                    @elseif(is_numeric($devMatchScore))
                                        {{ $devMatchScore }}% Fit
                                    @elseif(!empty($assignmentBreakdown['total_score']))
                                        Score: {{ $assignmentBreakdown['total_score'] }} pts
                                    @else
                                        Routed
                                    @endif
                                </span>
                            </div>
                            
                            <p class="text-[10px] text-emerald-800 leading-tight truncate" title="{{ $codeHelp['assignment_reason'] ?? ($bug->triage['assignment_rationale'] ?? 'Matched skills & capacity') }}">
                                {{ $codeHelp['assignment_reason'] ?? ($bug->triage['assignment_rationale'] ?? 'Matched skills & capacity') }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-emerald-200/60 flex items-center justify-between text-[9px] font-mono text-emerald-700">
                        <span>Load Balanced</span>
                        <span class="font-bold text-emerald-800">✓ Dispatched</span>
                    </div>
                </div>

            </div>
        </section>
        </template>

        <!-- 2-Column Content Layout (65% / 35%) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
            
            <!-- Left 65% (col-span-8): Technical Report, Screenshots, AI Code Diagnostics & Collaboration -->
            <div class="lg:col-span-8 space-y-4">
                
                <!-- 1. Description & Screenshot Evidence -->
                <section class="rounded-2xl border border-[#E2E8F0] bg-white p-4 sm:p-5 shadow-xs space-y-3">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">
                        Problem Description & Reproduction Details
                    </h2>
                    <div class="text-xs text-[#334155] leading-relaxed whitespace-pre-line bg-[#F8FAFC] p-3 rounded-xl border border-[#E2E8F0]">
                        {{ $bug->description }}
                    </div>

                    @if($bug->screenshot_path)
                        <div class="mt-3 pt-3 border-t border-[#E2E8F0]">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-[#0F172A] flex items-center gap-1.5">
                                    <span>📸</span>
                                    <span>Attached Screenshot / Visual Evidence</span>
                                </span>
                                <a href="/bugs/{{ $bug->id }}/screenshot" target="_blank" class="text-[11px] font-bold text-indigo-600 hover:underline">
                                    Open Full Size ↗
                                </a>
                            </div>
                            <div class="rounded-xl border border-indigo-100 bg-[#F8FAFC] p-2.5 text-center">
                                <a href="/bugs/{{ $bug->id }}/screenshot" target="_blank">
                                    <img src="/bugs/{{ $bug->id }}/screenshot" alt="Error Screenshot" class="max-h-80 mx-auto rounded-lg object-contain shadow-xs hover:opacity-95 transition">
                                </a>
                            </div>
                        </div>
                    @endif
                </section>

                <!-- 2. Suspected Culprit Files on GitHub & 3. AI Code Diagnostics (Developers & Managers only - hidden for Testers) -->
                @if(auth()->user()->role !== 'tester')
                    @php
                        $suspectedFiles = $bug->triage['code_help']['suspected_files'] ?? ($bug->triage['suspected_files'] ?? []);
                    @endphp
                    @if(!empty($suspectedFiles))
                        <section class="rounded-2xl border border-sky-200 bg-sky-50/50 p-4 sm:p-4.5 shadow-xs">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-base">🔍</span>
                                <h2 class="text-xs sm:text-sm font-bold text-sky-950">Suspected Culprit Files (GitHub Root Cause Locator)</h2>
                            </div>
                            <p class="text-xs text-sky-800 leading-relaxed">
                                The AI agent scanned your repository file tree and matched keywords to identify the exact files where this bug likely originates:
                            </p>
                            <div class="mt-2.5 space-y-1.5">
                                @foreach($suspectedFiles as $file)
                                    @php
                                        $fPath = $file['path'] ?? (is_string($file) ? $file : '');
                                        $fConf = $file['confidence'] ?? 92;
                                        $fReason = $file['reason'] ?? 'Matched project repository component';
                                        $baseSlug = !empty($projectRepo) ? preg_replace('#^https?://github\.com/#', '', rtrim($projectRepo, '/')) : 'MuarifHasnat/Auto-Bug-Traige';
                                        $baseSlug = preg_replace('/\.git$/', '', $baseSlug);
                                        $cleanP = ltrim(str_replace('\\', '/', $fPath), '/');
                                        $encodedP = implode('/', array_map('rawurlencode', explode('/', $cleanP)));
                                        $fBranch = !empty($file['branch']) ? $file['branch'] : 'HEAD';
                                        $ghLink = !empty($file['github_url']) ? $file['github_url'] : ('https://github.com/' . $baseSlug . '/blob/' . rawurlencode($fBranch) . '/' . $encodedP);
                                    @endphp
                                    <div class="flex flex-wrap items-center justify-between gap-2.5 rounded-xl bg-white border border-sky-200 p-2.5 sm:p-3 shadow-2xs hover:border-indigo-400 transition">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono text-xs font-bold text-slate-900 truncate">📄 {{ $fPath }}</span>
                                                <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.2 text-[10px] font-bold font-mono-code">{{ $fConf }}% Match</span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-0.5 truncate">{{ $fReason }}</p>
                                        </div>
                                        <a href="{{ $ghLink }}" target="_blank" rel="noreferrer noopener" class="inline-flex items-center gap-1 rounded-lg bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-xs font-bold px-3 py-1.5 transition shadow-xs shrink-0">
                                            <span>Open in GitHub</span> ↗
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-2.5 p-2.5 rounded-lg bg-white/80 border border-sky-200 text-xs text-sky-950 flex items-start gap-2">
                                <span class="text-sm shrink-0">💡</span>
                                <span><b>Developer Guidance:</b> Click <b>"Open in GitHub"</b> on the suspected file to open it directly in the project's repository. Inspect the logic causing the anomaly shown in the screenshot, apply the necessary code changes, and mark the issue resolved on Kanban.</span>
                            </div>
                        </section>
                    @else
                        @php
                            $baseSlug = !empty($projectRepo) ? preg_replace('#^https?://github\.com/#', '', rtrim($projectRepo, '/')) : 'MuarifHasnat/Auto-Bug-Traige';
                            $baseSlug = preg_replace('/\.git$/', '', $baseSlug);
                            $repoUrl = 'https://github.com/' . $baseSlug;
                        @endphp
                        <section class="rounded-2xl border border-sky-200 bg-sky-50/50 p-4 sm:p-4.5 shadow-xs">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-base">🔍</span>
                                <h2 class="text-xs sm:text-sm font-bold text-sky-950">Project Repository & Developer Guidance</h2>
                            </div>
                            <p class="text-xs text-sky-800 leading-relaxed">
                                Explore the project's GitHub repository components to inspect code associated with {{ $bug->detected_feature ?? 'this feature' }}:
                            </p>
                            <div class="mt-2.5 flex flex-wrap items-center justify-between gap-2.5 rounded-xl bg-white border border-sky-200 p-2.5 sm:p-3 shadow-2xs">
                                <div class="min-w-0 flex-1">
                                    <span class="font-mono text-xs font-bold text-slate-900">📦 {{ $baseSlug }}</span>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Feature Component: {{ $bug->detected_feature ?? 'General' }}</p>
                                </div>
                                <a href="{{ $repoUrl }}" target="_blank" rel="noreferrer noopener" class="inline-flex items-center gap-1 rounded-lg bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-xs font-bold px-3 py-1.5 transition shadow-xs shrink-0">
                                    <span>Open in GitHub</span> ↗
                                </a>
                            </div>
                            <div class="mt-2.5 p-2.5 rounded-lg bg-white/80 border border-sky-200 text-xs text-sky-950 flex items-start gap-2">
                                <span class="text-sm shrink-0">💡</span>
                                <span><b>Developer Guidance:</b> Click <b>"Open in GitHub"</b> to view the repository, locate the relevant view/controller for {{ $bug->detected_feature ?? 'this component' }}, make the necessary code fixes, and move the task to Done.</span>
                            </div>
                        </section>
                    @endif

                    <!-- 3. AI Root Cause, Affected Lines & Fix Recommendations -->
                    @php
                        $codeHelp = $bug->triage['code_help'] ?? [];
                        $llmAnalysis = $codeHelp['llm_analysis'] ?? ($bug->triage['llm_analysis'] ?? []);
                        $hasLlmAnalysis = !empty($llmAnalysis) && empty($llmAnalysis['error']);
                        $affectedLocations = $hasLlmAnalysis ? ($llmAnalysis['affected_locations'] ?? []) : [];
                        $fixSuggestions = $codeHelp['fix_suggestions'] ?? [];
                        $hasSyntaxDiagnostics = (($codeHelp['syntax_status'] ?? 'not_run') !== 'not_run') || !empty($codeHelp['fixed_code']);
                        $hasCodeDiagnostics = !empty($codeHelp) && ($hasSyntaxDiagnostics || $hasLlmAnalysis || !empty($fixSuggestions));
                    @endphp
                    @if($hasCodeDiagnostics)
                        <section class="rounded-2xl border border-[#E2E8F0] bg-white p-4 sm:p-4.5 shadow-xs space-y-3">
                            <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-2">
                                <h2 class="text-xs sm:text-sm font-bold text-[#0F172A] flex items-center gap-1.5">
                                    <span>🤖</span>
                                    <span>AI Root Cause &amp; Fix Guidance</span>
                                </h2>
                                @if($hasSyntaxDiagnostics)
                                    <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2 py-0.2 text-[10px] font-bold text-emerald-700 font-mono-code">
                                        {{ ucfirst($codeHelp['syntax_status'] ?? 'Checked') }}
                                    </span>
                                @elseif($hasLlmAnalysis)
                                    <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 text-[10px] font-bold text-indigo-700">
                                        Code &amp; OCR Analyzed
                                    </span>
                                @endif
                            </div>

                            @if($hasSyntaxDiagnostics)
                                <!-- Validation Status -->
                                <div class="rounded-xl bg-indigo-50/60 border border-indigo-100 p-2.5 sm:p-3 text-xs space-y-0.5">
                                    <p class="font-bold text-indigo-950">Code Analysis Status:</p>
                                    <p class="font-mono-code text-indigo-900">{{ $codeHelp['error_name'] ?? 'Syntax check completed.' }}</p>
                                    @if(!empty($codeHelp['syntax_checker']))
                                        <p class="text-indigo-700 text-[10px]">Checker Engine: {{ $codeHelp['syntax_checker'] }}</p>
                                    @endif
                                </div>
                            @endif

                            @if($hasLlmAnalysis && !empty($llmAnalysis['root_cause']))
                                <div class="rounded-xl bg-slate-50 border border-slate-200 p-3 text-xs space-y-1">
                                    <p class="font-bold text-slate-900">Root Cause Analysis:</p>
                                    <p class="text-slate-700 leading-relaxed">{{ $llmAnalysis['root_cause'] }}</p>
                                </div>
                            @endif

                            @if(!empty($affectedLocations))
                                <div class="rounded-xl border border-indigo-200 bg-indigo-50/50 p-3 space-y-2">
                                    <p class="text-xs font-bold text-indigo-950">Affected Code Locations &amp; Line Numbers:</p>
                                    <div class="space-y-1.5">
                                        @foreach($affectedLocations as $loc)
                                            @php
                                                $locPath = $loc['path'] ?? '';
                                                $locLine = $loc['line'] ?? null;
                                                $locReason = $loc['reason'] ?? '';
                                                $matchedCandidate = collect($suspectedFiles)->firstWhere('path', $locPath);
                                                $locBaseSlug = !empty($projectRepo) ? preg_replace('#^https?://github\.com/#', '', rtrim($projectRepo, '/')) : 'MuarifHasnat/Auto-Bug-Traige';
                                                $locBaseSlug = preg_replace('/\.git$/', '', $locBaseSlug);
                                                $locBranch = is_array($matchedCandidate) && !empty($matchedCandidate['branch']) ? $matchedCandidate['branch'] : 'main';
                                                $locClean = ltrim(str_replace('\\', '/', $locPath), '/');
                                                $locEncoded = implode('/', array_map('rawurlencode', explode('/', $locClean)));
                                                $locUrl = (is_array($matchedCandidate) && !empty($matchedCandidate['github_url']))
                                                    ? $matchedCandidate['github_url']
                                                    : ('https://github.com/' . $locBaseSlug . '/blob/' . rawurlencode($locBranch) . '/' . $locEncoded);
                                                if ($locLine) {
                                                    $locUrl .= '#L' . intval($locLine);
                                                }
                                            @endphp
                                            <div class="flex flex-wrap items-start justify-between gap-2 rounded-lg bg-white border border-indigo-200 p-2.5 text-xs">
                                                <div class="min-w-0 flex-1 space-y-0.5">
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <span class="font-mono font-bold text-slate-900 break-all">📄 {{ $locPath }}</span>
                                                        @if($locLine)
                                                            <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.5 text-[10px] font-bold font-mono-code">Line {{ $locLine }}</span>
                                                        @endif
                                                    </div>
                                                    @if($locReason)
                                                        <p class="text-slate-600 leading-relaxed">{{ $locReason }}</p>
                                                    @endif
                                                </div>
                                                @if($locPath)
                                                    <a href="{{ $locUrl }}" target="_blank" rel="noreferrer noopener" class="inline-flex items-center gap-1 rounded-lg border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[11px] font-bold px-2.5 py-1 transition shrink-0">
                                                        <span>Jump to Line</span> ↗
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Fix Suggestions -->
                            @if(!empty($codeHelp['fix_suggestions']))
                                <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-3 space-y-1.5">
                                    <p class="text-xs font-bold text-amber-950">Suggested Fixes (Review before applying):</p>
                                    <div class="space-y-1.5">
                                        @foreach($codeHelp['fix_suggestions'] as $suggestion)
                                            <div class="rounded-lg bg-white border border-amber-200 p-2.5 text-xs">
                                                <p class="font-bold text-slate-800">{{ $suggestion['title'] ?? 'Fix Recommendation' }}</p>
                                                <p class="mt-0.5 text-slate-600 leading-relaxed">{{ $suggestion['detail'] ?? '' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-3 space-y-1.5">
                                    <p class="text-xs font-bold text-amber-950">Developer Action Recommendations:</p>
                                    <div class="space-y-1.5">
                                        <div class="rounded-lg bg-white border border-amber-200 p-2.5 text-xs">
                                            <p class="font-bold text-slate-800">1. Inspect Repository Component</p>
                                            <p class="mt-0.5 text-slate-600 leading-relaxed">Open the repository in GitHub to review event handlers, request validation, and view templates for {{ $bug->detected_feature ?? 'this feature' }}.</p>
                                        </div>
                                        <div class="rounded-lg bg-white border border-amber-200 p-2.5 text-xs">
                                            <p class="font-bold text-slate-800">2. Check Network & Exception Logs</p>
                                            <p class="mt-0.5 text-slate-600 leading-relaxed">Inspect server logs and browser network requests during {{ strtolower($bug->title) }} to verify endpoint responses and exception traces.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Step-by-Step Interactive Checklist (shown only when code syntax diagnostics ran) -->
                            @if($hasSyntaxDiagnostics && !empty($codeHelp['steps']))
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-1.5">Step-by-Step Resolution Checklist:</h3>
                                    <div class="space-y-1.5">
                                        @foreach($codeHelp['steps'] as $idx => $step)
                                            <label class="flex items-start gap-2 bg-[#F8FAFC] p-2.5 rounded-lg border border-[#E2E8F0] cursor-pointer hover:bg-white transition">
                                                <input type="checkbox" class="mt-0.5 h-3.5 w-3.5 rounded border-[#CBD5E1] text-indigo-600 focus:ring-indigo-500 cursor-pointer" onchange="this.closest('label').classList.toggle('opacity-50', this.checked)">
                                                <span class="text-xs font-medium text-slate-800 leading-snug">{{ $step }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Submitted Code Snippet (if any) -->
                            @if(!empty($codeHelp['fixed_code']))
                                <div class="pt-1.5">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Submitted Code Reference</span>
                                        <button type="button" onclick="copyCodeSnippet(this, 'codeBlock')" class="inline-flex items-center gap-1 rounded-md border border-slate-300 bg-white hover:bg-slate-50 px-2 py-0.5 text-xs font-bold text-indigo-600 transition shadow-2xs">
                                            <span class="copy-icon">📋</span> <span class="copy-text">Copy Fix</span>
                                        </button>
                                    </div>
                                    <pre id="codeBlock" class="rounded-xl bg-slate-950 p-3 text-xs font-mono-code text-emerald-400 overflow-x-auto border border-slate-800"><code>{{ $codeHelp['fixed_code'] }}</code></pre>
                                </div>
                            @endif
                        </section>
                    @endif
                @endif

                <!-- 4. Developer Clarification Requests & Team Discussion -->
                <section class="rounded-2xl border border-[#E2E8F0] bg-white p-4 sm:p-5 shadow-xs space-y-3">
                    <h2 class="text-xs sm:text-sm font-bold text-[#0F172A] flex items-center gap-1.5">
                        <span>💬</span>
                        <span>Team Discussion & Clarifications</span>
                    </h2>

                    <!-- Clarification Requests from Developers -->
                    <div class="space-y-2">
                        @foreach($clarifications as $req)
                            <div class="rounded-xl border {{ $req->answer ? 'border-slate-200 bg-[#F8FAFC]' : 'border-amber-300 bg-amber-50/90 shadow-2xs' }} p-3 space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold {{ $req->answer ? 'text-slate-800' : 'text-amber-950' }}">❓ Clarification Requested by {{ $req->requester_name }}</span>
                                        @if(!$req->answer)
                                            <span class="rounded-full bg-amber-200 text-amber-900 px-1.5 py-0.2 text-[9px] font-extrabold flex items-center gap-1">
                                                <span>⏳</span> Needs Info
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($req->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="text-slate-800 leading-relaxed font-medium bg-white/70 p-2 rounded-lg border border-amber-200/60">{{ $req->question }}</p>

                                @if($req->answer)
                                    <div class="mt-1.5 rounded-lg bg-white border border-emerald-200 p-2.5 shadow-2xs">
                                        <p class="font-bold text-emerald-800 text-[11px] flex items-center gap-1">
                                            <span>✓</span> Answered by {{ $req->answerer_name }}:
                                        </p>
                                        <p class="mt-0.5 text-slate-700 leading-relaxed">{{ $req->answer }}</p>
                                    </div>
                                @elseif(auth()->id() === $bug->reporter_id || auth()->user()->role === 'manager')
                                    <form action="{{ route('bugs.clarifications.answer', [], false) }}" method="POST" class="mt-1.5 space-y-1.5">
                                        @csrf
                                        <input type="hidden" name="request_id" value="{{ $req->id }}">
                                        <textarea name="answer" required rows="2" placeholder="Answer the developer's question…" class="w-full rounded-lg border border-amber-300 bg-white p-2.5 text-xs outline-none focus:border-amber-500"></textarea>
                                        <button type="submit" class="rounded-lg bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 px-3 py-1.5 text-xs font-bold text-white transition shadow-2xs flex items-center gap-1">
                                            <span>✓</span> Reply & Resolve
                                        </button>
                                    </form>
                                @else
                                    <p class="text-[11px] text-amber-800 italic">Waiting for reporter's response…</p>
                                @endif
                            </div>
                        @endforeach

                        @if(auth()->user()->role === 'developer' && $bug->assigned_to === auth()->user()->name)
                            <form action="{{ route('bugs.clarifications.request', [], false) }}" method="POST" class="rounded-xl border border-amber-200 bg-amber-50/40 p-3 space-y-1.5">
                                @csrf
                                <input type="hidden" name="bug_id" value="{{ $bug->id }}">
                                <label class="block text-xs font-bold text-amber-950">Ask reporter for clarification / missing steps:</label>
                                <textarea name="question" required rows="2" placeholder="e.g. Can you provide the browser version or specific test account used?" class="w-full rounded-lg border border-amber-200 bg-white p-2 text-xs outline-none focus:border-amber-500"></textarea>
                                <button type="submit" class="rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-amber-500 transition">Send Clarification Request</button>
                            </form>
                        @endif
                    </div>

                    <!-- Comments List -->
                    <div class="pt-3 border-t border-[#E2E8F0] space-y-2.5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Comments ({{ $comments->count() }})</h3>
                        <div class="space-y-2">
                            @forelse($comments as $comment)
                                <div class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] p-2.5 sm:p-3 text-xs space-y-0.5">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-[#0F172A]">{{ $comment->author_name }}</span>
                                        <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $comment->body }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic py-1">No comments yet. Post the first comment below.</p>
                            @endforelse
                        </div>

                        <!-- Post Comment Form -->
                        <form action="{{ route('bugs.comments', [], false) }}" method="POST" class="pt-1.5 space-y-1.5">
                            @csrf
                            <input type="hidden" name="bug_id" value="{{ $bug->id }}">
                            <textarea name="body" required rows="2" placeholder="Write a comment or mention a teammate…" class="w-full rounded-lg border border-[#CBD5E1] bg-white p-2.5 text-xs outline-none focus:border-indigo-600"></textarea>
                            <button type="submit" class="rounded-lg bg-indigo-600 hover:bg-indigo-500 px-3.5 py-1.5 text-xs font-bold text-white transition shadow-2xs">
                                Post Comment
                            </button>
                        </form>
                    </div>
                </section>

                <!-- Ask the Agent Chatbot -->
                <section id="bugAssistantPanel"
                         data-endpoint="/api/bugs/{{ $bug->id }}/assistant-chat"
                         data-followup-endpoint="/api/bugs/{{ $bug->id }}/assistant-follow-up"
                         data-testplan-endpoint="/api/bugs/{{ $bug->id }}/assistant-test-plan"
                         data-bug-id="{{ $bug->id }}"
                         data-can-clarify="{{ auth()->user()->role === 'developer' && $bug->assigned_to === auth()->user()->name ? '1' : '0' }}"
                         data-csrf="{{ csrf_token() }}"
                         class="rounded-2xl border border-indigo-200 bg-white p-4 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-900 flex items-center gap-1.5">
                                <span>💬</span> Ask the Agent
                            </h2>
                            <p class="text-[10px] text-slate-500">Grounded in saved triage evidence, files &amp; trace</p>
                        </div>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700">AI Q&amp;A</span>
                    </div>

                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" id="btnAssistantFollowUp" class="rounded-lg border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 text-[10px] font-bold text-indigo-700 transition">
                            ❓ Ask follow-up
                        </button>
                        <button type="button" id="btnAssistantTestPlan" class="rounded-lg border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 text-[10px] font-bold text-emerald-700 transition">
                            ✅ Generate test plan
                        </button>
                        <button type="button" data-question="Why was this priority and developer chosen?" class="assistant-quick-q rounded-lg border border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-200 px-2.5 py-1 text-[10px] font-semibold text-slate-700 transition">
                            Why this assignee &amp; priority?
                        </button>
                        <button type="button" data-question="Which files are suspected and what should I check first?" class="assistant-quick-q rounded-lg border border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-200 px-2.5 py-1 text-[10px] font-semibold text-slate-700 transition">
                            What should I check first?
                        </button>
                    </div>

                    <div id="bugAssistantFollowUpBox" class="hidden space-y-1.5"></div>
                    <div id="bugAssistantTestPlanBox" class="hidden space-y-1.5"></div>

                    <div id="bugAssistantMessages" class="space-y-2 max-h-60 overflow-y-auto pr-1 custom-scroll">
                        @forelse($assistantMessages ?? [] as $msg)
                            @if($msg->role === 'user')
                                <div class="flex justify-end">
                                    <div class="max-w-[85%] rounded-2xl rounded-br-sm bg-indigo-600 px-3.5 py-2.5 text-xs text-white shadow-2xs">
                                        <p class="whitespace-pre-wrap leading-relaxed">{{ $msg->content }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex justify-start">
                                    <div class="max-w-[90%] rounded-2xl rounded-bl-sm border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-800 shadow-2xs">
                                        <p class="whitespace-pre-wrap leading-relaxed">{{ $msg->content }}</p>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p id="bugAssistantEmpty" class="text-[11px] text-slate-400 italic py-2 text-center">
                                Ask a question about this bug's triage, suspected files, or duplicate check.
                            </p>
                        @endforelse
                    </div>

                    <div id="bugAssistantTyping" class="hidden flex items-center gap-2 text-[11px] text-indigo-600 font-medium px-1">
                        <span class="h-2 w-2 rounded-full bg-indigo-500 animate-ping"></span>
                        <span>Agent is reviewing saved evidence...</span>
                    </div>
                    <p id="bugAssistantError" class="hidden text-[11px] font-semibold text-rose-600 px-1"></p>

                    <form id="bugAssistantForm" class="flex items-center gap-2 pt-1 border-t border-slate-100">
                        <input id="bugAssistantInput" type="text" maxlength="1000" required
                               placeholder="Ask about #ABT-{{ $bug->id }}..."
                               class="min-w-0 flex-1 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs text-[#0F172A] outline-none transition focus:border-indigo-500 focus:bg-white">
                        <button id="bugAssistantSendBtn" type="submit"
                                class="shrink-0 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-3.5 py-2 text-xs font-bold text-white shadow-2xs transition">
                            Ask Agent
                        </button>
                    </form>
                </section>

                <!-- Saved Agent Execution Trace (Compact 2-Column Grid) -->
                @php
                    $rawTrace = $bug->triage['progress_trace'] ?? [];
                    $traceByStep = [];
                    if (is_array($rawTrace)) {
                        foreach ($rawTrace as $entry) {
                            if (!is_array($entry) || empty($entry['step'])) continue;
                            $traceByStep[$entry['step']] = $entry;
                        }
                    }
                @endphp
                @if(!empty($traceByStep))
                <section class="rounded-2xl border border-[#E2E8F0] bg-white p-4 shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Agent Execution Trace</h2>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700">{{ count($traceByStep) }} Steps</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($traceByStep as $traceItem)
                            @php
                                $tStatus = strtolower((string) ($traceItem['status'] ?? 'completed'));
                                $tBadge = match($tStatus) {
                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'skipped' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    'failed' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                };
                            @endphp
                            <div class="rounded-lg border border-[#E2E8F0] bg-[#F8FAFC] p-2.5 text-xs space-y-1">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-bold text-[#0F172A] truncate">{{ $traceItem['label'] ?? $traceItem['step'] }}</span>
                                    <span class="shrink-0 rounded border px-1.5 py-0.5 text-[9px] font-bold uppercase {{ $tBadge }}">{{ $tStatus }}</span>
                                </div>
                                @if(!empty($traceItem['message']))
                                    <p class="text-[11px] text-slate-600 leading-snug">{{ $traceItem['message'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif
            </div>

            <!-- Right 35% (col-span-4): Status Advance, Assignee, AI Rationale & Timeline (Sticky) -->
            <div class="lg:col-span-4 space-y-3.5 lg:sticky lg:top-20">
                
                <!-- 1. Quick Status Advance Widget -->
                <section class="rounded-2xl border border-[#E2E8F0] bg-white p-4 shadow-xs space-y-2.5">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Workflow State</h2>
                    <div class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] p-3 text-center">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#64748B] block">Current Stage</span>
                        <span class="mt-0.5 text-base font-extrabold text-[#0F172A] block">{{ $bug->status }}</span>
                    </div>

                    @if(auth()->user()->role === 'developer' && count($transitions) > 0)
                        <form action="/api/bugs/override" method="POST" class="space-y-1.5 pt-1">
                            @csrf
                            <input type="hidden" name="id" value="{{ $bug->id }}">
                            <label class="block text-xs font-bold text-[#0F172A]">Advance to Next Stage:</label>
                            <div class="flex gap-1.5">
                                <select name="status" required class="w-full rounded-lg border border-[#CBD5E1] bg-white px-2.5 py-1.5 text-xs font-semibold text-[#0F172A] outline-none focus:border-indigo-600">
                                    <option value="" disabled selected>Select new status…</option>
                                    @foreach($transitions as $nextStatus)
                                        <option value="{{ $nextStatus }}">{{ $nextStatus }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="rounded-lg bg-indigo-600 hover:bg-indigo-500 px-3.5 py-1.5 text-xs font-bold text-white transition shrink-0">
                                    Update
                                </button>
                            </div>
                        </form>
                    @endif
                </section>

                <!-- 2. Assignee & Management -->
                <section class="rounded-2xl border border-[#E2E8F0] bg-white p-4 shadow-xs space-y-2.5">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Assigned Developer</h2>
                    <div class="flex items-center gap-2.5 p-2.5 bg-[#F8FAFC] rounded-xl border border-[#E2E8F0]">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-bold font-mono-code text-xs">
                            {{ strtoupper(substr($bug->assigned_to ?: 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-xs text-[#0F172A] truncate">{{ $bug->assigned_to ?: 'Unassigned' }}</p>
                            <p class="text-[10px] text-[#64748B]">Responsible Engineer</p>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'manager')
                        <form action="{{ route('bugs.reassign', [], false) }}" method="POST" class="pt-1 space-y-1.5">
                            @csrf
                            <input type="hidden" name="id" value="{{ $bug->id }}">
                            <label class="block text-xs font-bold text-slate-800">Reassign Issue:</label>
                            <div class="flex gap-1.5">
                                <select name="assigned_to" class="w-full rounded-lg border border-[#CBD5E1] bg-white px-2.5 py-1.5 text-xs text-slate-800">
                                    <option value="Unassigned">Unassigned</option>
                                    @foreach($developers as $dev)
                                        <option value="{{ $dev->name }}" @selected($bug->assigned_to === $dev->name)>{{ $dev->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="rounded-lg bg-slate-800 hover:bg-slate-700 px-3 py-1.5 text-xs font-bold text-white transition shrink-0">Save</button>
                            </div>
                        </form>
                    @endif

                    <!-- Explainable AI (XAI) Assignment Rationale -->
                    <div class="pt-1 text-xs space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-800 flex items-center gap-1">
                                <span>🧠</span>
                                <span>Explainable AI (XAI) Decision</span>
                            </span>
                            <span class="rounded bg-indigo-100 text-indigo-700 px-1.5 py-0.2 text-[9px] font-mono font-bold">Audit Verified</span>
                        </div>
                        <div class="rounded-lg border border-indigo-100 bg-indigo-50/60 p-2.5 space-y-1.5 text-[11px] text-indigo-950">
                            <div>
                                <span class="font-bold text-indigo-900 block mb-0.5">Developer Match Reasoning:</span>
                                <p class="leading-relaxed text-indigo-900/90">
                                    {{ $codeHelp['assignment_reason'] ?? 'Selected based on skill keywords and available workload capacity.' }}
                                </p>
                            </div>
                            @if(!empty($bug->triage['similarity_score']))
                                <div class="pt-1 border-t border-indigo-200/60 flex items-center justify-between text-[10px]">
                                    <span class="text-indigo-800 font-medium">Duplicate Vector Distance:</span>
                                    <span class="font-mono font-bold {{ $bug->triage['similarity_score'] > 75 ? 'text-amber-700' : 'text-emerald-700' }}">
                                        {{ $bug->triage['similarity_score'] }}% Cosine Similarity
                                    </span>
                                </div>
                            @endif
                            @if(!empty($featureEv['reason']) || !empty($priorityEv['reason']))
                                <div class="pt-1.5 border-t border-indigo-200/60 space-y-1 text-[10px]">
                                    @if(!empty($featureEv['reason']))
                                        <p class="text-indigo-900/90"><b>Module Evidence:</b> {{ $featureEv['reason'] }}</p>
                                    @endif
                                    @if(!empty($priorityEv['reason']))
                                        <p class="text-indigo-900/90"><b>Priority Evidence:</b> {{ $priorityEv['reason'] }}</p>
                                    @endif
                                </div>
                            @endif
                            @if(!empty($assignmentBreakdown))
                                <div class="pt-1.5 border-t border-indigo-200/60 text-[10px] text-indigo-900/90">
                                    <b>Routing Score Breakdown:</b>
                                    Skills +{{ $assignmentBreakdown['skill_score'] ?? 0 }},
                                    Language +{{ $assignmentBreakdown['language_score'] ?? 0 }},
                                    Feature +{{ $assignmentBreakdown['feature_score'] ?? 0 }},
                                    History +{{ $assignmentBreakdown['history_score'] ?? 0 }},
                                    Load -{{ $assignmentBreakdown['workload_penalty'] ?? 0 }}
                                    (<b>Total: {{ $assignmentBreakdown['total_score'] ?? 0 }}</b>)
                                </div>
                            @endif
                            @if(!empty($topDupMatches))
                                <div class="pt-1.5 border-t border-indigo-200/60 space-y-1 text-[10px]">
                                    <span class="font-bold text-indigo-900 block">Top Matching Reports:</span>
                                    @foreach($topDupMatches as $match)
                                        <div class="flex items-center justify-between gap-2 text-indigo-900/90">
                                            <span class="truncate">#ABT-{{ $match['id'] ?? '?' }} · {{ $match['title'] ?? 'Issue' }}</span>
                                            <span class="font-mono font-bold shrink-0">{{ $match['similarity_score'] ?? 0 }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @php
                                $decisionCitations = $bug->triage['decision_citations'] ?? ($codeHelp['decision_citations'] ?? []);
                            @endphp
                            @if(!empty($decisionCitations))
                                <div class="pt-1.5 border-t border-indigo-200/60 space-y-1.5 text-[10px]">
                                    <span class="font-bold text-indigo-900 block">Evidence Citations:</span>
                                    @foreach($decisionCitations as $cit)
                                        @php
                                            $isAiInf = ($cit['evidence_type'] ?? '') === 'AI inference';
                                        @endphp
                                        <div class="rounded-lg bg-white/80 border border-indigo-200/70 p-1.5 space-y-0.5">
                                            <div class="flex items-center justify-between gap-1">
                                                <span class="font-bold text-slate-800">{{ $cit['decision'] ?? 'Decision' }}</span>
                                                <div class="flex items-center gap-1 shrink-0">
                                                    <span class="rounded bg-slate-100 border border-slate-200 px-1 py-0.2 font-mono text-[9px] text-slate-700">{{ $cit['source'] ?? 'report_text' }}</span>
                                                    <span class="rounded px-1 py-0.2 text-[9px] font-bold {{ $isAiInf ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                                                        {{ $cit['evidence_type'] ?? 'Observed' }}
                                                    </span>
                                                </div>
                                            </div>
                                            @if(!empty($cit['reference']))
                                                <p class="text-slate-600 leading-snug">{{ $cit['reference'] }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @if(auth()->user()->role === 'manager')
                            <div class="rounded-xl border border-slate-200 bg-[#F8FAFC] p-3 text-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-[#0F172A]">Manager Human Feedback</span>
                                    <span class="text-[10px] font-mono text-slate-500">Improves metrics</span>
                                </div>
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <form method="POST" action="/api/bugs/recommendation/accept">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $bug->id }}">
                                        <button type="submit" class="rounded-lg border border-emerald-300 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 text-[11px] font-bold text-emerald-800 transition">
                                            ✓ Accept Assignment{{ ($triageMetric->assignment_feedback ?? null) === 'accepted' ? ' (Saved)' : '' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="/api/bugs/duplicate-review">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $bug->id }}">
                                        <input type="hidden" name="actual_duplicate" value="1">
                                        <button type="submit" class="rounded-lg border border-amber-300 bg-amber-50 hover:bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-800 transition">
                                            Confirm Duplicate{{ ($triageMetric->actual_duplicate ?? null) === 1 || ($triageMetric->actual_duplicate ?? null) === true ? ' ✓' : '' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="/api/bugs/duplicate-review">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $bug->id }}">
                                        <input type="hidden" name="actual_duplicate" value="0">
                                        <button type="submit" class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-2.5 py-1 text-[11px] font-bold text-slate-700 transition">
                                            Mark Unique{{ isset($triageMetric->actual_duplicate) && !$triageMetric->actual_duplicate ? ' ✓' : '' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                <!-- 3. Activity History Timeline -->
                <section class="rounded-2xl border border-[#E2E8F0] bg-white p-4 shadow-xs space-y-2.5">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Activity Timeline</h2>
                    <div class="space-y-1.5 max-h-80 overflow-y-auto pr-1 custom-scroll">
                        @forelse($activities as $act)
                            <div class="rounded-lg border border-[#E2E8F0] bg-[#F8FAFC] p-2.5 text-xs space-y-0.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-[#0F172A]">{{ ucfirst(str_replace('_', ' ', $act->event_type)) }}</span>
                                    <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="text-slate-600 text-[11px]">
                                    {{ $act->actor_name }}: 
                                    @if($act->from_value)
                                        <span class="line-through text-slate-400">{{ $act->from_value }}</span> →
                                    @endif
                                    <span class="font-semibold text-slate-800">{{ $act->to_value }}</span>
                                </p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic py-1">No activity recorded yet.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script>
        function copyCodeSnippet(btn, elementId) {
            const el = document.getElementById(elementId);
            if (!el) return;
            navigator.clipboard.writeText(el.innerText).then(() => {
                const text = btn.querySelector('.copy-text');
                const icon = btn.querySelector('.copy-icon');
                if (text) text.innerText = 'Copied!';
                if (icon) icon.innerText = '✓';
                btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                setTimeout(() => {
                    if (text) text.innerText = 'Copy Fix';
                    if (icon) icon.innerText = '📋';
                    btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                }, 2000);
            });
        }

        // Fallback initializer when Vite dev/build has not re-bundled resources/js/bug-assistant.js
        (function () {
            const panel = document.getElementById('bugAssistantPanel');
            if (!panel || panel.dataset.initialized === 'true') return;
            panel.dataset.initialized = 'true';

            const form = document.getElementById('bugAssistantForm');
            const input = document.getElementById('bugAssistantInput');
            const sendBtn = document.getElementById('bugAssistantSendBtn');
            const messagesEl = document.getElementById('bugAssistantMessages');
            const emptyEl = document.getElementById('bugAssistantEmpty');
            const typingEl = document.getElementById('bugAssistantTyping');
            const errorEl = document.getElementById('bugAssistantError');
            const endpoint = panel.dataset.endpoint;
            const csrf = panel.dataset.csrf || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            if (!form || !input || !sendBtn || !messagesEl || !endpoint) return;

            const appendBubble = (role, content, metaText) => {
                const wrapper = document.createElement('div');
                wrapper.className = role === 'user' ? 'flex justify-end' : 'flex justify-start';
                const bubble = document.createElement('div');
                bubble.className = role === 'user'
                    ? 'max-w-[85%] rounded-2xl rounded-br-sm bg-indigo-600 px-3.5 py-2.5 text-xs text-white shadow-2xs'
                    : 'max-w-[90%] rounded-2xl rounded-bl-sm border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs text-slate-800 shadow-2xs space-y-1';
                const textEl = document.createElement('p');
                textEl.className = 'whitespace-pre-wrap leading-relaxed';
                textEl.textContent = content;
                bubble.appendChild(textEl);
                if (metaText) {
                    const metaEl = document.createElement('span');
                    metaEl.className = 'block text-[10px] font-mono text-slate-400';
                    metaEl.textContent = metaText;
                    bubble.appendChild(metaEl);
                }
                wrapper.appendChild(bubble);
                messagesEl.appendChild(wrapper);
                messagesEl.scrollTop = messagesEl.scrollHeight;
            };

            const sendQuestion = async (questionText) => {
                const question = String(questionText || '').trim();
                if (!question) return;
                if (emptyEl) emptyEl.classList.add('hidden');
                if (errorEl) { errorEl.classList.add('hidden'); errorEl.textContent = ''; }
                appendBubble('user', question, '');
                input.value = '';
                input.disabled = true;
                sendBtn.disabled = true;
                sendBtn.textContent = 'Thinking...';
                if (typingEl) typingEl.classList.remove('hidden');
                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ message: question }),
                    });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok || !data.success) {
                        const validationMsg = data.errors ? Object.values(data.errors).flat()[0] : null;
                        throw new Error(validationMsg || data.message || 'Assistant could not answer right now.');
                    }
                    const metaText = data.fallback_reason ? `Evidence fallback (${data.fallback_reason})` : 'Grounded in saved triage';
                    appendBubble('assistant', data.message?.content || 'No response recorded.', metaText);
                } catch (err) {
                    if (errorEl) {
                        errorEl.textContent = err.message || 'Failed to contact the assistant.';
                        errorEl.classList.remove('hidden');
                    }
                } finally {
                    if (typingEl) typingEl.classList.add('hidden');
                    input.disabled = false;
                    sendBtn.disabled = false;
                    sendBtn.textContent = 'Ask Agent';
                    input.focus();
                }
            };

            form.addEventListener('submit', (e) => { e.preventDefault(); sendQuestion(input.value); });
            panel.querySelectorAll('.assistant-quick-q').forEach((btn) => {
                btn.addEventListener('click', () => sendQuestion(btn.dataset.question || btn.textContent || ''));
            });

            const followUpBtn = document.getElementById('btnAssistantFollowUp');
            const testPlanBtn = document.getElementById('btnAssistantTestPlan');
            const followUpBox = document.getElementById('bugAssistantFollowUpBox');
            const testPlanBox = document.getElementById('bugAssistantTestPlanBox');
            const followUpEndpoint = panel.dataset.followupEndpoint;
            const testPlanEndpoint = panel.dataset.testplanEndpoint;
            const bugId = panel.dataset.bugId;
            const canClarify = panel.dataset.canClarify === '1';

            const callAction = async (url, onSuccess) => {
                if (!url) return;
                if (emptyEl) emptyEl.classList.add('hidden');
                if (errorEl) { errorEl.classList.add('hidden'); errorEl.textContent = ''; }
                input.disabled = true; sendBtn.disabled = true;
                if (followUpBtn) followUpBtn.disabled = true;
                if (testPlanBtn) testPlanBtn.disabled = true;
                if (typingEl) typingEl.classList.remove('hidden');
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({}),
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok || !data.success) throw new Error(data.message || 'Assistant request failed.');
                    onSuccess(data);
                    const metaText = data.fallback_reason ? `Evidence fallback (${data.fallback_reason})` : 'Grounded in saved triage';
                    appendBubble('assistant', data.summary_text || 'Done.', metaText);
                } catch (err) {
                    if (errorEl) { errorEl.textContent = err.message || 'Assistant request failed.'; errorEl.classList.remove('hidden'); }
                } finally {
                    if (typingEl) typingEl.classList.add('hidden');
                    input.disabled = false; sendBtn.disabled = false;
                    if (followUpBtn) followUpBtn.disabled = false;
                    if (testPlanBtn) testPlanBtn.disabled = false;
                }
            };

            if (followUpBtn && followUpEndpoint) {
                followUpBtn.addEventListener('click', () => callAction(followUpEndpoint, (data) => {
                    if (!followUpBox) return;
                    followUpBox.innerHTML = '';
                    const h = document.createElement('div');
                    h.className = 'text-[11px] font-bold text-indigo-900';
                    h.textContent = 'Suggested Follow-up Questions';
                    followUpBox.appendChild(h);
                    (data.questions || []).forEach((qItem) => {
                        const card = document.createElement('div');
                        card.className = 'rounded-xl border border-indigo-100 bg-indigo-50/40 p-2.5 space-y-1 text-[11px]';
                        const qt = document.createElement('p'); qt.className = 'font-semibold text-slate-900'; qt.textContent = qItem.question;
                        const qr = document.createElement('p'); qr.className = 'text-[10px] text-slate-600'; qr.textContent = `${qItem.reason} (${qItem.citation})`;
                        card.append(qt, qr);
                        if (canClarify && bugId) {
                            const cf = document.createElement('form');
                            cf.method = 'POST'; cf.action = '/api/bugs/clarifications'; cf.className = 'pt-1';
                            cf.onsubmit = () => window.confirm('Send this follow-up question as a formal clarification request to the reporter?');
                            cf.innerHTML = `<input type="hidden" name="_token" value="${csrf}"><input type="hidden" name="bug_id" value="${bugId}">`;
                            const qi = document.createElement('input'); qi.type = 'hidden'; qi.name = 'question'; qi.value = qItem.question;
                            const cb = document.createElement('button'); cb.type = 'submit'; cb.className = 'rounded-lg border border-indigo-300 bg-white hover:bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-700 transition'; cb.textContent = 'Send as Clarification';
                            cf.append(qi, cb);
                            card.appendChild(cf);
                        }
                        followUpBox.appendChild(card);
                    });
                    followUpBox.classList.remove('hidden');
                }));
            }

            if (testPlanBtn && testPlanEndpoint) {
                testPlanBtn.addEventListener('click', () => callAction(testPlanEndpoint, (data) => {
                    if (!testPlanBox) return;
                    testPlanBox.innerHTML = '';
                    const h = document.createElement('div');
                    h.className = 'text-[11px] font-bold text-emerald-900';
                    h.textContent = data.title || 'Verification Test Plan';
                    testPlanBox.appendChild(h);
                    (data.checklist || []).forEach((item) => {
                        const lbl = document.createElement('label');
                        lbl.className = 'flex items-start gap-2 rounded-xl border border-emerald-100 bg-emerald-50/40 p-2.5 text-[11px] cursor-pointer';
                        const cb = document.createElement('input'); cb.type = 'checkbox'; cb.className = 'mt-0.5 rounded border-emerald-400 text-emerald-600';
                        const bd = document.createElement('div'); bd.className = 'space-y-0.5 min-w-0 flex-1';
                        const st = document.createElement('p'); st.className = 'font-semibold text-slate-900'; st.textContent = item.step;
                        const ex = document.createElement('p'); ex.className = 'text-[10px] text-slate-600'; ex.textContent = `Expected: ${item.expected} [${item.citation}]`;
                        bd.append(st, ex);
                        lbl.append(cb, bd);
                        testPlanBox.appendChild(lbl);
                    });
                    testPlanBox.classList.remove('hidden');
                }));
            }
        })();
    </script>
</body>
</html>

