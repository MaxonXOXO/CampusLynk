<!-- ========================================== -->
<!-- MODAL 1: Individual Seminar Evaluation Modal -->
<!-- ========================================== -->
<x-ui.modal id="evaluationModal" title="Seminar Presentation Evaluation (Clause 11.2.6)" maxWidth="max-w-2xl">
    <form id="evaluationForm" onsubmit="submitEvaluation(event)" class="space-y-4">
        <input type="hidden" id="evalRegNo" name="reg_no" value="">

        <!-- Student Info Strip -->
        <div class="p-3 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-between">
            <div>
                <div class="text-sm font-bold text-slate-900" id="evalStudentName">Student Name</div>
                <div class="text-xs text-slate-500 font-mono" id="evalStudentReg">Reg No: —</div>
            </div>
            <div class="text-right">
                <div class="text-xs text-slate-500 uppercase font-semibold">Total Score</div>
                <div class="text-xl font-extrabold text-blue-600" id="evalLiveTotal">0.0 / 75</div>
            </div>
        </div>

        <!-- Seminar Topic & Guide -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Approved Seminar Topic</label>
                <x-ui.input type="text" id="evalTopic" name="topic" placeholder="e.g. Edge AI in Renewable Energy" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Faculty Guide</label>
                <x-ui.select id="evalGuideMobile" name="guide_mobile_no">
                    <option value="">-- Select Faculty Guide --</option>
                    @foreach($guides as $g)
                        <option value="{{ $g->mobile_no }}">{{ $g->name }} ({{ $g->designation }})</option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>

        <!-- 6 Statutory Rubrics Grid -->
        <div class="border border-slate-200 rounded-xl p-3.5 space-y-3 bg-slate-50/50">
            <div class="text-xs font-bold text-slate-800 uppercase tracking-wider">Statutory Evaluation Rubrics (75M)</div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <!-- 1. Relevance (7.5M) -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1 flex justify-between">
                        <span>1. Relevance</span>
                        <span class="text-blue-600 font-bold">Max 7.5</span>
                    </label>
                    <x-ui.input type="number" step="0.5" min="0" max="7.5" id="evalRelevance" name="relevance" value="0" oninput="calculateLiveScore()" required />
                </div>

                <!-- 2. Literature (7.5M) -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1 flex justify-between">
                        <span>2. Literature</span>
                        <span class="text-blue-600 font-bold">Max 7.5</span>
                    </label>
                    <x-ui.input type="number" step="0.5" min="0" max="7.5" id="evalLiterature" name="literature" value="0" oninput="calculateLiveScore()" required />
                </div>

                <!-- 3. Presentation (37.5M) -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1 flex justify-between">
                        <span>3. Presentation</span>
                        <span class="text-emerald-600 font-bold">Max 37.5</span>
                    </label>
                    <x-ui.input type="number" step="0.5" min="0" max="37.5" id="evalPresentation" name="presentation" value="0" oninput="calculateLiveScore()" required />
                </div>

                <!-- 4. Discussion (7.5M) -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1 flex justify-between">
                        <span>4. Discussion</span>
                        <span class="text-blue-600 font-bold">Max 7.5</span>
                    </label>
                    <x-ui.input type="number" step="0.5" min="0" max="7.5" id="evalInteraction" name="interaction" value="0" oninput="calculateLiveScore()" required />
                </div>

                <!-- 5. Report (7.5M) -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1 flex justify-between">
                        <span>5. Report</span>
                        <span class="text-blue-600 font-bold">Max 7.5</span>
                    </label>
                    <x-ui.input type="number" step="0.5" min="0" max="7.5" id="evalReport" name="report" value="0" oninput="calculateLiveScore()" required />
                </div>

                <!-- 6. Attendance (7.5M) -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1 flex justify-between">
                        <span>6. Attendance</span>
                        <span class="text-amber-600 font-bold">Max 7.5</span>
                    </label>
                    <x-ui.input type="number" step="0.5" min="0" max="7.5" id="evalAttendance" name="attendance" value="0" oninput="calculateLiveScore()" required />
                    <div class="text-[10px] text-slate-500 mt-1" id="evalSuggestedAtt">Suggested: 7.5M (>=90%)</div>
                </div>
            </div>
        </div>

        <!-- Assessor Selector -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Evaluating Assessor</label>
            <x-ui.select id="evalAssessorMobile" name="assessor_mobile_no">
                @foreach($guides as $g)
                    <option value="{{ $g->mobile_no }}" {{ ($activeStaff && $activeStaff->mobile_no === $g->mobile_no) ? 'selected' : '' }}>
                        {{ $g->name }} ({{ $g->designation }})
                    </option>
                @endforeach
            </x-ui.select>
        </div>

        <x-slot:footer>
            <x-ui.button variant="secondary" size="md" type="button" onclick="document.getElementById('evaluationModal').classList.add('hidden')">
                <span>Cancel</span>
            </x-ui.button>
            <x-ui.button variant="primary" size="md" type="submit" id="btnSaveEvaluation">
                <span>Save Evaluation</span>
            </x-ui.button>
        </x-slot:footer>
    </form>
