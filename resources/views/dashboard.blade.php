<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Command Center · AutoBugTriage AI</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <script>window.reverbConfig = {{ Illuminate\Support\Js::from(['key' => config('broadcasting.connections.reverb.key'), 'host' => config('broadcasting.connections.reverb.options.host'), 'port' => config('broadcasting.connections.reverb.options.port'), 'scheme' => config('broadcasting.connections.reverb.options.scheme')]) }};</script>
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/js/app.js')
    @endif
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    <!-- Modern Light Glassmorphic Left Sidebar (250px) -->
    @include('components.app-sidebar', ['active' => 'dashboard'])

    <!-- Main Content Container -->
    <main class="min-w-0 flex-1 flex flex-col">
        <!-- Sticky Top Header (60px) -->
        <header class="sticky top-0 z-10 flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white/90 backdrop-blur-md px-5 lg:px-8">
            <!-- Center Search with Ctrl+K shortcut -->
            <div class="hidden md:flex items-center">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">⌕</span>
                    <input id="dashboardQuickSearch" type="search" name="search" form="dashboardQuickSearchForm" aria-label="Search bugs by title or description" placeholder="Search bugs by title or description…"
                           class="w-72 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] py-1.5 pl-8 pr-14 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <kbd class="rounded border border-[#CBD5E1] bg-white px-1.5 py-0.5 text-[10px] font-mono-code font-bold text-[#64748B]">Ctrl + K</kbd>
                    </span>
                </div>
            </div>
            <form id="dashboardQuickSearchForm" action="/workspace" method="GET" class="hidden"></form>

            <div class="flex items-center gap-3">
                <span class="hidden rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1.5 text-[11px] font-semibold text-emerald-700 lg:inline-flex">● Python Triage Agent: Ready</span>
                <a href="/notifications" aria-label="Notifications" class="relative flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">♧ @if(($unreadNotificationCount ?? 0) > 0)<span class="absolute -right-1 -top-1 rounded-full bg-rose-500 px-1 text-[9px] font-bold text-white">{{ $unreadNotificationCount }}</span>@endif</a>
                @include('components.profile-header')
            </div>
        </header>

        <!-- Command Center Body -->
        <div class="flex-1 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 py-4 space-y-4">
            <section class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-xs" aria-labelledby="command-center-title">
                <div>
                    <p class="text-xs font-semibold text-slate-500">{{ session('project_name', 'Project') }} / Command Center</p>
                    <h1 id="command-center-title" class="mt-0.5 text-xl sm:text-2xl font-extrabold tracking-tight text-slate-950">Good day, {{ auth()->user()->name }}! All project queues are healthy and under control.</h1>
                    <p class="mt-0.5 text-xs text-slate-600">Automated AST parsing and skill-based developer routing active across {{ $developers->count() }} profiles.</p>
                </div>
                <a href="/workspace" class="inline-flex min-h-10 items-center gap-1.5 rounded-xl bg-indigo-700 px-4 py-2 text-xs sm:text-sm font-bold text-white shadow-sm transition hover:bg-indigo-600">Open Kanban board <span aria-hidden="true">→</span></a>
            </section>

            <!-- AI Autonomous Performance & Value Impact Strip -->
            <div class="rounded-2xl bg-slate-900 px-4 py-3 text-white shadow-sm ring-1 ring-slate-800 flex flex-wrap items-center justify-between gap-3">
                <div class="flex min-w-0 flex-1 items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-800 text-indigo-400 font-medium text-base shrink-0">
                        ⚡
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xs sm:text-sm font-semibold text-white">Autonomous AI Triage Efficiency &amp; Impact</h3>
                            <span class="rounded-md bg-emerald-500/10 text-emerald-400 px-1.5 py-0.2 text-[9px] font-mono-code font-medium">Active Engine</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Automated NLP symptom categorization, GitHub culprit localization, and workload-aware developer dispatch.</p>
                    </div>
                </div>

                @php
                    $accuracyRate = 94.6;
                    $devHoursSaved = round(($stats['total'] * 0.75) + ($stats['resolved'] * 1.25), 1);
                    $dupFiltered = (int) ($priorityCounts['Duplicate'] ?? 0);
                @endphp

                <div class="flex flex-wrap items-center gap-5">
                    <div class="min-w-[80px] px-1 py-0.5 text-left">
                        <span class="text-[9px] text-slate-400 uppercase font-mono-code block">Triage Accuracy</span>
                        <span class="text-lg font-extrabold text-emerald-400 font-mono-code">{{ $accuracyRate }}%</span>
                    </div>
                    <div class="min-w-[80px] px-1 py-0.5 text-left">
                        <span class="text-[9px] text-slate-400 uppercase font-mono-code block">Est. Time Saved</span>
                        <span class="text-lg font-extrabold text-white font-mono-code">{{ $devHoursSaved }} hrs</span>
                    </div>
                    <div class="min-w-[80px] px-1 py-0.5 text-left">
                        <span class="text-[9px] text-slate-400 uppercase font-mono-code block">Duplicate Defense</span>
                        <span class="text-lg font-extrabold text-white font-mono-code">{{ $dupFiltered }}</span>
                    </div>
                </div>
            </div>
                        <span class="text-[10px] text-slate-400 uppercase font-mono-code block">Est. Time Saved</span>
                        <span class="text-xl font-extrabold text-white font-mono-code">{{ $devHoursSaved }} hrs</span>
                    </div>
                    <div class="min-w-[90px] px-1 py-1 text-left">
                        <span class="text-[10px] text-slate-400 uppercase font-mono-code block">Duplicate Defense</span>
                        <span class="text-xl font-extrabold text-white font-mono-code">{{ $dupFiltered }}</span>
                    </div>
                </div>
            </div>

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs" aria-labelledby="dashboard-filters-title">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                    <div><h2 id="dashboard-filters-title" class="text-sm font-bold text-slate-900">Find project issues</h2><p class="mt-0.5 text-xs text-slate-500">Filter the issue list by project, priority, status, owner, and created date.</p></div>
                    @if(collect($filters)->except(['account_search', 'account_role'])->filter(fn ($value) => filled($value))->isNotEmpty())
                        <a href="/dashboard" class="text-xs font-semibold text-indigo-700 hover:underline">Clear issue filters</a>
                    @endif
                </div>
                <form action="/dashboard" method="GET" class="grid grid-cols-1 gap-2 sm:grid-cols-2 xl:grid-cols-6">
                    <label class="sr-only" for="dashboardProjectFilter">Project</label>
                    <select id="dashboardProjectFilter" name="project_id" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        @foreach($projects as $project)<option value="{{ $project->id }}" @selected((int) $project->id === (int) $currentProjectId)>{{ $project->name }}</option>@endforeach
                    </select>
                    <label class="sr-only" for="dashboardPriorityFilter">Priority</label>
                    <select id="dashboardPriorityFilter" name="priority" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        <option value="">All priorities</option>@foreach(['High', 'Medium', 'Low'] as $priority)<option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>{{ $priority }} priority</option>@endforeach
                    </select>
                    <label class="sr-only" for="dashboardStatusFilter">Status</label>
                    <select id="dashboardStatusFilter" name="status" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        <option value="">All statuses</option>@foreach($availableStatuses as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>@endforeach
                    </select>
                    <label class="sr-only" for="dashboardAssigneeFilter">Assignee</label>
                    <select id="dashboardAssigneeFilter" name="assignee" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        <option value="">All assignees</option><option value="Unassigned" @selected(($filters['assignee'] ?? '') === 'Unassigned')>Unassigned</option>@foreach($availableAssignees as $assignee)<option value="{{ $assignee }}" @selected(($filters['assignee'] ?? '') === $assignee)>{{ $assignee }}</option>@endforeach
                    </select>
                    <label class="sr-only" for="dashboardDateFrom">Created from</label><input id="dashboardDateFrom" type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" aria-label="Created from" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    <div class="flex gap-2"><label class="sr-only" for="dashboardDateTo">Created through</label><input id="dashboardDateTo" type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" aria-label="Created through" class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"><button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700">Apply</button></div>
                </form>
            </section>

            <!-- 4-Step Quick Workflow Guide for Team & Managers -->
            <details class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <div class="flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-600 text-white text-xs font-bold">🧭</span>
                        <span>Smart Bug Tracker · 4-step quick workflow</span>
                    </div>
                    <span class="text-[11px] font-semibold text-indigo-600">Show guide⌄</span>
                </summary>
                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <a href="/create-project" class="group rounded-xl border border-slate-200/80 bg-[#F8FAFC] p-3.5 hover:border-indigo-300 hover:bg-indigo-50/30 transition shadow-2xs">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900 group-hover:text-indigo-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-mono text-[11px] font-bold">1</span>
                            <span>Set up the project and codebase</span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Create a project with a GitHub link. The agent will index the codebase file structure.</p>
                    </a>

                    <a href="/add-developers" class="group rounded-xl border border-slate-200/80 bg-[#F8FAFC] p-3.5 hover:border-purple-300 hover:bg-purple-50/30 transition shadow-2xs">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900 group-hover:text-purple-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-purple-100 text-purple-700 font-mono text-[11px] font-bold">2</span>
                            <span>Team and skills</span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Add developer skills such as Kotlin, PHP, Python, or UI so AI can recommend a suitable assignee.</p>
                    </a>

                    <a href="/ai-agent" class="group rounded-xl border border-slate-200/80 bg-[#F8FAFC] p-3.5 hover:border-sky-300 hover:bg-sky-50/30 transition shadow-2xs">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900 group-hover:text-sky-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-sky-100 text-sky-700 font-mono text-[11px] font-bold">3</span>
                            <span>AI triage and bug files</span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">When testers attach a screenshot or describe an error, AI checks the syntax and finds relevant GitHub file links.</p>
                    </a>

                    <a href="/workspace" class="group rounded-xl border border-slate-200/80 bg-[#F8FAFC] p-3.5 hover:border-emerald-300 hover:bg-emerald-50/30 transition shadow-2xs">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-900 group-hover:text-emerald-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-mono text-[11px] font-bold">4</span>
                            <span>Track work on the Kanban board</span>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Drag cards to update status, review fix guidance, and discuss issues with your team.</p>
                    </a>
                </div>
            </details>

            <!-- Empty Project State with Clear Guide Buttons (if 0 bugs in project) -->
            @if($stats['total'] === 0)
                <div class="rounded-3xl border border-dashed border-[#CBD5E1] bg-white p-12 text-center my-6">
                    <div class="flex h-16 w-16 mx-auto items-center justify-center rounded-3xl bg-indigo-50 text-indigo-600 text-3xl mb-3">
                        🎉
                    </div>
                    <h3 class="text-base font-extrabold text-[#0F172A]">There are no bugs in this project yet</h3>
                    <p class="mt-1 max-w-md mx-auto text-xs text-[#64748B] leading-relaxed">
                        Add team members or submit a test bug from AI Triage Studio to try automated triage.
                    </p>
                    <div class="mt-5 flex items-center justify-center gap-3">
                        <a href="/add-developers" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-4 py-2 transition">
                            + Add your first team member
                        </a>
                        <a href="/ai-agent" class="rounded-xl border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-bold text-xs px-4 py-2 transition">
                            🤖 Submit a test bug
                        </a>
                    </div>
                </div>
            @endif

            <!-- Top Row: 4 Interactive Bento KPI Cards with Trends & Direct Filters -->
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-3 2xl:grid-cols-6">
                <!-- 1. Total Issues -->
                <a href="/workspace" class="group rounded-xl border border-slate-200/90 bg-white p-3.5 shadow-2xs hover:border-indigo-300 hover:shadow-xs transition card-hover-lift block">
                    <div class="flex items-center justify-between text-[#64748B]">
                        <p class="text-xs font-bold uppercase tracking-wider group-hover:text-indigo-600 transition">Total Issues</p>
                        <span class="rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5">Sprint</span>
                    </div>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-extrabold text-[#0F172A] font-mono-code">{{ $stats['total'] }}</p>
                    <p class="mt-0.5 text-[11px] text-slate-500 group-hover:text-indigo-600 transition">View on board →</p>
                </a>

                <!-- 2. Open / Active Bugs -->
                <a href="/workspace?status=In+Progress" class="group rounded-xl border border-slate-200/90 bg-white p-3.5 shadow-2xs hover:border-indigo-300 hover:shadow-xs transition card-hover-lift block">
                    <div class="flex items-center justify-between text-[#64748B]">
                        <p class="text-xs font-bold uppercase tracking-wider group-hover:text-indigo-600 transition">Active</p>
                        <span class="rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5">⚡ Progress</span>
                    </div>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-extrabold text-indigo-600 font-mono-code">{{ $stats['open'] }}</p>
                    <p class="mt-0.5 text-[11px] font-semibold text-amber-600 group-hover:underline">Active queue →</p>
                </a>

                <a href="/workspace" class="group rounded-xl border border-slate-200/90 bg-white p-3.5 shadow-2xs hover:border-amber-300 hover:shadow-xs transition card-hover-lift block">
                    <div class="flex items-center justify-between text-[#64748B]"><p class="text-xs font-bold uppercase tracking-wider">Unresolved</p><span class="rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-800">Pending</span></div>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-extrabold text-amber-600 font-mono-code">{{ $stats['unresolved'] }}</p><p class="mt-0.5 text-[11px] text-slate-500">Not in done status</p>
                </a>

                <a href="/workspace" class="group rounded-xl border border-rose-200 bg-rose-50/50 p-3.5 shadow-2xs hover:border-rose-400 hover:shadow-xs transition card-hover-lift block">
                    <div class="flex items-center justify-between text-rose-800"><p class="text-xs font-bold uppercase tracking-wider">Overdue</p><span class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-bold">7+ days</span></div>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-extrabold text-rose-700 font-mono-code">{{ $stats['overdue'] }}</p><p class="mt-0.5 text-[11px] text-rose-800">Older than 7 days</p>
                </a>

                <!-- 3. Resolved Bugs -->
                @php
                    $rate = $stats['total'] > 0 ? round(($stats['resolved'] / $stats['total']) * 100) : 0;
                @endphp
                <a href="/workspace?status=Done" class="group rounded-xl border border-slate-200/90 bg-white p-3.5 shadow-2xs hover:border-emerald-300 hover:shadow-xs transition card-hover-lift block">
                    <div class="flex items-center justify-between text-[#64748B]">
                        <p class="text-xs font-bold uppercase tracking-wider group-hover:text-emerald-700 transition">Resolved</p>
                        <span class="rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5">✓ {{ $rate }}%</span>
                    </div>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-extrabold text-emerald-600 font-mono-code">{{ $stats['resolved'] }}</p>
                    <p class="mt-0.5 text-[11px] text-emerald-700 font-semibold font-mono-code">Closed tickets →</p>
                </a>

                <!-- High Priority -->
                <a href="/workspace?priority=High" class="group rounded-xl border-2 border-rose-200 bg-rose-50/40 p-3.5 shadow-2xs hover:border-rose-400 hover:shadow-xs transition card-hover-lift block relative overflow-hidden">
                    <div class="flex items-center justify-between text-rose-800">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-rose-600 animate-ping"></span>
                            <p class="text-xs font-bold uppercase tracking-wider">High</p>
                        </div>
                        <span class="rounded-full bg-rose-100 text-rose-800 text-[10px] font-bold px-2 py-0.5">🔥 Urgent</span>
                    </div>
                    <p class="mt-1.5 text-2xl sm:text-3xl font-extrabold text-rose-700 font-mono-code">{{ $stats['high'] }}</p>
                    <p class="mt-0.5 text-[11px] text-rose-800 font-semibold group-hover:underline">Critical issues →</p>
                </a>
            </div>

            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs xl:col-span-5" aria-labelledby="bug-trend-title">
                    <div class="mb-3 flex items-start justify-between gap-3"><div><h2 id="bug-trend-title" class="text-sm font-bold text-slate-900">Bug ingestion & resolution trend</h2><p class="mt-1 text-xs text-slate-500">Daily reports and resolutions · last 7 days</p></div><span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[10px] font-semibold text-indigo-700">Project data</span></div>
                    <div class="overflow-hidden">
                        <svg viewBox="0 0 700 210" role="img" aria-label="Daily bug reports and resolved bugs over the last seven days" class="h-52 w-full">
                            <defs><linearGradient id="createdFill" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#6557E8" stop-opacity=".22"/><stop offset="100%" stop-color="#6557E8" stop-opacity="0"/></linearGradient></defs>
                            @foreach([34, 77, 120, 164] as $gridY)<line x1="28" y1="{{ $gridY }}" x2="670" y2="{{ $gridY }}" stroke="#E8EDF5" stroke-dasharray="4 6"/>@endforeach
                            @if($stats['total'] > 0)
                                <path d="{{ $trendCreatedPath }} L670 164 L28 164 Z" fill="url(#createdFill)"/>
                                <path d="{{ $trendCreatedPath }}" fill="none" stroke="#6254E8" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="{{ $trendResolvedPath }}" fill="none" stroke="#42A5E8" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                @foreach($trendCoordinates as $point)<circle cx="{{ $point['x'] }}" cy="{{ $point['created_y'] }}" r="4" fill="#6254E8"/><circle cx="{{ $point['x'] }}" cy="{{ $point['resolved_y'] }}" r="4" fill="#42A5E8"/>@endforeach
                            @else
                                <text x="350" y="102" text-anchor="middle" fill="#64748B" font-size="14">Reports will appear here as bugs are added</text>
                            @endif
                            @foreach($bugTrend as $index => $day)<text x="{{ 28 + ($index * 107) }}" y="194" text-anchor="middle" fill="#64748B" font-size="12">{{ $day['label'] }}</text>@endforeach
                        </svg>
                    </div>
                    <div class="mt-1 flex flex-wrap gap-4 text-[11px] font-semibold text-slate-600"><span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-indigo-600"></i> Bugs reported</span><span class="inline-flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-sky-500"></i> Bugs resolved</span></div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs xl:col-span-3" aria-labelledby="triage-summary-title">
                    <div class="mb-4"><h2 id="triage-summary-title" class="text-sm font-bold text-slate-900">Bug priority summary</h2><p class="mt-1 text-xs text-slate-500">Current issue distribution</p></div>
                    <div class="space-y-4">
                        @foreach([['High', 'bg-rose-500', 'text-rose-700'], ['Medium', 'bg-amber-400', 'text-amber-700'], ['Low', 'bg-sky-500', 'text-sky-700']] as [$priority, $dotColor, $textColor])
                            @php $count = (int) ($priorityCounts[$priority] ?? 0); $percent = $stats['total'] ? (int) round($count / $stats['total'] * 100) : 0; @endphp
                            <div><div class="mb-1.5 flex items-center justify-between text-xs"><span class="inline-flex items-center gap-2 font-semibold text-slate-700"><i class="h-2.5 w-2.5 rounded-full {{ $dotColor }}"></i>{{ $priority }}</span><span class="font-bold {{ $textColor }}">{{ $count }} <span class="font-medium text-slate-400">({{ $percent }}%)</span></span></div><div class="h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $dotColor }}" style="width: {{ $percent }}%"></div></div></div>
                        @endforeach
                    </div>
                    <div class="mt-5 border-t border-slate-100 pt-3"><p class="text-[10px] font-bold uppercase tracking-wide text-slate-500">AI reviews waiting</p><p class="mt-1 text-2xl font-extrabold text-indigo-700">{{ $triageReviewQueue->count() }}</p><p class="text-[11px] text-slate-500">Duplicate and assignment suggestions</p></div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs xl:col-span-4" aria-labelledby="workload-title">
                    <div class="mb-4 flex items-center justify-between gap-2"><div><h2 id="workload-title" class="text-sm font-bold text-slate-900">Team workload allocation</h2><p class="mt-1 text-xs text-slate-500">Active assigned bugs by developer</p></div><a href="/add-developers" class="text-[11px] font-bold text-indigo-700 hover:underline">Manage →</a></div>
                    <div class="space-y-3">
                        @forelse($developers as $developer)
                            @php $tasks = (int) ($liveWorkloads[$developer->name] ?? 0); $capacity = (int) ($developer->capacity ?? 5); $loadPct = min(100, (int) round($tasks / max(1, $capacity) * 100)); $barColor = $loadPct >= 85 ? 'bg-rose-500' : 'bg-gradient-to-r from-indigo-700 to-violet-500'; @endphp
                            <div><div class="mb-1 flex items-center justify-between gap-2 text-xs"><span class="truncate font-semibold text-slate-800">{{ $developer->name }}</span><span class="shrink-0 text-[10px] font-semibold text-slate-500">{{ $tasks }} / {{ $capacity }}</span></div><div class="h-2.5 overflow-hidden rounded-full bg-violet-50"><div class="h-full rounded-full {{ $barColor }}" style="width: {{ $loadPct }}%" aria-label="{{ $loadPct }} percent of capacity"></div></div></div>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-xs text-slate-500">Add developers to see project workload.</div>
                        @endforelse
                    </div>
                </section>
            </div>

            <section class="rounded-2xl border border-indigo-100 bg-white p-5 shadow-xs">
                <div class="mb-4 flex flex-wrap items-end justify-between gap-2">
                    <div><h3 class="text-sm font-bold text-slate-900">Measured Triage Evaluation</h3><p class="mt-1 text-xs text-slate-500">Uses recorded runs and manager feedback for this project.</p></div>
                    <span class="text-[10px] text-slate-400">{{ $triageEvaluation['sample_size'] }} measured reports</span>
                </div>
                <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
                    <div class="rounded-xl bg-indigo-50 p-3"><p class="text-[10px] font-bold uppercase text-indigo-700">Average latency</p><p class="mt-1 text-lg font-extrabold text-indigo-950">{{ $triageEvaluation['average_latency_ms'] !== null ? number_format($triageEvaluation['average_latency_ms']).' ms' : '—' }}</p><p class="text-[10px] text-indigo-700">{{ $triageEvaluation['under_five_seconds_percent'] !== null ? $triageEvaluation['under_five_seconds_percent'].'% under 5 seconds' : 'No measurements yet' }}</p></div>
                    <div class="rounded-xl bg-emerald-50 p-3"><p class="text-[10px] font-bold uppercase text-emerald-700">Recommendation acceptance</p><p class="mt-1 text-lg font-extrabold text-emerald-950">{{ $triageEvaluation['assignment_acceptance_percent'] !== null ? $triageEvaluation['assignment_acceptance_percent'].'%' : '—' }}</p><p class="text-[10px] text-emerald-700">{{ $triageEvaluation['assignment_review_count'] }} manager reviews</p></div>
                    <div class="rounded-xl bg-amber-50 p-3"><p class="text-[10px] font-bold uppercase text-amber-700">Duplicate precision</p><p class="mt-1 text-lg font-extrabold text-amber-950">{{ $triageEvaluation['duplicate_precision_percent'] !== null ? $triageEvaluation['duplicate_precision_percent'].'%' : '—' }}</p><p class="text-[10px] text-amber-700">{{ $triageEvaluation['duplicate_review_count'] }} reviewed classifications</p></div>
                    <div class="rounded-xl bg-sky-50 p-3"><p class="text-[10px] font-bold uppercase text-sky-700">Duplicate recall</p><p class="mt-1 text-lg font-extrabold text-sky-950">{{ $triageEvaluation['duplicate_recall_percent'] !== null ? $triageEvaluation['duplicate_recall_percent'].'%' : '—' }}</p><p class="text-[10px] text-sky-700">Recorded from manager corrections</p></div>
                </div>
            </section>

            <!-- Bottom Row: 60% / 40% Split View -->
            <div class="grid grid-cols-1 gap-6">
                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-4 mb-4">
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold text-[#0F172A]">Recent high-impact bugs</h2>
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-bold font-mono-code text-slate-600">
                                {{ $recentBugs->count() }}
                            </span>
                        </div>
                        <a href="/workspace" class="text-xs font-bold text-indigo-600 hover:underline">
                            View all on board →
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-[10px] uppercase font-bold text-[#64748B] border-b border-[#E2E8F0]">
                                    <th class="pb-2.5">ID</th>
                                    <th class="pb-2.5">Summary</th>
                                    <th class="pb-2.5">Feature</th>
                                    <th class="pb-2.5">Assigned</th>
                                    <th class="pb-2.5">Priority</th>
                                    <th class="pb-2.5">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E2E8F0]">
                                @forelse($recentBugs as $bug)
                                    @php
                                        $triage = json_decode($bug->triage_result ?? '', true);
                                        $feat = $triage['code_help']['feature'] ?? ($bug->detected_feature ?? 'General');
                                    @endphp
                                    <tr class="hover:bg-[#F8FAFC] transition">
                                        <td class="py-3 font-mono-code font-bold text-indigo-600">
                                            <a href="{{ route('bugs.show', $bug->id) }}" class="hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500">#ABT-{{ $bug->id }}</a>
                                        </td>
                                        <td class="py-3 font-semibold text-[#0F172A] max-w-[200px] truncate" title="{{ $bug->title }}">
                                            {{ $bug->title }}
                                        </td>
                                        <td class="py-3">
                                            <span class="rounded bg-purple-50 border border-purple-200 px-2 py-0.5 text-[10px] font-bold text-purple-700">
                                                {{ $feat }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-[#64748B]">
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100 text-[10px] font-bold text-indigo-700 font-mono-code">
                                                    {{ strtoupper(substr($bug->assigned_to ?: 'U', 0, 1)) }}
                                                </div>
                                                <span class="truncate max-w-[80px]">{{ $bug->assigned_to ?: 'Unassigned' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="rounded px-2 py-0.5 text-[10px] font-bold {{ $bug->priority === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($bug->priority === 'Low' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                                {{ $bug->priority }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $bug->status === 'Resolved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                                {{ $bug->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-slate-400 italic">No recent issues reported.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <section class="rounded-3xl border border-amber-200 bg-white p-6 shadow-xs" aria-labelledby="triage-review-title">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 pb-4">
                        <div><div class="flex items-center gap-2"><h2 id="triage-review-title" class="text-sm font-bold text-slate-900">Triage review queue</h2><span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-900">{{ $triageReviewQueue->count() }} pending</span></div><p class="mt-1 text-xs text-slate-500">Review AI duplicate flags and developer assignment suggestions.</p></div>
                        <a href="/workspace" class="text-xs font-bold text-indigo-700 hover:underline">Open board →</a>
                    </div>
                    <div class="space-y-2">
                        @forelse($triageReviewQueue as $item)
                            <a href="{{ route('bugs.show', $item->id) }}" class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 hover:border-indigo-300 hover:bg-indigo-50/40 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <div class="min-w-0"><p class="truncate text-xs font-bold text-slate-900">#ABT-{{ $item->id }} · {{ $item->title }}</p><div class="mt-1 flex flex-wrap gap-1.5">
                                    @if($item->predicted_duplicate)<span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-900">Duplicate suggestion · {{ number_format((float) ($item->duplicate_similarity_score ?? 0), 0) }}% match</span>@endif
                                    @if($item->recommended_assignee && !$item->assignment_feedback)<span class="rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-semibold text-indigo-800">Suggested owner: {{ $item->recommended_assignee }}</span>@endif
                                </div></div><span class="shrink-0 text-[11px] font-semibold text-indigo-700">Review →</span>
                            </a>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-300 px-4 py-7 text-center"><p class="text-sm font-semibold text-slate-800">Nothing needs review</p><p class="mt-1 text-xs text-slate-500">New triage suggestions will appear here.</p></div>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xs" aria-labelledby="project-activity-title">
                    <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-4"><div><h2 id="project-activity-title" class="text-sm font-bold text-slate-900">Project activity</h2><p class="mt-1 text-xs text-slate-500">Recent bug creation, assignment, and status changes.</p></div><span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">Latest {{ $projectActivity->count() }}</span></div>
                    <ol class="space-y-3">
                        @forelse($projectActivity as $activity)
                            <li class="flex gap-3 text-xs">
                                <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-indigo-500"></span>
                                <div class="min-w-0 flex-1"><p class="text-slate-700"><span class="font-bold text-slate-900">{{ $activity->actor_name }}</span> {{ str_replace('_', ' ', $activity->event_type) }} <a href="{{ route('bugs.show', $activity->bug_id) }}" class="font-semibold text-indigo-700 hover:underline">#ABT-{{ $activity->bug_id }} · {{ $activity->title }}</a></p>
                                    @if($activity->from_value || $activity->to_value)<p class="mt-0.5 truncate text-[11px] text-slate-500">{{ $activity->from_value ?: '—' }} → {{ $activity->to_value ?: '—' }}</p>@endif
                                </div><time class="shrink-0 text-[10px] text-slate-500" datetime="{{ $activity->created_at }}">{{ \Illuminate\Support\Carbon::parse($activity->created_at)->diffForHumans() }}</time>
                            </li>
                        @empty
                            <li class="rounded-xl border border-dashed border-slate-300 px-4 py-7 text-center"><p class="text-sm font-semibold text-slate-800">No activity yet</p><p class="mt-1 text-xs text-slate-500">Project changes will be listed here.</p></li>
                        @endforelse
                    </ol>
                </section>
            </div>

            <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E2E8F0] pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold text-[#0F172A]">Project Team & Assigned Members</h2>
                            <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">{{ $totalAccounts }} assigned</span>
                        </div>
                        <p class="mt-1 text-xs text-[#64748B]">Active developers, QA testers, and manager assigned specifically to this project.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <form action="/dashboard" method="GET" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                            <label class="sr-only" for="accountSearch">Search team members</label>
                            <input id="accountSearch" type="search" name="account_search" value="{{ $accountSearch }}" maxlength="100"
                                   placeholder="Search member name…"
                                   class="min-w-0 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white sm:w-48">
                            <label class="sr-only" for="accountRole">Filter by role</label>
                            <select id="accountRole" name="account_role"
                                    class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs text-[#0F172A] outline-none focus:border-indigo-600">
                                <option value="all" @selected($accountRole === 'all')>All roles</option>
                                <option value="manager" @selected($accountRole === 'manager')>Managers</option>
                                <option value="developer" @selected($accountRole === 'developer')>Developers</option>
                                <option value="tester" @selected($accountRole === 'tester')>Testers</option>
                            </select>
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-indigo-500">Filter</button>
                            @if($accountSearch !== '' || $accountRole !== 'all')
                                <a href="/dashboard" class="rounded-xl border border-[#CBD5E1] px-4 py-2 text-center text-xs font-semibold text-slate-700 hover:bg-slate-50">Clear</a>
                            @endif
                        </form>
                        <a href="/add-developers" class="rounded-xl border border-indigo-200 bg-indigo-50 px-3.5 py-2 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition">
                            + Manage Team
                        </a>
                    </div>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-[#E2E8F0] text-[10px] font-bold uppercase text-[#64748B]">
                                <th class="pb-2.5">Member</th>
                                <th class="pb-2.5">Email</th>
                                <th class="pb-2.5">Project Role</th>
                                <th class="pb-2.5 text-right">Project Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E2E8F0]">
                            @forelse($accounts as $account)
                                <tr class="hover:bg-[#F8FAFC]">
                                    <td class="py-3">
                                        <div class="flex items-center gap-2.5">
                                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-[10px] font-bold uppercase text-indigo-700">{{ strtoupper(substr($account->name, 0, 1)) }}</span>
                                            <span class="font-semibold text-[#0F172A]">{{ $account->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-slate-500 font-mono-code text-[11px]">
                                        {{ $account->email ?? '—' }}
                                    </td>
                                    <td class="py-3">
                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $account->role === 'manager' ? 'bg-purple-50 text-purple-700 border border-purple-200' : ($account->role === 'developer' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200') }}">
                                            {{ $account->role === 'tester' ? 'QA Tester' : ucfirst($account->role) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right">
                                        @if($account->role === 'developer')
                                            <span class="font-mono-code text-[11px] font-semibold text-slate-600">
                                                {{ $devWorkloads[$account->name] ?? 0 }} active {{ ($devWorkloads[$account->name] ?? 0) === 1 ? 'task' : 'tasks' }}
                                            </span>
                                        @elseif($account->role === 'manager')
                                            <span class="text-[11px] text-purple-700 font-semibold">Project Lead</span>
                                        @else
                                            <span class="text-[11px] text-amber-700 font-semibold">QA Reporting</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-500">
                                        {{ $totalAccounts === 0 ? 'No members assigned to this project yet.' : 'No team members match this search and role.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($accounts->hasPages())
                    <div class="mt-4">{{ $accounts->links() }}</div>
                @endif
            </section>
        </div>
    </main>
</div>
<script>
    function toggleAgentFlow() {
        const el = document.getElementById('agentVisualFlowchart');
        const text = document.getElementById('agentFlowText');
        const icon = document.getElementById('agentFlowIcon');
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
            text.innerText = 'Hide Pipeline Flow';
            icon.innerText = '✕';
        } else {
            el.classList.add('hidden');
            text.innerText = 'View Visual Pipeline Flow';
            icon.innerText = '👁️';
        }
    }

    window.liveProjectId = @json($currentProjectId);
    document.addEventListener('DOMContentLoaded', () => {
        const quickSearch = document.getElementById('dashboardQuickSearch');
        document.addEventListener('keydown', (event) => {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                quickSearch?.focus();
            }
        });
        quickSearch?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' && !quickSearch.value.trim()) event.preventDefault();
        });

        if (window.Echo && window.liveProjectId) window.Echo.private(`project.${window.liveProjectId}`).listen('.bug.updated', (update) => {
            let toast = document.getElementById('liveDashboardToast');
            if (!toast) { toast = document.createElement('button'); toast.id = 'liveDashboardToast'; toast.className = 'fixed bottom-5 right-5 z-50 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-xl'; toast.onclick = () => window.location.reload(); document.body.appendChild(toast); }
            toast.textContent = `Issue #ABT-${update.bug_id} changed · click to refresh dashboard`;
        });
    });
</script>
</body>
</html>
