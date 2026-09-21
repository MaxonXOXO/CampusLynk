<x-layouts.workspace-layout 
    :title="'[R-2021] Virtual Major Project Room - ' . $batchSubject->subject_name"
    :subjectCode="$batchSubject->subject_code"
    :subjectName="$batchSubject->subject_name"
    activeNav="academics">

    <x-slot:headerActions>
        <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode(request()->getRequestUri()) }}" title="Class Attendance & Subject Log">
            <x-ui.button variant="secondary" size="sm" icon="calendar">
                <span class="ml-1.5">Attendance &amp; Log</span>
            </x-ui.button>
        </a>

        <x-ui.button variant="secondary" size="sm" icon="users" onclick="switchTab('tab-groups')" title="Project Groups Allocation">
            <span class="ml-1.5">Project Groups</span>
        </x-ui.button>

        <a href="/r21/classroom/project/{{ $batchSubject->id }}/report/print?type=consolidated" target="_blank" title="Print Consolidated Report">
            <x-ui.button variant="secondary" size="sm" icon="printer">
                <span class="ml-1.5">Print Register</span>
            </x-ui.button>
        </a>

        <a href="/dashboard" title="Return to Dashboard">
            <x-ui.button variant="secondary" size="sm" icon="arrow-left">
                <span class="ml-1.5">Back</span>
            </x-ui.button>
        </a>
    </x-slot:headerActions>

    <!-- Regulation & Marks Allocation Banner -->
    <div class="bg-gradient-to-r from-blue-900/40 via-slate-900 to-slate-900 border border-slate-700/60 rounded-2xl p-4 flex flex-wrap items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-2 flex-wrap">
            <x-ui.badge variant="success">R-2021 Regulation</x-ui.badge>
            <x-ui.badge variant="info">Clause 11.2.5 &amp; 11.3.4</x-ui.badge>
            <span class="text-xs text-slate-400 font-medium">
                {{ $classroom->department ?? $classroom->branch ?? 'Engineering' }} • Semester {{ $classroom->current_semester ?? $batchSubject->semester ?? 'VI' }}
            </span>
        </div>
        <div class="flex items-center gap-2 text-xs font-semibold">
            <span class="px-2.5 py-1 bg-slate-800/80 border border-slate-700 text-emerald-400 rounded-lg">CIA: 75 Marks</span>
            <span class="px-2.5 py-1 bg-slate-800/80 border border-slate-700 text-sky-400 rounded-lg">ESE: 50 Marks</span>
            <span class="px-2.5 py-1 bg-blue-600/20 border border-blue-500/40 text-blue-300 rounded-lg">Total: 125 Marks</span>
        </div>
    </div>

    <!-- Quick Stats Metric Strip -->
    @include('r21_project.partials.stats-strip')

    <!-- Workspace Tab Navigation -->
    <div class="border-b border-slate-700/80">
        <nav class="-mb-px flex space-x-2 overflow-x-auto" aria-label="Workspace Tabs" id="workspaceTabNav">
            <button type="button" onclick="switchTab('tab-register')" id="btn-tab-register" class="tab-btn active px-4 py-3 border-b-2 border-blue-500 text-sm font-semibold text-blue-400 flex items-center gap-2 transition-all">
                <x-ui.icon name="table" class="w-4 h-4" />
                <span>Master Marksheet</span>
                <span class="px-1.5 py-0.5 rounded-full text-xs bg-blue-500/20 text-blue-300 font-mono">{{ $totalStudents }}</span>
            </button>
            <button type="button" onclick="switchTab('tab-cia')" id="btn-tab-cia" class="tab-btn px-4 py-3 border-b-2 border-transparent text-sm font-medium text-slate-400 hover:text-slate-200 hover:border-slate-500 flex items-center gap-2 transition-all">
                <x-ui.icon name="edit" class="w-4 h-4" />
                <span>CIA (75M)</span>
            </button>
            <button type="button" onclick="switchTab('tab-ese')" id="btn-tab-ese" class="tab-btn px-4 py-3 border-b-2 border-transparent text-sm font-medium text-slate-400 hover:text-slate-200 hover:border-slate-500 flex items-center gap-2 transition-all">
                <x-ui.icon name="award" class="w-4 h-4" />
                <span>ESE 8-Rubrics (50M)</span>
            </button>
            <button type="button" onclick="switchTab('tab-reports')" id="btn-tab-reports" class="tab-btn px-4 py-3 border-b-2 border-transparent text-sm font-medium text-slate-400 hover:text-slate-200 hover:border-slate-500 flex items-center gap-2 transition-all">
                <x-ui.icon name="printer" class="w-4 h-4" />
                <span>Statutory Reports</span>
            </button>
            <button type="button" onclick="switchTab('tab-groups')" id="btn-tab-groups" class="tab-btn px-4 py-3 border-b-2 border-transparent text-sm font-medium text-slate-400 hover:text-slate-200 hover:border-slate-500 flex items-center gap-2 transition-all">
                <x-ui.icon name="users" class="w-4 h-4" />
                <span>Project Groups</span>
            </button>
            <button type="button" onclick="switchTab('tab-attainment')" id="btn-tab-attainment" class="tab-btn px-4 py-3 border-b-2 border-transparent text-sm font-medium text-slate-400 hover:text-slate-200 hover:border-slate-500 flex items-center gap-2 transition-all">
                <x-ui.icon name="chart-bar" class="w-4 h-4" />
                <span>CO Attainment</span>
            </button>
            <button type="button" onclick="switchTab('tab-rubrics')" id="btn-tab-rubrics" class="tab-btn px-4 py-3 border-b-2 border-transparent text-sm font-medium text-slate-400 hover:text-slate-200 hover:border-slate-500 flex items-center gap-2 transition-all">
                <x-ui.icon name="file-text" class="w-4 h-4" />
                <span>Regulation Reference</span>
            </button>
        </nav>
    </div>

    <!-- Workspace Tab Contents Container -->
    <div class="space-y-6 pt-2">
        @include('r21_project.partials.tab-register')
        @include('r21_project.partials.tab-cia')
        @include('r21_project.partials.tab-ese')
        @include('r21_project.partials.tab-reports')
        @include('r21_project.partials.tab-groups')
        @include('r21_project.partials.tab-attainment')
        @include('r21_project.partials.tab-rubrics')
    </div>

    <!-- Modals & Interactive Scripts -->
    @include('r21_project.partials.modals')
    @include('r21_project.partials.scripts')

</x-layouts.workspace-layout>
