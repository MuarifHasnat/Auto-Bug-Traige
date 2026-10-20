<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 1: Configure Project · AutoBugTriage AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] flex items-center justify-center p-4 sm:p-6 lg:p-10 selection:bg-indigo-500 selection:text-white bg-dot-grid">
    <div class="w-full max-w-2xl">
        <!-- Top Nav & Header Controls -->
        <div class="mb-5 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white font-black text-xs shadow-sm">
                    ⚡
                </div>
                <span class="text-sm font-extrabold tracking-tight text-[#0F172A]">AutoBugTriage</span>
                <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                    Wizard
                </span>
            </div>

            <a href="/logout" class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                <span>Sign Out</span>
            </a>
        </div>

        <!-- 3-Step Wizard Progress Bar -->
        <div class="mb-6 rounded-2xl border border-[#E2E8F0] bg-white p-4 shadow-xs">
            <div class="flex items-center justify-between text-xs font-bold">
                <!-- Step 1 (Active) -->
                <div class="flex items-center gap-2 text-indigo-600">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-white text-xs">1</span>
                    <span class="font-bold">Project Setup</span>
                </div>
                <div class="h-0.5 flex-1 mx-3 bg-[#E2E8F0]"></div>
                <!-- Step 2 -->
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 border border-slate-300 text-xs text-slate-500">2</span>
                    <span>Team & Skill Mapping</span>
                </div>
                <div class="h-0.5 flex-1 mx-3 bg-[#E2E8F0]"></div>
                <!-- Step 3 -->
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 border border-slate-300 text-xs text-slate-500">3</span>
                    <span>Workspace Launch</span>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 sm:p-10 shadow-sm card-hover-lift">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <span class="inline-block rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-bold text-indigo-700">
                        Manager: {{ session('manager_name', 'Lead') }}
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-[#0F172A] mt-2">
                        Configure Your Project Environment
                    </h1>
                    <p class="text-xs sm:text-sm text-[#64748B] mt-1">
                        Define project identity and tech stack dependencies for precision AI triage matching.
                    </p>
                </div>
                <div class="hidden sm:flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 text-2xl border border-indigo-100">
                    📁
                </div>
            </div>

            @if($errors->any())
                <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-800 flex items-start gap-2.5">
                    <span class="text-rose-500 font-bold mt-0.5">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="/create-project" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#0F172A] mb-1.5">Project Name</label>
                    <input type="text" name="project_name" required value="{{ old('project_name') }}" placeholder="e.g. Core SaaS Platform" 
                           class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-[#0F172A]">Technology Stack & Frameworks</label>
                        <span class="text-[11px] font-mono-code text-indigo-600 font-semibold">Selected Chips Auto-Fill Below</span>
                    </div>
                    <input type="text" id="techStackInput" name="project_stack" required value="{{ old('project_stack') }}" placeholder="e.g. Laravel, React, PostgreSQL, Docker" 
                           class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-4 py-2.5 text-xs sm:text-sm text-[#0F172A] placeholder-slate-400 outline-none transition focus:border-indigo-600 focus:bg-white focus:ring-2 focus:ring-indigo-600/10 font-mono-code">
                    
                    <!-- 15 Clickable Tech Chips -->
                    <div class="mt-3">
                        <p class="text-[11px] font-bold text-[#64748B] mb-2 uppercase tracking-wider">Quick Select Stack Chips:</p>
                        <div class="flex flex-wrap gap-1.5" id="chipContainer">
                            @php
                                $presetTechs = ['Laravel', 'React', 'Vue.js', 'Python', 'FastAPI', 'PostgreSQL', 'MySQL', 'Docker', 'Node.js', 'TypeScript', 'TailwindCSS', 'Kotlin', 'C++', 'Java', 'SQL'];
                            @endphp
                            @foreach($presetTechs as $tech)
                                <button type="button" onclick="toggleChip('{{ $tech }}', this)" 
                                        class="tech-chip rounded-lg border border-[#E2E8F0] bg-[#F8FAFC] px-2.5 py-1 text-xs font-semibold text-[#475569] hover:border-indigo-400 hover:text-indigo-600 transition">
                                    + {{ $tech }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- AI Note -->
                    <div class="mt-4 rounded-xl bg-amber-50 border border-amber-200/80 p-3 text-xs text-amber-900 flex items-start gap-2">
                        <span class="text-base shrink-0">💡</span>
                        <span><b>AI Agent Tip:</b> AI Agent এই টেক স্ট্যাকের ওপর ভিত্তি করেই ভবিষ্যতে বাগের ভাষা ও ক্যাটাগরি শনাক্ত করবে।</span>
                    </div>
                </div>

                <div class="pt-2 border-t border-[#E2E8F0]">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 py-3 px-4 text-xs sm:text-sm font-bold text-white shadow-lg shadow-indigo-600/25 transition hover:from-indigo-500 hover:to-blue-500 active:scale-[0.99]">
                        <span>Save & Proceed to Team Setup ➔</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleChip(tech, btn) {
            const input = document.getElementById('techStackInput');
            let current = input.value.split(',').map(s => s.trim()).filter(Boolean);
            
            if (current.includes(tech)) {
                current = current.filter(t => t !== tech);
                btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
                btn.classList.add('bg-[#F8FAFC]', 'text-[#475569]', 'border-[#E2E8F0]');
                btn.innerText = '+ ' + tech;
            } else {
                current.push(tech);
                btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
                btn.classList.remove('bg-[#F8FAFC]', 'text-[#475569]', 'border-[#E2E8F0]');
                btn.innerText = '✓ ' + tech;
            }
            input.value = current.join(', ');
        }
    </script>
</body>
</html>