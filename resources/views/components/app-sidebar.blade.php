@php
    $currentRole = auth()->user()->role;
    $isTesterWorkspace = $currentRole === 'tester';
    $sidebarWidth = 'w-[264px]';
    $activeWorkspaceProject = isset($projects) ? $projects->firstWhere('id', $currentProjectId ?? null) : null;
    $hasAssignedProject = isset($projects) ? $projects->isNotEmpty() : (bool) session('project_name');
    $activeClass = 'relative flex min-h-12 items-center gap-3 rounded-lg bg-indigo-50 px-3 py-3 text-sm font-bold text-indigo-700';
    $linkClass = 'flex min-h-12 items-center gap-3 rounded-lg px-3 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-950';
@endphp
<aside class="{{ $sidebarWidth }} sticky top-0 hidden h-screen max-h-screen shrink-0 flex-col overflow-y-auto border-r border-slate-200 bg-white lg:flex">
    <a href="/" class="flex min-h-[64px] items-center border-b border-slate-200 px-5 text-lg font-extrabold tracking-tight text-slate-950">AutoBug<span class="text-indigo-600">Triage</span>@if($isTesterWorkspace)<span class="ml-2 rounded bg-indigo-50 px-1.5 py-0.5 text-[9px] font-mono text-indigo-700">AI v1.0</span>@endif</a>
    <div class="mx-3 mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
        <p class="text-xs font-bold text-slate-600">Current project</p>
        <p class="mt-0.5 truncate text-base font-bold text-slate-900" title="{{ $hasAssignedProject ? ($activeWorkspaceProject?->name ?? session('project_name', 'Workspace')) : 'Not assigned yet' }}">{{ $hasAssignedProject ? ($activeWorkspaceProject?->name ?? session('project_name', 'Workspace')) : 'Not assigned yet' }}</p>
        <p class="mt-0.5 line-clamp-2 text-xs font-medium leading-5 text-slate-700">{{ $hasAssignedProject ? ($activeWorkspaceProject?->stack ?: session('project_stack', $isTesterWorkspace ? 'Project issue tracking and QA' : 'Team issue tracking')) : 'No project has been assigned to this account.' }}</p>
    </div>
    @if(isset($projects) && isset($currentProjectId))
        <div class="px-3">@include('components.project-switcher', ['variant' => 'sidebar'])</div>
    @endif
    <nav class="flex-1 space-y-1 px-3 py-4" aria-label="Main navigation">
        <a href="/dashboard" @if($active === 'dashboard') aria-current="page" @endif class="{{ $active === 'dashboard' ? $activeClass : $linkClass }}"><span aria-hidden="true">▦</span><span>Command Center</span></a>
        @if($isTesterWorkspace)
            <a href="{{ route('my-reports.index') }}" @if($active === 'my-reports') aria-current="page" @endif class="{{ $active === 'my-reports' ? $activeClass : $linkClass }}"><span aria-hidden="true">▤</span><span>My Reported Bugs</span></a>
        @endif
        <a href="/workspace" @if($active === 'workspace') aria-current="page" @endif class="{{ $active === 'workspace' ? $activeClass : $linkClass }}"><span aria-hidden="true">▤</span><span>{{ $isTesterWorkspace ? 'Kanban Workspace' : 'Kanban board' }}</span></a>
        <a href="/ai-agent" @if($active === 'ai-agent') aria-current="page" @endif class="{{ $active === 'ai-agent' ? $activeClass : $linkClass }}"><span aria-hidden="true">⚡</span><span>AI Triage Studio</span></a>
        <a href="/notifications" @if($active === 'notifications') aria-current="page" @endif class="{{ $active === 'notifications' ? $activeClass : $linkClass }}"><svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg><span class="flex-1">Notifications</span>@if(($unreadNotificationCount ?? 0) > 0)<span class="rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $unreadNotificationCount }}</span>@endif</a>
        @if(in_array($currentRole, ['manager', 'developer', 'tester'], true))
            <a href="{{ $currentRole === 'manager' ? '/add-developers' : route('team.index') }}" @if($active === 'team') aria-current="page" @endif class="{{ $active === 'team' ? $activeClass : $linkClass }}"><span aria-hidden="true">♙</span><span>{{ $currentRole === 'manager' ? 'Team and skills' : 'Team' }}</span></a>
        @endif
        @if($currentRole === 'manager')
            <a href="{{ route('workflow.settings') }}" @if($active === 'workflow') aria-current="page" @endif class="{{ $active === 'workflow' ? $activeClass : $linkClass }}"><span aria-hidden="true">⇄</span><span>Workflow settings</span></a>
            <a href="/create-project" @if($active === 'project') aria-current="page" @endif class="{{ $active === 'project' ? $activeClass : $linkClass }}"><span aria-hidden="true">＋</span><span>New project wizard</span></a>
        @endif
        <a href="{{ route('profile.show') }}" @if($active === 'profile') aria-current="page" @endif class="{{ $active === 'profile' ? $activeClass : $linkClass }}"><span aria-hidden="true">♙</span><span>My Profile</span></a>
    </nav>
    <div class="border-t border-slate-200 p-3">
        <form action="/logout" method="POST">@csrf<button type="submit" class="flex min-h-12 w-full items-center gap-3 rounded-lg px-3 py-3 text-sm font-semibold text-slate-600 transition hover:bg-rose-50 hover:text-rose-700"><span aria-hidden="true">⇥</span><span>Logout</span></button></form>
    </div>
</aside>
