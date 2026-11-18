<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    @if(auth()->user()->role === 'tester')<link rel="stylesheet" href="/css/tester-theme.css">@endif
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased {{ auth()->user()->role === 'tester' ? 'tester-shell' : '' }}">
<div class="flex min-h-screen">
    @include('components.app-sidebar', ['active' => 'notifications'])

    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-10 flex min-h-[60px] items-center justify-between gap-3 border-b border-[#E2E8F0] bg-white/90 px-5 backdrop-blur-md lg:px-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                <span class="hidden sm:inline">Workspace</span><span class="hidden text-slate-300 sm:inline">/</span>
                <span class="truncate font-bold text-[#0F172A]">{{ session('project_name', 'Notifications') }}</span><span class="text-slate-300">/</span>
                <span class="text-indigo-600">Notifications</span>
            </div>
            <div class="flex items-center gap-2">
                @include('components.project-switcher', ['variant' => 'mobile'])
                @include('components.profile-header')
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

            <nav class="mb-4 flex flex-wrap gap-2" aria-label="Filter notifications">
                @foreach(['all' => 'All', 'mention' => 'Mentions', 'assignment' => 'Assignments', 'status' => 'Status changes'] as $type => $label)
                    <a href="{{ route('notifications.index', $type === 'all' ? [] : ['type' => $type], false) }}" @if($notificationType === $type) aria-current="page" @endif class="rounded-xl border px-4 py-2 text-xs font-semibold transition {{ $notificationType === $type ? 'border-indigo-200 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">{{ $label }}</a>
                @endforeach
            </nav>

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
