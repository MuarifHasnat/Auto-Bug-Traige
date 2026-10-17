<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/css/modern-saas.css">
    <title>Manage Team</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="modern-saas min-h-screen p-6">

    <div class="max-w-4xl mx-auto space-y-6 mt-8">
        @if(session('success'))
            <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="rounded-xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-300">{{ $errors->first() }}</div>
        @endif
        
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 flex justify-between items-center">
            <div>
                <button type="button" onclick="goBack()" class="mb-3 rounded-lg border border-slate-600 px-3 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-700">← Back</button>
                <span class="text-xs text-amber-400 font-bold">Project: {{ session('project_name', 'My Project') }}</span>
                <h2 class="text-2xl font-bold text-white mt-1">👥 Manage project team</h2>
                <p class="text-xs text-slate-400">Only the manager can create Tester and Developer accounts.</p>
            </div>
            <a href="/workspace" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-5 py-3 rounded-xl text-sm">
                Next: Go to AI Bug Triage Workspace ➔
            </a>
            <a href="/logout" class="bg-rose-500/15 border border-rose-500/30 text-rose-300 font-bold px-4 py-2 rounded-xl text-sm">Log out</a>
        </div>

        <!-- Create Team Account -->
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700">
            <form action="/team-members" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                <div>
                    <label class="text-xs text-slate-300 font-bold">Full name</label>
                    <input type="text" name="name" required placeholder="e.g. Rahim Uddin"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-xs text-slate-300 font-bold">Email</label>
                    <input type="email" name="email" required placeholder="member@company.com"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-xs text-slate-300 font-bold">Role</label>
                    <select name="role" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm mt-1">
                        <option value="tester">Tester</option>
                        <option value="developer">Developer</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-300 font-bold">Temporary password</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-xs text-slate-300 font-bold">Confirm password</label>
                    <input type="password" name="password_confirmation" required minlength="8"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-xs text-slate-300 font-bold">Developer skills</label>
                    <input type="text" name="skills" placeholder="php laravel api backend"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div class="md:col-span-3">
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-black font-bold text-sm rounded-lg px-4 py-2">
                        + Create team account
                    </button>
                </div>
            </form>
        </div>

        <!-- Account List -->
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 space-y-3">
            <h3 class="text-sm font-bold text-slate-300">Team accounts</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($teamMembers as $member)
                <div class="bg-slate-900 p-4 rounded-xl border border-slate-700">
                    <p class="font-bold text-white">{{ $member->name }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $member->email }}</p>
                    <span class="inline-block mt-3 rounded-full bg-indigo-500/15 px-2 py-1 text-xs font-bold text-indigo-300">{{ ucfirst($member->role) }}</span>
                </div>
                @empty
                <p class="text-sm text-slate-400">No Tester or Developer accounts have been created yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Developer Skill Profiles -->
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 space-y-3">
            <h3 class="text-sm font-bold text-slate-300">Developer skill profiles used by AI assignment</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($developers as $dev)
                <div class="bg-slate-900 p-4 rounded-xl border border-slate-700">
                    <form action="/developers/{{ $dev->id }}" method="POST" class="space-y-2">
                        @csrf
                        @method('PUT')
                        <div class="flex justify-between gap-2 text-sm">
                            <input name="name" value="{{ $dev->name }}" required class="min-w-0 flex-1 rounded-lg border border-slate-700 bg-slate-950 px-2 py-1 font-bold">
                            <span class="text-indigo-400">{{ $dev->active_tasks }} Tasks</span>
                        </div>
                        <input name="skills" value="{{ $dev->skills }}" required class="w-full rounded-lg border border-slate-700 bg-slate-950 px-2 py-1 text-xs text-slate-300">
                        <div class="flex gap-2 pt-1">
                            <button class="rounded-lg bg-indigo-600 px-3 py-1 text-xs font-bold">Save</button>
                    </form>
                            <form action="/developers/{{ $dev->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg bg-rose-500/15 px-3 py-1 text-xs font-bold text-rose-300">Delete</button>
                            </form>
                        </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

<script>function goBack(){if(window.history.length>1){window.history.back();}else{window.location.href='/dashboard';}}</script>
</body>
</html>