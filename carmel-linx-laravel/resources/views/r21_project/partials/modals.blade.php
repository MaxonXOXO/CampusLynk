<!-- 1. Individual Student CIA Evaluation Modal -->
<x-ui.modal id="ciaEvalModal" title="Student CIA Evaluation (Clause 11.2.5)" maxWidth="max-w-lg">
    <form id="ciaEvalForm" onsubmit="event.preventDefault(); saveCiaStudentEval();" class="space-y-4">
        <input type="hidden" id="ciaModalRegNo" name="reg_no">
        
        <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-3 flex items-center justify-between">
            <div>
                <h4 class="text-xs font-bold text-white" id="ciaModalStudentName">Student Name</h4>
                <div class="text-[10px] text-slate-400 font-mono" id="ciaModalStudentReg">REG_NO</div>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-slate-400 uppercase">Attendance</span>
                <div class="text-xs font-mono font-bold text-emerald-400" id="ciaModalAttPct">100.0%</div>
            </div>
        </div>

        <div class="space-y-3 text-xs">
            <div>
                <label class="block font-semibold text-slate-300 mb-1">Weekly Project Diary (Max 30 Marks)</label>
                <input 
                    type="number" 
                    step="0.1" 
                    min="0" 
                    max="30" 
                    id="ciaModalDiary" 
                    name="formative_diary_marks" 
                    oninput="computeLiveCiaTotals()" 
                    required
                    class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-slate-200 font-mono focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1">Department Level Review (Max 30 Marks)</label>
                <input 
                    type="number" 
                    step="0.1" 
                    min="0" 
                    max="30" 
                    id="ciaModalDept" 
                    name="summative_dept_marks" 
                    oninput="computeLiveCiaTotals()" 
                    required
                    class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-slate-200 font-mono focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="font-semibold text-slate-300">Attendance Marks (Max 15 Marks)</label>
                    <span class="text-[10px] text-sky-400 font-mono" id="ciaModalSuggestedAtt">Suggested: 15.0</span>
                </div>
                <input 
                    type="number" 
                    step="0.1" 
                    min="0" 
                    max="15" 
                    id="ciaModalAttd" 
                    name="attendance_marks" 
                    oninput="computeLiveCiaTotals()" 
                    required
                    class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-slate-200 font-mono focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
            </div>
        </div>

        <!-- Live Totals Summary -->
        <div class="p-3 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-between">
            <div>
                <div class="text-[10px] text-slate-400 uppercase font-semibold">CIA Total (75M)</div>
                <div class="text-base font-extrabold text-emerald-400 font-mono" id="ciaModalLiveTotal">0.0 / 75</div>
            </div>
            <div class="flex items-center gap-2">
                <x-ui.badge variant="neutral" id="ciaModalGradeBadge">-</x-ui.badge>
                <x-ui.badge variant="neutral" id="ciaModalPassBadge">Pending</x-ui.badge>
            </div>
        </div>
    </form>

    <x-slot:footer>
        <x-ui.button variant="secondary" size="sm" onclick="closeCiaEvalModal()">Cancel</x-ui.button>
        <x-ui.button variant="primary" size="sm" id="btnSaveCiaModal" onclick="saveCiaStudentEval()">Save CIA Evaluation</x-ui.button>
    </x-slot:footer>
</x-ui.modal>


