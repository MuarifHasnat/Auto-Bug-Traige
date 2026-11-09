<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In · AutoBugTriage AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] flex flex-col justify-center items-center p-4 sm:p-6 bg-dot-grid selection:bg-indigo-500 selection:text-white">
    <div class="w-full max-w-md mx-auto space-y-6">
        <!-- Top Brand Logo -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-black shadow-md shadow-indigo-600/20 text-sm">
                    ⚡
                </div>
                <span class="text-lg font-extrabold tracking-tight text-[#0F172A]">
                    AutoBug<span class="text-indigo-600">Triage</span>
                </span>
                <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                    AI v1.0
                </span>
            </div>

            <a href="/" class="inline-flex items-center gap-1 rounded-xl border border-[#E2E8F0] bg-white px-3 py-1.5 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition shadow-2xs">
                Register Workspace →
            </a>
        </div>

        <!-- Clean Login Card -->
        <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-8 shadow-sm">
            <h1 class="text-2xl font-extrabold tracking-tight text-[#0F172A]">
                Welcome back
            </h1>
            <p class="mt-1 text-xs text-[#64748B] leading-relaxed">
                Sign in to access your project dashboard, kanban board, and AI triage studio.
            </p>

            @if($errors->any())
                <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs font-medium text-rose-800 flex items-start gap-2">
                    <span class="text-rose-500 font-bold">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form id="loginForm" action="/login" method="POST" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="loginEmail">Email Address</label>
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
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="loginPass">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" id="loginPass" name="password" required placeholder="••••••••" 
                               class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-9 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                        <button type="button" onclick="togglePass('loginPass')" aria-label="Toggle password visibility" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
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

                <button type="submit" class="w-full mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 py-2.5 px-4 text-xs sm:text-sm font-bold text-white shadow-md shadow-indigo-600/20 transition hover:from-indigo-500 hover:to-blue-500 active:scale-[0.99]">
                    <span>Sign In to Workspace</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <!-- Subtle 1-Click Demo Fill for easy testing -->
            <div class="mt-6 pt-5 border-t border-[#E2E8F0]">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2 text-center">Quick Demo Accounts (1-Click Fill)</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="fillDemo('demo-manager@example.test', '12345678')" 
                            class="rounded-xl border border-blue-200 bg-blue-50/50 py-1.5 px-2 text-[11px] font-bold text-blue-700 hover:bg-blue-100/70 transition text-center">
                        Manager
                    </button>
                    <button type="button" onclick="fillDemo('demo.alice@example.test', '12345678')" 
                            class="rounded-xl border border-emerald-200 bg-emerald-50/50 py-1.5 px-2 text-[11px] font-bold text-emerald-700 hover:bg-emerald-100/70 transition text-center">
                        Developer
                    </button>
                    <button type="button" onclick="fillDemo('demo-tester@example.test', '12345678')" 
                            class="rounded-xl border border-purple-200 bg-purple-50/50 py-1.5 px-2 text-[11px] font-bold text-purple-700 hover:bg-purple-100/70 transition text-center">
                        QA Tester
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer link -->
        <p class="text-center text-xs text-[#64748B]">
            Need a new organization? 
            <a href="/" class="font-bold text-indigo-600 hover:underline">
                Create Workspace as Manager
            </a>
        </p>
    </div>

    <script>
        function togglePass(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function fillDemo(email, pass) {
            const emailInput = document.getElementById('loginEmail');
            const passInput = document.getElementById('loginPass');
            emailInput.value = email;
            passInput.value = pass;
            
            emailInput.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50');
            passInput.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50');
            setTimeout(() => {
                emailInput.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50');
                passInput.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50');
            }, 500);
        }
    </script>
</body>
</html>
