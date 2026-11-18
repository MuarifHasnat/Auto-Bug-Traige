<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workflow settings · {{ session('project_name', 'Workspace') }}</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    @include('components.app-sidebar', ['active' => 'workflow'])
    <main class="min-w-0 flex-1">
        <nav class="sticky top-0 z-10 flex min-h-[68px] flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-white/95 px-5 py-3 backdrop-blur lg:px-8" aria-label="Page header">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500"><a href="/workspace" class="hover:text-indigo-600">Workspace</a><span>/</span><span>{{ session('project_name', 'Project') }}</span><span>/</span><span class="text-indigo-600">Workflow settings</span></div>
            <div class="flex items-center gap-3">@include('components.project-switcher', ['variant' => 'mobile']) @include('components.profile-header')</div>
        </nav>
        <div class="px-5 py-8 lg:px-8">
            <div class="mx-auto max-w-6xl">
                <header class="mb-7 mt-1">
                    <p class="text-xs font-semibold text-slate-500">{{ session('project_name', 'Project') }} / Workflow settings</p>
                    <h1 class="mt-1 text-3xl font-extrabold">Workflow settings</h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500">Set the board columns and which status moves are allowed.</p>
                </header>

                @if(session('success'))<div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
                @if(isset($errors) && $errors->any())<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-800"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

                <form id="workflowSettingsForm" action="{{ route('workflow.save', [], false) }}" method="POST" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    @csrf
                    <div class="grid gap-x-8 gap-y-7 md:grid-cols-2">
                        <section aria-labelledby="board-statuses-title">
                            <div class="flex items-center justify-between gap-3"><div><h2 id="board-statuses-title" class="text-base font-bold">Board statuses</h2><p class="mt-1 text-xs text-slate-500">Order controls the board columns. The first status is used for new reports.</p></div><span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-bold text-indigo-700">2–12 statuses</span></div>
                            <textarea id="statusesField" name="statuses" required class="hidden">{{ old('statuses', $statuses->pluck('name')->implode("\n")) }}</textarea>
                            <div id="statusChips" class="mt-4 flex min-h-10 flex-wrap items-center gap-2" aria-live="polite"></div>
                            <div class="mt-3 flex gap-2"><input id="newStatusInput" maxlength="40" placeholder="Add a status" class="min-w-0 flex-1 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"><button id="addStatusButton" type="button" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">＋ Add</button></div>
                            <p id="statusHint" class="mt-2 text-xs text-slate-500">Duplicate is reserved. Keep statuses currently used by bugs.</p>
                        </section>
                        <section aria-labelledby="completed-statuses-title">
                            <h2 id="completed-statuses-title" class="text-base font-bold">Completed statuses</h2><p class="mt-1 text-xs text-slate-500">These stop counting as active work in team workload totals.</p>
                            <textarea id="doneStatusesField" name="done_statuses" class="hidden">{{ old('done_statuses', $statuses->where('is_done', true)->pluck('name')->implode("\n")) }}</textarea>
                            <div id="doneStatusChips" class="mt-4 flex min-h-10 flex-wrap items-center gap-2"></div>
                        </section>
                        <section class="md:col-span-2" aria-labelledby="allowed-transitions-title">
                            <div class="flex flex-wrap items-end justify-between gap-3"><div><h2 id="allowed-transitions-title" class="text-base font-bold">Allowed transitions</h2><p class="mt-1 text-xs text-slate-500">Only listed moves appear on issue cards and are accepted by the server.</p></div>
                                <div class="flex flex-wrap items-center gap-2"><select id="transitionFrom" aria-label="Transition from" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm"></select><span class="text-slate-400">→</span><select id="transitionTo" aria-label="Transition to" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm"></select><button id="addTransitionButton" type="button" class="rounded-xl border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">＋ Add move</button></div>
                            </div>
                            <textarea id="transitionsField" name="transitions" required class="hidden">{{ old('transitions', $transitions->map(fn ($edge) => $edge->from_status.' -> '.$edge->to_status)->implode("\n")) }}</textarea>
                            <div id="transitionCards" class="mt-4 grid gap-2 sm:grid-cols-2 xl:grid-cols-3"></div>
                        </section>
                    </div>
                    <div class="mt-7 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5"><p class="text-sm text-slate-500">Changes affect every member of this project.</p><button type="submit" class="rounded-xl bg-indigo-700 px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-indigo-600">Save workflow</button></div>
                </form>

                <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" aria-labelledby="repository-sync-title">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4"><div><h2 id="repository-sync-title" class="text-base font-bold text-slate-900">GitHub codebase sync</h2><p class="text-sm text-slate-500">Index your repository so the AI can point to the exact file.</p></div><span class="rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700">{{ $projectStack }}</span></div>
                    <form action="{{ route('projects.sync-github', [], false) }}" method="POST" class="space-y-3">@csrf
                        <label for="githubRepo" class="block text-sm font-semibold text-slate-800">GitHub repository</label>
                        <div class="flex flex-col gap-2 sm:flex-row"><input id="githubRepo" type="text" name="github_repo" value="{{ old('github_repo', $projectGithubRepo) }}" placeholder="https://github.com/your-username/your-repository" class="min-w-0 flex-1 rounded-xl border border-slate-300 px-3 py-2.5 font-mono text-sm text-slate-800 outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600"><button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-800 hover:bg-slate-50">{{ $projectGithubRepo ? '↻ Re-sync repository' : 'Connect repository' }}</button></div>
                        <p class="text-xs leading-5 text-slate-500">Repository sync indexes the project tree for AI file-location hints. Make sure the repository is accessible to the configured GitHub connection.</p>
                    </form>
                </section>
            </div>
        </div>
    </main>
