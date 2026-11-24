<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New project wizard · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="wizard-font min-h-screen bg-[#F5F6FA] text-[#111827] antialiased selection:bg-indigo-500 selection:text-white">
<style>
    .wizard-font { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
</style>
<main class="mx-auto w-full max-w-3xl px-4 py-7 sm:px-6 lg:py-9">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-3">
            <a href="/" class="text-base font-extrabold tracking-tight text-[#0F172A]">AutoBug<span class="text-indigo-600">Triage</span><span class="ml-2 rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-mono font-semibold text-indigo-700">AI v1.0</span></a>
        </div>
        <form action="/logout" method="POST">@csrf<button type="submit" aria-label="Log out" title="Log out" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700"><svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M12 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5"/></svg></button></form>
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
        </header>

        @if(isset($errors) && $errors->any())
            <div class="mb-5 flex items-start gap-2.5 rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-sm font-medium text-rose-800"><span aria-hidden="true" class="font-bold text-rose-500">⚠</span><span>{{ $errors->first() }}</span></div>
        @endif

        <form id="createProjectForm" action="/create-project" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="projectName" class="mb-1.5 block text-sm font-bold">Project Name</label>
                <input id="projectName" type="text" name="project_name" required maxlength="150" value="{{ old('project_name') }}" placeholder="e.g. Core SaaS Platform" class="w-full rounded-xl border border-[#CBD5E1] bg-white px-3.5 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10">
            </div>

            <div>
                <div class="mb-1.5"><label for="techStackInput" class="block text-sm font-bold">Technology Stack &amp; Frameworks</label></div>
                <input id="techStackInput" type="text" name="project_stack" required value="{{ old('project_stack') }}" placeholder="e.g. Laravel, React, PostgreSQL, Docker" class="w-full rounded-xl border border-[#CBD5E1] bg-white px-3.5 py-3 font-mono text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10">
                <p class="mb-2 mt-3 text-[11px] font-bold uppercase tracking-wide text-slate-500">Quick select stack chips:</p>
                <div class="flex flex-wrap gap-2" id="chipContainer" aria-label="Quick select technology stack">
                    @foreach(['Laravel', 'PHP', 'CSS', 'React', 'Vue.js', 'Python', 'FastAPI', 'PostgreSQL', 'MySQL', 'Docker', 'Node.js', 'TypeScript', 'TailwindCSS', 'Kotlin', 'C++', 'Java', 'SQL'] as $tech)
                        <button type="button" data-tech="{{ $tech }}" onclick="toggleChip('{{ $tech }}', this)" aria-pressed="false" class="tech-chip rounded-full border border-[#CBD5E1] bg-white px-3 py-1.5 text-xs font-medium text-[#334155] transition hover:border-indigo-400 hover:text-indigo-700">+ {{ $tech }}</button>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="mb-1.5"><label for="githubRepo" class="text-sm font-bold">🐙 GitHub Repository Link</label></div>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <input id="githubRepo" type="url" name="github_repo" value="{{ old('github_repo', '') }}" placeholder="https://github.com/your-username/your-repository" aria-describedby="githubScanStatus" class="min-w-0 flex-1 rounded-xl border border-[#CBD5E1] bg-white px-3.5 py-3 font-mono text-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10">
                    <button id="scanGithubRepo" type="button" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2.5 text-sm font-bold text-indigo-700 transition hover:bg-indigo-100 disabled:cursor-wait disabled:opacity-60"><span aria-hidden="true">⌕</span> Validate &amp; scan</button>
                </div>
                <div id="githubScanStatus" class="mt-3 hidden rounded-xl border border-slate-200 bg-slate-50 p-4" role="status" aria-live="polite">
                    <div class="flex items-center justify-between gap-3"><p id="githubScanTitle" class="text-sm font-semibold text-slate-800">Ready to scan</p><p id="githubScanCount" class="hidden shrink-0 text-sm font-bold text-indigo-700"></p></div>
                    <div id="githubScanProgressTrack" class="mt-3 hidden h-2 overflow-hidden rounded-full bg-slate-200" role="progressbar" aria-label="Repository scan progress" aria-valuemin="0" aria-valuemax="100"><div id="githubScanProgress" class="h-full w-0 rounded-full bg-gradient-to-r from-indigo-500 to-blue-500 transition-[width] duration-700"></div></div>
                    <p id="githubScanDetail" class="mt-2 text-xs text-slate-600"></p>
                </div>
            </div>

            <div class="border-t border-[#E2E8F0] pt-4">
                <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-indigo-700 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save &amp; Proceed to Team Setup →</button>
            </div>
        </form>
    </section>
</main>
<script>
    const githubRepoInput = document.getElementById('githubRepo');
    const scanGithubButton = document.getElementById('scanGithubRepo');
    const githubScanPanel = document.getElementById('githubScanStatus');
    const githubScanTitle = document.getElementById('githubScanTitle');
    const githubScanDetail = document.getElementById('githubScanDetail');
    const githubScanCount = document.getElementById('githubScanCount');
    const githubScanProgress = document.getElementById('githubScanProgress');
    const githubScanProgressTrack = document.getElementById('githubScanProgressTrack');
    const createProjectForm = document.getElementById('createProjectForm');
    let verifiedGithubRepo = '';
    let githubScanRequest = null;

    function showGithubScan(title, detail = '', state = 'loading') {
        githubScanPanel.classList.remove('hidden', 'border-emerald-200', 'bg-emerald-50', 'border-rose-200', 'bg-rose-50');
        githubScanPanel.classList.add(state === 'success' ? 'border-emerald-200' : state === 'error' ? 'border-rose-200' : 'border-slate-200');
        githubScanPanel.classList.add(state === 'success' ? 'bg-emerald-50' : state === 'error' ? 'bg-rose-50' : 'bg-slate-50');
        githubScanTitle.textContent = title;
        githubScanDetail.textContent = detail;
        githubScanTitle.className = `text-sm font-semibold ${state === 'success' ? 'text-emerald-800' : state === 'error' ? 'text-rose-800' : 'text-slate-800'}`;
    }

    async function scanGithubRepository() {
        const repoUrl = githubRepoInput.value.trim();
        if (!repoUrl) {
            showGithubScan('Add a GitHub link first.', 'The repository link can be left empty if you do not want to connect GitHub.', 'error');
            githubRepoInput.focus();
            return false;
        }
        if (githubScanRequest) return githubScanRequest;
        if (verifiedGithubRepo === repoUrl) return true;

        scanGithubButton.disabled = true;
        scanGithubButton.innerHTML = '<span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-indigo-300 border-t-indigo-700" aria-hidden="true"></span> Scanning…';
        githubScanProgressTrack.classList.remove('hidden');
        githubScanCount.classList.add('hidden');
        githubScanProgress.style.width = '12%';
        showGithubScan('Checking the GitHub link…', 'Making sure this is a repository we can read.');

        const progressSteps = [
            [35, 'Connecting to GitHub…', 'Confirming the repository exists and is accessible.'],
            [64, 'Reading the project files…', 'Counting source and configuration files.'],
            [86, 'Preparing the index…', 'Organizing the file tree for bug triage.'],
        ];
        let progressIndex = 0;
        const progressTimer = window.setInterval(() => {
            if (progressIndex >= progressSteps.length) return;
            const [progress, title, detail] = progressSteps[progressIndex++];
            githubScanProgress.style.width = `${progress}%`;
            showGithubScan(title, detail);
        }, 1100);

        githubScanRequest = (async () => {
            try {
                const response = await fetch('{{ route('projects.preview-github') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ github_repo: repoUrl }),
                });
                const result = await response.json();
                if (!response.ok || !result.success) throw new Error(result.error || 'Could not scan this repository. Check the link and try again.');

                verifiedGithubRepo = repoUrl;
                githubScanProgress.style.width = '100%';
                githubScanCount.textContent = `${Number(result.total_files).toLocaleString()} files found`;
                githubScanCount.classList.remove('hidden');
                const languageSummary = result.primary_language ? ` Main language: ${result.primary_language}.` : '';
                showGithubScan('Repository verified and scanned.', `${result.repo} · branch ${result.branch}.${languageSummary}`, 'success');
                return true;
            } catch (error) {
                verifiedGithubRepo = '';
                githubScanProgressTrack.classList.add('hidden');
                showGithubScan('Repository scan failed.', error.message, 'error');
                return false;
            } finally {
                window.clearInterval(progressTimer);
                scanGithubButton.disabled = false;
                scanGithubButton.innerHTML = '<span aria-hidden="true">⌕</span> Validate &amp; scan';
                githubScanRequest = null;
            }
        })();

        return githubScanRequest;
    }

    scanGithubButton.addEventListener('click', scanGithubRepository);
    githubRepoInput.addEventListener('input', () => {
        verifiedGithubRepo = '';
        if (githubRepoInput.value.trim()) {
            showGithubScan('Link changed.', 'We’ll scan this repository when you leave the field or select Validate & scan.');
            githubScanProgressTrack.classList.add('hidden');
            githubScanCount.classList.add('hidden');
        } else {
            githubScanPanel.classList.add('hidden');
        }
    });
    githubRepoInput.addEventListener('blur', () => {
        if (githubRepoInput.value.trim() && verifiedGithubRepo !== githubRepoInput.value.trim()) scanGithubRepository();
    });
    createProjectForm.addEventListener('submit', async event => {
        const repoUrl = githubRepoInput.value.trim();
        if (!repoUrl || verifiedGithubRepo === repoUrl) return;
        event.preventDefault();
        const verified = await scanGithubRepository();
        if (verified && verifiedGithubRepo === githubRepoInput.value.trim()) {
            if (event.submitter) createProjectForm.requestSubmit(event.submitter);
            else createProjectForm.requestSubmit();
        }
        else githubRepoInput.focus();
    });

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
