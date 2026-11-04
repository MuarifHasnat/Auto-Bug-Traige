@if(isset($projects) && $projects->count() > 1)
    @php($darkSwitcher = ($variant ?? 'light') === 'dark')
    @php($compactSwitcher = ($variant ?? 'light') === 'mobile')
    <form action="{{ route('projects.switch') }}" method="POST" class="{{ $darkSwitcher ? 'mt-3' : ($compactSwitcher ? 'lg:hidden' : 'min-w-[180px]') }}">
        @csrf
        <label for="project-switcher-{{ $variant ?? 'light' }}" class="mb-1 block text-[10px] font-bold uppercase tracking-wider {{ $compactSwitcher ? 'sr-only' : '' }} {{ $darkSwitcher ? 'text-slate-400' : 'text-slate-500' }}">
            Switch project
        </label>
        <select id="project-switcher-{{ $variant ?? 'light' }}" name="project_id" onchange="this.form.submit()"
                class="{{ $compactSwitcher ? 'w-28 px-1.5 py-1.5' : 'w-full px-2.5 py-2' }} rounded-lg border text-xs font-semibold outline-none transition focus:border-indigo-500 {{ $darkSwitcher ? 'border-slate-700 bg-slate-900 text-white' : 'border-slate-300 bg-white text-slate-800' }}">
            @foreach($projects as $project)
                <option value="{{ $project->id }}" @selected((int) $project->id === (int) $currentProjectId)>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
    </form>
@endif
