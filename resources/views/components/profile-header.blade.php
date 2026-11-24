@php($profileUser = auth()->user())
<div class="flex shrink-0 items-center gap-2">
    <a href="{{ route('profile.show') }}" aria-label="Open my profile" title="My profile" class="flex items-center gap-2 rounded-xl p-1.5 transition hover:bg-slate-100">
        <span class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full border border-indigo-200 bg-indigo-50 text-sm font-bold text-indigo-700">
            @if($profileUser->profile_photo_path)<img src="{{ route('profile.photo') }}" alt="Profile picture" class="h-full w-full object-cover">@else{{ strtoupper(substr($profileUser->name, 0, 1)) }}@endif
        </span>
        <span class="hidden text-left sm:block"><span class="block max-w-36 truncate text-sm font-semibold text-slate-900">{{ $profileUser->name }}</span><span class="block text-xs text-slate-500">{{ ucfirst($profileUser->role) }}</span></span>
    </a>
    <form action="/logout" method="POST" class="flex">@csrf
        <button type="submit" aria-label="Log out" title="Log out" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700">
            <svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M12 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5"/></svg>
        </button>
    </form>
</div>
