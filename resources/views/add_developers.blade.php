<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team & AI Skill Matrix · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-[#0F172A] selection:bg-indigo-500 selection:text-white">
    <div class="flex min-h-screen">
        @include('components.app-sidebar', ['active' => 'team'])
        <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">
            <div class="mx-auto max-w-7xl space-y-6">
        <!-- Top Navigation Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-[#E2E8F0] shadow-xs">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700">
                        Project: {{ session('project_name', 'Software Project') }}
                    </span>
                    <span class="rounded-full bg-indigo-50 px-3 py-1.5 text-[11px] font-mono-code font-semibold text-indigo-700">
                        Step 2 / 3: Skill Matrix
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <form action="/logout" method="POST" class="m-0">
                    @csrf
                    <button type="submit" aria-label="Log out" title="Log out" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700"><svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M12 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5"/></svg></button>
                </form>
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

        @if(isset($errors) && $errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs font-medium text-rose-800 flex items-center gap-2.5 shadow-xs">
                <span class="text-rose-600 font-bold">⚠</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 items-start">
            
            <!-- Right Column (65% / col-span-8): AI Assignment Training Matrix & Workload Cards -->
            <div class="lg:col-span-12 space-y-6">
                <!-- Developer Skill & Capacity Grid -->
                <div class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E2E8F0] pb-4 mb-5">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-[#0F172A]">Team Skill & Capacity Grid</h3>
                                <span class="rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-xs font-bold text-indigo-700 font-mono-code">
                                    {{ $developers->count() + $teamMembers->where('role', 'tester')->count() }} Profiles
                                </span>
                            </div>
                            <p class="text-xs text-[#64748B] mt-0.5">Developer skills and capacity, alongside QA tester profiles assigned to this project.</p>
                        </div>
                    </div>

                    <!-- 2-Column Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($developers as $dev)
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
                                        <p class="font-bold">Remove this developer?</p>
                                        <p class="text-[11px] text-rose-700 mt-0.5">Their assigned issues will become unassigned.</p>
                                        <div class="mt-2.5 flex items-center justify-end gap-2">
                                            <button type="button" onclick="toggleDeleteConfirm({{ $dev->id }})" class="rounded-lg border border-[#CBD5E1] bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-700">
                                                Cancel
                                            </button>
                                            <form action="/developers/{{ $dev->id }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg bg-rose-600 text-white px-2.5 py-1 text-[11px] font-bold hover:bg-rose-700">
                                                    Yes, remove developer
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
                        @endforeach
                        @foreach($teamMembers->where('role', 'tester') as $tester)
                            <article class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4.5 shadow-xs card-hover-lift flex flex-col justify-between relative">
                                <div class="flex items-start justify-between gap-2 border-b border-[#E2E8F0] pb-3">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-tr from-purple-600 to-indigo-600 text-sm font-bold uppercase text-white shadow-xs">
                                            @if($tester->profile_photo_path)<img src="{{ route('team-members.photo', $tester->id) }}" alt="{{ $tester->name }}" class="h-full w-full object-cover">@else{{ strtoupper(substr($tester->name, 0, 2)) }}@endif
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="truncate text-sm font-bold leading-snug text-[#0F172A]">{{ $tester->name }}</h4>
                                            <span class="rounded border border-purple-200 bg-purple-50 px-1.5 py-0.5 text-[10px] font-bold text-purple-700">Tester</span>
                                            <p class="mt-1 truncate text-[11px] text-slate-500">{{ $tester->email }}</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('team-members.remove', $tester->id) }}" method="POST" onsubmit="return confirm('Remove this tester from the project?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Remove tester from project" aria-label="Remove {{ $tester->name }} from project" class="rounded-lg p-1 text-xs text-slate-400 transition hover:bg-rose-50 hover:text-rose-600">✕</button>
                                    </form>
                                </div>
                                <div class="mt-3.5">
                                    <p class="mb-1.5 text-[10px] font-bold uppercase tracking-wider text-[#64748B]">QA profile</p>
                                    <p class="text-xs text-slate-600">Can report bugs, review issues, and verify fixes for this project.</p>
                                </div>
                            </article>
                        @endforeach
                        @if($developers->isEmpty() && $teamMembers->where('role', 'tester')->isEmpty())
                            <div class="col-span-2 rounded-2xl border border-dashed border-[#CBD5E1] py-10 text-center text-xs text-[#64748B]">
                                No developer or tester profiles are assigned to this project yet.
                            </div>
                        @endif
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
                            <div class="rounded-xl border border-[#E2E8F0] p-3 bg-[#F8FAFC] flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-bold text-xs text-[#0F172A] truncate">{{ $member->name }}</p>
                                    <p class="text-[11px] text-[#64748B] truncate">{{ $member->email }}</p>
                                </div>
                                <div class="flex shrink-0 items-center gap-2">
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $member->role === 'developer' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                        {{ ucfirst($member->role) }}
                                    </span>
                                    @if($member->role === 'tester' && auth()->user()?->role === 'manager')
                                        <form action="{{ route('team-members.remove', $member->id) }}" method="POST" onsubmit="return confirm('Remove this tester from the current project?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-rose-200 px-2 py-1 text-[10px] font-semibold text-rose-700 transition hover:bg-rose-50" aria-label="Remove {{ $member->name }} from project">Remove</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <section class="rounded-3xl border border-[#E2E8F0] bg-white p-6 shadow-sm sm:p-8" aria-labelledby="available-members-title">
                    <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-5">
                        <div>
                            <h2 id="available-members-title" class="flex items-center gap-2 text-xl font-extrabold text-slate-950"><span class="h-2.5 w-2.5 rounded-full bg-indigo-600"></span>Available Workspace Developers (Click to Assign)</h2>
                            <p class="mt-1 text-sm text-slate-500">Choose an account to add it to <strong>{{ session('project_name', 'this project') }}</strong>.</p>
                        </div>
                        <div class="flex flex-wrap gap-2" role="group" aria-label="Filter accounts by role">
                            <button type="button" data-account-filter="all" aria-pressed="true" class="account-filter rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white">All Members</button>
                            <button type="button" data-account-filter="developer" aria-pressed="false" class="account-filter rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Developers</button>
                            <button type="button" data-account-filter="tester" aria-pressed="false" class="account-filter rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">QA Testers</button>
                        </div>
                    </div>

                    <label class="relative mt-5 block max-w-xl">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400" aria-hidden="true"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg></span>
                        <span class="sr-only">Search developers and testers</span>
                        <input id="workspaceAccountSearch" type="search" autocomplete="off" placeholder="Search accounts by name or email…" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm outline-none focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-50">
                    </label>

                    <div id="workspaceAccountGrid" class="mt-5 grid grid-cols-1 gap-3 lg:grid-cols-2 2xl:grid-cols-3">
                        @forelse($registeredAccounts as $account)
                            @php($isProjectMember = in_array($account->id, $assignedAccountIds, true))
                            <article data-workspace-account data-role="{{ $account->role }}" data-search="{{ strtolower($account->name.' '.$account->email) }}" class="flex min-w-0 items-center justify-between gap-4 rounded-2xl border {{ $isProjectMember ? 'border-emerald-200 bg-emerald-50/40' : 'border-slate-200 bg-white' }} p-4 transition hover:border-indigo-200 hover:shadow-sm sm:p-5">
                                <div class="flex min-w-0 items-center gap-3">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $account->role === 'developer' ? 'bg-gradient-to-br from-sky-500 to-blue-600' : 'bg-gradient-to-br from-emerald-500 to-teal-600' }} text-sm font-extrabold text-white">{{ strtoupper(substr($account->name, 0, 2)) }}</span>
                                    <div class="min-w-0"><h3 class="truncate text-base font-bold text-slate-900">{{ $account->name }}</h3><p class="truncate font-mono text-sm text-slate-400">{{ $account->email }}</p><span class="mt-2 inline-flex rounded-md border px-2 py-0.5 text-xs font-semibold {{ $account->role === 'developer' ? 'border-indigo-200 bg-indigo-50 text-indigo-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}">{{ $account->role === 'tester' ? 'QA Tester' : 'Developer' }}</span></div>
                                </div>
                                @if($isProjectMember)
                                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-sm font-bold text-emerald-700"><span aria-hidden="true">✓</span> Assigned</span>
                                @else
                                    <form action="/team-members" method="POST" class="shrink-0">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $account->id }}">
                                        <input type="hidden" name="skills" value="">
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-3 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700 sm:px-4"><span class="text-lg leading-none" aria-hidden="true">＋</span><span>Add to Project</span></button>
                                    </form>
                                @endif
                            </article>
                        @empty
                            <p class="col-span-full rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-sm text-slate-500">No developer or tester accounts have registered yet.</p>
                        @endforelse
                    </div>
                    <p id="workspaceAccountEmpty" class="mt-5 hidden rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">No matching accounts. Try another name or email.</p>
                </section>
            </div>
        </div>
            </div>
        </main>
    </div>

    <script>
        const workspaceAccountSearch = document.getElementById('workspaceAccountSearch');
        const workspaceAccountCards = [...document.querySelectorAll('[data-workspace-account]')];
        const workspaceAccountEmpty = document.getElementById('workspaceAccountEmpty');
        let activeAccountRole = 'all';

        function filterWorkspaceAccounts() {
            const query = workspaceAccountSearch?.value.trim().toLowerCase() || '';
            let visible = 0;
            workspaceAccountCards.forEach((card) => {
                const matchesRole = activeAccountRole === 'all' || card.dataset.role === activeAccountRole;
                const matchesSearch = !query || card.dataset.search.includes(query);
                const show = matchesRole && matchesSearch;
                card.classList.toggle('hidden', !show);
                if (show) visible++;
            });
            workspaceAccountEmpty?.classList.toggle('hidden', visible !== 0 || workspaceAccountCards.length === 0);
        }

        workspaceAccountSearch?.addEventListener('input', filterWorkspaceAccounts);
        document.querySelectorAll('.account-filter').forEach((button) => {
            button.addEventListener('click', () => {
                activeAccountRole = button.dataset.accountFilter;
                document.querySelectorAll('.account-filter').forEach((filterButton) => {
                    const selected = filterButton === button;
                    filterButton.setAttribute('aria-pressed', String(selected));
                    filterButton.classList.toggle('bg-slate-900', selected);
                    filterButton.classList.toggle('text-white', selected);
                    filterButton.classList.toggle('border', !selected);
                    filterButton.classList.toggle('border-slate-200', !selected);
                    filterButton.classList.toggle('bg-white', !selected);
                    filterButton.classList.toggle('text-slate-600', !selected);
                });
                filterWorkspaceAccounts();
            });
        });

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
