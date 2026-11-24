<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>QA Home · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/js/app.js')
    @endif
    <style>
        :root { color-scheme: light; }
        body { background: #f8fafc; color: #0f172a; }
        .qa-panel { background: #fff; border: 1px solid #e2e8f0; box-shadow: 0 8px 24px rgba(15,23,42,.035); }
        .qa-muted { color: #64748b; }
        .qa-grid { background: #f5f7fb; }
        :focus-visible { outline: 2px solid #6366f1; outline-offset: 2px; }
    </style>
</head>
<body class="min-h-screen antialiased">
<div class="flex min-h-screen">
    @include('components.app-sidebar', ['active' => 'dashboard'])

    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-20 flex min-h-[72px] flex-wrap items-center gap-4 border-b border-slate-200 bg-white/95 px-4 py-3 backdrop-blur-xl sm:px-6 xl:px-9">
            <a href="/dashboard" class="flex items-center gap-2.5 text-lg font-extrabold tracking-tight text-slate-900"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600">🐞</span><span>AutoBug<span class="text-emerald-400">Triage</span></span></a>
            <div class="hidden h-8 w-px bg-slate-200 md:block"></div>
            <div class="hidden md:block">@include('components.project-switcher', ['variant' => 'light'])</div>
            <form action="/dashboard" method="GET" class="order-3 w-full flex-1 md:order-none md:min-w-[220px]">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                <label class="sr-only" for="testerSearch">Search your reports</label>
                <div class="relative"><span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-500">⌕</span><input id="testerSearch" type="search" name="q" value="{{ $query }}" placeholder="Search your bug reports…" class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-500 focus:border-indigo-400 focus:outline-none"></div>
            </form>
            <div class="ml-auto flex items-center gap-3">
                <div class="md:hidden">@include('components.project-switcher', ['variant' => 'mobile'])</div>
                <a href="/notifications" aria-label="Notifications" title="Notifications" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100"><svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg></a>
                <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 rounded-xl p-1.5 hover:bg-slate-100"><span class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-emerald-50 text-sm font-bold text-emerald-700">@if(auth()->user()->profile_photo_path)<img src="{{ route('profile.photo') }}" class="h-full w-full object-cover" alt="Profile">@else{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}@endif</span><span class="hidden sm:block"><span class="block max-w-36 truncate text-sm font-bold text-slate-900">{{ auth()->user()->name }}</span><span class="text-xs text-slate-400">{{ ucfirst(auth()->user()->role) }}</span></span></a>
                <form action="/logout" method="POST" class="flex">@csrf<button type="submit" aria-label="Log out" title="Log out" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700"><svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M12 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5"/></svg></button></form>
            </div>
        </header>

        <div class="qa-grid min-h-[calc(100vh-72px)] px-4 py-6 sm:px-6 xl:px-9">
            <div class="mx-auto max-w-[1500px] space-y-5">
                <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-indigo-100 bg-white px-5 py-4 shadow-sm sm:px-7">
                    <div><p class="text-[11px] font-bold uppercase tracking-[.18em] text-indigo-600">{{ $project?->name ?? 'Project' }} · Tester workspace</p><h1 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900 sm:text-2xl">Good day, {{ auth()->user()->name }}! Found a bug?</h1><p class="mt-1 text-sm qa-muted">Report it and the AI will find the file, suggest a fix, and route it to a developer.</p></div>
                    <a href="/ai-agent" class="inline-flex min-h-11 shrink-0 items-center gap-2 rounded-xl bg-indigo-600 px-5 text-sm font-bold text-white shadow-md shadow-indigo-200 transition hover:bg-indigo-700">＋ Report Bug</a>
                </section>

                @include('components.project-command-center')

                <section aria-label="Tester dashboard summary" class="grid grid-cols-2 gap-3 xl:grid-cols-4">
                    <article class="qa-panel rounded-2xl p-4 sm:p-5"><p class="text-xs font-semibold text-slate-500">Reported by me</p><p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $totalReports }}</p><p class="mt-1 text-xs text-slate-500">In this project</p></article>
                    <article class="qa-panel rounded-2xl p-4 sm:p-5"><p class="text-xs font-semibold text-slate-500">Open</p><p class="mt-2 text-3xl font-extrabold text-amber-600">{{ $openReports }}</p><p class="mt-1 text-xs text-slate-500">Still being worked on</p></article>
                    <article class="qa-panel rounded-2xl p-4 sm:p-5"><p class="text-xs font-semibold text-slate-500">Waiting for my retest</p><p class="mt-2 text-3xl font-extrabold text-indigo-700">{{ $retestReports->count() }}</p><p class="mt-1 text-xs text-slate-500">Ready for QA verification</p></article>
                    <article class="qa-panel rounded-2xl p-4 sm:p-5"><p class="text-xs font-semibold text-slate-500">Verified and closed</p><p class="mt-2 text-3xl font-extrabold text-emerald-700">{{ $resolvedReports }}</p><p class="mt-1 text-xs text-slate-500">Completed by the team</p></article>
                </section>

                <div class="grid grid-cols-1 items-start gap-4 2xl:grid-cols-12">
                    <section class="qa-panel min-w-0 overflow-hidden rounded-2xl 2xl:col-span-8" aria-labelledby="my-reports-title">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                            <div><h2 id="my-reports-title" class="text-base font-bold text-slate-900">My reported bugs</h2><p class="mt-1 text-xs qa-muted">Check priority, status, AI file match, and assignee.</p></div>
                            <form action="/dashboard" method="GET"><input type="hidden" name="q" value="{{ $query }}"><label class="sr-only" for="testerStatus">Filter reports by status</label><select id="testerStatus" name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700"><option value="">All statuses</option>@foreach($availableStatuses as $availableStatus)<option value="{{ $availableStatus }}" @selected($statusFilter === $availableStatus)>{{ $availableStatus }}</option>@endforeach</select></form>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] text-left text-sm">
                                <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500"><tr><th scope="col" class="px-5 py-3">Title</th><th scope="col" class="px-3 py-3">Priority</th><th scope="col" class="px-3 py-3">Status</th><th scope="col" class="px-3 py-3">File found by AI</th><th scope="col" class="px-5 py-3">Assignee</th></tr></thead>
                                <tbody class="divide-y divide-slate-200">
                                @forelse($reports as $report)
                                    @php($reportTriage = json_decode($report->triage_result ?: '', true) ?: [])
                                    @php($reportSuspectedFile = data_get($reportTriage, 'code_help.suspected_files.0', data_get($reportTriage, 'suspected_files.0')))
                                    @php($reportFile = is_array($reportSuspectedFile) ? ($reportSuspectedFile['path'] ?? $reportSuspectedFile['file'] ?? null) : $reportSuspectedFile)
                                    @php($reportFile = $reportFile ?: $report->source_file_path ?: $report->source_file_name)
                                    <tr class="transition hover:bg-indigo-50/50"><th scope="row" class="max-w-[290px] px-5 py-4 font-semibold"><a href="{{ route('bugs.show', $report->id) }}" class="block truncate text-slate-900 hover:text-indigo-700">{{ $report->title }}</a><span class="mt-1 block font-mono text-[10px] font-normal text-slate-400">ABT-{{ $report->id }}</span></th><td class="whitespace-nowrap px-3 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-bold @class(['bg-rose-50 text-rose-700' => strtolower($report->priority) === 'high', 'bg-amber-50 text-amber-700' => strtolower($report->priority) === 'medium', 'bg-blue-50 text-blue-700' => strtolower($report->priority) === 'low', 'bg-slate-100 text-slate-600' => !in_array(strtolower($report->priority), ['high','medium','low'], true)])">{{ strtoupper($report->priority) }}</span></td><td class="whitespace-nowrap px-3 py-4"><span class="rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700">{{ $report->status }}</span></td><td class="max-w-[220px] px-3 py-4"><span class="block truncate font-mono text-xs text-slate-700" title="{{ $reportFile ?: 'No file identified' }}">{{ $reportFile ?: 'Not identified yet' }}</span></td><td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $report->assigned_to ?: 'Unassigned' }}</td></tr>
                                @empty
                                    <tr><td colspan="5" class="px-5 py-12 text-center"><span class="text-3xl" aria-hidden="true">🧪</span><h3 class="mt-2 font-bold text-slate-900">No reports found</h3><p class="mt-1 text-xs qa-muted">Try a different search or report your first bug.</p><a href="/ai-agent" class="mt-3 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700">Report Bug</a></td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($totalReports > 12)<p class="border-t border-slate-200 px-5 py-3 text-center text-xs text-slate-500">Showing the 12 most recently updated reports.</p>@endif
                    </section>

                    <aside class="space-y-4 2xl:col-span-4">
                        <section class="qa-panel rounded-2xl p-5" aria-labelledby="retest-queue-title"><div class="mb-4"><h2 id="retest-queue-title" class="text-base font-bold text-slate-900">Retest queue</h2><p class="mt-1 text-xs qa-muted">Reports in review or QA status are ready for verification.</p></div>
                            <div class="space-y-2">
                                @forelse($retestReports as $retestReport)
                                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 p-3"><div class="min-w-0"><p class="truncate text-sm font-semibold text-slate-800">{{ $retestReport->title }}</p><p class="mt-1 text-xs text-slate-500">{{ $retestReport->status }}</p></div><a href="{{ route('bugs.show', $retestReport->id) }}" class="shrink-0 rounded-lg bg-indigo-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-indigo-700">Verify</a></div>
                                @empty
                                    <div class="rounded-xl border border-dashed border-slate-300 px-4 py-6 text-center"><p class="text-sm font-semibold text-slate-700">Nothing to retest</p><p class="mt-1 text-xs text-slate-500">We’ll show reports here when they enter review.</p></div>
                                @endforelse
                            </div>
                        </section>
                        <section class="rounded-2xl border border-indigo-200 bg-indigo-50/70 p-5" aria-labelledby="latest-triage-title"><h2 id="latest-triage-title" class="text-base font-bold text-slate-900">Last AI triage result</h2>
                            @if($latestTriageBug)
                                <p class="mt-3 text-sm text-slate-600">Category: <strong class="font-semibold text-slate-800">{{ $latestTriageBug->detected_feature ?: data_get($latestTriage, 'feature', 'General') }}</strong></p><p class="mt-1 text-sm text-slate-600">File: <span class="font-mono text-xs text-slate-800">{{ $latestTriageFile ?: ($latestTriageBug->source_file_path ?: $latestTriageBug->source_file_name) ?: 'No file identified' }}</span></p><p class="mt-1 text-sm text-slate-600">Priority: <strong class="font-semibold text-slate-800">{{ data_get($latestTriage, 'priority', 'Not available') }}</strong></p><a href="{{ route('bugs.show', $latestTriageBug->id) }}" class="mt-3 inline-flex text-sm font-semibold text-indigo-700 hover:text-indigo-900">Open triage details →</a>
                            @else
                                <p class="mt-3 text-sm text-slate-600">Your AI analysis will appear here after you submit a bug report.</p><a href="/ai-agent" class="mt-3 inline-flex text-sm font-semibold text-indigo-700 hover:text-indigo-900">Submit a bug report →</a>
                            @endif
                        </section>
                    </aside>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
