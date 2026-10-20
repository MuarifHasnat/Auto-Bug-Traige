<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Sign Up · AutoBugTriage AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] flex selection:bg-indigo-500 selection:text-white">
    <!-- 50/50 Split Screen Layout -->
    <div class="flex-1 flex flex-col lg:flex-row min-h-screen">
        
        <!-- Left 50%: Clean Manager Sign Up Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 sm:p-10 lg:p-14 bg-white border-r border-[#E2E8F0]">
            <div>
                <!-- Top Brand & Back Bar -->
                <div class="flex items-center justify-between mb-8">
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

                    <a href="/login" class="inline-flex items-center gap-1.5 rounded-xl border border-[#E2E8F0] px-3.5 py-1.5 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition">
                        Sign In →
                    </a>
                </div>

                <!-- Form Card Content -->
                <div class="max-w-md mx-auto pt-4">
                    <!-- Manager Badge -->
                    <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-indigo-50 border border-indigo-200/80 px-3 py-1 text-xs font-bold text-indigo-700">
                        <span>🛡️</span>
                        <span>Manager Portal — Create Organization Workspace</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#0F172A]">
                        Create your workspace
                    </h1>
                    <p class="mt-2 text-xs sm:text-sm text-[#64748B] leading-relaxed">
                        Register as an engineering manager to initiate projects, configure team skills, and launch automated bug triage.
                    </p>

                    @if($errors->any())
                        <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-800 flex items-start gap-2.5">
                            <span class="text-rose-500 font-bold mt-0.5">⚠</span>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form action="/signup" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1.5">Manager Full Name</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <input type="text" name="manager_name" value="{{ old('manager_name') }}" required placeholder="e.g. Muarif Hasnat Rakib" 
                                       class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1.5">Official Work Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </span>
                                <input type="email" name="manager_email" value="{{ old('manager_email') }}" required placeholder="manager@company.com" 
                                       class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-[#0F172A] mb-1.5">Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </span>
                                    <input type="password" id="signupPass" name="password" required minlength="8" placeholder="••••••••" 
                                           class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-8 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                                    <button type="button" onclick="togglePass('signupPass')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                                        👁
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#0F172A] mb-1.5">Confirm Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </span>
                                    <input type="password" id="signupConfirmPass" name="password_confirmation" required minlength="8" placeholder="••••••••" 
                                           class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-8 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                                    <button type="button" onclick="togglePass('signupConfirmPass')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                                        👁
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-4 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 py-3 px-4 text-xs sm:text-sm font-bold text-white shadow-lg shadow-indigo-600/25 transition hover:from-indigo-500 hover:to-blue-500 active:scale-[0.99]">
                            <span>Launch Organization Workspace</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Footnote -->
            <div class="mt-8 pt-4 border-t border-[#E2E8F0] text-center max-w-md mx-auto">
                <p class="text-xs text-[#64748B]">
                    Already have an assigned account? 
                    <a href="/login" class="font-bold text-indigo-600 hover:underline">
                        Sign in to your project
                    </a>
                </p>
            </div>
        </div>

        <!-- Right 50%: Dark Precision Engineering Showcase Area -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#0F172A] text-white p-12 flex-col justify-between relative overflow-hidden bg-dot-grid-dark">
            <!-- Ambient Glow -->
            <div class="pointer-events-none absolute -top-24 -right-24 h-96 w-96 rounded-full bg-indigo-500/20 blur-[120px]"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-blue-500/20 blur-[120px]"></div>

            <div class="relative z-10 flex items-center justify-between">
                <span class="rounded-full bg-indigo-500/15 border border-indigo-500/30 px-3 py-1 text-xs font-bold text-indigo-300 font-mono-code">
                    ● PYTHON NLP & AST ENGINE
                </span>
                <span class="text-xs text-slate-400">Automated Triage Simulator</span>
            </div>

            <!-- Floating 3D Showcase Card: Bug Incoming -> AI Scanning -> Result Output -->
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
                    <p class="text-xs text-slate-400">Zero manual triage delay. Bugs are verified and assigned the moment they are reported.</p>
                </div>
            </div>

            <!-- Showcase Footer -->
            <div class="relative z-10 flex items-center justify-between text-xs text-slate-400 border-t border-slate-800/80 pt-4">
                <span>AutoBugTriage Architecture</span>
                <span class="font-mono-code text-slate-500">Laravel 11 • PostgreSQL • Python 3</span>
            </div>
        </div>
    </div>

    <script>
        function togglePass(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>