@php
    $cc = $commandCenter;
    $ccTotal = max(1, (int) $cc['stats']['total']);
    $ccTrendMax = max(1, (int) $cc['trend']->max(fn ($day) => max($day['created'], $day['resolved'])));
@endphp
<section class="space-y-4" aria-labelledby="project-command-center-title">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div><p class="text-xs font-bold uppercase tracking-[.16em] text-indigo-600">{{ $project?->name ?? 'Project' }} · Project overview</p><h2 id="project-command-center-title" class="mt-1 text-xl font-extrabold tracking-tight text-slate-950 sm:text-2xl">Command Center</h2><p class="mt-1 text-sm text-slate-500">See the project’s issue queue, recent progress, and team workload.</p></div>
        @if($project)<a href="/workspace" class="inline-flex min-h-10 items-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-indigo-700 hover:border-indigo-200 hover:bg-indigo-50">View Kanban board →</a>@endif
    </div>
    <div class="grid grid-cols-2 gap-3 xl:grid-cols-6">
        @foreach([
            ['Total issues', $cc['stats']['total'], 'All project reports', 'text-slate-950', 'border-slate-200'],
            ['Active', $cc['stats']['active'], 'Currently in progress', 'text-indigo-700', 'border-indigo-200'],
            ['Unresolved', $cc['stats']['unresolved'], 'Not completed yet', 'text-amber-700', 'border-amber-200'],
            ['Overdue', $cc['stats']['overdue'], 'Open for over 7 days', 'text-rose-700', 'border-rose-200'],
            ['Resolved', $cc['stats']['resolved'], 'In a completed status', 'text-emerald-700', 'border-emerald-200'],
            ['High priority', $cc['stats']['high'], 'Needs close attention', 'text-rose-700', 'border-rose-200'],
        ] as [$label, $value, $hint, $color, $border])
            <article class="rounded-2xl border {{ $border }} bg-white p-4 shadow-sm sm:p-5"><p class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ $label }}</p><p class="mt-2 text-3xl font-extrabold {{ $color }}">{{ $value }}</p><p class="mt-1 text-xs text-slate-500">{{ $hint }}</p></article>
        @endforeach
    </div>
    <div class="grid gap-4 xl:grid-cols-12">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-6" aria-labelledby="cc-trend-title">
            <div><h3 id="cc-trend-title" class="font-bold text-slate-900">Reports and resolutions</h3><p class="mt-1 text-xs text-slate-500">Daily activity over the last 7 days</p></div>
            <div class="mt-5 grid h-36 grid-cols-7 items-end gap-3 border-b border-slate-100 px-1">
                @foreach($cc['trend'] as $day)
                    <div class="flex h-full flex-col items-center justify-end gap-2"><div class="flex h-28 w-full items-end justify-center gap-1" title="{{ $day['created'] }} reported, {{ $day['resolved'] }} resolved"><span class="w-2/5 rounded-t bg-indigo-500" style="height: {{ max(4, round($day['created'] / $ccTrendMax * 100)) }}%"></span><span class="w-2/5 rounded-t bg-sky-500" style="height: {{ max(4, round($day['resolved'] / $ccTrendMax * 100)) }}%"></span></div><span class="pb-2 text-[11px] text-slate-500">{{ $day['label'] }}</span></div>
                @endforeach
            </div>
            <div class="mt-3 flex gap-5 text-xs text-slate-600"><span><i class="mr-1.5 inline-block h-2.5 w-2.5 rounded-full bg-indigo-500"></i>Reported</span><span><i class="mr-1.5 inline-block h-2.5 w-2.5 rounded-full bg-sky-500"></i>Resolved</span></div>
        </section>
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-3" aria-labelledby="cc-priority-title">
            <h3 id="cc-priority-title" class="font-bold text-slate-900">Priority summary</h3><p class="mt-1 text-xs text-slate-500">Issues by priority</p>
            <div class="mt-5 space-y-4">
                @foreach(['High' => 'bg-rose-500', 'Medium' => 'bg-amber-400', 'Low' => 'bg-sky-500'] as $priority => $bar)
                    @php($count = (int) ($cc['priorityCounts'][$priority] ?? 0))
                    <div><div class="mb-1.5 flex justify-between text-sm"><span class="font-medium text-slate-700">{{ $priority }}</span><span class="font-semibold text-slate-800">{{ $count }} <span class="font-normal text-slate-400">({{ (int) round($count / $ccTotal * 100) }}%)</span></span></div><div class="h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $bar }}" style="width: {{ (int) round($count / $ccTotal * 100) }}%"></div></div></div>
                @endforeach
            </div>
        </section>
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-3" aria-labelledby="cc-workload-title">
            <h3 id="cc-workload-title" class="font-bold text-slate-900">Team workload</h3><p class="mt-1 text-xs text-slate-500">Active issues assigned to each teammate</p>
            <div class="mt-5 space-y-4">
                @forelse($cc['workloads'] as $workload)
                    <div><div class="mb-1.5 flex justify-between gap-2 text-sm"><span class="truncate font-medium text-slate-700">{{ $workload->assigned_to }}</span><span class="shrink-0 text-slate-500">{{ $workload->total }}</span></div><div class="h-2 overflow-hidden rounded-full bg-indigo-50"><div class="h-full rounded-full bg-indigo-500" style="width: {{ min(100, (int) $workload->total * 20) }}%"></div></div></div>
                @empty
                    <p class="rounded-xl border border-dashed border-slate-200 px-3 py-6 text-center text-sm text-slate-500">No active issues are assigned yet.</p>
                @endforelse
            </div>
        </section>
    </div>
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="cc-recent-title">
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4"><div><h3 id="cc-recent-title" class="font-bold text-slate-900">Recently updated issues</h3><p class="mt-1 text-xs text-slate-500">Latest changes across this project</p></div>@if($project)<a href="/workspace" class="text-sm font-semibold text-indigo-700 hover:underline">Open board →</a>@endif</div>
        <div class="divide-y divide-slate-100">
            @forelse($cc['recent'] as $issue)
                <a href="{{ route('bugs.show', $issue->id) }}" class="flex flex-wrap items-center justify-between gap-3 px-5 py-3.5 hover:bg-slate-50"><span class="min-w-0"><span class="block truncate text-sm font-semibold text-slate-800">{{ $issue->title }}</span><span class="mt-1 block font-mono text-[11px] text-slate-400">ABT-{{ $issue->id }} · {{ $issue->assigned_to ?: 'Unassigned' }}</span></span><span class="flex shrink-0 items-center gap-2"><span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">{{ $issue->status }}</span><span class="text-xs text-slate-400">{{ $issue->updated_at ? \Illuminate\Support\Carbon::parse($issue->updated_at)->diffForHumans() : '' }}</span></span></a>
            @empty
                <p class="px-5 py-8 text-center text-sm text-slate-500">No issues have been reported in this project yet.</p>
            @endforelse
        </div>
    </section>
</section>
