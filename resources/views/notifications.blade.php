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

        <div class="mx-auto w-full max-w-[1500px] px-4 py-4 sm:px-6 sm:py-5 xl:px-9">
            <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold">Notifications</h1>
                    <p class="mt-0.5 text-xs text-slate-500">Bug mentions, comments, assignments and status changes.</p>
                </div>
                @if(($unreadNotificationCount ?? 0) > 0)
                    <form action="{{ route('notifications.read-all', [], false) }}" method="POST">@csrf<button class="rounded-xl border border-slate-300 bg-white px-3.5 py-1.5 text-xs font-bold transition hover:bg-slate-50 shadow-2xs">Mark all as read <span class="ml-1 text-slate-500">({{ $unreadNotificationCount }})</span></button></form>
                @endif
            </div>

            <nav class="mb-3 flex flex-wrap gap-1.5" aria-label="Filter notifications">
                @php
                    $notificationFilters = auth()->user()->role === 'tester'
                        ? ['all' => 'All', 'mention' => 'Mentions', 'comment' => 'Comments', 'status' => 'Status changes']
                        : ['all' => 'All', 'mention' => 'Mentions', 'assignment' => 'Assignments', 'status' => 'Status changes'];
                @endphp
                @foreach($notificationFilters as $type => $label)
                    <a href="{{ route('notifications.index', $type === 'all' ? [] : ['type' => $type], false) }}" @if($notificationType === $type) aria-current="page" @endif class="rounded-lg border px-3 py-1.5 text-xs font-semibold transition {{ $notificationType === $type ? 'border-indigo-200 bg-indigo-50 text-indigo-700' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">{{ $label }}</a>
                @endforeach
            </nav>

            <div class="space-y-2">
                @if($notifications->isNotEmpty())
                @foreach($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $event = strtolower((string) ($data['event'] ?? ''));
                        $notificationLabel = match (true) {
                            str_contains($event, 'mention') => 'Mention',
                            str_contains($event, 'comment') => 'Comment',
                            in_array($event, ['assigned', 'assignment_changed', 'reassigned'], true) => 'Assigned',
                            $event === 'status_changed' => 'Retest',
                            in_array($event, ['ai_triage', 'triaged', 'bug_created'], true) => 'AI',
                            default => 'Update',
                        };
                    @endphp
                    @if(auth()->user()->role === 'tester')
                    <article class="rounded-xl border {{ $notification->read_at ? 'border-slate-200 bg-white' : 'border-indigo-200 bg-white' }} p-3 sm:p-3.5 shadow-2xs">
                        <a href="{{ route('notifications.open', $notification->id, false) }}" class="flex items-center gap-2.5 sm:gap-3">
                            <span class="inline-flex w-20 shrink-0 justify-center rounded-full px-2.5 py-0.5 text-[11px] font-medium {{ $notificationLabel === 'Retest' ? 'bg-amber-100 text-amber-800' : ($notificationLabel === 'Assigned' ? 'bg-emerald-50 text-emerald-800' : 'bg-indigo-50 text-indigo-700') }}">{{ $notificationLabel }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="flex items-center gap-1.5"><span class="truncate text-xs sm:text-sm font-bold text-slate-900">{{ $data['title'] ?? 'Bug update' }}</span>@unless($notification->read_at)<span class="h-1.5 w-1.5 shrink-0 rounded-full bg-indigo-600" aria-label="Unread"></span>@endunless</span>
                                <span class="mt-0.5 block truncate text-xs text-slate-600">{{ $data['message'] ?? '' }}</span>
                            </span>
                            <time class="shrink-0 text-[10px] text-slate-500">{{ $notification->created_at->diffForHumans() }}</time>
                        </a>
                    </article>
                    @else
                    <article class="rounded-xl border {{ $notification->read_at ? 'border-slate-200 bg-white' : 'border-indigo-200 bg-indigo-50/60' }} p-3 sm:p-3.5 shadow-2xs">
                        <div class="flex flex-wrap items-center justify-between gap-2.5">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5"><h2 class="text-xs sm:text-sm font-bold">{{ $data['title'] ?? 'Bug update' }}</h2>@unless($notification->read_at)<span class="h-1.5 w-1.5 rounded-full bg-indigo-600" aria-label="Unread"></span>@endunless</div>
                                <p class="mt-0.5 text-xs text-slate-600">{{ $data['message'] ?? '' }}</p>
                                <p class="mt-1 text-[10px] text-slate-400">{{ $data['actor_name'] ?? 'A teammate' }} · {{ $notification->created_at->diffForHumans() }} · #ABT-{{ $data['bug_id'] ?? '' }}</p>
                            </div>
                            <div class="flex shrink-0 items-center gap-1.5">
                                <a href="{{ route('notifications.open', $notification->id, false) }}" class="flex items-center gap-1 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white shadow-2xs transition hover:bg-indigo-500"><span>View</span> ➔</a>
                                @unless($notification->read_at)<form action="{{ route('notifications.read', $notification->id, false) }}" method="POST">@csrf<button class="rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold transition hover:bg-slate-50">Mark Read</button></form>@endunless
                            </div>
                        </div>
                    </article>
                    @endif
                @endforeach
                @else
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center" role="status"><p class="text-lg font-bold">You’re all caught up</p><p class="mt-1 text-sm text-slate-500">Bug mentions, comments, assignments and status changes will appear here.</p></div>
                @endif
            </div>
            <div class="mt-6">{{ $notifications->links() }}</div>
        </div>
    </main>
</div>
</body>
</html>