<!-- 2. Group Common CIA Modal -->
<x-ui.modal id="groupCiaModal" title="Batch / Group Common CIA Evaluation" maxWidth="max-w-lg">
    <form id="groupCiaForm" onsubmit="event.preventDefault(); saveGroupCiaForm();" class="space-y-4">
        <input type="hidden" name="group_id" id="grpCiaModalGroupId">

        <div class="p-3 bg-amber-500/10 border border-amber-500/30 rounded-xl text-xs text-amber-300">
            Apply uniform Diary and Department Review marks to all students in <strong id="grpCiaModalTitle">Group</strong>. Attendance marks will be calculated individually.
        </div>

        <div class="space-y-3 text-xs">
            <div>
                <label class="block font-semibold text-slate-300 mb-1">Common Weekly Diary Marks (Max 30 Marks)</label>
                <input 
                    type="number" 
                    step="0.1" 
                    min="0" 
                    max="30" 
                    id="grpCiaDiary" 
                    name="formative_diary_marks" 
                    required
                    class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-slate-200 font-mono focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block font-semibold text-slate-300 mb-1">Common Department Review Marks (Max 30 Marks)</label>
                <input 
                    type="number" 
                    step="0.1" 
                    min="0" 
                    max="30" 
                    id="grpCiaDept" 
                    name="summative_dept_marks" 
                    required
                    class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-slate-200 font-mono focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
            </div>
        </div>
    </form>

    <x-slot:footer>
        <x-ui.button variant="secondary" size="sm" onclick="closeGroupCiaModal()">Cancel</x-ui.button>
        <x-ui.button variant="primary" size="sm" onclick="saveGroupCiaForm()">Apply to Group</x-ui.button>
    </x-slot:footer>
</x-ui.modal>


<!-- 3. Comprehensive Student Evaluation Modal (CIA + ESE) -->
<x-ui.modal id="evalModal" title="Student Comprehensive Evaluation (125 Marks)" maxWidth="max-w-2xl">
    <form id="evalForm" onsubmit="event.preventDefault(); saveStudentEval();" class="space-y-4">
        <input type="hidden" id="modalRegNo" name="reg_no">

        <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-3 flex items-center justify-between">
            <div>
                <h4 class="text-sm font-bold text-white" id="modalStudentName">Student Evaluation</h4>
                <div class="text-xs text-slate-400 font-mono" id="modalStudentReg">REG_NO</div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 font-mono font-semibold" id="modalGroupBadge">Group</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <!-- Left: CIA Section -->
            <div class="space-y-3 p-3 bg-slate-900/40 border border-slate-800 rounded-xl">
                <div class="flex items-center justify-between border-b border-slate-800 pb-1.5">
                    <span class="font-bold text-emerald-400 uppercase text-[11px]">CIA Evaluation (75M)</span>
                    <span class="font-mono text-emerald-300 font-bold" id="modalCiaSubTotal">0.0 / 75</span>
                </div>
                <div>
                    <label class="block text-slate-300 mb-1">Diary (30M)</label>
                    <input type="number" step="0.1" min="0" max="30" id="modalDiary" name="formative_diary_marks" oninput="computeLiveTotals()" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-xs text-white font-mono">
                </div>
                <div>
                    <label class="block text-slate-300 mb-1">Dept Review (30M)</label>
                    <input type="number" step="0.1" min="0" max="30" id="modalDept" name="summative_dept_marks" oninput="computeLiveTotals()" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-xs text-white font-mono">
                </div>
                <div>
                    <label class="block text-slate-300 mb-1">Attendance (15M)</label>
                    <input type="number" step="0.1" min="0" max="15" id="modalAttd" name="attendance_marks" oninput="computeLiveTotals()" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-xs text-white font-mono">
                </div>
            </div>

            <!-- Right: ESE Section -->
            <div class="space-y-3 p-3 bg-slate-900/40 border border-slate-800 rounded-xl">
                <div class="flex items-center justify-between border-b border-slate-800 pb-1.5">
                    <span class="font-bold text-sky-400 uppercase text-[11px]">ESE Evaluation (50M)</span>
                    <span class="font-mono text-sky-300 font-bold" id="modalEseSubTotal">0.0 / 50</span>
                </div>
                <div>
                    <label class="block text-slate-300 mb-1">Direct ESE Marks (50M)</label>
                    <input type="number" step="0.1" min="0" max="50" id="modalEseTotal" name="total_ese_50" oninput="onModalEseDirectInput()" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-xs text-white font-mono">
                </div>
                <div>
                    <label class="block text-slate-300 mb-1">ESE Grade</label>
                    <select id="modalEseGrade" name="ese_grade" onchange="onModalGradeSelect()" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-xs text-white font-mono font-bold">
                        <option value="">-- Select Grade --</option>
                        <option value="S">S (≥ 45.0 M)</option>
                        <option value="A+">A+ (42.5 – 44.9 M)</option>
                        <option value="A">A (40.0 – 42.4 M)</option>
                        <option value="B+">B+ (37.5 – 39.9 M)</option>
                        <option value="B">B (35.0 – 37.4 M)</option>
                        <option value="C+">C+ (32.5 – 34.9 M)</option>
                        <option value="C">C (30.0 – 32.4 M)</option>
                        <option value="D">D (25.0 – 29.9 M)</option>
                        <option value="F">F (&lt; 25.0 M)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 mb-1">Project Title</label>
                    <input type="text" id="modalProjectTitle" name="project_title" placeholder="Project title..." class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-xs text-white">
                </div>
            </div>
        </div>

        <!-- Grand Total Summary -->
        <div class="p-3 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-between">
            <div>
                <div class="text-[10px] text-slate-400 uppercase font-semibold">Grand Total (125 Marks)</div>
                <div class="text-lg font-extrabold text-blue-400 font-mono" id="modalGrandTotal">0.0 / 125</div>
            </div>
            <div class="flex items-center gap-2">
                <x-ui.badge variant="neutral" id="modalPassBadge">Pending</x-ui.badge>
            </div>
        </div>
    </form>

    <x-slot:footer>
        <x-ui.button variant="secondary" size="sm" onclick="closeEvalModal()">Cancel</x-ui.button>
        <x-ui.button variant="primary" size="sm" onclick="saveStudentEval()">Save Evaluation</x-ui.button>
    </x-slot:footer>
