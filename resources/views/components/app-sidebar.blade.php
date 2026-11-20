@php
    $currentRole = auth()->user()->role;
    $isTesterWorkspace = $currentRole === 'tester';
    $sidebarWidth = 'w-[240px]';
    $activeWorkspaceProject = isset($projects) ? $projects->firstWhere('id', $currentProjectId ?? null) : null;
    $activeClass = 'relative flex items-center gap-3 rounded-lg bg-indigo-50 px-3 py-2.5 text-[13px] font-bold text-indigo-700';
    $linkClass = 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-[13px] font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-950';
@endphp
<aside class="{{ $sidebarWidth }} sticky top-0 hidden h-screen max-h-screen shrink-0 flex-col overflow-y-auto border-r border-slate-200 bg-white lg:flex">
    <a href="/" class="flex min-h-[58px] items-center border-b border-slate-200 px-5 text-base font-extrabold tracking-tight text-slate-950">AutoBug<span class="text-indigo-600">Triage</span>@if($isTesterWorkspace)<span class="ml-2 rounded bg-indigo-50 px-1.5 py-0.5 text-[9px] font-mono text-indigo-700">AI v1.0</span>@endif</a>
    <div class="mx-3 mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
        <p class="text-[10px] font-bold text-slate-600">Current project</p>
        <p class="mt-0.5 truncate text-sm font-bold text-slate-900" title="{{ $activeWorkspaceProject?->name ?? session('project_name', 'Workspace') }}">{{ $activeWorkspaceProject?->name ?? session('project_name', 'Workspace') }}</p>
        <p class="mt-0.5 line-clamp-2 text-[11px] font-medium leading-4 text-slate-700">{{ $activeWorkspaceProject?->stack ?: session('project_stack', $isTesterWorkspace ? 'Project issue tracking and QA' : 'Team issue tracking') }}</p>
    </div>
    @if(isset($projects) && isset($currentProjectId))
        <div class="px-3">@include('components.project-switcher', ['variant' => 'sidebar'])</div>
    @endif
    <nav class="flex-1 space-y-1 px-3 py-4" aria-label="Main navigation">
        @if(in_array($currentRole, ['manager', 'tester'], true))
            <a href="/dashboard" @if($active === 'dashboard') aria-current="page" @endif class="{{ $active === 'dashboard' ? $activeClass : $linkClass }}"><span aria-hidden="true">▦</span><span>{{ $isTesterWorkspace ? 'Dashboard' : 'Command Center' }}</span></a>
        @endif
        @if($isTesterWorkspace)
            <a href="{{ route('my-reports.index') }}" @if($active === 'my-reports') aria-current="page" @endif class="{{ $active === 'my-reports' ? $activeClass : $linkClass }}"><span aria-hidden="true">▤</span><span>My Reported Bugs</span></a>
        @endif
        <a href="/workspace" @if($active === 'workspace') aria-current="page" @endif class="{{ $active === 'workspace' ? $activeClass : $linkClass }}"><span aria-hidden="true">▤</span><span>{{ $isTesterWorkspace ? 'Kanban Workspace' : 'Kanban board' }}</span></a>
        <a href="/ai-agent" @if($active === 'ai-agent') aria-current="page" @endif class="{{ $active === 'ai-agent' ? $activeClass : $linkClass }}"><span aria-hidden="true">⚡</span><span>AI Triage Studio</span></a>
        <a href="/notifications" @if($active === 'notifications') aria-current="page" @endif class="{{ $active === 'notifications' ? $activeClass : $linkClass }}"><span aria-hidden="true">♧</span><span class="flex-1">Notifications</span>@if(($unreadNotificationCount ?? 0) > 0)<span class="rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $unreadNotificationCount }}</span>@endif</a>
        @if($currentRole === 'manager')
            <a href="/add-developers" @if($active === 'team') aria-current="page" @endif class="{{ $active === 'team' ? $activeClass : $linkClass }}"><span aria-hidden="true">♙</span><span>Team and skills</span></a>
            <a href="{{ route('workflow.settings') }}" @if($active === 'workflow') aria-current="page" @endif class="{{ $active === 'workflow' ? $activeClass : $linkClass }}"><span aria-hidden="true">⇄</span><span>Workflow settings</span></a>
            <a href="/create-project" @if($active === 'project') aria-current="page" @endif class="{{ $active === 'project' ? $activeClass : $linkClass }}"><span aria-hidden="true">＋</span><span>New project wizard</span></a>
        @endif
    </nav>
</aside>
