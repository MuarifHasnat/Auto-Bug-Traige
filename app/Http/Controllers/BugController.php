<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use App\Notifications\BugUpdateNotification;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BugController extends Controller
{
    public function landingPage(): View
    {
        return view('landing');
    }

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
            'github_repo' => ['nullable', 'string', 'max:255'],
        ]);

        $project = DB::table('projects')->insertGetId([
            'manager_id' => Auth::id(),
            'name' => $data['project_name'],
            'stack' => $data['project_stack'],
            'github_repo' => $data['github_repo'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $request->session()->put([
            'project_name' => $data['project_name'],
            'project_stack' => $data['project_stack'],
            'github_repo' => $data['github_repo'] ?? null,
            'project_id' => $project,
        ]);
        $this->ensureProjectWorkflow((int) $project);

        $githubRepo = !empty($data['github_repo']) ? trim($data['github_repo']) : null;
        $syncNotice = '';
        if ($githubRepo) {
            try {
                $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
                $proc = Process::timeout(10)->run([$python, base_path('agent/file_locator.py'), '--index', $githubRepo]);
                if ($proc->successful()) {
                    $meta = json_decode($proc->output(), true);
                    if ($meta && ($meta['success'] ?? false)) {
                        $syncNotice = " • Indexed {$meta['total_files']} files ({$meta['primary_language']}) from branch '{$meta['branch']}'";
                    }
                }
            } catch (\Throwable) {}
        }

        return redirect('/add-developers')->with('success', "Project created successfully!{$syncNotice}");
    }

    public function syncGitHubRepo(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();

        $data = $request->validate([
            'github_repo' => ['nullable', 'string', 'max:255'],
        ]);

        if (array_key_exists('github_repo', $data) && $data['github_repo'] !== null) {
            $cleaned = trim($data['github_repo']);
            DB::table('projects')->where('id', $projectId)->update([
                'github_repo' => $cleaned ?: null,
                'updated_at' => now(),
            ]);
            session(['github_repo' => $cleaned ?: null]);
        }

        $repo = DB::table('projects')->where('id', $projectId)->value('github_repo') ?: session('github_repo');
        if (!$repo) {
            return back()->withErrors(['github' => 'No GitHub repository is linked. Please enter a valid repository URL.']);
        }

        $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
        $proc = Process::timeout(12)->run([$python, base_path('agent/file_locator.py'), '--index', $repo]);
        $meta = json_decode($proc->output(), true);

        if ($request->wantsJson()) {
            return response()->json($meta ?: ['success' => false, 'error' => 'Indexing process failed.']);
        }

        if ($meta && ($meta['success'] ?? false)) {
            return back()->with('success', "GitHub repository synced! Successfully cataloged {$meta['total_files']} files ({$meta['primary_language']}) on branch '{$meta['branch']}'. AI File Locator is primed!");
        }

        return back()->with('success', "GitHub repository link saved. (Tree pre-fetch will re-attempt during triage).");
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
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        DB::table('developers')->insert([
            'project_id' => $this->currentProjectId(),
            'name' => $data['name'],
            'skills' => strtolower($data['skills']),
            'active_tasks' => 0,
            'capacity' => $data['capacity'] ?? 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Developer added successfully.');
    }

    public function addTeamMember(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();

        $existingUser = \App\Models\User::where('email', $request->input('email'))->first();
        if ($existingUser) {
            $isAlreadyMember = DB::table('project_members')
                ->where('project_id', $projectId)
                ->where('user_id', $existingUser->id)
                ->exists();
            if ($isAlreadyMember) {
                return back()->withErrors(['email' => 'This user is already a member of this project.'])->withInput();
            }

            $data = $request->validate([
                'skills' => ['nullable', 'string', 'max:255'],
                'capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
            ]);

            DB::transaction(function () use ($existingUser, $projectId, $data) {
                DB::table('project_members')->insert([
                    'project_id' => $projectId,
                    'user_id' => $existingUser->id,
                ]);

                if ($existingUser->role === 'developer') {
                    DB::table('developers')->updateOrInsert(
                        ['project_id' => $projectId, 'name' => $existingUser->name],
                        [
                            'skills' => strtolower($data['skills'] ?? ''),
                            'active_tasks' => 0,
                            'capacity' => $data['capacity'] ?? 5,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            });

            return back()->with('success', ucfirst($existingUser->role).' ('.$existingUser->name.') assigned to this project successfully.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:tester,developer'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'skills' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
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
                    'capacity' => $data['capacity'] ?? 5,
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
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        DB::table('developers')->where('project_id', $this->currentProjectId())->where('id', $id)->update([
            'name' => $data['name'],
            'skills' => strtolower($data['skills']),
            'capacity' => $data['capacity'],
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Developer updated successfully.');
    }

    public function deleteDeveloper(int $id): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();

        $developer = DB::table('developers')->where('project_id', $projectId)->where('id', $id)->first();

        if (!$developer) {
            return back()->withErrors(['developer' => 'Developer not found.']);
        }

        if ($developer->active_tasks > 0) {
            return back()->withErrors(['developer' => 'A developer with active tasks cannot be removed.']);
        }

        DB::transaction(function () use ($projectId, $id, $developer) {
            DB::table('developers')->where('project_id', $projectId)->where('id', $id)->delete();
            $user = DB::table('users')->where('name', $developer->name)->first();
            if ($user) {
                DB::table('project_members')->where('project_id', $projectId)->where('user_id', $user->id)->delete();
            }
        });

        return back()->with('success', 'Developer removed successfully.');
    }

    public function workspacePage(Request $request): View
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'developer', 'tester'], true), 403);

        $projectId = $this->currentProjectId();
        $workflowStatuses = $this->ensureProjectWorkflow($projectId);
        $developers = DB::table('developers')->where('project_id', $projectId)->orderByDesc('active_tasks')->orderBy('name')->get();
        $query = DB::table('bugs')->where('project_id', $projectId)->orderByDesc('id');

        $legacyStatusNames = ['Open' => 'To Do', 'Resolved' => 'Done', 'Closed' => 'Done'];
        if (isset($legacyStatusNames[$request->input('status')])) $request->merge(['status' => $legacyStatusNames[$request->input('status')]]);
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:200'],
            'assignee' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in($workflowStatuses->pluck('name')->all())],
            'priority' => ['nullable', Rule::in(['High', 'Medium', 'Low'])],
            'issue_type' => ['nullable', Rule::in(['Bug', 'Task', 'Story', 'Improvement'])],
            'label' => ['nullable', 'string', 'max:50'],
        ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['priority'])) $query->where('priority', $filters['priority']);
        if (!empty($filters['assignee'])) {
            $query->where('assigned_to', $filters['assignee'] === 'Unassigned' ? null : $filters['assignee']);
        }
        if (!empty($filters['issue_type'])) $query->where('issue_type', $filters['issue_type']);
        if (!empty($filters['label'])) $query->whereJsonContains('labels', $filters['label']);

        $bugs = $query->get();
        $doneStatuses = $workflowStatuses->where('is_done', true)->pluck('name')->all();
        $activeStatuses = $workflowStatuses->where('is_done', false)->where('name', '!=', 'Duplicate')->pluck('name')->all();
        $stats = [
            'total' => DB::table('bugs')->where('project_id', $projectId)->count(),
            'open' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $activeStatuses)->count(),
            'resolved' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $doneStatuses)->count(),
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
        $clarifications = DB::table('bug_clarification_requests')
            ->where('project_id', $projectId)->orderBy('created_at')->get()->groupBy('bug_id');
        $watchingBugIds = DB::table('bug_watchers')->where('user_id', Auth::id())->pluck('bug_id')->all();
        $projectMembers = $this->projectParticipants($projectId);
        $bugMetrics = DB::table('bug_triage_metrics')->where('project_id', $projectId)->get()->keyBy('bug_id');
        $transitionsByStatus = DB::table('project_workflow_transitions')->where('project_id', $projectId)->orderBy('to_status')->get()->groupBy('from_status');
        $availableAssignees = DB::table('bugs')->where('project_id', $projectId)->whereNotNull('assigned_to')->distinct()->orderBy('assigned_to')->pluck('assigned_to')->all();
        array_unshift($availableAssignees, 'Unassigned');
        $availableLabels = DB::table('bugs')->where('project_id', $projectId)->whereNotNull('labels')->pluck('labels')
            ->flatMap(fn ($json) => is_array($json) ? $json : (json_decode($json, true) ?: []))->unique()->sort()->values();
        $savedFilters = DB::table('saved_bug_filters')->where('user_id', Auth::id())->where('project_id', $projectId)->orderBy('name')->get()
            ->map(function ($saved) { $saved->filters = json_decode($saved->filters, true) ?: []; return $saved; });

        $projects = $this->accessibleProjects();

        return view('workspace', compact('developers', 'bugs', 'stats', 'assignedBugs', 'projects', 'bugActivity', 'bugComments', 'clarifications', 'watchingBugIds', 'projectMembers', 'bugMetrics', 'filters', 'availableAssignees', 'availableLabels', 'savedFilters', 'workflowStatuses', 'transitionsByStatus') + [
            'currentProjectId' => $projectId,
        ]);
    }

    public function showBug(int $id): View
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'developer', 'tester'], true), 403);

        $projectId = $this->currentProjectId();
        $this->ensureProjectWorkflow($projectId);
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $id)->firstOrFail();
        $bug->triage = json_decode($bug->triage_result ?: '', true) ?: [];
        $comments = DB::table('bug_comments')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();
        $activities = DB::table('bug_activities')->where('project_id', $projectId)->where('bug_id', $id)->orderByDesc('created_at')->orderByDesc('id')->get();
        $clarifications = DB::table('bug_clarification_requests')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();
        $isWatching = DB::table('bug_watchers')->where('bug_id', $id)->where('user_id', Auth::id())->exists();
        $projectMembers = $this->projectParticipants($projectId);
        $developers = DB::table('developers')->where('project_id', $projectId)->orderBy('name')->get();
        $workflowStatuses = DB::table('project_workflow_statuses')->where('project_id', $projectId)->orderBy('position')->get();
        $transitions = DB::table('project_workflow_transitions')->where('project_id', $projectId)->where('from_status', $bug->status)->pluck('to_status')->all();
        $triageMetric = DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $id)->first();
        $projects = $this->accessibleProjects();

        return view('bug_details', compact(
            'bug', 'comments', 'activities', 'clarifications', 'isWatching',
            'projectMembers', 'developers', 'workflowStatuses', 'transitions',
            'triageMetric', 'projects'
        ) + [
            'currentProjectId' => $projectId,
        ]);
    }

    public function showScreenshot(int $id)
    {
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $id)->firstOrFail();
        abort_unless($bug->screenshot_path && Storage::disk('local')->exists($bug->screenshot_path), 404, 'Screenshot not found.');

        return Storage::disk('local')->response($bug->screenshot_path);
    }

    public function printableBugReport(int $id): View
    {
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $id)->firstOrFail();
        $bug->triage = json_decode($bug->triage_result ?: '', true) ?: [];
        $comments = DB::table('bug_comments')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();
        $activities = DB::table('bug_activities')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();
        $clarifications = DB::table('bug_clarification_requests')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();

        return view('bug_report_print', compact('bug', 'comments', 'activities', 'clarifications'));
    }

    public function dashboardPage(Request $request): View
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $projectId = $this->currentProjectId();
        $workflowStatuses = $this->ensureProjectWorkflow($projectId);
        $doneStatuses = $workflowStatuses->where('is_done', true)->pluck('name')->all();
        $activeStatuses = $workflowStatuses->where('is_done', false)->where('name', '!=', 'Duplicate')->pluck('name')->all();
        $stats = [
            'total' => DB::table('bugs')->where('project_id', $projectId)->count(),
            'open' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $activeStatuses)->count(),
            'resolved' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $doneStatuses)->count(),
            'high' => DB::table('bugs')->where('project_id', $projectId)->where('priority', 'High')->count(),
        ];
        $recentBugs = DB::table('bugs')->where('project_id', $projectId)->orderByDesc('id')->limit(8)->get();
        $developers = DB::table('developers')->where('project_id', $projectId)->orderByDesc('active_tasks')->limit(6)->get();
        $metricRows = DB::table('bug_triage_metrics')->where('project_id', $projectId)->get();
        $reviewedAssignments = $metricRows->whereIn('assignment_feedback', ['accepted', 'rejected']);
        $reviewedDuplicates = $metricRows->whereNotNull('actual_duplicate');
        $truePositives = $reviewedDuplicates->filter(fn ($row) => (bool) $row->predicted_duplicate && (bool) $row->actual_duplicate)->count();
        $falsePositives = $reviewedDuplicates->filter(fn ($row) => (bool) $row->predicted_duplicate && !(bool) $row->actual_duplicate)->count();
        $falseNegatives = $reviewedDuplicates->filter(fn ($row) => !(bool) $row->predicted_duplicate && (bool) $row->actual_duplicate)->count();
        $triageEvaluation = [
            'sample_size' => $metricRows->count(),
            'average_latency_ms' => $metricRows->count() ? (int) round($metricRows->avg('triage_duration_ms')) : null,
            'under_five_seconds_percent' => $metricRows->count() ? (int) round($metricRows->where('triage_duration_ms', '<', 5000)->count() / $metricRows->count() * 100) : null,
            'assignment_review_count' => $reviewedAssignments->count(),
            'assignment_acceptance_percent' => $reviewedAssignments->count() ? (int) round($reviewedAssignments->where('assignment_feedback', 'accepted')->count() / $reviewedAssignments->count() * 100) : null,
            'duplicate_review_count' => $reviewedDuplicates->count(),
            'duplicate_precision_percent' => ($truePositives + $falsePositives) ? (int) round($truePositives / ($truePositives + $falsePositives) * 100) : null,
            'duplicate_recall_percent' => ($truePositives + $falseNegatives) ? (int) round($truePositives / ($truePositives + $falseNegatives) * 100) : null,
        ];

        $projects = $this->accessibleProjects();
        $filters = $request->validate([
            'account_search' => ['nullable', 'string', 'max:100'],
            'account_role' => ['nullable', 'in:all,manager,developer,tester'],
        ]);
        $accountSearch = trim($filters['account_search'] ?? '');
        $accountRole = $filters['account_role'] ?? 'all';
        $participantIds = $this->projectParticipantIds($projectId);
        $accountsQuery = DB::table('users')
            ->whereIn('id', $participantIds)
            ->select('id', 'name', 'email', 'role')
            ->orderBy('name');

        if ($accountSearch !== '') {
            $accountsQuery->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($accountSearch).'%']);
        }
        if ($accountRole !== 'all') {
            $accountsQuery->where('role', $accountRole);
        }

        $accounts = $accountsQuery->paginate(20)->withQueryString();
        $totalAccounts = count($participantIds);

        $devWorkloads = DB::table('developers')
            ->where('project_id', $projectId)
            ->pluck('active_tasks', 'name')
            ->all();

        return view('dashboard', compact('stats', 'recentBugs', 'developers', 'projects', 'accounts', 'accountSearch', 'accountRole', 'totalAccounts', 'triageEvaluation', 'devWorkloads') + [
            'currentProjectId' => $projectId,
        ]);
    }

    public function aiAgentPage(Request $request): View
    {
        abort_unless(in_array(Auth::user()?->role, ['tester', 'developer'], true), 403);

        $projectId = $this->currentProjectId();
        $this->ensureProjectWorkflow($projectId);
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

    public function store(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        abort_unless(Auth::user()?->role === 'tester', 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:5000'],
            'issue_type' => ['required', Rule::in(['Bug', 'Task', 'Story', 'Improvement'])],
            'labels' => ['nullable', 'string', 'max:500'],
            'language' => ['nullable', 'string', 'in:auto,PHP,C++,C,Python,JavaScript,TypeScript,Java,Kotlin,SQL'],
            'code_snippet' => ['nullable', 'string', 'max:20000'],
            'screenshot' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'source_file' => ['nullable', 'file', 'mimes:txt,php,blade.php,cpp,c,h,hpp,py,js,ts,java,kt,kts,sql,json,xml,css,html', 'max:5120'],
        ]);

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
        $projectWorkflow = $this->ensureProjectWorkflow($projectId);
        $doneStatuses = $projectWorkflow->where('is_done', true)->pluck('name')->all();
        $pastBugs = DB::table('bugs')
            ->where('project_id', $projectId)
            ->whereRaw('is_duplicate = false')
            ->select('id', 'title', 'description')
            ->get()
            ->map(fn ($bug) => (array) $bug)
            ->all();
        $fixHistory = [];
        if ($doneStatuses) {
            foreach (DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $doneStatuses)->whereRaw('is_duplicate = false')
                ->whereNotNull('assigned_to')->get(['assigned_to', 'detected_feature', 'triage_result']) as $fixedBug) {
                $history = $fixHistory[$fixedBug->assigned_to] ?? ['resolved_features' => [], 'resolved_languages' => []];
                if ($fixedBug->detected_feature) $history['resolved_features'][] = strtolower($fixedBug->detected_feature);
                $resolvedTriage = json_decode($fixedBug->triage_result ?: '', true) ?: [];
                $resolvedLanguage = strtolower($resolvedTriage['code_help']['language'] ?? '');
                if ($resolvedLanguage && $resolvedLanguage !== 'unknown') $history['resolved_languages'][] = $resolvedLanguage;
                $fixHistory[$fixedBug->assigned_to] = $history;
            }
        }
        $developers = DB::table('developers')
            ->where('project_id', $projectId)
            ->select('id', 'name', 'skills', 'active_tasks', 'capacity')
            ->get()
            ->map(function ($developer) use ($fixHistory) {
                return array_merge((array) $developer, $fixHistory[$developer->name] ?? ['resolved_features' => [], 'resolved_languages' => []]);
            })
            ->all();

        $payloadPath = tempnam(storage_path('app'), 'triage_payload_');
        if ($payloadPath === false) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not prepare the report for triage.',
                ], 500);
            }
            return back()->withErrors(['bug' => 'Could not prepare the report for triage.']);
        }

        $triageStartedAt = microtime(true);
        try {
            file_put_contents($payloadPath, json_encode([
                ...$data,
                'code_snippet' => $analysisCode,
                'selected_language' => $data['language'] ?? 'auto',
                'attachment' => $attachment,
                'past_bugs' => $pastBugs,
                'developers' => $developers,
                'github_repo' => DB::table('projects')->where('id', $projectId)->value('github_repo') ?: session('github_repo'),
                'project_stack' => DB::table('projects')->where('id', $projectId)->value('stack') ?: session('project_stack', ''),
                'source_file_name' => $request->file('source_file')?->getClientOriginalName(),
            ], JSON_THROW_ON_ERROR));

            $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
            $result = Process::run([$python, base_path('agent/triage.py'), $payloadPath]);
        } finally {
            @unlink($payloadPath);
        }
        $triageDurationMs = (int) round((microtime(true) - $triageStartedAt) * 1000);

        if (!$result->successful()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI triage failed: '.$result->errorOutput(),
                ], 422);
            }
            return back()->withErrors(['bug' => 'AI triage failed: '.$result->errorOutput()]);
        }

        $aiResult = json_decode($result->output(), true);
        if (!is_array($aiResult)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI triage returned an invalid response: '.$result->output(),
                ], 422);
            }
            return back()->withErrors(['bug' => 'AI triage returned an invalid response.']);
        }

        $isDuplicate = (bool) ($aiResult['is_duplicate'] ?? false);
        $assignedTo = $aiResult['assigned_to'] ?? 'Unassigned';
        $priority = $aiResult['priority'] ?? 'Medium';
        $status = $isDuplicate ? 'Duplicate' : $projectWorkflow->first(fn ($workflowStatus) => $workflowStatus->name !== 'Duplicate')->name;
        $aiResult['status'] = $status;
        $labels = array_values(array_unique(array_filter(array_map('trim', explode(',', $data['labels'] ?? '')))));
        $bugId = DB::transaction(function () use (
            $projectId,
            $data,
            $aiResult,
            $isDuplicate,
            $assignedTo,
            $priority,
            $status,
            $labels,
            $triageDurationMs,
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
                'issue_type' => $data['issue_type'],
                'labels' => json_encode($labels, JSON_THROW_ON_ERROR),
                'assigned_to' => $assignedTo,
                'recommended_assignee' => $assignedTo,
                'sentiment' => $aiResult['sentiment'] ?? 'neutral',
                'sentiment_score' => $aiResult['sentiment_score'] ?? 0,
                'blocker_signal' => DB::raw(!empty($aiResult['blocker_signal']) ? 'true' : 'false'),
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

            DB::table('bug_triage_metrics')->insert([
                'project_id' => $projectId,
                'bug_id' => $bugId,
                'triage_duration_ms' => $triageDurationMs,
                'duplicate_similarity_score' => $aiResult['similarity_score'] ?? 0,
                'predicted_duplicate' => DB::raw($isDuplicate ? 'true' : 'false'),
                'recommended_assignee' => $assignedTo,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->recordBugActivity($projectId, $bugId, 'created', null, "Status: {$status}; Priority: {$priority}");
            if ($assignedTo !== 'Unassigned') {
                $this->recordBugActivity($projectId, $bugId, 'assignment_changed', 'Unassigned', $assignedTo);
            }

            if ($this->isActiveAssignment($projectId, $isDuplicate, $status, $assignedTo)) {
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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'bug_id' => $bugId,
                'assigned_to' => $assignedTo,
                'ai_result' => $aiResult,
                'redirect_url' => '/bugs/' . $bugId,
                'message' => 'Bug analyzed and added to the workspace.',
            ]);
        }

        return redirect('/ai-agent')
            ->with('ai_result', $aiResult)
            ->with('last_bug_id', $bugId)
            ->with('success', 'Bug analyzed and added to the workspace.');
    }

    public function override(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'developer'], true), 403);

        $projectId = $this->currentProjectId();
        $workflowStatuses = $this->ensureProjectWorkflow($projectId);
        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:bugs,id'],
            'status' => ['required', 'string', Rule::in($workflowStatuses->pluck('name')->all())],
        ]);
        DB::transaction(function () use ($data, $projectId) {
            $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->lockForUpdate()->firstOrFail();
            if ($bug->status === $data['status']) {
                return;
            }
            abort_unless(DB::table('project_workflow_transitions')->where('project_id', $projectId)
                ->where('from_status', $bug->status)->where('to_status', $data['status'])->exists(), 422, 'That status transition is not allowed.');

            $wasActive = $this->isActiveAssignment($projectId, (bool) $bug->is_duplicate, $bug->status, $bug->assigned_to);
            $willBeActive = $this->isActiveAssignment($projectId, (bool) $bug->is_duplicate, $data['status'], $bug->assigned_to);

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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Bug status moved to {$data['status']}.",
            ]);
        }

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
            $wasActive = $this->isActiveAssignment($projectId, $isDuplicate, $bug->status, $oldAssignee);
            $willBeActive = $this->isActiveAssignment($projectId, $isDuplicate, $bug->status, $newAssignee);

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
            $recommendation = $bug->recommended_assignee
                ?: DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $bug->id)->value('recommended_assignee')
                ?: 'Unassigned';
            DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $bug->id)->update([
                'assignment_feedback' => $newAssignee === $recommendation ? 'accepted' : 'rejected',
                'reviewed_by' => Auth::id(), 'reviewed_at' => now(), 'updated_at' => now(),
            ]);
            $recipients = $this->bugRelevantUserIds($bug, $projectId);
            $recipients = array_merge($recipients, $this->developerUserIds($projectId, [$oldAssignee, $newAssignee]));
            $this->notifyUsers($recipients, $projectId, (int) $bug->id, 'reassigned', 'Bug assignment changed', "Assignment changed from {$oldAssignee} to {$newAssignee}.");
        });

        return back()->with('success', 'Bug assignment updated.');
    }

    public function acceptRecommendation(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $data = $request->validate(['id' => ['required', 'integer']]);
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->firstOrFail();
        $existingMetric = DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $bug->id)->first();
        $recommendation = $bug->recommended_assignee ?: ($existingMetric->recommended_assignee ?? ($bug->assigned_to ?: 'Unassigned'));
        abort_if($recommendation === 'Unassigned' || $bug->assigned_to !== $recommendation, 422, 'The current assignment does not match an assignable recommendation.');
        if (!$existingMetric) {
            $triage = json_decode($bug->triage_result ?: '', true) ?: [];
            DB::table('bug_triage_metrics')->insert([
                'project_id' => $projectId, 'bug_id' => $bug->id, 'triage_duration_ms' => 0,
                'duplicate_similarity_score' => $triage['similarity_score'] ?? 0,
                'predicted_duplicate' => DB::raw(!empty($bug->is_duplicate) ? 'true' : 'false'), 'recommended_assignee' => $recommendation,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        $updated = DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $bug->id)
            ->update(['assignment_feedback' => 'accepted', 'reviewed_by' => Auth::id(), 'reviewed_at' => now(), 'updated_at' => now()]);
        abort_unless($updated, 404, 'No triage measurement exists for this issue.');
        return back()->with('success', 'AI assignment recommendation marked as accepted.');
    }

    public function reviewDuplicatePrediction(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $data = $request->validate([
            'id' => ['required', 'integer'],
            'actual_duplicate' => ['required', 'boolean'],
        ]);
        $projectId = $this->currentProjectId();
        $workflowStatuses = $this->ensureProjectWorkflow($projectId);

        DB::transaction(function () use ($data, $projectId, $workflowStatuses) {
            $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->lockForUpdate()->firstOrFail();
            $metrics = DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $bug->id)->first();
            if (!$metrics) {
                $triage = json_decode($bug->triage_result ?: '', true) ?: [];
                DB::table('bug_triage_metrics')->insert([
                    'project_id' => $projectId, 'bug_id' => $bug->id, 'triage_duration_ms' => 0,
                    'duplicate_similarity_score' => $triage['similarity_score'] ?? 0,
                    'predicted_duplicate' => DB::raw(!empty($bug->is_duplicate) ? 'true' : 'false'),
                    'recommended_assignee' => $bug->recommended_assignee ?: ($bug->assigned_to ?: 'Unassigned'),
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                $metrics = DB::table('bug_triage_metrics')->where('bug_id', $bug->id)->first();
            }

            $actualDuplicate = (bool) $data['actual_duplicate'];
            $targetStatus = $actualDuplicate
                ? 'Duplicate'
                : ($bug->status === 'Duplicate'
                    ? $workflowStatuses->first(fn ($status) => $status->name !== 'Duplicate')->name
                    : $bug->status);
            $wasActive = $this->isActiveAssignment($projectId, (bool) $bug->is_duplicate, $bug->status, $bug->assigned_to);
            $willBeActive = $this->isActiveAssignment($projectId, $actualDuplicate, $targetStatus, $bug->assigned_to);

            DB::table('bugs')->where('id', $bug->id)->update([
                'is_duplicate' => DB::raw($actualDuplicate ? 'true' : 'false'),
                'status' => $targetStatus,
                'duplicate_of' => $actualDuplicate ? $bug->duplicate_of : null,
                'updated_at' => now(),
            ]);
            if ($wasActive && !$willBeActive) $this->changeDeveloperWorkload($projectId, $bug->assigned_to, -1);
            elseif (!$wasActive && $willBeActive) $this->changeDeveloperWorkload($projectId, $bug->assigned_to, 1);

            DB::table('bug_triage_metrics')->where('id', $metrics->id)->update([
                'actual_duplicate' => DB::raw($actualDuplicate ? 'true' : 'false'),
                'reviewed_by' => Auth::id(), 'reviewed_at' => now(), 'updated_at' => now(),
            ]);
            $this->recordBugActivity($projectId, $bug->id, 'duplicate_reviewed', $bug->is_duplicate ? 'Duplicate' : 'Not duplicate', $actualDuplicate ? 'Duplicate' : 'Not duplicate');
            if ($bug->status !== $targetStatus) {
                $this->recordBugActivity($projectId, $bug->id, 'status_changed', $bug->status, $targetStatus);
                $this->notifyUsers($this->bugRelevantUserIds($bug, $projectId), $projectId, (int) $bug->id, 'status_changed', 'Bug classification updated', "Duplicate review moved this issue from {$bug->status} to {$targetStatus}.");
            }
        });

        return back()->with('success', 'Duplicate prediction reviewed and issue classification updated.');
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

    public function requestClarification(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bug_id' => ['required', 'integer'],
            'question' => ['required', 'string', 'max:2000'],
        ]);
        $projectId = $this->currentProjectId();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['bug_id'])->firstOrFail();
        abort_unless(Auth::user()->role === 'developer' && $bug->assigned_to === Auth::user()->name, 403, 'Only the assigned developer can request clarification.');
        $question = trim($data['question']);
        $requestId = DB::table('bug_clarification_requests')->insertGetId([
            'project_id' => $projectId, 'bug_id' => $bug->id, 'requested_by' => Auth::id(),
            'question' => $question, 'status' => 'open', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->recordBugActivity($projectId, (int) $bug->id, 'clarification_requested', null, $question);
        $recipientIds = array_merge($this->bugRelevantUserIds($bug, $projectId), $bug->reporter_id ? [(int) $bug->reporter_id] : []);
        $this->notifyUsers($recipientIds, $projectId, (int) $bug->id, 'clarification_requested', 'Clarification requested', Auth::user()->name.' asked: '.mb_substr($question, 0, 180));
        return back()->with('success', 'Clarification request sent.');
    }

    public function answerClarification(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'request_id' => ['required', 'integer'],
            'answer' => ['required', 'string', 'max:2000'],
        ]);
        $projectId = $this->currentProjectId();
        $clarification = DB::table('bug_clarification_requests')->where('project_id', $projectId)->where('id', $data['request_id'])->firstOrFail();
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $clarification->bug_id)->firstOrFail();
        abort_unless(Auth::user()->role === 'manager' || (int) $bug->reporter_id === Auth::id(), 403, 'Only the reporter or manager can answer this request.');
        abort_if($clarification->status !== 'open', 422, 'This clarification has already been answered.');
        $answer = trim($data['answer']);
        DB::table('bug_clarification_requests')->where('id', $clarification->id)->update([
            'answer' => $answer, 'status' => 'answered', 'answered_by' => Auth::id(), 'answered_at' => now(), 'updated_at' => now(),
        ]);
        $this->recordBugActivity($projectId, (int) $bug->id, 'clarification_answered', $clarification->question, $answer);
        $this->notifyUsers([(int) $clarification->requested_by], $projectId, (int) $bug->id, 'clarification_answered', 'Clarification answered', Auth::user()->name.' answered: '.mb_substr($answer, 0, 180));
        return back()->with('success', 'Clarification answered.');
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
        return redirect($bugId ? route('bugs.show', $bugId, false) : '/notifications');
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

    public function saveBugFilter(Request $request): RedirectResponse
    {
        $projectId = $this->currentProjectId();
        $assignees = DB::table('bugs')->where('project_id', $projectId)->whereNotNull('assigned_to')->distinct()->pluck('assigned_to')->all();
        $assignees[] = 'Unassigned';
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', Rule::unique('saved_bug_filters', 'name')->where(fn ($query) => $query->where('user_id', Auth::id())->where('project_id', $projectId))],
            'filters' => ['nullable', 'array'],
            'filters.search' => ['nullable', 'string', 'max:200'],
            'filters.assignee' => ['nullable', Rule::in($assignees)],
            'filters.status' => ['nullable', Rule::in($this->ensureProjectWorkflow($projectId)->pluck('name')->all())],
            'filters.priority' => ['nullable', Rule::in(['High', 'Medium', 'Low'])],
            'filters.issue_type' => ['nullable', Rule::in(['Bug', 'Task', 'Story', 'Improvement'])],
            'filters.label' => ['nullable', 'string', 'max:50'],
        ]);
        $filters = array_filter($data['filters'] ?? [], fn ($value) => $value !== null && $value !== '');
        DB::table('saved_bug_filters')->insert([
            'user_id' => Auth::id(), 'project_id' => $projectId, 'name' => $data['name'],
            'filters' => json_encode($filters, JSON_THROW_ON_ERROR), 'created_at' => now(), 'updated_at' => now(),
        ]);
        return back()->with('success', 'Filter saved.');
    }

    public function deleteBugFilter(int $id): RedirectResponse
    {
        DB::table('saved_bug_filters')->where('id', $id)->where('user_id', Auth::id())->where('project_id', $this->currentProjectId())->delete();
        return back()->with('success', 'Saved filter deleted.');
    }

    public function workflowPage(): View
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();
        $statuses = $this->ensureProjectWorkflow($projectId);
        $transitions = DB::table('project_workflow_transitions')->where('project_id', $projectId)->orderBy('from_status')->orderBy('to_status')->get();
        return view('workflow_settings', compact('statuses', 'transitions'));
    }

    public function saveWorkflow(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();
        $this->ensureProjectWorkflow($projectId);
        $data = $request->validate([
            'statuses' => ['required', 'string', 'max:2000'],
            'done_statuses' => ['nullable', 'string', 'max:500'],
            'transitions' => ['nullable', 'string', 'max:4000'],
        ]);

        $statuses = array_values(array_unique(array_filter(array_map('trim', preg_split('/\R/', $data['statuses'])))));
        $normalized = array_map(fn ($name) => mb_strtolower($name), $statuses);
        if (count($statuses) < 3 || count($statuses) > 12 || count(array_unique($normalized)) !== count($normalized)
            || !in_array('Duplicate', $statuses, true) || $statuses[0] === 'Duplicate') {
            return back()->withErrors(['statuses' => 'Use 3–12 unique status names, one per line. Keep the reserved Duplicate status, and put the initial status first.']);
        }
        foreach ($statuses as $name) {
            if (mb_strlen($name) > 40 || preg_match('/[<>]/u', $name) || str_contains($name, '->')) {
                return back()->withErrors(['statuses' => 'Each status must be 40 characters or fewer and cannot contain angle brackets or “->”.']);
            }
        }
        $usedStatuses = DB::table('bugs')->where('project_id', $projectId)->distinct()->pluck('status')->all();
        if (array_diff($usedStatuses, $statuses)) {
            return back()->withErrors(['statuses' => 'Keep every status currently used by a bug. Move those bugs first before removing a status.']);
        }

        $doneStatuses = array_values(array_unique(array_filter(array_map('trim', preg_split('/\R/', $data['done_statuses'] ?? '')))));
        if (array_diff($doneStatuses, $statuses)) {
            return back()->withErrors(['done_statuses' => 'Completed statuses must also appear in the status list.']);
        }
        if (!$doneStatuses || in_array('Duplicate', $doneStatuses, true)) {
            return back()->withErrors(['done_statuses' => 'Choose at least one completed status. Duplicate cannot be a completed status.']);
        }

        $transitionPairs = [];
        foreach (preg_split('/\R/', $data['transitions'] ?? '') as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $pair = preg_split('/\s*->\s*/', $line);
            if (count($pair) !== 2 || !in_array($pair[0], $statuses, true) || !in_array($pair[1], $statuses, true) || $pair[0] === $pair[1]) {
                return back()->withErrors(['transitions' => 'Each transition must use existing status names, like “To Do -> In Progress”.']);
            }
            $transitionPairs[$pair[0]."\0".$pair[1]] = [$pair[0], $pair[1]];
        }
        if (!$transitionPairs) return back()->withErrors(['transitions' => 'Add at least one allowed transition.']);

        DB::transaction(function () use ($projectId, $statuses, $doneStatuses, $transitionPairs) {
            DB::table('project_workflow_transitions')->where('project_id', $projectId)->delete();
            DB::table('project_workflow_statuses')->where('project_id', $projectId)->delete();
            foreach ($statuses as $position => $name) {
                DB::table('project_workflow_statuses')->insert([
                    'project_id' => $projectId, 'name' => $name, 'position' => $position,
                    'is_done' => DB::raw(in_array($name, $doneStatuses, true) ? 'true' : 'false'), 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            foreach (array_values($transitionPairs) as [$from, $to]) {
                DB::table('project_workflow_transitions')->insert([
                    'project_id' => $projectId, 'from_status' => $from, 'to_status' => $to,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            $activeNames = DB::table('project_workflow_statuses')->where('project_id', $projectId)->whereRaw('is_done = false')->where('name', '!=', 'Duplicate')->pluck('name')->all();
            foreach (DB::table('developers')->where('project_id', $projectId)->get(['name']) as $developer) {
                $activeCount = DB::table('bugs')->where('project_id', $projectId)->where('assigned_to', $developer->name)
                    ->whereIn('status', $activeNames)->whereRaw('is_duplicate = false')->count();
                DB::table('developers')->where('project_id', $projectId)->where('name', $developer->name)->update(['active_tasks' => $activeCount]);
            }
        });
        return back()->with('success', 'Project workflow updated.');
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

    private function isActiveAssignment(int $projectId, bool $isDuplicate, string $status, ?string $assignee): bool
    {
        $isDone = (bool) DB::table('project_workflow_statuses')->where('project_id', $projectId)->where('name', $status)->value('is_done');
        return !$isDuplicate
            && !$isDone
            && $assignee !== null
            && $assignee !== ''
            && $assignee !== 'Unassigned';
    }

    private function ensureProjectWorkflow(int $projectId)
    {
        $statuses = DB::table('project_workflow_statuses')->where('project_id', $projectId)->orderBy('position')->get();
        if ($statuses->isNotEmpty()) return $statuses;

        $defaults = [['To Do', false], ['In Progress', false], ['Review', false], ['Done', true], ['Duplicate', false]];
        $edges = [['To Do', 'In Progress'], ['In Progress', 'To Do'], ['In Progress', 'Review'], ['Review', 'In Progress'], ['Review', 'Done'], ['Done', 'To Do']];
        DB::transaction(function () use ($projectId, $defaults, $edges) {
            foreach ($defaults as $position => [$name, $isDone]) {
                DB::table('project_workflow_statuses')->insertOrIgnore([
                    'project_id' => $projectId, 'name' => $name, 'position' => $position,
                    'is_done' => DB::raw($isDone ? 'true' : 'false'), 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            foreach ($edges as [$from, $to]) {
                DB::table('project_workflow_transitions')->insertOrIgnore([
                    'project_id' => $projectId, 'from_status' => $from, 'to_status' => $to,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        });
        return DB::table('project_workflow_statuses')->where('project_id', $projectId)->orderBy('position')->get();
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
        $query = DB::table('projects')->select('projects.id', 'projects.name', 'projects.stack', 'projects.github_repo');

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
            'github_repo' => $project->github_repo ?? null,
        ]);
    }
}
