<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ session('project_name', 'Project') }} · Issues</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <style>
        body { font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
        .jira-scroll::-webkit-scrollbar { width: 7px; height: 7px; }
        .jira-scroll::-webkit-scrollbar-thumb { background: #c1c7d0; border-radius: 8px; }
    </style>
</head>
<body class="modern-saas bg-[#f4f5f7] text-[#172b4d]">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 shrink-0 flex-col bg-[#0747a6] text-white lg:flex">
            <div class="flex h-16 items-center gap-3 border-b border-white/15 px-5">
                <div class="flex h-8 w-8 items-center justify-center rounded bg-white text-lg font-black text-[#0747a6]">A</div>
                <span class="text-lg font-bold tracking-tight">AutoBugTriage</span>
            </div>
            <div class="border-b border-white/15 px-4 py-5">
                <p class="text-xs uppercase tracking-wider text-blue-200">Project</p>
                <p class="mt-2 truncate font-semibold">{{ session('project_name', 'Software Project') }}</p>
                <p class="mt-1 truncate text-xs text-blue-200">{{ session('project_stack', 'Project workspace') }}</p>
            </div>
            <nav class="flex-1 space-y-1 p-3 text-sm">
                @if(auth()->user()->role === 'manager')<a class="flex items-center gap-3 rounded px-3 py-2.5 text-blue-100 hover:bg-white/10" href="/dashboard">▦ <span>Dashboard</span></a>@endif
                <a class="flex items-center gap-3 rounded bg-white/15 px-3 py-2.5 font-semibold" href="/workspace">▦ <span>Issues</span></a>
                @if(auth()->user()->role === 'developer')<a class="flex items-center gap-3 rounded px-3 py-2.5 text-blue-100 hover:bg-white/10" href="/ai-agent">✦ <span>AI Agent</span></a>@endif
                @if(auth()->user()->role === 'manager')<a class="flex items-center gap-3 rounded px-3 py-2.5 text-blue-100 hover:bg-white/10" href="/add-developers">♙ <span>Team</span></a><a class="flex items-center gap-3 rounded px-3 py-2.5 text-blue-100 hover:bg-white/10" href="/create-project">⚙ <span>Project settings</span></a>@endif
            </nav>
            <div class="border-t border-white/15 p-4">
                <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-blue-200">{{ auth()->user()->email }}</p>
                <a href="/logout" class="mt-4 inline-block text-xs text-blue-100 hover:text-white">Log out →</a>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <header class="flex h-16 items-center justify-between border-b border-[#dfe1e6] bg-white px-5 lg:px-8">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded bg-[#0747a6] font-black text-white lg:hidden">A</div>
                    <span class="text-sm font-semibold text-[#5e6c84]">Projects</span>
                    <span class="text-[#c1c7d0]">/</span>
                    <span class="max-w-[180px] truncate text-sm font-semibold">{{ session('project_name', 'Project') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="goBack()" class="rounded border border-[#c1c7d0] bg-white px-3 py-2 text-sm font-semibold text-[#5e6c84] hover:bg-[#ebecf0]">← Back</button>
                    <span class="hidden text-sm text-[#5e6c84] sm:inline">Hi, {{ auth()->user()->name }}</span>
                    <a href="/logout" class="rounded border border-[#ffbdad] bg-[#ffebe6] px-3 py-2 text-sm font-semibold text-[#bf2600] hover:bg-[#ffbdad]">Log out</a>
                </div>
            </header>

            <div class="mx-auto max-w-[1600px] px-5 py-5 lg:px-8">
                @if(session('success'))
                    <div class="mb-5 rounded border border-[#abf5d1] bg-[#e3fcef] px-4 py-3 text-sm text-[#006644]">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-5 rounded border border-[#ffbdad] bg-[#ffebe6] px-4 py-3 text-sm text-[#bf2600]">{{ $errors->first() }}</div>
                @endif

                <div class="mb-7 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                    <div>
                        <p class="text-sm font-medium text-[#5e6c84]">Project / Issues</p>
                        <h1 class="mt-1 text-3xl font-semibold tracking-tight">Issues</h1>
                    </div>
                    <form id="issue-filters" method="GET" action="/workspace" class="flex flex-wrap gap-2">
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-2 text-[#6b778c]">⌕</span>
                            <input id="issue-search" data-live-search name="search" value="{{ request('search') }}" autocomplete="off" placeholder="Search issues..." aria-label="Search issues" class="w-56 rounded border border-[#c1c7d0] bg-white py-2 pl-8 pr-3 text-sm outline-none focus:border-[#4c9aff]">
                        </div>
                        <select name="priority" aria-label="Filter by priority" class="rounded border border-[#c1c7d0] bg-white px-3 py-2 text-sm"><option value="">All priorities</option>@foreach(['High','Medium','Low'] as $priority)<option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>@endforeach</select>
                        <button type="button" id="clear-filters" class="rounded bg-[#ebecf0] px-4 py-2 text-sm font-semibold hover:bg-[#dfe1e6]">Clear</button>
                    </form>
                </div>

                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Board</h2>
                </div>

                @php
                    $columns = [
                        'Open' => ['label' => 'TO DO', 'color' => 'bg-[#6554c0]'],
                        'In Progress' => ['label' => 'IN PROGRESS', 'color' => 'bg-[#0052cc]'],
                        'Resolved' => ['label' => 'DONE', 'color' => 'bg-[#36b37e]'],
                        'Closed' => ['label' => 'CLOSED', 'color' => 'bg-[#6b778c]'],
                    ];
                @endphp
                <div class="jira-scroll grid grid-cols-1 gap-3 overflow-x-auto pb-1 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($columns as $columnStatus => $column)
                        <section class="rounded bg-[#ebecf0] p-2.5">
                            <div class="mb-2 flex items-center justify-between px-1">
                                <div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full {{ $column['color'] }}"></span><h3 class="text-xs font-bold tracking-wide text-[#5e6c84]">{{ $column['label'] }}</h3></div>
                                <span class="text-xs font-semibold text-[#6b778c]">{{ $bugs->whereIn('status', $columnStatus === 'Open' ? ['Open', 'Duplicate'] : [$columnStatus])->count() }}</span>
                            </div>
                            <div class="space-y-3">
                                @forelse($bugs->whereIn('status', $columnStatus === 'Open' ? ['Open', 'Duplicate'] : [$columnStatus]) as $bug)
                                    <article class="rounded bg-white p-3 shadow-sm transition hover:shadow-md">
                                        <p class="text-xs font-semibold text-[#5e6c84]">ABT-{{ $bug->id }}</p>
                                        <h4 class="mt-2 text-sm font-semibold leading-5">{{ $bug->title }}</h4>
                                        <p class="mt-1 line-clamp-2 text-xs leading-5 text-[#6b778c]">{{ $bug->description }}</p>
                                        <div class="mt-3 flex items-center justify-between gap-2">
                                            <span class="rounded px-2 py-1 text-[11px] font-bold {{ $bug->priority === 'High' ? 'bg-[#ffebe6] text-[#bf2600]' : ($bug->priority === 'Low' ? 'bg-[#e6fcff] text-[#0065ff]' : 'bg-[#fff0b3] text-[#974f0c]') }}">{{ strtoupper($bug->priority) }}</span>
                                            <span class="max-w-[110px] truncate text-xs text-[#5e6c84]">{{ $bug->assigned_to ?: 'Unassigned' }}</span>
                                        </div>
                                        <form action="/api/bugs/override" method="POST" class="mt-2 border-t border-[#ebecf0] pt-2">@csrf<input type="hidden" name="id" value="{{ $bug->id }}"><select name="status" onchange="this.form.submit()" class="w-full rounded border border-[#dfe1e6] bg-white px-2 py-1 text-xs"><option value="Open" @selected($bug->status === 'Open')>To do</option><option value="In Progress" @selected($bug->status === 'In Progress')>In progress</option><option value="Resolved" @selected($bug->status === 'Resolved')>Done</option><option value="Closed" @selected($bug->status === 'Closed')>Closed</select></form>
                                    </article>
                                @empty
                                    <div class="rounded border border-dashed border-[#c1c7d0] p-3 text-center text-xs text-[#6b778c]">No issues</div>
                                @endforelse
                            </div>
                        </section>
                    @endforeach
                </div>

            </div>
        </main>
    </div>
    <script>
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '/dashboard';
            }
        }

        const filterForm = document.getElementById('issue-filters');
        const searchInput = document.querySelector('[data-live-search]');
        const priorityInput = filterForm.querySelector('[name="priority"]');
        const clearFilters = document.getElementById('clear-filters');
        let searchTimer;

        function applyFilters() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => filterForm.requestSubmit(), 300);
        }

        searchInput.addEventListener('input', applyFilters);
        priorityInput.addEventListener('change', () => filterForm.requestSubmit());
        clearFilters.addEventListener('click', () => {
            searchInput.value = '';
            priorityInput.value = '';
            filterForm.requestSubmit();
        });
    </script>
</body>
</html>
