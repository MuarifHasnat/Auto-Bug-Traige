<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account · AutoBugTriage AI</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-white text-[#111827] selection:bg-indigo-500 selection:text-white">
    <main class="grid min-h-screen lg:grid-cols-2">
        <aside class="relative hidden min-h-screen overflow-hidden bg-gradient-to-br from-[#302878] via-[#342d83] to-[#28236d] px-10 py-9 text-white lg:flex lg:flex-col lg:justify-between xl:px-[4.7vw]">
            <a href="/" class="text-xl font-extrabold tracking-tight text-white">AutoBugTriage</a>
            <div class="max-w-xl py-16">
                <div class="mb-7 inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-2xl">⚡</div>
                <h2 class="max-w-lg text-4xl font-extrabold leading-tight tracking-tight xl:text-5xl">Set up your team in minutes.</h2>
                <p class="mt-5 max-w-lg text-base leading-7 text-indigo-100/90 xl:text-lg">Managers create the workspace. Developers and testers join through their manager.</p>
            </div>
            <p class="text-sm font-medium text-indigo-100/75">AI v1.0</p>
        </aside>

        <section class="flex min-h-screen flex-col items-center justify-center px-5 py-8 sm:px-10 lg:px-12 xl:px-16">
            <div class="mb-7 flex w-full max-w-lg items-center justify-between lg:hidden">
                <a href="/" class="text-lg font-extrabold tracking-tight text-[#111827]">AutoBug<span class="text-indigo-600">Triage</span></a>
                <a href="/login" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">Sign in <span aria-hidden="true">→</span></a>
            </div>

            <div class="w-full max-w-lg">
                <h1 class="text-3xl font-extrabold tracking-tight text-[#111827] sm:text-4xl">Create your account</h1>

                @if(isset($errors) && $errors->any())
                    <div class="mt-5 flex items-start gap-2.5 rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-sm font-medium text-rose-800" role="alert">
                        <span class="font-bold text-rose-600" aria-hidden="true">⚠</span>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="/signup" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#111827]" for="signupRole">I am signing up as</label>
                        <select id="signupRole" name="role" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-[#111827] outline-none transition hover:border-slate-400 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10">
                            <option value="manager" @selected(old('role', 'manager') === 'manager')>Manager (create a workspace)</option>
                            <option value="developer" @selected(old('role') === 'developer')>Developer (join a team)</option>
                            <option value="tester" @selected(old('role') === 'tester')>QA tester (join a team)</option>
                        </select>
                        <p id="signupRoleHint" class="mt-1.5 text-xs leading-5 text-slate-500">Create a workspace, then invite your developers and testers.</p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#111827]" for="signupName">Full name</label>
                        <input type="text" id="signupName" name="manager_name" value="{{ old('manager_name') }}" required autofocus autocomplete="name" placeholder="Your full name"
                               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-[#111827] outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#111827]" for="signupEmail">Email address</label>
                        <input type="email" id="signupEmail" name="manager_email" value="{{ old('manager_email') }}" required autocomplete="email" placeholder="you@company.com"
                               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-[#111827] outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10">
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-[#111827]" for="signupPass">Password</label>
                            <div class="relative">
                                <input type="password" id="signupPass" name="password" required minlength="8" autocomplete="new-password" placeholder="At least 8 characters"
                                       class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-11 text-sm text-[#111827] outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10">
                                <button type="button" data-password-target="signupPass" aria-label="Show password" aria-pressed="false" class="absolute inset-y-0 right-0 flex w-11 items-center justify-center rounded-r-xl text-slate-500 transition hover:text-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500">
                                    <svg data-eye-visible class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>
                                    <svg data-eye-hidden class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.8 10.8 0 0 1 12 5c6.25 0 9.75 7 9.75 7a16.7 16.7 0 0 1-3.1 3.9M6.2 6.2A10.8 10.8 0 0 0 2.25 12s3.5 7 9.75 7a10.8 10.8 0 0 0 4-.75"/></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-[#111827]" for="signupConfirmPass">Confirm password</label>
                            <div class="relative">
                                <input type="password" id="signupConfirmPass" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Re-enter password"
                                       class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-11 text-sm text-[#111827] outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10">
                                <button type="button" data-password-target="signupConfirmPass" aria-label="Show password confirmation" aria-pressed="false" class="absolute inset-y-0 right-0 flex w-11 items-center justify-center rounded-r-xl text-slate-500 transition hover:text-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500">
                                    <svg data-eye-visible class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>
                                    <svg data-eye-hidden class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.8 10.8 0 0 1 12 5c6.25 0 9.75 7 9.75 7a16.7 16.7 0 0 1-3.1 3.9M6.2 6.2A10.8 10.8 0 0 0 2.25 12s3.5 7 9.75 7a10.8 10.8 0 0 0 4-.75"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-[#4938d0] px-4 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#3f30ba] focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-600/25 active:scale-[0.99]">
                        <span id="signupSubmitText">Create workspace</span>
                    </button>
                </form>

                <p class="mt-6 text-sm text-slate-600">Already have an account? <a href="/login" class="font-semibold text-indigo-700 underline decoration-indigo-300 underline-offset-2 hover:text-indigo-900">Sign in</a></p>
            </div>
        </section>
    </main>

    <script>
        const signupRole = document.getElementById('signupRole');
        const signupRoleHint = document.getElementById('signupRoleHint');
        const signupSubmitText = document.getElementById('signupSubmitText');

        function updateSignupRoleCopy() {
            if (signupRole.value === 'manager') {
                signupRoleHint.textContent = 'Create a workspace, then invite your developers and testers.';
                signupSubmitText.textContent = 'Create workspace';
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
