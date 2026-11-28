<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>{{ $pageTitle }} · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
    @if(auth()->user()->role === 'tester')<link rel="stylesheet" href="/css/tester-theme.css">@endif
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] antialiased {{ auth()->user()->role === 'tester' ? 'tester-shell' : '' }}">
<div class="flex min-h-screen">
    @include('components.app-sidebar', ['active' => $active])

    <main class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex min-h-[60px] items-center justify-between gap-3 border-b border-slate-200 bg-white/95 px-5 backdrop-blur-md lg:px-8">
            <p class="truncate text-xs font-semibold text-slate-500">Workspace <span class="mx-1 text-slate-300">/</span> {{ $pageTitle }}</p>
            @include('components.profile-header')
        </header>

        <section class="flex min-h-[calc(100vh-60px)] flex-1 items-center justify-center p-6" aria-labelledby="no-project-title">
            <div class="w-full max-w-xl rounded-2xl border border-amber-200 bg-amber-50 px-7 py-8 text-center shadow-sm sm:px-10">
                <h1 id="no-project-title" class="text-xl font-extrabold tracking-tight text-slate-900">No project selected</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $emptyStateMessage }}</p>
            </div>
        </section>
    </main>
</div>
</body>
</html>
