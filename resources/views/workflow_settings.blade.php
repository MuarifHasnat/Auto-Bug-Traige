<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workflow Settings · {{ session('project_name', 'Workspace') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased">
<main class="mx-auto max-w-5xl px-5 py-10">
    <a href="/workspace" class="text-sm font-bold text-indigo-600">← Back to board</a>
    <header class="mb-7 mt-4">
        <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">{{ session('project_name', 'Current project') }}</p>
        <h1 class="mt-1 text-3xl font-extrabold">Workflow settings</h1>
        <p class="mt-2 max-w-2xl text-sm text-slate-500">Set the board columns and decide which status moves are allowed. For example: To Do → In Progress → Review → Done.</p>
    </header>

    @if(session('success'))<div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-800"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <form action="{{ route('workflow.save') }}" method="POST" class="grid gap-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:grid-cols-2">
        @csrf
        <label class="block text-sm font-bold">Board statuses <span class="font-normal text-slate-500">(one per line, 2–12)</span>
            <textarea name="statuses" required rows="9" class="mt-2 w-full rounded-xl border border-slate-300 p-3 font-mono text-sm">{{ old('statuses', $statuses->pluck('name')->implode("\n")) }}</textarea>
            <span class="mt-2 block text-xs font-normal text-slate-500">Status names currently used by bugs must stay in the list. Their order controls board columns; the first status is used for new reports. Keep the reserved Duplicate status.</span>
        </label>
        <div class="space-y-5">
            <label class="block text-sm font-bold">Completed statuses <span class="font-normal text-slate-500">(one per line)</span>
                <textarea name="done_statuses" rows="3" class="mt-2 w-full rounded-xl border border-slate-300 p-3 font-mono text-sm">{{ old('done_statuses', $statuses->where('is_done', true)->pluck('name')->implode("\n")) }}</textarea>
                <span class="mt-2 block text-xs font-normal text-slate-500">These stop counting as active work in team workload totals.</span>
            </label>
            <label class="block text-sm font-bold">Allowed transitions <span class="font-normal text-slate-500">(one per line)</span>
                <textarea name="transitions" required rows="9" class="mt-2 w-full rounded-xl border border-slate-300 p-3 font-mono text-sm">{{ old('transitions', $transitions->map(fn ($edge) => $edge->from_status.' -> '.$edge->to_status)->implode("\n")) }}</textarea>
                <span class="mt-2 block text-xs font-normal text-slate-500">Only listed moves appear on issue cards and are accepted by the server.</span>
            </label>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5 md:col-span-2">
            <p class="text-xs text-slate-500">Changing workflow settings affects every member working in this project.</p>
            <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-500">Save workflow</button>
        </div>
    </form>

    <!-- Connected GitHub Repository & Codebase Pre-Indexer -->
    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2">
                <span class="text-xl">🐙</span>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">GitHub Codebase Synchronization</h2>
                    <p class="text-xs text-slate-500">Connect and pre-index your project's GitHub repository structure for automated, error-free file location.</p>
                </div>
            </div>
            <span class="text-[11px] font-mono-code font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 px-2.5 py-1 rounded-full">
                {{ session('project_stack', 'Multi-stack') }}
            </span>
        </div>

        <form action="{{ route('projects.sync-github') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-800 mb-1">GitHub Repository Link or Slug</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" name="github_repo" value="{{ old('github_repo', session('github_repo', 'https://github.com/MuarifHasnat/Auto-Bug-Traige')) }}" placeholder="https://github.com/owner/repository" 
                           class="flex-1 rounded-xl border border-slate-300 p-2.5 font-mono-code text-xs text-slate-800 outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-slate-900 to-indigo-900 hover:from-slate-800 hover:to-indigo-800 px-5 py-2.5 text-xs font-bold text-white shadow-xs transition active:scale-[0.99]">
                        <span>↻ Re-sync & Pre-Index Codebase</span>
                    </button>
                </div>
                <p class="mt-2 text-[11px] text-slate-500 leading-relaxed">
                    💡 <b>Deep Architectural Understanding:</b> রি-সিঙ্ক বাটনে ক্লিক করলে এজেন্ট গিটহাব থেকে সরাসরি আপনার রিপোজিটরির ডিরেক্টরি ট্রি স্ক্যান করে <b>UI Layers, Controllers, Services, ও Models</b> ইনডেক্স করে রাখবে—যাতে বাগ ফাইল লোকেটর কোনো ভুল ছাড়াই কোডের আসল কালপ্রিট ফাইল নির্দেশ করতে পারে।
                </p>
            </div>
        </form>
    </div>
</main>
</body>
</html>
