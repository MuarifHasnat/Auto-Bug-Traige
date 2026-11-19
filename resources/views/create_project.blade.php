<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New project wizard · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F5F6FA] text-[#111827] antialiased selection:bg-indigo-500 selection:text-white">
<main class="mx-auto w-full max-w-3xl px-4 py-7 sm:px-6 lg:py-9">
    <div class="mb-4 flex items-center justify-between gap-4">
        <a href="/" class="text-base font-extrabold tracking-tight text-[#0F172A]">AutoBug<span class="text-indigo-600">Triage</span><span class="ml-2 rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-mono font-semibold text-indigo-700">AI v1.0</span></a>
        <a href="/logout" class="rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50">Sign out</a>
    </div>

    <nav aria-label="Project setup progress" class="mb-4 rounded-2xl border border-[#E2E8F0] bg-white px-4 py-3.5 shadow-xs sm:px-5">
        <ol class="flex items-center justify-between gap-2 text-[11px] font-semibold sm:text-sm">
            <li aria-current="step" class="whitespace-nowrap text-indigo-700">1 Project Setup</li>
            <li class="text-slate-600">2 Team &amp; Skill Mapping</li>
            <li class="text-right text-slate-600">3 Workspace Launch</li>
        </ol>
    </nav>

    <section class="rounded-2xl border border-[#E2E8F0] bg-white p-5 shadow-sm sm:p-7">
        <header class="mb-5">
            <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">Manager: {{ auth()->user()->name }}</span>
            <h1 class="mt-3 text-2xl font-extrabold tracking-tight sm:text-3xl">Configure Your Project Environment</h1>
            <p class="mt-1.5 text-sm text-[#475569]">Define project identity and tech stack dependencies for precision AI triage matching.</p>
        </header>

        @if(isset($errors) && $errors->any())
            <div class="mb-5 flex items-start gap-2.5 rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-sm font-medium text-rose-800"><span aria-hidden="true" class="font-bold text-rose-500">⚠</span><span>{{ $errors->first() }}</span></div>
        @endif

        <form action="/create-project" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="projectName" class="mb-1.5 block text-sm font-bold">Project Name</label>
                <input id="projectName" type="text" name="project_name" required maxlength="150" value="{{ old('project_name') }}" placeholder="e.g. Core SaaS Platform" class="w-full rounded-xl border border-[#CBD5E1] bg-white px-3.5 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10">
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between gap-2"><label for="techStackInput" class="block text-sm font-bold">Technology Stack &amp; Frameworks</label><span class="text-right font-mono text-[10px] font-semibold text-indigo-600 sm:text-xs">Selected chips auto-fill below</span></div>
                <input id="techStackInput" type="text" name="project_stack" required value="{{ old('project_stack') }}" placeholder="e.g. Laravel, React, PostgreSQL, Docker" class="w-full rounded-xl border border-[#CBD5E1] bg-white px-3.5 py-3 font-mono text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10">
                <p class="mb-2 mt-3 text-[11px] font-bold uppercase tracking-wide text-slate-500">Quick select stack chips:</p>
                <div class="flex flex-wrap gap-2" id="chipContainer" aria-label="Quick select technology stack">
                    @foreach(['Laravel', 'React', 'Vue.js', 'Python', 'FastAPI', 'PostgreSQL', 'MySQL', 'Docker', 'Node.js', 'TypeScript', 'TailwindCSS', 'Kotlin', 'C++', 'Java', 'SQL'] as $tech)
                        <button type="button" data-tech="{{ $tech }}" onclick="toggleChip('{{ $tech }}', this)" aria-pressed="false" class="tech-chip rounded-full border border-[#CBD5E1] bg-white px-3 py-1.5 text-xs font-medium text-[#334155] transition hover:border-indigo-400 hover:text-indigo-700">+ {{ $tech }}</button>
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl border border-amber-200 bg-amber-50/80 p-3 text-xs leading-5 text-amber-900">
                <b>AI Agent Tip:</b> The agent uses your selected stack to identify likely bug languages and categories.
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between gap-2"><label for="githubRepo" class="text-sm font-bold">🐙 GitHub Repository Link</label><span class="rounded-full bg-emerald-50 px-3 py-1 font-mono text-[10px] font-semibold text-emerald-700">Auto Pre-Indexer</span></div>
                <input id="githubRepo" type="url" name="github_repo" value="{{ old('github_repo', '') }}" placeholder="https://github.com/your-username/your-repository" class="w-full rounded-xl border border-[#CBD5E1] bg-white px-3.5 py-3 font-mono text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10">
                <p class="mt-2 text-xs leading-5 text-slate-600">Link a repository to scan its structure and index source files for accurate AI file-location hints.</p>
            </div>

            <div class="border-t border-[#E2E8F0] pt-4">
                <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-indigo-700 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save &amp; Proceed to Team Setup →</button>
            </div>
        </form>
    </section>
</main>
<script>
    function toggleChip(tech, button) {
        const input = document.getElementById('techStackInput');
        const selected = input.value.split(',').map(item => item.trim()).filter(Boolean);
        const existing = selected.findIndex(item => item.toLowerCase() === tech.toLowerCase());
        if (existing >= 0) selected.splice(existing, 1); else selected.push(tech);
        input.value = selected.join(', ');
        const active = existing < 0;
        button.setAttribute('aria-pressed', String(active));
        button.textContent = `${active ? '✓ ' : '+ '}${tech}`;
        button.classList.toggle('border-indigo-600', active);
        button.classList.toggle('bg-indigo-600', active);
        button.classList.toggle('text-white', active);
        button.classList.toggle('text-[#334155]', !active);
    }
    document.getElementById('techStackInput').addEventListener('input', event => {
        const values = event.target.value.split(',').map(item => item.trim().toLowerCase());
        document.querySelectorAll('.tech-chip').forEach(button => {
            const active = values.includes(button.dataset.tech.toLowerCase());
            button.setAttribute('aria-pressed', String(active));
            button.textContent = `${active ? '✓ ' : '+ '}${button.dataset.tech}`;
            button.classList.toggle('border-indigo-600', active);
            button.classList.toggle('bg-indigo-600', active);
            button.classList.toggle('text-white', active);
            button.classList.toggle('text-[#334155]', !active);
        });
    });
    document.getElementById('techStackInput').dispatchEvent(new Event('input', { bubbles: true }));
</script>
</body>
</html>