</x-ui.modal>


<!-- 4. Group ESE 8-Rubrics Modal -->
<x-ui.modal id="groupEseModal" title="Group ESE 8-Rubrics Assessment (50 Marks)" maxWidth="max-w-2xl">
    <form id="groupEseForm" onsubmit="event.preventDefault(); saveGroupEseForm();" class="space-y-4">
        <input type="hidden" name="group_id" id="grpEseModalGroupId">

        <div class="p-3 bg-sky-500/10 border border-sky-500/30 rounded-xl text-xs text-sky-300">
            Apply uniform ESE 8-Rubric scores to all students in <strong id="grpEseModalTitle">Group</strong>. Scores will be credited towards their official ESE mark.
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <label class="block text-slate-300 mb-1">Proto (10M)</label>
                <input type="number" step="0.1" min="0" max="10" id="grpEseProto" name="ese_prototype" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-white font-mono">
            </div>
            <div>
                <label class="block text-slate-300 mb-1">Tools (5M)</label>
                <input type="number" step="0.1" min="0" max="5" id="grpEseTools" name="ese_modern_tools" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-white font-mono">
            </div>
            <div>
                <label class="block text-slate-300 mb-1">Pres (7.5M)</label>
                <input type="number" step="0.1" min="0" max="7.5" id="grpEsePres" name="ese_presentation" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-white font-mono">
            </div>
            <div>
                <label class="block text-slate-300 mb-1">Inno (2.5M)</label>
                <input type="number" step="0.1" min="0" max="2.5" id="grpEseInno" name="ese_innovativeness" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-white font-mono">
            </div>
            <div>
                <label class="block text-slate-300 mb-1">Viva (7.5M)</label>
                <input type="number" step="0.1" min="0" max="7.5" id="grpEseViva" name="ese_viva" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-white font-mono">
            </div>
            <div>
                <label class="block text-slate-300 mb-1">Indiv (7.5M)</label>
                <input type="number" step="0.1" min="0" max="7.5" id="grpEseIndiv" name="ese_individual_contrib" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-white font-mono">
            </div>
            <div>
                <label class="block text-slate-300 mb-1">Grp (5M)</label>
                <input type="number" step="0.1" min="0" max="5" id="grpEseGrp" name="ese_group_activity" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-white font-mono">
            </div>
            <div>
                <label class="block text-slate-300 mb-1">Rep (5M)</label>
                <input type="number" step="0.1" min="0" max="5" id="grpEseRep" name="ese_project_report" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-white font-mono">
            </div>
        </div>
    </form>

    <x-slot:footer>
        <x-ui.button variant="secondary" size="sm" onclick="closeGroupEseModal()">Cancel</x-ui.button>
        <x-ui.button variant="primary" size="sm" onclick="saveGroupEseForm()">Apply to Group</x-ui.button>
    </x-slot:footer>
