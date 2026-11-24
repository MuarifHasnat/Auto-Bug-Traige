<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="color-scheme" content="light">
    <title>Project Team · {{ $currentProject?->name ?? 'Workspace' }}</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    @include('components.app-sidebar', ['active' => 'team'])
    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-20 flex min-h-[60px] items-center justify-between gap-3 border-b border-slate-200 bg-white/95 px-5 backdrop-blur-md lg:px-8">
            <p class="truncate text-xs font-semibold text-slate-500">{{ $currentProject?->name ?? 'Project' }} <span class="mx-1 text-slate-300">/</span> Team</p>
            @include('components.profile-header')
        </header>
        <div class="mx-auto w-full max-w-[1500px] space-y-5 px-4 py-6 sm:px-6 xl:px-9">
            <section><p class="text-[11px] font-bold uppercase tracking-[.18em] text-indigo-600">{{ $currentProject?->name ?? 'Project' }}</p><h1 class="mt-1 text-2xl font-extrabold tracking-tight sm:text-3xl">Project team</h1><p class="mt-1 text-sm text-slate-500">People assigned to this project and how to reach them.</p></section>
            <section class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3" aria-label="Project members">
                @forelse($members as $member)
                    @php($skills = $developerSkills[$member->name] ?? '')
                    @php($isOnline = $member->id === auth()->id() || ($member->last_seen_at && $member->last_seen_at->gt(now()->subMinutes(3))))
                    <a href="{{ route('profile.public', $member->id) }}" class="block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-label="View {{ $member->name }}'s profile">
                        <div class="flex items-center gap-3">
                            <span class="relative flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full bg-indigo-50 font-bold text-indigo-700">
                                @if($member->profile_photo_path)<img src="{{ route('team-members.photo', $member->id) }}" alt="{{ $member->name }} profile" class="h-full w-full object-cover">@else{{ strtoupper(substr($member->name, 0, 1)) }}@endif
                                @if($isOnline)<span class="absolute bottom-0 right-0 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500" title="Online" aria-label="Online"></span>@endif
                            </span>
                            <span class="min-w-0 flex-1"><span class="block truncate font-bold text-slate-900">{{ $member->name }} @if($member->id === auth()->id())<span class="text-xs font-medium text-slate-400">(You)</span>@endif</span><span class="mt-1 inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold {{ $member->role === 'manager' ? 'bg-violet-50 text-violet-700' : ($member->role === 'developer' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700') }}">{{ $member->role === 'tester' ? 'QA Tester' : ucfirst($member->role) }}</span></span>
                        </div>
                        <span class="mt-4 block truncate text-sm text-indigo-700">{{ $member->email }}</span>
                        @if($member->role === 'developer')<span class="mt-3 block text-xs text-slate-500"><span class="font-semibold text-slate-700">Skills:</span> {{ $skills ?: 'Not added yet' }}</span>@endif
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-5 py-12 text-center md:col-span-2 xl:col-span-3"><h2 class="font-semibold">No team members found</h2><p class="mt-1 text-sm text-slate-500">Ask your manager to add people to this project.</p></div>
                @endforelse
            </section>
        </div>
    </main>
</div>
</body>
</html>
