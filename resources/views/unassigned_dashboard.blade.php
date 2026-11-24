<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>Dashboard · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    @include('components.app-sidebar', ['active' => 'dashboard'])

    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-20 flex min-h-[60px] items-center justify-between gap-3 border-b border-slate-200 bg-white/95 px-5 backdrop-blur-md lg:px-8">
            <p class="truncate text-xs font-semibold text-slate-500">Dashboard <span class="mx-1 text-slate-300">/</span> Personal workspace</p>
            @include('components.profile-header')
        </header>

        <div class="mx-auto min-h-[calc(100vh-60px)] max-w-[1700px] space-y-6 px-4 py-6 sm:px-6 xl:px-9">
            <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-amber-200 bg-white p-5 shadow-sm sm:px-7">
                <div><p class="text-xs font-bold uppercase tracking-[.16em] text-amber-700">Personal workspace</p><h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">No project assigned yet</h1><p class="mt-2 text-sm text-slate-600">Your account is ready, {{ auth()->user()->name }}. A project manager needs to add <strong class="text-slate-900">{{ auth()->user()->email }}</strong> to a project. Until then, the project figures below are zero.</p></div>
                <div class="flex gap-3"><a href="/dashboard" class="inline-flex min-h-10 items-center rounded-xl bg-indigo-600 px-4 text-sm font-bold text-white hover:bg-indigo-700">Refresh dashboard ↻</a><a href="/profile" class="inline-flex min-h-10 items-center rounded-xl border border-slate-200 px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50">My profile</a></div>
            </section>
            @include('components.project-command-center')
        </div>
    </main>
</div>
</body>
</html>
