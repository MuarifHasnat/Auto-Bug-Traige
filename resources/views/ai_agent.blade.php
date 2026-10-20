<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Triage Studio · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    <!-- Left Sidebar (250px) -->
    <aside class="hidden w-[250px] shrink-0 flex-col bg-[#0F172A] text-white lg:flex border-r border-slate-800 z-20">
        <!-- Brand -->
        <div class="flex h-[60px] items-center justify-between border-b border-slate-800 px-5">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-black text-xs shadow-md shadow-indigo-600/30">
                    ⚡
                </div>
                <span class="text-sm font-extrabold tracking-tight text-white">AutoBug<span class="text-indigo-400">Triage</span></span>
            </div>
            <span class="rounded-full bg-indigo-500/15 border border-indigo-500/30 px-2 py-0.5 text-[10px] font-bold text-indigo-300 font-mono-code">
                AI v1.0
            </span>
        </div>

        <!-- Project Switcher Box -->
        <div class="border-b border-slate-800 p-4 bg-slate-950/60">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Current Workspace</p>
            <p class="mt-1 truncate font-bold text-white text-xs">{{ session('project_name', 'Software Project') }}</p>
            <p class="mt-0.5 truncate text-[10px] text-indigo-300 font-mono-code">{{ session('project_stack', 'Laravel • React • PGSQL') }}</p>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 space-y-1 p-3 text-xs">
            @if(auth()->user()->role === 'manager')
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/dashboard">
                    <span class="text-sm">▦</span>
                    <span>Command Center</span>
                </a>
            @endif

            @if(auth()->user()->role !== 'tester')
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/workspace">
                    <span class="text-sm">▤</span>
                    <span>Kanban Workspace</span>
                </a>
            @endif

            <a class="relative flex items-center gap-3 rounded-xl bg-indigo-600/20 text-indigo-300 px-3.5 py-2.5 font-bold transition border border-indigo-500/30" href="/ai-agent">
                <span class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-indigo-500"></span>
                <span class="text-sm">⚡</span>
                <span>AI Triage Studio</span>
            </a>

            @if(auth()->user()->role === 'manager')
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/add-developers">
                    <span class="text-sm">👥</span>
                    <span>Team & Skills Matrix</span>
                </a>
            @endif
        </nav>

        <!-- User Profile Footer -->
        <div class="border-t border-slate-800 p-4 bg-slate-950/80">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600/30 text-indigo-300 text-xs font-bold font-mono-code uppercase border border-indigo-500/30">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-white">{{ auth()->user()->name }}</p>
                    <span class="inline-block rounded px-1.5 py-0.2 text-[9px] font-bold bg-blue-500/20 text-blue-300 font-mono-code">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
                <a href="/logout" title="Sign Out" class="text-slate-400 hover:text-rose-400 p-1">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <main class="min-w-0 flex-1 flex flex-col">
        <!-- Sticky Top Header (60px) -->
        <header class="sticky top-0 z-10 flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white/90 backdrop-blur-md px-5 lg:px-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                <span>Workspace</span>
                <span class="text-slate-300">/</span>
                <span class="text-[#0F172A] font-bold">{{ session('project_name', 'Project') }}</span>
                <span class="text-slate-300">/</span>
                <span class="text-indigo-600">AI Triage Workbench</span>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-bold text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-mono-code">Python Triage Agent: Ready</span>
                </div>
                <a href="/logout" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                    Sign Out
                </a>
            </div>
        </header>

        <!-- Studio Body -->
        <div class="flex-1 mx-auto w-full max-w-7xl px-5 py-6 lg:px-8 space-y-6">
            <!-- Header Title -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-extrabold tracking-tight text-[#0F172A]">AI Triage Workbench</h1>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-3 py-0.5 text-xs font-bold text-indigo-700 font-mono-code">
                            Hero Studio
                        </span>
                    </div>
                    <p class="text-xs text-[#64748B] mt-0.5">Submit software anomalies for AST syntax inspection, duplicate hash checking, and automated engineer assignment.</p>
                </div>
                <span class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] px-3.5 py-1.5 text-xs font-bold text-[#0F172A]">
                    Mode: {{ ucfirst(auth()->user()->role) }} Console
                </span>
            </div>

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3.5 text-xs font-medium text-emerald-800 flex items-center gap-2.5 shadow-xs">
                    <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-800 flex items-start gap-2.5 shadow-xs">
                    <span class="text-rose-500 font-bold mt-0.5">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Split-Screen IDE Workbench: 45% Left / 55% Right -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Panel (45% / col-span-5): Tester Bug Input Console with 1-Click Templates & Paste Support -->
                <section class="lg:col-span-5 rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm">
                    <div class="border-b border-[#E2E8F0] pb-4 mb-4">
                        <h2 class="text-base font-bold text-[#0F172A]">Submit New Bug for AI Triage</h2>
                        <p class="text-xs text-[#64748B] mt-0.5">Fill out report details or attach crash snippets for AST diagnosis.</p>
                    </div>

                    <form id="triageForm" action="/api/bugs/store" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="handleTriageSubmit()">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1">Issue Summary / Title</label>
                            <input id="bugTitle" name="title" required maxlength="200" placeholder="e.g. ZeroDivisionError in invoice calculation logic" 
                                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white">
                        </div>

                        <!-- 1-Click Bug Description Templates UX -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-[#0F172A]">Description</label>
                                <div class="flex items-center gap-1">
                                    <span class="text-[10px] text-[#64748B] font-bold">1-Click Templates:</span>
                                    <button type="button" onclick="applyTemplate('ui')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-indigo-600 hover:bg-indigo-50">+ UI Glitch</button>
                                    <button type="button" onclick="applyTemplate('backend')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-rose-600 hover:bg-rose-50">+ 500 Crash</button>
                                    <button type="button" onclick="applyTemplate('db')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-amber-600 hover:bg-amber-50">+ Slow Query</button>
                                </div>
                            </div>
                            <textarea id="bugDescription" name="description" required rows="4" maxlength="5000" 
                                      placeholder="Steps to reproduce, expected behavior, actual crash stack trace..." 
                                      class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white"></textarea>
                        </div>

                        <!-- Mini Code Editor with Auto-Language Detection -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-[#0F172A]">Code Snippet</label>
                                <div class="flex items-center gap-2">
                                    <span id="detectedLangBadge" class="hidden rounded-full bg-emerald-50 border border-emerald-200 px-2 py-0.2 text-[10px] font-mono-code font-bold text-emerald-700">
                                        ✨ Auto-detected: Python
                                    </span>
                                    <select id="langSelect" name="language" class="rounded-lg border border-[#E2E8F0] bg-[#F8FAFC] px-2 py-1 text-[11px] font-semibold text-[#0F172A] outline-none focus:border-indigo-600">
                                        <option value="auto">Auto-Detect</option>
                                        <option>PHP</option>
                                        <option>Python</option>
                                        <option>JavaScript</option>
                                        <option>TypeScript</option>
                                        <option>C++</option>
                                        <option>C</option>
                                        <option>Java</option>
                                        <option>SQL</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Dark Code Editor with Line Number Gutter -->
                            <div class="code-editor-wrapper mt-1 border border-slate-800">
                                <div class="line-numbers">
                                    1<br>2<br>3<br>4<br>5<br>6<br>7<br>8
                                </div>
                                <textarea id="codeSnippetInput" name="code_snippet" rows="8" maxlength="20000" 
                                          oninput="detectLanguageRealtime(this.value)"
                                          placeholder="// Paste buggy function or crash snippet here...&#10;function calculateTax($subtotal, $rate) {&#10;    return $subtotal / $rate;&#10;}" 
                                          class="flex-1 bg-transparent p-3 font-mono-code text-xs leading-6 text-emerald-400 placeholder-slate-600 outline-none resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Dual File Dropzone with Clipboard Screenshot (Ctrl+V) Support -->
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <label id="screenshotDropzone" class="rounded-2xl border-2 border-dashed border-[#CBD5E1] p-3 text-center cursor-pointer hover:border-indigo-500 bg-[#F8FAFC] transition">
                                <span class="text-base block">📷</span>
                                <span class="text-xs font-bold text-[#0F172A] block mt-1" id="screenshotStatus">Screenshot / Paste</span>
                                <span class="text-[10px] text-[#64748B] block">Drop or press <b>Ctrl + V</b></span>
                                <input type="file" id="screenshotInput" name="screenshot" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="handleFileSelected(this, 'screenshotStatus')">
                            </label>

                            <label class="rounded-2xl border-2 border-dashed border-[#CBD5E1] p-3 text-center cursor-pointer hover:border-indigo-500 bg-[#F8FAFC] transition">
                                <span class="text-base block">📁</span>
                                <span class="text-xs font-bold text-[#0F172A] block mt-1" id="sourceStatus">Source File</span>
                                <span class="text-[10px] text-[#64748B] block">.py, .php, .js, .cpp</span>
                                <input type="file" id="sourceInput" name="source_file" accept=".txt,.php,.blade.php,.cpp,.c,.h,.hpp,.py,.js,.ts,.java,.sql,.json,.xml,.css,.html" class="hidden" onchange="handleFileSelected(this, 'sourceStatus')">
                            </label>
                        </div>

                        <!-- Submit Button with Loading State & Keyboard Shortcut Info -->
                        <div>
                            <button type="submit" id="submitTriageBtn" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 py-3 px-4 text-xs sm:text-sm font-bold text-white shadow-lg shadow-indigo-600/25 transition hover:from-indigo-500 hover:to-blue-500 active:scale-[0.99]">
                                <span>✨ Run AI Triage & Auto-Assign Developer</span>
                            </button>
                            <p class="text-center text-[10px] text-slate-400 mt-1 font-mono-code">
                                Pro Tip: Press <b>Ctrl + Enter</b> anywhere to triage
                            </p>
                        </div>
                    </form>
                </section>

                <!-- Right Panel (55% / col-span-7): AI Diagnostic & Auto-Fix Studio -->
                <section class="lg:col-span-7 rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm flex flex-col min-h-[580px]">
                    <div class="border-b border-[#E2E8F0] pb-4 mb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-[#0F172A]">AI Diagnostic & Auto-Fix Studio</h2>
                            <p class="text-xs text-[#64748B]">Real-time pipeline diagnostics, AST parser output, and code patch generator.</p>
                        </div>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 text-xs font-bold text-indigo-700 font-mono-code">
                            Diagnostics Engine
                        </span>
                    </div>

                    @if(session('ai_result'))
                        @php($ai = session('ai_result'))
                        <!-- State 2: Result State (4 Human-in-the-Loop Sections) -->
                        <div class="space-y-4 animate-fade-in flex-1">
                            
                            <!-- Section A: AI Triage Pipeline Bar with Confidence Gauge -->
                            <div class="rounded-2xl border border-indigo-200 bg-gradient-to-r from-indigo-50/60 to-purple-50/60 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-lg bg-blue-50 border border-blue-200 px-2.5 py-1 text-xs font-bold text-[#0052cc] font-mono-code">
                                            {{ $ai['code_help']['language'] ?? 'Auto' }}
                                        </span>
                                        <span class="rounded-lg bg-purple-50 border border-purple-200 px-2.5 py-1 text-xs font-bold text-purple-700">
                                            Module: {{ $ai['code_help']['feature'] ?? 'General' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-indigo-950 font-mono-code">
                                            {{ number_format(($ai['code_help']['confidence'] ?? 0.94) * 100) }}% Confidence
                                        </span>
                                    </div>
                                </div>
                                <!-- Confidence Gauge Bar -->
                                <div class="h-2 w-full rounded-full bg-indigo-200/60 overflow-hidden">
                                    <div class="h-2 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600" style="width: {{ max(10, ($ai['code_help']['confidence'] ?? 0.94) * 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Section B: Root Cause & Error Diagnosis Box -->
                            <div class="rounded-2xl border-2 border-rose-200 bg-rose-50/50 p-4 text-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2 text-rose-800 font-bold">
                                        <span class="h-2 w-2 rounded-full bg-rose-600 animate-pulse"></span>
                                        <span>Root Cause & Error Classification</span>
                                    </div>
                                    <span class="rounded bg-rose-100 px-1.5 py-0.5 text-[10px] font-bold text-rose-800">
                                        {{ $ai['priority'] ?? 'Medium' }} Priority
                                    </span>
                                </div>
                                <p class="font-mono-code text-xs font-bold text-rose-900 mt-1">
                                    ● {{ $ai['code_help']['error_name'] ?? 'Analysis complete' }}
                                </p>
                                <p class="text-[11px] text-rose-700 mt-1">
                                    Context: {{ $ai['code_help']['attachment_note'] ?? 'No external files provided.' }}
                                </p>
                            </div>

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

                                <!-- Code View Switcher: Final Code vs Diff -->
                                <div class="border border-[#E2E8F0] rounded-2xl p-3.5 bg-white">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2 text-xs font-bold">
                                            <button type="button" onclick="switchStudioCode('final')" id="btnStudioFinal" class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                [ Final Fixed Code Only ]
                                            </button>
                                            <button type="button" onclick="switchStudioCode('diff')" id="btnStudioDiff" class="px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50">
                                                [ Before vs After (Diff) ]
                                            </button>
                                        </div>

                                        <button type="button" onclick="copyStudioFix()" id="copyStudioBtn" 
                                                class="rounded-lg border border-[#CBD5E1] bg-white px-2.5 py-1 text-xs font-bold text-indigo-600 hover:bg-[#F1F5F9] transition">
                                            📋 Copy Fixed Code
                                        </button>
                                    </div>

                                    <div id="studioFinalView">
                                        <textarea id="studioFixedCode" readonly spellcheck="false" class="w-full min-h-[160px] rounded-xl bg-slate-950 p-3.5 font-mono-code text-xs leading-5 text-emerald-400 border border-slate-800 outline-none">{{ $ai['code_help']['fixed_code'] ?? '// No fixed code snippet generated.' }}</textarea>
                                    </div>

                                    <div id="studioDiffView" class="hidden">
                                        <textarea readonly spellcheck="false" class="w-full min-h-[160px] rounded-xl bg-slate-950 p-3.5 font-mono-code text-xs leading-5 text-indigo-300 border border-slate-800 outline-none">{{ $ai['code_help']['diff'] ?? '// No diff changes.' }}</textarea>
                                    </div>
                                </div>

                                <div class="pt-1 text-right">
                                    <a href="/workspace" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:underline">
                                        <span>Go to Kanban Workspace ➔</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- State 1: Empty / Ready State (AI System Blueprint Illustration) -->
                        <div class="flex-1 flex flex-col justify-center items-center py-8 text-center">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 border border-indigo-200 text-2xl text-indigo-600">
                                🤖
                            </div>
                            <h3 class="text-base font-extrabold text-[#0F172A]">AI System Blueprint Ready</h3>
                            <p class="mt-1 max-w-sm text-xs text-[#64748B] leading-relaxed">
                                Enter your bug summary or paste code on the left to trigger the 4-phase automated triage sequence:
                            </p>

                            <!-- 4 Pipeline Blueprint Cards -->
                            <div class="grid grid-cols-2 gap-3 mt-6 w-full max-w-md text-left">
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-white text-[10px] font-bold mb-1.5">1</span>
                                    <p class="text-xs font-bold text-[#0F172A]">AST Syntax Parsing</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Scans code tokens & exception signatures</p>
                                </div>
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-[10px] font-bold mb-1.5">2</span>
                                    <p class="text-xs font-bold text-[#0F172A]">Duplicate Checking</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Vector similarity with past project bugs</p>
                                </div>
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-purple-600 text-white text-[10px] font-bold mb-1.5">3</span>
                                    <p class="text-xs font-bold text-[#0F172A]">Severity & Feature</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Calculates Priority and Feature Module</p>
                                </div>
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-white text-[10px] font-bold mb-1.5">4</span>
                                    <p class="text-xs font-bold text-[#0F172A]">Skill-Based Routing</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Matches developer skills & workload capacity</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </main>
</div>

<script>
    // 1-Click Bug Description Templates
    function applyTemplate(type) {
        const desc = document.getElementById('bugDescription');
        const title = document.getElementById('bugTitle');
        if (type === 'ui') {
            title.value = title.value || 'UI Alignment glitch on mobile viewport';
            desc.value = `### 1. Steps to Reproduce:
- 1. Open page on mobile viewport (< 640px)
- 2. Observe navigation header overflow

### 2. Expected Result:
- Mobile drawer should toggle cleanly without horizontal scroll

### 3. Actual Error / Behavior:
- Header overflows by 24px and overlaps content`;
        } else if (type === 'backend') {
            title.value = title.value || '500 Internal Server Error in API endpoint';
            desc.value = `### 1. Steps to Reproduce:
- 1. Send POST request with missing authorization bearer token
- 2. Inspect server error response log

### 2. Expected Result:
- Should return 401 Unauthorized JSON response

### 3. Actual Error / Behavior:
- Server threw 500 Uncaught Exception: Call to a member function on null`;
        } else if (type === 'db') {
            title.value = title.value || 'Slow database query & N+1 problem on list endpoint';
            desc.value = `### 1. Steps to Reproduce:
- 1. Load workspace with 50+ issue records
- 2. Observe database query execution time

### 2. Expected Result:
- Queries should complete under 120ms using eager loading

### 3. Actual Error / Behavior:
- Multiple redundant SELECT queries causing 3.4s delay`;
        }
        desc.focus();
    }

    // Auto-Language Detection in Realtime
    function detectLanguageRealtime(code) {
        const badge = document.getElementById('detectedLangBadge');
        const select = document.getElementById('langSelect');
        if (!code || code.trim().length < 5) {
            badge.classList.add('hidden');
            return;
        }

        let detected = null;
        if (/<\?php|\$[a-zA-Z0-9_]+|->|namespace\s+/i.test(code)) {
            detected = 'PHP';
        } else if (/def\s+[a-zA-Z0-9_]+\(|import\s+[a-zA-Z0-9_]+|elif\s+|print\(/i.test(code)) {
            detected = 'Python';
        } else if (/interface\s+[A-Z]|:\s*string|:\s*number|readonly\s+/i.test(code)) {
            detected = 'TypeScript';
        } else if (/const\s+|let\s+|console\.log\(|function\s*\(|=>/i.test(code)) {
            detected = 'JavaScript';
        } else if (/#include\s*<|std::|cout\s*<</i.test(code)) {
            detected = 'C++';
        } else if (/SELECT\s+.+FROM|INSERT\s+INTO|UPDATE\s+.+SET/i.test(code)) {
            detected = 'SQL';
        }

        if (detected) {
            badge.innerText = '✨ Auto-detected: ' + detected;
            badge.classList.remove('hidden');
            if (select.value === 'auto') {
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].text.toLowerCase() === detected.toLowerCase()) {
                        select.selectedIndex = i;
                        break;
                    }
                }
            }
        } else {
            badge.classList.add('hidden');
        }
    }

    // Clipboard Screenshot Paste (Ctrl + V)
    window.addEventListener('paste', function(e) {
        const items = (e.clipboardData || e.originalEvent.clipboardData).items;
        for (let i = 0; i < items.length; i++) {
            if (items[i].type.indexOf('image') !== -1) {
                const blob = items[i].getAsFile();
                const fileInput = document.getElementById('screenshotInput');
                const dt = new DataTransfer();
                dt.items.add(blob);
                fileInput.files = dt.files;
                
                const status = document.getElementById('screenshotStatus');
                status.innerText = '✓ Screenshot Pasted (' + Math.round(blob.size / 1024) + ' KB)';
                status.parentElement.classList.add('border-emerald-500', 'bg-emerald-50/50');
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
                handleTriageSubmit();
            }
        }
    });

    // Button Loading State & Double Submit Prevention
    function handleTriageSubmit() {
        const btn = document.getElementById('submitTriageBtn');
        btn.innerHTML = `
            <span class="h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></span>
            <span>⏳ AI কোড পরীক্ষা করছে... একটু অপেক্ষা করুন</span>
        `;
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');
        document.getElementById('triageForm').submit();
    }

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
        setTimeout(() => btn.innerText = '📋 Copy Fixed Code', 2000);
    }
</script>
</body>
</html>
