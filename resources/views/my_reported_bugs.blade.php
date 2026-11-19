<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>My Reported Bugs · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <link rel="stylesheet" href="/css/tester-theme.css">
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/js/app.js')
    @endif
    <style>
        :root { color-scheme: light; }
        body { background: #f5f7fb; color: #0f172a; }
        :focus-visible { outline: 2px solid #4f46e5; outline-offset: 2px; }
    </style>
</head>
<body class="min-h-screen antialiased">
<div class="flex min-h-screen">
    @include('components.app-sidebar', ['active' => 'my-reports'])
    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-20 flex min-h-[68px] items-center justify-between gap-4 border-b border-slate-200 bg-white/95 px-5 py-3 backdrop-blur sm:px-7">
            <div class="min-w-0 truncate text-xs font-semibold text-slate-500"><a href="/dashboard" class="hover:text-indigo-700">Workspace</a><span class="mx-2 text-slate-300">/</span><span>{{ $project?->name ?? 'Project' }}</span><span class="mx-2 text-slate-300">/</span><span class="text-indigo-700">My Reported Bugs</span></div>
            <div class="flex shrink-0 items-center gap-2.5">
                <a href="/notifications" aria-label="Notifications" class="hidden h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 sm:flex">♧</a>
                @include('components.profile-header')
            </div>
        </header>

        <div class="min-h-[calc(100vh-68px)] px-4 py-6 sm:px-6 xl:px-8">
            <div class="mx-auto max-w-[1500px] space-y-5">
                <section class="flex flex-wrap items-center justify-between gap-4">
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">My Reported Bugs</h1>
                    <a href="/ai-agent" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-indigo-700 px-5 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-600">＋ Report Bug</a>
                </section>

                <section aria-label="Filter reported bugs" class="rounded-2xl border border-slate-200 bg-white p-3.5 shadow-sm">
                    <form action="{{ route('my-reports.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                        <label class="sr-only" for="myReportsSearch">Search my bugs</label>
                        <input id="myReportsSearch" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search my bugs" class="min-w-[210px] flex-1 rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-indigo-500 sm:max-w-xs">
                        <label class="sr-only" for="myReportsStatus">All statuses</label>
                        <select id="myReportsStatus" name="status" class="rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800"><option value="">All statuses</option>@foreach($workflowStatuses as $workflowStatus)<option value="{{ $workflowStatus->name }}" @selected(($filters['status'] ?? '') === $workflowStatus->name)>{{ $workflowStatus->name }}</option>@endforeach</select>
                        <label class="sr-only" for="myReportsPriority">All priorities</label>
                        <select id="myReportsPriority" name="priority" class="rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800"><option value="">All priorities</option>@foreach(['High','Medium','Low'] as $priority)<option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>{{ $priority }}</option>@endforeach</select>
                        <label class="sr-only" for="myReportsDate">Reported date</label>
                        <input id="myReportsDate" type="date" name="date" value="{{ $filters['date'] ?? '' }}" class="rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700">
                        @if($selectedReport)<input type="hidden" name="selected" value="{{ $selectedReport->id }}">@endif
                        <button type="submit" class="min-h-11 rounded-xl bg-indigo-700 px-5 text-sm font-bold text-white hover:bg-indigo-600">Apply</button>
                    </form>
                </section>

                @if(session('success'))<div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif

                <div class="grid grid-cols-1 items-start gap-4 2xl:grid-cols-12">
                    <section class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm 2xl:col-span-8" aria-label="Reported bug list">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] text-left">
                                <thead class="border-b border-slate-200 text-[10px] font-semibold uppercase tracking-wide text-slate-500"><tr><th scope="col" class="px-5 py-4">Title</th><th scope="col" class="px-3 py-4">Priority</th><th scope="col" class="px-3 py-4">Status</th><th scope="col" class="px-3 py-4">File found by AI</th><th scope="col" class="px-3 py-4">Assignee</th><th scope="col" class="px-5 py-4">Reported</th></tr></thead>
                                <tbody class="divide-y divide-slate-200">
                                @forelse($reports as $report)
                                    @php($rowTriage = json_decode($report->triage_result ?: '', true) ?: [])
                                    @php($rowFiles = data_get($rowTriage, 'code_help.suspected_files', data_get($rowTriage, 'suspected_files', [])))
                                    @php($rowFirstFile = is_array($rowFiles) ? ($rowFiles[0] ?? null) : null)
                                    @php($rowFile = is_array($rowFirstFile) ? ($rowFirstFile['path'] ?? $rowFirstFile['file'] ?? null) : $rowFirstFile)
                                    @php($rowFile = $rowFile ?: $report->source_file_path ?: $report->source_file_name)
                                    @php($rowUrl = route('my-reports.index', array_merge(request()->query(), ['selected' => $report->id])))
                                    <tr class="{{ (int) ($selectedReport?->id ?? 0) === (int) $report->id ? 'bg-indigo-50/70' : 'hover:bg-slate-50' }} transition">
                                        <th scope="row" class="max-w-[190px] px-5 py-3.5 text-sm font-bold leading-5 text-slate-900"><a href="{{ $rowUrl }}" class="block hover:text-indigo-700">{{ $report->title }}</a></th>
                                        <td class="whitespace-nowrap px-3 py-3.5 text-xs font-extrabold @class(['text-rose-600' => $report->priority === 'High', 'text-amber-700' => $report->priority === 'Medium', 'text-blue-700' => $report->priority === 'Low', 'text-slate-600' => !in_array($report->priority, ['High','Medium','Low'], true)])">{{ strtoupper($report->priority) }}</td>
                                        <td class="whitespace-nowrap px-3 py-3.5"><span class="rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700">{{ $report->status }}</span></td>
                                        <td class="max-w-[190px] px-3 py-3.5"><span class="block truncate font-mono text-xs text-slate-800" title="{{ $rowFile ?: 'No file identified' }}">{{ $rowFile ?: 'Not identified' }}</span></td>
                                        <td class="whitespace-nowrap px-3 py-3.5 text-xs text-slate-700">{{ $report->assigned_to ?: '—' }}</td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-xs text-slate-600">{{ \Illuminate\Support\Carbon::parse($report->created_at)->format('M d') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-5 py-12 text-center"><p class="font-semibold text-slate-800">No reported bugs found</p><p class="mt-1 text-xs text-slate-500">Change your filters or submit a bug report.</p></td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm 2xl:col-span-4">
                        @if($selectedReport)
                            <div class="flex items-start justify-between gap-3"><div class="min-w-0"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">ABT-{{ $selectedReport->id }}</p><h2 class="mt-1 text-base font-bold leading-snug text-slate-900">{{ $selectedReport->title }}</h2></div><a href="{{ route('bugs.show', $selectedReport->id) }}" class="shrink-0 text-xs font-semibold text-indigo-700 hover:underline">Details ↗</a></div>
                            <span class="mt-3 inline-flex rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700">{{ $selectedReport->status }}</span>
                            <div class="mt-4 border-t border-slate-100 pt-4"><h3 class="text-xs font-bold text-slate-900">AI diagnosis</h3><p class="mt-2 text-sm leading-6 text-slate-600">{{ $selectedDiagnosis ? \Illuminate\Support\Str::limit($selectedDiagnosis, 260) : \Illuminate\Support\Str::limit($selectedReport->description ?: 'AI analysis details are not available for this report yet.', 260) }}</p></div>
                            <div class="mt-4"><h3 class="text-xs font-bold text-slate-900">File</h3><p class="mt-2 break-all rounded-lg bg-slate-900 px-3 py-2.5 font-mono text-xs text-slate-100">{{ $selectedFile ?: ($selectedReport->source_file_path ?: ($selectedReport->source_file_name ?: 'No file identified')) }}</p></div>
                            <div class="mt-4"><h3 class="text-xs font-bold text-slate-900">Comments</h3>
                                @if($selectedComments->isNotEmpty())<div class="mt-2 max-h-28 space-y-2 overflow-y-auto">@foreach($selectedComments as $comment)<p class="rounded-lg bg-slate-50 px-3 py-2 text-xs leading-5 text-slate-600"><strong class="text-slate-800">{{ $comment->author_name }}</strong>: {{ $comment->body }}</p>@endforeach</div>@endif
                                <form action="{{ route('bugs.comments') }}" method="POST" class="mt-2 space-y-2">@csrf<input type="hidden" name="bug_id" value="{{ $selectedReport->id }}"><label class="sr-only" for="reportComment">Add a comment for the developer</label><textarea id="reportComment" name="body" rows="2" maxlength="2000" required placeholder="Add a comment for the developer" class="w-full resize-y rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-indigo-500"></textarea><button type="submit" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Add comment</button></form>
                            </div>
                            <a href="{{ route('bugs.show', $selectedReport->id) }}" class="mt-3 flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Open in Full Page</a>
                        @else
                            <div class="py-10 text-center"><h2 class="font-bold text-slate-900">Select a reported bug</h2><p class="mt-1 text-sm text-slate-500">Choose a row to see its AI diagnosis, file, and comments.</p></div>
                        @endif
                    </aside>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
