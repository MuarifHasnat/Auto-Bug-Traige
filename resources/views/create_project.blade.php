<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New project wizard · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased selection:bg-indigo-500 selection:text-white bg-dot-grid">
<main class="mx-auto w-full max-w-3xl px-4 py-8 sm:px-6 lg:py-10">
    <div class="mb-5 flex items-center justify-between gap-4">
        <a href="/" class="text-base font-extrabold tracking-tight text-[#0F172A]">AutoBug<span class="text-indigo-600">Triage</span></a>
        @include('components.profile-header')
    </div>

    <nav aria-label="Project setup progress" class="mb-6 rounded-2xl border border-[#E2E8F0] bg-white px-5 py-4 shadow-xs">
        <ol class="flex items-center justify-between gap-3 text-xs font-semibold sm:text-sm">
            <li aria-current="step" class="flex items-center gap-2 text-indigo-700"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-white">1</span><span>Project setup</span></li>
            <li class="mx-1 h-px flex-1 bg-slate-200" aria-hidden="true"></li>
            <li class="flex items-center gap-2 text-slate-500"><span class="flex h-6 w-6 items-center justify-center rounded-full border border-slate-300 bg-slate-50">2</span><span class="hidden sm:inline">Team and skills</span></li>
            <li class="mx-1 h-px flex-1 bg-slate-200" aria-hidden="true"></li>
            <li class="flex items-center gap-2 text-slate-500"><span class="flex h-6 w-6 items-center justify-center rounded-full border border-slate-300 bg-slate-50">3</span><span class="hidden sm:inline">Launch</span></li>
        </ol>
    </nav>

    <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm sm:p-8">
        <header class="mb-6">
            <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Configure your project</h1>
            <p class="mt-2 text-sm text-[#64748B]">The AI uses your tech stack to detect the bug language and category.</p>
        </header>

        @if(isset($errors) && $errors->any())
            <div class="mb-5 flex items-start gap-2.5 rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-sm font-medium text-rose-800"><span aria-hidden="true" class="font-bold text-rose-500">⚠</span><span>{{ $errors->first() }}</span></div>
        @endif

        <form action="/create-project" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="projectName" class="mb-2 block text-sm font-bold">Project name</label>
                <input id="projectName" type="text" name="project_name" required maxlength="150" value="{{ old('project_name') }}" placeholder="e.g. Core SaaS Platform" class="w-full rounded-xl border border-[#CBD5E1] bg-white px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10">
            </div>

            <div>
                <label for="techStackInput" class="mb-2 block text-sm font-bold">Technology stack</label>
                <input id="techStackInput" type="text" name="project_stack" required value="{{ old('project_stack') }}" placeholder="e.g. Laravel, React, PostgreSQL" class="w-full rounded-xl border border-[#CBD5E1] bg-white px-4 py-3 font-mono text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10">
                <div class="mt-3 flex flex-wrap gap-2" id="chipContainer" aria-label="Quick select technology stack">
                    @foreach(['Java', 'Python', 'React', 'Laravel', 'MySQL', 'PostgreSQL', 'Docker', 'Kotlin'] as $tech)
                        <button type="button" data-tech="{{ $tech }}" onclick="toggleChip('{{ $tech }}', this)" aria-pressed="false" class="tech-chip rounded-full border border-[#CBD5E1] bg-white px-4 py-2 text-sm font-medium text-[#334155] transition hover:border-indigo-400 hover:text-indigo-700">+ {{ $tech }}</button>
                    @endforeach
                </div>
            </div>

            <div>
                <label for="githubRepo" class="mb-2 block text-sm font-bold">GitHub repository link</label>
                <input id="githubRepo" type="url" name="github_repo" value="{{ old('github_repo', '') }}" placeholder="https://github.com/your-username/your-repository" class="w-full rounded-xl border border-[#CBD5E1] bg-white px-4 py-3 font-mono text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10">
                <p class="mt-2 text-xs leading-5 text-slate-500">The AI scans the repository so it can point to the exact file for each bug.</p>
            </div>

            <div class="border-t border-[#E2E8F0] pt-5">
                <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-indigo-700 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save and continue to team setup →</button>
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
</script>
</body>
</html>
