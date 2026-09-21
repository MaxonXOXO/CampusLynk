<x-layouts.workspace-layout 
    :title="'[R-2021] Virtual Seminar Room - ' . $batchSubject->subject_name"
    :subjectCode="$batchSubject->subject_code"
    :subjectName="$batchSubject->subject_name"
    activeNav="academics">

    <x-slot:headerActions>
        <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode(request()->getRequestUri()) }}" title="Class Attendance & Subject Log">
            <x-ui.button variant="secondary" size="sm" icon="calendar">
                <span class="ml-1.5">Attendance &amp; Log</span>
            </x-ui.button>
        </a>

        <x-ui.button variant="secondary" size="sm" icon="file-text" onclick="openSyllabusModal()" title="Upload / View Course Syllabus">
            <span class="ml-1.5">Syllabus</span>
        </x-ui.button>

        <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=consolidated" target="_blank" title="Print Consolidated Report">
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

    <!-- Regulation & Marks Allocation Banner (Clause 11.2.6) -->
    <div class="bg-gradient-to-r from-blue-900/40 via-slate-900 to-slate-900 border border-slate-700/60 rounded-2xl p-4 flex flex-wrap items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-2 flex-wrap">
            <x-ui.badge variant="success">R-2021 Regulation</x-ui.badge>
            <x-ui.badge variant="info">Clause 11.2.6 Seminar Assessment</x-ui.badge>
            <span class="text-xs text-slate-400 font-medium">
                {{ $classroom->department ?? $classroom->branch ?? 'Engineering' }} • Semester {{ $classroom->current_semester ?? $batchSubject->semester ?? 'VI' }}
            </span>
        </div>
        <div class="flex items-center gap-2 text-xs font-semibold">
            <span class="px-2.5 py-1 bg-slate-800/80 border border-slate-700 text-emerald-400 rounded-lg">CIA: 75 Marks (100% CIE)</span>
            <span class="px-2.5 py-1 bg-slate-800/80 border border-slate-700 text-amber-400 rounded-lg">67.5M Academic + 7.5M Attendance</span>
            <span class="px-2.5 py-1 bg-blue-600/20 border border-blue-500/40 text-blue-300 rounded-lg">Assessed by Faculty Committee</span>
        </div>
    </div>

    <!-- Quick Stats Metric Strip -->
    @include('r21_seminar.partials.stats-strip')

    <!-- Workspace Tab Navigation -->
    <div class="border-b border-slate-700/80">
        <nav class="-mb-px flex space-x-2 overflow-x-auto" aria-label="Workspace Tabs" id="workspaceTabNav">
            <button type="button" onclick="switchTab('tab-evaluation')" id="btn-tab-evaluation" class="tab-btn active px-4 py-3 border-b-2 border-blue-500 text-sm font-semibold text-blue-400 flex items-center gap-2 transition-all">
                <x-ui.icon name="table" class="w-4 h-4" />
                <span>Evaluation Register</span>
                <span class="px-1.5 py-0.5 rounded-full text-xs bg-blue-500/20 text-blue-300 font-mono">{{ $totalStudents }}</span>
            </button>
            <button type="button" onclick="switchTab('tab-schedule')" id="btn-tab-schedule" class="tab-btn px-4 py-3 border-b-2 border-transparent text-sm font-medium text-slate-400 hover:text-slate-200 hover:border-slate-500 flex items-center gap-2 transition-all">
                <x-ui.icon name="calendar" class="w-4 h-4" />
                <span>Presentation Schedule &amp; Log</span>
            </button>
            <button type="button" onclick="switchTab('tab-consolidated')" id="btn-tab-consolidated" class="tab-btn px-4 py-3 border-b-2 border-transparent text-sm font-medium text-slate-400 hover:text-slate-200 hover:border-slate-500 flex items-center gap-2 transition-all">
                <x-ui.icon name="award" class="w-4 h-4" />
                <span>Consolidated &amp; Grades</span>
            </button>
            <button type="button" onclick="switchTab('tab-attainment')" id="btn-tab-attainment" class="tab-btn px-4 py-3 border-b-2 border-transparent text-sm font-medium text-slate-400 hover:text-slate-200 hover:border-slate-500 flex items-center gap-2 transition-all">
                <x-ui.icon name="chart-bar" class="w-4 h-4" />
                <span>CO Attainment (CO1–CO3)</span>
            </button>
            <button type="button" onclick="switchTab('tab-rubrics')" id="btn-tab-rubrics" class="tab-btn px-4 py-3 border-b-2 border-transparent text-sm font-medium text-slate-400 hover:text-slate-200 hover:border-slate-500 flex items-center gap-2 transition-all">
                <x-ui.icon name="file-text" class="w-4 h-4" />
                <span>Clause 11.2.6 Rubrics Guide</span>
            </button>
        </nav>
    </div>

    <!-- Workspace Tab Contents Container -->
    <div class="space-y-6 pt-2">
        @include('r21_seminar.partials.tab-evaluation')
        @include('r21_seminar.partials.tab-schedule')
        @include('r21_seminar.partials.tab-consolidated')
        @include('r21_seminar.partials.tab-attainment')
        @include('r21_seminar.partials.tab-rubrics')
    </div>

    <!-- Modals & Interactive Scripts -->
    @include('r21_seminar.partials.modals')
    @include('r21_seminar.partials.scripts')

</x-layouts.workspace-layout>
