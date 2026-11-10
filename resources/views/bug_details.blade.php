<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>#ABT-{{ $bug->id }}: {{ $bug->title }} · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
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

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-5 lg:px-8 pt-4">
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-800 flex items-center gap-2 shadow-xs">
                <span>⚠</span>
                <span>{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-5 lg:px-8 py-6 space-y-6">
        <!-- Header Hero Card -->
        <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-mono-code font-bold text-sm text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-xl">
                        #ABT-{{ $bug->id }}
                    </span>
                    <span class="rounded-xl px-2.5 py-1 text-xs font-bold {{ $bug->priority === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($bug->priority === 'Low' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                        {{ $bug->priority === 'High' ? '🔥 High Priority' : ($bug->priority === 'Low' ? '▼ Low Priority' : '● Medium Priority') }}
                    </span>
                    <span class="rounded-xl px-2.5 py-1 text-xs font-bold {{ $bug->status === 'Done' || $bug->status === 'Resolved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                        Status: {{ $bug->status }}
                    </span>
                    @if($bug->detected_feature)
                        <span class="rounded-xl bg-purple-50 border border-purple-200 px-2.5 py-1 text-xs font-bold text-purple-700">
                            ✨ Module: {{ $bug->detected_feature }}
                        </span>
                    @endif
                    @if($bug->is_duplicate)
                        <span class="rounded-xl bg-amber-50 border border-amber-200 px-2.5 py-1 text-xs font-bold text-amber-800">
                            ⚠️ Duplicate of #ABT-{{ $bug->duplicate_of }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <form action="{{ route('bugs.watch', [], false) }}" method="POST">
                        @csrf
                        <input type="hidden" name="bug_id" value="{{ $bug->id }}">
                        <button type="submit" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 transition">
                            {{ $isWatching ? '★ Watching' : '☆ Watch Issue' }}
                        </button>
                    </form>
                </div>
            </div>

            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] leading-snug">
                {{ $bug->title }}
            </h1>

            <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-[#64748B]">
                <span>Reported {{ \Carbon\Carbon::parse($bug->created_at)->diffForHumans() }}</span>
                <span>•</span>
                <span>Assigned to <b class="text-slate-800 font-bold">{{ $bug->assigned_to ?: 'Unassigned' }}</b></span>
                @if($bug->issue_type)
                    <span>•</span>
                    <span>Type: <b class="text-slate-800">{{ $bug->issue_type }}</b></span>
                @endif
            </div>
        </div>

        <!-- 2-Column Content Layout (65% / 35%) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left 65% (col-span-8): Technical Report, Screenshots, AI Code Diagnostics & Collaboration -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- 1. Description & Screenshot Evidence -->
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#64748B]">
                        Problem Description & Reproduction Details
                    </h2>
                    <div class="text-xs text-[#334155] leading-relaxed whitespace-pre-line bg-[#F8FAFC] p-4 rounded-2xl border border-[#E2E8F0]">
                        {{ $bug->description }}
                    </div>

                    @if($bug->screenshot_path)
                        <div class="mt-4 pt-4 border-t border-[#E2E8F0]">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-[#0F172A] flex items-center gap-1.5">
                                    <span>📸</span>
                                    <span>Attached Screenshot / Visual Evidence</span>
                                </span>
                                <a href="/bugs/{{ $bug->id }}/screenshot" target="_blank" class="text-[11px] font-bold text-indigo-600 hover:underline">
                                    Open Full Size ↗
                                </a>
                            </div>
                            <div class="rounded-2xl border border-indigo-100 bg-[#F8FAFC] p-3 text-center">
                                <a href="/bugs/{{ $bug->id }}/screenshot" target="_blank">
                                    <img src="/bugs/{{ $bug->id }}/screenshot" alt="Error Screenshot" class="max-h-80 mx-auto rounded-xl object-contain shadow-xs hover:opacity-95 transition">
                                </a>
                            </div>
                        </div>
                    @endif
                </section>

                <!-- 2. Suspected Culprit Files on GitHub (Root Cause Locator) -->
                @php
                    $suspectedFiles = $bug->triage['code_help']['suspected_files'] ?? ($bug->triage['suspected_files'] ?? []);
                @endphp
                @if(!empty($suspectedFiles))
                    <section class="rounded-3xl border border-sky-200 bg-sky-50/50 p-6 shadow-xs">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-lg">🔍</span>
                            <h2 class="text-sm font-bold text-sky-950">Suspected Culprit Files (GitHub Root Cause Locator)</h2>
                        </div>
                        <p class="text-xs text-sky-800 leading-relaxed">
                            The AI agent scanned your repository file tree and matched keywords to identify the exact files where this bug likely originates:
                        </p>
                        <div class="mt-3 space-y-2">
                            @foreach($suspectedFiles as $file)
                                <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl bg-white border border-sky-200 p-3 shadow-2xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="text-slate-400 font-mono text-xs">📄</span>
                                        <span class="font-mono text-xs font-bold text-slate-800 truncate">{{ $file['path'] ?? $file }}</span>
                                    </div>
                                    @php
                                        $fPath = $file['path'] ?? (is_string($file) ? $file : '');
                                        $ghLink = !empty($file['github_url']) ? $file['github_url'] : ('https://github.com/MuarifHasnat/Auto-Bug-Traige/blob/main/' . ltrim($fPath, '/'));
                                    @endphp
                                    <a href="{{ $ghLink }}" target="_blank" rel="noreferrer noopener" class="inline-flex items-center gap-1 rounded-lg bg-sky-100 hover:bg-sky-200 text-sky-900 text-xs font-bold px-3 py-1.5 transition">
                                        <span>Inspect on GitHub</span> ↗
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- 3. AI Code Diagnostics & Fix Recommendations -->
                @php
                    $codeHelp = $bug->triage['code_help'] ?? [];
                @endphp
                @if(!empty($codeHelp))
                    <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-4">
                        <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                            <h2 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                                <span>🤖</span>
                                <span>AI Diagnostics & Fix Guidance</span>
                            </h2>
                            <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-[10px] font-bold text-emerald-700 font-mono-code">
                                {{ ucfirst($codeHelp['syntax_status'] ?? 'Checked') }}
                            </span>
                        </div>

                        <!-- Validation Status -->
                        <div class="rounded-2xl bg-indigo-50/60 border border-indigo-100 p-4 text-xs space-y-1">
                            <p class="font-bold text-indigo-950">Code Analysis Status:</p>
                            <p class="font-mono-code text-indigo-900">{{ $codeHelp['error_name'] ?? 'Syntax check completed.' }}</p>
                            @if(!empty($codeHelp['syntax_checker']))
                                <p class="text-indigo-700 text-[11px]">Checker Engine: {{ $codeHelp['syntax_checker'] }}</p>
                            @endif
                        </div>

                        <!-- Fix Suggestions -->
                        @if(!empty($codeHelp['fix_suggestions']))
                            <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4 space-y-2">
                                <p class="text-xs font-bold text-amber-950">Suggested Fixes (Review before applying):</p>
                                <div class="space-y-2">
                                    @foreach($codeHelp['fix_suggestions'] as $suggestion)
                                        <div class="rounded-xl bg-white border border-amber-200 p-3 text-xs">
                                            <p class="font-bold text-slate-800">{{ $suggestion['title'] ?? 'Fix Recommendation' }}</p>
                                            <p class="mt-1 text-slate-600">{{ $suggestion['detail'] ?? '' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Step-by-Step Interactive Checklist -->
                        @if(!empty($codeHelp['steps']))
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-2">Step-by-Step Resolution Checklist:</h3>
                                <div class="space-y-2">
                                    @foreach($codeHelp['steps'] as $idx => $step)
                                        <label class="flex items-start gap-2.5 bg-[#F8FAFC] p-3 rounded-xl border border-[#E2E8F0] cursor-pointer hover:bg-white transition">
                                            <input type="checkbox" class="mt-0.5 h-4 w-4 rounded border-[#CBD5E1] text-indigo-600 focus:ring-indigo-500 cursor-pointer" onchange="this.closest('label').classList.toggle('opacity-50', this.checked)">
                                            <span class="text-xs font-medium text-slate-800 leading-relaxed">{{ $step }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Submitted Code Snippet (if any) -->
                        @if(!empty($codeHelp['fixed_code']))
                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Submitted Code Reference</span>
                                    <button type="button" onclick="copyCodeSnippet(this, 'codeBlock')" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-2.5 py-1 text-xs font-bold text-indigo-600 transition shadow-2xs">
                                        <span class="copy-icon">📋</span> <span class="copy-text">Copy Fix</span>
                                    </button>
                                </div>
                                <pre id="codeBlock" class="rounded-2xl bg-slate-950 p-4 text-xs font-mono-code text-emerald-400 overflow-x-auto border border-slate-800"><code>{{ $codeHelp['fixed_code'] }}</code></pre>
                            </div>
                        @endif
                    </section>
                @endif

                <!-- 4. Developer Clarification Requests & Team Discussion -->
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-4">
                    <h2 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                        <span>💬</span>
                        <span>Team Discussion & Clarifications</span>
                    </h2>

                    <!-- Clarification Requests from Developers -->
                    <div class="space-y-3">
                        @foreach($clarifications as $req)
                            <div class="rounded-2xl border {{ $req->answer ? 'border-slate-200 bg-[#F8FAFC]' : 'border-amber-300 bg-amber-50/90 shadow-sm' }} p-4 space-y-3 text-xs">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold {{ $req->answer ? 'text-slate-800' : 'text-amber-950' }}">❓ Clarification Requested by {{ $req->requester_name }}</span>
                                        @if(!$req->answer)
                                            <span class="rounded-full bg-amber-200 text-amber-900 px-2 py-0.5 text-[10px] font-extrabold flex items-center gap-1">
                                                <span>⏳</span> Needs Info
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($req->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="text-slate-800 leading-relaxed font-medium bg-white/70 p-2.5 rounded-xl border border-amber-200/60">{{ $req->question }}</p>

                                @if($req->answer)
                                    <div class="mt-2 rounded-xl bg-white border border-emerald-200 p-3 shadow-2xs">
                                        <p class="font-bold text-emerald-800 text-[11px] flex items-center gap-1.5">
                                            <span>✓</span> Answered by {{ $req->answerer_name }}:
                                        </p>
                                        <p class="mt-1 text-slate-700 leading-relaxed">{{ $req->answer }}</p>
                                    </div>
                                @elseif(auth()->id() === $bug->reporter_id || auth()->user()->role === 'manager')
                                    <form action="{{ route('bugs.clarifications.answer', [], false) }}" method="POST" class="mt-2 space-y-2">
                                        @csrf
                                        <input type="hidden" name="request_id" value="{{ $req->id }}">
                                        <textarea name="answer" required rows="2" placeholder="Answer the developer's question…" class="w-full rounded-xl border border-amber-300 bg-white p-3 text-xs outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10"></textarea>
                                        <button type="submit" class="rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 px-4 py-2 text-xs font-bold text-white transition shadow-sm flex items-center gap-1.5">
                                            <span>✓</span> Reply & Resolve
                                        </button>
                                    </form>
                                @else
                                    <p class="text-[11px] text-amber-800 italic">Waiting for reporter's response…</p>
                                @endif
                            </div>
                        @endforeach

                        @if(auth()->user()->role === 'developer' && $bug->assigned_to === auth()->user()->name)
                            <form action="{{ route('bugs.clarifications.request', [], false) }}" method="POST" class="rounded-2xl border border-amber-200 bg-amber-50/40 p-4 space-y-2">
                                @csrf
                                <input type="hidden" name="bug_id" value="{{ $bug->id }}">
                                <label class="block text-xs font-bold text-amber-950">Ask reporter for clarification / missing steps:</label>
                                <textarea name="question" required rows="2" placeholder="e.g. Can you provide the browser version or specific test account used?" class="w-full rounded-xl border border-amber-200 bg-white p-2.5 text-xs outline-none focus:border-amber-500"></textarea>
                                <button type="submit" class="rounded-xl bg-amber-600 px-3.5 py-1.5 text-xs font-bold text-white hover:bg-amber-500 transition">Send Clarification Request</button>
                            </form>
                        @endif
                    </div>

                    <!-- Comments List -->
                    <div class="pt-4 border-t border-[#E2E8F0] space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Comments ({{ $comments->count() }})</h3>
                        <div class="space-y-3">
                            @forelse($comments as $comment)
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3.5 text-xs space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-[#0F172A]">{{ $comment->author_name }}</span>
                                        <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-slate-700 whitespace-pre-wrap">{{ $comment->body }}</p>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic py-2">No comments yet. Post the first comment below.</p>
                            @endforelse
                        </div>

                        <!-- Post Comment Form -->
                        <form action="{{ route('bugs.comments', [], false) }}" method="POST" class="pt-2 space-y-2">
                            @csrf
                            <input type="hidden" name="bug_id" value="{{ $bug->id }}">
                            <textarea name="body" required rows="2" placeholder="Write a comment or mention a teammate…" class="w-full rounded-xl border border-[#CBD5E1] bg-white p-3 text-xs outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10"></textarea>
                            <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 px-4 py-2 text-xs font-bold text-white transition shadow-xs">
                                Post Comment
                            </button>
                        </form>
                    </div>
                </section>
            </div>

            <!-- Right 35% (col-span-4): Status Advance, Assignee, AI Rationale & Timeline (Sticky) -->
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                
                <!-- 1. Quick Status Advance Widget -->
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-3">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Workflow State</h2>
                    <div class="rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] p-4 text-center">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#64748B] block">Current Stage</span>
                        <span class="mt-1 text-lg font-extrabold text-[#0F172A] block">{{ $bug->status }}</span>
                    </div>

                    @if(in_array(auth()->user()->role, ['developer', 'manager'], true) && count($transitions) > 0)
                        <form action="/api/bugs/override" method="POST" class="space-y-2 pt-2">
                            @csrf
                            <input type="hidden" name="id" value="{{ $bug->id }}">
                            <label class="block text-xs font-bold text-[#0F172A]">Advance to Next Stage:</label>
                            <div class="flex gap-2">
                                <select name="status" required class="w-full rounded-xl border border-[#CBD5E1] bg-white px-3 py-2 text-xs font-semibold text-[#0F172A] outline-none focus:border-indigo-600">
                                    <option value="" disabled selected>Select new status…</option>
                                    @foreach($transitions as $nextStatus)
                                        <option value="{{ $nextStatus }}">{{ $nextStatus }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 px-4 py-2 text-xs font-bold text-white transition shrink-0">
                                    Update
                                </button>
                            </div>
                        </form>
                    @endif
                </section>

                <!-- 2. Assignee & Management -->
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-3">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Assigned Developer</h2>
                    <div class="flex items-center gap-3 p-3 bg-[#F8FAFC] rounded-2xl border border-[#E2E8F0]">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-bold font-mono-code text-sm">
                            {{ strtoupper(substr($bug->assigned_to ?: 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-xs text-[#0F172A] truncate">{{ $bug->assigned_to ?: 'Unassigned' }}</p>
                            <p class="text-[11px] text-[#64748B]">Responsible Engineer</p>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'manager')
                        <form action="{{ route('bugs.reassign', [], false) }}" method="POST" class="pt-2 space-y-2">
                            @csrf
                            <input type="hidden" name="id" value="{{ $bug->id }}">
                            <label class="block text-xs font-bold text-slate-800">Reassign Issue:</label>
                            <div class="flex gap-2">
                                <select name="assigned_to" class="w-full rounded-xl border border-[#CBD5E1] bg-white px-3 py-2 text-xs text-slate-800">
                                    <option value="Unassigned">Unassigned</option>
                                    @foreach($developers as $dev)
                                        <option value="{{ $dev->name }}" @selected($bug->assigned_to === $dev->name)>{{ $dev->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="rounded-xl bg-slate-800 hover:bg-slate-700 px-3.5 py-2 text-xs font-bold text-white transition shrink-0">Save</button>
                            </div>
                        </form>
                    @endif

                    <!-- AI Assignment Rationale -->
                    <div class="pt-2 text-xs text-slate-600">
                        <p class="font-bold text-slate-800 mb-1">AI Recommendation Rationale:</p>
                        <p class="text-[11px] leading-relaxed bg-indigo-50/50 p-2.5 rounded-xl border border-indigo-100 text-indigo-950">
                            {{ $codeHelp['assignment_reason'] ?? 'Selected based on skill keywords and available workload capacity.' }}
                        </p>
                    </div>
                </section>

                <!-- 3. Activity History Timeline -->
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs space-y-3">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Activity Timeline</h2>
                    <div class="space-y-2.5 max-h-96 overflow-y-auto pr-1 custom-scroll">
                        @forelse($activities as $act)
                            <div class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] p-3 text-xs space-y-1">
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
                            <p class="text-xs text-slate-400 italic py-2">No activity recorded yet.</p>
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
    </script>
</body>
</html>

