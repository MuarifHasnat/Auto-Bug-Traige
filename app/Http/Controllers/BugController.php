<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use App\Notifications\BugUpdateNotification;
use Illuminate\View\View;

class BugController extends Controller
{
    public function signupPage(): View
    {
        return view('signup');
    }

    public function handleSignup(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'manager_name' => ['required', 'string', 'max:100'],
            'manager_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'name' => $data['manager_name'],
            'email' => $data['manager_email'],
            'role' => 'manager',
            'password' => $data['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('manager_name', $user->name);

        return redirect($user->role === 'manager' ? '/create-project' : ($user->role === 'developer' ? '/workspace' : '/ai-agent'));
    }

    public function loginPage(): View
    {
        return view('login');
    }

    public function handleLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'The email or password is incorrect.'])->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('manager_name', Auth::user()->name);

        return redirect(match (Auth::user()->role) {
            'manager' => '/dashboard',
            'developer' => '/workspace',
            default => '/ai-agent',
        });
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function createProjectPage(): View
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        return view('create_project');
    }

    public function handleCreateProject(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $data = $request->validate([
            'project_name' => ['required', 'string', 'max:150'],
            'project_stack' => ['required', 'string', 'max:255'],
        ]);

        $project = DB::table('projects')->insertGetId([
            'manager_id' => Auth::id(),
            'name' => $data['project_name'],
            'stack' => $data['project_stack'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $request->session()->put([
            'project_name' => $data['project_name'],
            'project_stack' => $data['project_stack'],
            'project_id' => $project,
        ]);

        return redirect('/add-developers');
    }

    public function addDevelopersPage(): View
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $projectId = $this->currentProjectId();
        $projects = $this->accessibleProjects();
        $developers = DB::table('developers')->where('project_id', $projectId)->orderBy('name')->get();
        $teamMembers = \App\Models\User::query()
            ->whereIn('role', ['tester', 'developer'])
            ->whereIn('id', DB::table('project_members')->where('project_id', $projectId)->pluck('user_id'))
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        return view('add_developers', [
            'developers' => $developers,
            'teamMembers' => $teamMembers,
            'projects' => $projects,
            'currentProjectId' => $projectId,
        ]);
    }

    public function switchProject(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_id' => ['required', 'integer'],
        ]);

        $project = $this->accessibleProjects()->firstWhere('id', (int) $data['project_id']);
        abort_unless($project, 403, 'You are not assigned to that project.');

        $this->setCurrentProject($request, $project);

        return back()->with('success', 'Switched to '.$project->name.'.');
    }

    public function storeDeveloper(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'skills' => ['required', 'string', 'max:255'],
        ]);

        DB::table('developers')->insert([
            'project_id' => $this->currentProjectId(),
            'name' => $data['name'],
            'skills' => strtolower($data['skills']),
            'active_tasks' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Developer added successfully.');
    }

    public function addTeamMember(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:tester,developer'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'skills' => ['nullable', 'string', 'max:255'],
        ]);

        $member = DB::transaction(function () use ($data, $projectId) {
            $member = \App\Models\User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => $data['password'],
            ]);
            DB::table('project_members')->insert([
                'project_id' => $projectId,
                'user_id' => $member->id,
            ]);

            if ($member->role === 'developer') {
                DB::table('developers')->insert([
                    'project_id' => $projectId,
                    'name' => $member->name,
                    'skills' => strtolower($data['skills'] ?? ''),
                    'active_tasks' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $member;
        });

        return back()->with('success', ucfirst($member->role).' account created successfully.');
    }

    public function updateDeveloper(Request $request, int $id): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'skills' => ['required', 'string', 'max:255'],
        ]);

        DB::table('developers')->where('project_id', $this->currentProjectId())->where('id', $id)->update([
            'name' => $data['name'],
            'skills' => strtolower($data['skills']),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Developer updated successfully.');
    }

    public function deleteDeveloper(int $id): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $developer = DB::table('developers')->where('project_id', $this->currentProjectId())->where('id', $id)->first();

        if (!$developer) {
            return back()->withErrors(['developer' => 'Developer not found.']);
        }

        if ($developer->active_tasks > 0) {
            return back()->withErrors(['developer' => 'A developer with active tasks cannot be removed.']);
        }

        DB::table('developers')->where('project_id', $this->currentProjectId())->where('id', $id)->delete();

        return back()->with('success', 'Developer removed successfully.');
    }

    public function workspacePage(Request $request): View
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'developer', 'tester'], true), 403);

        $projectId = $this->currentProjectId();
        $developers = DB::table('developers')->where('project_id', $projectId)->orderByDesc('active_tasks')->orderBy('name')->get();
        $query = DB::table('bugs')->where('project_id', $projectId)->orderByDesc('id');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->string('priority')->toString());
        }

        $bugs = $query->get();
        $stats = [
            'total' => DB::table('bugs')->where('project_id', $projectId)->count(),
            'open' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', ['Open', 'In Progress'])->count(),
            'resolved' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', ['Resolved', 'Closed'])->count(),
            'high' => DB::table('bugs')->where('project_id', $projectId)->where('priority', 'High')->count(),
        ];

        $assignedBugsQuery = DB::table('bugs')
            ->where('project_id', $projectId)
            ->whereNotNull('triage_result');

        if (Auth::user()?->role === 'developer') {
            $assignedBugsQuery->where('assigned_to', Auth::user()->name);
        }

        $assignedBugs = $assignedBugsQuery
            ->orderByDesc('id')
            ->get()
            ->map(function ($bug) {
                $bug->triage = json_decode($bug->triage_result, true) ?: [];
                return $bug;
            });

        $bugActivity = DB::table('bug_activities')
            ->where('project_id', $projectId)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get(['id', 'bug_id', 'event_type', 'from_value', 'to_value', 'actor_name', 'created_at'])
            ->groupBy('bug_id');

        $bugComments = DB::table('bug_comments')
            ->where('project_id', $projectId)
            ->orderBy('created_at')
            ->get(['id', 'bug_id', 'user_id', 'author_name', 'body', 'created_at'])
            ->groupBy('bug_id');
        $watchingBugIds = DB::table('bug_watchers')->where('user_id', Auth::id())->pluck('bug_id')->all();
        $projectMembers = $this->projectParticipants($projectId);

        $projects = $this->accessibleProjects();

        return view('workspace', compact('developers', 'bugs', 'stats', 'assignedBugs', 'projects', 'bugActivity', 'bugComments', 'watchingBugIds', 'projectMembers') + [
            'currentProjectId' => $projectId,
        ]);
    }

    public function dashboardPage(Request $request): View
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $projectId = $this->currentProjectId();
        $stats = [
            'total' => DB::table('bugs')->where('project_id', $projectId)->count(),
            'open' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', ['Open', 'In Progress'])->count(),
            'resolved' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', ['Resolved', 'Closed'])->count(),
            'high' => DB::table('bugs')->where('project_id', $projectId)->where('priority', 'High')->count(),
        ];
        $recentBugs = DB::table('bugs')->where('project_id', $projectId)->orderByDesc('id')->limit(8)->get();
        $developers = DB::table('developers')->where('project_id', $projectId)->orderByDesc('active_tasks')->limit(6)->get();

        $projects = $this->accessibleProjects();
        $filters = $request->validate([
            'account_search' => ['nullable', 'string', 'max:100'],
            'account_role' => ['nullable', 'in:all,manager,developer,tester'],
        ]);
        $accountSearch = trim($filters['account_search'] ?? '');
        $accountRole = $filters['account_role'] ?? 'all';
        $accountsQuery = DB::table('users')->select('id', 'name', 'role')->orderBy('name');

        if ($accountSearch !== '') {
            $accountsQuery->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($accountSearch).'%']);
        }
        if ($accountRole !== 'all') {
            $accountsQuery->where('role', $accountRole);
        }

        $accounts = $accountsQuery->paginate(20)->withQueryString();
        $totalAccounts = DB::table('users')->count();

        return view('dashboard', compact('stats', 'recentBugs', 'developers', 'projects', 'accounts', 'accountSearch', 'accountRole', 'totalAccounts') + [
            'currentProjectId' => $projectId,
        ]);
    }

    public function aiAgentPage(Request $request): View
    {
        abort_unless(in_array(Auth::user()?->role, ['tester', 'developer'], true), 403);

        $projectId = $this->currentProjectId();
        $bugs = DB::table('bugs')->where('project_id', $projectId)->orderByDesc('id')->limit(10)->get();
        $assignedBugs = collect();
        if (Auth::user()->role === 'developer') {
            $assignedBugs = DB::table('bugs')
                ->where('project_id', $projectId)
                ->where('assigned_to', Auth::user()->name)
                ->whereNotNull('triage_result')
                ->orderByDesc('id')
                ->get()
                ->map(function ($bug) {
                    $bug->triage = json_decode($bug->triage_result, true) ?: [];
                    return $bug;
                });
        }
        $mode = Auth::user()->role === 'developer' ? 'developer' : 'tester';

        $projects = $this->accessibleProjects();

        return view('ai_agent', compact('bugs', 'assignedBugs', 'mode', 'projects') + [
            'currentProjectId' => $projectId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'tester', 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:5000'],
            'language' => ['nullable', 'string', 'in:auto,PHP,C++,C,Python,JavaScript,TypeScript,Java,SQL'],
            'code_snippet' => ['nullable', 'string', 'max:20000'],
            'screenshot' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'source_file' => ['nullable', 'file', 'mimes:txt,php,blade.php,cpp,c,h,hpp,py,js,ts,java,sql,json,xml,css,html', 'max:5120'],
        ]);

        if (!$request->filled('code_snippet') && !$request->hasFile('source_file') && !$request->hasFile('screenshot')) {
            return back()->withErrors(['bug' => 'Add a code snippet, source file, or screenshot so the Agent can inspect the report.']);
        }

        $sourceFilePath = $request->file('source_file')?->store('bug-reports/source', 'local');
        $screenshotPath = $request->file('screenshot')?->store('bug-reports/screenshots', 'local');
        $uploadedCode = $request->file('source_file')
            ? file_get_contents(Storage::disk('local')->path($sourceFilePath))
            : '';
        $analysisCode = trim($data['code_snippet'] ?? '') ?: $uploadedCode;
        $attachment = $request->file('screenshot')
            ? ['type' => 'screenshot', 'name' => $request->file('screenshot')->getClientOriginalName()]
            : ($request->file('source_file')
                ? ['type' => 'source_file', 'name' => $request->file('source_file')->getClientOriginalName()]
                : null);

        $projectId = $this->currentProjectId();
        $pastBugs = DB::table('bugs')
            ->where('project_id', $projectId)
            ->whereRaw('is_duplicate = false')
            ->select('id', 'title', 'description')
            ->get()
            ->map(fn ($bug) => (array) $bug)
            ->all();
        $developers = DB::table('developers')
            ->where('project_id', $projectId)
            ->select('id', 'name', 'skills', 'active_tasks')
            ->get()
            ->map(fn ($developer) => (array) $developer)
            ->all();

        $payloadPath = tempnam(storage_path('app'), 'triage_payload_');
        if ($payloadPath === false) {
            return back()->withErrors(['bug' => 'Could not prepare the report for triage.']);
        }

        try {
            file_put_contents($payloadPath, json_encode([
                ...$data,
                'code_snippet' => $analysisCode,
                'selected_language' => $data['language'] ?? 'auto',
                'attachment' => $attachment,
                'past_bugs' => $pastBugs,
                'developers' => $developers,
            ], JSON_THROW_ON_ERROR));

            $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
            $result = Process::run([$python, base_path('agent/triage.py'), $payloadPath]);
        } finally {
            @unlink($payloadPath);
        }

        if (!$result->successful()) {
            return back()->withErrors(['bug' => 'AI triage failed: '.$result->errorOutput()]);
        }

        $aiResult = json_decode($result->output(), true);
        if (!is_array($aiResult)) {
            return back()->withErrors(['bug' => 'AI triage returned an invalid response.']);
        }

        $isDuplicate = (bool) ($aiResult['is_duplicate'] ?? false);
        $assignedTo = $aiResult['assigned_to'] ?? 'Unassigned';
        $priority = $aiResult['priority'] ?? 'Medium';
        $status = $aiResult['status'] ?? ($isDuplicate ? 'Duplicate' : 'Open');
        $bugId = DB::transaction(function () use (
            $projectId,
            $data,
            $aiResult,
            $isDuplicate,
            $assignedTo,
            $priority,
            $status,
            $screenshotPath,
            $sourceFilePath,
            $request
        ) {
            $bugId = DB::table('bugs')->insertGetId([
                'project_id' => $projectId,
                'reporter_id' => Auth::id(),
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $priority,
                'status' => $status,
                'assigned_to' => $assignedTo,
                'is_duplicate' => DB::raw($isDuplicate ? 'true' : 'false'),
                'duplicate_of' => $aiResult['duplicate_of'] ?? null,
                'screenshot_path' => $screenshotPath,
                'source_file_path' => $sourceFilePath,
                'source_file_name' => $request->file('source_file')?->getClientOriginalName(),
                'detected_feature' => $aiResult['feature'] ?? null,
                'triage_result' => json_encode($aiResult, JSON_THROW_ON_ERROR),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->recordBugActivity($projectId, $bugId, 'created', null, "Status: {$status}; Priority: {$priority}");
            if ($assignedTo !== 'Unassigned') {
                $this->recordBugActivity($projectId, $bugId, 'assignment_changed', 'Unassigned', $assignedTo);
            }

            if ($this->isActiveAssignment($isDuplicate, $status, $assignedTo)) {
                DB::table('developers')->where('project_id', $projectId)->where('name', $assignedTo)->increment('active_tasks');
            }

            return $bugId;
        });

        $assignee = DB::table('project_members')
            ->join('users', 'users.id', '=', 'project_members.user_id')
            ->where('project_members.project_id', $projectId)
            ->where('users.role', 'developer')
            ->where('users.name', $assignedTo)
            ->first(['users.id']);
        if ($assignee) {
            $this->notifyUsers([$assignee->id], $projectId, $bugId, 'assigned', 'Bug assigned to you', 'A new bug was assigned to you.');
        }

        return redirect('/ai-agent')
            ->with('ai_result', $aiResult)
            ->with('last_bug_id', $bugId)
            ->with('success', 'Bug analyzed and added to the workspace.');
    }

    public function override(Request $request): RedirectResponse
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'developer'], true), 403);

        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:bugs,id'],
            'status' => ['required', 'in:Open,Duplicate,In Progress,Resolved,Closed'],
        ]);
        $projectId = $this->currentProjectId();
        DB::transaction(function () use ($data, $projectId) {
            $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->lockForUpdate()->firstOrFail();
            if ($bug->status === $data['status']) {
                return;
            }

            $wasActive = $this->isActiveAssignment((bool) $bug->is_duplicate, $bug->status, $bug->assigned_to);
            $willBeActive = $this->isActiveAssignment((bool) $bug->is_duplicate, $data['status'], $bug->assigned_to);

            DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->update([
                'status' => $data['status'],
                'updated_at' => now(),
            ]);

            if ($wasActive && !$willBeActive) {
                $this->changeDeveloperWorkload($projectId, $bug->assigned_to, -1);
            } elseif (!$wasActive && $willBeActive) {
                $this->changeDeveloperWorkload($projectId, $bug->assigned_to, 1);
            }

            $this->recordBugActivity($projectId, $bug->id, 'status_changed', $bug->status, $data['status']);
            $recipients = $this->bugRelevantUserIds($bug, $projectId);
            $this->notifyUsers($recipients, $projectId, (int) $bug->id, 'status_changed', 'Bug status changed', "Status changed from {$bug->status} to {$data['status']}.");
        });

        return back()->with('success', 'Bug status updated.');
    }

    public function reassign(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $data = $request->validate([
            'id' => ['required', 'integer'],
            'assigned_to' => ['required', 'string', 'max:100'],
        ]);
        $projectId = $this->currentProjectId();

        if ($data['assigned_to'] !== 'Unassigned'
            && !DB::table('developers')->where('project_id', $projectId)->where('name', $data['assigned_to'])->exists()) {
            return back()->withErrors(['assigned_to' => 'Choose a developer from the current project.']);
        }

        DB::transaction(function () use ($data, $projectId) {
            $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->lockForUpdate()->firstOrFail();
            $oldAssignee = $bug->assigned_to ?: 'Unassigned';
            $newAssignee = $data['assigned_to'];
            if ($oldAssignee === $newAssignee) {
                return;
            }

            $isDuplicate = (bool) $bug->is_duplicate;
            $wasActive = $this->isActiveAssignment($isDuplicate, $bug->status, $oldAssignee);
            $willBeActive = $this->isActiveAssignment($isDuplicate, $bug->status, $newAssignee);

            DB::table('bugs')->where('project_id', $projectId)->where('id', $bug->id)->update([
                'assigned_to' => $newAssignee,
                'updated_at' => now(),
            ]);

            if ($wasActive) {
                $this->changeDeveloperWorkload($projectId, $oldAssignee, -1);
            }
            if ($willBeActive) {
                $this->changeDeveloperWorkload($projectId, $newAssignee, 1);
            }

            $this->recordBugActivity($projectId, $bug->id, 'assignment_changed', $oldAssignee, $newAssignee);
            $recipients = $this->bugRelevantUserIds($bug, $projectId);
            $recipients = array_merge($recipients, $this->developerUserIds($projectId, [$oldAssignee, $newAssignee]));
            $this->notifyUsers($recipients, $projectId, (int) $bug->id, 'reassigned', 'Bug assignment changed', "Assignment changed from {$oldAssignee} to {$newAssignee}.");
        });

        return back()->with('success', 'Bug assignment updated.');
    }

    public function storeComment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bug_id' => ['required', 'integer'],
            'body' => ['required', 'string', 'max:5000'],
            'mentioned_user_ids' => ['nullable', 'array', 'max:20'],
            'mentioned_user_ids.*' => ['integer', 'distinct'],
        ]);
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['bug_id'])->firstOrFail();
        $participantIds = $this->projectParticipantIds($projectId);
        $mentions = array_values(array_intersect(array_map('intval', $data['mentioned_user_ids'] ?? []), $participantIds));

        $commentId = DB::transaction(function () use ($data, $projectId, $mentions) {
            $id = DB::table('bug_comments')->insertGetId([
                'project_id' => $projectId,
                'bug_id' => $data['bug_id'],
                'user_id' => Auth::id(),
                'author_name' => Auth::user()->name,
                'body' => trim($data['body']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            foreach ($mentions as $userId) {
                DB::table('bug_comment_mentions')->insert(['comment_id' => $id, 'user_id' => $userId]);
            }
            return $id;
        });

        $mentioned = $mentions;
        $this->notifyUsers($mentioned, $projectId, (int) $bug->id, 'mentioned', 'You were mentioned in a bug', Auth::user()->name.' mentioned you: '.mb_substr(trim($data['body']), 0, 180));
        $watchers = DB::table('bug_watchers')->where('bug_id', $bug->id)->pluck('user_id')->all();
        $this->notifyUsers(array_diff($watchers, $mentioned), $projectId, (int) $bug->id, 'comment', 'New bug comment', Auth::user()->name.' commented: '.mb_substr(trim($data['body']), 0, 180));

        return back()->with('success', 'Comment posted.');
    }

    public function toggleWatch(Request $request): RedirectResponse
    {
        $data = $request->validate(['bug_id' => ['required', 'integer']]);
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['bug_id'])->firstOrFail();
        $watcher = DB::table('bug_watchers')->where('bug_id', $bug->id)->where('user_id', Auth::id())->first();
        if ($watcher) {
            DB::table('bug_watchers')->where('id', $watcher->id)->delete();
            return back()->with('success', 'You are no longer watching this bug.');
        }
        DB::table('bug_watchers')->insert(['bug_id' => $bug->id, 'user_id' => Auth::id(), 'created_at' => now()]);
        return back()->with('success', 'You are now watching this bug.');
    }

    public function notificationsPage(): View
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(30);
        return view('notifications', compact('notifications'));
    }

    public function openNotification(Request $request, string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->whereKey($id)->firstOrFail();
        $projectId = (int) ($notification->data['project_id'] ?? 0);
        $project = $this->accessibleProjects()->firstWhere('id', $projectId);
        abort_unless($project, 403);
        $this->setCurrentProject($request, $project);
        $notification->markAsRead();
        $bugId = (int) ($notification->data['bug_id'] ?? 0);
        return redirect($bugId ? '/workspace#bug-'.$bugId : '/notifications');
    }

    public function markNotificationRead(string $id): RedirectResponse
    {
        Auth::user()->notifications()->whereKey($id)->firstOrFail()->markAsRead();
        return back();
    }

    public function markAllNotificationsRead(): RedirectResponse
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        return back()->with('success', 'All notifications marked as read.');
    }

    private function projectParticipants(int $projectId)
    {
        return DB::table('users')->whereIn('id', $this->projectParticipantIds($projectId))->orderBy('name')->get(['id', 'name', 'role']);
    }

    private function projectParticipantIds(int $projectId): array
    {
        $managerId = DB::table('projects')->where('id', $projectId)->value('manager_id');
        return array_values(array_unique(array_merge(
            DB::table('project_members')->where('project_id', $projectId)->pluck('user_id')->map(fn ($id) => (int) $id)->all(),
            $managerId ? [(int) $managerId] : [],
        )));
    }

    private function bugRelevantUserIds(object $bug, int $projectId): array
    {
        $ids = DB::table('bug_watchers')->where('bug_id', $bug->id)->pluck('user_id')->all();
        if (!empty($bug->reporter_id)) $ids[] = (int) $bug->reporter_id;
        return array_values(array_unique(array_merge($ids, $this->developerUserIds($projectId, [$bug->assigned_to ?? '']))));
    }

    private function developerUserIds(int $projectId, array $names): array
    {
        return DB::table('project_members')->join('users', 'users.id', '=', 'project_members.user_id')
            ->where('project_members.project_id', $projectId)->where('users.role', 'developer')
            ->whereIn('users.name', array_filter($names))->pluck('users.id')->map(fn ($id) => (int) $id)->all();
    }

    private function notifyUsers(array $userIds, int $projectId, int $bugId, string $event, string $title, string $message): void
    {
        $actorId = (int) Auth::id();
        $recipients = array_values(array_unique(array_filter(array_map('intval', $userIds), fn ($id) => $id !== $actorId)));
        if (!$recipients) return;
        $notification = new BugUpdateNotification($projectId, $bugId, $event, $title, $message, Auth::user()->name);
        \App\Models\User::whereIn('id', $recipients)->get()->each(fn ($user) => $user->notify($notification));
    }

    private function isActiveAssignment(bool $isDuplicate, string $status, ?string $assignee): bool
    {
        return !$isDuplicate
            && !in_array($status, ['Duplicate', 'Resolved', 'Closed'], true)
            && $assignee !== null
            && $assignee !== ''
            && $assignee !== 'Unassigned';
    }

    private function changeDeveloperWorkload(int $projectId, string $developerName, int $amount): void
    {
        $query = DB::table('developers')
            ->where('project_id', $projectId)
            ->where('name', $developerName);

        if ($amount < 0) {
            $query->where('active_tasks', '>', 0)->decrement('active_tasks');
        } else {
            $query->increment('active_tasks');
        }
    }

    private function recordBugActivity(int $projectId, int $bugId, string $eventType, ?string $from, ?string $to): void
    {
        DB::table('bug_activities')->insert([
            'project_id' => $projectId,
            'bug_id' => $bugId,
            'actor_id' => Auth::id(),
            'actor_name' => Auth::user()->name,
            'event_type' => $eventType,
            'from_value' => $from,
            'to_value' => $to,
            'created_at' => now(),
        ]);
    }

    private function currentProjectId(): int
    {
        $projects = $this->accessibleProjects();
        abort_unless($projects->isNotEmpty(), 403, 'You are not assigned to a project.');

        $project = $projects->firstWhere('id', (int) session('project_id')) ?? $projects->first();
        $this->setCurrentProject(request(), $project);

        return (int) $project->id;
    }

    private function accessibleProjects()
    {
        $query = DB::table('projects')->select('projects.id', 'projects.name', 'projects.stack');

        if (Auth::user()->role === 'manager') {
            return $query->where('projects.manager_id', Auth::id())->orderBy('projects.name')->get();
        }

        return $query
            ->join('project_members', 'projects.id', '=', 'project_members.project_id')
            ->where('project_members.user_id', Auth::id())
            ->orderBy('projects.name')
            ->get();
    }

    private function setCurrentProject(Request $request, object $project): void
    {
        $request->session()->put([
            'project_id' => $project->id,
            'project_name' => $project->name,
            'project_stack' => $project->stack,
        ]);
    }
}
