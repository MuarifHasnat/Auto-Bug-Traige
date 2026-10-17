<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <title>Step 2: Create Project</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="modern-saas min-h-screen flex items-center justify-center p-6">

    <div class="bg-slate-800 p-8 rounded-2xl border border-slate-700 w-full max-w-lg space-y-6">
        <div class="flex items-center justify-between">
            <button type="button" onclick="goBack()" class="rounded-lg border border-slate-600 px-3 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-700">← Back</button>
            <a href="/logout" class="rounded-lg border border-rose-500/30 bg-rose-500/10 px-3 py-2 text-xs font-semibold text-rose-300">Log out</a>
        </div>
        <div>
            <span class="text-xs bg-indigo-500/20 text-indigo-300 px-3 py-1 rounded-full font-bold">
                Welcome, {{ session('manager_name', 'Manager') }}
            </span>
            <h2 class="text-2xl font-bold text-white mt-3">📁 Create Your Project</h2>
            <p class="text-xs text-slate-400 mt-1">Page 2: Enter your project details before adding developers.</p>
        </div>

        <form action="/create-project" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs text-slate-300 font-bold">Project Name</label>
                <input type="text" name="project_name" required placeholder="e.g. E-Commerce Web App" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm mt-1">
            </div>

            <div>
                <label class="text-xs text-slate-300 font-bold">Tech Stack</label>
                <input type="text" name="project_stack" required placeholder="e.g. Laravel, React, MySQL" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-sm mt-1">
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-xl text-sm">
                Create Project & Go to Add Developers ➔
            </button>
        </form>
    </div>

<script>function goBack(){if(window.history.length>1){window.history.back();}else{window.location.href='/login';}}</script>
</body>
</html>