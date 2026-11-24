<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="color-scheme" content="light">
    <title>Developer Dashboard · {{ $project?->name ?? 'Workspace' }}</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    @include('components.app-sidebar', ['active' => 'dashboard'])
    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-20 flex min-h-[60px] items-center justify-between gap-3 border-b border-slate-200 bg-white/95 px-5 backdrop-blur-md lg:px-8">
            <p class="truncate text-xs font-semibold text-slate-500">{{ $project?->name ?? 'Project' }} <span class="mx-1 text-slate-300">/</span> Developer Dashboard</p>
            <div class="flex shrink-0 items-center gap-2">
                <a href="{{ route('notifications.index') }}" aria-label="Notifications{{ ($unreadNotificationCount ?? 0) > 0 ? ', '.$unreadNotificationCount.' unread' : '' }}" title="Notifications" class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                    <svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg>
                    @if(($unreadNotificationCount ?? 0) > 0)<span class="absolute -right-1 -top-1 min-w-4 rounded-full bg-rose-500 px-1 py-0.5 text-center text-[10px] font-bold leading-none text-white">{{ $unreadNotificationCount }}</span>@endif
                </a>
                @include('components.profile-header')
            </div>
        </header>
        <div class="mx-auto max-w-[1500px] space-y-5 px-4 py-6 sm:px-6 xl:px-9">
            <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-indigo-100 bg-white px-5 py-5 shadow-sm sm:px-7">
                <div><p class="text-[11px] font-bold uppercase tracking-[.18em] text-indigo-600">{{ $project?->name ?? 'Project' }} · Developer workspace</p><h1 class="mt-1 text-2xl font-extrabold tracking-tight sm:text-3xl">Welcome, {{ auth()->user()->name }}</h1></div>
            </section>
            @include('components.project-command-center')
            <section class="grid grid-cols-2 gap-3 xl:grid-cols-4" aria-label="My work summary">
                <article class="rounded-2xl border border-slate-200 bg-white p-5"><p class="text-xs font-semibold text-slate-500">Assigned to me</p><p class="mt-2 text-3xl font-extrabold">{{ $stats['total'] }}</p><p class="mt-1 text-xs text-slate-500">All project issues</p></article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5"><p class="text-xs font-semibold text-slate-500">Active</p><p class="mt-2 text-3xl font-extrabold text-indigo-700">{{ $stats['open'] }}</p><p class="mt-1 text-xs text-slate-500">Currently in progress</p></article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5"><p class="text-xs font-semibold text-slate-500">Needs review</p><p class="mt-2 text-3xl font-extrabold text-amber-600">{{ $stats['review'] }}</p><p class="mt-1 text-xs text-slate-500">Review, QA, or testing status</p></article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5"><p class="text-xs font-semibold text-slate-500">Completed</p><p class="mt-2 text-3xl font-extrabold text-emerald-700">{{ $stats['resolved'] }}</p><p class="mt-1 text-xs text-slate-500">In a completed status</p></article>
            </section>
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="assigned-issues-title">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4"><div><h2 id="assigned-issues-title" class="text-base font-bold">My assigned issues</h2><p class="mt-1 text-xs text-slate-500">Issues assigned to your account in this project.</p></div><a href="/workspace" class="text-sm font-semibold text-indigo-700 hover:underline">View board →</a></div>
                <div class="overflow-x-auto"><table class="w-full min-w-[650px] text-left text-sm"><thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-3">Issue</th><th class="px-3 py-3">Priority</th><th class="px-3 py-3">Status</th><th class="px-5 py-3">Feature</th></tr></thead><tbody class="divide-y divide-slate-100">
                @forelse($assignedIssues as $issue)
                    <tr class="hover:bg-indigo-50/40"><th class="px-5 py-4 font-semibold"><a href="{{ route('bugs.show', $issue->id) }}" class="text-slate-900 hover:text-indigo-700">{{ $issue->title }}</a><span class="mt-1 block font-mono text-[10px] font-normal text-slate-400">ABT-{{ $issue->id }}</span></th><td class="px-3 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ strtolower($issue->priority) === 'high' ? 'bg-rose-50 text-rose-700' : (strtolower($issue->priority) === 'medium' ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700') }}">{{ strtoupper($issue->priority) }}</span></td><td class="px-3 py-4"><span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $issue->status }}</span></td><td class="px-5 py-4 text-slate-600">{{ $issue->detected_feature ?: 'General' }}</td></tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center"><p class="font-semibold text-slate-800">No issues assigned yet</p><p class="mt-1 text-xs text-slate-500">New assignments for this project will appear here.</p><a href="/workspace" class="mt-3 inline-flex text-sm font-semibold text-indigo-700">Open Kanban board →</a></td></tr>
                @endforelse
                </tbody></table></div>
            </section>
            @if($latestIssue)
                <section class="rounded-2xl border border-indigo-200 bg-indigo-50/70 p-5"><h2 class="font-bold">Latest assigned issue</h2><p class="mt-2 text-sm text-slate-700">{{ $latestIssue->title }} · {{ $latestIssue->status }}</p><a href="{{ route('bugs.show', $latestIssue->id) }}" class="mt-2 inline-flex text-sm font-semibold text-indigo-700">Open issue →</a></section>
            @endif
        </div>
    </main>
</div>
</body>
</html>
