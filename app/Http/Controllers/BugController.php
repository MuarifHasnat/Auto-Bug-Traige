<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use App\Notifications\BugUpdateNotification;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BugController extends Controller
{
    public function profilePage(): View
    {
        $projects = $this->accessibleProjects();
        $currentProjectId = (int) (session('project_id') ?: ($projects->first()->id ?? 0));
        $unreadNotificationCount = Auth::user()->unreadNotifications()->count();

        return view('profile', compact('projects', 'currentProjectId', 'unreadNotificationCount'));
    }

    public function profilePhoto(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $path = Auth::user()->profile_photo_path;
        abort_unless($path && Storage::disk('public')->exists($path), 404);

        return response()->file(Storage::disk('public')->path($path), [
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    public function teamMemberPhoto(int $id): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $projectId = $this->currentProjectId();
        abort_unless(in_array($id, $this->projectParticipantIds($projectId), true), 404);
        $path = \App\Models\User::query()->whereKey($id)->value('profile_photo_path');
        abort_unless($path && Storage::disk('public')->exists($path), 404);

        return response()->file(Storage::disk('public')->path($path), [
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    public function sharedProfile(int $id): View
    {
        $accessibleProjectIds = $this->accessibleProjects()->pluck('id')->map(fn ($projectId) => (int) $projectId)->all();
        $isProjectMember = DB::table('project_members')->whereIn('project_id', $accessibleProjectIds)->where('user_id', $id)->exists()
            || DB::table('projects')->whereIn('id', $accessibleProjectIds)->where('manager_id', $id)->exists();
        abort_unless($id === (int) Auth::id() || $isProjectMember, 403);
        $profile = \App\Models\User::query()->select(['id', 'name', 'email', 'phone', 'role', 'profile_photo_path', 'last_seen_at'])->findOrFail($id);
        $profilePhotoUrl = $profile->id === Auth::id() ? route('profile.photo') : route('team-members.photo', $profile->id);
        return view('shared_profile', compact('profile', 'profilePhotoUrl'));
    }

    public function presencePing(): JsonResponse
    {
        Auth::user()->forceFill(['last_seen_at' => now()])->saveQuietly();

        return response()->json(['ok' => true]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) Storage::disk('public')->delete($user->profile_photo_path);
            $data['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }
        unset($data['profile_photo']);
        $user->update($data);
        $request->session()->put('manager_name', $user->name);
        if ($request->hasFile('profile_photo')) $user->refresh();
        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Your current password is incorrect.',
            'password.confirmed' => 'The new password confirmation does not match.',
        ]);

        Auth::user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('password_success', 'Your password has been changed successfully.');
    }

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
            'role' => ['required', Rule::in(['manager', 'developer', 'tester'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'name' => $data['manager_name'],
            'email' => $data['manager_email'],
            'role' => $data['role'],
            'password' => $data['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('manager_name', $user->name);

        if ($user->role === 'manager') {
            return redirect('/create-project');
        }

        return redirect('/dashboard');
    }

    public function pendingAccessPage(): View|RedirectResponse
    {
        abort_unless(in_array(Auth::user()?->role, ['developer', 'tester'], true), 403);

        return redirect('/dashboard');
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
        $credentials['email'] = strtolower(trim($credentials['email']));

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'The email or password is incorrect.'])->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('manager_name', Auth::user()->name);

        $user = Auth::user();
        if ($user->role === 'manager') {
            return redirect($this->accessibleProjects()->isEmpty() ? '/create-project' : '/dashboard');
        }

        return redirect('/dashboard');
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
            'github_repo' => ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) {
                if (filled($value) && !$this->isValidGitHubRepositoryUrl(trim($value))) {
                    $fail('Enter a valid GitHub repository link, such as https://github.com/owner/repository.');
                }
            }],
        ]);

        $data['github_repo'] = filled($data['github_repo'] ?? null) ? trim($data['github_repo']) : null;

        $githubRepo = $data['github_repo'];
        $repoMetadata = null;
        if ($githubRepo) {
            try {
                $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
                $process = Process::timeout(25)->run([$python, base_path('agent/file_locator.py'), '--preview', $githubRepo]);
                $repoMetadata = json_decode($process->output(), true);
                if (!$process->successful() || !is_array($repoMetadata) || !($repoMetadata['success'] ?? false)) {
                    return back()->withInput()->withErrors([
                        'github_repo' => is_array($repoMetadata) ? ($repoMetadata['error'] ?? 'Could not read this GitHub repository. Check its access and try again.') : 'Could not complete the GitHub repository scan. Try again.',
                    ]);
                }
            } catch (\Throwable) {
                return back()->withInput()->withErrors([
                    'github_repo' => 'The repository scan took too long or GitHub could not be reached. Please try again.',
                ]);
            }
        }

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

        $syncNotice = $repoMetadata
            ? " • Scanned {$repoMetadata['total_files']} files ({$repoMetadata['primary_language']}) from branch '{$repoMetadata['branch']}'"
            : '';

        return redirect('/add-developers')->with('success', "Project created successfully!{$syncNotice}");
    }

    public function previewGitHubRepo(Request $request): JsonResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);

        $repoUrl = trim((string) $request->input('github_repo', ''));
        if (!$this->isValidGitHubRepositoryUrl($repoUrl)) {
            return response()->json([
                'success' => false,
                'error' => 'Enter a valid repository link, such as https://github.com/owner/repository.',
            ], 422);
        }

        try {
            $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
            $process = Process::timeout(25)->run([$python, base_path('agent/file_locator.py'), '--preview', $repoUrl]);
            $metadata = json_decode($process->output(), true);

            if (!$process->successful() || !is_array($metadata) || !($metadata['success'] ?? false)) {
                return response()->json([
                    'success' => false,
                    'error' => is_array($metadata) ? ($metadata['error'] ?? 'Could not read this repository. Check its access and try again.') : 'Could not complete the repository scan. Try again.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'repo' => $metadata['repo'] ?? '',
                'branch' => $metadata['branch'] ?? 'default branch',
                'total_files' => (int) ($metadata['total_files'] ?? 0),
                'primary_language' => $metadata['primary_language'] ?? null,
                'cached' => (bool) ($metadata['cached'] ?? false),
            ]);
        } catch (\Throwable) {
            return response()->json([
                'success' => false,
                'error' => 'The repository scan took too long or GitHub could not be reached. Please try again.',
            ], 503);
        }
    }

    private function isValidGitHubRepositoryUrl(string $url): bool
    {
        $parts = parse_url($url);
        if (!is_array($parts)
            || strtolower($parts['scheme'] ?? '') !== 'https'
            || strtolower($parts['host'] ?? '') !== 'github.com'
            || isset($parts['user'])
            || isset($parts['pass'])
            || isset($parts['port'])
            || isset($parts['query'])
            || isset($parts['fragment'])) {
            return false;
        }

        return (bool) preg_match('~^/[A-Za-z0-9](?:[A-Za-z0-9-]{0,37})/[A-Za-z0-9_.-]{1,100}(?:\.git)?/?$~', $parts['path'] ?? '');
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
            ->get(['id', 'name', 'email', 'role', 'profile_photo_path']);
        $registeredAccounts = \App\Models\User::query()
            ->whereIn('role', ['tester', 'developer'])
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);
        $assignedAccountIds = $teamMembers->pluck('id')->all();

        return view('add_developers', [
            'developers' => $developers,
            'teamMembers' => $teamMembers,
            'registeredAccounts' => $registeredAccounts,
            'assignedAccountIds' => $assignedAccountIds,
            'projects' => $projects,
            'currentProjectId' => $projectId,
        ]);
    }

    public function teamPage(): View
    {
        abort_unless(in_array(Auth::user()?->role, ['developer', 'tester'], true), 403);
        $projectId = $this->currentProjectId();
        $projects = $this->accessibleProjects();
        $currentProject = $projects->firstWhere('id', $projectId);
        $members = \App\Models\User::query()->whereIn('id', $this->projectParticipantIds($projectId))
            ->orderByRaw("CASE role WHEN 'manager' THEN 0 WHEN 'developer' THEN 1 ELSE 2 END")
            ->orderBy('name')->get(['id', 'name', 'email', 'phone', 'role', 'profile_photo_path', 'last_seen_at']);
        $developerSkills = DB::table('developers')->where('project_id', $projectId)->pluck('skills', 'name');
        $unreadNotificationCount = Auth::user()->unreadNotifications()->count();

        return view('team_directory', compact('projects', 'currentProject', 'members', 'developerSkills', 'unreadNotificationCount') + [
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

        $previousProjectId = (int) $request->session()->get(
            'last_opened_project_id',
            $request->session()->get('project_id', 0),
        );
        $recentProjectIds = collect($request->session()->get('recent_project_ids', []))
            ->map(fn ($id) => (int) $id)
            ->reject(fn ($id) => $id === (int) $project->id)
            ->prepend((int) $project->id)
            ->when($previousProjectId > 0 && $previousProjectId !== (int) $project->id, fn ($ids) => $ids->push($previousProjectId)->unique())
            ->take(8)
            ->values()
            ->all();
        $request->session()->put([
            'recent_project_ids' => $recentProjectIds,
            'last_opened_project_id' => (int) $project->id,
        ]);

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

        $existingUser = null;
        if ($request->filled('user_id')) {
            $selection = $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'skills' => ['nullable', 'string', 'max:255'],
                'capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
            ]);
            $existingUser = \App\Models\User::findOrFail($selection['user_id']);
            if (!in_array($existingUser->role, ['tester', 'developer'], true)) {
                return back()->withErrors(['user_id' => 'Only tester and developer accounts can be added to a project.']);
            }
        } else {
            $existingUser = \App\Models\User::where('email', $request->input('email'))->first();
        }
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

    public function removeTeamMember(int $id): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'manager', 403);
        $projectId = $this->currentProjectId();
        $member = DB::table('project_members')
            ->join('users', 'users.id', '=', 'project_members.user_id')
            ->where('project_members.project_id', $projectId)
            ->where('users.id', $id)
            ->whereIn('users.role', ['developer', 'tester'])
            ->first(['users.id', 'users.name', 'users.role']);

        if (!$member) {
            return back()->withErrors(['team_member' => 'Developer or tester was not found in this project.']);
        }

        $activeStatuses = $this->ensureProjectWorkflow($projectId)
            ->where('is_done', false)->where('name', '!=', 'Duplicate')->pluck('name')->all();
        if ($member->role === 'developer' && DB::table('bugs')->where('project_id', $projectId)
            ->where('assigned_to', $member->name)->whereIn('status', $activeStatuses)->whereRaw('is_duplicate = false')->exists()) {
            return back()->withErrors(['team_member' => 'Move this developer’s active bugs to someone else before removing them.']);
        }

        DB::transaction(function () use ($projectId, $id, $member) {
            DB::table('project_members')->where('project_id', $projectId)->where('user_id', $id)->delete();
            if ($member->role === 'developer') {
                DB::table('developers')->where('project_id', $projectId)->where('name', $member->name)->delete();
            }
        });

        return back()->with('success', ucfirst($member->role).' removed from this project.');
    }

    public function workspacePage(Request $request): View
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'developer', 'tester'], true), 403);

        $canMoveKanbanIssues = Auth::user()->role === 'developer';

        $projectId = $this->currentProjectId();
        $projects = $this->accessibleProjects();
        if (Auth::user()->role === 'manager' && $request->filled('project_id')) {
            $projectSelection = $request->validate(['project_id' => ['required', 'integer']]);
            $selectedProject = $projects->firstWhere('id', (int) $projectSelection['project_id']);
            abort_unless($selectedProject, 403, 'You are not assigned to that project.');
            $projectId = (int) $selectedProject->id;
            $this->setCurrentProject($request, $selectedProject);
        }
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
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
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
        if (!empty($filters['date_from'])) $query->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('created_at', '<=', $filters['date_to']);

        $bugs = $query->get();
        $reportedByMeCount = DB::table('bugs')->where('project_id', $projectId)->where('reporter_id', Auth::id())->count();
        $assignedToMeCount = DB::table('bugs')->where('project_id', $projectId)->where('assigned_to', Auth::user()->name)->count();
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

        $currentProject = $projects->firstWhere('id', $projectId);
        $projectGithubRepo = $currentProject?->github_repo ?: session('github_repo', '');

        return view('workspace', compact('developers', 'bugs', 'stats', 'assignedBugs', 'projects', 'bugActivity', 'bugComments', 'clarifications', 'watchingBugIds', 'projectMembers', 'bugMetrics', 'filters', 'availableAssignees', 'availableLabels', 'savedFilters', 'workflowStatuses', 'transitionsByStatus', 'reportedByMeCount', 'assignedToMeCount', 'canMoveKanbanIssues') + [
            'currentProjectId' => $projectId,
            'currentProject' => $currentProject,
            'projectGithubRepo' => $projectGithubRepo,
        ]);
    }

    public function myReportedBugsPage(Request $request): View
    {
        abort_unless(Auth::user()?->role === 'tester', 403);

        $projectId = $this->currentProjectId();
        $projects = $this->accessibleProjects();
        $project = $projects->firstWhere('id', $projectId);
        $workflowStatuses = $this->ensureProjectWorkflow($projectId);
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:200'],
            'status' => ['nullable', Rule::in($workflowStatuses->pluck('name')->all())],
            'priority' => ['nullable', Rule::in(['High', 'Medium', 'Low'])],
            'date' => ['nullable', 'date'],
            'selected' => ['nullable', 'integer'],
        ]);

        $reportsQuery = DB::table('bugs')->where('project_id', $projectId)->where('reporter_id', Auth::id());
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $reportsQuery->where(fn ($builder) => $builder->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"));
        }
        if (!empty($filters['status'])) $reportsQuery->where('status', $filters['status']);
        if (!empty($filters['priority'])) $reportsQuery->where('priority', $filters['priority']);
        if (!empty($filters['date'])) $reportsQuery->whereDate('created_at', $filters['date']);

        $reports = (clone $reportsQuery)->orderByDesc('created_at')->orderByDesc('id')->limit(50)
            ->get(['id', 'title', 'description', 'status', 'priority', 'assigned_to', 'source_file_path', 'source_file_name', 'detected_feature', 'triage_result', 'created_at']);
        $selectedReport = $reports->firstWhere('id', (int) ($filters['selected'] ?? 0)) ?: $reports->first();
        $selectedTriage = json_decode($selectedReport?->triage_result ?: '', true) ?: [];
        $selectedFiles = data_get($selectedTriage, 'code_help.suspected_files', data_get($selectedTriage, 'suspected_files', []));
        $selectedFile = is_array($selectedFiles) && isset($selectedFiles[0])
            ? (is_array($selectedFiles[0]) ? ($selectedFiles[0]['path'] ?? $selectedFiles[0]['file'] ?? null) : $selectedFiles[0])
            : null;
        $selectedDiagnosis = data_get($selectedTriage, 'code_help.llm_analysis.root_cause')
            ?: data_get($selectedTriage, 'code_help.fix_suggestions.0.detail')
            ?: data_get($selectedTriage, 'code_help.diagnostics.0.message');
        $selectedComments = $selectedReport
            ? DB::table('bug_comments')->where('project_id', $projectId)->where('bug_id', $selectedReport->id)->orderByDesc('created_at')->limit(4)->get()
            : collect();

        return view('my_reported_bugs', compact(
            'projects', 'project', 'projectId', 'workflowStatuses', 'filters', 'reports',
            'selectedReport', 'selectedTriage', 'selectedFile', 'selectedDiagnosis', 'selectedComments'
        ) + ['currentProjectId' => $projectId]);
    }

    public function showBug(int $id): View
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'developer', 'tester'], true), 403);

        $projectId = $this->currentProjectId();
        $this->ensureProjectWorkflow($projectId);
        $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $id)->firstOrFail();
        $bug->triage = json_decode($bug->triage_result ?: '', true) ?: [];
        $projectRepo = DB::table('projects')->where('id', $projectId)->value('github_repo') ?: session('github_repo');

        // Dynamically resolve culprit files if not yet resolved
        $existingFiles = $bug->triage['code_help']['suspected_files'] ?? ($bug->triage['suspected_files'] ?? null);
        if ($existingFiles === null && !empty($projectRepo)) {
            $resolvedFiles = $this->resolveSuspectedFilesForBug($bug, $projectRepo);
            $bug->triage['code_help']['suspected_files'] = $resolvedFiles;
            $bug->triage['suspected_files'] = $resolvedFiles;
            DB::table('bugs')->where('id', $bug->id)->update([
                'triage_result' => json_encode($bug->triage, JSON_THROW_ON_ERROR),
                'updated_at' => now(),
            ]);
        }

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
            'projectRepo' => $projectRepo,
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
        $projectRepo = DB::table('projects')->where('id', $projectId)->value('github_repo') ?: session('github_repo');

        // Dynamically resolve culprit files if not yet resolved
        $existingFiles = $bug->triage['code_help']['suspected_files'] ?? ($bug->triage['suspected_files'] ?? null);
        if ($existingFiles === null && !empty($projectRepo)) {
            $resolvedFiles = $this->resolveSuspectedFilesForBug($bug, $projectRepo);
            $bug->triage['code_help']['suspected_files'] = $resolvedFiles;
            $bug->triage['suspected_files'] = $resolvedFiles;
            DB::table('bugs')->where('id', $bug->id)->update([
                'triage_result' => json_encode($bug->triage, JSON_THROW_ON_ERROR),
                'updated_at' => now(),
            ]);
        }

        $comments = DB::table('bug_comments')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();
        $activities = DB::table('bug_activities')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();
        $clarifications = DB::table('bug_clarification_requests')->where('project_id', $projectId)->where('bug_id', $id)->orderBy('created_at')->get();

        return view('bug_report_print', compact('bug', 'comments', 'activities', 'clarifications'));
    }

    protected function resolveSuspectedFilesForBug(object $bug, ?string $githubRepo): array
    {
        if (empty($githubRepo)) {
            return [];
        }
        $payloadPath = storage_path('app/resolve_' . uniqid('', true) . '.json');
        try {
            $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
            file_put_contents($payloadPath, json_encode([
                'title' => $bug->title,
                'description' => $bug->description,
                'feature' => $bug->detected_feature ?? 'General',
                'github_repo' => $githubRepo,
                'code_snippet' => '',
                'selected_language' => 'auto',
                'project_stack' => DB::table('projects')->where('id', $bug->project_id)->value('stack') ?: '',
                'source_file_name' => $bug->source_file_name ?? '',
                'groq_api_key' => config('services.groq.key', env('GROQ_API_KEY')),
                'groq_model' => config('services.groq.model', env('GROQ_MODEL', 'openai/gpt-oss-120b')),
            ], JSON_THROW_ON_ERROR));

            $result = Process::timeout(3)->run([$python, base_path('agent/file_locator.py'), '--locate', $payloadPath]);
            if ($result->successful()) {
                $decoded = json_decode(trim($result->output()), true);
                if (is_array($decoded) && !empty($decoded)) {
                    return $decoded;
                }
            }
        } catch (\Throwable) {
            // Silently ignore dynamic locator failures
        } finally {
            @unlink($payloadPath);
        }
        return [];
    }

    public function dashboardPage(Request $request): View
    {
        if (in_array(Auth::user()?->role, ['developer', 'tester'], true) && $this->accessibleProjects()->isEmpty()) {
            return view('unassigned_dashboard', [
                'projects' => collect(),
                'currentProjectId' => null,
                'unreadNotificationCount' => Auth::user()->unreadNotifications()->count(),
                'project' => null,
                'commandCenter' => [
                    'stats' => ['total' => 0, 'active' => 0, 'unresolved' => 0, 'overdue' => 0, 'resolved' => 0, 'high' => 0],
                    'priorityCounts' => collect(),
                    'trend' => collect(range(0, 6))->map(fn () => ['label' => '', 'created' => 0, 'resolved' => 0]),
                    'workloads' => collect(),
                    'recent' => collect(),
                ],
            ]);
        }

        if (Auth::user()?->role === 'tester') {
            $projectId = $this->currentProjectId();
            $projects = $this->accessibleProjects();
            $project = DB::table('projects')->where('id', $projectId)->first();
            $workflow = $this->ensureProjectWorkflow($projectId);
            $doneStatuses = $workflow->where('is_done', true)->pluck('name')->all();
            $availableStatuses = $workflow->pluck('name')->all();
            $searchFilters = $request->validate([
                'q' => ['nullable', 'string', 'max:100'],
                'status' => ['nullable', 'string', 'max:80'],
            ]);
            $query = trim($searchFilters['q'] ?? '');
            $statusFilter = $searchFilters['status'] ?? '';
            $reportsQuery = DB::table('bugs')->where('project_id', $projectId)->where('reporter_id', Auth::id());
            $totalReports = (clone $reportsQuery)->count();
            $openReports = (clone $reportsQuery)->whereNotIn('status', $doneStatuses)->count();
            $resolvedReports = (clone $reportsQuery)->whereIn('status', $doneStatuses)->count();
            $highPriorityReports = (clone $reportsQuery)->where('priority', 'High')->count();
            $assignedReports = (clone $reportsQuery)->whereNotNull('assigned_to')->where('assigned_to', '!=', 'Unassigned')->count();
            $retestStatuses = collect($availableStatuses)->filter(fn ($status) => preg_match('/review|retest|qa|verify|testing/i', $status))->values()->all();
            $retestReports = (clone $reportsQuery)->when($retestStatuses, fn ($builder) => $builder->whereIn('status', $retestStatuses), fn ($builder) => $builder->whereRaw('1 = 0'))
                ->orderByDesc('updated_at')->limit(5)->get(['id', 'title', 'status']);
            if ($query !== '') {
                $reportsQuery->where(function ($search) use ($query) {
                    $search->where('title', 'like', '%'.$query.'%')->orWhere('description', 'like', '%'.$query.'%');
                });
            }
            if ($statusFilter !== '' && in_array($statusFilter, $availableStatuses, true)) {
                $reportsQuery->where('status', $statusFilter);
            }
            $reports = $reportsQuery->orderByDesc('updated_at')->orderByDesc('id')->limit(12)
                ->get(['id', 'title', 'description', 'status', 'priority', 'assigned_to', 'triage_state', 'source_file_path', 'source_file_name', 'detected_feature', 'triage_result', 'updated_at']);
            $latestTriageBug = (clone $reportsQuery)->whereNotNull('triage_result')->orderByDesc('updated_at')->first(['id', 'triage_result', 'detected_feature']);
            $latestTriage = json_decode($latestTriageBug?->triage_result ?: '', true) ?: [];
            $latestTriageFiles = data_get($latestTriage, 'code_help.suspected_files', data_get($latestTriage, 'suspected_files', []));
            $latestTriageFile = is_array($latestTriageFiles) && isset($latestTriageFiles[0])
                ? (is_array($latestTriageFiles[0]) ? ($latestTriageFiles[0]['path'] ?? $latestTriageFiles[0]['file'] ?? null) : $latestTriageFiles[0])
                : null;
            $unreadNotificationCount = Auth::user()->unreadNotifications()->count();
            $commandCenter = $this->projectCommandCenterData($projectId);

            return view('tester_dashboard', compact('projects', 'project', 'projectId', 'totalReports', 'openReports', 'resolvedReports', 'highPriorityReports', 'assignedReports', 'reports', 'query', 'statusFilter', 'availableStatuses', 'retestReports', 'latestTriageBug', 'latestTriage', 'latestTriageFile', 'unreadNotificationCount', 'commandCenter') + [
                'currentProjectId' => $projectId,
            ]);
        }

        if (Auth::user()?->role === 'developer') {
            $projectId = $this->currentProjectId();
            $projects = $this->accessibleProjects();
            $project = $projects->firstWhere('id', $projectId);
            $workflow = $this->ensureProjectWorkflow($projectId);
            $doneStatuses = $workflow->where('is_done', true)->pluck('name')->all();
            $activeStatuses = $workflow->where('is_done', false)->where('name', '!=', 'Duplicate')->pluck('name')->all();
            $mine = DB::table('bugs')->where('project_id', $projectId)->where('assigned_to', Auth::user()->name);
            $stats = [
                'total' => (clone $mine)->count(),
                'open' => (clone $mine)->whereIn('status', $activeStatuses)->whereRaw('is_duplicate = false')->count(),
                'review' => (clone $mine)->where(function ($query) {
                    $query->where('status', 'ilike', '%review%')->orWhere('status', 'ilike', '%qa%')->orWhere('status', 'ilike', '%test%');
                })->count(),
                'resolved' => (clone $mine)->whereIn('status', $doneStatuses)->count(),
            ];
            $assignedIssues = (clone $mine)->orderByRaw("CASE priority WHEN 'High' THEN 0 WHEN 'Medium' THEN 1 ELSE 2 END")
                ->orderByDesc('updated_at')->limit(12)
                ->get(['id', 'title', 'priority', 'status', 'detected_feature', 'updated_at']);
            $latestIssue = $assignedIssues->first();
            $unreadNotificationCount = Auth::user()->unreadNotifications()->count();
            $commandCenter = $this->projectCommandCenterData($projectId);

            return view('developer_dashboard', compact('projects', 'project', 'projectId', 'stats', 'assignedIssues', 'latestIssue', 'unreadNotificationCount', 'commandCenter') + [
                'currentProjectId' => $projectId,
            ]);
        }

        abort_unless(Auth::user()?->role === 'manager', 403);

        $currentProjectId = $this->currentProjectId();
        $projects = $this->accessibleProjects();
        $filters = $request->validate([
            'project_id' => ['nullable', 'integer'],
            'priority' => ['nullable', Rule::in(['High', 'Medium', 'Low'])],
            'status' => ['nullable', 'string', 'max:80'],
            'assignee' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'account_search' => ['nullable', 'string', 'max:100'],
            'account_role' => ['nullable', 'in:all,manager,developer,tester'],
        ]);
        $projectId = $currentProjectId;
        if (!empty($filters['project_id'])) {
            $selectedProject = $projects->firstWhere('id', (int) $filters['project_id']);
            abort_unless($selectedProject, 403, 'You are not assigned to that project.');
            $projectId = (int) $selectedProject->id;
            $this->setCurrentProject($request, $selectedProject);
        }
        $workflowStatuses = $this->ensureProjectWorkflow($projectId);
        $doneStatuses = $workflowStatuses->where('is_done', true)->pluck('name')->all();
        $activeStatuses = $workflowStatuses->where('is_done', false)->where('name', '!=', 'Duplicate')->pluck('name')->all();
        $stats = [
            'total' => DB::table('bugs')->where('project_id', $projectId)->count(),
            'open' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $activeStatuses)->count(),
            'unresolved' => DB::table('bugs')->where('project_id', $projectId)->whereNotIn('status', $doneStatuses)->count(),
            'resolved' => DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $doneStatuses)->count(),
            'high' => DB::table('bugs')->where('project_id', $projectId)->where('priority', 'High')->count(),
            // This prototype has no due-date field; flag unresolved reports untouched for 7+ days.
            'overdue' => DB::table('bugs')->where('project_id', $projectId)->whereNotIn('status', $doneStatuses)->where('created_at', '<', now()->subDays(7))->count(),
        ];
        $bugQuery = DB::table('bugs')->where('project_id', $projectId);
        if (!empty($filters['priority'])) $bugQuery->where('priority', $filters['priority']);
        if (!empty($filters['status'])) {
            abort_unless($workflowStatuses->contains('name', $filters['status']), 422, 'Choose a status from this project workflow.');
            $bugQuery->where('status', $filters['status']);
        }
        if (!empty($filters['assignee'])) $bugQuery->where('assigned_to', $filters['assignee'] === 'Unassigned' ? null : $filters['assignee']);
        if (!empty($filters['date_from'])) $bugQuery->whereDate('created_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $bugQuery->whereDate('created_at', '<=', $filters['date_to']);
        $recentBugs = (clone $bugQuery)->orderByRaw("CASE priority WHEN 'High' THEN 0 WHEN 'Medium' THEN 1 ELSE 2 END")
            ->orderByDesc('created_at')->orderByDesc('id')->limit(8)->get();
        $developers = DB::table('developers')->where('project_id', $projectId)->orderByDesc('active_tasks')->limit(6)->get();
        $liveWorkloads = DB::table('bugs')->where('project_id', $projectId)->whereIn('status', $activeStatuses)
            ->whereRaw('is_duplicate = false')->whereNotNull('assigned_to')->select('assigned_to', DB::raw('COUNT(*) as task_count'))
            ->groupBy('assigned_to')->pluck('task_count', 'assigned_to')->all();
        $developers = $developers->sortByDesc(fn ($developer) => $liveWorkloads[$developer->name] ?? 0)->values();
        $availableStatuses = $workflowStatuses->pluck('name');
        $availableAssignees = DB::table('bugs')->where('project_id', $projectId)->whereNotNull('assigned_to')->distinct()->orderBy('assigned_to')->pluck('assigned_to');
        $trendStart = now()->subDays(6)->startOfDay();
        $createdTrend = DB::table('bugs')->where('project_id', $projectId)->where('created_at', '>=', $trendStart)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupByRaw('DATE(created_at)')->pluck('total', 'day');
        $resolvedTrend = DB::table('bug_activities')->where('project_id', $projectId)->where('event_type', 'status_changed')
            ->whereIn('to_value', $doneStatuses)->where('created_at', '>=', $trendStart)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupByRaw('DATE(created_at)')->pluck('total', 'day');
        $bugTrend = collect(range(0, 6))->map(function ($offset) use ($trendStart, $createdTrend, $resolvedTrend) {
            $day = $trendStart->copy()->addDays($offset);
            $key = $day->toDateString();
            return ['label' => $day->format('D'), 'date' => $day->format('M j'), 'created' => (int) ($createdTrend[$key] ?? 0), 'resolved' => (int) ($resolvedTrend[$key] ?? 0)];
        });
        $trendMax = max(1, (int) $bugTrend->max(fn ($day) => max($day['created'], $day['resolved'])));
        $trendCoordinates = $bugTrend->values()->map(function ($day, $index) use ($trendMax) {
            $x = 28 + ($index * 107);
            return ['x' => $x, 'created_y' => 164 - (int) round(($day['created'] / $trendMax) * 130), 'resolved_y' => 164 - (int) round(($day['resolved'] / $trendMax) * 130)];
        });
        $trendCreatedPath = $trendCoordinates->map(fn ($point, $index) => ($index === 0 ? 'M' : 'L').$point['x'].' '.$point['created_y'])->implode(' ');
        $trendResolvedPath = $trendCoordinates->map(fn ($point, $index) => ($index === 0 ? 'M' : 'L').$point['x'].' '.$point['resolved_y'])->implode(' ');
        $priorityCounts = DB::table('bugs')->where('project_id', $projectId)->select('priority', DB::raw('COUNT(*) as total'))->groupBy('priority')->pluck('total', 'priority');
        $projectActivity = DB::table('bug_activities')->join('bugs', 'bugs.id', '=', 'bug_activities.bug_id')
            ->where('bug_activities.project_id', $projectId)->orderByDesc('bug_activities.created_at')->orderByDesc('bug_activities.id')
            ->limit(12)->get(['bug_activities.id', 'bug_activities.bug_id', 'bugs.title', 'bug_activities.actor_name', 'bug_activities.event_type', 'bug_activities.from_value', 'bug_activities.to_value', 'bug_activities.created_at']);
        $triageReviewQueue = DB::table('bug_triage_metrics')->join('bugs', function ($join) {
            $join->on('bugs.id', '=', 'bug_triage_metrics.bug_id')->on('bugs.project_id', '=', 'bug_triage_metrics.project_id');
        })->where('bug_triage_metrics.project_id', $projectId)
            ->where(function ($query) {
                $query->where(function ($duplicate) {
                    $duplicate->whereNull('bug_triage_metrics.actual_duplicate');
                })->orWhere(function ($assignment) {
                    $assignment->whereNotNull('bug_triage_metrics.recommended_assignee')->whereNull('bug_triage_metrics.assignment_feedback');
                });
            })->orderByDesc('bug_triage_metrics.created_at')->limit(8)
            ->get(['bugs.id', 'bugs.title', 'bugs.status', 'bugs.assigned_to', 'bugs.recommended_assignee', 'bug_triage_metrics.predicted_duplicate', 'bug_triage_metrics.duplicate_similarity_score', 'bug_triage_metrics.assignment_feedback']);
        $metricRows = DB::table('bug_triage_metrics')->where('project_id', $projectId)->get();
        $reviewedAssignments = $metricRows->whereIn('assignment_feedback', ['accepted', 'rejected']);
        $reviewedDuplicates = $metricRows->whereNotNull('actual_duplicate');
        $truePositives = $reviewedDuplicates->filter(fn ($row) => (bool) $row->predicted_duplicate && (bool) $row->actual_duplicate)->count();
        $falsePositives = $reviewedDuplicates->filter(fn ($row) => (bool) $row->predicted_duplicate && !(bool) $row->actual_duplicate)->count();
        $falseNegatives = $reviewedDuplicates->filter(fn ($row) => !(bool) $row->predicted_duplicate && (bool) $row->actual_duplicate)->count();
        // Legacy/manual records use 0 to mean "not measured".
        $measuredRows = $metricRows->filter(fn ($row) => (int) $row->triage_duration_ms > 0);
        $latencies = $measuredRows->pluck('triage_duration_ms')->sort()->values();
        $p95Index = $latencies->isEmpty() ? null : (int) ceil($latencies->count() * 0.95) - 1;
        $triageEvaluation = [
            'sample_size' => $measuredRows->count(),
            'average_latency_ms' => $measuredRows->count() ? (int) round($measuredRows->avg('triage_duration_ms')) : null,
            'p95_latency_ms' => $p95Index !== null ? (int) $latencies[$p95Index] : null,
            'under_five_seconds_percent' => $measuredRows->count() ? (int) round($measuredRows->where('triage_duration_ms', '<', 5000)->count() / $measuredRows->count() * 100) : null,
            'assignment_review_count' => $reviewedAssignments->count(),
            'assignment_acceptance_percent' => $reviewedAssignments->count() ? (int) round($reviewedAssignments->where('assignment_feedback', 'accepted')->count() / $reviewedAssignments->count() * 100) : null,
            'duplicate_review_count' => $reviewedDuplicates->count(),
            'duplicate_precision_percent' => ($truePositives + $falsePositives) ? (int) round($truePositives / ($truePositives + $falsePositives) * 100) : null,
            'duplicate_recall_percent' => ($truePositives + $falseNegatives) ? (int) round($truePositives / ($truePositives + $falseNegatives) * 100) : null,
            'duplicate_confirmed_count' => $truePositives,
        ];
        $activeStatusNames = DB::table('project_workflow_statuses')->where('project_id', $projectId)->whereRaw('is_done = false')->where('name', '!=', 'Duplicate')->pluck('name')->all();
        $capacityOverflowCount = DB::table('developers')->where('project_id', $projectId)->get(['name', 'capacity'])
            ->filter(function ($developer) use ($projectId, $activeStatusNames) {
                $activeCount = DB::table('bugs')->where('project_id', $projectId)->where('assigned_to', $developer->name)
                    ->whereIn('status', $activeStatusNames)->whereRaw('is_duplicate = false')->count();
                return $activeCount > (int) $developer->capacity;
            })->count();
        $triageEvaluation['capacity_overflow_count'] = $capacityOverflowCount;

        $accountSearch = trim($filters['account_search'] ?? '');
        $accountRole = $filters['account_role'] ?? 'all';
        $participantIds = $this->projectParticipantIds($projectId);
        $accountsQuery = DB::table('users')
            ->whereIn('id', $participantIds)
            ->select('id', 'name', 'email', 'role', 'profile_photo_path')
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

        return view('dashboard', compact('stats', 'recentBugs', 'developers', 'projects', 'accounts', 'accountSearch', 'accountRole', 'totalAccounts', 'triageEvaluation', 'devWorkloads', 'filters', 'availableStatuses', 'availableAssignees', 'projectActivity', 'triageReviewQueue', 'liveWorkloads', 'bugTrend', 'trendMax', 'trendCoordinates', 'trendCreatedPath', 'trendResolvedPath', 'priorityCounts') + [
            'currentProjectId' => $projectId,
        ]);
    }

    private function projectCommandCenterData(int $projectId): array
    {
        $statuses = $this->ensureProjectWorkflow($projectId);
        $done = $statuses->where('is_done', true)->pluck('name')->all();
        $active = $statuses->where('is_done', false)->where('name', '!=', 'Duplicate')->pluck('name')->all();
        $bugs = DB::table('bugs')->where('project_id', $projectId);
        $total = (clone $bugs)->count();
        $stats = [
            'total' => $total,
            'active' => (clone $bugs)->whereIn('status', $active)->whereRaw('is_duplicate = false')->count(),
            'unresolved' => (clone $bugs)->whereNotIn('status', $done)->count(),
            'overdue' => (clone $bugs)->whereNotIn('status', $done)->where('created_at', '<', now()->subDays(7))->count(),
            'resolved' => (clone $bugs)->whereIn('status', $done)->count(),
            'high' => (clone $bugs)->where('priority', 'High')->count(),
        ];
        $priorityCounts = (clone $bugs)->select('priority', DB::raw('COUNT(*) as total'))->groupBy('priority')->pluck('total', 'priority');
        $trendStart = now()->subDays(6)->startOfDay();
        $created = (clone $bugs)->where('created_at', '>=', $trendStart)->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupByRaw('DATE(created_at)')->pluck('total', 'day');
        $resolved = DB::table('bug_activities')->where('project_id', $projectId)->where('event_type', 'status_changed')->whereIn('to_value', $done)->where('created_at', '>=', $trendStart)->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupByRaw('DATE(created_at)')->pluck('total', 'day');
        $trend = collect(range(0, 6))->map(function ($offset) use ($trendStart, $created, $resolved) {
            $day = $trendStart->copy()->addDays($offset);
            return ['label' => $day->format('D'), 'created' => (int) ($created[$day->toDateString()] ?? 0), 'resolved' => (int) ($resolved[$day->toDateString()] ?? 0)];
        });
        $workloads = (clone $bugs)->whereIn('status', $active)->whereRaw('is_duplicate = false')->whereNotNull('assigned_to')->select('assigned_to', DB::raw('COUNT(*) as total'))->groupBy('assigned_to')->orderByDesc('total')->limit(6)->get();
        $recent = (clone $bugs)->orderByDesc('updated_at')->limit(6)->get(['id', 'title', 'status', 'priority', 'assigned_to', 'updated_at']);

        return compact('stats', 'priorityCounts', 'trend', 'workloads', 'recent');
    }

    public function aiAgentPage(Request $request): View
    {
        abort_unless(in_array(Auth::user()?->role, ['manager', 'tester', 'developer'], true), 403);

        $projectId = $this->currentProjectId();
        $this->ensureProjectWorkflow($projectId);
        $projectBugCount = DB::table('bugs')->where('project_id', $projectId)->count();
        $bugsQuery = DB::table('bugs')->where('project_id', $projectId);
        if (Auth::user()->role === 'tester') {
            $bugsQuery->where('reporter_id', Auth::id());
        }
        $bugs = $bugsQuery->orderByDesc('id')->limit(10)->get();
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
        $myReports = Auth::user()->role === 'tester'
            ? DB::table('bugs')->where('project_id', $projectId)->where('reporter_id', Auth::id())
                ->orderByDesc('updated_at')->orderByDesc('id')->limit(8)
                ->get(['id', 'title', 'status', 'priority', 'assigned_to', 'triage_state', 'updated_at'])
            : collect();
        $mode = Auth::user()->role === 'developer' ? 'developer' : 'tester';

        $projects = $this->accessibleProjects();
        $currentProject = DB::table('projects')->where('id', $projectId)->first();
        $projectGithubRepo = $currentProject?->github_repo ?: session('github_repo', '');

        $pastBugs = DB::table('bugs')
            ->where('project_id', $projectId)
            ->whereRaw('is_duplicate = false')
            ->select('id', 'title', 'description')
            ->limit(100)
            ->get();

        return view('ai_agent', compact('bugs', 'assignedBugs', 'myReports', 'mode', 'projects', 'projectBugCount', 'pastBugs') + [
            'currentProjectId' => $projectId,
            'currentProject' => $currentProject,
            'projectGithubRepo' => $projectGithubRepo,
        ]);
    }

    public function store(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        abort_unless(Auth::user()?->role === 'tester', 403);
        $submissionStartedAt = hrtime(true);

        // Screenshot OCR, repository lookup and LLM analysis can exceed PHP's
        // default 30-second request limit. Keep the request alive for the
        // bounded agent run below so an uploaded image is not abandoned before
        // its bug report is persisted.
        set_time_limit(120);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:5000'],
            'expected_result' => ['nullable', 'string', 'max:2000'],
            'reproduction_steps' => ['nullable', 'array', 'max:12'],
            'reproduction_steps.*' => ['nullable', 'string', 'max:500'],
            'issue_type' => ['required', Rule::in(['Bug', 'Task', 'Story', 'Improvement'])],
            'labels' => ['nullable', 'string', 'max:500'],
            'language' => ['nullable', 'string', 'in:auto,PHP,C++,C,Python,JavaScript,TypeScript,Java,Kotlin,SQL'],
            'code_snippet' => ['nullable', 'string', 'max:20000'],
            'screenshot' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,jfif', 'max:10240'],
            'source_file' => ['nullable', 'file', 'mimes:txt,php,blade.php,cpp,c,h,hpp,py,js,ts,java,kt,kts,sql,json,xml,css,html', 'max:5120'],
        ]);

        $cleanTitle = trim($data['title']);
        $rawDesc = trim($data['description']);
        $hasAttachment = $request->hasFile('screenshot') || $request->hasFile('source_file') || !empty($data['code_snippet']);
        if ($this->isGibberishOrInvalidReport($cleanTitle, $rawDesc, $hasAttachment)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a clear and meaningful bug description. Random or invalid text (e.g. "' . e($cleanTitle) . '") cannot be triaged.',
                ], 422);
            }
            return back()->withInput()->withErrors(['title' => 'Please provide a clear and meaningful bug description. Random or invalid text cannot be triaged.']);
        }

        $reportSections = [];
        $reproductionSteps = array_values(array_filter(array_map('trim', $data['reproduction_steps'] ?? [])));
        if ($reproductionSteps) {
            $reportSections[] = "Steps to reproduce:\n".collect($reproductionSteps)->values()->map(fn ($step, $index) => ($index + 1).'. '.$step)->implode("\n");
        }
        if (!empty($data['expected_result'])) $reportSections[] = "Expected behavior:\n".trim($data['expected_result']);
        $reportSections[] = "Observed behavior:\n".trim($data['description']);
        $data['description'] = implode("\n\n", $reportSections);

        $sourceFilePath = $request->file('source_file')?->store('bug-reports/source', 'local');
        $screenshotPath = $request->file('screenshot')?->store('bug-reports/screenshots', 'local');
        $screenshotAbsolutePath = $screenshotPath ? Storage::disk('local')->path($screenshotPath) : null;
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

        try {
            $formRepo = $request->input('github_repo');
            $projectRepo = DB::table('projects')->where('id', $projectId)->value('github_repo');
            $effectiveRepo = !empty($formRepo) ? trim($formRepo) : ($projectRepo ?: session('github_repo'));

            if (!empty($formRepo) && trim($formRepo) !== $projectRepo) {
                DB::table('projects')->where('id', $projectId)->update([
                    'github_repo' => trim($formRepo),
                    'updated_at' => now(),
                ]);
                session(['github_repo' => trim($formRepo)]);
            }

            file_put_contents($payloadPath, json_encode([
                ...$data,
                'code_snippet' => $analysisCode,
                'selected_language' => $data['language'] ?? 'auto',
                'attachment' => $attachment,
                'screenshot_path' => $screenshotAbsolutePath,
                'screenshot_name' => $request->file('screenshot')?->getClientOriginalName(),
                'groq_api_key' => config('services.groq.key', env('GROQ_API_KEY')),
                'groq_model' => config('services.groq.model', env('GROQ_MODEL', 'openai/gpt-oss-120b')),
                'groq_vision_model' => config('services.groq.vision_model', env('GROQ_VISION_MODEL', 'qwen/qwen3.8-27b')),
                'github_token' => config('services.github.token'),
                'past_bugs' => $pastBugs,
                'developers' => $developers,
                'github_repo' => $effectiveRepo,
                'project_stack' => DB::table('projects')->where('id', $projectId)->value('stack') ?: session('project_stack', ''),
                'source_file_name' => $request->file('source_file')?->getClientOriginalName(),
            ], JSON_THROW_ON_ERROR));

            $python = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
            $result = Process::timeout(110)->run([$python, base_path('agent/triage.py'), $payloadPath]);
        } finally {
            @unlink($payloadPath);
        }
        // Measure the complete submit-to-analysis path, including request
        // validation, context preparation, uploads, and the agent call.
        $triageDurationMs = (int) round((hrtime(true) - $submissionStartedAt) / 1_000_000);

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
        $recommendedAssignee = $assignedTo;
        $bugId = DB::transaction(function () use (
            $projectId,
            $data,
            $isDuplicate,
            $priority,
            $status,
            $labels,
            $triageDurationMs,
            $screenshotPath,
            $sourceFilePath,
            $request,
            &$aiResult,
            &$assignedTo,
            $recommendedAssignee,
            $projectWorkflow
        ) {
            if ($assignedTo !== 'Unassigned' && !$isDuplicate) {
                $lockedDevelopers = $this->lockDeveloperWorkloads($projectId, [$assignedTo]);
                $developer = $lockedDevelopers->get($assignedTo);
                if (!$developer || (int) $developer->active_tasks >= (int) $developer->capacity) {
                    $assignedTo = 'Unassigned';
                }
            }
            $aiResult['assigned_to'] = $assignedTo;
            if ($assignedTo === 'Unassigned' && $recommendedAssignee !== 'Unassigned' && !$isDuplicate) {
                $aiResult['assignment_reason'] = 'The recommended developer reached capacity before assignment; this report was left unassigned for manager review.';
            }
            $bugId = DB::table('bugs')->insertGetId([
                'project_id' => $projectId,
                'reporter_id' => Auth::id(),
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $priority,
                'status' => $status,
                'triage_state' => 'complete',
                'issue_type' => $data['issue_type'],
                'labels' => json_encode($labels, JSON_THROW_ON_ERROR),
                'assigned_to' => $assignedTo,
                'recommended_assignee' => $recommendedAssignee,
                'sentiment' => $aiResult['sentiment'] ?? 'neutral',
                'sentiment_score' => $aiResult['sentiment_score'] ?? 0,
                'blocker_signal' => DB::raw(!empty($aiResult['blocker_signal']) ? 'true' : 'false'),
                'is_duplicate' => DB::raw($isDuplicate ? 'true' : 'false'),
                'duplicate_of' => $aiResult['duplicate_of'] ?? null,
                'screenshot_path' => $screenshotPath,
                'source_file_path' => $sourceFilePath,
                'source_file_name' => $request->file('source_file')?->getClientOriginalName(),
                'detected_feature' => $aiResult['feature'] ?? ($aiResult['code_help']['feature'] ?? 'General'),
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
                'recommended_assignee' => $recommendedAssignee,
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

        DB::table('bug_triage_metrics')->where('project_id', $projectId)->where('bug_id', $bugId)
            ->update(['triage_duration_ms' => (int) round((hrtime(true) - $submissionStartedAt) / 1_000_000)]);

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
                'status' => $status,
                'triage_state' => 'complete',
                'triage_duration_ms' => (int) round((hrtime(true) - $submissionStartedAt) / 1_000_000),
                'ai_result' => $aiResult,
                'has_screenshot' => !empty($screenshotPath),
                'screenshot_url' => $screenshotPath ? "/bugs/{$bugId}/screenshot" : null,
                'screenshot_name' => $request->file('screenshot')?->getClientOriginalName(),
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
        abort_unless(Auth::user()?->role === 'developer', 403, 'Managers can view the Kanban board but cannot move issues.');

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

            if (!$wasActive && $willBeActive) {
                $locked = $this->lockDeveloperWorkloads($projectId, [$bug->assigned_to]);
                $developer = $locked->get($bug->assigned_to);
                abort_unless($developer && (int) $developer->active_tasks < (int) $developer->capacity, 422, 'This developer has reached capacity. Increase capacity or reassign another issue first.');
            }

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

    public function kanbanMove(Request $request): \Illuminate\Http\JsonResponse
    {
        abort_unless(Auth::user()?->role === 'developer', 403, 'Managers can view the Kanban board but cannot move issues.');
        $response = $this->override($request);
        abort_unless($response instanceof \Illuminate\Http\JsonResponse, 500);
        return $response;
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

        $assignmentError = null;
        DB::transaction(function () use ($data, $projectId, &$assignmentError) {
            $bug = DB::table('bugs')->where('project_id', $projectId)->where('id', $data['id'])->lockForUpdate()->firstOrFail();
            $oldAssignee = $bug->assigned_to ?: 'Unassigned';
            $newAssignee = $data['assigned_to'];
            if ($oldAssignee === $newAssignee) {
                return;
            }

            $isDuplicate = (bool) $bug->is_duplicate;
            $wasActive = $this->isActiveAssignment($projectId, $isDuplicate, $bug->status, $oldAssignee);
            $willBeActive = $this->isActiveAssignment($projectId, $isDuplicate, $bug->status, $newAssignee);

            $locked = $this->lockDeveloperWorkloads($projectId, [$oldAssignee, $newAssignee]);
            if ($willBeActive && $newAssignee !== $oldAssignee) {
                $newDeveloper = $locked->get($newAssignee);
                if (!$newDeveloper || (int) $newDeveloper->active_tasks >= (int) $newDeveloper->capacity) {
                    $assignmentError = 'That developer has reached capacity. Increase capacity or free a slot before assigning this issue.';
                    return;
                }
            }

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

        if ($assignmentError) return back()->withErrors(['assigned_to' => $assignmentError]);

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

            if (!$wasActive && $willBeActive) {
                $locked = $this->lockDeveloperWorkloads($projectId, [$bug->assigned_to]);
                $developer = $locked->get($bug->assigned_to);
                abort_unless($developer && (int) $developer->active_tasks < (int) $developer->capacity, 422, 'This developer has reached capacity. Increase capacity or reassign the issue before confirming it as active.');
            }

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

    public function notificationsPage(Request $request): View
    {
        $notificationType = $request->query('type', 'all');
        $notificationQuery = Auth::user()->notifications()->latest();
        if ($notificationType !== 'all') {
            $events = match ($notificationType) {
                'mention' => ['mention', 'mentioned', 'comment_mention'],
                'comment' => ['comment', 'comment_added', 'comment_mention'],
                'assignment' => ['assigned', 'assignment_changed', 'reassigned'],
                'status' => ['status_changed'],
                default => [],
            };
            if ($events) {
                $eventPlaceholders = implode(', ', array_fill(0, count($events), '?'));
                $notificationQuery->whereRaw("CAST(data AS jsonb) ->> 'event' IN ({$eventPlaceholders})", $events);
            } else {
                $notificationType = 'all';
            }
        }
        $notifications = $notificationQuery->paginate(30)->withQueryString();
        $projects = $this->accessibleProjects();
        $currentProjectId = (int) (session('project_id') ?: ($projects->first()->id ?? 0));
        $unreadNotificationCount = Auth::user()->unreadNotifications()->count();

        return view('notifications', compact('notifications', 'projects', 'currentProjectId', 'unreadNotificationCount', 'notificationType'));
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
        $currentProject = DB::table('projects')->where('id', $projectId)->first();
        $projectGithubRepo = $currentProject?->github_repo ?: session('github_repo', '');
        $projectStack = $currentProject?->stack ?: session('project_stack', 'Multi-stack');
        return view('workflow_settings', compact('statuses', 'transitions', 'projectGithubRepo', 'projectStack'));
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
            $line = trim(str_replace('→', '->', $line));
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

    /** Lock developer capacity rows and reconcile counters from current active assignments. */
    private function lockDeveloperWorkloads(int $projectId, array $names)
    {
        $names = array_values(array_unique(array_filter($names, fn ($name) => is_string($name) && $name !== '' && $name !== 'Unassigned')));
        if (!$names) return collect();

        $developers = DB::table('developers')->where('project_id', $projectId)->whereIn('name', $names)
            ->orderBy('id')->lockForUpdate()->get();
        $activeStatuses = DB::table('project_workflow_statuses')->where('project_id', $projectId)
            ->whereRaw('is_done = false')->where('name', '!=', 'Duplicate')->pluck('name')->all();
        foreach ($developers as $developer) {
            $actualCount = DB::table('bugs')->where('project_id', $projectId)->where('assigned_to', $developer->name)
                ->whereIn('status', $activeStatuses)->whereRaw('is_duplicate = false')->count();
            if ((int) $developer->active_tasks !== $actualCount) {
                DB::table('developers')->where('id', $developer->id)->update(['active_tasks' => $actualCount]);
                $developer->active_tasks = $actualCount;
            }
        }
        return $developers->keyBy('name');
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

    private function isGibberishOrInvalidReport(string $title, string $desc, bool $hasAttachment): bool
    {
        $cleanTitle = strtolower(trim($title));
        $cleanDesc = strtolower(trim($desc));

        if ($hasAttachment && strlen($cleanTitle) >= 4 && preg_match('/[aeiouy]/i', $cleanTitle)) {
            return false;
        }

        if (strlen($cleanTitle) < 5) {
            return true;
        }

        // Repeated characters e.g. aaaa, zzzz
        if (preg_match('/(.)\1{3,}/', $cleanTitle)) {
            return true;
        }

        // Repeating 2-3 char patterns e.g. mbmbm, asdasd, qweqwe
        if (preg_match('/^(.{2,3})\1+.*$/', $cleanTitle) && !str_contains($cleanTitle, ' ')) {
            return true;
        }

        $knownAbbrs = ['sql', 'php', 'css', 'html', 'jwt', 'api', 'cli', 'sdk', 'sso', 'ssl', 'ssh', 'dns', 'url', 'xml', 'csv', 'svg', 'npm', 'jvm', 'grpc', 'http', 'https'];
        $words = array_values(array_filter(explode(' ', preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $cleanTitle))));

        if (count($words) <= 1) {
            $single = $words[0] ?? $cleanTitle;
            if (in_array($single, $knownAbbrs, true)) {
                return strlen($cleanDesc) < 10;
            }
            // No vowels in a single word of 4+ characters
            if (!preg_match('/[aeiouy]/i', $single)) {
                return true;
            }
            if (strlen($single) < 5 && !preg_match('/^\d{3}$/', $single)) {
                return true;
            }
        }

        $combined = $cleanTitle . ' ' . $cleanDesc;
        preg_match_all('/[aeiouy]/i', $combined, $vowels);
        preg_match_all('/[a-z]/i', $combined, $letters);
        if (count($letters[0] ?? []) >= 5 && count($vowels[0] ?? []) === 0) {
            return true;
        }

        return false;
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
