<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
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
        $request->session()->put([...$data, 'project_id' => $project]);

        return redirect('/add-developers');
    }

    public function addDevelopersPage(): View
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $projectId = $this->currentProjectId();
        $developers = DB::table('developers')->where('project_id', $projectId)->orderBy('name')->get();
        $teamMembers = \App\Models\User::query()
            ->whereIn('role', ['tester', 'developer'])
            ->whereIn('id', DB::table('project_members')->where('project_id', $projectId)->pluck('user_id'))
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        return view('add_developers', compact('developers', 'teamMembers'));
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
        abort_unless(in_array(Auth::user()?->role, ['manager', 'developer'], true), 403);

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

        return view('workspace', compact('developers', 'bugs', 'stats'));
    }

    public function dashboardPage(): View
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

        return view('dashboard', compact('stats', 'recentBugs', 'developers'));
    }

    public function aiAgentPage(Request $request): View
    {
        abort_unless(in_array(Auth::user()?->role, ['tester', 'developer'], true), 403);

        $projectId = $this->currentProjectId();
        $bugs = DB::table('bugs')->where('project_id', $projectId)->orderByDesc('id')->limit(10)->get();
        $mode = Auth::user()->role === 'developer' ? 'developer' : 'tester';

        return view('ai_agent', compact('bugs', 'mode'));
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
            ->where('is_duplicate', false)
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

        $payloadPath = storage_path('app/triage_payload.json');
        file_put_contents($payloadPath, json_encode([
            ...$data,
            'code_snippet' => $analysisCode,
            'selected_language' => $data['language'] ?? 'auto',
            'attachment' => $attachment,
            'past_bugs' => $pastBugs,
            'developers' => $developers,
        ], JSON_THROW_ON_ERROR));

        $result = Process::run(['python', base_path('agent/triage.py'), $payloadPath]);
        if (!$result->successful()) {
            return back()->withErrors(['bug' => 'AI triage failed: '.$result->errorOutput()]);
        }

        $aiResult = json_decode($result->output(), true);
        if (!is_array($aiResult)) {
            return back()->withErrors(['bug' => 'AI triage returned an invalid response.']);
        }

        $isDuplicate = (bool) ($aiResult['is_duplicate'] ?? false);
        $assignedTo = $aiResult['assigned_to'] ?? 'Unassigned';
        $bugId = DB::table('bugs')->insertGetId([
            'project_id' => $projectId,
            'title' => $data['title'],
            'description' => $data['description'],
            'priority' => $aiResult['priority'] ?? 'Medium',
            'status' => $aiResult['status'] ?? ($isDuplicate ? 'Duplicate' : 'Open'),
            'assigned_to' => $assignedTo,
            'is_duplicate' => $isDuplicate,
            'duplicate_of' => $aiResult['duplicate_of'] ?? null,
            'screenshot_path' => $screenshotPath,
            'source_file_path' => $sourceFilePath,
            'source_file_name' => $request->file('source_file')?->getClientOriginalName(),
            'detected_feature' => $aiResult['feature'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (!$isDuplicate && $assignedTo !== 'Unassigned') {
            DB::table('developers')->where('project_id', $projectId)->where('name', $assignedTo)->increment('active_tasks');
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
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->firstOrFail();

        if (in_array($data['status'], ['Resolved', 'Closed'], true)
            && !in_array($bug->status, ['Resolved', 'Closed'], true)
            && $bug->assigned_to) {
            DB::table('developers')->where('project_id', $projectId)->where('name', $bug->assigned_to)
                ->where('active_tasks', '>', 0)->decrement('active_tasks');
        }

        DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->update([
            'status' => $data['status'],
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Bug status updated.');
    }

    private function currentProjectId(): int
    {
        $projectId = session('project_id');
        if ($projectId && DB::table('projects')->where('id', $projectId)->where('manager_id', Auth::id())->exists()) {
            return (int) $projectId;
        }

        $project = Auth::user()->role === 'manager'
            ? DB::table('projects')->where('manager_id', Auth::id())->oldest('id')->first()
            : DB::table('projects')
                ->join('project_members', 'projects.id', '=', 'project_members.project_id')
                ->where('project_members.user_id', Auth::id())
                ->oldest('projects.id')
                ->select('projects.*')
                ->first();

        abort_unless($project, 403, 'You are not assigned to a project.');
        session(['project_id' => $project->id, 'project_name' => $project->name, 'project_stack' => $project->stack]);

        return (int) $project->id;
    }
}