</x-ui.modal>

<!-- ========================================== -->
<!-- MODAL 2: Seminar Schedule Modal -->
<!-- ========================================== -->
<x-ui.modal id="scheduleModal" title="Update Seminar Schedule &amp; Topic" maxWidth="max-w-md">
    <form id="scheduleForm" onsubmit="submitSchedule(event)" class="space-y-4">
        <input type="hidden" id="schedRegNo" name="reg_no" value="">

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Student</label>
            <div class="text-sm font-bold text-slate-900" id="schedStudentName">Student Name</div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Seminar Topic</label>
            <x-ui.input type="text" id="schedTopic" name="topic" placeholder="Enter approved seminar presentation topic" required />
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Presentation Date</label>
            <x-ui.input type="date" id="schedDate" name="presentation_date" />
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Faculty Guide</label>
            <x-ui.select id="schedGuideMobile" name="guide_mobile_no">
                <option value="">-- Select Faculty Guide --</option>
                @foreach($guides as $g)
                    <option value="{{ $g->mobile_no }}">{{ $g->name }} ({{ $g->designation }})</option>
                @endforeach
            </x-ui.select>
        </div>

        <x-slot:footer>
            <x-ui.button variant="secondary" size="md" type="button" onclick="document.getElementById('scheduleModal').classList.add('hidden')">
                <span>Cancel</span>
            </x-ui.button>
            <x-ui.button variant="primary" size="md" type="submit" id="btnSaveSchedule">
                <span>Save Schedule</span>
            </x-ui.button>
        </x-slot:footer>
    </form>
</x-ui.modal>

<!-- ========================================== -->
<!-- MODAL 3: Committee Assessor Breakdown Modal -->
<!-- ========================================== -->
<x-ui.modal id="breakdownModal" title="Committee Evaluation Breakdown" maxWidth="max-w-xl">
    <div class="space-y-3">
        <div class="text-xs text-slate-500" id="breakdownStudentInfo">
            Student: <strong class="text-slate-900" id="breakdownStudentName">—</strong>
        </div>

        <div id="breakdownTableContainer">
            <!-- Dynamic committee breakdown rows inserted by JS -->
        </div>

        <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-between text-xs">
            <span class="font-semibold text-blue-900">Official Committee Averaged Score (CIA):</span>
            <span class="text-base font-extrabold text-blue-700 font-mono" id="breakdownFinalScore">0.0 / 75</span>
        </div>
    </div>

    <x-slot:footer>
        <x-ui.button variant="secondary" size="md" type="button" onclick="document.getElementById('breakdownModal').classList.add('hidden')">
            <span>Close</span>
        </x-ui.button>
    </x-slot:footer>
</x-ui.modal>

<!-- ========================================== -->
<!-- MODAL 4: Syllabus Upload Modal -->
<!-- ========================================== -->
<x-ui.modal id="syllabusModal" title="Course Syllabus Document" maxWidth="max-w-md">
    <form id="syllabusForm" onsubmit="submitSyllabus(event)" class="space-y-4">
        @if(!empty($courseFile->syllabus_pdf_path))
            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 text-emerald-800">
                    <x-ui.icon name="check-circle" class="w-4 h-4 text-emerald-600" />
                    <span>Current Syllabus Uploaded</span>
                </div>
                <a href="{{ $courseFile->syllabus_pdf_path }}" target="_blank" class="font-bold text-emerald-700 underline">
                    View PDF
                </a>
            </div>
        @endif

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Upload Syllabus PDF (Max 15MB)</label>
            <input type="file" id="syllabusFileInput" name="syllabus_file" accept=".pdf" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required />
        </div>

        <x-slot:footer>
            <x-ui.button variant="secondary" size="md" type="button" onclick="document.getElementById('syllabusModal').classList.add('hidden')">
                <span>Cancel</span>
            </x-ui.button>
            <x-ui.button variant="primary" size="md" type="submit" id="btnUploadSyllabus">
                <span>Upload PDF</span>
            </x-ui.button>
        </x-slot:footer>
    </form>
</x-ui.modal>
