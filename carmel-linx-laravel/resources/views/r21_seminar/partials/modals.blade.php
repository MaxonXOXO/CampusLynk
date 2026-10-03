<!-- ========================================== -->
<!-- MODAL 1: Individual Seminar Evaluation Modal -->
<!-- Full Clause 11.2.6 Evaluation Rubrics (75M) -->
<!-- ========================================== -->
<div id="evaluationModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-3xl shadow-2xl overflow-hidden flex flex-col my-auto max-h-[94vh]">
        <!-- Modal Header -->
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center shrink-0">
                    <x-ui.icon name="award" class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm sm:text-base font-bold text-slate-900 leading-tight" id="evalModalStudentName">Student Evaluation</span>
                        <span class="text-[11px] font-semibold text-slate-600 font-mono px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200" id="evalModalStudentMeta">
                            Reg: — • Roll: —
                        </span>
                    </div>
                    <div class="text-[11px] text-slate-500 mt-0.5">Clause 11.2.6 Evaluation Rubrics &bull; Max 75 Marks (Committee Averaged)</div>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <!-- Live Header Score Pill -->
                <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 border border-blue-200 font-mono text-xs">
                    <span class="text-slate-600 font-medium">Total:</span>
                    <span class="font-bold text-blue-700 text-sm" id="evalHeaderScoreVal">0.0</span>
                    <span class="text-slate-400 text-[10px]">/ 75</span>
                </div>

                <button type="button" onclick="closeEvaluationModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer" title="Close (Esc)">
                    <x-ui.icon name="x" class="w-5 h-5" />
                </button>
            </div>
        </div>

        <!-- Form wrapping Body & Footer -->
        <form id="evaluationForm" onsubmit="submitEvaluationForm(event)" class="flex flex-col overflow-hidden flex-grow">
            <input type="hidden" id="evalRegNo" name="reg_no" value="">

            <!-- Scrollable Body -->
            <div class="p-4 sm:p-5 overflow-y-auto space-y-4">
                
                <!-- Metadata Strip: Topic, Assessor, Guide & Date -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                    <!-- Approved Seminar Topic -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Approved Seminar Topic</label>
                        <input type="text" id="evalTopicInput" name="topic" placeholder="e.g. Edge AI in Renewable Energy" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition font-sans">
                        <input type="hidden" id="evalTopic" value="">
                    </div>

                    <!-- Assessor, Guide & Presentation Date (3 columns) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] uppercase tracking-wide font-bold text-slate-600 mb-1">Evaluating Assessor</label>
                            <select id="evalAssessorMobile" name="assessor_mobile_no" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-slate-800 text-xs focus:border-blue-500 outline-none transition" onchange="onAssessorChange(this.value)">
                                @foreach($guides as $g)
                                    <option value="{{ $g->mobile_no }}" {{ ($activeStaff && $activeStaff->mobile_no == $g->mobile_no) ? 'selected' : '' }}>
                                        {{ $g->name }} ({{ $g->designation }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="hidden" id="currentAssessorDisplay">{{ $activeStaff->name ?? 'Faculty' }}</span>
                        </div>

                        <div>
                            <label class="block text-[11px] uppercase tracking-wide font-bold text-slate-600 mb-1">Assigned Guide</label>
                            <select id="evalGuideSelect" name="guide_mobile_no" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-slate-800 text-xs focus:border-blue-500 outline-none transition">
                                <option value="">— Select Guide —</option>
                                @foreach($guides as $g)
                                    <option value="{{ $g->mobile_no }}">{{ $g->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" id="evalGuideMobile" value="">
                        </div>

                        <div>
                            <label class="block text-[11px] uppercase tracking-wide font-bold text-slate-600 mb-1">Presentation Date</label>
                            <input type="date" id="evalPresentationDateInput" name="presentation_date" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-slate-800 text-xs font-mono focus:border-blue-500 outline-none transition">
                        </div>
                    </div>
                </div>

                <!-- Committee Breakdown Box (Shown if other faculty evaluated) -->
                <div id="evalCommitteeBreakdownBox" class="hidden p-3 rounded-xl bg-blue-50/60 border border-blue-200">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                            <x-ui.icon name="users" class="w-4 h-4 text-blue-600" />
                            <span>Recorded Committee Scores</span>
                        </span>
                        <span class="text-xs font-semibold text-blue-700" id="evalBreakdownAvgText">Average: 0 / 75</span>
                    </div>
                    <div id="evalBreakdownList" class="mt-2 space-y-1.5 text-xs"></div>
                </div>

                <!-- 6 Rubric Criteria in a Clean, Compact 2-Column Grid -->
                <div class="border border-slate-200 rounded-xl p-3.5 space-y-3 bg-slate-50/40">
                    <div class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center justify-between">
                        <span>Statutory Evaluation Rubrics (75 Marks)</span>
                        <span class="text-[11px] text-slate-500 font-normal font-sans">Paired Number &amp; Slider Controls</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        
                        <!-- Criterion 1: Relevance of Topic (Max 7.5) -->
                        <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold flex items-center justify-center shrink-0">1</span>
                                    <span class="font-bold text-slate-800 text-xs truncate">Relevance of Topic</span>
                                    <span class="text-[10px] text-slate-400 font-mono">10%</span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_relevance" name="relevance" class="w-16 bg-slate-50 border border-slate-300 rounded-lg px-2 py-0.5 text-center text-blue-700 font-bold text-xs focus:border-blue-500 focus:bg-white outline-none" oninput="syncEvalSlider('relevance')">
                                    <span class="text-slate-400 text-[10px]">/ 7.5</span>
                                </div>
                            </div>
                            <input type="range" min="0" max="7.5" step="0.25" id="range_relevance" class="w-full cursor-pointer accent-blue-600" oninput="syncEvalInput('relevance')">
                        </div>

                        <!-- Criterion 2: Literature Survey (Max 7.5) -->
                        <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold flex items-center justify-center shrink-0">2</span>
                                    <span class="font-bold text-slate-800 text-xs truncate">Literature Survey</span>
                                    <span class="text-[10px] text-slate-400 font-mono">10%</span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_literature" name="literature" class="w-16 bg-slate-50 border border-slate-300 rounded-lg px-2 py-0.5 text-center text-blue-700 font-bold text-xs focus:border-blue-500 focus:bg-white outline-none" oninput="syncEvalSlider('literature')">
                                    <span class="text-slate-400 text-[10px]">/ 7.5</span>
                                </div>
                            </div>
                            <input type="range" min="0" max="7.5" step="0.25" id="range_literature" class="w-full cursor-pointer accent-blue-600" oninput="syncEvalInput('literature')">
                        </div>

                        <!-- Criterion 3: Presentation Delivery (Max 37.5 - 50%) -->
                        <div class="p-3 rounded-xl bg-blue-50/80 border border-blue-200 shadow-sm space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0 shadow-sm">3</span>
                                    <span class="font-extrabold text-blue-950 text-xs truncate">Presentation Delivery</span>
                                    <span class="px-1.5 py-0.2 rounded bg-blue-200/80 text-blue-800 text-[9px] font-bold border border-blue-300">50%</span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="37.5" id="input_presentation" name="presentation" class="w-18 bg-white border border-blue-400 rounded-lg px-2 py-0.5 text-center text-blue-700 font-black text-xs focus:border-blue-600 outline-none shadow-sm" oninput="syncEvalSlider('presentation')">
                                    <span class="text-blue-700 font-bold text-[10px]">/ 37.5</span>
                                </div>
                            </div>
                            <input type="range" min="0" max="37.5" step="0.5" id="range_presentation" class="w-full cursor-pointer accent-blue-600" oninput="syncEvalInput('presentation')">
                        </div>

                        <!-- Criterion 4: Interaction / Viva (Max 7.5) -->
                        <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold flex items-center justify-center shrink-0">4</span>
                                    <span class="font-bold text-slate-800 text-xs truncate">Defense &amp; Discussion</span>
                                    <span class="text-[10px] text-slate-400 font-mono">10%</span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_interaction" name="interaction" class="w-16 bg-slate-50 border border-slate-300 rounded-lg px-2 py-0.5 text-center text-blue-700 font-bold text-xs focus:border-blue-500 focus:bg-white outline-none" oninput="syncEvalSlider('interaction')">
                                    <span class="text-slate-400 text-[10px]">/ 7.5</span>
                                </div>
                            </div>
                            <input type="range" min="0" max="7.5" step="0.25" id="range_interaction" class="w-full cursor-pointer accent-blue-600" oninput="syncEvalInput('interaction')">
                        </div>

                        <!-- Criterion 5: Seminar Report (Max 7.5) -->
                        <div class="p-3 rounded-xl bg-white border border-slate-200 shadow-sm space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold flex items-center justify-center shrink-0">5</span>
                                    <span class="font-bold text-slate-800 text-xs truncate">Seminar Report</span>
                                    <span class="text-[10px] text-slate-400 font-mono">10%</span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_report" name="report" class="w-16 bg-slate-50 border border-slate-300 rounded-lg px-2 py-0.5 text-center text-blue-700 font-bold text-xs focus:border-blue-500 focus:bg-white outline-none" oninput="syncEvalSlider('report')">
                                    <span class="text-slate-400 text-[10px]">/ 7.5</span>
                                </div>
                            </div>
                            <input type="range" min="0" max="7.5" step="0.25" id="range_report" class="w-full cursor-pointer accent-blue-600" oninput="syncEvalInput('report')">
                        </div>

                        <!-- Criterion 6: Attendance (Max 7.5 with 1-click Auto) -->
                        <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-200 shadow-sm space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="w-5 h-5 rounded-full bg-emerald-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0">6</span>
                                    <span class="font-bold text-slate-800 text-xs">Attendance</span>
                                    <button type="button" onclick="applySuggestedAttendance()" id="btnApplySuggestedAtt" class="px-2 py-0.5 bg-emerald-100 hover:bg-emerald-200 border border-emerald-300 text-emerald-800 rounded-md font-bold text-[10px] transition flex items-center gap-1 cursor-pointer shrink-0" title="Apply Auto Attendance Score">
                                        <x-ui.icon name="zap" class="w-3 h-3 text-emerald-700" />
                                        <span>Auto (<span id="modalSuggestedAttVal">7.5</span>M)</span>
                                    </button>
                                    <span id="modalAttHelpText" class="hidden"></span>
                                </div>
                                <div class="flex items-center gap-1 font-mono shrink-0">
                                    <input type="number" step="0.5" min="0" max="7.5" id="input_attendance" name="attendance" class="w-16 bg-white border border-emerald-400 rounded-lg px-2 py-0.5 text-center text-emerald-700 font-bold text-xs focus:border-emerald-600 outline-none" oninput="syncEvalSlider('attendance')">
                                    <span class="text-slate-400 text-[10px]">/ 7.5</span>
                                </div>
                            </div>
                            <input type="range" min="0" max="7.5" step="0.25" id="range_attendance" class="w-full cursor-pointer accent-emerald-600" oninput="syncEvalInput('attendance')">
                        </div>

                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-3 shrink-0 shadow-sm">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-500">Total Score</div>
                        <div class="text-lg sm:text-xl font-black text-slate-900 font-mono leading-tight" id="evalLiveTotal">
                            0.0 <span class="text-xs text-slate-400 font-normal">/ 75.0</span>
                        </div>
                    </div>
                    <div class="border-l border-slate-200 pl-3">
                        <div class="text-[10px] uppercase font-bold text-slate-500">Grade</div>
                        <div class="text-xs sm:text-sm font-extrabold text-slate-800" id="evalLiveGrade">—</div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <x-ui.button variant="secondary" size="md" type="button" onclick="closeEvaluationModal()">
                        <span>Cancel</span>
                    </x-ui.button>
                    <x-ui.button variant="primary" size="md" type="submit" id="btnSaveEval">
                        <x-ui.icon name="save" class="w-4 h-4 mr-1.5" />
                        <span>Save Evaluation</span>
                    </x-ui.button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 2: Seminar Schedule & Topic Modal   -->
<!-- ========================================== -->
<div id="scheduleModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden flex flex-col my-auto max-h-[90vh]">
        <!-- Modal Header -->
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center shrink-0">
                    <x-ui.icon name="calendar" class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                    <div class="text-sm sm:text-base font-bold text-slate-900" id="schedModalStudentName">Seminar Topic &amp; Presentation Schedule</div>
                    <div class="text-xs text-slate-500 font-mono" id="schedModalStudentMeta">Reg: —</div>
                </div>
            </div>
            <button type="button" onclick="closeScheduleModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                <x-ui.icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <!-- Modal Form -->
        <form id="scheduleForm" onsubmit="submitScheduleForm(event)" class="p-5 space-y-4 text-xs sm:text-sm overflow-y-auto flex-grow">
            <input type="hidden" id="schedRegNo" name="reg_no" value="">

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Approved Seminar Topic</label>
                <textarea id="schedTopic" name="topic" rows="3" required placeholder="Enter approved seminar presentation topic..." class="w-full bg-white border border-slate-200 rounded-xl p-3 text-slate-800 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition font-sans"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Presentation Date</label>
                <input type="date" id="schedDate" name="presentation_date" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 text-xs font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Faculty Guide</label>
                <select id="schedGuideMobile" name="guide_mobile_no" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                    <option value="">— Select Faculty Guide —</option>
                    @foreach($guides as $g)
                        <option value="{{ $g->mobile_no }}">{{ $g->name }} ({{ $g->designation }})</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                <x-ui.button variant="secondary" size="md" type="button" onclick="closeScheduleModal()">
                    <span>Cancel</span>
                </x-ui.button>
                <x-ui.button variant="primary" size="md" type="submit" id="btnSaveSchedule">
                    <x-ui.icon name="save" class="w-4 h-4 mr-1.5" />
                    <span>Save Schedule</span>
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 3: Committee Assessor Breakdown Modal -->
<!-- Full Faculty Scorecard & SBTE Grade        -->
<!-- ========================================== -->
<div id="facultyBreakdownModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden flex flex-col my-auto max-h-[90vh]">
        <!-- Modal Header -->
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0">
                    <x-ui.icon name="users" class="w-5 h-5 text-emerald-600" />
                </div>
                <div>
                    <div class="text-sm sm:text-base font-bold text-slate-900" id="breakdownModalStudentName">Faculty Evaluation Breakdown</div>
                    <div class="text-xs text-slate-500 font-mono" id="breakdownModalStudentMeta">Reg: —</div>
                </div>
            </div>
            <button type="button" onclick="closeFacultyBreakdownModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                <x-ui.icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <div class="p-5 space-y-4 text-xs sm:text-sm overflow-y-auto max-h-[70vh] flex-grow">
            <!-- Summary Banner -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between shadow-sm">
                <div>
                    <div class="text-[10px] uppercase font-bold text-slate-500">Final Averaged CIA Mark</div>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-600 font-mono" id="breakdownFinalAvg">
                        0.0 <span class="text-xs sm:text-sm text-slate-400 font-normal">/ 75.0</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-[10px] uppercase font-bold text-slate-500">SBTE Grade</div>
                    <div class="text-base sm:text-lg font-extrabold text-slate-800 mt-0.5" id="breakdownFinalGrade">—</div>
                </div>
            </div>

            <div class="text-xs font-bold text-slate-700">Individual Faculty Assessor Marks:</div>
            <div id="breakdownCardsContainer" class="space-y-2"></div>
        </div>

        <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-end shrink-0">
            <x-ui.button variant="secondary" size="md" type="button" onclick="closeFacultyBreakdownModal()">
                <span>Close</span>
            </x-ui.button>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 4: Syllabus Upload Modal             -->
<!-- ========================================== -->
<div id="syllabusModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col my-auto max-h-[90vh]">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center shrink-0">
                    <x-ui.icon name="file-text" class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                    <div class="text-sm sm:text-base font-bold text-slate-900">Course Syllabus Document</div>
                    <div class="text-xs text-slate-500">Upload or review official syllabus</div>
                </div>
            </div>
            <button type="button" onclick="closeSyllabusModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                <x-ui.icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <form id="syllabusForm" onsubmit="submitSyllabusForm(event)" class="p-5 space-y-4">
            @if(!empty($courseFile->syllabus_pdf_path))
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-emerald-800">
                        <x-ui.icon name="check-circle" class="w-4 h-4 text-emerald-600" />
                        <span class="font-medium">Current Syllabus Available</span>
                    </div>
                    <a href="{{ $courseFile->syllabus_pdf_path }}" target="_blank" class="font-bold text-emerald-700 hover:underline">
                        View PDF
                    </a>
                </div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Upload Syllabus PDF (Max 15MB)</label>
                <input type="file" id="syllabusFileInput" name="syllabus_file" accept=".pdf" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" required />
            </div>

            <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                <x-ui.button variant="secondary" size="md" type="button" onclick="closeSyllabusModal()">
                    <span>Cancel</span>
                </x-ui.button>
                <x-ui.button variant="primary" size="md" type="submit" id="btnUploadSyllabus">
                    <x-ui.icon name="upload" class="w-4 h-4 mr-1.5" />
                    <span>Upload PDF</span>
                </x-ui.button>
            </div>
        </form>
    </div>
</div>
