<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account · AutoBugTriage AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] flex flex-col justify-center items-center p-4 sm:p-6 bg-dot-grid selection:bg-indigo-500 selection:text-white">
    <div class="w-full max-w-lg mx-auto space-y-6">
        <!-- Top Brand Bar -->
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

            <div class="flex items-center gap-2">
                <a href="/" class="inline-flex items-center gap-1 rounded-xl border border-[#E2E8F0] bg-white px-3 py-1.5 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition shadow-2xs">
                    ← Home
                </a>
                <a href="/login" class="inline-flex items-center gap-1 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition shadow-2xs">
                    Sign In →
                </a>
            </div>
        </div>

        <!-- Role-based signup card -->
        <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-8 shadow-sm">
            <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-indigo-50 border border-indigo-200/80 px-3 py-1 text-xs font-bold text-indigo-700">
                <span>👥</span>
                <span>Create an account — choose your role</span>
            </div>

            <h1 class="text-2xl font-extrabold tracking-tight text-[#0F172A]">
                Create your workspace
            </h1>
            <p class="mt-1 text-xs text-[#64748B] leading-relaxed">
                Managers create workspaces. Developers and testers can create an account, then join a project through their manager.
            </p>

            @if($errors->any())
                <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs font-medium text-rose-800 flex items-start gap-2">
                    <span class="text-rose-500 font-bold">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="/signup" method="POST" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="signupRole">I’m signing up as</label>
                    <select id="signupRole" name="role" required class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2.5 text-xs sm:text-sm text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                        <option value="manager" @selected(old('role', 'manager') === 'manager')>Manager · create a workspace</option>
                        <option value="developer" @selected(old('role') === 'developer')>Developer · join a team</option>
                        <option value="tester" @selected(old('role') === 'tester')>QA tester · join a team</option>
                    </select>
                    <p id="signupRoleHint" class="mt-1.5 text-[11px] leading-relaxed text-[#64748B]">Create a workspace, then invite your developers and testers.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="signupName">Full name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <input type="text" id="signupName" name="manager_name" value="{{ old('manager_name') }}" required autofocus placeholder="e.g. Muarif Hasnat" 
                               class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="signupEmail">Email address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </span>
                        <input type="email" id="signupEmail" name="manager_email" value="{{ old('manager_email') }}" required placeholder="manager@company.com" 
                               class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="signupPass">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input type="password" id="signupPass" name="password" required minlength="8" placeholder="••••••••" 
                                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-8 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                            <button type="button" data-password-target="signupPass" aria-label="Show password" aria-pressed="false" class="absolute inset-y-0 right-0 z-10 flex w-11 items-center justify-center rounded-r-xl text-slate-400 transition hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500">
                                <svg data-eye-visible class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>
                                <svg data-eye-hidden class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.8 10.8 0 0 1 12 5c6.25 0 9.75 7 9.75 7a16.7 16.7 0 0 1-3.1 3.9M6.2 6.2C3.7 8 2.25 12 2.25 12s3.5 7 9.75 7a10.8 10.8 0 0 0 4-.75"/></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#0F172A] mb-1.5" for="signupConfirmPass">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </span>
                            <input type="password" id="signupConfirmPass" name="password_confirmation" required minlength="8" placeholder="••••••••" 
                                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] pl-10 pr-8 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                            <button type="button" data-password-target="signupConfirmPass" aria-label="Show password confirmation" aria-pressed="false" class="absolute inset-y-0 right-0 z-10 flex w-11 items-center justify-center rounded-r-xl text-slate-400 transition hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500">
                                <svg data-eye-visible class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>
                                <svg data-eye-hidden class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.8 10.8 0 0 1 12 5c6.25 0 9.75 7 9.75 7a16.7 16.7 0 0 1-3.1 3.9M6.2 6.2C3.7 8 2.25 12 2.25 12s3.5 7 9.75 7a10.8 10.8 0 0 0 4-.75"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full mt-3 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 py-3 px-4 text-xs sm:text-sm font-bold text-white shadow-md shadow-indigo-600/20 transition hover:from-indigo-500 hover:to-blue-500 active:scale-[0.99]">
                    <span id="signupSubmitText">Launch Organization Workspace</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Footer link -->
        <p class="text-center text-xs text-[#64748B]">
            Already have an assigned account? 
            <a href="/login" class="font-bold text-indigo-600 hover:underline">
                Sign in to your project
            </a>
        </p>
    </div>

    <script>
        const signupRole = document.getElementById('signupRole');
        const signupRoleHint = document.getElementById('signupRoleHint');
        const signupSubmitText = document.getElementById('signupSubmitText');

        function updateSignupRoleCopy() {
            if (signupRole.value === 'manager') {
                signupRoleHint.textContent = 'Create a workspace, then invite your developers and testers.';
                signupSubmitText.textContent = 'Launch Organization Workspace';
            } else if (signupRole.value === 'developer') {
                signupRoleHint.textContent = 'Create your account, then ask your project manager to add this email to a project.';
                signupSubmitText.textContent = 'Create developer account';
            } else {
                signupRoleHint.textContent = 'Create your account, then ask your project manager to add this email to a project.';
                signupSubmitText.textContent = 'Create tester account';
            }
        }

        signupRole.addEventListener('change', updateSignupRoleCopy);
        updateSignupRoleCopy();

        document.querySelectorAll('[data-password-target]').forEach((button) => {
            const input = document.getElementById(button.dataset.passwordTarget);
            if (!input) return;

            button.addEventListener('click', () => {
                const isRevealed = input.type === 'password';
                input.type = isRevealed ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(isRevealed));
                const label = button.dataset.passwordTarget === 'signupConfirmPass' ? 'password confirmation' : 'password';
                button.setAttribute('aria-label', `${isRevealed ? 'Hide' : 'Show'} ${label}`);
                button.querySelector('[data-eye-visible]').classList.toggle('hidden', isRevealed);
                button.querySelector('[data-eye-hidden]').classList.toggle('hidden', !isRevealed);
            });
        });
    </script>
</body>
</html>