</x-ui.modal>


<!-- 5. ESE Examiners Panel Modal -->
<x-ui.modal id="examinersModal" title="ESE Examiners Panel (Clause 11.3.4)" maxWidth="max-w-xl">
    <form id="examinersForm" onsubmit="event.preventDefault(); saveExaminersForm();" class="space-y-4">
        <!-- Internal Examiner -->
        <div class="p-3 bg-slate-900/60 border border-slate-800 rounded-xl space-y-3">
            <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                <x-ui.icon name="user" class="w-3.5 h-3.5 text-emerald-400" />
                <span>Internal Examiner</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                <div>
                    <label class="block text-slate-300 mb-1">Examiner Name</label>
                    <input type="text" id="internalExaminerName" name="internal_name" value="{{ $examiners['internal_name'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
                </div>
                <div>
                    <label class="block text-slate-300 mb-1">Designation</label>
                    <input type="text" id="internalExaminerDesig" name="internal_designation" value="{{ $examiners['internal_designation'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
                </div>
            </div>
        </div>

        <!-- External Examiner -->
        <div class="p-3 bg-slate-900/60 border border-slate-800 rounded-xl space-y-3">
            <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                <x-ui.icon name="user-check" class="w-3.5 h-3.5 text-sky-400" />
                <span>External Examiner</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                <div>
                    <label class="block text-slate-300 mb-1">Examiner Name</label>
                    <input type="text" id="externalExaminerName" name="external_name" value="{{ $examiners['external_name'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
                </div>
                <div>
                    <label class="block text-slate-300 mb-1">Designation</label>
                    <input type="text" id="externalExaminerDesig" name="external_designation" value="{{ $examiners['external_designation'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
                </div>
                <div>
                    <label class="block text-slate-300 mb-1">Institution / College</label>
                    <input type="text" id="externalExaminerCollege" name="external_college" value="{{ $examiners['external_college'] ?? '' }}" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
                </div>
            </div>
        </div>

        <!-- Exam Date -->
        <div class="text-xs">
            <label class="block text-slate-300 mb-1">Date of ESE Project Examination</label>
            <input type="date" id="examinerExamDate" name="exam_date" value="{{ $examiners['exam_date'] ?? date('Y-m-d') }}" class="w-full max-w-xs bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-white">
        </div>
    </form>

    <x-slot:footer>
        <x-ui.button variant="secondary" size="sm" onclick="closeExaminersModal()">Cancel</x-ui.button>
        <x-ui.button variant="primary" size="sm" onclick="saveExaminersForm()">Save Examiners Panel</x-ui.button>
    </x-slot:footer>
</x-ui.modal>


<!-- 6. Delete Group Confirmation Modal -->
<x-ui.modal id="modalDeleteGroupConfirm" title="Delete Project Group" maxWidth="max-w-md">
    <div class="space-y-3">
        <input type="hidden" id="deleteGroupIndex">
        <p class="text-xs text-slate-300">
            Are you sure you want to delete this project group?
        </p>
        <p class="text-[11px] text-rose-400">
            All students assigned to this group will become unassigned. Any existing marks entered for individual students will be retained.
        </p>
    </div>

    <x-slot:footer>
        <x-ui.button variant="secondary" size="sm" onclick="closeDeleteModal()">Cancel</x-ui.button>
        <x-ui.button variant="danger" size="sm" onclick="confirmDeleteGroup()">Confirm Delete</x-ui.button>
    </x-slot:footer>
</x-ui.modal>
