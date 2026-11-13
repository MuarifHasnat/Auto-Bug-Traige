<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting for Project Access · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] bg-dot-grid flex items-center justify-center p-4 selection:bg-indigo-500 selection:text-white">
    <main class="w-full max-w-xl rounded-3xl border border-[#E2E8F0] bg-white p-7 shadow-sm sm:p-9">
        <a href="/" class="inline-flex items-center gap-2 text-sm font-extrabold text-slate-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white">⚡</span>
            <span>AutoBug<span class="text-indigo-600">Triage</span></span>
        </a>

        <div class="mt-8 flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-xl ring-1 ring-amber-200" aria-hidden="true">⏳</div>
        <p class="mt-5 text-[11px] font-black uppercase tracking-[.16em] text-amber-700">One more step</p>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Your account is ready</h1>
        <p class="mt-3 text-sm leading-relaxed text-slate-600">
            There isn’t a project connected to this account yet. Ask your project manager to add <strong class="text-slate-900">{{ auth()->user()->email }}</strong> from Team Members.
        </p>

        <div class="mt-5 rounded-2xl border border-indigo-100 bg-indigo-50/70 p-4 text-sm leading-relaxed text-indigo-950">
            Once you’re added, refresh this page or sign in again to open your {{ auth()->user()->role === 'developer' ? 'developer' : 'QA tester' }} workspace.
        </div>

        <div class="mt-7 flex flex-col gap-3 sm:flex-row">
            <a href="/pending-access" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">Check project access <span aria-hidden="true">↻</span></a>
            <form action="/logout" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Sign out</button>
            </form>
        </div>
    </main>
</body>
</html>
