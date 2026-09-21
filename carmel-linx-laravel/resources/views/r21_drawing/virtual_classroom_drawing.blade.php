<x-layouts.workspace-layout 
    :title="'[' . ($batchSubject->formatted_subject_code ?? $batchSubject->subject_code) . '] ' . $batchSubject->subject_name . ' — Virtual Drawing Hall (R-2021)'"
    :subjectCode="$batchSubject->formatted_subject_code ?? $batchSubject->subject_code"
    :subjectName="$batchSubject->subject_name"
    activeNav="academics"
>
    <!-- Header Action Slot -->
    <x-slot:headerActions>
        <div class="flex items-center gap-2.5">
            <span id="globalAutoSaveIndicator" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 transition-all">
                <x-ui.icon name="check" class="w-3.5 h-3.5 text-emerald-600" />
                <span>All Changes Saved</span>
            </span>
            <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/cia" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 transition-all shadow-2xs no-underline">
                <x-ui.icon name="printer" class="w-4 h-4 text-slate-500" />
                <span>Print CIA Marksheet</span>
            </a>
        </div>
    </x-slot:headerActions>

    <div class="space-y-6">
        <!-- 1. Executive Stat Overview Banner -->
        @include('r21_drawing.partials.stat-cards')

        <!-- 2. Workspace Navigation Tabs -->
        <div class="bg-white border border-slate-200 rounded-2xl p-2 shadow-xs">
            <nav class="flex flex-wrap gap-1.5" id="r21DrawingTabs" role="tablist">
                <button type="button" 
                        onclick="switchDrawingTab('tab-formative', this)" 
                        id="btn-tab-formative"
                        class="tab-trigger active flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all bg-blue-50 text-blue-700 border border-blue-200 shadow-2xs">
                    <x-ui.icon name="pen-tool" class="w-4 h-4 text-blue-600" />
                    <span>Formative Assessment (Sheets - 40% / {{ $formativeMax }}M)</span>
                </button>
                <button type="button" 
                        onclick="switchDrawingTab('tab-summative', this)" 
                        id="btn-tab-summative"
                        class="tab-trigger flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 border border-transparent transition-all">
                    <x-ui.icon name="clipboard-check" class="w-4 h-4 text-indigo-500" />
                    <span>Summative Tests (Avg 2 Tests - 40% / {{ $summativeMax }}M)</span>
                </button>
                <button type="button" 
                        onclick="switchDrawingTab('tab-attendance', this)" 
                        id="btn-tab-attendance"
                        class="tab-trigger flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 border border-transparent transition-all">
                    <x-ui.icon name="user-check" class="w-4 h-4 text-emerald-500" />
                    <span>Attendance &amp; Performance (20% / {{ $attMax }}M)</span>
                </button>
                <button type="button" 
                        onclick="switchDrawingTab('tab-cia', this)" 
                        id="btn-tab-cia"
                        class="tab-trigger flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 border border-transparent transition-all">
                    <x-ui.icon name="award" class="w-4 h-4 text-amber-500" />
                    <span>Consolidated CIA &amp; Attainment ({{ $ciaMax }}M)</span>
                </button>
                <button type="button" 
                        onclick="switchDrawingTab('tab-lessonplan', this)" 
                        id="btn-tab-lessonplan"
                        class="tab-trigger flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 border border-transparent transition-all">
                    <x-ui.icon name="calendar" class="w-4 h-4 text-blue-500" />
                    <span>Lesson Plan (60 Hours)</span>
                </button>
                <button type="button" 
                        onclick="switchDrawingTab('tab-syllabus', this)" 
                        id="btn-tab-syllabus"
                        class="tab-trigger flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 border border-transparent transition-all">
                    <x-ui.icon name="file-text" class="w-4 h-4 text-rose-500" />
                    <span>Syllabus, COs &amp; Matrix</span>
                </button>
            </nav>
        </div>

        <!-- 3. Tab Contents Container -->
        <div id="r21DrawingTabPanes" class="space-y-6">
            <!-- TAB 1: FORMATIVE ASSESSMENT (DRAWING SHEETS) -->
            <div id="tab-formative" class="tab-pane block space-y-4">
                @include('r21_drawing.partials.tab-formative')
            </div>

            <!-- TAB 2: SUMMATIVE ASSESSMENT (SERIES TESTS) -->
            <div id="tab-summative" class="tab-pane hidden space-y-4">
                @include('r21_drawing.partials.tab-summative')
            </div>

            <!-- TAB 3: ATTENDANCE & PERFORMANCE (20% WEIGHTAGE) -->
            <div id="tab-attendance" class="tab-pane hidden space-y-4">
                @include('r21_drawing.partials.tab-attendance')
            </div>

            <!-- TAB 4: CONSOLIDATED CIA & ATTAINMENT -->
            <div id="tab-cia" class="tab-pane hidden space-y-4">
                @include('r21_drawing.partials.tab-cia')
            </div>

            <!-- TAB 5: LESSON PLAN (60 HOURS) -->
            <div id="tab-lessonplan" class="tab-pane hidden space-y-4">
                @include('r21_drawing.partials.tab-lessonplan')
            </div>

            <!-- TAB 6: SYLLABUS, COS & MATRIX -->
            <div id="tab-syllabus" class="tab-pane hidden space-y-4">
                @include('r21_drawing.partials.tab-syllabus')
            </div>
        </div>
    </div>

    <!-- Client-Side Scripts & State Management -->
    @include('r21_drawing.partials.scripts')
</x-layouts.workspace-layout>
