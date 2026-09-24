<x-layouts.workspace-layout 
    :title="'[R-2026] Practicum Virtual Classroom (Basic Science Practicum) - ' . $batchSubject->subject_name"
    :subjectCode="$batchSubject->subject_code"
    :subjectName="$batchSubject->subject_name"
    activeNav="academics">

    <x-slot:headerActions>
        <a href="/staff/attendance-log?subject_id={{ $batchSubject->id }}&return_to={{ urlencode(request()->getRequestUri()) }}" title="Class Attendance & Log">
            <x-ui.button variant="secondary" size="sm" icon="calendar">
                <span class="ml-1.5">Attendance &amp; Log</span>
            </x-ui.button>
        </a>

        @if($practicumCourseFile && $practicumCourseFile->syllabus_pdf_path)
            <a href="/storage/{{ $practicumCourseFile->syllabus_pdf_path }}" target="_blank" title="View Syllabus PDF">
                <x-ui.button variant="secondary" size="sm" icon="file-text">
                    <span class="ml-1.5">Syllabus PDF</span>
                </x-ui.button>
            </a>
        @endif

        <x-ui.button variant="secondary" size="sm" icon="upload" onclick="openSyllabusModal()" title="Upload Course Syllabus">
            <span class="ml-1.5">Upload Syllabus</span>
        </x-ui.button>

        <a href="/r26/classroom/practicum/course-file/{{ $batchSubject->id }}" title="Course File Console">
            <x-ui.button variant="secondary" size="sm" icon="folder">
                <span class="ml-1.5">Course File</span>
            </x-ui.button>
        </a>

        <a href="/dashboard" title="Return to Dashboard">
            <x-ui.button variant="secondary" size="sm" icon="arrow-left">
                <span class="ml-1.5">Back</span>
            </x-ui.button>
        </a>
    </x-slot:headerActions>

    <!-- Regulation & Header Stats Card -->
    @include('r26_practicum.partials.header-stats')

    <!-- Dual Mode Switcher (Theory Classroom vs Lab Workspace) -->
    <div class="bg-white border border-slate-200/80 p-2 rounded-2xl mb-5 flex items-center justify-center gap-3 shadow-xs">
        <button onclick="switchMode('theory')" id="mode-btn-theory" class="mode-btn active w-1/2 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2 text-sm sm:text-base cursor-pointer">
            <x-ui.icon name="book" class="w-5 h-5" />
            <span>Virtual Theory Classroom</span>
        </button>
        <button onclick="switchMode('lab')" id="mode-btn-lab" class="mode-btn w-1/2 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2 text-sm sm:text-base cursor-pointer">
            <x-ui.icon name="cpu" class="w-5 h-5" />
            <span>Virtual Lab Workspace</span>
        </button>
    </div>

    <!-- MODE A: VIRTUAL THEORY CLASSROOM -->
    <div id="mode-theory-container" class="space-y-5">
        <!-- Theory Sub-Tabs Navigation -->
        <div class="bg-white border border-slate-200/80 p-2 rounded-2xl flex items-center gap-2 overflow-x-auto shadow-xs">
            <button onclick="switchTheorySubtab('overview')" id="theory-tab-overview" class="subtab-btn active px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Modules &amp; COs</button>
            <button onclick="switchTheorySubtab('planner')" id="theory-tab-planner" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Lesson Plan</button>
            <button onclick="switchTheorySubtab('sl')" id="theory-tab-sl" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Self-Learning</button>
            <button onclick="switchTheorySubtab('series')" id="theory-tab-series" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Theory Series</button>
            <button onclick="switchTheorySubtab('ese')" id="theory-tab-ese" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Theory ESE (60M)</button>
            <button onclick="switchTheorySubtab('surveys')" id="theory-tab-surveys" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Surveys</button>
            <button onclick="switchTheorySubtab('attendance')" id="theory-tab-attendance" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Attendance</button>
            <button onclick="switchTheorySubtab('materials')" id="theory-tab-materials" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Study Materials</button>
        </div>

        @include('r26_practicum.partials.tab-theory-overview')
        @include('r26_practicum.partials.tab-theory-planner')
        @include('r26_practicum.partials.tab-theory-sl')
        @include('r26_practicum.partials.tab-theory-series')
        @include('r26_practicum.partials.tab-theory-ese')
        @include('r26_practicum.partials.tab-theory-surveys')
        @include('r26_practicum.partials.tab-theory-attendance')
        @include('r26_practicum.partials.tab-theory-materials')
    </div>

    <!-- MODE B: BASIC SCIENCE LAB WORKSPACE -->
    <div id="mode-lab-container" class="hidden space-y-5">
        <!-- Lab Sub-Tabs Navigation -->
        <div class="bg-white border border-slate-200/80 p-2 rounded-2xl flex items-center gap-2 overflow-x-auto shadow-xs">
            <button onclick="switchLabSubtab('roster')" id="lab-tab-roster" class="subtab-btn active px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Student Batches</button>
            <button onclick="switchLabSubtab('planner')" id="lab-tab-planner" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Practical Lesson Plan</button>
            <button onclick="switchLabSubtab('eval')" id="lab-tab-eval" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Continuous Evaluation (Table 2.2)</button>
            <button onclick="switchLabSubtab('series')" id="lab-tab-series" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Practical Series Test (Table 3.1)</button>
            <button onclick="switchLabSubtab('ese')" id="lab-tab-ese" class="subtab-btn px-3 py-2 rounded-xl font-semibold whitespace-nowrap cursor-pointer">Lab Scheme (40M CIA / 0M Lab ESE)</button>
        </div>

        @include('r26_practicum.partials.tab-lab-roster')
        @include('r26_practicum.partials.tab-lab-planner')
        @include('r26_practicum.partials.basic_science_evaluation')
        @include('r26_practicum.partials.tab-lab-series')
        @include('r26_practicum.partials.tab-lab-ese')
    </div>

    <!-- Modals -->
    @include('r26_practicum.partials.modal-sl')
    @include('r26_practicum.partials.modal-ese')
    @include('r26_practicum.partials.modal-series')
    @include('r26_practicum.partials.modal-experiment-eval')
    @include('r26_practicum.partials.modal-qp-editor')
    @include('r26_practicum.partials.modal-surveys')

    <!-- Scripts -->
    <script>
        window.csrfToken = '{{ csrf_token() }}';
        window.batchSubjectId = {{ $batchSubject->id }};
    </script>
    <script>
    @include('r26_practicum.partials.scripts-core')
    @include('r26_practicum.partials.scripts-sl')
    @include('r26_practicum.partials.scripts-planner')
    @include('r26_practicum.partials.scripts-qp')
    @include('r26_practicum.partials.scripts-ese-theory')
    @include('r26_practicum.partials.scripts-series-theory')
    @include('r26_practicum.partials.scripts-eval-exp')
    @include('r26_practicum.partials.scripts-eval-series-pr')
    @include('r26_practicum.partials.scripts-eval-ese-pr')
    @include('r26_practicum.partials.scripts-surveys')
    </script>

</x-layouts.workspace-layout>
