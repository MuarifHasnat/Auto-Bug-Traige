<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="mx-auto max-w-4xl px-5 py-10">
    <header class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div><a href="/workspace" class="text-sm font-bold text-indigo-600">← Workspace</a><h1 class="mt-3 text-2xl font-extrabold">Notifications</h1><p class="mt-1 text-sm text-slate-500">Bug mentions, comments, assignments and status changes.</p></div>
        @if($notifications->whereNull('read_at')->isNotEmpty())
            <form action="{{ route('notifications.read-all', [], false) }}" method="POST">@csrf<button class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-bold">Mark all read</button></form>
        @endif
    </header>
    <div class="space-y-3">
        @forelse($notifications as $notification)
            @php($data = $notification->data)
            <article class="rounded-2xl border {{ $notification->read_at ? 'border-slate-200 bg-white' : 'border-indigo-200 bg-indigo-50/60' }} p-4 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="min-w-0"><div class="flex items-center gap-2"><h2 class="text-sm font-bold">{{ $data['title'] ?? 'Bug update' }}</h2>@unless($notification->read_at)<span class="h-2 w-2 rounded-full bg-indigo-600"></span>@endunless</div>
                        <p class="mt-1 text-sm text-slate-600">{{ $data['message'] ?? '' }}</p>
                        <p class="mt-2 text-[11px] text-slate-400">{{ $data['actor_name'] ?? 'A teammate' }} · {{ $notification->created_at->diffForHumans() }} · #ABT-{{ $data['bug_id'] ?? '' }}</p></div>
                    <div class="flex shrink-0 gap-2">
                        <form action="{{ route('notifications.open', $notification->id, false) }}" method="POST">@csrf<button class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-bold text-white">Open bug</button></form>
                        @unless($notification->read_at)<form action="{{ route('notifications.read', $notification->id, false) }}" method="POST">@csrf<button class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold">Read</button></form>@endunless
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center"><p class="font-bold">You’re all caught up</p><p class="mt-1 text-sm text-slate-500">New bug updates will appear here.</p></div>
        @endforelse
    </div>
    <div class="mt-6">{{ $notifications->links() }}</div>
</div>
</body>
</html>
