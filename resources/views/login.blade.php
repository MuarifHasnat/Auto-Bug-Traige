<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in | AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="modern-saas min-h-screen flex items-center justify-center p-6">
    <main class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">
        <button type="button" onclick="goBack()" class="rounded-xl border border-slate-700 px-3 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-800">← Back</button>
        <div class="text-center mb-8">
            <div class="text-4xl">🤖</div>
            <h1 class="text-2xl font-bold text-indigo-300 mt-3">Welcome back</h1>
            <p class="text-sm text-slate-400 mt-1">Sign in to your bug workspace</p>
        </div>

        @if($errors->any())
            <div class="mb-5 rounded-xl border border-rose-500/30 bg-rose-500/10 p-3 text-sm text-rose-300">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="text-sm text-slate-300">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-2 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 outline-none focus:border-indigo-400">
            </div>
            <div>
                <label class="text-sm text-slate-300">Password</label>
                <input type="password" name="password" required
                    class="mt-2 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 outline-none focus:border-indigo-400">
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-400">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-600 bg-slate-950">
                Remember me
            </label>
            <button class="w-full rounded-xl bg-indigo-600 py-3 font-bold hover:bg-indigo-500">Sign in</button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-400">
            New here? <a href="/" class="text-indigo-300 hover:text-indigo-200">Create an account</a>
        </p>
    </main>
<script>function goBack(){if(window.history.length>1){window.history.back();}else{window.location.href='/';}}</script>
</body>
</html>
