<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ session('project_name', 'Workspace') }} · Kanban Board</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased">
    <div class="flex min-h-screen">
        <!-- Fixed Left Sidebar (250px) -->
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
                @include('components.project-switcher', ['variant' => 'dark'])
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 space-y-1 p-3 text-xs">
                @if(auth()->user()->role === 'manager')
                    <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/dashboard">
                        <span class="text-sm">▦</span>
                        <span>Command Center</span>
                    </a>
                @endif

                <a class="relative flex items-center gap-3 rounded-xl bg-indigo-600/20 text-indigo-300 px-3.5 py-2.5 font-bold transition border border-indigo-500/30" href="/workspace">
                    <span class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-indigo-500"></span>
                    <span class="text-sm">▤</span>
                    <span>Kanban Workspace</span>
                </a>
                <a class="flex items-center justify-between gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/notifications">
                    <span class="flex items-center gap-3"><span>🔔</span><span>Notifications</span></span>
                    @if(($unreadNotificationCount ?? 0) > 0)<span class="rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] text-white">{{ $unreadNotificationCount }}</span>@endif
                </a>

                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/ai-agent">
                    <span class="text-sm">⚡</span>
                    <span>AI Triage Studio</span>
                </a>

                @if(auth()->user()->role === 'manager')
                    <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="{{ route('workflow.settings') }}">
                        <span class="text-sm">⇄</span><span>Workflow Settings</span>
                    </a>
                    <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/add-developers">
                        <span class="text-sm">👥</span>
                        <span>Team & Skills Matrix</span>
                    </a>
                    <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/create-project">
                        <span class="text-sm">⚙</span>
                        <span>New Project Wizard</span>
                    </a>
                @endif
            </nav>

            <!-- User Footer -->
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

        <!-- Main Content Area -->
        <main class="min-w-0 flex-1 flex flex-col">
            <!-- Sticky Header (60px) -->
            <header class="sticky top-0 z-10 flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white/90 backdrop-blur-md px-5 lg:px-8">
                <div class="flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                    <span>Workspace</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-[#0F172A] font-bold">{{ session('project_name', 'Project') }}</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-indigo-600">Kanban Board</span>
                </div>

                <div class="flex items-center gap-3">
                    @include('components.project-switcher', ['variant' => 'mobile'])
                    <div class="flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-bold text-emerald-700">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="font-mono-code">Python Triage Agent: Ready</span>
                    </div>
                    <a href="/logout" class="rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                        Log out
                    </a>
                </div>
            </header>

            <!-- Board Body Container -->
            <div class="flex-1 mx-auto w-full max-w-[1700px] px-5 py-6 lg:px-8 space-y-5">
                
                <!-- Role-Aware Contextual Greeting Banner -->
                @php
                    $activeWorkflowStatuses = $workflowStatuses->where('is_done', false)->where('name', '!=', 'Duplicate')->pluck('name')->all();
                    $myAssignedCount = $bugs->where('assigned_to', auth()->user()->name)->whereIn('status', $activeWorkflowStatuses)->count();
                @endphp
                <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50/70 via-white to-blue-50/70 p-4 shadow-xs flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-600 text-white font-bold text-base shadow-sm">
                            👋
                        </div>
                        <div>
                            @if(auth()->user()->role === 'manager')
                                <h2 class="text-sm font-bold text-[#0F172A]">
                                    Good Day, {{ auth()->user()->name }}! 
                                    @if($stats['high'] > 0)
                                        <span class="text-rose-600 font-extrabold">{{ $stats['high'] }} High-Priority</span> bugs require attention today.
                                    @else
                                        All priority queues are operating smoothly.
                                    @endif
                                </h2>
                                <p class="text-xs text-[#64748B]">Automated developer routing active across {{ $developers->count() }} engineering profiles.</p>
                            @else
                                <h2 class="text-sm font-bold text-[#0F172A]">
                                    Welcome, {{ auth()->user()->name }}! 
                                    You have <span class="text-indigo-600 font-extrabold">{{ $myAssignedCount }} active</span> {{ Str::plural('bug', $myAssignedCount) }} assigned to your profile.
                                </h2>
                                <p class="text-xs text-[#64748B]">Click "Show Only Assigned to Me" below to focus exclusively on your queue.</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if(auth()->user()->role === 'manager' && $stats['high'] > 0)
                            <a href="/workspace?priority=High" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-rose-700 transition">
                                <span>🔥 Focus Critical Bugs ({{ $stats['high'] }})</span>
                            </a>
                        @endif
                        <button type="button" onclick="toggleMyIssues('{{ auth()->user()->name }}')" id="myIssuesToggle" 
                                class="inline-flex items-center gap-1.5 rounded-xl border border-indigo-200 bg-white px-3 py-1.5 text-xs font-bold text-indigo-700 shadow-2xs hover:bg-indigo-50 transition">
                            <span>👤 Show Only Assigned to Me ({{ $myAssignedCount }})</span>
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3.5 text-xs font-medium text-emerald-800 flex items-center gap-2.5 shadow-xs">
                        <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Filter & Control Toolbar -->
                <div class="rounded-2xl border border-[#E2E8F0] bg-white p-4 shadow-xs flex flex-col items-stretch gap-3">
                    <form id="issue-filters" method="GET" action="/workspace" class="flex flex-wrap items-center gap-2.5">
                        <!-- Real-time Search Box -->
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">⌕</span>
                            <input id="issue-search" data-live-search name="search" value="{{ $filters['search'] ?? '' }}" autocomplete="off" placeholder="Search issues, stack traces..."
                                   class="w-56 sm:w-64 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] py-2 pl-8 pr-3 text-xs outline-none transition focus:border-indigo-600 focus:bg-white">
                        </div>

                        <!-- Priority Dropdown -->
                        <select name="priority" class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A] outline-none transition focus:border-indigo-600">
                            <option value="">All Priorities</option>
                            @foreach(['High','Medium','Low'] as $priority)
                                <option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>{{ $priority }} Priority</option>
                            @endforeach
                        </select>

                        <select name="assignee" class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A]">
                            <option value="">All Assignees</option>
                            @foreach($availableAssignees as $assignee)<option value="{{ $assignee }}" @selected(($filters['assignee'] ?? '') === $assignee)>{{ $assignee }}</option>@endforeach
                        </select>
                        <select name="status" class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A]">
                            <option value="">All Statuses</option>
                            @foreach($workflowStatuses as $workflowStatus)<option value="{{ $workflowStatus->name }}" @selected(($filters['status'] ?? '') === $workflowStatus->name)>{{ $workflowStatus->name }}</option>@endforeach
                        </select>
                        <select name="issue_type" class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A]">
                            <option value="">All Issue Types</option>
                            @foreach(['Bug', 'Task', 'Story', 'Improvement'] as $type)<option value="{{ $type }}" @selected(($filters['issue_type'] ?? '') === $type)>{{ $type }}</option>@endforeach
                        </select>
                        <select name="label" class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A]">
                            <option value="">All Labels</option>
                            @foreach($availableLabels as $label)<option value="{{ $label }}" @selected(($filters['label'] ?? '') === $label)>{{ $label }}</option>@endforeach
                        </select>

                        <button type="button" id="clear-filters" class="rounded-xl border border-[#E2E8F0] bg-white px-3 py-2 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition">
                            Clear
                        </button>
                    </form>

                    <div class="flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3">
                        <select id="savedFilterSelect" class="min-w-48 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A]">
                            <option value="">Saved filters…</option>
                            @foreach($savedFilters as $savedFilter)
                                <option value="{{ $savedFilter->id }}" data-filters="{{ json_encode($savedFilter->filters) }}">{{ $savedFilter->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" id="loadSavedFilter" class="rounded-xl border border-[#E2E8F0] bg-white px-3 py-2 text-xs font-semibold">Load filter</button>
                        <button type="button" id="deleteSavedFilter" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700">Delete saved</button>
                        <form id="saveFilterForm" action="{{ route('bug-filters.store', [], false) }}" method="POST" class="ml-auto">
                            @csrf<div id="savedFilterPayload"></div>
                            <input type="hidden" name="name" id="savedFilterName">
                            <button type="button" id="saveCurrentFilter" class="rounded-xl bg-indigo-600 px-3 py-2 text-xs font-bold text-white">Save current filter</button>
                        </form>
                        <form id="deleteFilterForm" method="POST" class="hidden">@csrf @method('DELETE')</form>
                    </div>

                    <!-- Quick Toggles -->
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="toggleDuplicates()" id="duplicatesToggle" 
                                class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-1.5 text-xs font-bold text-[#475569] hover:border-indigo-300 transition">
                            🚫 Hide Duplicates
                        </button>
                    </div>
                </div>

                <!-- Empty Project State with Clear Guide Buttons (if 0 bugs in project) -->
                @if($bugs->count() === 0 && collect($filters)->filter()->isEmpty())
                    <div class="rounded-3xl border border-dashed border-[#CBD5E1] bg-white p-12 text-center my-6">
                        <div class="flex h-16 w-16 mx-auto items-center justify-center rounded-3xl bg-indigo-50 text-indigo-600 text-3xl mb-3">
                            🎉
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">আপনার প্রজেক্টে এখনো কোনো বাগ নেই!</h3>
                        <p class="mt-1 max-w-md mx-auto text-xs text-[#64748B] leading-relaxed">
                            টিম মেম্বারদের যোগ করুন অথবা এআই এজেন্ট স্টুডিও থেকে সরাসরি টেস্ট বাগ সাবমিট করে অটোমেটিক ট্রায়াজ পরীক্ষা করুন।
                        </p>
                        <div class="mt-5 flex items-center justify-center gap-3">
                            <a href="/add-developers" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-4 py-2 transition">
                                + প্রথম টিম মেম্বার যোগ করুন
                            </a>
                            <a href="/ai-agent" class="rounded-xl border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-bold text-xs px-4 py-2 transition">
                                🤖 টেস্ট বাগ সাবমিট করুন
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Search Empty State -->
                @if($bugs->count() === 0 && collect($filters)->filter()->isNotEmpty())
                    <div class="rounded-2xl border border-dashed border-[#CBD5E1] bg-white p-8 text-center my-4">
                        <span class="text-2xl text-slate-400">🔍</span>
                        <p class="text-sm font-bold text-[#0F172A] mt-2">"{{ request('search') }}" নামে কোনো বাগ পাওয়া যায়নি।</p>
                        <a href="/workspace" class="inline-block mt-3 text-xs font-bold text-indigo-600 hover:underline">
                            ↺ সব ফিল্টার মুছে ফেলুন (Clear Filters)
                        </a>
                    </div>
                @endif

                <!-- Workflow-driven Kanban columns -->
                <div class="jira-scroll grid gap-4 overflow-x-auto pb-4" style="grid-template-columns: repeat({{ $workflowStatuses->count() }}, minmax(280px, 1fr));">
                    @foreach($workflowStatuses as $workflowStatus)
                        @php
                            $columnStatus = $workflowStatus->name;
                            $colBugs = $bugs->where('status', $columnStatus);
                            $columnColors = $workflowStatus->is_done
                                ? ['dot' => 'bg-emerald-500', 'border' => 'border-t-emerald-500']
                                : ($loop->first ? ['dot' => 'bg-indigo-500', 'border' => 'border-t-indigo-500'] : ['dot' => 'bg-amber-500', 'border' => 'border-t-amber-500']);
                        @endphp
                        <section class="rounded-2xl border border-[#E2E8F0] border-t-4 {{ $columnColors['border'] }} bg-[#F1F5F9]/80 p-3.5 flex flex-col min-h-[500px]">
                            <!-- Column Header -->
                            <div class="mb-3 flex items-center justify-between px-1">
                                <div class="flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 rounded-full {{ $columnColors['dot'] }}"></span>
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#475569]">{{ $columnStatus }}</h3>
                                </div>
                                <span class="rounded-full bg-white border border-[#CBD5E1] px-2 py-0.5 text-xs font-mono-code font-bold text-slate-700 shadow-xs" id="col-count-{{ Str::slug($columnStatus) }}">
                                    {{ $colBugs->count() }}
                                </span>
                            </div>

                            <!-- Column Cards Container with Independent Scroll & Drag Target -->
                            <div class="space-y-3 flex-1 overflow-y-auto max-h-[680px] pr-1 custom-scroll kanban-dropzone rounded-xl transition-all duration-150 p-1" 
                                 id="column-{{ Str::slug($columnStatus) }}"
                                 data-status="{{ $columnStatus }}"
                                 ondragover="handleDragOver(event)"
                                 ondragleave="handleDragLeave(event)"
                                 ondrop="handleDrop(event, '{{ $columnStatus }}')">
                                @forelse($colBugs as $bug)
                                    @php
                                        $triage = json_decode($bug->triage_result ?? '', true);
                                        $hasAi = !empty($triage['code_help']);
                                        $feat = $triage['code_help']['feature'] ?? ($bug->detected_feature ?? 'Module');
                                        $activity = $bugActivity->get($bug->id, collect())->values()->all();
                                        $triageMetric = $bugMetrics->get($bug->id);
                                        
                                        // Priority Strip Class
                                        $priorityClass = $bug->priority === 'High' ? 'priority-strip-high' : ($bug->priority === 'Low' ? 'priority-strip-low' : 'priority-strip-medium');
                                        $prioritySymbol = $bug->priority === 'High' ? '🔥 ▲ High' : ($bug->priority === 'Low' ? '▼ Low' : '● Medium');
                                    @endphp
                                    <!-- 4-Layer Card Anatomy with Drag-and-Drop & 4px Left Accent Strip -->
                                    <article id="bug-{{ $bug->id }}" class="bug-card rounded-2xl border border-[#E2E8F0] {{ $priorityClass }} bg-white p-4 shadow-xs card-hover-lift cursor-grab active:cursor-grabbing transition"
                                             data-id="{{ $bug->id }}"
                                             data-status="{{ $bug->status }}"
                                             data-assignee="{{ $bug->assigned_to }}"
                                             data-duplicate="{{ $bug->is_duplicate ? 'true' : 'false' }}"
                                             draggable="true"
                                             ondragstart="handleDragStart(event, {{ $bug->id }}, '{{ $bug->status }}')"
                                             ondragend="handleDragEnd(event)"
                                             onclick="openDrawer({{ $bug->id }})">
                                        
                                        <!-- Layer 1: Top Meta (#ABT-X + Priority with Symbol) -->
                                        <div class="flex items-center justify-between mb-2">
                                            <a href="/bugs/{{ $bug->id }}" title="Open full issue page" onclick="event.stopPropagation()" class="font-mono-code font-bold text-xs text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded px-1.5 py-0.5 transition inline-flex items-center gap-1">
                                                <span>#ABT-{{ $bug->id }}</span>
                                                <span class="text-[9px]">↗</span>
                                            </a>
                                            <span class="rounded px-2 py-0.5 text-[10px] font-bold {{ $bug->priority === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($bug->priority === 'Low' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                                {{ $prioritySymbol }}
                                            </span>
                                        </div>

                                        <!-- Layer 2: Title (Line Clamp 2) -->
                                        <h4 class="text-xs sm:text-sm font-bold text-[#0F172A] leading-snug line-clamp-2">
                                            {{ $bug->title }}
                                        </h4>

                                        <!-- Layer 3: AI Badges & Duplicate Warning with Plain Language Tooltip -->
                                        <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                                            <span class="rounded bg-purple-50 border border-purple-200 px-2 py-0.5 text-[10px] font-bold text-purple-700">
                                                ✨ Feature: {{ $feat }}
                                            </span>
                                            @if($bug->is_duplicate)
                                                <span title="এই বাগটি আগে রিপোর্ট করা #ABT-{{ $bug->duplicate_of }} এর সাথে হুবহু মিলে গেছে, তাই নতুন করে কাজ করার প্রয়োজন নেই।" 
                                                      class="rounded bg-amber-50 border border-amber-200 px-2 py-0.5 text-[10px] font-bold text-amber-800 cursor-help">
                                                    ⚠️ Duplicate of #ABT-{{ $bug->duplicate_of }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Layer 4: Footer Divider (Assignee + 1-Click Status Advance + Patch Ready) -->
                                        <div class="mt-3 pt-2.5 border-t border-[#E2E8F0] flex items-center justify-between gap-2" onclick="event.stopPropagation()">
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-600 to-blue-600 text-white text-[10px] font-mono-code font-bold uppercase">
                                                    {{ strtoupper(substr($bug->assigned_to ?: 'U', 0, 1)) }}
                                                </div>
                                                <span class="truncate max-w-[85px] text-[11px] font-medium text-[#64748B]">
                                                    {{ $bug->assigned_to ?: 'Unassigned' }}
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-1.5 shrink-0">
                                                @if($hasAi)
                                                    <span title="AI কোড ফিক্স ও সলিউশন ড্রয়ারে প্রস্তুত" class="inline-flex items-center gap-0.5 rounded px-1.5 py-0.5 text-[10px] font-mono-code font-bold bg-emerald-50 border border-emerald-200 text-emerald-700 cursor-help">
                                                        &lt;/&gt; Patch Ready
                                                    </span>
                                                @endif

                                                <!-- 1-Click Quick Status Advance Button -->
                                                @if(auth()->user()->role === 'tester')
                                                    <span class="text-[10px] text-slate-400">{{ $bug->status }}</span>
                                                @elseif($transitionsByStatus->has($bug->status) && $transitionsByStatus->get($bug->status)->isNotEmpty())
                                                    <form action="/api/bugs/override" method="POST" class="inline-block">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $bug->id }}">
                                                        <select name="status" onchange="this.form.submit()" 
                                                                class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-[#0F172A] outline-none hover:bg-white transition">
                                                            <option value="" selected disabled>{{ $bug->status }}</option>
                                                            @foreach($transitionsByStatus->get($bug->status) as $transition)
                                                                <option value="{{ $transition->to_status }}">Move to {{ $transition->to_status }}</option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                @else
                                                    <span class="text-[10px] text-slate-400">{{ $bug->status }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </article>

                                    <!-- Hidden Drawer Template Content for this Bug -->
                                    <div id="drawer-data-{{ $bug->id }}" class="hidden">
                                        <div data-id="{{ $bug->id }}"
                                             data-title="{{ $bug->title }}"
                                             data-desc="{{ $bug->description }}"
                                             data-priority="{{ $bug->priority }}"
                                             data-sentiment="{{ $bug->sentiment ?? 'neutral' }}"
                                             data-blocker="{{ ($bug->blocker_signal ?? false) ? 'true' : 'false' }}"
                                             data-status="{{ $bug->status }}"
                                             data-assignee="{{ $bug->assigned_to ?: 'Unassigned' }}"
                                             data-recommended-assignee="{{ $bug->recommended_assignee ?: ($bug->assigned_to ?: 'Unassigned') }}"
                                             data-assignment-feedback="{{ $triageMetric->assignment_feedback ?? '' }}"
                                             data-predicted-duplicate="{{ ($triageMetric->predicted_duplicate ?? $bug->is_duplicate) ? 'true' : 'false' }}"
                                             data-actual-duplicate="{{ isset($triageMetric) && $triageMetric->actual_duplicate !== null ? ($triageMetric->actual_duplicate ? 'true' : 'false') : '' }}"
                                             data-feat="{{ $feat }}"
                                             data-error="{{ $triage['code_help']['error_name'] ?? 'Analysis complete' }}"
                                             data-scope="{{ $triage['code_help']['analysis_scope'] ?? 'This limited static check does not prove the code is correct.' }}"
                                             data-syntax-status="{{ $triage['code_help']['syntax_status'] ?? 'unknown' }}"
                                             data-syntax-checker="{{ $triage['code_help']['syntax_checker'] ?? '' }}"
                                             data-steps="{{ json_encode($triage['code_help']['steps'] ?? []) }}"
                                             data-fix-suggestions="{{ json_encode($triage['code_help']['fix_suggestions'] ?? []) }}"
                                             data-fixed="{{ $triage['code_help']['fixed_code'] ?? '' }}"
                                             data-diff="{{ $triage['code_help']['diff'] ?? '' }}"
                                             data-suspected-files="{{ json_encode($triage['code_help']['suspected_files'] ?? ($triage['suspected_files'] ?? [])) }}"
                                             data-history="{{ json_encode($activity) }}"
                                             data-comments="{{ json_encode($bugComments->get($bug->id, collect())->values()) }}"
                                             data-clarifications="{{ json_encode($clarifications->get($bug->id, collect())->values()) }}"
                                             data-watching="{{ in_array($bug->id, $watchingBugIds, true) ? 'true' : 'false' }}"
                                             data-project-users="{{ json_encode($projectMembers) }}"
                                             data-reason="{{ $triage['code_help']['assignment_reason'] ?? 'Matched developer tech stack keywords.' }}">
                                        </div>
                                    </div>
                                @empty
                                    <!-- Empty Column Illustration -->
                                    <div class="py-12 text-center border border-dashed border-[#CBD5E1] rounded-2xl bg-white/60">
                                        <span class="text-2xl text-slate-300">✓</span>
                                        <p class="text-xs text-[#64748B] mt-1 font-medium">No issues in this stage</p>
                                    </div>
                                @endforelse
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>
        </main>
    </div>

    <!-- Slide-Over Drawer (Right-side Panel) -->
    <div id="drawerBackdrop" class="fixed inset-0 bg-slate-950/40 backdrop-blur-xs z-50 hidden opacity-0 transition-opacity duration-300" onclick="closeDrawer()"></div>
    <div id="slideDrawer" class="fixed right-0 top-0 bottom-0 w-full max-w-xl bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out border-l border-[#E2E8F0] flex flex-col">
        <!-- Drawer Header -->
        <div class="flex items-center justify-between p-5 border-b border-[#E2E8F0] bg-[#F8FAFC]">
            <div class="flex items-center gap-2">
                <span id="drawerBugId" class="font-mono-code font-bold text-xs text-indigo-600 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded">
                    #ABT-
                </span>
                <span id="drawerPriorityPill" class="rounded px-2 py-0.5 text-[10px] font-bold"></span>
            </div>
            <button type="button" onclick="closeDrawer()" class="text-slate-400 hover:text-slate-700 font-bold p-1 text-sm">
                ✕ Close
            </button>
        </div>

        <!-- Bug detail tabs -->
        <div class="flex border-b border-[#E2E8F0] bg-white text-xs font-bold text-[#64748B]">
            <button type="button" onclick="switchDrawerTab('tabDetails')" id="btnTabDetails" class="flex-1 py-3 border-b-2 border-indigo-600 text-indigo-600 transition text-center">
                1. Details
            </button>
            <button type="button" onclick="switchDrawerTab('tabTriage')" id="btnTabTriage" class="flex-1 py-3 border-b-2 border-transparent hover:text-[#0F172A] transition text-center">
                2. AI Triage Report
            </button>
            <button type="button" onclick="switchDrawerTab('tabPatch')" id="btnTabPatch" class="flex-1 py-3 border-b-2 border-transparent hover:text-[#0F172A] transition text-center">
                3. Code Reference
            </button>
            <button type="button" onclick="switchDrawerTab('tabActivity')" id="btnTabActivity" class="flex-1 py-3 border-b-2 border-transparent hover:text-[#0F172A] transition text-center">
                4. Activity
            </button>
            <button type="button" onclick="switchDrawerTab('tabDiscussion')" id="btnTabDiscussion" class="flex-1 py-3 border-b-2 border-transparent hover:text-[#0F172A] transition text-center">
                5. Discussion
            </button>
        </div>

        <!-- Drawer Body Tabs Content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scroll">
            <!-- Tab 1: Details -->
            <div id="tabDetails" class="space-y-4">
                <div class="flex flex-wrap items-center gap-2">
                    <a id="drawerFullPageLink" href="#" class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-2 text-xs font-bold shadow-2xs transition">
                        <span>Open Full Issue Page</span> ↗
                    </a>
                    <a id="drawerReportLink" href="#" target="_blank" class="inline-flex rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition">Print / Save report as PDF</a>
                </div>
                <div>
                    <h3 id="drawerTitle" class="text-lg font-extrabold text-[#0F172A] leading-snug"></h3>
                    <p id="drawerDesc" class="mt-2 text-xs text-[#475569] leading-relaxed whitespace-pre-line"></p>
                </div>
                <div class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] p-4 text-xs space-y-2">
                    <p><b>Feature Module:</b> <span id="drawerFeature" class="font-bold text-purple-700"></span></p>
                    <p><b>Assigned Developer:</b> <span id="drawerAssignee" class="font-bold text-indigo-600"></span></p>
                </div>
                @if(auth()->user()->role === 'manager')
                    <form id="reassignBugForm" action="{{ route('bugs.reassign', [], false) }}" method="POST" class="rounded-xl border border-indigo-200 bg-indigo-50/60 p-4 space-y-2" onclick="event.stopPropagation()">
                        @csrf
                        <input type="hidden" name="id" id="reassignBugId">
                        <label for="reassignDeveloper" class="block text-xs font-bold text-indigo-950">Reassign bug</label>
                        <div class="flex gap-2">
                            <select id="reassignDeveloper" name="assigned_to" class="min-w-0 flex-1 rounded-lg border border-indigo-200 bg-white px-2.5 py-2 text-xs text-slate-800">
                                <option value="Unassigned">Unassigned</option>
                                @foreach($developers as $developer)
                                    <option value="{{ $developer->name }}">{{ $developer->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-bold text-white hover:bg-indigo-500">Save</button>
                        </div>
                    </form>
                    <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3" onclick="event.stopPropagation()">
                        <div><p class="text-xs font-bold text-slate-900">Agent recommendation review</p><p class="mt-1 text-[11px] text-slate-500">Confirm whether the duplicate and assignment recommendations are correct to measure acceptance, precision and recall.</p></div>
                        <p class="text-xs">Recommended assignee: <b id="drawerRecommendedAssignee"></b><span id="drawerAssignmentFeedback" class="ml-2 text-[10px] text-slate-500"></span></p>
                        <form id="acceptRecommendationForm" action="{{ route('bugs.recommendation.accept', [], false) }}" method="POST" class="hidden">@csrf<input type="hidden" name="id" id="acceptRecommendationBugId"><button id="acceptRecommendationButton" class="rounded-lg bg-emerald-600 px-3 py-2 text-[11px] font-bold text-white">Accept recommendation</button></form>
                        <form id="duplicateReviewForm" action="{{ route('bugs.duplicate-review', [], false) }}" method="POST" class="space-y-2">
                            @csrf<input type="hidden" name="id" id="reviewDuplicateBugId">
                            <p id="duplicateReviewState" class="text-[11px] text-slate-600"></p>
                            <div class="flex flex-wrap gap-2"><button id="confirmDuplicateValue" name="actual_duplicate" value="1" class="rounded-lg border border-slate-300 px-2.5 py-1.5 text-[10px] font-semibold">Confirm prediction</button><button id="correctDuplicateValue" name="actual_duplicate" value="0" class="rounded-lg border border-slate-300 px-2.5 py-1.5 text-[10px] font-semibold">Correct prediction</button></div>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Tab 2: AI Triage Report with Interactive Checkbox Checklist -->
            <div id="tabTriage" class="hidden space-y-4">
                <div class="rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-xs text-rose-800">
                    <p class="font-bold">Code Validation Result:</p>
                    <p id="drawerSyntaxStatus" class="mt-1 font-bold"></p>
                    <p id="drawerError" class="mt-1 font-mono-code"></p>
                    <p id="drawerSyntaxChecker" class="mt-1"></p>
                    <p id="drawerScope" class="mt-2"></p>
                </div>
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-3.5 text-xs text-amber-950">
                    <p class="font-bold">Possible fix suggestions · review before applying</p>
                    <ol id="drawerFixSuggestions" class="mt-2 space-y-2"></ol>
                </div>
                <div id="drawerSuspectedFilesSection" class="hidden rounded-xl border border-sky-200 bg-sky-50/70 p-3.5 text-xs text-sky-950">
                    <div class="flex items-center gap-1.5 font-bold text-sky-900">
                        <span>🔍</span>
                        <span>Suspected Culprit Files (Root Cause Locator)</span>
                    </div>
                    <p class="mt-1 text-[11px] text-sky-700">Agent analyzed project file tree to pinpoint where this bug likely originates:</p>
                    <div id="drawerSuspectedFilesList" class="mt-2.5 space-y-1.5"></div>
                </div>
                <div class="rounded-xl bg-indigo-50 border border-indigo-200 p-3.5 text-xs text-indigo-900">
                    <p class="font-bold">Smart Assignment Rationale:</p>
                    <p id="drawerReason" class="mt-1"></p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-2">Step-by-Step Interactive Fix Checklist:</h4>
                    <ul id="drawerStepsList" class="space-y-2 text-xs text-[#0F172A]"></ul>
                </div>
            </div>

            <!-- Tab 3: Submitted code reference -->
            <div id="tabPatch" class="hidden space-y-4">
                <div class="flex items-center gap-2 border-b border-[#E2E8F0] pb-2 text-xs font-bold">
                    <button type="button" onclick="togglePatchView('final')" id="btnViewFinal" class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Submitted Code
                    </button>
                </div>

                <div id="viewFinalContainer">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#0F172A]">Submitted code (original)</span>
                        <button type="button" onclick="copyPatchCode()" id="copyPatchBtn" 
                                class="rounded-lg border border-[#CBD5E1] bg-white px-2.5 py-1 text-xs font-bold text-indigo-600 hover:bg-[#F1F5F9] transition">
                            📋 Copy Submitted Code
                        </button>
                    </div>
                    <p class="mb-2 text-[11px] text-slate-600">Suggestions are displayed in the Triage tab for review. This original snippet is never changed automatically.</p>
                    <textarea id="drawerFixedCode" readonly spellcheck="false" 
                              class="w-full min-h-[220px] rounded-xl bg-slate-950 p-4 font-mono-code text-xs leading-5 text-emerald-400 border border-slate-800 focus:outline-none"></textarea>
                </div>

                <div id="viewDiffContainer" class="hidden">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#0F172A] block mb-2">Code Changes (Unified Diff)</span>
                    <textarea id="drawerDiff" readonly spellcheck="false" 
                              class="w-full min-h-[180px] rounded-xl bg-slate-950 p-4 font-mono-code text-xs leading-5 text-indigo-300 border border-slate-800 focus:outline-none"></textarea>
                </div>
            </div>

            <div id="tabActivity" class="hidden space-y-3">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Status & assignment history</h4>
                    <p class="mt-1 text-[11px] text-slate-500">New changes are recorded with the acting user's name and time.</p>
                </div>
                <ol id="drawerActivityList" class="space-y-3"></ol>
            </div>

            <div id="tabDiscussion" class="hidden space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div><h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B]">Comments & mentions</h4><p class="mt-1 text-[11px] text-slate-500">Mention a project member to notify them. Watch to receive bug updates.</p></div>
                    <form action="{{ route('bugs.watch', [], false) }}" method="POST" onclick="event.stopPropagation()">
                        @csrf<input type="hidden" name="bug_id" id="watchBugId">
                        <button type="submit" id="watchBugButton" class="rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-2 text-[11px] font-bold text-indigo-700">Watch</button>
                    </form>
                </div>
                <ol id="drawerCommentsList" class="space-y-3"></ol>
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-3">
                    <p id="drawerSentiment" class="text-xs font-bold text-amber-900">Report signal: Neutral</p>
                    @if(auth()->user()->role === 'developer')
                        <form action="{{ route('bugs.clarifications.request', [], false) }}" method="POST" class="mt-2 space-y-2" onclick="event.stopPropagation()">
                            @csrf<input type="hidden" name="bug_id" id="clarificationBugId">
                            <textarea name="question" required maxlength="2000" rows="2" placeholder="Ask the reporter for missing steps or expected behavior…" class="w-full rounded-lg border border-amber-300 bg-white p-2 text-xs"></textarea>
                            <button class="rounded-lg border border-amber-300 bg-white px-3 py-2 text-[11px] font-bold text-amber-900">Request clarification</button>
                        </form>
                    @endif
                </div>
                <ol id="drawerClarificationsList" class="space-y-2"></ol>
                <form action="{{ route('bugs.comments', [], false) }}" method="POST" id="bugCommentForm" class="space-y-2 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] p-3" onclick="event.stopPropagation()">
                    @csrf<input type="hidden" name="bug_id" id="commentBugId">
                    <div id="mentionInputs"></div>
                    <div class="flex gap-2"><select id="mentionMember" class="min-w-0 flex-1 rounded-lg border border-slate-300 bg-white px-2 py-2 text-xs"><option value="">Mention teammate…</option></select><button type="button" onclick="insertMention()" class="rounded-lg border border-slate-300 bg-white px-2.5 text-xs font-semibold">@ Mention</button></div>
                    <textarea name="body" id="commentBody" required maxlength="5000" rows="3" placeholder="Write a comment…" class="w-full rounded-lg border border-slate-300 bg-white p-2.5 text-xs"></textarea>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white">Post comment</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDrawer(bugId) {
            const container = document.getElementById('drawer-data-' + bugId);
            if (!container) return;
            const data = container.firstElementChild;
            const bugIdValue = data.getAttribute('data-id');
            document.getElementById('watchBugId').value = bugIdValue;
            document.getElementById('commentBugId').value = bugIdValue;
            const clarificationBugId = document.getElementById('clarificationBugId');
            if (clarificationBugId) clarificationBugId.value = bugIdValue;
            const watchButton = document.getElementById('watchBugButton');
            watchButton.textContent = data.getAttribute('data-watching') === 'true' ? 'Unwatch' : 'Watch';
            const memberSelect = document.getElementById('mentionMember');
            memberSelect.innerHTML = '<option value="">Mention teammate…</option>';
            try {
                JSON.parse(data.getAttribute('data-project-users') || '[]').forEach((member) => {
                    if (String(member.id) === String({{ auth()->id() }})) return;
                    const option = document.createElement('option'); option.value = member.id; option.textContent = `${member.name} · ${member.role}`; memberSelect.appendChild(option);
                });
            } catch (error) {}
            renderComments(data.getAttribute('data-comments'));
            const signal = document.getElementById('drawerSentiment');
            const blocker = data.getAttribute('data-blocker') === 'true';
            const sentiment = data.getAttribute('data-sentiment') || 'neutral';
            signal.textContent = blocker ? `Blocker signal detected · ${sentiment} sentiment` : `Report signal: ${sentiment}`;
            signal.className = `text-xs font-bold ${blocker ? 'text-rose-800' : 'text-amber-900'}`;
            renderClarifications(data.getAttribute('data-clarifications'));

            document.getElementById('drawerBugId').innerText = '#ABT-' + data.getAttribute('data-id');
            document.getElementById('drawerReportLink').href = `/bugs/${encodeURIComponent(bugIdValue)}/report`;
            const fullPageLink = document.getElementById('drawerFullPageLink');
            if (fullPageLink) fullPageLink.href = `/bugs/${encodeURIComponent(bugIdValue)}`;
            document.getElementById('drawerTitle').innerText = data.getAttribute('data-title');
            document.getElementById('drawerDesc').innerText = data.getAttribute('data-desc');
            document.getElementById('drawerFeature').innerText = data.getAttribute('data-feat');
            document.getElementById('drawerAssignee').innerText = data.getAttribute('data-assignee');
            const reassignBugId = document.getElementById('reassignBugId');
            const reassignDeveloper = document.getElementById('reassignDeveloper');
            if (reassignBugId && reassignDeveloper) {
                reassignBugId.value = data.getAttribute('data-id');
                const currentAssignee = data.getAttribute('data-assignee') || 'Unassigned';
                reassignDeveloper.value = Array.from(reassignDeveloper.options).some(option => option.value === currentAssignee)
                    ? currentAssignee
                    : 'Unassigned';
            }
            const metricIds = ['acceptRecommendationBugId', 'reviewDuplicateBugId'];
            metricIds.forEach(id => { const input = document.getElementById(id); if (input) input.value = bugIdValue; });
            const recommendedAssignee = data.getAttribute('data-recommended-assignee') || 'Unassigned';
            const assignmentFeedback = data.getAttribute('data-assignment-feedback') || '';
            const recLabel = document.getElementById('drawerRecommendedAssignee');
            if (recLabel) recLabel.textContent = recommendedAssignee;
            const feedbackLabel = document.getElementById('drawerAssignmentFeedback');
            if (feedbackLabel) feedbackLabel.textContent = assignmentFeedback ? `(${assignmentFeedback})` : '';
            const acceptForm = document.getElementById('acceptRecommendationForm');
            if (acceptForm) acceptForm.classList.toggle('hidden', assignmentFeedback !== '' || recommendedAssignee === 'Unassigned' || recommendedAssignee !== (data.getAttribute('data-assignee') || 'Unassigned'));
            const predictedDuplicate = data.getAttribute('data-predicted-duplicate') === 'true';
            const actualDuplicate = data.getAttribute('data-actual-duplicate');
            const duplicateState = document.getElementById('duplicateReviewState');
            const confirmDuplicateValue = document.getElementById('confirmDuplicateValue');
            const correctDuplicateValue = document.getElementById('correctDuplicateValue');
            if (duplicateState) duplicateState.textContent = actualDuplicate === '' ? `Agent predicted ${predictedDuplicate ? 'Duplicate' : 'Not duplicate'}; review its prediction:` : `Manager confirmed ${actualDuplicate === 'true' ? 'Duplicate' : 'Not duplicate'}.`;
            if (confirmDuplicateValue && correctDuplicateValue) {
                confirmDuplicateValue.value = predictedDuplicate ? '1' : '0';
                confirmDuplicateValue.textContent = `Confirm ${predictedDuplicate ? 'Duplicate' : 'Not duplicate'}`;
                correctDuplicateValue.value = predictedDuplicate ? '0' : '1';
                correctDuplicateValue.textContent = `Correct to ${predictedDuplicate ? 'Not duplicate' : 'Duplicate'}`;
                confirmDuplicateValue.disabled = actualDuplicate !== '';
                correctDuplicateValue.disabled = actualDuplicate !== '';
            }
            document.getElementById('drawerError').innerText = data.getAttribute('data-error');
            const syntaxStatus = data.getAttribute('data-syntax-status') || 'unknown';
            const syntaxLabels = { passed: 'Syntax check passed', failed: 'Syntax errors found', timeout: 'Checker timed out', not_available: 'Checker unavailable', not_run: 'Check not run', unknown: 'Check status unknown' };
            document.getElementById('drawerSyntaxStatus').innerText = syntaxLabels[syntaxStatus] || 'Check status unknown';
            document.getElementById('drawerSyntaxChecker').innerText = data.getAttribute('data-syntax-checker') ? `Checker: ${data.getAttribute('data-syntax-checker')}` : '';
            document.getElementById('drawerScope').innerText = data.getAttribute('data-scope');
            const suggestionsList = document.getElementById('drawerFixSuggestions');
            suggestionsList.innerHTML = '';
            try {
                const suggestions = JSON.parse(data.getAttribute('data-fix-suggestions') || '[]');
                if (suggestions.length === 0) {
                    const empty = document.createElement('li');
                    empty.className = 'text-slate-700';
                    empty.textContent = 'No specific fix suggestion was identified. The submitted code remains unchanged.';
                    suggestionsList.appendChild(empty);
                }
                suggestions.forEach((suggestion) => {
                    const item = document.createElement('li');
                    item.className = 'rounded-lg border border-amber-200 bg-white/80 p-2.5';
                    const title = document.createElement('p');
                    title.className = 'font-bold';
                    title.textContent = suggestion.title;
                    const detail = document.createElement('p');
                    detail.className = 'mt-1';
                    detail.textContent = suggestion.detail;
                    item.append(title, detail);
                    const code = suggestion.proposed_code || suggestion.example_code;
                    if (code) {
                        const details = document.createElement('details');
                        details.className = 'mt-2';
                        const summary = document.createElement('summary');
                        summary.className = 'cursor-pointer font-semibold';
                        summary.textContent = suggestion.proposed_code ? 'Preview proposed edit (not applied)' : 'View example guard (not applied)';
                        const preview = document.createElement('pre');
                        preview.className = 'mt-2 overflow-x-auto rounded-lg bg-slate-950 p-2.5 font-mono-code text-[11px] leading-5 text-emerald-300';
                        preview.textContent = code;
                        details.append(summary, preview);
                        item.appendChild(details);
                    }
                    suggestionsList.appendChild(item);
                });
            } catch (error) {
                suggestionsList.textContent = 'Fix suggestions could not be loaded. The submitted code remains unchanged.';
            }
            document.getElementById('drawerReason').innerText = data.getAttribute('data-reason');

            const suspectedFilesRaw = data.getAttribute('data-suspected-files');
            const suspectedSection = document.getElementById('drawerSuspectedFilesSection');
            const suspectedList = document.getElementById('drawerSuspectedFilesList');
            if (suspectedSection && suspectedList) {
                suspectedList.innerHTML = '';
                let files = [];
                try { files = JSON.parse(suspectedFilesRaw || '[]'); } catch (e) { files = []; }
                if (files && files.length > 0) {
                    files.forEach(f => {
                        const item = document.createElement('div');
                        item.className = 'flex items-center justify-between gap-2 rounded-lg bg-white border border-sky-200 px-2.5 py-1.5 text-xs';
                        const left = document.createElement('div');
                        left.className = 'flex items-center gap-1.5 min-w-0';
                        const filePath = typeof f === 'string' ? f : (f.path || '');
                        left.innerHTML = `<span class="text-slate-400 font-mono text-[10px]">📄</span><span class="font-mono text-[11px] font-semibold text-slate-800 truncate">${filePath}</span>`;
                        item.appendChild(left);
                        if (f && f.github_url) {
                            const link = document.createElement('a');
                            link.href = f.github_url;
                            link.target = '_blank';
                            link.rel = 'noreferrer noopener';
                            link.className = 'inline-flex items-center gap-1 rounded bg-sky-100 hover:bg-sky-200 text-sky-800 text-[10px] font-bold px-2 py-0.5 shrink-0 transition';
                            link.innerHTML = `<span>View on GitHub</span> ↗`;
                            item.appendChild(link);
                        }
                        suspectedList.appendChild(item);
                    });
                    suspectedSection.classList.remove('hidden');
                } else {
                    suspectedSection.classList.add('hidden');
                }
            }
            document.getElementById('drawerFixedCode').value = data.getAttribute('data-fixed') || '// No submitted code provided.';
            document.getElementById('drawerDiff').value = data.getAttribute('data-diff') || '// No diff available.';

            const activityList = document.getElementById('drawerActivityList');
            activityList.innerHTML = '';
            try {
                const activity = JSON.parse(data.getAttribute('data-history') || '[]');
                activity.forEach((event) => {
                    const item = document.createElement('li');
                    item.className = 'rounded-xl border border-[#E2E8F0] bg-white p-3 text-xs';
                    const labels = { created: 'Bug created', status_changed: 'Status changed', duplicate_reviewed: 'Duplicate classification reviewed', clarification_requested: 'Clarification requested', clarification_answered: 'Clarification answered', assignment_changed: 'Assignment changed' };
                    const label = labels[event.event_type] || 'Bug updated';
                    const detail = event.event_type === 'created'
                        ? (event.to_value || '')
                        : `${event.from_value || '—'} → ${event.to_value || '—'}`;
                    const heading = document.createElement('p');
                    heading.className = 'font-bold text-slate-900';
                    heading.textContent = label;
                    const change = document.createElement('p');
                    change.className = 'mt-1 text-slate-700';
                    change.textContent = detail;
                    const meta = document.createElement('p');
                    meta.className = 'mt-2 text-[10px] text-slate-500';
                    const timestamp = new Date((event.created_at || '').replace(' ', 'T'));
                    const timeLabel = Number.isNaN(timestamp.getTime()) ? event.created_at : timestamp.toLocaleString();
                    meta.textContent = `${event.actor_name} · ${timeLabel}`;
                    item.append(heading, change, meta);
                    activityList.appendChild(item);
                });
                if (activity.length === 0) {
                    const empty = document.createElement('li');
                    empty.className = 'rounded-xl border border-dashed border-slate-300 p-4 text-xs text-slate-500';
                    empty.textContent = 'No activity was recorded for this bug.';
                    activityList.appendChild(empty);
                }
            } catch (error) {
                activityList.textContent = 'Activity history could not be loaded.';
            }

            // Priority pill
            const priority = data.getAttribute('data-priority');
            const pill = document.getElementById('drawerPriorityPill');
            pill.innerText = priority + ' Priority';
            pill.className = 'rounded px-2 py-0.5 text-[10px] font-bold ' + 
                (priority === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200');

            // Interactive Checklist
            const stepsList = document.getElementById('drawerStepsList');
            stepsList.innerHTML = '';
            try {
                const steps = JSON.parse(data.getAttribute('data-steps'));
                steps.forEach((step, idx) => {
                    const li = document.createElement('li');
                    li.className = 'flex items-start gap-2.5 bg-[#F8FAFC] p-2 rounded-xl border border-[#E2E8F0]';
                    li.innerHTML = `
                        <input type="checkbox" id="step-${idx}" class="mt-0.5 h-4 w-4 rounded border-[#CBD5E1] text-indigo-600 focus:ring-indigo-500 cursor-pointer" onchange="this.parentElement.classList.toggle('opacity-50', this.checked)">
                        <label for="step-${idx}" class="cursor-pointer font-medium leading-relaxed">${step}</label>
                    `;
                    stepsList.appendChild(li);
                });
            } catch(e) {}

            switchDrawerTab('tabDetails');
            togglePatchView('final');
            document.getElementById('drawerBackdrop').classList.remove('hidden');
            setTimeout(() => document.getElementById('drawerBackdrop').classList.remove('opacity-0'), 10);
            document.getElementById('slideDrawer').classList.remove('translate-x-full');
        }

        function closeDrawer() {
            document.getElementById('slideDrawer').classList.add('translate-x-full');
            document.getElementById('drawerBackdrop').classList.add('opacity-0');
            setTimeout(() => document.getElementById('drawerBackdrop').classList.add('hidden'), 300);
        }

        function switchDrawerTab(tabId) {
            ['tabDetails', 'tabTriage', 'tabPatch', 'tabActivity', 'tabDiscussion'].forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });
            ['btnTabDetails', 'btnTabTriage', 'btnTabPatch', 'btnTabActivity', 'btnTabDiscussion'].forEach(id => {
                document.getElementById(id).classList.remove('border-indigo-600', 'text-indigo-600');
                document.getElementById(id).classList.add('border-transparent');
            });

            document.getElementById(tabId).classList.remove('hidden');
            const activeBtn = ({ tabDetails: 'btnTabDetails', tabTriage: 'btnTabTriage', tabPatch: 'btnTabPatch', tabActivity: 'btnTabActivity', tabDiscussion: 'btnTabDiscussion' })[tabId];
            document.getElementById(activeBtn).classList.add('border-indigo-600', 'text-indigo-600');
            document.getElementById(activeBtn).classList.remove('border-transparent');
        }

        function renderComments(serialized) {
            const list = document.getElementById('drawerCommentsList'); list.innerHTML = '';
            try {
                const comments = JSON.parse(serialized || '[]');
                comments.forEach((comment) => {
                    const item = document.createElement('li'); item.className = 'rounded-xl border border-[#E2E8F0] bg-white p-3';
                    const author = document.createElement('p'); author.className = 'text-xs font-bold text-slate-900'; author.textContent = comment.author_name;
                    const body = document.createElement('p'); body.className = 'mt-2 whitespace-pre-wrap break-words text-xs text-slate-700'; body.textContent = comment.body;
                    const meta = document.createElement('p'); meta.className = 'mt-2 text-[10px] text-slate-400'; meta.textContent = new Date((comment.created_at || '').replace(' ', 'T')).toLocaleString();
                    item.append(author, body, meta); list.appendChild(item);
                });
                if (!comments.length) { const empty = document.createElement('li'); empty.className = 'rounded-xl border border-dashed border-slate-300 p-4 text-xs text-slate-500'; empty.textContent = 'No comments yet. Start the discussion.'; list.appendChild(empty); }
            } catch (error) { list.textContent = 'Comments could not be loaded.'; }
        }

        function renderClarifications(serialized) {
            const list = document.getElementById('drawerClarificationsList'); list.innerHTML = '';
            try {
                const requests = JSON.parse(serialized || '[]');
                requests.forEach((request) => {
                    const item = document.createElement('li'); item.className = 'rounded-xl border border-slate-200 bg-white p-3 text-xs';
                    const question = document.createElement('p'); question.className = 'font-semibold text-slate-800'; question.textContent = `Clarification · ${request.status}: ${request.question}`; item.appendChild(question);
                    if (request.answer) { const answer = document.createElement('p'); answer.className = 'mt-2 whitespace-pre-wrap text-slate-600'; answer.textContent = `Answer: ${request.answer}`; item.appendChild(answer); }
                    @if(auth()->user()->role === 'manager' || auth()->user()->role === 'tester')
                    if (request.status === 'open') {
                        const form = document.createElement('form'); form.method = 'POST'; form.action = @json(route('bugs.clarifications.answer', [], false)); form.className = 'mt-2 space-y-2';
                        form.innerHTML = @json('<input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="request_id" value="">');
                        form.querySelector('[name=request_id]').value = request.id;
                        const answerInput = document.createElement('textarea'); answerInput.name = 'answer'; answerInput.required = true; answerInput.maxLength = 2000; answerInput.placeholder = 'Answer the developer…'; answerInput.className = 'w-full rounded-lg border border-slate-300 p-2';
                        const submit = document.createElement('button'); submit.type = 'submit'; submit.className = 'rounded-lg bg-indigo-600 px-3 py-2 text-[11px] font-bold text-white'; submit.textContent = 'Send answer';
                        form.append(answerInput, submit); form.onclick = (event) => event.stopPropagation(); item.appendChild(form);
                    }
                    @endif
                    list.appendChild(item);
                });
                if (!requests.length) { const empty = document.createElement('li'); empty.className = 'text-[11px] text-slate-500'; empty.textContent = 'No clarification requests.'; list.appendChild(empty); }
            } catch (error) { list.textContent = 'Clarification requests could not be loaded.'; }
        }

        function insertMention() {
            const select = document.getElementById('mentionMember'); if (!select.value) return;
            const option = select.options[select.selectedIndex]; const body = document.getElementById('commentBody');
            const start = body.selectionStart, end = body.selectionEnd, mention = '@' + option.textContent.split(' · ')[0] + ' ';
            body.setRangeText(mention, start, end, 'end'); body.focus();
            const inputs = document.getElementById('mentionInputs');
            if (![...inputs.querySelectorAll('input')].some(input => input.value === select.value)) {
                const input = document.createElement('input'); input.type = 'hidden'; input.name = 'mentioned_user_ids[]'; input.value = select.value; inputs.appendChild(input);
            }
            select.value = '';
        }

        function togglePatchView(mode) {
            if (mode === 'final') {
                document.getElementById('viewFinalContainer').classList.remove('hidden');
                document.getElementById('viewDiffContainer').classList.add('hidden');
                document.getElementById('btnViewFinal').className = 'px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200';
                document.getElementById('btnViewDiff').className = 'px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50';
            } else {
                document.getElementById('viewFinalContainer').classList.add('hidden');
                document.getElementById('viewDiffContainer').classList.remove('hidden');
                document.getElementById('btnViewDiff').className = 'px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200';
                document.getElementById('btnViewFinal').className = 'px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50';
            }
        }

        function copyPatchCode() {
            const code = document.getElementById('drawerFixedCode').value;
            navigator.clipboard.writeText(code);
            const btn = document.getElementById('copyPatchBtn');
            btn.innerText = '✓ Copied to Clipboard!';
            setTimeout(() => btn.innerText = '📋 Copy Submitted Code', 2000);
        }

        // Live Filters
        const filterForm = document.getElementById('issue-filters');
        const searchInput = document.querySelector('[data-live-search]');
        const clearFilters = document.getElementById('clear-filters');
        let searchTimer;

        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => filterForm.requestSubmit(), 300);
            });
        }
        filterForm.querySelectorAll('select').forEach(select => select.addEventListener('change', () => filterForm.requestSubmit()));
        if (clearFilters) {
            clearFilters.addEventListener('click', () => {
                filterForm.querySelectorAll('input, select').forEach(control => control.value = '');
                filterForm.requestSubmit();
            });
        }

        document.getElementById('loadSavedFilter').addEventListener('click', () => {
            const selected = document.getElementById('savedFilterSelect').selectedOptions[0];
            if (!selected || !selected.value) return;
            let saved = {};
            try { saved = JSON.parse(selected.dataset.filters || '{}'); } catch (error) {}
            filterForm.querySelectorAll('input[name], select[name]').forEach(control => control.value = saved[control.name] || '');
            filterForm.requestSubmit();
        });
        document.getElementById('saveCurrentFilter').addEventListener('click', () => {
            const name = prompt('Name this saved filter:');
            if (!name || !name.trim()) return;
            const payload = document.getElementById('savedFilterPayload'); payload.replaceChildren();
            filterForm.querySelectorAll('input[name], select[name]').forEach(control => {
                if (!control.value) return;
                const input = document.createElement('input'); input.type = 'hidden'; input.name = `filters[${control.name}]`; input.value = control.value; payload.appendChild(input);
            });
            document.getElementById('savedFilterName').value = name.trim();
            document.getElementById('saveFilterForm').requestSubmit();
        });
        document.getElementById('deleteSavedFilter').addEventListener('click', () => {
            const id = document.getElementById('savedFilterSelect').value;
            if (!id || !confirm('Delete this saved filter?')) return;
            const form = document.getElementById('deleteFilterForm'); form.action = `/api/bug-filters/${encodeURIComponent(id)}`; form.requestSubmit();
        });

        // Quick Toggles
        let onlyMyIssuesActive = false;
        function toggleMyIssues(userName) {
            onlyMyIssuesActive = !onlyMyIssuesActive;
            const btn = document.getElementById('myIssuesToggle');
            btn.classList.toggle('bg-indigo-600', onlyMyIssuesActive);
            btn.classList.toggle('text-white', onlyMyIssuesActive);

            document.querySelectorAll('.bug-card').forEach(card => {
                const assignee = card.getAttribute('data-assignee');
                if (onlyMyIssuesActive && assignee !== userName) {
                    card.style.display = 'none';
                } else {
                    card.style.display = 'block';
                }
            });
        }

        let hideDuplicatesActive = false;
        function toggleDuplicates() {
            hideDuplicatesActive = !hideDuplicatesActive;
            const btn = document.getElementById('duplicatesToggle');
            btn.classList.toggle('bg-indigo-600', hideDuplicatesActive);
            btn.classList.toggle('text-white', hideDuplicatesActive);

            document.querySelectorAll('.bug-card').forEach(card => {
                const isDup = card.getAttribute('data-duplicate') === 'true';
                if (hideDuplicatesActive && isDup) {
                    card.style.display = 'none';
                } else if (!onlyMyIssuesActive) {
                    card.style.display = 'block';
                }
            });
        }

        // Kanban Drag and Drop
        let draggedBugId = null;
        let draggedFromStatus = null;

        function handleDragStart(event, bugId, currentStatus) {
            draggedBugId = bugId;
            draggedFromStatus = currentStatus;
            event.dataTransfer.setData('text/plain', bugId);
            event.dataTransfer.effectAllowed = 'move';
            const card = document.getElementById(`bug-${bugId}`);
            if (card) {
                card.classList.add('opacity-40', 'scale-[0.98]', 'ring-2', 'ring-indigo-500');
            }
        }

        function handleDragEnd(event) {
            if (draggedBugId) {
                const card = document.getElementById(`bug-${draggedBugId}`);
                if (card) {
                    card.classList.remove('opacity-40', 'scale-[0.98]', 'ring-2', 'ring-indigo-500');
                }
            }
            document.querySelectorAll('.kanban-dropzone').forEach(zone => {
                zone.classList.remove('bg-indigo-50/70', 'ring-2', 'ring-indigo-400');
            });
        }

        function handleDragOver(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
            const dropzone = event.currentTarget;
            dropzone.classList.add('bg-indigo-50/70', 'ring-2', 'ring-indigo-400');
        }

        function handleDragLeave(event) {
            const dropzone = event.currentTarget;
            dropzone.classList.remove('bg-indigo-50/70', 'ring-2', 'ring-indigo-400');
        }

        async function handleDrop(event, targetStatus) {
            event.preventDefault();
            const dropzone = event.currentTarget;
            dropzone.classList.remove('bg-indigo-50/70', 'ring-2', 'ring-indigo-400');

            if (!draggedBugId || draggedFromStatus === targetStatus) return;

            const card = document.getElementById(`bug-${draggedBugId}`);
            if (!card) return;

            // Optimistically move card in DOM
            dropzone.prepend(card);
            card.setAttribute('data-status', targetStatus);

            function toSlug(s) {
                return (s || '').toLowerCase().trim().replace(/[\s\W-]+/g, '-');
            }

            const fromCountEl = document.getElementById(`col-count-${toSlug(draggedFromStatus)}`);
            const toCountEl = document.getElementById(`col-count-${toSlug(targetStatus)}`);
            if (fromCountEl) fromCountEl.textContent = Math.max(0, parseInt(fromCountEl.textContent || '0') - 1);
            if (toCountEl) toCountEl.textContent = parseInt(toCountEl.textContent || '0') + 1;

            const prevStatus = draggedFromStatus;
            draggedFromStatus = targetStatus;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                const res = await fetch('/api/bugs/override', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: draggedBugId,
                        status: targetStatus,
                        reason: `Moved via drag and drop from ${prevStatus} to ${targetStatus}`
                    })
                });
                if (!res.ok) {
                    window.location.reload();
                }
            } catch (err) {
                window.location.reload();
            }
        }
    </script>
</body>
</html>
