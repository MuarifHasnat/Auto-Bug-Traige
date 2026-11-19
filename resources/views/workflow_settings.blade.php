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
        <div class="px-5 py-6 lg:px-8">
            <div class="mx-auto max-w-6xl">
                <header class="mb-4 mt-1">
                    <a href="/workspace" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">← Back to board</a>
                    <p class="mt-4 text-xs font-bold uppercase tracking-wide text-indigo-600">{{ strtoupper(session('project_name', 'Project')) }}</p>
                    <h1 class="mt-0.5 text-3xl font-extrabold">Workflow settings</h1>
                    <p class="mt-1 max-w-3xl text-sm text-slate-600">Set the board columns and decide which status moves are allowed. For example: To Do → In Progress → Review → Done.</p>
                </header>

                @if(session('success'))<div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
                @if(isset($errors) && $errors->any())<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-800"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

                <form id="workflowSettingsForm" action="{{ route('workflow.save', [], false) }}" method="POST" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    @csrf
                    <div class="grid gap-x-5 gap-y-5 md:grid-cols-2">
                        <section aria-labelledby="board-statuses-title">
                            <label for="statusesField" id="board-statuses-title" class="block text-sm font-bold">Board statuses <span class="font-normal text-slate-600">(one per line, 2–12)</span></label>
                            <textarea id="statusesField" name="statuses" required rows="6" class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white p-3 font-mono text-sm leading-5 text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ old('statuses', $statuses->pluck('name')->implode("\n")) }}</textarea>
                            <p class="mt-2 text-xs leading-4 text-slate-600">Status names currently used by bugs must stay in the list. Their order controls board columns; the first status is used for new reports. Keep the reserved Duplicate status.</p>
                        </section>
                        <section aria-labelledby="completed-statuses-title">
                            <label for="doneStatusesField" id="completed-statuses-title" class="block text-sm font-bold">Completed statuses <span class="font-normal text-slate-600">(one per line)</span></label>
                            <textarea id="doneStatusesField" name="done_statuses" rows="2" class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white p-3 font-mono text-sm leading-5 text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ old('done_statuses', $statuses->where('is_done', true)->pluck('name')->implode("\n")) }}</textarea>
                            <p class="mt-2 text-xs leading-4 text-slate-600">These stop counting as active work in team workload totals.</p>
                            <label for="transitionsField" id="allowed-transitions-title" class="mt-4 block text-sm font-bold">Allowed transitions <span class="font-normal text-slate-600">(one per line)</span></label>
                            <textarea id="transitionsField" name="transitions" required rows="6" class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white p-3 font-mono text-sm leading-5 text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ old('transitions', $transitions->map(fn ($edge) => $edge->from_status.' → '.$edge->to_status)->implode("\n")) }}</textarea>
                            <p class="mt-2 text-xs leading-4 text-slate-600">Only listed moves appear on issue cards and are accepted by the server.</p>
                        </section>
                    </div>
                    <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4"><p class="text-sm text-slate-600">Changing workflow settings affects every member working in this project.</p><button type="submit" class="rounded-xl bg-indigo-700 px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-indigo-600">Save workflow</button></div>
                </form>

                <section class="mt-4 rounded-2xl border border-slate-200 bg-white p-4 sm:p-5" aria-labelledby="repository-sync-title">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="min-w-0 flex-1"><h2 id="repository-sync-title" class="text-base font-bold text-slate-900">GitHub Codebase Synchronization</h2><p class="text-sm text-slate-600">Connect and pre-index your project repository for automated, error-free file location.</p></div>
                        <div class="flex flex-wrap gap-2" aria-label="Project technology stack">
                            @foreach(array_filter(array_map('trim', explode(',', $projectStack))) as $technology)
                                <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-800">{{ $technology }}</span>
                            @endforeach
                        </div>
                    </div>
                    <form action="{{ route('projects.sync-github', [], false) }}" method="POST" class="mt-3 flex flex-col gap-2 sm:flex-row">@csrf
                        <label for="githubRepo" class="sr-only">GitHub repository link</label><input id="githubRepo" type="text" name="github_repo" value="{{ old('github_repo', $projectGithubRepo) }}" placeholder="https://github.com/your-username/your-repository" class="min-w-0 flex-1 rounded-xl border border-slate-300 px-3 py-2.5 font-mono text-sm text-slate-800 outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                        <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-800 hover:bg-slate-50">{{ $projectGithubRepo ? 'Re-sync repository' : 'Connect repository' }}</button>
                    </form>
                </section>
            </div>
        </div>
    </main>
</div>
</body>
</html>
