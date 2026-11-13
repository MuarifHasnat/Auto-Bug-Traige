<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoBugTriage · Next-Gen AI Bug Triage & Root Cause Intelligence</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        html { scroll-padding-top: 5rem; }
        body { background: #f7f8fc; }
        .hero-glow { background: radial-gradient(ellipse at 78% 12%, rgba(129, 140, 248, .2), transparent 34%), radial-gradient(ellipse at 8% 34%, rgba(56, 189, 248, .12), transparent 30%); }
        .hero-grid { background-image: linear-gradient(rgba(99,102,241,.035) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,.035) 1px, transparent 1px); background-size: 42px 42px; mask-image: linear-gradient(to bottom, black, transparent 88%); }
        .glow-effect { box-shadow: 0 28px 90px -34px rgba(49, 46, 129, .34), 0 0 0 1px rgba(99, 102, 241, .08); }
        .gradient-border {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(14, 165, 233, 0.1));
        }
        .preview-float { animation: preview-float 7s ease-in-out infinite; }
        .soft-pulse { animation: soft-pulse 2.8s ease-in-out infinite; }
        .hero-layout { display: flex; flex-direction: column; }
        .hero-layout > :nth-child(1) { order: 1; }
        .hero-layout > :nth-child(2) { order: 2; }
        .hero-layout > :nth-child(3) { order: 3; }
        .hero-layout > :nth-child(4) { order: 4; }
        .hero-layout > :nth-child(6) { order: 5; }
        .hero-layout > :nth-child(5) { order: 6; }
        @keyframes preview-float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        @keyframes soft-pulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,.18); } 50% { box-shadow: 0 0 0 7px rgba(16,185,129,0); } }
        #features .grid > div, #roles .grid > div, #demo .grid > div { transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease; }
        #features .grid > div:hover, #roles .grid > div:hover, #demo .grid > div:hover { transform: translateY(-5px); border-color: rgba(129,140,248,.55); box-shadow: 0 18px 45px -25px rgba(49,46,129,.35); }
        @media (min-width: 1024px) {
            .hero-layout { display: grid; grid-template-columns: minmax(0,.92fr) minmax(0,1.08fr); column-gap: 3.25rem; align-items: center; text-align: left; }
            .hero-layout > :nth-child(1) { grid-column: 1; grid-row: 1; }
            .hero-layout > :nth-child(2) { grid-column: 1; grid-row: 2; max-width: 100%; margin-left: 0; margin-right: 0; font-size: clamp(3rem, 4vw, 4.3rem); line-height: 1.04; letter-spacing: -.055em; }
            .hero-layout > :nth-child(3) { grid-column: 1; grid-row: 3; margin-left: 0; margin-right: 0; }
            .hero-layout > :nth-child(4) { grid-column: 1; grid-row: 4; justify-content: flex-start; flex-wrap: wrap; }
            .hero-layout > :nth-child(5) { grid-column: 1 / -1; grid-row: 5; width: 100%; max-width: none; margin-top: 2rem; }
            .hero-layout > :nth-child(6) { grid-column: 2; grid-row: 1 / 5; width: 100%; margin-top: 0; }
        }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; } }
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
                        AI v2.4
                    </span>
                </div>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-7 text-xs font-bold text-[#64748B]">
                <a href="#features" class="hover:text-indigo-600 transition">Features</a>
                <a href="#pipeline" class="hover:text-indigo-600 transition">AI Pipeline</a>
                <a href="#locator" class="hover:text-indigo-600 transition">Culprit Locator</a>
                <a href="#roles" class="hover:text-indigo-600 transition">Team Workflows</a>
                <a href="#demo" class="hover:text-indigo-600 transition flex items-center gap-1.5 text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    Demo Accounts
                </a>
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ auth()->user()->role === 'manager' ? '/dashboard' : (auth()->user()->role === 'developer' ? '/workspace' : '/ai-agent') }}"
                       class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-indigo-600/30 transition hover:scale-[1.02]">
                        <span>Open Workspace</span>
                        <span>→</span>
                    </a>
                @else
                    <a href="/login" class="inline-flex items-center rounded-xl border border-[#E2E8F0] bg-white px-3.5 py-1.5 text-xs font-bold text-[#0F172A] hover:bg-[#F1F5F9] transition">
                        Sign In
                    </a>
                    <a href="/signup" class="inline-flex items-center gap-1 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 px-4 py-2 text-xs font-bold text-white shadow-sm shadow-indigo-600/30 transition hover:scale-[1.02]">
                        <span>Get Started Free</span>
                        <span>→</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="relative isolate pt-12 pb-16 sm:pt-16 sm:pb-20 overflow-hidden hero-glow">
            <div class="pointer-events-none absolute inset-0 -z-10 hero-grid"></div>
            <div class="pointer-events-none absolute -right-40 top-20 -z-10 h-96 w-96 rounded-full bg-indigo-300/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-40 bottom-8 -z-10 h-80 w-80 rounded-full bg-sky-300/20 blur-3xl"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center hero-layout">
                <!-- Announcement Chip -->
                <div class="inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-white/80 px-3.5 py-1.5 text-[11px] sm:text-xs font-bold text-indigo-700 shadow-sm backdrop-blur mb-6">
                    <span class="flex h-2 w-2 rounded-full bg-indigo-600 animate-pulse soft-pulse"></span>
                    <span>Empirical AI Research Prototype</span>
                    <span class="text-indigo-300">•</span>
                    <span class="text-indigo-900 font-extrabold">Autonomous Bug Triage & Root Cause Intelligence</span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-[#0F172A] tracking-tight max-w-4xl mx-auto leading-[1.15]">
                    Transform Bug Reports into <br class="hidden sm:inline"/>
                    <span class="bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-600 bg-clip-text text-transparent">
                        Diagnosed Tasks & Code Culprits
                    </span>
                    in Seconds.
                </h1>

                <!-- Subtitle -->
                <p class="mt-5 text-sm sm:text-base lg:text-lg text-[#64748B] max-w-2xl mx-auto leading-relaxed">
                    AutoBugTriage empowers black-box QA testers to report bugs with natural language and screenshots. Our multi-engine AI automatically predicts severity, detects duplicate tickets, verifies AST syntax, pinpoints suspected GitHub files, and dispatches the right engineer.
                </p>

                <!-- Dual CTAs & Trust Badges -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="/signup" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/30 hover:from-indigo-700 hover:to-blue-700 transition hover:scale-[1.02]">
                        <span>Create Organization Workspace</span>
                        <span>→</span>
                    </a>
                    <a href="#demo" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl border border-[#CBD5E1] bg-white px-6 py-3.5 text-sm font-extrabold text-[#0F172A] hover:bg-[#F8FAFC] transition shadow-xs">
                        <span>🧪 Explore 1-Click Demo</span>
                    </a>
                    <a href="https://github.com/MuarifHasnat/Auto-Bug-Traige" target="_blank" rel="noopener noreferrer" 
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl border border-[#E2E8F0] bg-slate-50 px-5 py-3.5 text-sm font-semibold text-[#475569] hover:bg-slate-100 transition">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                        <span>GitHub Repo ↗</span>
                    </a>
                </div>

                <!-- Metrics Strip -->
                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 max-w-4xl mx-auto rounded-3xl border border-white bg-white/75 p-2 shadow-lg shadow-slate-200/60 backdrop-blur">
                    <div class="p-4 rounded-2xl bg-white/50 text-center transition hover:bg-indigo-50/70">
                        <div class="text-2xl font-black text-indigo-600 font-mono-code">100%</div>
                        <div class="text-xs font-bold text-[#64748B] mt-0.5">Project Tenant Isolation</div>
                    </div>
                    <div class="p-4 rounded-2xl bg-white/50 text-center transition hover:bg-emerald-50/70">
                        <div class="text-2xl font-black text-emerald-600 font-mono-code">Sub-Sec</div>
                        <div class="text-xs font-bold text-[#64748B] mt-0.5">Zero-Shot AI Inference</div>
                    </div>
                    <div class="p-4 rounded-2xl bg-white/50 text-center transition hover:bg-blue-50/70">
                        <div class="text-2xl font-black text-blue-600 font-mono-code">AST + Repo</div>
                        <div class="text-xs font-bold text-[#64748B] mt-0.5">GitHub Culprit Locator</div>
                    </div>
                    <div class="p-4 rounded-2xl bg-white/50 text-center transition hover:bg-purple-50/70">
                        <div class="text-2xl font-black text-purple-600 font-mono-code">Balance</div>
                        <div class="text-xs font-bold text-[#64748B] mt-0.5">Workload-Aware Dispatch</div>
                    </div>
                </div>

                <!-- Hero Product Visual Mockup -->
                <div class="mt-14 max-w-5xl mx-auto rounded-3xl border border-white bg-white/80 p-2.5 sm:p-4 shadow-2xl glow-effect text-left backdrop-blur-xl preview-float">
                    <!-- Window chrome -->
                    <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3 px-2">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-rose-500 inline-block"></span>
                            <span class="h-3 w-3 rounded-full bg-amber-500 inline-block"></span>
                            <span class="h-3 w-3 rounded-full bg-emerald-500 inline-block"></span>
                            <span class="ml-2 text-xs font-bold text-[#64748B] font-mono-code">AutoBugTriage Command Center · #ABT-104</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Multi-Engine Active
                            </span>
                        </div>
                    </div>

                    <!-- 3-Column Triage Preview Card -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-3 sm:p-4 bg-[#F8FAFC] rounded-2xl mt-3">
                        <!-- Col 1: QA Submission -->
                        <div class="rounded-xl border border-[#E2E8F0] bg-white p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 font-mono-code">1. Tester Report (QA)</span>
                                <span class="rounded bg-slate-100 text-[10px] font-bold px-1.5 py-0.5 text-slate-600">Black-Box</span>
                            </div>
                            <h4 class="text-xs font-bold text-[#0F172A]">OAuth token refresh failure on cart checkout</h4>
                            <p class="text-[11px] text-[#64748B] leading-relaxed">
                                Tester clicked "Confirm Payment". Screen froze with 401 error. Screenshot attached with console log.
                            </p>
                            <div class="rounded-lg border border-dashed border-indigo-200 bg-indigo-50/50 p-2 text-center">
                                <span class="text-xs">📸</span>
                                <span class="text-[10px] font-bold text-indigo-700 ml-1">checkout-error-401.png</span>
                            </div>
                        </div>

                        <!-- Col 2: AI Autonomous Analysis -->
                        <div class="rounded-xl border border-indigo-200 bg-indigo-50/30 p-4 space-y-2.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600 font-mono-code">2. AI Diagnostics</span>
                                <span class="rounded-full bg-rose-100 text-rose-800 text-[10px] font-bold px-2 py-0.5">High / Blocker</span>
                            </div>
                            <div class="space-y-1.5 text-[11px]">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Duplicate Check:</span>
                                    <span class="font-mono-code font-bold text-emerald-600">Score 0.12 (Unique)</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Syntax Scanner:</span>
                                    <span class="font-bold text-indigo-600">Verified Exception Pattern</span>
                                </div>
                                <div class="rounded-lg bg-sky-50 border border-sky-200 p-2 mt-2">
                                    <div class="text-[10px] font-bold text-sky-800 flex items-center gap-1">
                                        <span>🔍 Suspected GitHub Culprit:</span>
                                    </div>
                                    <span class="font-mono-code text-[10px] font-bold text-sky-900 block truncate mt-0.5">
                                        app/Services/PaymentService.php
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Col 3: Smart Assignment -->
                        <div class="rounded-xl border border-[#E2E8F0] bg-white p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 font-mono-code">3. Developer Dispatch</span>
                                <span class="rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5">Assigned</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center font-bold text-xs text-indigo-700">
                                    AL
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-[#0F172A]">Alice Developer</div>
                                    <div class="text-[10px] text-[#64748B]">Backend · Payments · Auth</div>
                                </div>
                            </div>
                            <div class="rounded-lg bg-slate-50 border border-slate-200 p-2 text-[10px] text-slate-600 leading-relaxed">
                                <span class="font-bold text-slate-800">Rationale:</span> Matched domain tags 'auth', 'payment', 'token' with Alice's stack. Workload: 1 active task.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- The 4-Pillar Pipeline Flow -->
        <section id="pipeline" class="py-20 bg-white border-y border-[#E2E8F0]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-bold text-indigo-700 font-mono-code">
                        ARCHITECTURE WORKFLOW
                    </span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
                        How AutoBugTriage Works
                    </h2>
                    <p class="mt-3 text-sm sm:text-base text-[#64748B]">
                        An end-to-end intelligent pipeline that turns ambiguous tester reports into actionable engineering tickets.
                    </p>
                </div>

                <div class="mt-14 grid grid-cols-1 md:grid-cols-4 gap-6 relative">
                    <!-- Step 1 -->
                    <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-3 relative group hover:border-indigo-300 transition">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white font-black text-sm shadow-md shadow-indigo-600/20">
                            1
                        </div>
                        <h3 class="text-sm font-extrabold text-[#0F172A]">Black-Box Submission</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            QA Testers file issues using natural language and upload screenshots via drag-and-drop or <kbd class="font-mono-code bg-white px-1 py-0.5 rounded border border-slate-200">Ctrl+V</kbd>. No code knowledge required.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-3 relative group hover:border-indigo-300 transition">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white font-black text-sm shadow-md shadow-blue-600/20">
                            2
                        </div>
                        <h3 class="text-sm font-extrabold text-[#0F172A]">AI Classification & AST</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Python AI engine classifies severity, calculates cosine semantic duplicate similarity, and verifies stack trace syntax using AST rules.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-3 relative group hover:border-indigo-300 transition">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-600 text-white font-black text-sm shadow-md shadow-sky-600/20">
                            3
                        </div>
                        <h3 class="text-sm font-extrabold text-[#0F172A]">GitHub Culprit Locator</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Tree-scanner correlates bug symptoms with the project repository, pinpointing suspected culprit files with direct GitHub navigation links.
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-3 relative group hover:border-indigo-300 transition">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-600 text-white font-black text-sm shadow-md shadow-purple-600/20">
                            4
                        </div>
                        <h3 class="text-sm font-extrabold text-[#0F172A]">Workload Dispatch</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Automatically assigns the best developer using domain keyword matching and active task load balance, with transparent rationale.
                        </p>
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
                            Complete tenant separation. Every project maintains its own isolated team roster, Kanban board, triage metrics, and settings. No cross-project data leakage.
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
                            Managers configure their project GitHub repo. The AI scans repository paths and points engineers directly to files likely causing the error.
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
                        <h3 class="text-base font-extrabold text-[#0F172A]">AST Syntax Diagnostics</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Parses submitted code and stack traces using Abstract Syntax Tree rules, generating step-by-step diagnostic checklists for developers.
                        </p>
                        <div class="pt-2 text-[11px] font-mono-code text-emerald-600 font-bold">
                            ✓ Interactive Repro Checklists
                        </div>
                    </div>

                    <!-- Feature 4: NLP Semantic Duplicate Detection -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-7 space-y-3 shadow-xs hover:shadow-md transition">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-50 border border-amber-200 text-xl">
                            🧠
                        </div>
                        <h3 class="text-base font-extrabold text-[#0F172A]">Semantic Duplicate Detection</h3>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Cosine vector similarity checks prevent duplicate bug reports from cluttering sprint backlogs, with transparent review dialogs for managers.
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
                        <h3 class="text-base font-extrabold text-[#0F172A]">Live Kanban Board</h3>
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

        <!-- Role-Based Workflows -->
        <section id="roles" class="py-20 bg-white border-y border-[#E2E8F0]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="rounded-full bg-purple-50 border border-purple-200 px-3 py-1 text-xs font-bold text-purple-700 font-mono-code">
                        TAILORED EXPERIENCES
                    </span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
                        Designed for Every Role on the Team
                    </h2>
                </div>

                <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Manager Role -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 font-black">👑</span>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#0F172A]">Engineering Manager</h3>
                                <p class="text-[11px] text-[#64748B]">Command Center & Strategy</p>
                            </div>
                        </div>
                        <ul class="space-y-2 text-xs text-[#475569]">
                            <li class="flex items-start gap-2"><span>•</span> Create projects & configure GitHub repositories.</li>
                            <li class="flex items-start gap-2"><span>•</span> Add developers with domain skill taxonomy.</li>
                            <li class="flex items-start gap-2"><span>•</span> Monitor team workload & override AI assignments.</li>
                            <li class="flex items-start gap-2"><span>•</span> Review duplicate detections & triage metrics.</li>
                        </ul>
                    </div>

                    <!-- Developer Role -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700 font-black">💻</span>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#0F172A]">Software Developer</h3>
                                <p class="text-[11px] text-[#64748B]">Fix with Context & Clarity</p>
                            </div>
                        </div>
                        <ul class="space-y-2 text-xs text-[#475569]">
                            <li class="flex items-start gap-2"><span>•</span> Focused queue of issues assigned directly to them.</li>
                            <li class="flex items-start gap-2"><span>•</span> Dedicated issue details page (`/bugs/{id}`) with screenshot.</li>
                            <li class="flex items-start gap-2"><span>•</span> Direct links to suspect GitHub culprit files.</li>
                            <li class="flex items-start gap-2"><span>•</span> Clarification Q&A thread with QA tester.</li>
                        </ul>
                    </div>

                    <!-- QA Tester Role -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-[#F8FAFC] p-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 font-black">🧪</span>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#0F172A]">QA Black-Box Tester</h3>
                                <p class="text-[11px] text-[#64748B]">Zero-Friction Bug Filing</p>
                            </div>
                        </div>
                        <ul class="space-y-2 text-xs text-[#475569]">
                            <li class="flex items-start gap-2"><span>•</span> Report bugs in plain natural language.</li>
                            <li class="flex items-start gap-2"><span>•</span> Drag-and-drop or paste screenshots (<kbd class="font-mono-code bg-white px-1 py-0.5 rounded border border-slate-200">Ctrl+V</kbd>).</li>
                            <li class="flex items-start gap-2"><span>•</span> Receive instant AI confirmation & syntax feedback.</li>
                            <li class="flex items-start gap-2"><span>•</span> Answer developer clarification requests.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Interactive 1-Click Demo Accounts -->
        <section id="demo" class="py-20 bg-[#F8FAFC]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-xs font-bold text-amber-700 font-mono-code">
                        INSTANT ACCESS
                    </span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
                        Try with Preloaded Demo Accounts
                    </h2>
                    <p class="mt-3 text-sm sm:text-base text-[#64748B]">
                        Click any role below to automatically fill the credentials and explore the workspace immediately.
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Manager Demo Card -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-5 space-y-3.5 shadow-2xs hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xl">👑</span>
                            <span class="rounded bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700">Manager</span>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-[#0F172A]">Demo Manager</div>
                            <div class="text-[11px] font-mono-code text-slate-500 truncate">demo-manager@example.test</div>
                        </div>
                        <div class="text-[10px] text-slate-500 bg-slate-50 p-2 rounded-xl border border-slate-100">
                            Password: <span class="font-mono-code font-bold text-slate-800">12345678</span>
                        </div>
                        <a href="/login?email=demo-manager@example.test" 
                           class="w-full inline-flex items-center justify-center gap-1 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 transition shadow-xs">
                            Login as Manager →
                        </a>
                    </div>

                    <!-- Developer Alice Demo Card -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-5 space-y-3.5 shadow-2xs hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xl">💻</span>
                            <span class="rounded bg-blue-50 border border-blue-200 px-2 py-0.5 text-[10px] font-bold text-blue-700">Backend / Auth</span>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-[#0F172A]">Alice Developer</div>
                            <div class="text-[11px] font-mono-code text-slate-500 truncate">demo.alice@example.test</div>
                        </div>
                        <div class="text-[10px] text-slate-500 bg-slate-50 p-2 rounded-xl border border-slate-100">
                            Password: <span class="font-mono-code font-bold text-slate-800">12345678</span>
                        </div>
                        <a href="/login?email=demo.alice@example.test" 
                           class="w-full inline-flex items-center justify-center gap-1 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 transition shadow-xs">
                            Login as Alice →
                        </a>
                    </div>

                    <!-- Developer Bob Demo Card -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-5 space-y-3.5 shadow-2xs hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xl">🎨</span>
                            <span class="rounded bg-purple-50 border border-purple-200 px-2 py-0.5 text-[10px] font-bold text-purple-700">Frontend / UI</span>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-[#0F172A]">Bob Developer</div>
                            <div class="text-[11px] font-mono-code text-slate-500 truncate">demo.bob@example.test</div>
                        </div>
                        <div class="text-[10px] text-slate-500 bg-slate-50 p-2 rounded-xl border border-slate-100">
                            Password: <span class="font-mono-code font-bold text-slate-800">12345678</span>
                        </div>
                        <a href="/login?email=demo.bob@example.test" 
                           class="w-full inline-flex items-center justify-center gap-1 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold py-2 transition shadow-xs">
                            Login as Bob →
                        </a>
                    </div>

                    <!-- Tester Demo Card -->
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-5 space-y-3.5 shadow-2xs hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xl">🧪</span>
                            <span class="rounded bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-[10px] font-bold text-emerald-700">QA Tester</span>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-[#0F172A]">Demo Tester</div>
                            <div class="text-[11px] font-mono-code text-slate-500 truncate">demo.tester@example.test</div>
                        </div>
                        <div class="text-[10px] text-slate-500 bg-slate-50 p-2 rounded-xl border border-slate-100">
                            Password: <span class="font-mono-code font-bold text-slate-800">12345678</span>
                        </div>
                        <a href="/login?email=demo.tester@example.test" 
                           class="w-full inline-flex items-center justify-center gap-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2 transition shadow-xs">
                            Login as Tester →
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA Banner -->
        <section class="py-20 bg-gradient-to-br from-[#0F172A] to-[#1E293B] text-white text-center">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <span class="inline-flex rounded-full bg-indigo-500/10 border border-indigo-400/20 px-3.5 py-1 text-xs font-bold text-indigo-300 font-mono-code">
                    READY TO ELIMINATE TRIAGE BOTTLENECKS?
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Start Triaging Bugs Autonomously Today
                </h2>
                <p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto leading-relaxed">
                    Set up your project in 60 seconds, connect your team, and let AI handle severity classification, duplicate detection, and GitHub file pinpointing.
                </p>
                <div class="pt-3 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="/signup" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 hover:bg-indigo-500 px-7 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-indigo-600/40 transition hover:scale-[1.02]">
                        <span>Create Organization Workspace</span>
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
    <footer class="bg-white border-t border-[#E2E8F0] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-black text-xs">
                    ⚡
                </div>
                <div>
                    <span class="text-sm font-extrabold text-[#0F172A]">AutoBug<span class="text-indigo-600">Triage</span></span>
                    <span class="text-xs text-[#64748B] block">Smart Bug Triage System (SBTS)</span>
                </div>
            </div>

            <!-- Tech Badges -->
            <div class="flex flex-wrap items-center justify-center gap-2 text-[10px] font-mono-code font-bold text-[#64748B]">
                <span class="rounded bg-slate-100 px-2 py-0.5 border border-slate-200">Laravel 12</span>
                <span class="rounded bg-slate-100 px-2 py-0.5 border border-slate-200">Tailwind CSS</span>
                <span class="rounded bg-slate-100 px-2 py-0.5 border border-slate-200">Python 3.14 AI</span>
                <span class="rounded bg-slate-100 px-2 py-0.5 border border-slate-200">SQLite / MySQL</span>
            </div>

            <div class="text-xs text-[#64748B]">
                © {{ date('Y') }} AutoBugTriage. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>

