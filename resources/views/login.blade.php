<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In · AutoBugTriage AI</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-white text-[#111827] selection:bg-indigo-500 selection:text-white">
    <main class="grid min-h-screen lg:grid-cols-2">
        <aside class="relative hidden min-h-screen overflow-hidden bg-gradient-to-br from-[#302878] via-[#342d83] to-[#28236d] px-10 py-9 text-white lg:flex lg:flex-col lg:justify-between xl:px-[4.7vw]">
            <a href="/" class="text-xl font-extrabold tracking-tight text-white">AutoBugTriage</a>
            <div class="max-w-xl py-16">
                <div class="mb-7 inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-2xl">⚡</div>
                <h2 class="max-w-lg text-4xl font-extrabold leading-tight tracking-tight xl:text-5xl">Find the bug. Locate the file. Fix it faster.</h2>
                <p class="mt-5 max-w-lg text-base leading-7 text-indigo-100/90 xl:text-lg">An AI agent that detects bugs, points to the exact file and suggests the fix.</p>
            </div>
            <p class="text-sm font-medium text-indigo-100/75">AI v1.0</p>
        </aside>

        <section class="flex min-h-screen flex-col items-center justify-center px-5 py-8 sm:px-10 lg:px-12 xl:px-16">
            <div class="mb-8 flex w-full max-w-md items-center justify-between lg:hidden">
                <a href="/" class="text-lg font-extrabold tracking-tight text-[#111827]">AutoBug<span class="text-indigo-600">Triage</span></a>
                <a href="/signup" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">Create account <span aria-hidden="true">→</span></a>
            </div>

            <div class="w-full max-w-md">
                <h1 class="text-3xl font-extrabold tracking-tight text-[#111827] sm:text-4xl">Welcome back</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">Sign in to your project workspace.</p>

                @if(isset($errors) && $errors->any())
                    <div class="mt-6 flex items-start gap-2.5 rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-sm font-medium text-rose-800" role="alert">
                        <span class="font-bold text-rose-600" aria-hidden="true">⚠</span>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if(session('login_session_expired'))
                    <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-3.5 text-sm font-medium text-amber-900" role="alert">
                        This sign-in page expired. Please enter your details again.
                    </div>
                @endif

                <form id="loginForm" action="/login" method="POST" class="mt-7 space-y-5">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#111827]" for="loginEmail">Email address</label>
                        <input type="email" id="loginEmail" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="you@company.com"
                               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-[#111827] outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10">
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="block text-sm font-semibold text-[#111827]" for="loginPass">Password</label>
                        </div>
                        <div class="relative">
                            <input type="password" id="loginPass" name="password" required autocomplete="current-password" placeholder="Password"
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-12 text-sm text-[#111827] outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10">
                            <button type="button" data-password-target="loginPass" aria-label="Show password" aria-pressed="false" class="absolute inset-y-0 right-0 flex w-12 items-center justify-center rounded-r-xl text-slate-500 transition hover:text-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500">
                                <svg data-eye-visible class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>
                                <svg data-eye-hidden class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.8 10.8 0 0 1 12 5c6.25 0 9.75 7 9.75 7a16.7 16.7 0 0 1-3.1 3.9M6.2 6.2A10.8 10.8 0 0 0 2.25 12s3.5 7 9.75 7a10.8 10.8 0 0 0 4-.75"/></svg>
                            </button>
                        </div>
                    </div>

                    <label class="flex cursor-pointer select-none items-center gap-2.5 text-sm text-slate-600">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20">
                        <span>Remember my session</span>
                    </label>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-[#4938d0] px-4 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#3f30ba] focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-600/25 active:scale-[0.99]">
                        Sign in to workspace
                    </button>
                </form>

                <p class="mt-7 text-sm text-slate-600">Need a new organization? <a href="/signup" class="font-semibold text-indigo-700 underline decoration-indigo-300 underline-offset-2 hover:text-indigo-900">Create an account</a></p>
            </div>
        </section>
    </main>

    <script>
        document.querySelectorAll('[data-password-target]').forEach((button) => {
            const input = document.getElementById(button.dataset.passwordTarget);
            if (!input) return;
            button.addEventListener('click', () => {
                const isRevealed = input.type === 'password';
                input.type = isRevealed ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(isRevealed));
                button.setAttribute('aria-label', isRevealed ? 'Hide password' : 'Show password');
                button.querySelector('[data-eye-visible]').classList.toggle('hidden', isRevealed);
                button.querySelector('[data-eye-hidden]').classList.toggle('hidden', !isRevealed);
            });
        });

    </script>
</body>
</html>
