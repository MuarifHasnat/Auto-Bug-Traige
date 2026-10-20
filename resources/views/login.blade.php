<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In · AutoBugTriage AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] flex selection:bg-indigo-500 selection:text-white">
    <!-- 50/50 Split Screen Layout -->
    <div class="flex-1 flex flex-col lg:flex-row min-h-screen">
        
        <!-- Left 50%: Universal Sign In Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 sm:p-10 lg:p-12 bg-white border-r border-[#E2E8F0]">
            <div>
                <!-- Top Brand Bar -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-black shadow-md shadow-indigo-600/20 text-sm">
                            ⚡
                        </div>
                        <span class="text-base font-extrabold tracking-tight text-[#0F172A]">
                            AutoBug<span class="text-indigo-600">Triage</span>
                        </span>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                            AI v1.0
                        </span>
                    </div>

                    <a href="/" class="inline-flex items-center gap-1.5 rounded-xl border border-[#E2E8F0] px-3.5 py-1.5 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition">
                        Create Workspace →
                    </a>
                </div>

                <!-- Form Content -->
                <div class="max-w-md mx-auto pt-2">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#0F172A]">
                        Welcome back
                    </h1>
                    <p class="mt-1.5 text-xs sm:text-sm text-[#64748B] leading-relaxed">
                        Sign in to access your project dashboard, kanban issues, or AI triage console.
                    </p>

                    <!-- UX Feature: One-Click Demo Switcher for fast testing/evaluation -->
                    <div class="mt-4 rounded-2xl border border-indigo-100 bg-indigo-50/50 p-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-900 flex items-center gap-1">
                                <span>⚡</span> Quick Demo Login (1-Click Fill)
                            </span>
                            <span class="text-[10px] text-indigo-600 font-mono-code">No typing needed</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" onclick="fillDemo('rakib@gmail.com', '12345678', 'Manager')" 
                                    class="rounded-xl border border-blue-200 bg-white py-1.5 px-2 text-[11px] font-bold text-[#0052cc] hover:bg-blue-50 transition shadow-2xs">
                                👤 Manager
                            </button>
                            <button type="button" onclick="fillDemo('rakib1@gmail.com', '12345678', 'Developer')" 
                                    class="rounded-xl border border-emerald-200 bg-white py-1.5 px-2 text-[11px] font-bold text-[#006644] hover:bg-emerald-50 transition shadow-2xs">
                                💻 Developer
                            </button>
                            <button type="button" onclick="fillDemo('rakib11@gmail.com', '12345678', 'Tester')" 
                                    class="rounded-xl border border-purple-200 bg-white py-1.5 px-2 text-[11px] font-bold text-[#6554c0] hover:bg-purple-50 transition shadow-2xs">
                                🐞 QA Tester
                            </button>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-800 flex items-start gap-2.5">
                            <span class="text-rose-500 font-bold mt-0.5">⚠</span>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form id="loginForm" action="/login" method="POST" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1.5">Email Address</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </span>
                                <input type="email" id="loginEmail" name="email" value="{{ old('email') }}" required autofocus placeholder="name@company.com" 
                                       class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1.5">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <input type="password" id="loginPass" name="password" required placeholder="••••••••" 
                                       class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-8 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                                <button type="button" onclick="togglePass('loginPass')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                                    👁
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-0.5">
                            <label class="flex items-center gap-2 text-xs text-[#64748B] cursor-pointer select-none">
                                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-[#CBD5E1] text-indigo-600 focus:ring-indigo-500/20">
                                <span>Remember my session</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 py-3 px-4 text-xs sm:text-sm font-bold text-white shadow-lg shadow-indigo-600/25 transition hover:from-indigo-500 hover:to-blue-500 active:scale-[0.99]">
                            <span>Sign In to Workspace</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Footnote -->
            <div class="mt-6 pt-4 border-t border-[#E2E8F0] text-center max-w-md mx-auto">
                <p class="text-xs text-[#64748B]">
                    New organization? 
                    <a href="/" class="font-bold text-indigo-600 hover:underline">
                        Register workspace as Manager
                    </a>
                </p>
            </div>
        </div>

        <!-- Right 50%: Precision Engineering Showcase Area -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#0F172A] text-white p-12 flex-col justify-between relative overflow-hidden bg-dot-grid-dark">
            <!-- Ambient Glow -->
            <div class="pointer-events-none absolute -top-24 -right-24 h-96 w-96 rounded-full bg-blue-500/20 blur-[120px]"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-indigo-500/20 blur-[120px]"></div>

            <div class="relative z-10 flex items-center justify-between">
                <span class="rounded-full bg-indigo-500/15 border border-indigo-500/30 px-3 py-1 text-xs font-bold text-indigo-300 font-mono-code">
                    ● PYTHON NLP & AST ENGINE
                </span>
                <span class="text-xs text-slate-400">Automated Triage Simulator</span>
            </div>

            <!-- Floating 3D Showcase Card -->
            <div class="relative z-10 my-auto py-8">
                <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6 shadow-2xl backdrop-blur-xl card-hover-lift">
                    <!-- Step 1: Input -->
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-rose-500/20 text-rose-400 text-xs font-bold">1</span>
                            <span class="text-xs font-bold text-slate-300">Incoming Bug Report</span>
                        </div>
                        <span class="font-mono-code text-[11px] text-slate-400">#ABT-104</span>
                    </div>

                    <div class="mt-3 rounded-xl bg-slate-950 p-3 border border-slate-800/80">
                        <p class="font-mono-code text-xs text-emerald-400 leading-snug">
                            TypeError: Unsupported operand type(s) for /: 'int' and 'NoneType' in calculateDiscount()
                        </p>
                    </div>

                    <!-- Step 2: AI Scanning Animation -->
                    <div class="my-4 flex items-center justify-center gap-3 py-2 bg-indigo-500/10 rounded-xl border border-indigo-500/20">
                        <span class="h-2 w-2 rounded-full bg-indigo-400 animate-ping"></span>
                        <span class="text-xs font-bold text-indigo-300 font-mono-code">AI Scanning: AST Tree • Duplicate Hash • Skill Matrix</span>
                    </div>

                    <!-- Step 3: Resulting Auto-Triage Tags -->
                    <div class="grid grid-cols-3 gap-2 pt-1 text-center">
                        <div class="rounded-xl bg-rose-500/15 border border-rose-500/30 p-2.5">
                            <span class="block text-[10px] uppercase font-bold text-rose-300">Severity</span>
                            <span class="text-xs font-extrabold text-white mt-0.5 block">High Priority</span>
                        </div>
                        <div class="rounded-xl bg-emerald-500/15 border border-emerald-500/30 p-2.5">
                            <span class="block text-[10px] uppercase font-bold text-emerald-300">Duplicate Check</span>
                            <span class="text-xs font-extrabold text-white mt-0.5 block">False (Unique)</span>
                        </div>
                        <div class="rounded-xl bg-indigo-500/15 border border-indigo-500/30 p-2.5">
                            <span class="block text-[10px] uppercase font-bold text-indigo-300">Auto-Assigned</span>
                            <span class="text-xs font-extrabold text-white mt-0.5 block truncate">96% Skill Match</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-xs text-slate-400">Role-based routing direct to Manager Dashboard, Kanban Board, or AI Console.</p>
                </div>
            </div>

            <!-- Showcase Footer -->
            <div class="relative z-10 flex items-center justify-between text-xs text-slate-400 border-t border-slate-800/80 pt-4">
                <span>AutoBugTriage Platform</span>
                <span class="font-mono-code text-slate-500">Precision Developer Workspace</span>
            </div>
        </div>
    </div>

    <script>
        function togglePass(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function fillDemo(email, pass, role) {
            const emailInput = document.getElementById('loginEmail');
            const passInput = document.getElementById('loginPass');
            emailInput.value = email;
            passInput.value = pass;
            
            // Highlight visual feedback
            emailInput.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50');
            passInput.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50');
            setTimeout(() => {
                emailInput.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50');
                passInput.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50');
            }, 800);
        }
    </script>
</body>
</html>
