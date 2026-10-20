<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Command Center · AutoBugTriage AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    <!-- Fixed Left Sidebar (250px) -->
    <aside class="hidden w-[250px] shrink-0 flex-col bg-[#0F172A] text-white lg:flex border-r border-slate-800 z-20">
        <!-- Logo & AI Badge -->
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

        <!-- Navigation with Active Indicator Pill -->
        <nav class="flex-1 space-y-1 p-3 text-xs">
            <a class="relative flex items-center gap-3 rounded-xl bg-indigo-600/20 text-indigo-300 px-3.5 py-2.5 font-bold transition border border-indigo-500/30" href="/dashboard">
                <span class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-indigo-500"></span>
                <span class="text-sm">▦</span>
                <span>Command Center</span>
            </a>
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/workspace">
                <span class="text-sm">▤</span>
                <span>Kanban Workspace</span>
            </a>
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/add-developers">
                <span class="text-sm">👥</span>
                <span>Team & Skills Matrix</span>
            </a>
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800/60 transition" href="/create-project">
                <span class="text-sm">⚙</span>
                <span>New Project Wizard</span>
            </a>
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
            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                <span>Workspace</span>
                <span class="text-slate-300">/</span>
                <span class="text-[#0F172A] font-bold">{{ session('project_name', 'Project') }}</span>
                <span class="text-slate-300">/</span>
                <span class="text-indigo-600">Command Center</span>
            </div>

            <!-- Center Search with Ctrl+K shortcut -->
            <div class="hidden md:flex items-center">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">⌕</span>
                    <input type="text" placeholder="Search issues, features, team..." 
                           class="w-72 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] py-1.5 pl-8 pr-14 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <kbd class="rounded border border-[#CBD5E1] bg-white px-1.5 py-0.5 text-[10px] font-mono-code font-bold text-[#64748B]">Ctrl + K</kbd>
                    </span>
                </div>
            </div>

            <!-- Right Live Status Pill -->
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

        <!-- Command Center Body -->
        <div class="flex-1 mx-auto w-full max-w-7xl px-5 py-6 lg:px-8 space-y-6">
            <!-- Role-Aware Contextual Greeting Banner -->
            <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50/70 via-white to-blue-50/70 p-4.5 shadow-xs flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 text-white font-bold text-lg shadow-sm">
                        👋
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-[#0F172A]">
                            Good Day, {{ auth()->user()->name }}!
                            @if($stats['high'] > 0)
                                <span class="text-rose-600 font-extrabold">{{ $stats['high'] }} High-Priority</span> bugs require attention today.
                            @else
                                All project queues are healthy and under control.
                            @endif
                        </h2>
                        <p class="text-xs text-[#64748B] mt-0.5">Automated AST parsing and skill-based developer routing active across {{ $developers->count() }} profiles.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($stats['high'] > 0)
                        <a href="/workspace?priority=High" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition">
                            <span>🔥 View Critical Bugs ({{ $stats['high'] }})</span>
                        </a>
                    @endif
                    <a href="/workspace" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition">
                        <span>Open Kanban Board ➔</span>
                    </a>
                </div>
            </div>

            <!-- Empty Project State with Clear Guide Buttons (if 0 bugs in project) -->
            @if($stats['total'] === 0)
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

            <!-- Top Row: 4 Bento KPI Cards -->
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                <!-- 1. Total Issues -->
                <div class="rounded-2xl border border-[#E2E8F0] bg-white p-5 shadow-xs card-hover-lift">
                    <div class="flex items-center justify-between text-[#64748B]">
                        <p class="text-xs font-bold uppercase tracking-wider">Total Issues</p>
                        <span class="text-base">📁</span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-[#0F172A] font-mono-code">{{ $stats['total'] }}</p>
                    <p class="mt-1 text-[11px] text-[#64748B]">Across all active sprints</p>
                </div>

                <!-- 2. Open / Active Bugs -->
                <div class="rounded-2xl border border-[#E2E8F0] bg-white p-5 shadow-xs card-hover-lift">
                    <div class="flex items-center justify-between text-[#64748B]">
                        <p class="text-xs font-bold uppercase tracking-wider">Active Issues</p>
                        <span class="text-base">⚡</span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-indigo-600 font-mono-code">{{ $stats['open'] }}</p>
                    <p class="mt-1 text-[11px] font-semibold text-amber-600">Needs attention</p>
                </div>

                <!-- 3. Resolved Bugs -->
                <div class="rounded-2xl border border-[#E2E8F0] bg-white p-5 shadow-xs card-hover-lift">
                    <div class="flex items-center justify-between text-[#64748B]">
                        <p class="text-xs font-bold uppercase tracking-wider">Resolved</p>
                        <span class="text-base text-emerald-600">✓</span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-emerald-600 font-mono-code">{{ $stats['resolved'] }}</p>
                    @php
                        $rate = $stats['total'] > 0 ? round(($stats['resolved'] / $stats['total']) * 100) : 0;
                    @endphp
                    <p class="mt-1 text-[11px] text-emerald-700 font-semibold font-mono-code">Resolution Rate: {{ $rate }}%</p>
                </div>

                <!-- 4. Critical / High Priority -->
                <div class="rounded-2xl border-2 border-rose-200 bg-rose-50/40 p-5 shadow-xs card-hover-lift relative overflow-hidden">
                    <div class="flex items-center justify-between text-rose-800">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-rose-600 animate-ping"></span>
                            <p class="text-xs font-bold uppercase tracking-wider">Critical / High</p>
                        </div>
                        <span class="text-base">🔥</span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-rose-700 font-mono-code">{{ $stats['high'] }}</p>
                    <p class="mt-1 text-[11px] text-rose-800 font-semibold">Immediate attention needed</p>
                </div>
            </div>

            <!-- Middle Row: AI Efficiency Banner Card -->
            <div class="rounded-2xl border border-indigo-200 bg-gradient-to-r from-indigo-50/80 via-purple-50/60 to-blue-50/80 p-5 shadow-xs flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 text-white text-xl shadow-md shadow-indigo-600/30">
                        🤖
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-indigo-950">AI Triage Efficiency Summary</h3>
                        <p class="text-xs text-indigo-800/80 mt-0.5">Automated AST parsing and developer skill routing operating with zero human bottleneck.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 text-xs font-mono-code font-bold">
                    <span class="rounded-xl bg-white/80 border border-indigo-200/80 px-3 py-1.5 text-indigo-700">
                        ⚡ 100% Skill Matching
                    </span>
                    <span class="rounded-xl bg-white/80 border border-indigo-200/80 px-3 py-1.5 text-emerald-700">
                        ✓ Zero Triage Latency
                    </span>
                </div>
            </div>

            <!-- Bottom Row: 60% / 40% Split View -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left 60% (col-span-7): Recent Issues Activity Table -->
                <section class="lg:col-span-7 rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-4 mb-4">
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold text-[#0F172A]">Recent Issues Activity Feed</h2>
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
                                            #ABT-{{ $bug->id }}
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

                <!-- Right 40% (col-span-5): Team Workload & Bottleneck Widget -->
                <section class="lg:col-span-5 rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs">
                    <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-4 mb-4">
                        <h2 class="text-sm font-bold text-[#0F172A]">Team Workload & Capacity</h2>
                        <a href="/add-developers" class="text-xs font-bold text-indigo-600 hover:underline">
                            Manage Team
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($developers as $developer)
                            @php
                                $tasks = (int) $developer->active_tasks;
                                $color = $tasks <= 2 ? 'bg-emerald-500' : ($tasks <= 4 ? 'bg-amber-500' : 'bg-rose-500');
                                $pill = $tasks <= 2 ? 'Available' : ($tasks <= 4 ? 'Moderate' : 'Heavy Load');
                            @endphp
                            <div class="rounded-2xl border border-[#E2E8F0] p-3.5 bg-[#F8FAFC]">
                                <div class="flex items-center justify-between text-xs mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-600/15 text-[11px] font-bold text-indigo-700 font-mono-code">
                                            {{ strtoupper(substr($developer->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-[#0F172A]">{{ $developer->name }}</span>
                                    </div>
                                    <span class="font-mono-code text-[11px] font-bold text-[#64748B]">
                                        {{ $tasks }} Active ({{ $pill }})
                                    </span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-[#E2E8F0] overflow-hidden">
                                    <div class="h-2 rounded-full {{ $color }} transition-all duration-500" 
                                         style="width: {{ min(100, max(8, $tasks * 20)) }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic py-6 text-center">No developers registered in this project.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </main>
</div>
</body>
</html>
