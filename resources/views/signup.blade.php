<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <title>Manager signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="modern-saas min-h-screen flex items-center justify-center p-6">

    <div class="bg-slate-800 p-8 rounded-2xl border border-slate-700 w-full max-w-md space-y-6">
        <button type="button" onclick="goBack()" class="rounded-lg border border-slate-600 px-3 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-700">← Back</button>
        <div class="text-center">
            <div class="text-4xl">🤖</div>
            <h1 class="text-2xl font-bold text-indigo-400 mt-2">AutoBugTriage AI</h1>
            <p class="text-xs text-slate-400 mt-1">Only managers can create a project</p>
        </div>

        <form action="/signup" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs text-slate-300 font-bold">Manager Full Name</label>
                <input type="text" name="manager_name" required placeholder="e.g. Muarif Hasnat Rakib" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm mt-1">
            </div>

            <div>
                <label class="text-xs text-slate-300 font-bold">Email Address</label>
                <input type="email" name="manager_email" required placeholder="manager@company.com" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm mt-1">
            </div>
            <div>
                <label class="text-xs text-slate-300 font-bold">Password</label>
                <input type="password" name="password" required minlength="8" placeholder="••••••••" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm mt-1">
            </div>
                <div>
                    <label class="text-xs text-slate-300 font-bold">Confirm Password</label>
                    <input type="password" name="password_confirmation" required minlength="8"
                           class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm mt-1">
                </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl text-sm">
            Create account
            </button>
        </form>
        <p class="text-center text-xs text-slate-400">Already have an account? <a href="/login" class="text-indigo-300">Sign in</a></p>
    </div>

<script>function goBack(){if(window.history.length>1){window.history.back();}else{window.location.href='/login';}}</script>
</body>
</html>