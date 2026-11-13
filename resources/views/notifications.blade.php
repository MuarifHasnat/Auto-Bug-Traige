<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    <aside class="hidden w-[250px] shrink-0 flex-col border-r border-[#E2E8F0] bg-white/95 text-slate-800 backdrop-blur-md lg:flex">
        <div class="flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white px-5">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-xs font-black text-white shadow-md shadow-indigo-600/20">⚡</div>
                <span class="text-sm font-extrabold tracking-tight text-[#0F172A]">AutoBug<span class="text-indigo-600">Triage</span></span>
            </div>
            <span class="rounded-full border border-indigo-200 bg-indigo-50 px-2 py-0.5 font-mono-code text-[10px] font-bold text-indigo-700">AI v1.0</span>
        </div>

        <div class="border-b border-[#E2E8F0] bg-[#F8FAFC] p-4">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Current Workspace</p>
            <p class="mt-1 truncate text-xs font-bold text-slate-900">{{ session('project_name', 'Software Project') }}</p>
            <p class="mt-0.5 truncate font-mono-code text-[10px] text-indigo-600">{{ session('project_stack', 'Laravel • React • PGSQL') }}</p>
            @include('components.project-switcher', ['variant' => 'light'])
        </div>

        <nav class="flex-1 space-y-1 p-3 text-xs" aria-label="Main navigation">
            @if(auth()->user()->role === 'manager')
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="/dashboard"><span class="text-sm">▦</span><span>Command Center</span></a>
            @endif
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="/workspace"><span class="text-sm">▤</span><span>Kanban Workspace</span></a>
            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="/ai-agent"><span class="text-sm">⚡</span><span>AI Triage Studio</span></a>
            <a class="relative flex items-center justify-between gap-3 rounded-xl border border-indigo-200/60 bg-gradient-to-r from-indigo-50 to-purple-50 px-3.5 py-2.5 font-bold text-indigo-700 shadow-2xs" href="/notifications" aria-current="page">
                <span class="absolute bottom-2 left-0 top-2 w-1 rounded-r-full bg-indigo-600"></span>
                <span class="flex items-center gap-3"><span>🔔</span><span>Notifications</span></span>
                @if($unreadNotificationCount > 0)<span class="rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $unreadNotificationCount }}</span>@endif
            </a>
            @if(auth()->user()->role === 'manager')
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="/add-developers"><span class="text-sm">👥</span><span>Team & Skills Matrix</span></a>
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="{{ route('workflow.settings') }}"><span class="text-sm">⇄</span><span>Workflow Settings</span></a>
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 transition hover:bg-slate-100/70 hover:text-slate-900" href="/create-project"><span class="text-sm">⚙</span><span>New Project Wizard</span></a>
            @endif
        </nav>

        <div class="border-t border-[#E2E8F0] bg-[#F8FAFC] p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-indigo-200 bg-indigo-100 font-mono-code text-xs font-bold uppercase text-indigo-700">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                    <span class="inline-block rounded border border-indigo-100 bg-indigo-50 px-1.5 py-0.5 font-mono-code text-[9px] font-bold text-indigo-700">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
                <a href="/logout" title="Sign out" aria-label="Sign out" class="p-1 text-slate-400 transition hover:text-rose-600"><span aria-hidden="true">↪</span></a>
            </div>
        </div>
    </aside>

    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-10 flex min-h-[60px] items-center justify-between gap-3 border-b border-[#E2E8F0] bg-white/90 px-5 backdrop-blur-md lg:px-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                <span class="hidden sm:inline">Workspace</span><span class="hidden text-slate-300 sm:inline">/</span>
                <span class="truncate font-bold text-[#0F172A]">{{ session('project_name', 'Notifications') }}</span><span class="text-slate-300">/</span>
                <span class="text-indigo-600">Notifications</span>
            </div>
            <div class="flex items-center gap-2">
                @include('components.project-switcher', ['variant' => 'mobile'])
                <a href="/logout" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">Sign Out</a>
            </div>
        </header>

        <div class="mx-auto max-w-5xl px-5 py-8 lg:px-8">
            <div class="mb-7 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold">Notifications</h1>
                    <p class="mt-1 text-sm text-slate-500">Bug mentions, comments, assignments and status changes.</p>
                </div>
                @if($notifications->whereNull('read_at')->isNotEmpty())
                    <form action="{{ route('notifications.read-all', [], false) }}" method="POST">@csrf<button class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold transition hover:bg-slate-50">Mark all read</button></form>
                @endif
            </div>

            <div class="space-y-3">
                @forelse($notifications as $notification)
                    @php($data = $notification->data)
                    <article class="rounded-2xl border {{ $notification->read_at ? 'border-slate-200 bg-white' : 'border-indigo-200 bg-indigo-50/60' }} p-4 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2"><h2 class="text-sm font-bold">{{ $data['title'] ?? 'Bug update' }}</h2>@unless($notification->read_at)<span class="h-2 w-2 rounded-full bg-indigo-600" aria-label="Unread"></span>@endunless</div>
                                <p class="mt-1 text-sm text-slate-600">{{ $data['message'] ?? '' }}</p>
                                <p class="mt-2 text-[11px] text-slate-400">{{ $data['actor_name'] ?? 'A teammate' }} · {{ $notification->created_at->diffForHumans() }} · #ABT-{{ $data['bug_id'] ?? '' }}</p>
                            </div>
                            <div class="flex shrink-0 items-center gap-2">
                                <a href="{{ route('notifications.open', $notification->id, false) }}" class="flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-bold text-white shadow-xs transition hover:bg-indigo-500"><span>View Bug Details</span> ➔</a>
                                @unless($notification->read_at)<form action="{{ route('notifications.read', $notification->id, false) }}" method="POST">@csrf<button class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-semibold transition hover:bg-slate-50">Mark Read</button></form>@endunless
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center"><p class="font-bold">You’re all caught up</p><p class="mt-1 text-sm text-slate-500">New bug updates will appear here.</p></div>
                @endforelse
            </div>
            <div class="mt-6">{{ $notifications->links() }}</div>
        </div>
    </main>
</div>
</body>
</html>
