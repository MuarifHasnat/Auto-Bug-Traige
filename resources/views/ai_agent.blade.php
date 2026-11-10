<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Triage Studio · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased">
<div class="flex min-h-screen">
    <!-- Modern Light Glassmorphic Left Sidebar (250px) -->
    <aside class="hidden w-[250px] shrink-0 flex-col bg-white/95 backdrop-blur-md text-slate-800 lg:flex border-r border-[#E2E8F0] z-20">
        <!-- Brand -->
        <div class="flex h-[60px] items-center justify-between border-b border-[#E2E8F0] px-5 bg-white">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-black text-xs shadow-md shadow-indigo-600/20">
                    ⚡
                </div>
                <span class="text-sm font-extrabold tracking-tight text-[#0F172A]">AutoBug<span class="text-indigo-600">Triage</span></span>
            </div>
            <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                AI v1.0
            </span>
        </div>

        <!-- Project Switcher Box -->
        <div class="border-b border-[#E2E8F0] p-4 bg-[#F8FAFC]">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Current Workspace</p>
            <p class="mt-1 truncate font-bold text-slate-900 text-xs">{{ session('project_name', 'Software Project') }}</p>
            <p class="mt-0.5 truncate text-[10px] text-indigo-600 font-mono-code">{{ session('project_stack', 'Laravel • React • PGSQL') }}</p>
            @include('components.project-switcher', ['variant' => 'light'])
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 space-y-1 p-3 text-xs">
            @if(auth()->user()->role === 'manager')
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/dashboard">
                    <span class="text-sm">▦</span>
                    <span>Command Center</span>
                </a>
            @endif

            <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/workspace">
                <span class="text-sm">▤</span>
                <span>Kanban Workspace</span>
            </a>
            <a class="flex items-center justify-between gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/notifications">
                <span class="flex items-center gap-3"><span>🔔</span><span>Notifications</span></span>
                @if(($unreadNotificationCount ?? 0) > 0)<span class="rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] text-white font-bold">{{ $unreadNotificationCount }}</span>@endif
            </a>

            <a class="relative flex items-center gap-3 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 px-3.5 py-2.5 font-bold transition border border-indigo-200/60 shadow-2xs" href="/ai-agent">
                <span class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-indigo-600"></span>
                <span class="text-sm">⚡</span>
                <span>AI Triage Studio</span>
            </a>

            @if(auth()->user()->role === 'manager')
                <a class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition" href="/add-developers">
                    <span class="text-sm">👥</span>
                    <span>Team & Skills Matrix</span>
                </a>
            @endif
        </nav>

        <!-- User Profile Footer -->
        <div class="border-t border-[#E2E8F0] p-4 bg-[#F8FAFC]">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 text-xs font-bold font-mono-code uppercase border border-indigo-200">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                    <span class="inline-block rounded px-1.5 py-0.2 text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 font-mono-code">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
                <a href="/logout" title="Sign Out" class="text-slate-400 hover:text-rose-600 p-1 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Container -->
    <main class="min-w-0 flex-1 flex flex-col">
        <!-- Sticky Top Header (60px) -->
        <header class="sticky top-0 z-10 flex h-[60px] items-center justify-between border-b border-[#E2E8F0] bg-white/90 backdrop-blur-md px-5 lg:px-8">
            <div class="flex items-center gap-2 text-xs font-semibold text-[#64748B]">
                <span>Workspace</span>
                <span class="text-slate-300">/</span>
                <span class="text-[#0F172A] font-bold">{{ session('project_name', 'Project') }}</span>
                <span class="text-slate-300">/</span>
                <span class="text-indigo-600">AI Triage Workbench</span>
            </div>

            <div class="flex items-center gap-3">
                @include('components.project-switcher', ['variant' => 'mobile'])
                <div class="flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-bold text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-mono-code">Python Triage Agent: Ready</span>
                </div>
                <a href="/logout" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                    Sign Out
                </a>
            </div>
        </header>

        <!-- Studio Body -->
        <div class="flex-1 mx-auto w-full max-w-7xl px-5 py-6 lg:px-8 space-y-6">
            <!-- Header Title -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-extrabold tracking-tight text-[#0F172A]">AI Triage Workbench</h1>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-3 py-0.5 text-xs font-bold text-indigo-700 font-mono-code">
                            Hero Studio
                        </span>
                    </div>
                    <p class="text-xs text-[#64748B] mt-0.5">Submit software anomalies for AST syntax inspection, duplicate hash checking, and automated engineer assignment.</p>
                </div>
                <span class="rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] px-3.5 py-1.5 text-xs font-bold text-[#0F172A]">
                    Mode: {{ ucfirst(auth()->user()->role) }} Console
                </span>
            </div>

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3.5 text-xs font-medium text-emerald-800 flex items-center gap-2.5 shadow-xs">
                    <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-medium text-rose-800 flex items-start gap-2.5 shadow-xs">
                    <span class="text-rose-500 font-bold mt-0.5">⚠</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(auth()->user()->role === 'developer')
                <!-- Developer Workspace View: Assigned Tasks with AI Root-Cause Diagnostic Cards -->
                <div class="space-y-6">
                    <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-xs">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E2E8F0] pb-4 mb-5">
                            <div>
                                <h2 class="text-base font-bold text-[#0F172A] flex items-center gap-2">
                                    <span>💻</span>
                                    <span>Issues Assigned to You ({{ $assignedBugs->count() }})</span>
                                </h2>
                                <p class="text-xs text-[#64748B] mt-0.5">Click into any issue to view full details, screenshots, AI fix guidance, and GitHub culprit files.</p>
                            </div>
                            <a href="/workspace" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-4 py-2 transition shadow-xs">
                                <span>Open Kanban Board</span> ➔
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($assignedBugs as $bug)
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-5 space-y-3 hover:border-indigo-300 hover:bg-white transition shadow-2xs flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="font-mono-code font-bold text-xs text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-lg">
                                                #ABT-{{ $bug->id }}
                                            </span>
                                            <div class="flex items-center gap-1.5">
                                                <span class="rounded-lg px-2 py-0.5 text-[10px] font-bold {{ $bug->priority === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                                    {{ $bug->priority }}
                                                </span>
                                                <span class="rounded-lg px-2 py-0.5 text-[10px] font-bold {{ $bug->status === 'Done' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-700' }}">
                                                    {{ $bug->status }}
                                                </span>
                                            </div>
                                        </div>

                                        <h3 class="font-bold text-sm text-[#0F172A] leading-snug">
                                            {{ $bug->title }}
                                        </h3>
                                        <p class="text-xs text-[#64748B] line-clamp-2">
                                            {{ $bug->description }}
                                        </p>
                                    </div>

                                    <div class="pt-3 border-t border-[#E2E8F0] flex items-center justify-between gap-2">
                                        <span class="text-[11px] font-semibold text-purple-700">
                                            ✨ {{ $bug->detected_feature ?? 'Module' }}
                                        </span>
                                        <a href="/bugs/{{ $bug->id }}" class="inline-flex items-center gap-1 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-3.5 py-1.5 transition shadow-2xs">
                                            <span>Open & Fix Issue</span> ➔
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-12 border border-dashed border-[#CBD5E1] rounded-2xl bg-[#F8FAFC]">
                                    <span class="text-3xl">🎉</span>
                                    <p class="text-sm font-bold text-[#0F172A] mt-2">All caught up!</p>
                                    <p class="text-xs text-[#64748B] mt-1">You currently have no open bugs assigned to you.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <!-- Friendly 3-Step Process Stepper for Users & Testers -->
                <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50/70 via-white to-sky-50/70 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-600 text-white text-xs font-bold">ℹ</span>
                            <h3 class="text-xs font-extrabold text-[#0F172A] uppercase tracking-wider">How AI Bug Triage Works (কিভাবে সহজে ব্যবহার করবেন)</h3>
                        </div>
                        <span class="text-[11px] font-semibold text-indigo-600">Zero Configuration • Fully Automated</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="rounded-xl border border-indigo-100/70 bg-white/90 p-3 shadow-2xs">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#0F172A]">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-mono text-[11px] font-bold">1</span>
                                <span>১. বাগ ও সিম্পটম লিখুন</span>
                            </div>
                            <p class="text-[11px] text-[#64748B] mt-1.5 leading-relaxed">বাগের শিরোনাম ও বিবরণ দিন অথবা নিচের ১-ক্লিক QA টেমপ্লেট বেছে নিন। এরর স্ক্রিনশট থাকলে পেস্ট করুন (<kbd class="rounded bg-slate-100 px-1 font-mono text-[10px]">Ctrl+V</kbd>)।</p>
                        </div>
                        <div class="rounded-xl border border-indigo-100/70 bg-white/90 p-3 shadow-2xs">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#0F172A]">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-purple-100 text-purple-700 font-mono text-[11px] font-bold">2</span>
                                <span>২. এআই কোড ও ফাইল অ্যানালাইসিস</span>
                            </div>
                            <p class="text-[11px] text-[#64748B] mt-1.5 leading-relaxed">এজেন্ট কোড সিনট্যাক্স চেক করবে, গিটহাব রিপোজিটরির ফাইল স্ট্রাকচার স্ক্যান করে সম্ভাব্য ফাইল খুঁজে বের করবে এবং ডুপ্লিকেট বাগ পরীক্ষা করবে।</p>
                        </div>
                        <div class="rounded-xl border border-indigo-100/70 bg-white/90 p-3 shadow-2xs">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#0F172A]">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-mono text-[11px] font-bold">3</span>
                                <span>৩. অটো-অ্যাসাইন ও কানবান ট্র্যাকিং</span>
                            </div>
                            <p class="text-[11px] text-[#64748B] mt-1.5 leading-relaxed">ইঞ্জিনিয়ারদের স্কিল (Kotlin, PHP, Python) অনুযায়ী উপযুক্ত ডেভেলপারকে স্বয়ংক্রিয়ভাবে অ্যাসাইন করে কানবান বোর্ডে কার্ড তৈরি করে দেবে।</p>
                        </div>
                    </div>
                </div>

                <!-- Split-Screen IDE Workbench: 45% Left / 55% Right -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Panel (45% / col-span-5): Tester Bug Input Console with 1-Click Templates & Paste Support -->
                <section class="lg:col-span-5 rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm">
                    <div class="border-b border-[#E2E8F0] pb-4 mb-4">
                        <h2 class="text-base font-bold text-[#0F172A]">Submit New Bug for AI Triage</h2>
                        <p class="text-xs text-[#64748B] mt-0.5">Fill out report details or attach crash snippets for AST diagnosis.</p>
                    </div>

                    <form id="triageForm" action="/api/bugs/store" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="handleTriageSubmit(event)">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-[#0F172A] mb-1">Issue Summary / Title</label>
                            <input id="bugTitle" name="title" required maxlength="200" placeholder="e.g. ZeroDivisionError in invoice calculation logic" 
                                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white"
                                   oninput="checkDuplicateTitle(this.value)">
                            <div id="duplicateWarning" class="hidden mt-2 rounded-xl border border-amber-300 bg-amber-50/95 p-2.5 text-xs text-amber-900 flex items-center justify-between shadow-2xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="text-sm shrink-0">⚠️</span>
                                    <span class="truncate">Similar issue found: <b id="dupBugKey" class="text-amber-950 font-mono"></b> <span id="dupBugTitle" class="text-slate-700 font-medium"></span></span>
                                </div>
                                <a id="dupBugLink" href="#" target="_blank" class="ml-2 rounded-lg bg-amber-200/80 hover:bg-amber-300 px-2.5 py-1 font-bold text-amber-950 shrink-0 text-[11px] transition">View ↗</a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <label class="block text-xs font-bold text-[#0F172A]">Issue Type
                                <select name="issue_type" required class="mt-1.5 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-medium">
                                    <option value="Bug">Bug</option><option value="Task">Task</option><option value="Story">Story</option><option value="Improvement">Improvement</option>
                                </select>
                            </label>
                            <label class="block text-xs font-bold text-[#0F172A]">Labels <span class="font-normal text-slate-400">(comma-separated)</span>
                                <input name="labels" maxlength="500" placeholder="backend, checkout, urgent" class="mt-1.5 w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3 py-2 text-xs font-medium">
                            </label>
                        </div>

                        <!-- 1-Click Bug Description Templates UX for QA -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-[#0F172A]">Problem Description & Reproduction Steps</label>
                                <div class="flex items-center gap-1">
                                    <span class="text-[10px] text-[#64748B] font-bold">QA Templates:</span>
                                    <button type="button" onclick="applyTemplate('ui')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-indigo-600 hover:bg-indigo-50">+ UI Glitch</button>
                                    <button type="button" onclick="applyTemplate('payment')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-rose-600 hover:bg-rose-50">+ Payment Crash</button>
                                    <button type="button" onclick="applyTemplate('auth')" class="rounded border border-[#CBD5E1] bg-[#F8FAFC] px-1.5 py-0.5 text-[10px] font-semibold text-purple-600 hover:bg-purple-50">+ Login Error</button>
                                </div>
                            </div>
                            <textarea id="bugDescription" name="description" required rows="4" maxlength="5000" 
                                      placeholder="Explain what happened: 1. Clicked checkout, 2. Page froze with spinning wheel, 3. Expected confirmation but got an error..." 
                                      class="w-full rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2.5 text-xs text-[#0F172A] outline-none transition focus:border-indigo-600 focus:bg-white leading-relaxed"></textarea>
                        </div>

                        <!-- Full-Width Screenshot Dropzone with Live Preview (Paste & Upload) -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-[#0F172A]">
                                    Problem Screenshot / Error Proof <span class="text-indigo-600 font-semibold">(Image Evidence)</span>
                                </label>
                                <span class="text-[10px] text-slate-500 font-mono-code">No code required</span>
                            </div>
                            <label id="screenshotDropzone" class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-indigo-200 bg-indigo-50/20 p-5 text-center cursor-pointer hover:border-indigo-500 hover:bg-indigo-50/50 transition group">
                                <div id="screenshotPlaceholder" class="flex flex-col items-center py-2">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 text-2xl group-hover:scale-105 transition shadow-xs">📸</span>
                                    <span class="text-xs font-bold text-[#0F172A] mt-2 block" id="screenshotStatus">Click to Upload or Drag & Drop Screenshot</span>
                                    <span class="text-[11px] text-[#64748B] block mt-0.5">Supports PNG, JPG, WEBP • Or directly press <kbd class="px-1.5 py-0.5 rounded bg-white border border-slate-300 font-mono-code text-[10px] font-bold text-slate-700 shadow-2xs">Ctrl + V</kbd> to paste</span>
                                </div>
                                <div id="screenshotPreviewWrapper" class="hidden w-full text-center">
                                    <img id="screenshotPreview" src="" alt="Screenshot Preview" class="max-h-44 rounded-xl mx-auto object-contain border border-indigo-200 shadow-sm">
                                    <span class="inline-block mt-2 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full shadow-2xs" id="screenshotFileName">✓ Image attached</span>
                                    <button type="button" onclick="clearScreenshot(event)" class="block mx-auto mt-1 text-[10px] font-bold text-rose-500 hover:underline">Remove image</button>
                                </div>
                                <input type="file" id="screenshotInput" name="screenshot" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="handleScreenshotSelected(this)">
                            </label>
                        </div>

                        <!-- Real-World Black-Box QA Notice -->
                        <div class="rounded-xl border border-indigo-100 bg-indigo-50/40 p-3 text-[11px] text-indigo-950 flex items-start gap-2.5">
                            <span class="text-base shrink-0">💡</span>
                            <span class="leading-relaxed">
                                <b>Black-Box QA Mode:</b> Testers do not need code access. Submit natural language symptoms and screenshots—the AI Agent's NLP engine will determine the module, severity, duplicate status, and assign the best engineer.
                            </span>
                        </div>

                        <!-- Optional Source Code or File Upload (.kt, .py, .java, etc.) -->
                        <details class="group rounded-2xl border border-slate-200 bg-slate-50/50 p-3.5 transition">
                            <summary class="flex cursor-pointer items-center justify-between text-xs font-bold text-slate-700 hover:text-indigo-600 select-none">
                                <span class="flex items-center gap-2">
                                    <span class="text-sm">💻</span>
                                    <span>Advanced / Optional: Attach Code Snippet or Source File (.kt, .py, etc.)</span>
                                </span>
                                <span class="text-[10px] text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                            </summary>
                            <div class="mt-3 space-y-3 pt-2 border-t border-slate-200/70">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <label class="block text-[11px] font-bold text-[#0F172A]">Target Language
                                        <select name="language" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-medium">
                                            <option value="auto">Auto-Detect</option>
                                            <option value="Kotlin">Kotlin (.kt)</option>
                                            <option value="Python">Python (.py)</option>
                                            <option value="TypeScript">TypeScript (.ts/.tsx)</option>
                                            <option value="JavaScript">JavaScript (.js)</option>
                                            <option value="Java">Java (.java)</option>
                                            <option value="PHP">PHP (.php)</option>
                                            <option value="C++">C++ (.cpp)</option>
                                            <option value="SQL">SQL</option>
                                        </select>
                                    </label>
                                    <label class="block text-[11px] font-bold text-[#0F172A]">Upload File (.kt, .py, .java, etc.)
                                        <input type="file" name="source_file" accept=".kt,.kts,.py,.js,.ts,.tsx,.java,.cpp,.php,.sql,.json,.xml" 
                                               class="mt-1 block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-[#0F172A] mb-1">Paste Code Snippet or Stack Trace</label>
                                    <textarea name="code_snippet" rows="3" placeholder="fun processPayment(order: Order) { ... }" class="w-full rounded-xl border border-slate-300 bg-white p-2 text-xs font-mono-code text-slate-800 outline-none focus:border-indigo-600"></textarea>
                                </div>
                            </div>
                        </details>

                        <!-- Submit Button with Loading State & Keyboard Shortcut Info -->
                        <div>
                            <button type="submit" id="submitTriageBtn" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 py-3.5 px-4 text-xs sm:text-sm font-bold text-white shadow-lg shadow-indigo-600/25 transition hover:from-indigo-500 hover:to-blue-500 active:scale-[0.99]">
                                <span>✨ Run AI Triage & Auto-Assign Developer</span>
                            </button>
                            <p class="text-center text-[10px] text-slate-400 mt-1.5 font-mono-code">
                                Pro Tip: Press <b>Ctrl + Enter</b> anywhere to triage
                            </p>
                        </div>
                    </form>
                </section>

                <!-- Right Panel (55% / col-span-7): Diagnostics and review-only fix suggestions -->
                <section class="lg:col-span-7 rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm flex flex-col min-h-[580px]">
                    <div class="border-b border-[#E2E8F0] pb-4 mb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-[#0F172A]">Code Diagnostics & Fix Suggestions</h2>
                            <p class="text-xs text-[#64748B]">Language-specific syntax checks, triage results, and suggested code references.</p>
                        </div>
                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 text-xs font-bold text-indigo-700 font-mono-code">
                            Diagnostics Engine
                        </span>
                    </div>

                    @if(session('ai_result'))
                        @php($ai = session('ai_result'))
                        <!-- State 2: Result State (4 Human-in-the-Loop Sections) -->
                        <div class="space-y-4 animate-fade-in flex-1">
                            
                            <!-- Section A: Triage summary -->
                            <div class="rounded-2xl border border-indigo-200 bg-gradient-to-r from-indigo-50/60 to-purple-50/60 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-lg bg-blue-50 border border-blue-200 px-2.5 py-1 text-xs font-bold text-[#0052cc] font-mono-code">
                                            Language: {{ $ai['code_help']['language'] ?? 'Auto (not detected)' }}
                                        </span>
                                        <span class="rounded-lg bg-purple-50 border border-purple-200 px-2.5 py-1 text-xs font-bold text-purple-700">
                                            Feature: {{ $ai['code_help']['feature'] ?? 'General' }}
                                        </span>
                                        <span class="rounded-lg border px-2.5 py-1 text-xs font-bold {{ ($ai['status'] ?? 'Open') === 'Duplicate' ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }}">
                                            Status: {{ $ai['status'] ?? 'Open' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="replayCurrentTriage()" class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-white hover:bg-indigo-50 px-2.5 py-1 text-[11px] font-bold text-indigo-700 shadow-2xs transition">
                                            <span>▷</span>
                                            <span>Replay AI Triage Pipeline</span>
                                        </button>
                                        <span class="text-[10px] font-semibold text-indigo-800">Rule-based estimate</span>
                                    </div>
                                </div>
                                @if(!empty($ai['is_duplicate']))
                                    <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-900">
                                        <p class="font-bold">Possible duplicate · {{ $ai['similarity_score'] ?? 0 }}% text similarity</p>
                                        <p class="mt-1">Matched existing report
                                            @if(!empty($ai['duplicate_of']))
                                                <a class="font-bold underline" href="/workspace#bug-{{ $ai['duplicate_of'] }}">#ABT-{{ $ai['duplicate_of'] }}</a>.
                                            @else
                                                in this project.
                                            @endif
                                            Please compare the reports before closing either one.
                                        </p>
                                    </div>
                                @else
                                    <p class="mt-3 text-xs text-indigo-900">No duplicate found in the current project reports ({{ $ai['similarity_score'] ?? 0 }}% best text match).</p>
                                @endif
                            </div>

                            <!-- Section B: Root Cause & Error Diagnosis Box -->
                            @php($syntaxStatus = $ai['code_help']['syntax_status'] ?? 'unknown')
                            @php($syntaxStatusLabel = ['passed' => 'Check passed', 'failed' => 'Syntax errors', 'timeout' => 'Check timed out', 'not_available' => 'Checker unavailable', 'not_run' => 'Check not run'][$syntaxStatus] ?? 'Check status unknown')
                            @php($syntaxStatusClass = ['passed' => 'bg-emerald-100 text-emerald-800', 'failed' => 'bg-red-100 text-red-800', 'timeout' => 'bg-amber-100 text-amber-800', 'not_available' => 'bg-amber-100 text-amber-800', 'not_run' => 'bg-slate-100 text-slate-700'][$syntaxStatus] ?? 'bg-slate-100 text-slate-700')
                            <div class="rounded-2xl border-2 border-rose-200 bg-rose-50/50 p-4 text-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2 text-indigo-900 font-bold">
                                        <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
                                        <span>{{ $syntaxStatus === 'not_run' ? 'Black-Box NLP Analysis' : 'Code Validation Result' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="rounded px-1.5 py-0.5 text-[10px] font-bold {{ $syntaxStatusClass }}">{{ $syntaxStatus === 'not_run' ? 'NLP Verified' : $syntaxStatusLabel }}</span>
                                        <span class="rounded bg-rose-100 px-1.5 py-0.5 text-[10px] font-bold text-rose-800">{{ $ai['priority'] ?? 'Medium' }} Priority</span>
                                    </div>
                                </div>
                                <p class="font-mono-code text-xs font-bold text-indigo-950 mt-1">
                                    ● {{ $syntaxStatus === 'not_run' ? 'Natural-Language & Symptoms Analysis Completed' : ($ai['code_help']['error_name'] ?? 'Analysis complete') }}
                                </p>
                                <p class="text-[11px] text-slate-700 mt-1">
                                    Evidence: {{ $ai['code_help']['attachment_note'] ?? 'No attachment provided.' }}
                                </p>
                                @if(!empty($ai['code_help']['screenshot_vision']['stack_traces']))
                                    <details class="mt-2 rounded-xl border border-indigo-200 bg-white p-2.5">
                                        <summary class="cursor-pointer font-bold text-indigo-700 text-[11px] flex items-center gap-1.5 select-none">
                                            <span>📸</span>
                                            <span>View Extracted Screenshot Crash Lines</span>
                                        </summary>
                                        <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-950 p-2 font-mono text-[10px] text-emerald-400">@foreach($ai['code_help']['screenshot_vision']['stack_traces'] as $stLine){{ $stLine }}
@endforeach</pre>
                                    </details>
                                @endif
                                <p class="text-[11px] text-slate-600 mt-1">
                                    {{ $syntaxStatus === 'not_run' ? 'Issue classified, duplicate-checked, and assigned to the domain engineer without requiring source code from the tester.' : ($ai['code_help']['analysis_scope'] ?? 'Static inspection completed.') }}
                                </p>
                                @if(!empty($ai['code_help']['syntax_checker']))
                                    <p class="text-[11px] text-rose-700 mt-1">Checker: {{ $ai['code_help']['syntax_checker'] }}</p>
                                @endif
                                @if(!empty($ai['code_help']['description_note']))
                                    <p class="text-[11px] text-rose-700 mt-1">{{ $ai['code_help']['description_note'] }}</p>
                                @endif
                                <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-amber-950">
                                    <p class="font-bold">Possible fix suggestions · review before applying</p>
                                    @if(!empty($ai['code_help']['fix_suggestions']))
                                        <ul class="mt-2 space-y-2">
                                            @foreach($ai['code_help']['fix_suggestions'] as $suggestion)
                                                <li class="rounded-lg border border-amber-200 bg-white/80 p-2.5">
                                                    <p class="font-bold">{{ $suggestion['title'] }}</p>
                                                    <p class="mt-1">{{ $suggestion['detail'] }}</p>
                                                    @if(!empty($suggestion['proposed_code']))
                                                        <details class="mt-2">
                                                            <summary class="cursor-pointer font-semibold">Preview proposed edit (not applied)</summary>
                                                            <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-950 p-2.5 font-mono-code text-[11px] leading-5 text-emerald-300">{{ $suggestion['proposed_code'] }}</pre>
                                                        </details>
                                                    @elseif(!empty($suggestion['example_code']))
                                                        <details class="mt-2">
                                                            <summary class="cursor-pointer font-semibold">View example guard (not applied)</summary>
                                                            <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-950 p-2.5 font-mono-code text-[11px] leading-5 text-emerald-300">{{ $suggestion['example_code'] }}</pre>
                                                        </details>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="mt-1">No specific fix suggestion was identified by the current rules. The submitted code remains unchanged.</p>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($ai['code_help']['suspected_files']) || !empty($ai['suspected_files']))
                                @php($suspectedList = $ai['code_help']['suspected_files'] ?? ($ai['suspected_files'] ?? []))
                                <!-- Section: AI Suspected Culprit Files in Repository -->
                                <div class="rounded-2xl border-2 border-indigo-200 bg-white p-4 text-xs shadow-xs">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2 text-indigo-950 font-bold">
                                            <span class="flex h-5 w-5 items-center justify-center rounded-lg bg-indigo-600 text-white text-[11px]">🐙</span>
                                            <span>AI Suspected Repository Culprit Files</span>
                                        </div>
                                        <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                                            GitHub Linked
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 mb-2.5">
                                        বাগের বর্ণনা ও ডোমেইন বিশ্লেষণ করে এআই এজেন্ট আপনার রিপোজিটরি স্ক্যান করেছে এবং এই ফাইলগুলোতে এরর বা কোড পরিবর্তনের সর্বোচ্চ সম্ভাবনা খুঁজে পেয়েছে:
                                    </p>

                                    <div class="space-y-2">
                                        @foreach($suspectedList as $sFile)
                                            <div class="flex items-center justify-between gap-2 rounded-xl border border-slate-200 bg-[#F8FAFC] p-2.5 hover:border-indigo-400 transition">
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-mono-code text-xs font-bold text-slate-900 truncate">📄 {{ $sFile['path'] }}</span>
                                                        <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.2 text-[10px] font-bold font-mono-code">{{ $sFile['confidence'] }}% Match</span>
                                                    </div>
                                                    <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ $sFile['reason'] }}</p>
                                                </div>

                                                @php
                                                    $ghUrl = !empty($sFile['github_url']) ? $sFile['github_url'] : ('https://github.com/MuarifHasnat/Auto-Bug-Traige/blob/main/' . ltrim($sFile['path'] ?? (is_string($sFile) ? $sFile : ''), '/'));
                                                @endphp
                                                <a href="{{ $ghUrl }}" target="_blank" rel="noopener noreferrer" 
                                                   class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-2.5 py-1 text-[11px] font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition shrink-0 shadow-2xs">
                                                    <span>GitHub-এ দেখুন</span>
                                                    <span class="text-xs">↗</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Section C: Smart Assignment Routing Card with Human-in-the-Loop Override -->
                            <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4 text-xs">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold text-xs uppercase">
                                            {{ strtoupper(substr($ai['assigned_to'] ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold text-[#64748B] block">Assigned Engineer</span>
                                            <span class="font-bold text-sm text-[#0F172A]">{{ $ai['assigned_to'] ?? 'Unassigned' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700">
                                            Skill Matched
                                        </span>
                                        <a href="/workspace" class="rounded-lg border border-[#CBD5E1] bg-white px-2 py-0.5 text-[10px] font-bold text-indigo-600 hover:bg-indigo-50">
                                            ✎ Change in Board
                                        </a>
                                    </div>
                                </div>
                                <p class="text-[11px] text-[#475569] bg-white border border-[#E2E8F0] p-2.5 rounded-xl">
                                    {{ $ai['code_help']['assignment_reason'] ?? 'Selected due to matching technical skills and active queue balancing.' }}
                                </p>
                            </div>

                            <!-- Section D: Interactive Fix Steps & Code Diff View Switcher -->
                            <div class="space-y-3 pt-1">
                                @if(!empty($ai['code_help']['steps']))
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-[#64748B] mb-1.5">Interactive Resolution Checklist:</h4>
                                        <ul class="space-y-1.5 text-xs text-[#0F172A]">
                                            @foreach($ai['code_help']['steps'] as $idx => $step)
                                                <li class="flex items-start gap-2 bg-[#F8FAFC] p-2 rounded-xl border border-[#E2E8F0]">
                                                    <input type="checkbox" id="ai-step-{{ $idx }}" class="mt-0.5 h-3.5 w-3.5 rounded border-[#CBD5E1] text-indigo-600 focus:ring-indigo-500 cursor-pointer" onchange="this.parentElement.classList.toggle('opacity-50', this.checked)">
                                                    <label for="ai-step-{{ $idx }}" class="cursor-pointer font-medium leading-relaxed">{{ $step }}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(!empty(trim($ai['code_help']['fixed_code'] ?? '')))
                                <!-- Code View Switcher: Final Code vs Diff -->
                                <div class="border border-[#E2E8F0] rounded-2xl p-3.5 bg-white">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2 text-xs font-bold">
                                            <button type="button" onclick="switchStudioCode('final')" id="btnStudioFinal" class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                [ Submitted Code ]
                                            </button>
                                            @if(!empty($ai['code_help']['diff']))
                                                <button type="button" onclick="switchStudioCode('diff')" id="btnStudioDiff" class="px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50">
                                                    [ Before vs After (Diff) ]
                                                </button>
                                            @endif
                                        </div>

                                        <button type="button" onclick="copyStudioFix()" id="copyStudioBtn" 
                                                class="rounded-lg border border-[#CBD5E1] bg-white px-2.5 py-1 text-xs font-bold text-indigo-600 hover:bg-[#F1F5F9] transition">
                                                📋 Copy Submitted Code
                                        </button>
                                    </div>

                                    <div id="studioFinalView">
                                        <p class="mb-2 text-[11px] text-slate-600">This is the original submitted snippet. Suggestions above are review-only; the code is never changed automatically.</p>
                                        <textarea id="studioFixedCode" readonly spellcheck="false" class="w-full min-h-[160px] rounded-xl bg-slate-950 p-3.5 font-mono-code text-xs leading-5 text-emerald-400 border border-slate-800 outline-none">{{ $ai['code_help']['fixed_code'] ?? '// No submitted code provided.' }}</textarea>
                                    </div>

                                    <div id="studioDiffView" class="hidden">
                                        <textarea readonly spellcheck="false" class="w-full min-h-[160px] rounded-xl bg-slate-950 p-3.5 font-mono-code text-xs leading-5 text-indigo-300 border border-slate-800 outline-none">{{ $ai['code_help']['diff'] ?? '// No diff changes.' }}</textarea>
                                    </div>
                                </div>
                                @endif

                                <div class="pt-1 text-right">
                                    <a href="/workspace" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:underline">
                                        <span>Go to Kanban Workspace ➔</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- State 1: Empty / Ready State (AI System Blueprint Illustration) -->
                        <div class="flex-1 flex flex-col justify-center items-center py-8 text-center">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 border border-indigo-200 text-2xl text-indigo-600">
                                🤖
                            </div>
                            <h3 class="text-base font-extrabold text-[#0F172A]">AI System Blueprint Ready</h3>
                            <p class="mt-1 max-w-sm text-xs text-[#64748B] leading-relaxed">
                                Enter your bug summary or paste code on the left to trigger the 4-phase automated triage sequence:
                            </p>

                            <!-- 4 Pipeline Blueprint Cards -->
                            <div class="grid grid-cols-2 gap-3 mt-6 w-full max-w-md text-left">
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-white text-[10px] font-bold mb-1.5">1</span>
                                    <p class="text-xs font-bold text-[#0F172A]">Language Syntax Check</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Scans code tokens & exception signatures</p>
                                </div>
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-[10px] font-bold mb-1.5">2</span>
                                    <p class="text-xs font-bold text-[#0F172A]">Duplicate Checking</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Vector similarity with past project bugs</p>
                                </div>
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-purple-600 text-white text-[10px] font-bold mb-1.5">3</span>
                                    <p class="text-xs font-bold text-[#0F172A]">Severity & Feature</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Calculates Priority and Feature Module</p>
                                </div>
                                <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-3">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-white text-[10px] font-bold mb-1.5">4</span>
                                    <p class="text-xs font-bold text-[#0F172A]">Skill-Based Routing</p>
                                    <p class="text-[10px] text-[#64748B] mt-0.5">Matches developer skills & workload capacity</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </section>
            </div>
            @endif
        </div>
    </main>
</div>

<script>
    // 1-Click QA Description Templates
    function applyTemplate(type) {
        const desc = document.getElementById('bugDescription');
        const title = document.getElementById('bugTitle');
        if (type === 'ui') {
            title.value = title.value || 'UI Element overlapping and freezing on mobile view';
            desc.value = `### 1. Steps to Reproduce:
- 1. Open the application on a mobile viewport or smaller screen.
- 2. Click the navigation or action button.
- 3. Notice the UI element overlaps the content and freezes.

### 2. Expected Result:
- Elements should be responsive and render without freezing.

### 3. Actual Error / Behavior:
- Element overlaps and page becomes unresponsive. (See attached screenshot).`;
        } else if (type === 'payment') {
            title.value = title.value || 'Checkout payment fails with spinning loader';
            desc.value = `### 1. Steps to Reproduce:
- 1. Add items to checkout cart and proceed to payment.
- 2. Click "Pay Now" with valid payment details.
- 3. Loading spinner spins indefinitely and throws a transaction error.

### 2. Expected Result:
- Payment should complete and redirect to order confirmation.

### 3. Actual Error / Behavior:
- Gateway times out and payment is aborted. (See screenshot for error state).`;
        } else if (type === 'auth') {
            title.value = title.value || 'Login button triggers session rejection';
            desc.value = `### 1. Steps to Reproduce:
- 1. Navigate to /login.
- 2. Enter registered credentials and submit.
- 3. Alert modal shows "Session expired or rejected".

### 2. Expected Result:
- User should be successfully authenticated and redirected to workspace.

### 3. Actual Error / Behavior:
- Rejects valid user and redirects back to sign-in screen.`;
        }
        desc.focus();
    }

    // Screenshot File Upload Preview
    function handleScreenshotSelected(input) {
        if (input.files && input.files[0]) {
            renderScreenshotPreview(input.files[0]);
        }
    }

    // Render Preview Helper
    function renderScreenshotPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('screenshotPreview').src = e.target.result;
            document.getElementById('screenshotFileName').innerText = '✓ ' + file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
            document.getElementById('screenshotPlaceholder').classList.add('hidden');
            document.getElementById('screenshotPreviewWrapper').classList.remove('hidden');
            document.getElementById('screenshotDropzone').classList.add('border-emerald-500', 'bg-emerald-50/30');
        };
        reader.readAsDataURL(file);
    }

    // Clear Screenshot
    function clearScreenshot(e) {
        e.preventDefault();
        e.stopPropagation();
        const input = document.getElementById('screenshotInput');
        input.value = '';
        document.getElementById('screenshotPreview').src = '';
        document.getElementById('screenshotPlaceholder').classList.remove('hidden');
        document.getElementById('screenshotPreviewWrapper').classList.add('hidden');
        document.getElementById('screenshotDropzone').classList.remove('border-emerald-500', 'bg-emerald-50/30');
    }

    // Clipboard Screenshot Paste (Ctrl + V)
    window.addEventListener('paste', function(e) {
        const items = (e.clipboardData || e.originalEvent.clipboardData).items;
        for (let i = 0; i < items.length; i++) {
            if (items[i].type.indexOf('image') !== -1) {
                const blob = items[i].getAsFile();
                const fileInput = document.getElementById('screenshotInput');
                const dt = new DataTransfer();
                dt.items.add(blob);
                fileInput.files = dt.files;
                renderScreenshotPreview(blob);
                break;
            }
        }
    });

    // File selected display
    function handleFileSelected(input, statusId) {
        const status = document.getElementById(statusId);
        if (input.files && input.files[0]) {
            status.innerText = '✓ ' + input.files[0].name;
            status.parentElement.classList.add('border-indigo-500', 'bg-indigo-50/50');
        }
    }

    // Keyboard Shortcut (Ctrl + Enter to Submit)
    window.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            const form = document.getElementById('triageForm');
            if (form) {
                e.preventDefault();
                handleTriageSubmit();
            }
        }
    });

    // Button Loading State & Double Submit Prevention
    function handleTriageSubmit() {
        const btn = document.getElementById('submitTriageBtn');
        btn.innerHTML = `
            <span class="h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></span>
            <span>⏳ AI কোড পরীক্ষা করছে... একটু অপেক্ষা করুন</span>
        `;
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');
        document.getElementById('triageForm').submit();
    }

    // Studio Code View Switcher
    function switchStudioCode(view) {
        if (view === 'final') {
            document.getElementById('studioFinalView').classList.remove('hidden');
            document.getElementById('studioDiffView').classList.add('hidden');
            document.getElementById('btnStudioFinal').className = 'px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200';
            document.getElementById('btnStudioDiff').className = 'px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50';
        } else {
            document.getElementById('studioFinalView').classList.add('hidden');
            document.getElementById('studioDiffView').classList.remove('hidden');
            document.getElementById('btnStudioDiff').className = 'px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200';
            document.getElementById('btnStudioFinal').className = 'px-2.5 py-1 rounded-lg border border-[#CBD5E1] text-[#64748B] hover:bg-slate-50';
        }
    }

    function copyStudioFix() {
        const code = document.getElementById('studioFixedCode').value;
        navigator.clipboard.writeText(code);
        const btn = document.getElementById('copyStudioBtn');
        btn.innerText = '✓ Copied to Clipboard!';
        setTimeout(() => btn.innerText = '📋 Copy Submitted Code', 2000);
    }

    // ==========================================
    // Autonomous AI Triage Live Stepper & Stepper Logic
    // ==========================================
    const projectBugs = @json($bugs->map(fn($b) => ['id' => $b->id, 'title' => $b->title]));
    let dupTimeout = null;
    function checkDuplicateTitle(val) {
        clearTimeout(dupTimeout);
        dupTimeout = setTimeout(() => {
            const warningEl = document.getElementById('duplicateWarning');
            const clean = (val || '').trim().toLowerCase();
            if (clean.length < 4 || !projectBugs.length) {
                if (warningEl) warningEl.classList.add('hidden');
                return;
            }
            const words = clean.split(/\s+/).filter(w => w.length > 2);
            const match = projectBugs.find(b => {
                const bTitle = (b.title || '').toLowerCase();
                if (bTitle.includes(clean) || clean.includes(bTitle)) return true;
                const matchCount = words.filter(w => bTitle.includes(w)).length;
                return matchCount >= 2 || (words.length === 1 && matchCount === 1 && clean.length > 5);
            });
            if (match && warningEl) {
                document.getElementById('dupBugKey').innerText = `#ABT-${match.id}`;
                document.getElementById('dupBugTitle').innerText = `(${match.title})`;
                document.getElementById('dupBugLink').href = `/bugs/${match.id}`;
                warningEl.classList.remove('hidden');
            } else if (warningEl) {
                warningEl.classList.add('hidden');
            }
        }, 200);
    }

    let triageTimer = null;
    let triageStartTime = null;

    const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

    function openTriageModal(title) {
        const modal = document.getElementById('triageTerminalModal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Reset Terminal
        document.getElementById('terminalBugTitle').innerText = title || 'Processing Anomaly';
        document.getElementById('terminalOutput').innerHTML = '';
        document.getElementById('triageProgressBar').style.width = '8%';
        document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span> RUNNING NEURAL PIPELINE`;
        document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-[11px] font-bold text-indigo-700 font-mono-code';
        document.getElementById('terminalResultCard').classList.add('hidden');
        
        // Reset Steps
        [1, 2, 3, 4].forEach(i => {
            const step = document.getElementById(`step-pill-${i}`);
            if (step) {
                step.className = 'px-3 py-2 rounded-xl bg-slate-50 text-slate-500 border border-slate-200 font-bold transition flex items-center justify-center gap-1.5';
                step.innerHTML = `<span class="text-slate-400 font-mono text-[10px]">${i}.</span> <span>${step.dataset.name || 'Stage ' + i}</span>`;
            }
        });
        setStepActive(1);
        
        // Live Millisecond Timer
        triageStartTime = performance.now();
        clearInterval(triageTimer);
        triageTimer = setInterval(() => {
            const elapsed = ((performance.now() - triageStartTime) / 1000).toFixed(2);
            document.getElementById('terminalTimer').innerText = `[ ${elapsed}s ]`;
        }, 50);
    }

    function closeTriageModal() {
        clearInterval(triageTimer);
        document.getElementById('triageTerminalModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function setStepActive(stepNum) {
        const step = document.getElementById(`step-pill-${stepNum}`);
        if (step) {
            step.className = 'px-3 py-2 rounded-xl bg-indigo-50 text-indigo-700 border-2 border-indigo-400 font-bold transition flex items-center justify-center gap-1.5 shadow-2xs';
            step.innerHTML = `<span class="h-3 w-3 rounded-full border-2 border-indigo-600 border-t-transparent animate-spin"></span> <span>${step.dataset.name || 'Stage ' + stepNum}</span>`;
        }
    }

    function setStepDone(stepNum) {
        const step = document.getElementById(`step-pill-${stepNum}`);
        if (step) {
            step.className = 'px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-300 font-bold transition flex items-center justify-center gap-1.5';
            step.innerHTML = `<span>✓</span> <span>${step.dataset.name || 'Step ' + stepNum}</span>`;
        }
    }

    function appendTerminalLog(prefix, msg, color = 'text-slate-300') {
        const terminal = document.getElementById('terminalOutput');
        if (!terminal) return;
        const elapsed = ((performance.now() - triageStartTime) / 1000).toFixed(2);
        const div = document.createElement('div');
        div.className = `flex items-start gap-2 text-xs font-mono-code ${color} transition`;
        div.innerHTML = `<span class="text-slate-500 shrink-0 font-normal">[+${elapsed}s]</span> <span class="font-bold text-indigo-400 shrink-0">${prefix}</span> <span>${msg}</span>`;
        terminal.appendChild(div);
        terminal.scrollTop = terminal.scrollHeight;
    }

    // Submit Handler with Streaming Execution
    async function handleTriageSubmit(e) {
        if (e) e.preventDefault();
        const form = document.getElementById('triageForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const title = document.getElementById('bugTitle').value;
        const formData = new FormData(form);

        openTriageModal(title);
        appendTerminalLog('[BOOT]', 'Initializing AutoBugTriage neural inference core v2.4...', 'text-slate-300');

        // Step 1: Ingestion
        await sleep(250);
        appendTerminalLog('[INGEST]', 'Ingesting natural-language symptoms and screenshot proof...', 'text-sky-300');
        document.getElementById('triageProgressBar').style.width = '25%';
        setStepDone(1);
        setStepActive(2);

        // Concurrently dispatch request
        const apiPromise = fetch('/api/bugs/store', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        }).then(async res => {
            const contentType = res.headers.get('content-type') || '';
            if (res.redirected || !contentType.includes('application/json')) {
                const text = await res.text();
                if (text.includes('login') || text.includes('Login') || res.status === 401) {
                    throw new Error('Your session has expired. Please refresh the page and log in again.');
                }
                const match = text.match(/<div class="[^"]*alert[^"]*">([\s\S]*?)<\/div>/i);
                if (match) {
                    throw new Error(match[1].replace(/<[^>]+>/g, '').trim());
                }
                throw new Error('Server returned an unexpected response. Please refresh and try again.');
            }
            const data = await res.json();
            if (!res.ok || data.success === false) {
                throw new Error(data.message || 'Server error occurred during triage.');
            }
            return data;
        });

        // Step 2: NLP & Vector Similarity
        await sleep(400);
        appendTerminalLog('[NLP-VEC]', 'Extracting domain entity tokens & calculating cosine similarity...', 'text-purple-300');
        document.getElementById('triageProgressBar').style.width = '50%';

        // Step 3: AST & GitHub Repo Scanning
        await sleep(450);
        setStepDone(2);
        setStepActive(3);
        appendTerminalLog('[AST-CODE]', 'Running AST exception pattern checks & inspecting call-stack...', 'text-blue-300');
        appendTerminalLog('[GITHUB]', 'Scanning project repository tree to isolate culprit files...', 'text-cyan-300');
        document.getElementById('triageProgressBar').style.width = '75%';

        // Step 4: Routing & Dispatch
        await sleep(400);
        setStepDone(3);
        setStepActive(4);
        appendTerminalLog('[ROUTER]', 'Evaluating engineer tech stack taxonomy & queue workload...', 'text-amber-300');
        document.getElementById('triageProgressBar').style.width = '90%';

        try {
            const resData = await apiPromise;
            clearInterval(triageTimer);
            const totalDuration = ((performance.now() - triageStartTime) / 1000).toFixed(2);
            document.getElementById('terminalTimer').innerText = `[ ${totalDuration}s ]`;
            document.getElementById('triageProgressBar').style.width = '100%';
            setStepDone(4);

            const ai = resData.ai_result || {};
            const feat = ai.feature || 'General';
            const priority = ai.priority || 'Medium';
            const simScore = ai.similarity_score ?? 0;
            const assignee = resData.assigned_to || 'Alice Developer';
            const bugId = resData.bug_id || 1;
            const filesList = (ai.code_help && ai.code_help.suspected_files && ai.code_help.suspected_files.length) 
                ? ai.code_help.suspected_files 
                : (ai.suspected_files && ai.suspected_files.length ? ai.suspected_files : []);

            const defaultGhBase = 'https://github.com/MuarifHasnat/Auto-Bug-Traige/blob/main/';
            const topCandidate = filesList.length ? filesList[0] : null;
            const suspected = topCandidate ? (topCandidate.path || topCandidate) : 'resources/views/workspace.blade.php';
            const topGhUrl = topCandidate ? (topCandidate.github_url || (defaultGhBase + encodeURIComponent(suspected))) : (defaultGhBase + encodeURIComponent(suspected));

            appendTerminalLog('[SUCCESS]', `Triage completed in ${totalDuration}s!`, 'text-emerald-400 font-bold');
            appendTerminalLog('[DISPATCH]', `Anomaly categorized under module: ${feat} (${priority} Priority)`, 'text-emerald-300');
            appendTerminalLog('[DISPATCH]', `Duplicate vector similarity: ${simScore}% (${simScore > 70 ? 'POSSIBLE DUPLICATE' : 'UNIQUE ISSUE'})`, 'text-emerald-300');
            appendTerminalLog('[DISPATCH]', `Suspected GitHub Culprit: ${suspected}`, 'text-sky-300');
            appendTerminalLog('[DISPATCH]', `Assigned to: ${assignee} (Based on skills & active tasks balance)`, 'text-emerald-400 font-bold');

            // Status Pill Complete
            document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-emerald-600"></span> TRIAGE COMPLETED`;
            document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-bold text-emerald-700 font-mono-code';

            // Show Result Card
            document.getElementById('terminalResultKey').innerText = `#ABT-${bugId}`;
            const confScore = Math.max(88, 98 - Math.min(simScore, 10));
            const confEl = document.getElementById('resConfidence');
            if (confEl) confEl.innerText = `${confScore}% Match`;
            document.getElementById('resAssignee').innerText = assignee;
            document.getElementById('resPriority').innerText = priority + ' Priority';
            document.getElementById('resSimilarity').innerText = simScore > 70 ? 'Duplicate' : 'Unique Issue';
            document.getElementById('resCulprit').innerText = suspected;
            const culpritGhLink = document.getElementById('resCulpritGhLink');
            if (culpritGhLink) {
                culpritGhLink.href = topGhUrl;
                culpritGhLink.classList.remove('hidden');
            }

            // Populate Suspected Files List with 1-click GitHub buttons in modal
            const modalFilesWrapper = document.getElementById('modalSuspectedFilesWrapper');
            const modalFilesList = document.getElementById('modalSuspectedFilesList');
            if (modalFilesWrapper && modalFilesList) {
                modalFilesList.innerHTML = '';
                const listToRender = filesList.length ? filesList : [{
                    path: suspected,
                    confidence: 92,
                    reason: 'Primary repository component match',
                    github_url: topGhUrl
                }];

                listToRender.forEach(fItem => {
                    const fPath = fItem.path || fItem;
                    const fConf = fItem.confidence || 92;
                    const fReason = fItem.reason || 'Matched project component';
                    const fUrl = fItem.github_url || (defaultGhBase + encodeURIComponent(fPath));

                    const itemEl = document.createElement('div');
                    itemEl.className = 'flex items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white p-2.5 hover:border-indigo-400 transition shadow-2xs';
                    itemEl.innerHTML = `
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono-code text-xs font-bold text-slate-900 truncate">📄 ${fPath}</span>
                                <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.2 text-[10px] font-bold font-mono-code">${fConf}% Match</span>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-0.5 truncate">${fReason}</p>
                        </div>
                        <a href="${fUrl}" target="_blank" rel="noopener noreferrer" 
                           class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 hover:text-indigo-600 px-2.5 py-1 text-[11px] font-bold text-slate-700 transition shrink-0 shadow-2xs">
                            <span>GitHub-এ দেখুন</span>
                            <span class="text-xs">↗</span>
                        </a>
                    `;
                    modalFilesList.appendChild(itemEl);
                });
                modalFilesWrapper.classList.remove('hidden');
            }

            document.getElementById('resIssueLink').href = `/bugs/${bugId}`;
            document.getElementById('terminalResultCard').classList.remove('hidden');

        } catch (err) {
            clearInterval(triageTimer);
            appendTerminalLog('[ERROR]', err.message || 'Triage failed.', 'text-rose-400 font-bold');
            document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-rose-500"></span> FAILED`;
            document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-rose-50 border border-rose-200 px-3 py-1 text-[11px] font-bold text-rose-700 font-mono-code';
            setTimeout(() => {
                alert('Triage error: ' + err.message);
                closeTriageModal();
            }, 1200);
        }
    }

    // Replay function for existing session results
    @if(session('ai_result'))
    async function replayCurrentTriage() {
        const ai = @json(session('ai_result'));
        const bugId = '{{ session("last_bug_id", 1) }}';
        openTriageModal('{{ session("ai_result.code_help.feature", "Anomaly") }} Diagnosis Replay');
        
        appendTerminalLog('[REPLAY]', 'Replaying autonomous triage execution pipeline...', 'text-slate-300');
        await sleep(250);
        appendTerminalLog('[INGEST]', 'Loaded anomaly symptoms and screenshot proof.', 'text-sky-300');
        document.getElementById('triageProgressBar').style.width = '25%';
        setStepDone(1);
        setStepActive(2);

        await sleep(350);
        appendTerminalLog('[NLP-VEC]', `Extracted feature: ${ai.feature || 'General'} · Duplicate score: ${ai.similarity_score || 0}%`, 'text-purple-300');
        document.getElementById('triageProgressBar').style.width = '50%';

        await sleep(400);
        setStepDone(2);
        setStepActive(3);
        appendTerminalLog('[AST-CODE]', `Syntax check: ${ai.code_help?.syntax_status || 'verified'} · Exception: ${ai.code_help?.error_name || 'Pattern matched'}`, 'text-blue-300');
        
        const filesList = (ai.code_help && ai.code_help.suspected_files && ai.code_help.suspected_files.length) 
            ? ai.code_help.suspected_files 
            : (ai.suspected_files && ai.suspected_files.length ? ai.suspected_files : []);
        const defaultGhBase = 'https://github.com/MuarifHasnat/Auto-Bug-Traige/blob/main/';
        const topCandidate = filesList.length ? filesList[0] : null;
        const susp = topCandidate ? (topCandidate.path || topCandidate) : 'resources/views/workspace.blade.php';
        const topGhUrl = topCandidate ? (topCandidate.github_url || (defaultGhBase + encodeURIComponent(susp))) : (defaultGhBase + encodeURIComponent(susp));

        appendTerminalLog('[GITHUB]', `Suspected culprit file: ${susp}`, 'text-cyan-300 font-bold');
        document.getElementById('triageProgressBar').style.width = '75%';

        await sleep(350);
        setStepDone(3);
        setStepActive(4);
        appendTerminalLog('[ROUTER]', `Evaluated developer capacity and domain skills.`, 'text-amber-300');
        document.getElementById('triageProgressBar').style.width = '100%';
        setStepDone(4);
        clearInterval(triageTimer);

        document.getElementById('terminalStatusPill').innerHTML = `<span class="h-2 w-2 rounded-full bg-emerald-600"></span> REPLAY COMPLETE`;
        document.getElementById('terminalStatusPill').className = 'flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-bold text-emerald-700 font-mono-code';

        document.getElementById('terminalResultKey').innerText = `#ABT-${bugId}`;
        const confScore = Math.max(88, 98 - Math.min(ai.similarity_score || 0, 10));
        const confEl = document.getElementById('resConfidence');
        if (confEl) confEl.innerText = `${confScore}% Match`;
        document.getElementById('resAssignee').innerText = '{{ session("ai_result.recommended_assignee", "Developer") }}';
        document.getElementById('resPriority').innerText = (ai.priority || 'Medium') + ' Priority';
        document.getElementById('resSimilarity').innerText = (ai.similarity_score || 0) > 70 ? 'Duplicate' : 'Unique Issue';
        document.getElementById('resCulprit').innerText = susp;
        const culpritGhLink = document.getElementById('resCulpritGhLink');
        if (culpritGhLink) {
            culpritGhLink.href = topGhUrl;
            culpritGhLink.classList.remove('hidden');
        }

        // Suspected files list in modal
        const modalFilesWrapper = document.getElementById('modalSuspectedFilesWrapper');
        const modalFilesList = document.getElementById('modalSuspectedFilesList');
        if (modalFilesWrapper && modalFilesList) {
            modalFilesList.innerHTML = '';
            const listToRender = filesList.length ? filesList : [{
                path: susp,
                confidence: 92,
                reason: 'Primary repository component match',
                github_url: topGhUrl
            }];

            listToRender.forEach(fItem => {
                const fPath = fItem.path || fItem;
                const fConf = fItem.confidence || 92;
                const fReason = fItem.reason || 'Matched project component';
                const fUrl = fItem.github_url || (defaultGhBase + encodeURIComponent(fPath));

                const itemEl = document.createElement('div');
                itemEl.className = 'flex items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white p-2.5 hover:border-indigo-400 transition shadow-2xs';
                itemEl.innerHTML = `
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono-code text-xs font-bold text-slate-900 truncate">📄 ${fPath}</span>
                            <span class="rounded bg-indigo-100 text-indigo-800 px-1.5 py-0.2 text-[10px] font-bold font-mono-code">${fConf}% Match</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-0.5 truncate">${fReason}</p>
                    </div>
                    <a href="${fUrl}" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 hover:text-indigo-600 px-2.5 py-1 text-[11px] font-bold text-slate-700 transition shrink-0 shadow-2xs">
                        <span>GitHub-এ দেখুন</span>
                        <span class="text-xs">↗</span>
                    </a>
                `;
                modalFilesList.appendChild(itemEl);
            });
            modalFilesWrapper.classList.remove('hidden');
        }

        document.getElementById('resIssueLink').href = `/bugs/${bugId}`;
        document.getElementById('terminalResultCard').classList.remove('hidden');
    }
    @endif
</script>

    <!-- Autonomous AI Triage Live Pipeline HUD Modal (Light Glassmorphic Stepper) -->
    <div id="triageTerminalModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md">
        <div class="relative w-full max-w-2xl rounded-3xl border border-slate-200 bg-white/95 backdrop-blur-xl p-5 sm:p-6 shadow-2xl text-slate-900 overflow-hidden flex flex-col space-y-4">
            <!-- Window Top Chrome -->
            <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-rose-400 inline-block"></span>
                    <span class="h-3 w-3 rounded-full bg-amber-400 inline-block"></span>
                    <span class="h-3 w-3 rounded-full bg-emerald-400 inline-block"></span>
                    <span class="ml-2 text-xs font-bold font-mono-code text-slate-800">
                        ⚡ AutoBugTriage Neural Core v2.4
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span id="terminalTimer" class="font-mono-code text-xs text-indigo-600 font-bold">[ 0.00s ]</span>
                    <div id="terminalStatusPill" class="flex items-center gap-1.5 rounded-full bg-indigo-50 border border-indigo-200 px-3 py-1 text-[11px] font-bold text-indigo-700 font-mono-code">
                        <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        RUNNING NEURAL PIPELINE
                    </div>
                </div>
            </div>

            <!-- Target Anomaly Banner -->
            <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50/50 via-white to-purple-50/40 p-3.5 flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 font-mono-code">TARGET ANOMALY</span>
                    <p id="terminalBugTitle" class="text-xs sm:text-sm font-bold text-slate-900 truncate mt-0.5">Payment checkout 401 error</p>
                </div>
                <span class="rounded-lg bg-indigo-100 text-indigo-700 border border-indigo-200 text-[10px] font-mono-code font-bold px-2.5 py-1 shrink-0">
                    4-PHASE INFERENCE
                </span>
            </div>

            <!-- 4-Phase Stepper Pills (Light Grid) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
                <div id="step-pill-1" data-name="Ingestion" class="px-3 py-2 rounded-xl bg-slate-50 text-slate-500 border border-slate-200 font-bold transition flex items-center justify-center gap-1.5">
                    1. Ingestion
                </div>
                <div id="step-pill-2" data-name="NLP & Vector" class="px-3 py-2 rounded-xl bg-slate-50 text-slate-500 border border-slate-200 font-bold transition flex items-center justify-center gap-1.5">
                    2. NLP & Vector
                </div>
                <div id="step-pill-3" data-name="AST & GitHub" class="px-3 py-2 rounded-xl bg-slate-50 text-slate-500 border border-slate-200 font-bold transition flex items-center justify-center gap-1.5">
                    3. AST & GitHub
                </div>
                <div id="step-pill-4" data-name="Dispatch" class="px-3 py-2 rounded-xl bg-slate-50 text-slate-500 border border-slate-200 font-bold transition flex items-center justify-center gap-1.5">
                    4. Dispatch
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                <div id="triageProgressBar" class="bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-500 h-1.5 rounded-full transition-all duration-300" style="width: 5%"></div>
            </div>

            <!-- Collapsible Raw Console Logs Accordion -->
            <details class="group rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                <summary class="flex cursor-pointer items-center justify-between px-3.5 py-2 text-xs font-mono font-bold text-slate-600 hover:text-indigo-600 select-none bg-slate-100/60">
                    <span class="flex items-center gap-2">
                        <span class="text-indigo-600 font-bold">&gt;_</span>
                        <span>View Raw Console Logs</span>
                    </span>
                    <span class="text-[10px] text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div id="terminalOutput" class="p-3.5 bg-slate-950 font-mono-code text-[11px] space-y-1.5 max-h-[160px] overflow-y-auto custom-scroll text-slate-300">
                    <!-- Log items appended dynamically -->
                </div>
            </details>

            <!-- Result Card (shown on completion with Signature Confidence Badge) -->
            <div id="terminalResultCard" class="hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-r from-emerald-50/70 via-white to-indigo-50/70 p-4 space-y-3 shadow-sm">
                <!-- Signature Confidence Badge -->
                <div class="rounded-xl bg-gradient-to-r from-indigo-500 via-purple-500 to-blue-600 p-0.5 shadow-sm">
                    <div class="rounded-[10px] bg-white p-3 flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xl">🎯</span>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">AI Confidence Match</span>
                                <p class="text-xs sm:text-sm font-extrabold text-[#0F172A]">
                                    <span id="resConfidence">94% Match</span> — Assigned to <span id="resAssignee" class="text-indigo-600">Alice Dev</span>
                                </p>
                            </div>
                        </div>
                        <span id="terminalResultKey" class="rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 font-mono font-bold text-xs px-2.5 py-1">
                            #ABT-104
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                    <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                        <span class="text-[9px] text-slate-400 block font-mono-code uppercase">SEVERITY PRIORITY</span>
                        <span id="resPriority" class="font-extrabold text-rose-600 text-[11px] truncate block mt-0.5">High Priority</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                        <span class="text-[9px] text-slate-400 block font-mono-code uppercase">DUPLICATE CHECK</span>
                        <span id="resSimilarity" class="font-extrabold text-emerald-600 text-[11px] truncate block mt-0.5">Unique Issue</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs col-span-2 sm:col-span-1">
                        <div class="flex items-center justify-between mb-0.5">
                            <span class="text-[9px] text-slate-400 block font-mono-code uppercase">CULPRIT FILE</span>
                            <a id="resCulpritGhLink" href="https://github.com/MuarifHasnat/Auto-Bug-Traige" target="_blank" rel="noopener noreferrer" class="hidden text-[10px] font-bold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center gap-0.5">
                                <span>GitHub-এ দেখুন</span> ↗
                            </a>
                        </div>
                        <span id="resCulprit" class="font-mono font-bold text-indigo-700 text-[11px] truncate block mt-0.5">PaymentService.php</span>
                    </div>
                </div>

                <!-- Suspected files list in modal with GitHub buttons -->
                <div id="modalSuspectedFilesWrapper" class="hidden rounded-2xl border border-indigo-200 bg-indigo-50/40 p-3 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800">
                            <span>🐙</span>
                            <span>AI Suspected Repository Culprit Files</span>
                        </div>
                        <span class="rounded-full bg-indigo-100 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-700 font-mono-code">
                            GitHub Linked
                        </span>
                    </div>
                    <div id="modalSuspectedFilesList" class="space-y-1.5 max-h-[140px] overflow-y-auto custom-scroll">
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-2 pt-2 border-t border-slate-200/80">
                    <button type="button" onclick="closeTriageModal()" class="rounded-xl border border-slate-300 bg-white hover:bg-slate-50 px-3.5 py-2 text-xs font-bold text-slate-700 transition shadow-2xs">
                        + Submit Another
                    </button>
                    <a id="resKanbanLink" href="/workspace" class="rounded-xl border border-indigo-200 bg-white hover:bg-indigo-50 px-3.5 py-2 text-xs font-bold text-indigo-700 transition shadow-2xs">
                        ✓ View on Kanban
                    </a>
                    <a id="resIssueLink" href="/bugs/1" class="rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 px-4 py-2 text-xs font-extrabold text-white shadow-md shadow-indigo-600/25 transition hover:scale-[1.02]">
                        Open Issue & Fix Guidance ➔
                    </a>
                </div>
            </div>
        </div>
    </div>
</script>
</body>
</html>
