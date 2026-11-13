<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team & AI Skill Matrix · AutoBugTriage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] p-4 sm:p-6 lg:p-8 selection:bg-indigo-500 selection:text-white bg-dot-grid">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Top Navigation Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-[#E2E8F0] shadow-xs">
            <div class="flex items-center gap-3">
                <a href="/dashboard" class="rounded-xl border border-[#E2E8F0] px-3.5 py-1.5 text-xs font-semibold text-[#64748B] hover:bg-[#F1F5F9] transition">
                    ← Dashboard
                </a>
                <div class="flex items-center gap-2">
                    <span class="rounded-lg bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-bold text-indigo-700">
                        📁 Project: {{ session('project_name', 'Software Project') }}
                    </span>
                    <span class="rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-[11px] font-mono-code font-bold text-slate-600">
                        Step 2 / 3: Skill Matrix
                    </span>
                    @include('components.project-switcher', ['variant' => 'light'])
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="/workspace" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:from-indigo-500 hover:to-blue-500 transition">
                    <span>Launch Kanban Workspace</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="/logout" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                    Log out
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-medium text-emerald-800 flex items-center gap-2.5 shadow-xs">
                <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-medium text-rose-800 flex items-center gap-2.5 shadow-xs">
                <span class="text-rose-600 font-bold">⚠</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 items-start">
            
            <!-- Right Column (65% / col-span-8): AI Assignment Training Matrix & Workload Cards -->
            <div class="lg:col-span-12 space-y-6">
                <section class="rounded-3xl border border-indigo-100 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-bold text-[#0F172A]">Add an existing Developer or Tester</h2>
                            <p class="mt-1 text-xs text-[#64748B]">Choose a registered account to add it to {{ session('project_name', 'this project') }}.</p>
                        </div>
                        <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-bold text-indigo-700">{{ $registeredAccounts->count() }} registered accounts</span>
                    </div>
                    <form id="existingAccountForm" action="/team-members" method="POST" class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
                        @csrf
                        <div class="relative">
                            <label class="sr-only" for="teamAccountSearch">Search by name or Gmail</label>
                            <input id="teamAccountSearch" type="search" autocomplete="off" placeholder="Search team accounts by name or email…" class="w-full min-w-0 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC] px-3.5 py-2.5 text-xs text-[#0F172A] outline-none focus:border-indigo-600">
                            <input id="selectedTeamEmail" type="hidden" name="email" required>
                            <div id="teamAccountResults" class="absolute z-20 mt-1 hidden max-h-64 w-full overflow-y-auto rounded-xl border border-[#E2E8F0] bg-white p-1.5 shadow-xl" role="listbox" aria-label="Registered team accounts">
                            @foreach($registeredAccounts as $account)
                                <button type="button" role="option" data-name="{{ strtolower($account->name) }}" data-email="{{ strtolower($account->email) }}" data-account-email="{{ $account->email }}" data-assigned="{{ in_array($account->id, $assignedAccountIds, true) ? 'true' : 'false' }}" class="team-account-option flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-left hover:bg-indigo-50 disabled:cursor-not-allowed disabled:opacity-50" @disabled(in_array($account->id, $assignedAccountIds, true))>
                                    <span class="min-w-0"><span class="block truncate text-xs font-semibold text-[#0F172A]">{{ $account->name }}</span><span class="block truncate text-[11px] text-slate-500">{{ $account->email }}</span></span>
                                    <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-bold {{ $account->role === 'developer' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">{{ $account->role === 'tester' ? 'QA Tester' : 'Developer' }}{{ in_array($account->id, $assignedAccountIds, true) ? ' · Added' : '' }}</span>
                                </button>
                            @endforeach
                                <p id="teamAccountNoResults" class="hidden px-3 py-3 text-xs text-slate-500">No account matches that name or email.</p>
                            </div>
                        </div>
                        <div>
                            <input id="selectedTeamLabel" type="text" readonly placeholder="No account selected" class="w-full min-w-0 rounded-xl border border-[#E2E8F0] bg-slate-50 px-3.5 py-2.5 text-xs text-slate-600">
                            <input type="hidden" name="skills" value="">
                        </div>
                        <button id="addExistingAccountButton" type="submit" disabled class="rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">Add to project</button>
                    </form>
                    @if($registeredAccounts->isEmpty())
                        <p class="mt-3 text-xs text-slate-500">No developer or tester accounts have been created yet.</p>
                    @endif
                </section>

                <!-- Developer Skill & Capacity Grid -->
                <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E2E8F0] pb-4 mb-5">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-[#0F172A]">AI Developer Skill & Capacity Grid</h3>
                                <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-xs font-bold text-indigo-700 font-mono-code">
                                    {{ $developers->count() }} Profiles
                                </span>
                            </div>
                            <p class="text-xs text-[#64748B] mt-0.5">Real-time skills matrix and capacity monitored by the Python Triage Agent.</p>
                        </div>
                    </div>

                    <!-- 2-Column Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($developers as $dev)
                            @php
                                $taskCount = (int) $dev->active_tasks;
                                $capacity = (int) ($dev->capacity ?? 5);
                                if ($taskCount < $capacity && $taskCount <= 2) {
                                    $barColor = 'bg-emerald-500';
                                    $textColor = 'text-emerald-700';
                                    $bgBadge = 'bg-emerald-50 border-emerald-200';
                                    $statusText = 'Available for Auto-Assign';
                                } elseif ($taskCount < $capacity) {
                                    $barColor = 'bg-amber-500';
                                    $textColor = 'text-amber-700';
                                    $bgBadge = 'bg-amber-50 border-amber-200';
                                    $statusText = 'Moderate Load';
                                } else {
                                    $barColor = 'bg-rose-500';
                                    $textColor = 'text-rose-700';
                                    $bgBadge = 'bg-rose-50 border-rose-200';
                                    $statusText = 'At capacity — auto-assign paused';
                                }
                                $skillsArray = array_filter(array_map('trim', preg_split('/[\s,]+/', $dev->skills)));
                            @endphp
                            <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4.5 shadow-xs card-hover-lift flex flex-col justify-between relative">
                                <div>
                                    <!-- Header: Avatar + Name + Edit/Delete with Safe Confirm Popover -->
                                    <div class="flex items-start justify-between gap-2 border-b border-[#E2E8F0] pb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-bold text-sm shadow-xs uppercase">
                                                {{ strtoupper(substr($dev->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-sm text-[#0F172A] leading-snug">{{ $dev->name }}</h4>
                                                <span class="rounded px-1.5 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 font-mono-code">
                                                    Developer
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Safe Delete Trigger -->
                                        <button type="button" onclick="toggleDeleteConfirm({{ $dev->id }})" title="Remove Developer" class="text-slate-400 hover:text-rose-600 transition p-1 text-xs">
                                            ✕
                                        </button>
                                    </div>

                                    <!-- Safe Confirm Popover (Zero Ugly Native Alert) -->
                                    <div id="delete-confirm-{{ $dev->id }}" class="hidden my-2 rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800">
                                        <p class="font-bold">ডেভেলপারকে রিমুভ করবেন?</p>
                                        <p class="text-[11px] text-rose-700 mt-0.5">রিমুভ করলে তার কাজগুলো আন-অ্যাসাইন হয়ে যাবে।</p>
                                        <div class="mt-2.5 flex items-center justify-end gap-2">
                                            <button type="button" onclick="toggleDeleteConfirm({{ $dev->id }})" class="rounded-lg border border-[#CBD5E1] bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-700">
                                                বাতিল
                                            </button>
                                            <form action="/developers/{{ $dev->id }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg bg-rose-600 text-white px-2.5 py-1 text-[11px] font-bold hover:bg-rose-700">
                                                    হ্যাঁ, রিমুভ করুন
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Skill Matrix Section: Monospace Badges -->
                                    <div class="mt-3.5">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-[#64748B] mb-1.5">Skill Matrix Keywords:</p>
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse($skillsArray as $skill)
                                                <span class="rounded-md bg-white border border-[#CBD5E1] px-2 py-0.5 text-[11px] font-mono-code font-semibold text-[#334155]">
                                                    {{ $skill }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-slate-400 italic">No skills registered</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <!-- AI Workload Bar (0-2 Green, 3-4 Yellow, 5+ Red) -->
                                <div class="mt-5 pt-3 border-t border-[#E2E8F0]">
                                    <div class="flex items-center justify-between text-xs mb-1.5">
                                        <span class="font-bold text-[#0F172A]">{{ $taskCount }} / {{ $capacity }} Active Bugs</span>
                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold border {{ $bgBadge }} {{ $textColor }}">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                    <div class="h-2 w-full rounded-full bg-[#E2E8F0] overflow-hidden">
                                        <div class="h-2 rounded-full {{ $barColor }} transition-all duration-500" style="width: {{ min(100, max(8, $taskCount * 20)) }}%"></div>
                                    </div>

                                    <!-- Inline Edit Form -->
                                    <form action="/developers/{{ $dev->id }}" method="POST" class="mt-3 flex items-center gap-1.5">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $dev->name }}">
                                        <input type="text" name="skills" value="{{ $dev->skills }}" required placeholder="Edit skills..." 
                                               class="min-w-0 flex-1 rounded-lg border border-[#E2E8F0] bg-white px-2.5 py-1 text-[11px] font-mono-code text-[#0F172A] outline-none focus:border-indigo-600">
                                        <input type="number" name="capacity" value="{{ $capacity }}" min="1" max="100" required aria-label="Maximum active issues" class="w-16 rounded-lg border border-[#E2E8F0] bg-white px-2 py-1 text-[11px]">
                                        <button type="submit" class="rounded-lg bg-indigo-50 border border-indigo-200 px-2.5 py-1 text-[11px] font-bold text-indigo-700 hover:bg-indigo-100 transition">
                                            Save
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-10 border border-dashed border-[#CBD5E1] rounded-2xl text-xs text-[#64748B]">
                                No developer profiles created yet. Add developers using the form on the left.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Team Accounts List (Testers & Developers) -->
                <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-[#0F172A] mb-3 flex items-center gap-2">
                        <span>👥 Registered Team Accounts</span>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-mono-code font-bold text-slate-600">{{ $teamMembers->count() }}</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($teamMembers as $member)
                            <div class="rounded-xl border border-[#E2E8F0] p-3 bg-[#F8FAFC] flex items-center justify-between">
                                <div class="min-w-0">
                                    <p class="font-bold text-xs text-[#0F172A] truncate">{{ $member->name }}</p>
                                    <p class="text-[11px] text-[#64748B] truncate">{{ $member->email }}</p>
                                </div>
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $member->role === 'developer' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                    {{ ucfirst($member->role) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const teamAccountSearch = document.getElementById('teamAccountSearch');
        const teamAccountResults = document.getElementById('teamAccountResults');
        const selectedTeamEmail = document.getElementById('selectedTeamEmail');
        const selectedTeamLabel = document.getElementById('selectedTeamLabel');
        const existingAccountForm = document.getElementById('existingAccountForm');
        const addExistingAccountButton = document.getElementById('addExistingAccountButton');

        if (teamAccountSearch) {
            teamAccountSearch.addEventListener('input', () => {
                const query = teamAccountSearch.value.trim().toLowerCase();
                let visibleCount = 0;
                selectedTeamEmail.value = '';
                selectedTeamLabel.value = '';
                addExistingAccountButton.disabled = true;
                teamAccountResults.querySelectorAll('.team-account-option').forEach((option) => {
                    const matches = !query || option.dataset.name.includes(query) || option.dataset.email.includes(query);
                    option.style.display = matches ? 'flex' : 'none';
                    if (matches) visibleCount++;
                });
                document.getElementById('teamAccountNoResults').classList.toggle('hidden', visibleCount !== 0);
                teamAccountResults.classList.toggle('hidden', query === '');
            });

            teamAccountSearch.addEventListener('focus', () => {
                if (teamAccountSearch.value.trim()) teamAccountSearch.dispatchEvent(new Event('input'));
            });

            teamAccountResults.querySelectorAll('.team-account-option:not(:disabled)').forEach((option) => {
                option.addEventListener('click', () => {
                    selectedTeamEmail.value = option.dataset.accountEmail;
                    selectedTeamLabel.value = option.querySelector('span span').textContent + ' · ' + option.querySelector('span span + span').textContent;
                    teamAccountSearch.value = option.querySelector('span span').textContent;
                    teamAccountResults.classList.add('hidden');
                    addExistingAccountButton.disabled = false;
                });
            });

            document.addEventListener('click', (event) => {
                if (!teamAccountResults.contains(event.target) && event.target !== teamAccountSearch) teamAccountResults.classList.add('hidden');
            });

            existingAccountForm.addEventListener('submit', (event) => {
                if (!selectedTeamEmail.value) {
                    event.preventDefault();
                    teamAccountSearch.focus();
                    teamAccountResults.classList.remove('hidden');
                }
            });
        }

        function handleRoleChange(role) {
            const group = document.getElementById('skillsFieldGroup');
            const capacityGroup = document.getElementById('capacityFieldGroup');
            const capacityInput = document.getElementById('memberCapacity');
            if (role === 'developer') {
                group.style.display = 'block';
                capacityGroup.style.display = 'block';
                capacityInput.disabled = false;
            } else {
                group.style.display = 'none';
                capacityGroup.style.display = 'none';
                capacityInput.disabled = true;
            }
        }

        function appendSkill(skill) {
            const input = document.getElementById('skillsInput');
            let current = input.value.split(',').map(s => s.trim()).filter(Boolean);
            if (!current.includes(skill)) {
                current.push(skill);
                input.value = current.join(', ');
            }
            input.focus();
        }

        function generatePassword() {
            const chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$';
            let pass = '';
            for (let i = 0; i < 10; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('memberPass').value = pass;
            document.getElementById('memberConfirmPass').value = pass;
            document.getElementById('memberPass').type = 'text';
            document.getElementById('memberConfirmPass').type = 'text';

            const notice = document.getElementById('generatedNotice');
            notice.classList.remove('hidden');
            notice.innerText = '⚡ Password Generated: ' + pass + ' (Copy and save)';
        }

        function toggleDeleteConfirm(devId) {
            const box = document.getElementById('delete-confirm-' + devId);
            box.classList.toggle('hidden');
        }
    </script>
</body>
</html>
