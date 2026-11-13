@if(isset($projects) && $projects->count() > 0)
    @php($darkSwitcher = ($variant ?? 'light') === 'dark')
    @php($compactSwitcher = ($variant ?? 'light') === 'mobile')
    @if($compactSwitcher)
        <form action="{{ route('projects.switch', [], false) }}" method="POST" class="lg:hidden">
            @csrf
            <label for="project-switcher-mobile" class="sr-only">Switch project</label>
            <select id="project-switcher-mobile" name="project_id" onchange="this.form.submit()" class="w-28 rounded-lg border border-slate-300 bg-white px-1.5 py-1.5 text-xs font-semibold text-slate-800 outline-none transition focus:border-indigo-500">
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected((int) $project->id === (int) $currentProjectId)>{{ $project->name }}</option>
                @endforeach
            </select>
        </form>
    @else
        @php($activeProject = $projects->firstWhere('id', $currentProjectId))
        <details class="group mt-3" aria-label="Projects">
            <summary class="flex cursor-pointer list-none items-center gap-2 rounded-lg px-1 py-1.5 text-[10px] font-bold uppercase tracking-wider {{ $darkSwitcher ? 'text-slate-400 hover:text-slate-200' : 'text-slate-500 hover:text-slate-700' }} focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                <span>Projects</span>
                @if($activeProject)
                    <span class="min-w-0 flex-1 truncate text-right normal-case tracking-normal {{ $darkSwitcher ? 'text-slate-300' : 'text-slate-700' }}">{{ $activeProject->name }}</span>
                @endif
                <svg class="h-3 w-3 shrink-0 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg>
            </summary>
            <ul class="mt-1 max-h-48 space-y-1 overflow-y-auto">
                @foreach($projects as $project)
                    <li>
                        @if((int) $project->id === (int) $currentProjectId)
                            <div aria-current="page" class="flex items-center gap-2 rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 py-2 text-xs font-bold text-indigo-700">
                                <span aria-hidden="true" class="h-1.5 w-1.5 shrink-0 rounded-full bg-indigo-600"></span>
                                <span class="min-w-0 flex-1 truncate">{{ $project->name }}</span>
                                <span class="shrink-0 text-[9px] font-semibold uppercase tracking-wide">Current</span>
                            </div>
                        @else
                            <form action="{{ route('projects.switch', [], false) }}" method="POST">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg border border-transparent px-2.5 py-2 text-left text-xs font-semibold {{ $darkSwitcher ? 'text-slate-300 hover:border-slate-700 hover:bg-slate-900' : 'text-slate-600 hover:border-slate-200 hover:bg-slate-50 hover:text-slate-900' }} transition">
                                    <span aria-hidden="true" class="h-1.5 w-1.5 shrink-0 rounded-full bg-slate-300"></span>
                                    <span class="min-w-0 truncate">{{ $project->name }}</span>
                                </button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        </details>
    @endif
@endif
