<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <title>QA Home · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    @vite('resources/js/app.js')
    <style>
        :root { color-scheme: dark; }
        body { background: #090d14; color: #e5e7eb; }
        .qa-panel { background: linear-gradient(145deg, rgba(21,27,38,.96), rgba(13,18,27,.98)); border: 1px solid #273140; box-shadow: 0 14px 40px rgba(0,0,0,.18); }
        .qa-muted { color: #9ca8ba; }
        .qa-grid { background-image: linear-gradient(rgba(90,110,140,.055) 1px, transparent 1px), linear-gradient(90deg, rgba(90,110,140,.055) 1px, transparent 1px); background-size: 28px 28px; }
        :focus-visible { outline: 2px solid #7c8cff; outline-offset: 2px; }
    </style>
</head>
<body class="min-h-screen antialiased">
<div class="flex min-h-screen">
    <aside class="hidden w-[250px] shrink-0 flex-col border-r border-slate-800 bg-[#0d121b] py-5 lg:flex">
        <a href="/dashboard" aria-label="AutoBugTriage QA home" class="mb-8 flex items-center gap-3 border-b border-slate-800 px-5 pb-5">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-2xl shadow-lg shadow-indigo-950">🐞</span>
            <span class="text-base font-extrabold tracking-tight text-white">AutoBug<span class="text-emerald-400">Triage</span></span>
        </a>
        <nav class="flex flex-1 flex-col gap-2 px-3" aria-label="Main navigation">
            <a href="/dashboard" aria-current="page" class="flex min-h-12 items-center gap-4 rounded-xl border border-indigo-400/30 bg-indigo-500/15 px-4 text-sm font-semibold text-indigo-200"><span class="w-7 text-center text-2xl leading-none">⌂</span><span>Command Center</span></a>
            <a href="/workspace" class="flex min-h-12 items-center gap-4 rounded-xl px-4 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"><span class="w-7 text-center text-2xl leading-none">▤</span><span>Kanban Workspace</span></a>
            <a href="/ai-agent" class="flex min-h-12 items-center gap-4 rounded-xl px-4 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"><span class="w-7 text-center text-2xl leading-none">⚡</span><span>AI Triage Studio</span></a>
            <a href="/notifications" class="flex min-h-12 items-center gap-4 rounded-xl px-4 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"><span class="w-7 text-center text-2xl leading-none">♧</span><span>Notifications</span></a>
        </nav>
        <div class="mx-3 border-t border-slate-800 pt-3">
            <a href="/logout" class="flex min-h-12 items-center gap-4 rounded-xl px-4 text-sm font-medium text-slate-400 transition hover:bg-rose-950/50 hover:text-rose-300"><span class="w-7 text-center text-2xl leading-none">⇥</span><span>Sign Out</span></a>
        </div>
    </aside>

    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-20 flex min-h-[72px] flex-wrap items-center gap-4 border-b border-slate-800 bg-[#0b1018]/95 px-4 py-3 backdrop-blur-xl sm:px-6 xl:px-9">
            <a href="/dashboard" class="flex items-center gap-2.5 text-lg font-extrabold tracking-tight text-white"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600">🐞</span><span>AutoBug<span class="text-emerald-400">Triage</span></span></a>
            <div class="hidden h-8 w-px bg-slate-700 md:block"></div>
            <div class="hidden md:block">@include('components.project-switcher', ['variant' => 'dark'])</div>
            <form action="/dashboard" method="GET" class="order-3 w-full flex-1 md:order-none md:min-w-[220px]">
                <label class="sr-only" for="testerSearch">Search your reports</label>
                <div class="relative"><span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-500">⌕</span><input id="testerSearch" type="search" name="q" value="{{ $query }}" placeholder="Search your bug reports…" class="w-full rounded-xl border border-slate-700 bg-[#151c27] py-2.5 pl-9 pr-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-indigo-400 focus:outline-none"></div>
            </form>
            <div class="ml-auto flex items-center gap-3">
                <div class="md:hidden">@include('components.project-switcher', ['variant' => 'mobile'])</div>
                <a href="/notifications" aria-label="Notifications" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-700 text-slate-300 hover:bg-slate-800">♧</a>
                <div class="flex items-center gap-2.5"><div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500/15 font-bold text-emerald-300">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><div class="hidden sm:block"><p class="max-w-36 truncate text-xs font-bold text-white">{{ auth()->user()->name }}</p><p class="text-[10px] text-slate-400">QA Tester</p></div></div>
            </div>
        </header>

        <div class="qa-grid min-h-[calc(100vh-72px)] px-4 py-6 sm:px-6 xl:px-9">
            <div class="mx-auto max-w-[1500px] space-y-6">
                <section class="flex flex-wrap items-end justify-between gap-4">
                    <div><p class="text-[11px] font-bold uppercase tracking-[.2em] text-emerald-400">Quality workspace · {{ $project?->name ?? 'Project' }}</p><h1 class="mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Good to see you, {{ auth()->user()->name }}</h1><p class="mt-1 text-sm qa-muted">Your reports, triage progress, and recent fixes in one place.</p></div>
                    <a href="/ai-agent" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-blue-600 px-5 text-sm font-bold text-white shadow-lg shadow-indigo-950/60 transition hover:brightness-110">＋ Report a bug <span aria-hidden="true">→</span></a>
                </section>

                <section aria-labelledby="qa-kpi-title">
                    <h2 id="qa-kpi-title" class="mb-3 text-xs font-bold uppercase tracking-[.16em] text-slate-400">Your QA overview</h2>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 2xl:grid-cols-4">
                        <article class="qa-panel rounded-2xl p-5"><p class="text-xs font-semibold text-slate-400">Reports submitted</p><p class="mt-3 text-3xl font-extrabold text-white">{{ $totalReports }}</p><p class="mt-1 text-xs text-slate-500">In this project</p></article>
                        <article class="qa-panel rounded-2xl p-5"><p class="text-xs font-semibold text-slate-400">Still in progress</p><p class="mt-3 text-3xl font-extrabold text-indigo-300">{{ $openReports }}</p><p class="mt-1 text-xs text-slate-500">Not in a completed status</p></article>
                        <article class="qa-panel rounded-2xl p-5"><p class="text-xs font-semibold text-slate-400">Resolved</p><p class="mt-3 text-3xl font-extrabold text-emerald-400">{{ $resolvedReports }}</p><p class="mt-1 text-xs text-slate-500">Marked complete by the team</p></article>
                        <article class="qa-panel rounded-2xl p-5"><p class="text-xs font-semibold text-slate-400">Assigned to a developer</p><p class="mt-3 text-3xl font-extrabold text-violet-300">{{ $assignedReports }}</p><p class="mt-1 text-xs text-slate-500">{{ $highPriorityReports }} high priority</p></article>
                    </div>
                </section>

                <div class="grid grid-cols-1 gap-4 2xl:grid-cols-12">
                    <section class="qa-panel rounded-2xl p-5 2xl:col-span-7" aria-labelledby="my-reports-title">
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-slate-800 pb-4"><div><h2 id="my-reports-title" class="text-base font-bold text-white">My bug reports</h2><p class="mt-1 text-xs qa-muted">Track status, owner, and the latest update.</p></div><span class="rounded-full border border-slate-700 bg-slate-900 px-2.5 py-1 text-[10px] font-bold text-slate-300">{{ $reports->count() }} shown</span></div>
                        <div class="space-y-2.5">
                            @forelse($reports as $report)
                                <a href="{{ route('bugs.show', $report->id) }}" class="block rounded-xl border border-slate-800 bg-[#111823] p-3.5 transition hover:border-indigo-500/60 hover:bg-[#151e2b] focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                    <div class="flex flex-wrap items-start justify-between gap-3"><div class="min-w-0"><p class="font-mono text-[10px] font-bold text-indigo-300">ABT-{{ $report->id }} <span class="mx-1 text-slate-600">·</span> {{ $report->priority }} priority</p><h3 class="mt-1 truncate text-sm font-bold text-slate-100">{{ $report->title }}</h3></div><span class="shrink-0 rounded-full border border-slate-700 bg-slate-800 px-2.5 py-1 text-[10px] font-semibold text-slate-200">{{ $report->status }}</span></div>
                                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-400"><span>Developer: <strong class="font-semibold text-slate-200">{{ $report->assigned_to ?: 'Unassigned' }}</strong></span><span>{{ ($report->triage_state ?? 'complete') === 'complete' ? 'Triage complete' : 'Triage in progress' }} · {{ \Illuminate\Support\Carbon::parse($report->updated_at)->diffForHumans() }}</span></div>
                                </a>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-700 px-5 py-10 text-center"><span class="text-3xl" aria-hidden="true">🧪</span><h3 class="mt-3 text-sm font-bold text-white">No reports yet</h3><p class="mt-1 text-xs qa-muted">Create your first report and follow triage here.</p><a href="/ai-agent" class="mt-4 inline-flex min-h-10 items-center rounded-lg bg-indigo-500 px-4 text-xs font-bold text-white hover:bg-indigo-400">Create a report</a></div>
                            @endforelse
                        </div>
                        @if($totalReports > 12)<p class="mt-3 text-center text-[11px] text-slate-500">Showing the 12 most recently updated reports.</p>@endif
                    </section>

                    <div class="space-y-4 2xl:col-span-5">
                        <section class="qa-panel rounded-2xl p-5" aria-labelledby="quick-actions-title">
                            <div class="mb-4 border-b border-slate-800 pb-3"><h2 id="quick-actions-title" class="text-base font-bold text-white">QA actions</h2><p class="mt-1 text-xs qa-muted">Pick up where you left off.</p></div>
                            <div class="grid gap-2.5">
                                <a href="/ai-agent" class="flex items-center gap-3 rounded-xl border border-indigo-400/20 bg-indigo-500/10 p-3.5 hover:bg-indigo-500/15"><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-500/20 text-lg text-indigo-200">＋</span><span><strong class="block text-xs text-white">Submit a bug report</strong><span class="mt-0.5 block text-[10px] text-slate-400">Add steps, expected result, and a screenshot.</span></span><span class="ml-auto text-slate-500">→</span></a>
                                <a href="/workspace" class="flex items-center gap-3 rounded-xl border border-slate-800 bg-[#111823] p-3.5 hover:border-slate-600"><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/10 text-lg text-emerald-300">▤</span><span><strong class="block text-xs text-white">Browse project issues</strong><span class="mt-0.5 block text-[10px] text-slate-400">See the shared project board.</span></span><span class="ml-auto text-slate-500">→</span></a>
                            </div>
                        </section>
                        <section class="qa-panel rounded-2xl p-5" aria-labelledby="triage-help-title"><div class="flex items-start gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-300">✦</span><div><h2 id="triage-help-title" class="text-sm font-bold text-white">What happens after you report?</h2><p class="mt-2 text-xs leading-relaxed qa-muted">The triage assistant checks your description for likely priority and duplicate reports, then suggests an owner. Your report status and developer assignment appear in the list.</p><p class="mt-2 text-[10px] text-slate-500">Suggestions can be reviewed by your project team.</p></div></div></section>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
