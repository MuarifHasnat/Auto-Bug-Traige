<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoBugTriage · Bug report triage for QA and engineering teams</title>
    <meta name="description" content="Turn bug reports into reviewable next steps. See reproduction details, syntax-check clues, and likely repository files in one place.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        :focus-visible { outline: 3px solid #4f46e5; outline-offset: 3px; }
        .glow-effect {
            box-shadow: 0 0 50px -10px rgba(99, 102, 241, 0.25);
        }
        .gradient-border {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(14, 165, 233, 0.1));
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto !important; }
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] bg-dot-grid selection:bg-indigo-500 selection:text-white font-sans antialiased">

    <!-- Top Sticky Glassmorphism Header -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-[#E2E8F0] transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-2.5 group">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-black shadow-md shadow-indigo-600/20 group-hover:scale-105 transition">
                    ⚡
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-extrabold tracking-tight text-[#0F172A]">
                        AutoBug<span class="text-indigo-600">Triage</span>
                    </span>
                    <span class="hidden sm:inline-flex rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                        Product preview
                    </span>
                </div>
            </a>

            <!-- Nav Links -->
            <nav aria-label="Main navigation" class="hidden md:flex items-center gap-7 text-xs font-bold text-slate-700">
                <a href="#features" class="rounded px-1 py-2 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600">Features</a>
                <a href="#workflow" class="rounded px-1 py-2 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600">Workflow</a>
                <a href="#demo" class="rounded-full border border-amber-300 bg-amber-50 px-3 py-2 text-amber-900 hover:bg-amber-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Demo</a>
                <details class="group relative">
                    <summary class="min-h-10 cursor-pointer list-none rounded px-2 py-2 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600">More <span aria-hidden="true" class="ml-1 group-open:inline-block group-open:rotate-180">⌄</span></summary>
                    <div class="absolute right-0 top-full z-50 mt-2 w-52 rounded-xl border border-slate-200 bg-white p-1.5 text-left shadow-xl">
                        <a class="block rounded-lg px-3 py-2.5 hover:bg-indigo-50 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600" href="#comparison">What it does</a>
                        <a class="block rounded-lg px-3 py-2.5 hover:bg-indigo-50 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600" href="#roles">For teams</a>
                        <a class="block rounded-lg px-3 py-2.5 hover:bg-indigo-50 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600" href="#requirements">Setup requirements</a>
                        <a class="block rounded-lg px-3 py-2.5 hover:bg-indigo-50 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600" href="#faq">FAQ</a>
                        <a class="block rounded-lg px-3 py-2.5 hover:bg-indigo-50 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-600" href="#data-policy">Privacy &amp; data</a>
                    </div>
                </details>
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="/login" class="inline-flex items-center rounded-xl border border-[#E2E8F0] bg-white px-3.5 py-2 text-xs font-bold text-[#0F172A] hover:bg-[#F1F5F9] transition">
                        Sign In
                    </a>
                    <a href="{{ auth()->user()->role === 'manager' ? '/dashboard' : (auth()->user()->role === 'developer' ? '/workspace' : '/ai-agent') }}"
                       class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-indigo-600/30 transition hover:scale-[1.02]">
                        <span>Open Workspace</span>
                        <span>→</span>
                    </a>
                @else
                    <a href="/login" class="inline-flex items-center rounded-xl border border-[#E2E8F0] bg-white px-3.5 py-1.5 text-xs font-bold text-[#0F172A] hover:bg-[#F1F5F9] transition">
                        Sign In
                    </a>
                    <a href="/signup" class="inline-flex min-h-11 items-center gap-1 rounded-xl bg-gradient-to-r from-indigo-700 to-blue-700 hover:from-indigo-800 hover:to-blue-800 px-4 py-2.5 text-xs font-bold text-white shadow-sm shadow-indigo-600/30 transition hover:scale-[1.02] focus-visible:ring-2 focus-visible:ring-indigo-700 focus-visible:ring-offset-2">
                        <span class="hidden sm:inline">Create workspace</span><span class="sm:hidden">Get started</span>
                        <span>→</span>
                    </a>
                @endauth
            </div>
        </div>
        <nav aria-label="Mobile navigation" class="border-t border-slate-200 bg-white/95 md:hidden">
            <div class="mx-auto flex max-w-7xl items-center gap-1 overflow-x-auto px-3 py-1.5 text-xs font-bold text-slate-700">
                <a href="#features" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Features</a>
                <a href="#workflow" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Workflow</a>
                <a href="#demo" class="shrink-0 rounded-lg bg-amber-50 px-3 py-2 text-amber-900 hover:bg-amber-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Demo</a>
                <a href="#comparison" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">What it does</a>
                <a href="#roles" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">For teams</a>
                <a href="#requirements" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Requirements</a>
                <a href="#faq" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">FAQ</a>
                <a href="#data-policy" class="shrink-0 rounded-lg px-3 py-2 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-indigo-600">Privacy &amp; data</a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Jira-inspired product hero -->
        <section class="bg-white pb-16 pt-10 sm:pb-20 sm:pt-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-14">
                    <div class="lg:col-span-7">
                        <div class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3.5 py-1.5 text-xs font-bold text-blue-800">
                            <span class="h-2 w-2 rounded-full bg-blue-600" aria-hidden="true"></span>
                            Bug triage for QA and engineering teams
                        </div>
                        <h1 class="mt-6 max-w-3xl text-4xl font-extrabold leading-[1.08] tracking-[-0.04em] text-slate-950 sm:text-5xl lg:text-[4.25rem]">
                            Turn bug reports into <span class="text-blue-700">clear next steps.</span>
                        </h1>
                        <p class="mt-6 max-w-2xl text-base leading-7 text-slate-700 sm:text-lg">
                            Give developers the reproduction details, priority, likely code area, and owner they need to start investigating.
                        </p>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="/signup" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-full bg-blue-700 px-7 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800 focus-visible:ring-2 focus-visible:ring-blue-700 focus-visible:ring-offset-2">
                                Create workspace <span aria-hidden="true">→</span>
                            </a>
                            <a href="#demo" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-full border border-slate-300 bg-white px-7 py-3 text-sm font-bold text-slate-900 transition hover:bg-slate-50 focus-visible:ring-2 focus-visible:ring-blue-700 focus-visible:ring-offset-2">
                                Explore the sample <span aria-hidden="true">↗</span>
                            </a>
                        </div>
                        <p class="mt-4 text-xs text-slate-600">Managers create projects. Developers and testers can sign up and join a team.</p>
                    </div>

                    <aside aria-label="Example triage result" class="relative lg:col-span-5">
                        <div class="absolute -inset-4 -z-10 rounded-[2rem] bg-gradient-to-br from-blue-100 via-indigo-50 to-sky-100 blur-xl" aria-hidden="true"></div>
                        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-900/10">
                            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-700 text-sm text-white" aria-hidden="true">⚡</span>
                                    <div><p class="text-xs font-extrabold text-slate-900">Triage overview</p><p class="text-[10px] text-slate-500">Illustrative sample · ABT-104</p></div>
                                </div>
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-800 ring-1 ring-emerald-200">Needs review</span>
                            </div>
                            <div class="space-y-4 bg-slate-50/80 p-5">
                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <div class="flex items-center justify-between gap-3"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Bug report</p><span class="rounded-md bg-rose-50 px-2 py-1 text-[10px] font-bold text-rose-800">High priority</span></div>
                                    <h2 class="mt-2 text-sm font-extrabold text-slate-950">Checkout fails after token refresh</h2>
                                    <p class="mt-1.5 text-xs leading-relaxed text-slate-600">Payment confirmation freezes and returns a 401 error. Reproduction notes and screenshot are attached.</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="rounded-2xl border border-slate-200 bg-white p-3.5"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Likely file</p><code class="mt-2 block break-all text-[10px] font-semibold text-blue-800">PaymentService.php</code></div>
                                    <div class="rounded-2xl border border-slate-200 bg-white p-3.5"><p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Suggested owner</p><p class="mt-2 text-xs font-extrabold text-slate-900">Payments team</p><p class="mt-0.5 text-[10px] text-slate-500">Skill and workload match</p></div>
                                </div>
                                <p class="px-1 text-[10px] leading-relaxed text-slate-500">Example output only. File hints and assignment suggestions need human review.</p>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="mt-14 grid grid-cols-2 gap-3 border-t border-slate-200 pt-6 md:grid-cols-4 md:gap-5">
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-sm font-extrabold text-blue-800">Repro steps</p><p class="mt-1 text-xs text-slate-600">QA evidence in one report</p></div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-sm font-extrabold text-emerald-800">Syntax clues</p><p class="mt-1 text-xs text-slate-600">When a checker is available</p></div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-sm font-extrabold text-indigo-800">Likely files</p><p class="mt-1 text-xs text-slate-600">Hints from a linked repository</p></div>
                    <div class="rounded-2xl bg-slate-50 px-4 py-4"><p class="text-sm font-extrabold text-violet-800">Clear owner</p><p class="mt-1 text-xs text-slate-600">Skill and workload context</p></div>
                </div>
            </div>
        </section>

        <!-- Simple account setup path -->
        <section aria-labelledby="setup-heading" class="border-b border-slate-200 bg-white py-12 sm:py-14">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-700">A short setup</p>
                        <h2 id="setup-heading" class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Start with the basics</h2>
                    </div>
                    <a href="/signup" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-indigo-700 px-5 py-3 text-sm font-bold text-white hover:bg-indigo-800 focus-visible:ring-2 focus-visible:ring-indigo-700 focus-visible:ring-offset-2">Create workspace <span class="ml-2" aria-hidden="true">→</span></a>
                </div>
                <ol class="mt-6 grid gap-3 md:grid-cols-3">
                    <li class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><span class="text-xs font-bold text-indigo-700">01 · ACCOUNT</span><p class="mt-1 text-sm font-semibold text-slate-900">Choose a role and add your name, email, and password.</p></li>
                    <li class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><span class="text-xs font-bold text-indigo-700">02 · PROJECT</span><p class="mt-1 text-sm font-semibold text-slate-900">Managers add a project name and tech stack. A GitHub link is optional.</p></li>
                    <li class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><span class="text-xs font-bold text-indigo-700">03 · TEAM</span><p class="mt-1 text-sm font-semibold text-slate-900">Add developers and testers, then start filing or reviewing reports.</p></li>
                </ol>
                <p class="mt-3 text-xs leading-relaxed text-slate-600">Developers and testers can also create an account; a manager must add them to a project before they can access its workspace.</p>
                <div id="requirements" class="mt-8 scroll-mt-28 rounded-3xl border border-slate-200 bg-slate-50 p-5 sm:p-7">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold uppercase tracking-wider text-blue-800">Before you start</p>
                        <h3 class="mt-2 text-xl font-extrabold tracking-tight text-slate-950">Setup requirements</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">A manager can start without connecting GitHub. Linking a repository adds file-path clues to triage.</p>
                    </div>
                    <div class="mt-5 grid gap-3 md:grid-cols-3">
                        <article class="rounded-2xl border border-slate-200 bg-white p-4">
                            <h4 class="text-sm font-extrabold text-slate-900">Application</h4>
                            <p class="mt-2 text-xs leading-relaxed text-slate-600">PHP 8.2+, Laravel 12, Python 3, a database, and configured file storage. Use the included setup guide for local or hosted deployment.</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4">
                            <h4 class="text-sm font-extrabold text-slate-900">Supported checks</h4>
                            <p class="mt-2 text-xs leading-relaxed text-slate-600">Python, PHP, JavaScript, TypeScript, C, C++, Java, Kotlin, and SQLite SQL. Matching parsers or compilers must be installed for each check.</p>
                        </article>
                        <article class="rounded-2xl border border-slate-200 bg-white p-4">
                            <h4 class="text-sm font-extrabold text-slate-900">Repository access</h4>
                            <p class="mt-2 text-xs leading-relaxed text-slate-600">GitHub is optional. Public repository paths can be looked up; private repositories need a server-side GitHub token configured by the operator.</p>
                        </article>
                    </div>
                    <p class="mt-4 text-xs leading-relaxed text-slate-500">SQL checks validate SQLite syntax only. Missing language tools make that language check unavailable; the app reports when a checker is missing.</p>
                </div>
            </div>
        </section>

        <!-- Product walkthrough -->
        <section id="workflow" class="py-16 sm:py-20 bg-white border-y border-[#E2E8F0]">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <span class="rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-bold text-indigo-700 font-mono-code">A QUICK PRODUCT WALKTHROUGH</span>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">From report to clear next step</h2>
                    <p class="mt-3 text-sm sm:text-base text-slate-600">Explore a sample report, its triage summary, and a suggested owner. This interactive preview uses example data only—no account, password, or live workspace is involved.</p>
                </div>

                <div id="demo" class="mt-9 grid scroll-mt-24 gap-5 lg:grid-cols-[260px_1fr] lg:gap-8">
                    <div role="tablist" aria-label="Bug triage walkthrough" class="flex gap-2 overflow-x-auto pb-1 lg:flex-col lg:overflow-visible">
                        <button type="button" role="tab" id="walkthrough-tab-report" aria-controls="walkthrough-panel-report" aria-selected="false" tabindex="-1" data-walkthrough-tab="report" class="walkthrough-tab flex min-w-[175px] flex-1 items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 text-left transition hover:border-indigo-200 lg:min-w-0">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-sm font-black text-slate-600">1</span><span><span class="block text-xs font-extrabold text-slate-900">Submit a report</span><span class="mt-0.5 block text-[10px] text-slate-500">Plain language + evidence</span></span>
                        </button>
                        <button type="button" role="tab" id="walkthrough-tab-diagnosis" aria-controls="walkthrough-panel-diagnosis" aria-selected="true" tabindex="0" data-walkthrough-tab="diagnosis" class="walkthrough-tab flex min-w-[175px] flex-1 items-center gap-3 rounded-2xl border border-indigo-200 bg-indigo-50 p-4 text-left shadow-sm transition">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-sm font-black text-white">2</span><span><span class="block text-xs font-extrabold text-slate-900">Review AI triage</span><span class="mt-0.5 block text-[10px] text-slate-500">Priority + likely code area</span></span>
                        </button>
                        <button type="button" role="tab" id="walkthrough-tab-assignment" aria-controls="walkthrough-panel-assignment" aria-selected="false" tabindex="-1" data-walkthrough-tab="assignment" class="walkthrough-tab flex min-w-[175px] flex-1 items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 text-left transition hover:border-indigo-200 lg:min-w-0">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-sm font-black text-slate-600">3</span><span><span class="block text-xs font-extrabold text-slate-900">Assign with context</span><span class="mt-0.5 block text-[10px] text-slate-500">Owner + clear rationale</span></span>
                        </button>
                    </div>

                    <div class="min-h-[300px] rounded-3xl border border-slate-200 bg-[#F8FAFC] p-4 sm:p-6">
                        <div id="walkthrough-panel-report" role="tabpanel" aria-labelledby="walkthrough-tab-report" tabindex="0" data-walkthrough-panel="report" class="hidden h-full">
                            <div class="flex items-center justify-between gap-3"><span class="text-[10px] font-black uppercase tracking-[.16em] text-slate-400">1 · Tester report</span><span class="rounded-full bg-white px-3 py-1 text-[10px] font-bold text-slate-600 ring-1 ring-slate-200">No code required</span></div>
                            <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Example bug</p>
                                <h3 class="mt-2 text-lg font-extrabold text-slate-900">OAuth token refresh failure on cart checkout</h3>
                                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">“I clicked Confirm Payment. The screen froze and showed a 401 error.”</p>
                                <div class="mt-4 inline-flex items-center gap-2 rounded-xl border border-dashed border-indigo-200 bg-indigo-50/70 px-3 py-2 text-xs font-semibold text-indigo-700"><span aria-hidden="true">📸</span> checkout-error-401.png</div>
                            </div>
                        </div>

                        <div id="walkthrough-panel-diagnosis" role="tabpanel" aria-labelledby="walkthrough-tab-diagnosis" tabindex="0" data-walkthrough-panel="diagnosis" class="h-full">
                            <div class="flex items-center justify-between gap-3"><span class="text-[10px] font-black uppercase tracking-[.16em] text-indigo-600">2 · Example triage summary</span><span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-emerald-200">Sample result</span></div>
                            <h3 class="mt-3 text-base font-extrabold text-slate-900">OAuth token refresh failure on cart checkout</h3>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200 bg-white p-4"><div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Priority</div><div class="mt-2 inline-flex rounded-lg bg-rose-50 px-2.5 py-1.5 text-sm font-extrabold text-rose-700">High · Blocker</div></div>
                                <div class="rounded-2xl border border-slate-200 bg-white p-4"><div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Duplicate check</div><div class="mt-2 text-sm font-extrabold text-emerald-700">Score 0.12 · Unique</div></div>
                                <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:col-span-2"><div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Likely repository file</div><div class="mt-2 flex flex-wrap items-center justify-between gap-2"><code class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-sky-200">app/Services/PaymentService.php</code><span class="text-[10px] font-semibold text-slate-500">AST pattern reviewed</span></div></div>
                            </div>
                        </div>

                        <div id="walkthrough-panel-assignment" role="tabpanel" aria-labelledby="walkthrough-tab-assignment" tabindex="0" data-walkthrough-panel="assignment" class="hidden h-full">
                            <div class="flex items-center justify-between gap-3"><span class="text-[10px] font-black uppercase tracking-[.16em] text-violet-600">3 · Developer dispatch</span><span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-emerald-200">Assigned</span></div>
                            <div class="mt-5 flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 font-black text-indigo-700">AL</div><div><h3 class="text-base font-extrabold text-slate-900">Alice Developer</h3><p class="mt-0.5 text-xs text-slate-500">Backend · Payments · Auth</p></div></div>
                            <div class="mt-3 rounded-2xl border border-indigo-100 bg-indigo-50/70 p-4"><div class="text-[10px] font-black uppercase tracking-wider text-indigo-700">Why this developer?</div><p class="mt-1.5 text-sm leading-relaxed text-slate-700">Domain tags match Alice’s skills, with one active task in the sample.</p></div>
                        </div>

                        <p class="mt-4 text-[10px] leading-relaxed text-slate-400">Illustrative example only. Triage details depend on the report, repository, and project team.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Deep-Dive Features Grid -->
        <section id="features" class="py-20 bg-[#F8FAFC]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="rounded-full bg-blue-50 border border-blue-200 px-3 py-1 text-xs font-bold text-blue-700 font-mono-code">
                        CORE INNOVATIONS
                    </span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
                        Built for Modern Software Engineering
                    </h2>
                    <p class="mt-3 text-sm sm:text-base text-[#64748B]">
                        Engineered specifically to solve duplicate tickets, misassigned bugs, and ambiguous repro steps.
                    </p>
                </div>

                <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Feature 1: Multi-Tenant Isolation -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-50 border border-indigo-200 text-xl">
                            🛡️
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">Strict Project Isolation</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Project views are scoped to the selected project, including its team roster, issue board, metrics, and workflow settings.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-indigo-600 font-bold">
                            ✓ Scoped Queries & Session Guards
                        </div>
                    </div>

                    <!-- Feature 2: GitHub Culprit Locator -->
                    <div id="locator" class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-50 border border-sky-200 text-xl">
                            🔍
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">GitHub Culprit Locator</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Link a repository to compare report clues with its file paths. The result is a hint for engineers to review, not proof that a file caused the bug.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-sky-600 font-bold">
                            ✓ Direct GitHub Source Links
                        </div>
                    </div>

                    <!-- Feature 3: AST Diagnostics & Checklist -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 border border-emerald-200 text-xl">
                            ⚡
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">AST and syntax checks, clearer handoffs</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            QA keeps reproduction steps and evidence with the report. Developers can review syntax-check results, error clues, and suspected files together before investigating.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-emerald-600 font-bold">
                            ✓ Syntax checks are hints, not a logic or runtime guarantee
                        </div>
                    </div>

                    <!-- Feature 4: NLP Semantic Duplicate Detection -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-50 border border-amber-200 text-xl">
                            🧠
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">Semantic Duplicate Detection</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Text-similarity estimates surface reports that may describe the same issue. Managers can review matches; the system does not automatically remove reports.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-amber-600 font-bold">
                            ✓ Vector Similarity Scoring
                        </div>
                    </div>

                    <!-- Feature 5: Dedicated Issue Studio -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-purple-50 border border-purple-200 text-xl">
                            📋
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">Dedicated Issue Studio</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Full-page workspace (`/bugs/{id}`) with high-res screenshot viewer, clarification Q&A threads, activity audit logs, and status advance controls.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-purple-600 font-bold">
                            ✓ Direct Deep-Links & Audit Trail
                        </div>
                    </div>

                    <!-- Feature 6: Dynamic Kanban & Workflow -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-rose-50 border border-rose-200 text-xl">
                            📊
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">Kanban board</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Interactive board across triage, open, in progress, review, and resolved stages. Full workflow transition validation and filter presets.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-rose-600 font-bold">
                            ✓ Transition State Guards
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Clear feature scope and limitations -->
        <section id="comparison" aria-labelledby="comparison-heading" class="scroll-mt-24 border-y border-slate-200 bg-white py-16 sm:py-20">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-800">Clear expectations</p>
                    <h2 id="comparison-heading" class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Helpful triage, with human review</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">See what the current product can help your team do—and where a person still needs to make the call.</p>
                </div>
                <div class="mt-7 grid gap-4 md:grid-cols-2">
                    <article class="rounded-3xl border border-emerald-200 bg-emerald-50/50 p-6">
                        <h3 class="flex items-center gap-2 text-base font-extrabold text-slate-950"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-700 text-sm text-white" aria-hidden="true">✓</span> What it helps with</h3>
                        <ul class="mt-4 space-y-3 text-sm leading-relaxed text-slate-700">
                            <li>Collect reproduction steps and screenshots in a shared bug report.</li>
                            <li>Suggest a priority and surface reports that may be duplicates.</li>
                            <li>Run available syntax checks and show likely repository file paths.</li>
                            <li>Recommend an owner using team skills and current workload.</li>
                        </ul>
                    </article>
                    <article class="rounded-3xl border border-amber-200 bg-amber-50/60 p-6">
                        <h3 class="flex items-center gap-2 text-base font-extrabold text-slate-950"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-amber-700 text-sm text-white" aria-hidden="true">!</span> What it does not guarantee</h3>
                        <ul class="mt-4 space-y-3 text-sm leading-relaxed text-slate-700">
                            <li>It does not prove root cause or confirm a suspected file is faulty.</li>
                            <li>It does not execute submitted code or verify runtime behavior, security, or business logic.</li>
                            <li>It does not automatically delete duplicate reports or apply suggested code fixes.</li>
                            <li>Its checks depend on installed language tools and configured repository access.</li>
                        </ul>
                    </article>
                </div>
            </div>
        </section>

        <!-- Role-Based Workflows -->
        <section id="roles" class="py-20 bg-white border-y border-[#E2E8F0]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="rounded-full bg-purple-50 border border-purple-200 px-3 py-1 text-xs font-bold text-purple-700 font-mono-code">
                        TAILORED EXPERIENCES
                    </span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
                        One workflow, three clear roles
                    </h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">Each person sees the work they need to move a bug from report to resolution.</p>
                </div>

                <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Manager Role -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 font-black">👑</span>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#0F172A]">Engineering Manager</h3>
                                <p class="text-[11px] text-[#64748B]">Set up the project and team</p>
                            </div>
                        </div>
                        <ul class="space-y-2 text-xs text-[#475569]">
                            <li class="flex items-start gap-2"><span>•</span> Create a project and optionally connect its repository.</li>
                            <li class="flex items-start gap-2"><span>•</span> Add developers and testers to the project.</li>
                            <li class="flex items-start gap-2"><span>•</span> Review suggested priority, duplicates, and assignments.</li>
                            <li class="flex items-start gap-2"><span>•</span> Track the team queue and change assignments when needed.</li>
                        </ul>
                    </div>

                    <!-- Developer Role -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700 font-black">💻</span>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#0F172A]">Software Developer</h3>
                                <p class="text-[11px] text-[#64748B]">Investigate assigned issues</p>
                            </div>
                        </div>
                        <ul class="space-y-2 text-xs text-[#475569]">
                            <li class="flex items-start gap-2"><span>•</span> Review assigned issues with reproduction details and screenshots.</li>
                            <li class="flex items-start gap-2"><span>•</span> Check syntax output and repository file hints where available.</li>
                            <li class="flex items-start gap-2"><span>•</span> Ask the reporter for clarification in the issue thread.</li>
                            <li class="flex items-start gap-2"><span>•</span> Update issue status as investigation progresses.</li>
                        </ul>
                    </div>

                    <!-- QA Tester Role -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 font-black">🧪</span>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#0F172A]">QA Black-Box Tester</h3>
                                <p class="text-[11px] text-[#64748B]">Report with useful evidence</p>
                            </div>
                        </div>
                        <ul class="space-y-2 text-xs text-[#475569]">
                            <li class="flex items-start gap-2"><span>•</span> Describe the issue in plain language with reproduction steps.</li>
                            <li class="flex items-start gap-2"><span>•</span> Attach or paste a screenshot as evidence.</li>
                            <li class="flex items-start gap-2"><span>•</span> Review the triage summary and status updates.</li>
                            <li class="flex items-start gap-2"><span>•</span> Answer developer questions in the issue thread.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- What the current prototype supports and how data is handled -->
        <section id="data-policy" aria-labelledby="scope-heading" class="scroll-mt-24 border-y border-slate-200 bg-slate-50 py-16 sm:py-20">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-700">Trust and data</p>
                    <h2 id="scope-heading" class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Understand how your information is handled</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-700">AutoBugTriage is a prototype. Triage suggestions need human review and are not a substitute for debugging, security review, or tests.</p>
                </div>
                <div class="mt-7 grid gap-4 lg:grid-cols-2">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5">
                        <h3 class="text-base font-bold text-slate-900">Where data is processed</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-700">Account details and bug reports are stored in the configured app database. Screenshots and uploaded files use the configured file storage. Report text and code are passed to the local Python triage process.</p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5">
                        <h3 class="text-base font-bold text-slate-900">External services</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-700">Repository paths are requested from GitHub when lookup is used. If Gemini vision is configured by the operator, screenshots are sent to Google for image analysis. Do not submit secrets or sensitive production data.</p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 lg:col-span-2">
                        <h3 class="text-base font-bold text-slate-900">Prototype limitations</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-700">The operator controls storage, backups, and retention. A separate formal privacy policy, retention schedule, and public uptime commitment are not currently published. The app does not run submitted code, and syntax checks cannot confirm logic, security, or runtime behavior.</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Frequently asked questions -->
        <section id="faq" aria-labelledby="faq-heading" class="scroll-mt-24 bg-white py-16 sm:py-20">
            <div class="mx-auto grid max-w-6xl gap-10 px-4 sm:px-6 lg:grid-cols-[.8fr_1.2fr] lg:px-8">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-800">FAQ</p>
                    <h2 id="faq-heading" class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Common questions</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">Need more detail? See the setup guide or open an issue on GitHub.</p>
                    <a href="https://github.com/MuarifHasnat/Auto-Bug-Traige/blob/main/README.md" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex min-h-10 items-center font-bold text-blue-800 hover:text-blue-950 focus-visible:ring-2 focus-visible:ring-blue-700">Read the setup guide <span class="ml-1" aria-hidden="true">↗</span></a>
                </div>
                <div class="divide-y divide-slate-200 border-y border-slate-200">
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Can developers and testers create their own accounts?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">Yes. They can sign up with their role, then a manager must add their account to a project before they can access its work.</p>
                    </details>
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Do I need to connect GitHub?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">No. GitHub is optional. Linking a repository enables file-path hints; private repositories need a server-side token configured by the app operator.</p>
                    </details>
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Which languages can be checked?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">Python, PHP, JavaScript, TypeScript, C, C++, Java, Kotlin, and SQLite SQL. The matching parser or compiler must be installed; SQL checks do not validate other database dialects.</p>
                    </details>
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Are AI triage results always correct?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">No. Priority, duplicate, file, and owner results are suggestions. Review them with the report and project context before acting.</p>
                    </details>
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Does the app execute submitted code?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">No. It runs available syntax and static checks only. It does not test runtime behavior or prove that the code is safe or logically correct.</p>
                    </details>
                    <details class="group py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900 focus-visible:ring-2 focus-visible:ring-blue-700"><span>Where can I learn about data handling?</span><span class="text-xl text-blue-700 transition group-open:rotate-45" aria-hidden="true">+</span></summary>
                        <p class="mt-3 pr-8 text-sm leading-relaxed text-slate-600">The <a href="#data-policy" class="font-semibold text-blue-800 underline underline-offset-2">Trust and data section</a> describes what is stored and which external services may receive information.</p>
                    </details>
                </div>
            </div>
        </section>

        <!-- Final CTA Banner -->
        <section class="py-20 bg-gradient-to-br from-[#0F172A] to-[#1E293B] text-white text-center">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <span class="inline-flex rounded-full bg-indigo-500/10 border border-indigo-400/20 px-3.5 py-1 text-xs font-bold text-indigo-300 font-mono-code">
                    A CLEARER WAY TO REVIEW BUG REPORTS
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Give every report a useful next step
                </h2>
                <p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto leading-relaxed">
                    Create a project, add your team, and review suggested priority, possible duplicates, and repository file hints in one workspace.
                </p>
                <div class="pt-3 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="/signup" class="w-full min-h-12 sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-700 hover:bg-indigo-600 px-7 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/40 transition hover:scale-[1.02] focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-slate-900">
                        <span>Create workspace</span>
                        <span>→</span>
                    </a>
                    <a href="/login" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-700 bg-slate-800/80 hover:bg-slate-800 px-7 py-3.5 text-sm font-bold text-slate-200 transition">
                        <span>Sign In to Existing Project</span>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-slate-950 text-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-[1.3fr_1fr_1fr_1fr]">
                <div>
                    <a href="/" class="inline-flex items-center gap-2.5 rounded focus-visible:ring-2 focus-visible:ring-white">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white" aria-hidden="true">⚡</span>
                        <span class="text-base font-extrabold">AutoBug<span class="text-blue-300">Triage</span></span>
                    </a>
                    <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-300">A practical bug handoff workspace for QA and engineering teams.</p>
                    <p class="mt-3 text-xs text-slate-400">Prototype · No public uptime commitment</p>
                </div>
                <nav aria-label="Product links">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Product</h2>
                    <ul class="mt-4 space-y-3 text-sm font-medium text-slate-200">
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#features">Features</a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#workflow">Workflow demo</a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#comparison">What it does</a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#requirements">Setup requirements</a></li>
                    </ul>
                </nav>
                <nav aria-label="Resources">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Resources</h2>
                    <ul class="mt-4 space-y-3 text-sm font-medium text-slate-200">
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="https://github.com/MuarifHasnat/Auto-Bug-Traige/blob/main/README.md" target="_blank" rel="noopener noreferrer">Documentation <span aria-hidden="true">↗</span></a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="https://github.com/MuarifHasnat/Auto-Bug-Traige" target="_blank" rel="noopener noreferrer">GitHub <span aria-hidden="true">↗</span></a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="https://github.com/MuarifHasnat/Auto-Bug-Traige/issues" target="_blank" rel="noopener noreferrer">Contact / feedback <span aria-hidden="true">↗</span></a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="https://github.com/MuarifHasnat/Auto-Bug-Traige/commits/main" target="_blank" rel="noopener noreferrer">Version history <span aria-hidden="true">↗</span></a></li>
                    </ul>
                </nav>
                <nav aria-label="Trust and help">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Trust &amp; help</h2>
                    <ul class="mt-4 space-y-3 text-sm font-medium text-slate-200">
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#data-policy">Privacy &amp; data handling</a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#faq">Frequently asked questions</a></li>
                        <li><a class="rounded hover:text-white focus-visible:ring-2 focus-visible:ring-white" href="#roles">For managers, developers &amp; testers</a></li>
                    </ul>
                </nav>
            </div>
            <div class="mt-10 flex flex-col gap-2 border-t border-slate-800 pt-5 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                <p>© {{ date('Y') }} AutoBugTriage. Product preview.</p>
                <p>Review triage suggestions before taking action.</p>
            </div>
        </div>
    </footer>

    <script>
        (() => {
            const tabs = Array.from(document.querySelectorAll('[data-walkthrough-tab]'));
            const panels = Array.from(document.querySelectorAll('[data-walkthrough-panel]'));

            function activateWalkthroughStep(step, moveFocus = false) {
                tabs.forEach((tab) => {
                    const active = tab.dataset.walkthroughTab === step;
                    tab.setAttribute('aria-selected', String(active));
                    tab.tabIndex = active ? 0 : -1;
                    tab.classList.toggle('border-indigo-200', active);
                    tab.classList.toggle('bg-indigo-50', active);
                    tab.classList.toggle('shadow-sm', active);
                    tab.classList.toggle('border-slate-200', !active);
                    tab.classList.toggle('bg-white', !active);
                    const number = tab.querySelector('span');
                    number.classList.toggle('bg-indigo-600', active);
                    number.classList.toggle('text-white', active);
                    number.classList.toggle('bg-slate-100', !active);
                    number.classList.toggle('text-slate-600', !active);
                    if (active && moveFocus) tab.focus();
                });

                panels.forEach((panel) => {
                    panel.hidden = panel.dataset.walkthroughPanel !== step;
                    panel.classList.toggle('hidden', panel.hidden);
                });
            }

            tabs.forEach((tab, index) => {
                tab.addEventListener('click', () => activateWalkthroughStep(tab.dataset.walkthroughTab));
                tab.addEventListener('keydown', (event) => {
                    let nextIndex = index;
                    if (event.key === 'ArrowRight' || event.key === 'ArrowDown') nextIndex = (index + 1) % tabs.length;
                    else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') nextIndex = (index - 1 + tabs.length) % tabs.length;
                    else if (event.key === 'Home') nextIndex = 0;
                    else if (event.key === 'End') nextIndex = tabs.length - 1;
                    else return;
                    event.preventDefault();
                    activateWalkthroughStep(tabs[nextIndex].dataset.walkthroughTab, true);
                });
            });
        })();
    </script>

</body>
</html>

