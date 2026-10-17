<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Agent · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="modern-saas">
<div class="flex min-h-screen">
    <aside class="hidden w-64 shrink-0 flex-col bg-[#0747a6] text-white lg:flex">
        <div class="flex h-16 items-center gap-3 border-b border-white/15 px-5"><div class="flex h-8 w-8 items-center justify-center rounded bg-white font-black text-[#0747a6]">A</div><b class="text-lg">AutoBugTriage</b></div>
        <div class="border-b border-white/15 px-4 py-5"><p class="text-xs uppercase text-blue-200">Project</p><p class="mt-2 truncate font-semibold">{{ session('project_name', 'Software Project') }}</p></div>
        <nav class="flex-1 space-y-1 p-3 text-sm">@if(auth()->user()->role === 'manager')<a class="flex rounded px-3 py-2.5 text-blue-100 hover:bg-white/10" href="/dashboard">▦ <span class="ml-3">Dashboard</span></a>@endif @if(auth()->user()->role !== 'tester')<a class="flex rounded px-3 py-2.5 text-blue-100 hover:bg-white/10" href="/workspace">▤ <span class="ml-3">Assigned issues</span></a>@endif<a class="flex rounded bg-white/15 px-3 py-2.5 font-semibold" href="/ai-agent">✦ <span class="ml-3">AI Agent</span></a>@if(auth()->user()->role === 'manager')<a class="flex rounded px-3 py-2.5 text-blue-100 hover:bg-white/10" href="/add-developers">♙ <span class="ml-3">Team</span></a>@endif</nav>
        <div class="border-t border-white/15 p-4"><p class="truncate text-sm">{{ auth()->user()->name }}</p><a href="/logout" class="mt-3 inline-block text-xs text-blue-100">Log out →</a></div>
    </aside>
    <main class="min-w-0 flex-1">
        <header class="flex h-16 items-center justify-between border-b border-[#dfe1e6] bg-white px-5 lg:px-8">
            <div><button type="button" onclick="goBack()" class="mr-3 rounded border border-[#c1c7d0] bg-white px-3 py-2 text-sm font-semibold text-[#5e6c84]">← Back</button><span class="text-sm text-[#5e6c84]">Projects /</span> <b class="text-sm">AI Agent</b></div>
            <div class="flex items-center gap-3"><a href="/logout" class="rounded border border-[#ffbdad] bg-[#ffebe6] px-3 py-2 text-sm font-semibold text-[#bf2600]">Log out</a><span class="rounded-full bg-[#e3fcef] px-3 py-1 text-xs font-bold text-[#006644]">● Active</span></div>
        </header>
        <div class="mx-auto max-w-5xl px-5 py-8 lg:px-10">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div><p class="text-sm text-[#5e6c84]">Automated triage</p><h1 class="mt-1 text-3xl font-semibold">AI Agent</h1><p class="mt-2 text-sm text-[#6b778c]">Tester submits reports; developer reviews the final implementation suggestion.</p></div>
                <span class="rounded-full bg-[#e6fcff] px-3 py-2 text-xs font-bold text-[#0052cc]">{{ ucfirst(auth()->user()->role) }} workspace</span>
            </div>
            @if(session('success'))<div class="mt-5 rounded border border-[#abf5d1] bg-[#e3fcef] px-4 py-3 text-sm text-[#006644]">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="mt-5 rounded border border-[#ffbdad] bg-[#ffebe6] px-4 py-3 text-sm text-[#bf2600]">{{ $errors->first() }}</div>@endif
            <div class="mt-7 grid gap-6 lg:grid-cols-2">
                <section class="rounded bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold">Tester report</h2><p class="mt-1 text-sm text-[#6b778c]">Submit a bug with code, screenshot or source file.</p>
                    <form action="/api/bugs/store" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">@csrf
                        <input name="title" required maxlength="200" placeholder="Issue summary" class="w-full rounded border border-[#c1c7d0] px-3 py-2.5 text-sm">
                        <textarea name="description" required rows="5" maxlength="5000" placeholder="What happened? Include steps to reproduce..." class="w-full rounded border border-[#c1c7d0] px-3 py-2.5 text-sm"></textarea>
                        <label class="block text-sm font-semibold text-[#5e6c84]">Programming language<select name="language" class="mt-2 w-full rounded border border-[#c1c7d0] px-3 py-2.5 text-sm"><option value="auto">Auto-detect from code</option><option>PHP</option><option>C++</option><option>C</option><option>Python</option><option>JavaScript</option><option>TypeScript</option><option>Java</option><option>SQL</option></select></label>
                        <textarea name="code_snippet" rows="8" maxlength="20000" placeholder="Paste code here (optional if you upload a source file)" class="w-full rounded border border-[#c1c7d0] px-3 py-2.5 font-mono text-xs"></textarea>
                        <div class="grid gap-3 sm:grid-cols-2"><label class="rounded border border-dashed border-[#c1c7d0] p-3 text-sm text-[#5e6c84]">Screenshot<input type="file" name="screenshot" accept="image/png,image/jpeg,image/webp" class="mt-2 block w-full text-xs"></label><label class="rounded border border-dashed border-[#c1c7d0] p-3 text-sm text-[#5e6c84]">Source file<input type="file" name="source_file" accept=".txt,.php,.cpp,.c,.h,.hpp,.py,.js,.ts,.java,.sql,.json,.xml,.css,.html" class="mt-2 block w-full text-xs"></label></div>
                        <button class="rounded bg-[#0052cc] px-5 py-2.5 text-sm font-semibold text-white">Run AI triage</button>
                    </form>
                </section>
                <section class="rounded bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold">{{ $mode === 'tester' ? 'Tester report' : 'Developer handoff' }}</h2>
                    @if(session('ai_result'))
                        @php($ai = session('ai_result'))
                        <div class="mt-5 flex flex-wrap gap-2"><span class="rounded-full bg-[#e6fcff] px-3 py-1 text-xs font-bold text-[#0065ff]">{{ $ai['code_help']['language'] ?? 'Unknown' }}</span><span class="rounded-full bg-[#f4f5f7] px-3 py-1 text-xs font-bold text-[#5e6c84]">Feature: {{ $ai['code_help']['feature'] ?? 'General' }}</span><span class="rounded-full bg-[#f4f5f7] px-3 py-1 text-xs font-bold text-[#5e6c84]">Confidence {{ number_format(($ai['code_help']['confidence'] ?? 0) * 100) }}%</span></div>
                        <p class="mt-4 rounded bg-[#e3fcef] p-3 text-sm font-semibold text-[#006644]">{{ $ai['code_help']['error_name'] ?? 'Analysis complete' }}</p>
                        <div class="mt-3 grid gap-2 text-sm sm:grid-cols-2"><div class="rounded bg-[#f4f5f7] p-3"><span class="text-xs text-[#6b778c]">Assigned developer</span><p class="font-semibold text-[#0052cc]">{{ $ai['assigned_to'] ?? 'Unassigned' }}</p></div><div class="rounded bg-[#f4f5f7] p-3"><span class="text-xs text-[#6b778c]">Attachment</span><p class="truncate font-semibold">{{ $ai['code_help']['attachment_note'] ?? 'None' }}</p></div></div>
                        <ol class="mt-4 list-decimal space-y-2 pl-5 text-sm text-[#5e6c84]">@foreach($ai['code_help']['steps'] ?? [] as $step)<li>{{ $step }}</li>@endforeach</ol>
                        @if($mode === 'tester')<div class="mt-6 rounded bg-[#fff7d6] p-4 text-sm text-[#5e6c84]"><b>Next step:</b> The assigned developer will review and implement the final fix. Use the diagnosis above to verify the bug after resolution.</div>@endif
                        @if($mode === 'developer' && !empty($ai['code_help']['diff']))<details class="mt-6"><summary class="cursor-pointer text-sm font-semibold text-[#0052cc]">View code diff</summary><textarea readonly spellcheck="false" class="mt-2 min-h-[150px] w-full rounded bg-[#172b4d] p-4 font-mono text-xs leading-5 text-[#b3d4ff]">{{ $ai['code_help']['diff'] }}</textarea></details>@endif
                        @if($mode === 'developer' && !empty($ai['code_help']['fixed_code']))<h3 class="mt-6 text-sm font-semibold">Complete suggested fix</h3><textarea readonly spellcheck="false" class="mt-2 min-h-[280px] w-full rounded bg-[#172b4d] p-4 font-mono text-xs leading-5 text-[#b3d4ff]">{{ $ai['code_help']['fixed_code'] }}</textarea>@endif
                    @else
                        <div class="mt-5 rounded bg-[#f4f5f7] p-4 text-sm text-[#6b778c]">Run a diagnosis to see the report.</div>
                    @endif
                </section>
            </div>
            @if($mode === 'developer')
                <section class="mt-6 rounded bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold">Assigned AI suggestions</h2>
                    <p class="mt-1 text-sm text-[#6b778c]">Implementation guidance for issues assigned to you.</p>
                    <div class="mt-5 space-y-4">
                        @forelse($assignedBugs as $assignedBug)
                            @php($suggestion = $assignedBug->triage['code_help'] ?? [])
                            <article class="rounded border border-[#dfe1e6] p-4">
                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <div><p class="text-xs font-semibold text-[#5e6c84]">ABT-{{ $assignedBug->id }}</p><h3 class="mt-1 font-semibold">{{ $assignedBug->title }}</h3></div>
                                    <span class="rounded bg-[#e6fcff] px-2 py-1 text-xs font-bold text-[#0052cc]">{{ $assignedBug->priority }}</span>
                                </div>
                                <p class="mt-2 text-sm text-[#5e6c84]">{{ $suggestion['error_name'] ?? 'Analysis complete' }}</p>
                                @if(!empty($suggestion['steps']))
                                    <ol class="mt-3 list-decimal space-y-1 pl-5 text-sm text-[#5e6c84]">@foreach($suggestion['steps'] as $step)<li>{{ $step }}</li>@endforeach</ol>
                                @endif
                                @if(!empty($suggestion['fixed_code']))
                                    <h4 class="mt-4 text-sm font-semibold">Complete suggested fix</h4>
                                    <textarea readonly spellcheck="false" class="mt-2 min-h-[180px] w-full rounded bg-[#172b4d] p-4 font-mono text-xs leading-5 text-[#b3d4ff]">{{ $suggestion['fixed_code'] }}</textarea>
                                @endif
                                @if(!empty($suggestion['diff']))
                                    <details class="mt-3"><summary class="cursor-pointer text-sm font-semibold text-[#0052cc]">View code diff</summary><textarea readonly spellcheck="false" class="mt-2 min-h-[120px] w-full rounded bg-[#172b4d] p-4 font-mono text-xs leading-5 text-[#b3d4ff]">{{ $suggestion['diff'] }}</textarea></details>
                                @endif
                            </article>
                        @empty
                            <p class="rounded border border-dashed border-[#c1c7d0] p-4 text-sm text-[#6b778c]">No AI suggestions are assigned to you yet.</p>
                        @endforelse
                    </div>
                </section>
            @endif
        </div>
    </main>
</div>
<script>function goBack(){if(window.history.length>1){window.history.back();}else{window.location.href='/dashboard';}}</script>
</body>
</html>