</div>
<script>
(() => {
    const statusesField = document.getElementById('statusesField');
    const doneField = document.getElementById('doneStatusesField');
    const transitionsField = document.getElementById('transitionsField');
    const statusHost = document.getElementById('statusChips');
    const doneHost = document.getElementById('doneStatusChips');
    const transitionHost = document.getElementById('transitionCards');
    const fromSelect = document.getElementById('transitionFrom');
    const toSelect = document.getElementById('transitionTo');
    let statuses = statusesField.value.split(/\r?\n/).map(value => value.trim()).filter(Boolean);
    let completed = doneField.value.split(/\r?\n/).map(value => value.trim()).filter(Boolean);
    let transitions = transitionsField.value.split(/\r?\n/).map(value => value.trim()).filter(Boolean);

    const chipClass = name => name.toLowerCase() === 'duplicate' ? 'border-slate-200 bg-slate-100 text-slate-600' : completed.includes(name) ? 'border-emerald-100 bg-emerald-50 text-emerald-800' : 'border-indigo-100 bg-indigo-50 text-indigo-800';
    function syncFields() {
        statusesField.value = statuses.join('\n');
        doneField.value = completed.join('\n');
        transitionsField.value = transitions.join('\n');
    }
    function render() {
        statusHost.replaceChildren();
        statuses.forEach((name, index) => {
            const chip = document.createElement('span');
            chip.className = `inline-flex items-center gap-1 rounded-full border px-3 py-1.5 text-sm font-medium ${chipClass(name)}`;
            const label = document.createElement('span'); label.textContent = name; chip.append(label);
            if (name.toLowerCase() !== 'duplicate') {
                const reorder = document.createElement('button'); reorder.type = 'button'; reorder.className = 'ml-1 text-xs opacity-60 hover:opacity-100'; reorder.textContent = index === 0 ? '→' : '↔'; reorder.title = index === 0 ? 'Move to end' : 'Move earlier'; reorder.setAttribute('aria-label', `${reorder.title}: ${name}`);
                reorder.addEventListener('click', () => { const target = index === 0 ? statuses.length - 1 : index - 1; [statuses[index], statuses[target]] = [statuses[target], statuses[index]]; render(); }); chip.append(reorder);
                const remove = document.createElement('button'); remove.type = 'button'; remove.className = 'ml-0.5 text-base leading-none opacity-50 hover:text-rose-600 hover:opacity-100'; remove.textContent = '×'; remove.title = 'Remove status'; remove.setAttribute('aria-label', `Remove ${name}`); remove.addEventListener('click', () => { statuses = statuses.filter(item => item !== name); completed = completed.filter(item => item !== name); transitions = transitions.filter(edge => !edge.split(/\s*->\s*/).includes(name)); render(); }); chip.append(remove);
            }
            statusHost.append(chip);
        });
        doneHost.replaceChildren();
        statuses.filter(name => name.toLowerCase() !== 'duplicate').forEach(name => {
            const button = document.createElement('button'); const isDone = completed.includes(name); button.type = 'button'; button.className = `rounded-full border px-3 py-1.5 text-sm font-medium ${isDone ? 'border-emerald-100 bg-emerald-50 text-emerald-800' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'}`; button.textContent = `${isDone ? '✓ ' : ''}${name}`; button.setAttribute('aria-pressed', String(isDone)); button.addEventListener('click', () => { completed = isDone ? completed.filter(item => item !== name) : [...completed, name]; render(); }); doneHost.append(button);
        });
        [fromSelect, toSelect].forEach(select => { const old = select.value; select.replaceChildren(...statuses.filter(name => name.toLowerCase() !== 'duplicate').map(name => { const option = document.createElement('option'); option.value = name; option.textContent = name; return option; })); if (statuses.includes(old)) select.value = old; });
        transitionHost.replaceChildren();
        transitions.forEach((edge, index) => {
            const card = document.createElement('div'); card.className = 'flex min-h-12 items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm';
            const text = document.createElement('span'); text.className = 'font-medium text-slate-800'; text.textContent = edge;
            const remove = document.createElement('button'); remove.type = 'button'; remove.className = 'rounded-md px-2 py-1 text-slate-400 hover:bg-rose-50 hover:text-rose-600'; remove.textContent = '×'; remove.title = 'Remove transition'; remove.setAttribute('aria-label', `Remove transition ${edge}`); remove.addEventListener('click', () => { transitions.splice(index, 1); render(); }); card.append(text, remove); transitionHost.append(card);
        });
        syncFields();
    }
    function addStatus() {
        const input = document.getElementById('newStatusInput'); const value = input.value.trim();
        const normalized = value.toLocaleLowerCase();
        if (!value || value.length > 40 || /[<>]|->/.test(value) || statuses.some(item => item.toLocaleLowerCase() === normalized) || statuses.length >= 12) return;
        const duplicateIndex = statuses.findIndex(item => item.toLowerCase() === 'duplicate'); statuses.splice(duplicateIndex < 0 ? statuses.length : duplicateIndex, 0, value); input.value = ''; render(); input.focus();
    }
    document.getElementById('addStatusButton').addEventListener('click', addStatus);
    document.getElementById('newStatusInput').addEventListener('keydown', event => { if (event.key === 'Enter') { event.preventDefault(); addStatus(); } });
    document.getElementById('addTransitionButton').addEventListener('click', () => { const from = fromSelect.value; const to = toSelect.value; const edge = `${from} -> ${to}`; if (!from || !to || from === to || transitions.includes(edge)) return; transitions.push(edge); render(); });
    document.getElementById('workflowSettingsForm').addEventListener('submit', event => { syncFields(); if (!completed.length) { event.preventDefault(); document.getElementById('statusHint').textContent = 'Choose at least one completed status.'; document.getElementById('statusHint').className = 'mt-2 text-xs font-semibold text-rose-600'; } });
    render();
})();
</script>
</body>
</html>
