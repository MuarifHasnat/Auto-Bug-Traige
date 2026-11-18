@php
    $currentRole = auth()->user()->role;
    $activeClass = 'relative flex items-center gap-3 rounded-lg bg-indigo-50 px-3 py-2.5 text-[13px] font-semibold text-indigo-700';
    $linkClass = 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] text-slate-600 transition hover:bg-slate-100 hover:text-slate-950';
@endphp
<aside class="hidden w-[194px] shrink-0 flex-col border-r border-slate-200 bg-white lg:flex">
    <a href="/" class="flex h-[58px] items-center border-b border-slate-200 px-5 text-base font-extrabold tracking-tight text-slate-950">AutoBug<span class="text-indigo-600">Triage</span></a>
    <nav class="flex-1 space-y-1 px-3 py-4" aria-label="Main navigation">
        @if(in_array($currentRole, ['manager', 'tester'], true))
            <a href="/dashboard" @if($active === 'dashboard') aria-current="page" @endif class="{{ $active === 'dashboard' ? $activeClass : $linkClass }}"><span aria-hidden="true">▦</span><span>Command Center</span></a>
        @endif
        <a href="/workspace" @if($active === 'workspace') aria-current="page" @endif class="{{ $active === 'workspace' ? $activeClass : $linkClass }}"><span aria-hidden="true">▤</span><span>Kanban board</span></a>
        <a href="/ai-agent" @if($active === 'ai-agent') aria-current="page" @endif class="{{ $active === 'ai-agent' ? $activeClass : $linkClass }}"><span aria-hidden="true">⚡</span><span>AI Triage Studio</span></a>
        <a href="/notifications" @if($active === 'notifications') aria-current="page" @endif class="{{ $active === 'notifications' ? $activeClass : $linkClass }}"><span aria-hidden="true">♧</span><span class="flex-1">Notifications</span>@if(($unreadNotificationCount ?? 0) > 0)<span class="rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $unreadNotificationCount }}</span>@endif</a>
        @if($currentRole === 'manager')
            <a href="/add-developers" @if($active === 'team') aria-current="page" @endif class="{{ $active === 'team' ? $activeClass : $linkClass }}"><span aria-hidden="true">♙</span><span>Team and skills</span></a>
            <a href="{{ route('workflow.settings') }}" @if($active === 'workflow') aria-current="page" @endif class="{{ $active === 'workflow' ? $activeClass : $linkClass }}"><span aria-hidden="true">⇄</span><span>Workflow settings</span></a>
            <a href="/create-project" @if($active === 'project') aria-current="page" @endif class="{{ $active === 'project' ? $activeClass : $linkClass }}"><span aria-hidden="true">＋</span><span>New project wizard</span></a>
        @endif
    </nav>
</aside>
