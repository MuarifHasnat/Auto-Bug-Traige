@php($profileUser = auth()->user())
<div class="flex shrink-0 items-center gap-2">
    <a href="{{ route('profile.show') }}" aria-label="Open my profile" title="My profile" class="flex items-center gap-2 rounded-xl p-1.5 transition hover:bg-slate-100">
        <span class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full border border-indigo-200 bg-indigo-50 text-sm font-bold text-indigo-700">
            @if($profileUser->profile_photo_path)<img src="{{ route('profile.photo') }}" alt="Profile picture" class="h-full w-full object-cover">@else{{ strtoupper(substr($profileUser->name, 0, 1)) }}@endif
        </span>
        <span class="hidden text-left sm:block"><span class="block max-w-36 truncate text-sm font-semibold text-slate-900">{{ $profileUser->name }}</span><span class="block text-xs text-slate-500">{{ ucfirst($profileUser->role) }}</span></span>
    </a>
    <a href="/logout" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700">Log out</a>
</div>
