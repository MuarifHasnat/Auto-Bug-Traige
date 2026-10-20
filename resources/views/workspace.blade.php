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

                @if(auth()->user()->role === 'developer')
                    <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/ai-agent">
                        <span class="text-sm">⚡</span>
                        <span>AI Fix Studio</span>
                    </a>
                @endif

                @if(auth()->user()->role === 'manager')
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
                    $myAssignedCount = $bugs->where('assigned_to', auth()->user()->name)->whereIn('status', ['Open', 'In Progress'])->count();
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
                <div class="rounded-2xl border border-[#E2E8F0] bg-white p-4 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                    <form id="issue-filters" method="GET" action="/workspace" class="flex flex-wrap items-center gap-2.5">
                        <!-- Real-time Search Box -->
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">⌕</span>
                            <input id="issue-search" data-live-search name="search" value="{{ request('search') }}" autocomplete="off" placeholder="Search issues, stack traces..." 
                                   class="w-56 sm:w-64 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] py-2 pl-8 pr-3 text-xs outline-none transition focus:border-indigo-600 focus:bg-white">
                        </div>

                        <!-- Priority Dropdown -->
                        <select name="priority" class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-semibold text-[#0F172A] outline-none transition focus:border-indigo-600">
                            <option value="">All Priorities</option>
                            @foreach(['High','Medium','Low'] as $priority)
                                <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }} Priority</option>
                            @endforeach
                        </select>

                        <button type="button" id="clear-filters" class="rounded-xl border border-[#E2E8F0] bg-white px-3 py-2 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition">
                            Clear
                        </button>
                    </form>

                    <!-- Quick Toggles -->
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="toggleDuplicates()" id="duplicatesToggle" 
                                class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-1.5 text-xs font-bold text-[#475569] hover:border-indigo-300 transition">
                            🚫 Hide Duplicates
                        </button>
                    </div>
                </div>

                <!-- Empty Project State with Clear Guide Buttons (if 0 bugs in project) -->
                @if($bugs->count() === 0 && !request('search') && !request('priority'))
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
                @if($bugs->count() === 0 && request('search'))
                    <div class="rounded-2xl border border-dashed border-[#CBD5E1] bg-white p-8 text-center my-4">
                        <span class="text-2xl text-slate-400">🔍</span>
                        <p class="text-sm font-bold text-[#0F172A] mt-2">"{{ request('search') }}" নামে কোনো বাগ পাওয়া যায়নি।</p>
                        <a href="/workspace" class="inline-block mt-3 text-xs font-bold text-indigo-600 hover:underline">
                            ↺ সব ফিল্টার মুছে ফেলুন (Clear Filters)
                        </a>
                    </div>
                @endif

                <!-- 4 Kanban Columns -->
                @php
                    $columns = [
                        'Open' => ['label' => 'To Do', 'dot' => 'bg-indigo-500', 'border' => 'border-t-indigo-500'],
                        'In Progress' => ['label' => 'In Progress', 'dot' => 'bg-amber-500', 'border' => 'border-t-amber-500'],
                        'Resolved' => ['label' => 'Resolved', 'dot' => 'bg-emerald-500', 'border' => 'border-t-emerald-500'],
                        'Closed' => ['label' => 'Duplicate / Closed', 'dot' => 'bg-slate-500', 'border' => 'border-t-slate-500'],
                    ];
                @endphp

                <div class="jira-scroll grid grid-cols-1 gap-4 overflow-x-auto pb-4 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($columns as $columnStatus => $col)
                        @php
                            if ($columnStatus === 'Open') {
                                $colBugs = $bugs->where('status', 'Open');
                            } elseif ($columnStatus === 'Closed') {
                                $colBugs = $bugs->whereIn('status', ['Closed', 'Duplicate']);
                            } else {
                                $colBugs = $bugs->where('status', $columnStatus);
                            }
                        @endphp
                        <section class="rounded-2xl border border-[#E2E8F0] border-t-4 {{ $col['border'] }} bg-[#F1F5F9]/80 p-3.5 flex flex-col min-h-[500px]">
                            <!-- Column Header -->
                            <div class="mb-3 flex items-center justify-between px-1">
                                <div class="flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 rounded-full {{ $col['dot'] }}"></span>
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#475569]">{{ $col['label'] }}</h3>
                                </div>
                                <span class="rounded-full bg-white border border-[#CBD5E1] px-2 py-0.5 text-xs font-mono-code font-bold text-slate-700 shadow-xs">
                                    {{ $colBugs->count() }}
                                </span>
                            </div>

                            <!-- Column Cards Container with Independent Scroll -->
                            <div class="space-y-3 flex-1 overflow-y-auto max-h-[680px] pr-1 custom-scroll" id="column-{{ $columnStatus }}">
                                @forelse($colBugs as $bug)
                                    @php
                                        $triage = json_decode($bug->triage_result ?? '', true);
                                        $hasAi = !empty($triage['code_help']);
                                        $feat = $triage['code_help']['feature'] ?? ($bug->detected_feature ?? 'Module');
                                        
                                        // Priority Strip Class
                                        $priorityClass = $bug->priority === 'High' ? 'priority-strip-high' : ($bug->priority === 'Low' ? 'priority-strip-low' : 'priority-strip-medium');
                                        $prioritySymbol = $bug->priority === 'High' ? '🔥 ▲ High' : ($bug->priority === 'Low' ? '▼ Low' : '● Medium');
                                    @endphp
                                    <!-- 4-Layer Card Anatomy with 4px Left Accent Strip & Tooltips -->
                                    <article class="bug-card rounded-2xl border border-[#E2E8F0] {{ $priorityClass }} bg-white p-4 shadow-xs card-hover-lift cursor-pointer transition"
                                             data-assignee="{{ $bug->assigned_to }}"
                                             data-duplicate="{{ $bug->is_duplicate ? 'true' : 'false' }}"
                                             onclick="openDrawer({{ $bug->id }})">
                                        
                                        <!-- Layer 1: Top Meta (#ABT-X + Priority with Symbol) -->
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-mono-code font-bold text-xs text-indigo-600 bg-indigo-50 border border-indigo-100 rounded px-1.5 py-0.5">
                                                #ABT-{{ $bug->id }}
                                            </span>
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
                                                @if($bug->status === 'Open')
                                                    <form action="/api/bugs/override" method="POST" class="inline-block">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $bug->id }}">
                                                        <input type="hidden" name="status" value="In Progress">
                                                        <button type="submit" title="Advance to In Progress" class="rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white px-2 py-0.5 text-[10px] font-bold transition">
                                                            ▶ Start Work
                                                        </button>
                                                    </form>
                                                @elseif($bug->status === 'In Progress')
                                                    <form action="/api/bugs/override" method="POST" class="inline-block">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $bug->id }}">
                                                        <input type="hidden" name="status" value="Resolved">
                                                        <button type="submit" title="Mark as Resolved" class="rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white px-2 py-0.5 text-[10px] font-bold transition">
                                                            ✓ Mark Resolved
                                                        </button>
                                                    </form>
                                                @else
                                                    <!-- Mini Status Dropdown for Done/Closed -->
                                                    <form action="/api/bugs/override" method="POST" class="inline-block">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $bug->id }}">
                                                        <select name="status" onchange="this.form.submit()" 
                                                                class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-[#0F172A] outline-none hover:bg-white transition">
                                                            <option value="Open" @selected($bug->status === 'Open')>To Do</option>
                                                            <option value="In Progress" @selected($bug->status === 'In Progress')>In Prog</option>
                                                            <option value="Resolved" @selected($bug->status === 'Resolved')>Done</option>
                                                            <option value="Closed" @selected($bug->status === 'Closed')>Closed</option>
                                                        </select>
                                                    </form>
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
                                             data-status="{{ $bug->status }}"
                                             data-assignee="{{ $bug->assigned_to ?: 'Unassigned' }}"
                                             data-feat="{{ $feat }}"
                                             data-error="{{ $triage['code_help']['error_name'] ?? 'Analysis complete' }}"
                                             data-steps="{{ json_encode($triage['code_help']['steps'] ?? []) }}"
                                             data-fixed="{{ $triage['code_help']['fixed_code'] ?? '' }}"
                                             data-diff="{{ $triage['code_help']['diff'] ?? '' }}"
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

        <!-- 3 Drawer Tabs -->
        <div class="flex border-b border-[#E2E8F0] bg-white text-xs font-bold text-[#64748B]">
            <button type="button" onclick="switchDrawerTab('tabDetails')" id="btnTabDetails" class="flex-1 py-3 border-b-2 border-indigo-600 text-indigo-600 transition text-center">
                1. Details
            </button>
            <button type="button" onclick="switchDrawerTab('tabTriage')" id="btnTabTriage" class="flex-1 py-3 border-b-2 border-transparent hover:text-[#0F172A] transition text-center">
                2. AI Triage Report
            </button>
            <button type="button" onclick="switchDrawerTab('tabPatch')" id="btnTabPatch" class="flex-1 py-3 border-b-2 border-transparent hover:text-[#0F172A] transition text-center">
                3. Suggested Code Patch
            </button>
        </div>

        <!-- Drawer Body Tabs Content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scroll">
            <!-- Tab 1: Details -->
            <div id="tabDetails" class="space-y-4">
                <div>
                    <h3 id="drawerTitle" class="text-lg font-extrabold text-[#0F172A] leading-snug"></h3>
                    <p id="drawerDesc" class="mt-2 text-xs text-[#475569] leading-relaxed whitespace-pre-line"></p>
                </div>
                <div class="rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] p-4 text-xs space-y-2">
                    <p><b>Feature Module:</b> <span id="drawerFeature" class="font-bold text-purple-700"></span></p>
                    <p><b>Assigned Developer:</b> <span id="drawerAssignee" class="font-bold text-indigo-600"></span></p>
                </div>
            </div>

            <!-- Tab 2: AI Triage Report with Interactive Checkbox Checklist -->
            <div id="tabTriage" class="hidden space-y-4">
                <div class="rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-xs text-rose-800">
                    <p class="font-bold">Diagnostic Classification:</p>
                    <p id="drawerError" class="mt-1 font-mono-code"></p>
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

            <!-- Tab 3: Suggested Code Patch with Split View & Copy Button -->
            <div id="tabPatch" class="hidden space-y-4">
                <div class="flex items-center gap-2 border-b border-[#E2E8F0] pb-2 text-xs font-bold">
                    <button type="button" onclick="togglePatchView('final')" id="btnViewFinal" class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Final Fixed Code Only
                    </button>
                    <button type="button" onclick="togglePatchView('diff')" id="btnViewDiff" class="px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50">
                        Code Diff (Unified)
                    </button>
                </div>

                <div id="viewFinalContainer">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#0F172A]">Clean Code Patch</span>
                        <button type="button" onclick="copyPatchCode()" id="copyPatchBtn" 
                                class="rounded-lg border border-[#CBD5E1] bg-white px-2.5 py-1 text-xs font-bold text-indigo-600 hover:bg-[#F1F5F9] transition">
                            📋 Copy Fixed Code
                        </button>
                    </div>
                    <textarea id="drawerFixedCode" readonly spellcheck="false" 
                              class="w-full min-h-[220px] rounded-xl bg-slate-950 p-4 font-mono-code text-xs leading-5 text-emerald-400 border border-slate-800 focus:outline-none"></textarea>
                </div>

                <div id="viewDiffContainer" class="hidden">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#0F172A] block mb-2">Code Changes (Unified Diff)</span>
                    <textarea id="drawerDiff" readonly spellcheck="false" 
                              class="w-full min-h-[180px] rounded-xl bg-slate-950 p-4 font-mono-code text-xs leading-5 text-indigo-300 border border-slate-800 focus:outline-none"></textarea>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDrawer(bugId) {
            const container = document.getElementById('drawer-data-' + bugId);
            if (!container) return;
            const data = container.firstElementChild;

            document.getElementById('drawerBugId').innerText = '#ABT-' + data.getAttribute('data-id');
            document.getElementById('drawerTitle').innerText = data.getAttribute('data-title');
            document.getElementById('drawerDesc').innerText = data.getAttribute('data-desc');
            document.getElementById('drawerFeature').innerText = data.getAttribute('data-feat');
            document.getElementById('drawerAssignee').innerText = data.getAttribute('data-assignee');
            document.getElementById('drawerError').innerText = data.getAttribute('data-error');
            document.getElementById('drawerReason').innerText = data.getAttribute('data-reason');
            document.getElementById('drawerFixedCode').value = data.getAttribute('data-fixed') || '// No fixed code provided.';
            document.getElementById('drawerDiff').value = data.getAttribute('data-diff') || '// No diff available.';

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
            ['tabDetails', 'tabTriage', 'tabPatch'].forEach(id => {
                document.getElementById(id).classList.add('hidden');
            });
            ['btnTabDetails', 'btnTabTriage', 'btnTabPatch'].forEach(id => {
                document.getElementById(id).classList.remove('border-indigo-600', 'text-indigo-600');
                document.getElementById(id).classList.add('border-transparent');
            });

            document.getElementById(tabId).classList.remove('hidden');
            const activeBtn = tabId === 'tabDetails' ? 'btnTabDetails' : (tabId === 'tabTriage' ? 'btnTabTriage' : 'btnTabPatch');
            document.getElementById(activeBtn).classList.add('border-indigo-600', 'text-indigo-600');
            document.getElementById(activeBtn).classList.remove('border-transparent');
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
            setTimeout(() => btn.innerText = '📋 Copy Fixed Code', 2000);
        }

        // Live Filters
        const filterForm = document.getElementById('issue-filters');
        const searchInput = document.querySelector('[data-live-search]');
        const priorityInput = filterForm.querySelector('[name="priority"]');
        const clearFilters = document.getElementById('clear-filters');
        let searchTimer;

        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => filterForm.requestSubmit(), 300);
            });
        }
        if (priorityInput) priorityInput.addEventListener('change', () => filterForm.requestSubmit());
        if (clearFilters) {
            clearFilters.addEventListener('click', () => {
                searchInput.value = '';
                priorityInput.value = '';
                filterForm.requestSubmit();
            });
        }

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
    </script>
</body>
</html>
