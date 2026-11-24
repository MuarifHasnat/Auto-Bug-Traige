<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ session('project_name', 'Workspace') }} · Kanban Board</title>
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
        @include('components.app-sidebar', ['active' => 'workspace'])

        <!-- Main Content Area -->
        <main class="min-w-0 flex-1 flex flex-col">
            <!-- Sticky Header (60px) -->
            <header class="sticky top-0 z-10 flex min-h-[76px] items-center justify-between gap-4 border-b border-[#E2E8F0] bg-white/90 px-5 py-3 backdrop-blur-md lg:px-8">
                <div class="min-w-0 truncate text-xs font-semibold text-slate-500"><a href="/dashboard" class="hover:text-indigo-700">Workspace</a><span class="mx-2 text-slate-300">/</span><span>{{ $currentProject?->name ?? session('project_name', 'Project') }}</span><span class="mx-2 text-slate-300">/</span><span class="text-indigo-700">Kanban Workspace</span></div>
                <div class="ml-auto flex shrink-0 items-center gap-2.5">
                    @if(auth()->user()->role !== 'tester')
                        @include('components.project-switcher', ['variant' => 'header'])
                    @else
                        <span class="hidden rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 sm:inline-flex">● AI Agent Ready</span>
                    @endif
                    @if(auth()->user()->role !== 'tester')<a href="/notifications" aria-label="Notifications" title="Notifications" class="relative flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50"><svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg> @if(($unreadNotificationCount ?? 0) > 0)<span class="absolute -right-1 -top-1 rounded-full bg-rose-500 px-1 text-[9px] font-bold text-white">{{ $unreadNotificationCount }}</span>@endif</a>@endif
                    @include('components.profile-header')
                </div>
            </header>

            <!-- Board Body Container -->
            <div class="flex-1 w-full px-5 lg:px-6 py-4 space-y-4">
                
                @php
                    $activeWorkflowStatuses = $workflowStatuses->where('is_done', false)->where('name', '!=', 'Duplicate')->pluck('name')->all();
                    $myAssignedCount = $bugs->where('assigned_to', auth()->user()->name)->whereIn('status', $activeWorkflowStatuses)->count();
                @endphp
                <section class="flex flex-wrap items-center justify-between gap-3" aria-labelledby="kanban-title">
                    <div class="flex flex-wrap items-center gap-2"><h1 id="kanban-title" class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-950">{{ auth()->user()->role === 'tester' ? 'Kanban Workspace' : 'Kanban board' }}</h1>@if(auth()->user()->role === 'manager')<span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-600">View only</span>@endif</div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if(auth()->user()->role === 'tester')
                            <button type="button" onclick="toggleMyIssues('{{ auth()->user()->name }}')" id="myIssuesToggle" aria-pressed="false" class="inline-flex min-h-9 items-center rounded-xl border border-slate-300 bg-white px-3.5 text-xs font-semibold text-slate-700 transition hover:border-indigo-300 hover:bg-indigo-50 shadow-2xs"><span>Show Only Reported by Me ({{ $reportedByMeCount }})</span></button>
                        @endif
                        @if(auth()->user()->role !== 'manager')<a href="/ai-agent" class="inline-flex min-h-9 items-center gap-1.5 rounded-xl bg-indigo-700 px-4 py-1.5 text-xs font-bold text-white shadow-2xs transition hover:bg-indigo-600">＋ Report Bug</a>@endif
                    </div>
                </section>

                <!-- Compact Board Filters -->
                <section class="rounded-2xl border border-slate-200 bg-white p-2.5 sm:p-3 shadow-xs" aria-label="Board filters">
                    <form id="issue-filters" method="GET" action="/workspace" class="flex flex-wrap items-center gap-2">
                        @if(auth()->user()->role === 'manager')
                            <label class="sr-only" for="kanbanProjectFilter">Project</label>
                            <select id="kanbanProjectFilter" name="project_id" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800"><option value="">Select project</option>@foreach($projects as $project)<option value="{{ $project->id }}" @selected((int) $currentProjectId === (int) $project->id)>{{ $project->name }}</option>@endforeach</select>
                        @endif
                        <label class="sr-only" for="issue-search">Search issues and stack traces</label>
                        <div class="relative min-w-[220px] flex-1 sm:max-w-sm">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">⌕</span>
                            <input id="issue-search" data-live-search name="search" value="{{ $filters['search'] ?? '' }}" autocomplete="off" placeholder="Search issues, stack traces…" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-8 pr-3 text-sm outline-none transition focus:border-indigo-500 focus:bg-white">
                        </div>
                        <label class="sr-only" for="kanbanPriorityFilter">Priority</label>
                        <select id="kanbanPriorityFilter" name="priority" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800"><option value="">All priorities</option>@foreach(['High','Medium','Low'] as $priority)<option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>{{ $priority }}</option>@endforeach</select>
                        <label class="sr-only" for="kanbanAssigneeFilter">Assignee</label>
                        <select id="kanbanAssigneeFilter" name="assignee" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800"><option value="">All assignees</option>@foreach($availableAssignees as $assignee)<option value="{{ $assignee }}" @selected(($filters['assignee'] ?? '') === $assignee)>{{ $assignee }}</option>@endforeach</select>
                        <label class="sr-only" for="kanbanStatusFilter">Status</label>
                        <select id="kanbanStatusFilter" name="status" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800"><option value="">All statuses</option>@foreach($workflowStatuses as $workflowStatus)<option value="{{ $workflowStatus->name }}" @selected(($filters['status'] ?? '') === $workflowStatus->name)>{{ $workflowStatus->name }}</option>@endforeach</select>
                        @if(auth()->user()->role === 'manager')
                            <label class="sr-only" for="kanbanDateFrom">Created from</label><input id="kanbanDateFrom" type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" aria-label="Created from" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700">
                            <label class="sr-only" for="kanbanDateTo">Created through</label><input id="kanbanDateTo" type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" aria-label="Created through" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">Apply</button>
                        @endif
                        <label class="sr-only" for="kanbanLabelFilter">Label</label>
                        <select id="kanbanLabelFilter" name="label" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800"><option value="">All labels</option>@foreach($availableLabels as $label)<option value="{{ $label }}" @selected(($filters['label'] ?? '') === $label)>{{ $label }}</option>@endforeach</select>
                        <label class="sr-only" for="kanbanIssueTypeFilter">Issue type</label>
                        <select id="kanbanIssueTypeFilter" name="issue_type" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800"><option value="">All issue types</option>@foreach(['Bug','Task','Story','Improvement'] as $type)<option value="{{ $type }}" @selected(($filters['issue_type'] ?? '') === $type)>{{ $type }}</option>@endforeach</select>
                        <label class="sr-only" for="savedFilterSelect">Saved filters</label>
                        <select id="savedFilterSelect" class="min-w-36 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800"><option value="">Saved filters…</option>@foreach($savedFilters as $savedFilter)<option value="{{ $savedFilter->id }}" data-filters="{{ json_encode($savedFilter->filters) }}">{{ $savedFilter->name }}</option>@endforeach</select>
                        <button type="button" id="clear-filters" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Clear</button>
                        <button type="button" onclick="toggleDuplicates()" id="duplicatesToggle" aria-pressed="false" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Hide Duplicates</button>
                    </form>
                    <details class="w-full border-t border-slate-100 pt-2">
                            <summary class="w-fit cursor-pointer list-none rounded-lg px-2 py-1 text-xs font-semibold text-slate-500 hover:bg-slate-50">More filters and saved filter actions <span aria-hidden="true">⌄</span></summary>
                            <div class="mt-3 flex flex-wrap items-center gap-2.5">
                                @if(auth()->user()->role === 'manager' && $stats['high'] > 0)<a href="/workspace?priority=High" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700">🔥 Critical {{ $stats['high'] }}</a>@endif
                                <button type="button" id="loadSavedFilter" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold">Load filter</button>
                                <button type="button" id="deleteSavedFilter" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700">Delete saved</button>
                                <form id="saveFilterForm" action="{{ route('bug-filters.store', [], false) }}" method="POST" class="ml-auto">@csrf<div id="savedFilterPayload"></div><input type="hidden" name="name" id="savedFilterName"><button type="button" id="saveCurrentFilter" class="rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-bold text-white">Save current filter</button></form>
                                <form id="deleteFilterForm" method="POST" class="hidden">@csrf @method('DELETE')</form>
                            </div>
                    </details>
                </section>

                <!-- Search Empty State -->
                @if($bugs->count() === 0 && collect($filters)->filter()->isNotEmpty())
                    <div class="rounded-2xl border border-dashed border-[#CBD5E1] bg-white p-8 text-center my-4">
                        <span class="text-2xl text-slate-400">🔍</span>
                        <p class="text-sm font-bold text-[#0F172A] mt-2">No bugs found matching “{{ request('search') }}”.</p>
                        <a href="/workspace" class="inline-block mt-3 text-xs font-bold text-indigo-600 hover:underline">
                            ↺ Clear all filters
                        </a>
                    </div>
                @endif

                <!-- Workflow-driven Kanban columns -->
                <div class="jira-scroll flex md:grid gap-4 overflow-x-auto pb-4 snap-x snap-mandatory scroll-smooth touch-pan-x" style="grid-template-columns: repeat({{ $workflowStatuses->count() }}, minmax(285px, 1fr));">
                    @foreach($workflowStatuses as $workflowStatus)
                        @php
                            $columnStatus = $workflowStatus->name;
                            $colBugs = $bugs->where('status', $columnStatus);
                            $columnColors = $workflowStatus->is_done
                                ? ['dot' => 'bg-emerald-500', 'border' => 'border-t-emerald-500']
                                : ($loop->first ? ['dot' => 'bg-indigo-500', 'border' => 'border-t-indigo-500'] : ['dot' => 'bg-amber-500', 'border' => 'border-t-amber-500']);
                        @endphp
                        <section class="snap-center shrink-0 w-[290px] md:w-auto rounded-xl border border-slate-200/80 border-t-4 {{ $columnColors['border'] }} bg-slate-100/70 p-3.5 flex flex-col min-h-[500px]">
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
                            <div class="space-y-3 flex-1 overflow-y-auto max-h-[680px] pr-1 custom-scroll {{ $canMoveKanbanIssues ? 'kanban-dropzone rounded-xl transition-all duration-150 p-1' : 'rounded-xl p-1' }}"
                                 id="column-{{ Str::slug($columnStatus) }}"
                                 data-status="{{ $columnStatus }}"
                                 @if($canMoveKanbanIssues) ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event, '{{ $columnStatus }}')" @endif>
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
                                    <!-- Refactored Premium SaaS Card Anatomy (Linear Style) -->
                                    <article id="bug-{{ $bug->id }}" 
                                             class="bug-card group block card-hover-lift rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-900/5 mb-3 {{ $canMoveKanbanIssues ? 'cursor-grab active:cursor-grabbing' : 'cursor-pointer' }} {{ $priorityClass }}"
                                             data-id="{{ $bug->id }}"
                                             data-status="{{ $bug->status }}"
                                             data-assignee="{{ $bug->assigned_to }}"
                                             data-reporter-id="{{ $bug->reporter_id }}"
                                             data-duplicate="{{ $bug->is_duplicate ? 'true' : 'false' }}"
                                             @if($canMoveKanbanIssues) draggable="true" ondragstart="handleDragStart(event, {{ $bug->id }}, '{{ $bug->status }}')" ondragend="handleDragEnd(event)" @endif
                                             onclick="openDrawer({{ $bug->id }})">
                                        
                                        <!-- Header: ID & Priority (No heavy borders) -->
                                        <div class="flex items-center justify-between mb-2.5">
                                            <a href="/bugs/{{ $bug->id }}" title="Open bug page" onclick="event.stopPropagation()" class="text-xs font-medium text-slate-500 font-mono hover:text-indigo-600 transition-colors">
                                                ABT-{{ $bug->id }}
                                            </a>
                                            
                                            <!-- Subtle Priority Badge -->
                                            <span class="flex items-center gap-1.5 rounded-md bg-slate-50 px-2 py-1 text-[11px] font-medium {{ $bug->priority === 'High' ? 'text-rose-600' : ($bug->priority === 'Medium' ? 'text-amber-600' : 'text-emerald-600') }}">
                                                <span class="h-1.5 w-1.5 rounded-full {{ $bug->priority === 'High' ? 'bg-rose-500' : ($bug->priority === 'Medium' ? 'bg-amber-500' : 'bg-emerald-500') }}"></span> 
                                                {{ $bug->priority }}
                                            </span>
                                        </div>

                                        <!-- Title & Snippet -->
                                        <h4 class="text-sm font-semibold text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors">
                                            {{ $bug->title }}
                                        </h4>
                                        <p class="mt-1.5 text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                            {{ $bug->description }}
                                        </p>

                                        <!-- Footer: Assignee & Module -->
                                        <div class="mt-4 flex items-center justify-between pt-3 border-t border-slate-100" onclick="event.stopPropagation()">
                                            <div class="flex items-center gap-2">
                                                <!-- Text-based Avatar -->
                                                <div class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-50 text-[9px] font-bold text-indigo-600 uppercase">
                                                    {{ substr($bug->assigned_to ?: 'U', 0, 1) }}
                                                </div>
                                                <span class="text-xs font-medium text-slate-600">{{ $bug->assigned_to ?: 'Unassigned' }}</span>
                                            </div>
                                            
                                            <div class="flex items-center gap-1.5">
                                                @if($hasAi)
                                                    <span title="AI code patch available in drawer" class="text-[10px] font-mono font-medium text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded cursor-help">&lt;/&gt; Patch</span>
                                                @endif
                                                @if(!empty($bug->detected_feature ?? $feat))
                                                    <span class="text-[10px] font-medium text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded">{{ $bug->detected_feature ?? $feat }}</span>
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
                                    <!-- Empty Column Illustration (SaaS Zero-Data View) -->
                                    <div class="flex min-h-40 flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 bg-white/70 px-4 py-8 text-center">
                                        <span class="text-2xl text-slate-300" aria-hidden="true">✓</span>
                                        <p class="mt-2 text-xs font-medium text-slate-500">No issues in this stage</p>
                                    </div>
                                @endforelse
                            </div>
                        </section>
                    @endforeach
                </div>
                <!-- Interactive Quick-Start & Feature Tips Banner (Collapsible) -->
                <div id="quickTipsBanner" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs">
                    <div class="flex items-center justify-between cursor-pointer select-none" role="button" tabindex="0" onclick="toggleQuickTips()" onkeydown="if(event.key === 'Enter' || event.key === ' '){event.preventDefault();toggleQuickTips()}">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-7 w-7 items-center justify-center rounded-xl bg-amber-500/15 text-amber-700 font-bold text-sm">💡</span>
                            <div>
                                <h3 class="text-xs font-extrabold text-[#0F172A]">Workspace Quick Guide</h3>
                                <p class="text-[11px] text-[#64748B]">A quick overview of the board’s main features.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="quickTipsToggleText" class="text-[11px] font-bold text-indigo-600">Show Guide ▾</span>
                        </div>
                    </div>

                    <div id="quickTipsContent" class="hidden mt-3.5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-3 border-t border-slate-100">
                        @if($canMoveKanbanIssues)<div class="rounded-xl bg-[#F8FAFC] border border-slate-200/80 p-3"><div class="flex items-center gap-2 text-xs font-bold text-slate-900"><span class="text-indigo-600">🖱️</span><span>1. Drag and drop</span></div><p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Drag a bug card to another column to update its status.</p></div>@endif

                        <div class="rounded-xl bg-[#F8FAFC] border border-slate-200/80 p-3">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                                <span class="text-purple-600">🔍</span>
                                <span>2. Click to preview</span>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Click a card to open its side panel with AI diagnostics, root cause, and comments.</p>
                        </div>

                        <div class="rounded-xl bg-[#F8FAFC] border border-slate-200/80 p-3">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                                <span class="text-sky-600">📄</span>
                                <span>3. Full issue page and GitHub</span>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Use “Open in Full Page” to review issue details and linked GitHub files.</p>
                        </div>

                        <div class="rounded-xl bg-[#F8FAFC] border border-slate-200/80 p-3">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                                <span class="text-emerald-600">⚡</span>
                                <span>4. Submit a bug</span>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">Select “+ Report Bug” to open AI Triage Studio and get an engineer assignment recommendation.</p>
                        </div>
                    </div>
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

        <!-- Bug detail tabs (3 Clean Linear-Style Tabs) -->
        <div class="flex border-b border-[#E2E8F0] bg-[#F8FAFC] text-xs font-bold text-[#64748B] px-4 pt-2 gap-2">
            <button type="button" onclick="switchDrawerTab('tabAI')" id="btnTabAI" class="drawer-tab-btn py-2.5 px-3 border-b-2 border-indigo-600 text-indigo-600 transition flex items-center gap-1.5 font-bold">
                <span>🤖 AI Diagnosis & Code</span>
            </button>
            <button type="button" onclick="switchDrawerTab('tabDiscussion')" id="btnTabDiscussion" class="drawer-tab-btn py-2.5 px-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition flex items-center gap-1.5 font-semibold">
                <span>💬 Discussion</span>
            </button>
            <button type="button" onclick="switchDrawerTab('tabActivity')" id="btnTabActivity" class="drawer-tab-btn py-2.5 px-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition flex items-center gap-1.5 font-semibold">
                <span>🕒 Activity Log</span>
            </button>
        </div>

        <!-- Drawer Body Tabs Content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scroll">
            <!-- Tab 1: AI Diagnosis, Details & Code -->
            <div id="tabAI" class="space-y-4">
                <div class="flex flex-wrap items-center gap-2">
                    <a id="drawerFullPageLink" href="#" class="inline-flex items-center gap-1 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white px-3.5 py-2 text-xs font-bold shadow-2xs transition">
                        <span>Open Full Issue Page</span> ↗
                    </a>
                    <a id="drawerReportLink" href="#" target="_blank" class="inline-flex rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition">Print PDF</a>
                </div>

                <div>
                    <h3 id="drawerTitle" class="text-base sm:text-lg font-extrabold text-[#0F172A] leading-snug"></h3>
                    <p id="drawerDesc" class="mt-2 text-xs text-[#475569] leading-relaxed whitespace-pre-line bg-[#F8FAFC] p-3 rounded-xl border border-slate-200/80"></p>
                </div>

                <div class="rounded-xl border border-[#E2E8F0] bg-white p-3.5 text-xs space-y-2 shadow-2xs">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Feature Module:</span>
                        <span id="drawerFeature" class="font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 font-medium">Assigned Developer:</span>
                        <span id="drawerAssignee" class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200"></span>
                    </div>
                </div>

                <!-- Validation Result -->
                <div class="rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-xs text-rose-800">
                    <p class="font-bold">Code Validation Result:</p>
                    <p id="drawerSyntaxStatus" class="mt-1 font-bold"></p>
                    <p id="drawerError" class="mt-1 font-mono-code"></p>
                    <p id="drawerSyntaxChecker" class="mt-1"></p>
                    <p id="drawerScope" class="mt-2"></p>
                </div>

                <!-- Suspected Culprit Files -->
                <div id="drawerSuspectedFilesSection" class="hidden rounded-xl border border-sky-200 bg-sky-50/70 p-3.5 text-xs text-sky-950">
                    <div class="flex items-center gap-1.5 font-bold text-sky-900">
                        <span>🔍</span>
                        <span>Suspected Culprit Files (GitHub Root Cause)</span>
                    </div>
                    <p class="mt-1 text-[11px] text-sky-700">Agent matched repository files to isolate culprit files:</p>
                    <div id="drawerSuspectedFilesList" class="mt-2 space-y-1.5"></div>
                </div>

                <!-- Explainable AI (XAI) Assignment Rationale in Drawer -->
                <div class="rounded-xl border border-indigo-100 bg-indigo-50/70 p-3.5 text-xs text-indigo-950 space-y-1">
                    <div class="flex items-center gap-1.5 font-bold text-indigo-900">
                        <span>🧠</span>
                        <span>Explainable AI (XAI) Routing Decision:</span>
                    </div>
                    <p id="drawerReason" class="text-[11px] leading-relaxed text-indigo-900/90"></p>
                </div>

                <!-- Fix Suggestions -->
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-3.5 text-xs text-amber-950">
                    <p class="font-bold">Fix Suggestions & Recommendations:</p>
                    <ol id="drawerFixSuggestions" class="mt-2 space-y-2"></ol>
                </div>

                <!-- Steps Checklist -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-2">Step-by-Step Fix Checklist:</h4>
                    <ul id="drawerStepsList" class="space-y-2 text-xs text-[#0F172A]"></ul>
                </div>

                <!-- Code Reference -->
                <div class="rounded-xl border border-slate-200 bg-white p-3.5 space-y-2 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Submitted Snippet</span>
                        <button type="button" onclick="copyPatchCode()" id="copyPatchBtn" 
                                class="rounded-lg border border-slate-300 bg-white px-2.5 py-1 text-xs font-bold text-indigo-600 hover:bg-slate-50 transition">
                            📋 Copy Code
                        </button>
                    </div>
                    <textarea id="drawerFixedCode" readonly spellcheck="false" 
                              class="w-full min-h-[160px] rounded-xl bg-slate-950 p-3.5 font-mono-code text-xs leading-5 text-emerald-400 border border-slate-800 focus:outline-none"></textarea>
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
                    @if(auth()->user()->role !== 'manager')<form action="{{ route('bugs.watch', [], false) }}" method="POST" onclick="event.stopPropagation()">
                        @csrf<input type="hidden" name="bug_id" id="watchBugId">
                        <button type="submit" id="watchBugButton" class="rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-2 text-[11px] font-bold text-indigo-700">Watch</button>
                    </form>@endif
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
                @if(auth()->user()->role !== 'manager')<form action="{{ route('bugs.comments', [], false) }}" method="POST" id="bugCommentForm" class="space-y-2 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] p-3" onclick="event.stopPropagation()">
                    @csrf<input type="hidden" name="bug_id" id="commentBugId">
                    <div id="mentionInputs"></div>
                    <div class="flex gap-2"><select id="mentionMember" class="min-w-0 flex-1 rounded-lg border border-slate-300 bg-white px-2 py-2 text-xs"><option value="">Mention teammate…</option></select><button type="button" onclick="insertMention()" class="rounded-lg border border-slate-300 bg-white px-2.5 text-xs font-semibold">@ Mention</button></div>
                    <textarea name="body" id="commentBody" required maxlength="5000" rows="3" placeholder="Write a comment…" class="w-full rounded-lg border border-slate-300 bg-white p-2.5 text-xs"></textarea>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white">Post comment</button>
                </form>@endif
            </div>
        </div>
    </div>

    <script>
        window.liveProjectId = @json($currentProjectId);
        window.projectGithubRepo = @json($projectGithubRepo ?? '');
        function openDrawer(bugId) {
            const container = document.getElementById('drawer-data-' + bugId);
            if (!container) return;
            const data = container.firstElementChild;
            const bugIdValue = data.getAttribute('data-id');
            const watchBugId = document.getElementById('watchBugId');
            const commentBugId = document.getElementById('commentBugId');
            if (watchBugId) watchBugId.value = bugIdValue;
            if (commentBugId) commentBugId.value = bugIdValue;
            const clarificationBugId = document.getElementById('clarificationBugId');
            if (clarificationBugId) clarificationBugId.value = bugIdValue;
            const watchButton = document.getElementById('watchBugButton');
            if (watchButton) watchButton.textContent = data.getAttribute('data-watching') === 'true' ? 'Unwatch' : 'Watch';
            const memberSelect = document.getElementById('mentionMember');
            if (memberSelect) {
                memberSelect.innerHTML = '<option value="">Mention teammate…</option>';
                try {
                    JSON.parse(data.getAttribute('data-project-users') || '[]').forEach((member) => {
                        if (String(member.id) === String({{ auth()->id() }})) return;
                        const option = document.createElement('option'); option.value = member.id; option.textContent = `${member.name} · ${member.role}`; memberSelect.appendChild(option);
                    });
                } catch (error) {}
            }
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
                    const defaultSuggestions = [
                        { title: '1. Inspect Repository Files', detail: 'Open culprit files on GitHub to inspect event listeners, templates, and route controllers.' },
                        { title: '2. Check Logs & Network Calls', detail: 'Verify browser console network errors and backend exception logs to isolate failure traces.' }
                    ];
                    defaultSuggestions.forEach(s => {
                        const item = document.createElement('li');
                        item.className = 'rounded-lg border border-amber-200 bg-white/80 p-2.5';
                        item.innerHTML = `<p class="font-bold">${s.title}</p><p class="mt-1">${s.detail}</p>`;
                        suggestionsList.appendChild(item);
                    });
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
            const rEl = document.getElementById('drawerReason');
            if (rEl) rEl.innerText = data.getAttribute('data-reason') || 'Matched developer tech stack skills & backlog workload.';

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
                        let currentRepo = window.projectGithubRepo || 'MuarifHasnat/Auto-Bug-Traige';
                        let repoSlug = currentRepo.replace(/^https?:\/\/github\.com\//, '').replace(/\.git$/, '').replace(/\/$/, '') || 'MuarifHasnat/Auto-Bug-Traige';
                        let ghUrl = (f && f.github_url) ? f.github_url : null;
                        if (!ghUrl && filePath) {
                            let effBranch = (f && f.branch) ? f.branch : 'HEAD';
                            let cleanPath = filePath.replace(/\\/g, '/').replace(/^\/+/, '');
                            let encodedPath = cleanPath.split('/').map(seg => encodeURIComponent(seg)).join('/');
                            ghUrl = `https://github.com/${repoSlug}/blob/${encodeURIComponent(effBranch)}/${encodedPath}`;
                        }
                        if (ghUrl) {
                            const link = document.createElement('a');
                            link.href = ghUrl;
                            link.target = '_blank';
                            link.rel = 'noreferrer noopener';
                            link.className = 'inline-flex items-center gap-1 rounded bg-sky-100 hover:bg-sky-200 text-sky-800 text-[10px] font-bold px-2 py-0.5 shrink-0 transition';
                            link.innerHTML = `<span>Open in GitHub</span> ↗`;
                            item.appendChild(link);
                        }
                        suspectedList.appendChild(item);
                    });
                    suspectedSection.classList.remove('hidden');
                } else {
                    const currentRepo = window.projectGithubRepo || 'MuarifHasnat/Auto-Bug-Traige';
                    const repoSlug = currentRepo.replace(/^https?:\/\/github\.com\//, '').replace(/\.git$/, '').replace(/\/$/, '') || 'MuarifHasnat/Auto-Bug-Traige';
                    const item = document.createElement('div');
                    item.className = 'flex items-center justify-between gap-2 rounded-lg bg-white border border-sky-200 px-2.5 py-1.5 text-xs';
                    item.innerHTML = `<span class="text-slate-600 text-[11px]">Inspect project repository components:</span>
                        <a href="https://github.com/${repoSlug}" target="_blank" rel="noreferrer noopener" class="inline-flex items-center gap-1 rounded bg-sky-100 hover:bg-sky-200 text-sky-800 text-[10px] font-bold px-2 py-0.5 shrink-0 transition">
                            <span>Open in GitHub</span> ↗
                        </a>`;
                    suspectedList.appendChild(item);
                    suspectedSection.classList.remove('hidden');
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
            ['tabAI', 'tabActivity', 'tabDiscussion'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            });
            ['btnTabAI', 'btnTabActivity', 'btnTabDiscussion'].forEach(id => {
                const btn = document.getElementById(id);
                if (btn) {
                    btn.classList.remove('border-indigo-600', 'text-indigo-600');
                    btn.classList.add('border-transparent', 'text-slate-500');
                }
            });

            const activeEl = document.getElementById(tabId);
            if (activeEl) activeEl.classList.remove('hidden');
            const activeBtnId = ({ tabAI: 'btnTabAI', tabActivity: 'btnTabActivity', tabDiscussion: 'btnTabDiscussion' })[tabId] || 'btnTabAI';
            const activeBtn = document.getElementById(activeBtnId);
            if (activeBtn) {
                activeBtn.classList.add('border-indigo-600', 'text-indigo-600');
                activeBtn.classList.remove('border-transparent', 'text-slate-500');
            }
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
                    @if(auth()->user()->role === 'tester')
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
        document.querySelectorAll('[form="issue-filters"]').forEach(control => control.addEventListener('change', () => filterForm.requestSubmit()));
        if (clearFilters) {
            clearFilters.addEventListener('click', () => {
                document.querySelectorAll('#issue-filters input, #issue-filters select, [form="issue-filters"]').forEach(control => control.value = '');
                filterForm.requestSubmit();
            });
        }

        document.getElementById('loadSavedFilter').addEventListener('click', () => {
            const selected = document.getElementById('savedFilterSelect').selectedOptions[0];
            if (!selected || !selected.value) return;
            let saved = {};
            try { saved = JSON.parse(selected.dataset.filters || '{}'); } catch (error) {}
            document.querySelectorAll('#issue-filters [name], [form="issue-filters"][name]').forEach(control => control.value = saved[control.name] || '');
            filterForm.requestSubmit();
        });
        document.getElementById('saveCurrentFilter').addEventListener('click', () => {
            const name = prompt('Name this saved filter:');
            if (!name || !name.trim()) return;
            const payload = document.getElementById('savedFilterPayload'); payload.replaceChildren();
            document.querySelectorAll('#issue-filters [name], [form="issue-filters"][name]').forEach(control => {
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
        let hideDuplicatesActive = false;
        const myIssueFilterUsesReporter = @json(auth()->user()->role === 'tester');
        const myIssueFilterLabel = @json(auth()->user()->role === 'tester' ? 'Reported by Me' : 'Assigned to Me');
        const myIssueFilterCount = @json(auth()->user()->role === 'tester' ? $reportedByMeCount : $assignedToMeCount);
        function applyQuickIssueFilters() {
            document.querySelectorAll('.bug-card').forEach(card => {
                const assignee = card.getAttribute('data-assignee');
                const reporterId = card.getAttribute('data-reporter-id');
                const isDuplicate = card.getAttribute('data-duplicate') === 'true';
                const isMine = myIssueFilterUsesReporter
                    ? reporterId === @json((string) auth()->id())
                    : assignee === @json(auth()->user()->name);
                card.style.display = (onlyMyIssuesActive && !isMine) || (hideDuplicatesActive && isDuplicate) ? 'none' : '';
            });
        }

        function toggleMyIssues(userName) {
            onlyMyIssuesActive = !onlyMyIssuesActive;
            const btn = document.getElementById('myIssuesToggle');
            btn.classList.toggle('bg-indigo-600', onlyMyIssuesActive);
            btn.classList.toggle('text-white', onlyMyIssuesActive);
            btn.setAttribute('aria-pressed', String(onlyMyIssuesActive));
            btn.querySelector('span').textContent = `${onlyMyIssuesActive ? 'Showing Only ' : 'Show Only '}${myIssueFilterLabel} (${myIssueFilterCount})`;
            applyQuickIssueFilters();
        }

        function toggleDuplicates() {
            hideDuplicatesActive = !hideDuplicatesActive;
            const btn = document.getElementById('duplicatesToggle');
            btn.classList.toggle('bg-indigo-600', hideDuplicatesActive);
            btn.classList.toggle('text-white', hideDuplicatesActive);
            btn.setAttribute('aria-pressed', String(hideDuplicatesActive));
            btn.textContent = hideDuplicatesActive ? '🚫 Duplicates Hidden' : '🚫 Hide Duplicates';
            applyQuickIssueFilters();
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

        function showToast(message) {
            let toast = document.getElementById('abtToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'abtToast';
                toast.className = 'fixed bottom-5 right-5 z-50 rounded-xl bg-slate-900 text-white px-4 py-3 text-xs font-semibold shadow-2xl border border-slate-700 flex items-center gap-2 toast-enter';
                document.body.appendChild(toast);
            }
            toast.innerHTML = message;
            toast.style.display = 'flex';
            clearTimeout(window.__toastTimer);
            window.__toastTimer = setTimeout(() => {
                toast.style.display = 'none';
            }, 3200);
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
            showToast(`✅ <b>#ABT-${draggedBugId}</b> moved to <span class="text-indigo-300">${targetStatus}</span>`);

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

        function toggleQuickTips() {
            const content = document.getElementById('quickTipsContent');
            const text = document.getElementById('quickTipsToggleText');
            if (!content || !text) return;
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                text.textContent = 'Hide Guide ▴';
                localStorage.setItem('abt_show_guide', '1');
            } else {
                content.classList.add('hidden');
                text.textContent = 'Show Guide ▾';
                localStorage.setItem('abt_show_guide', '0');
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const guideVisible = localStorage.getItem('abt_show_guide') === '1';
            const guideContent = document.getElementById('quickTipsContent');
            const guideText = document.getElementById('quickTipsToggleText');
            if (guideContent && guideText) {
                guideContent.classList.toggle('hidden', !guideVisible);
                guideText.textContent = guideVisible ? 'Hide Guide ▴' : 'Show Guide ▾';
            }

            const hash = window.location.hash;
            if (hash && hash.startsWith('#bug-')) {
                const bugId = hash.replace('#bug-', '');
                if (bugId) {
                    openDrawer(bugId);
                    const card = document.getElementById('bug-' + bugId);
                    if (card) {
                        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
          if (window.Echo && window.liveProjectId) {
            window.Echo.private(`project.${window.liveProjectId}`).listen('.bug.updated', (update) => {
                let toast = document.getElementById('liveBugUpdateToast');
                if (!toast) {
                    toast = document.createElement('div');
                    toast.id = 'liveBugUpdateToast';
                    toast.className = 'fixed bottom-5 right-5 z-[100] max-w-sm rounded-xl border border-indigo-200 bg-white p-4 text-sm shadow-xl';
                    document.body.appendChild(toast);
                }
                const message = document.createElement('p');
                message.className = 'font-semibold text-slate-800';
                message.textContent = `Issue #ABT-${update.bug_id} updated: ${update.summary}`;
                const refresh = document.createElement('button');
                refresh.type = 'button'; refresh.className = 'mt-2 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white';
                refresh.textContent = 'Refresh workspace'; refresh.onclick = () => window.location.reload();
                toast.replaceChildren(message, refresh);
            });
          }
        });
    </script>
</body>
</html>
