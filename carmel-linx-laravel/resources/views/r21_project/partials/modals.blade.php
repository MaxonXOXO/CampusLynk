<!-- ========================================== -->
<!-- 1. Individual Student CIA Evaluation Modal -->
<!-- ========================================== -->
<div id="ciaEvalModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden flex flex-col my-auto max-h-[90vh]">
        <!-- Modal Header -->
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center shrink-0">
                    <x-ui.icon name="award" class="w-4 h-4 text-blue-600" />
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Student CIA Evaluation (Clause 11.2.5)</h3>
                    <div class="text-[11px] text-slate-500">Max 75 Marks Continuous Internal Assessment</div>
                </div>
            </div>
            <button type="button" onclick="closeCiaEvalModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                <x-ui.icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <form id="ciaEvalForm" onsubmit="event.preventDefault(); saveCiaStudentEval();" class="p-5 space-y-4 text-xs overflow-y-auto flex-grow">
            <input type="hidden" id="ciaModalRegNo" name="reg_no" value="">
            <input type="hidden" id="ciaModalGroupId" name="group_id" value="">
            
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-bold text-slate-900" id="ciaModalStudentName">Student Name</h4>
                    <div class="text-[10px] text-slate-500 font-mono" id="ciaModalStudentReg">REG_NO</div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-500 uppercase font-semibold">Attendance</span>
                    <div class="text-xs font-mono font-bold text-emerald-600" id="ciaModalAttPct">100.0%</div>
                </div>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Weekly Project Diary (Max 30 Marks)</label>
                    <input 
                        type="number" 
                        step="0.1" 
                        min="0" 
                        max="30" 
                        id="ciaModalDiary" 
                        name="formative_diary_marks" 
                        oninput="computeLiveCiaTotals()" 
                        required
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Department Level Review (Max 30 Marks)</label>
                    <input 
                        type="number" 
                        step="0.1" 
                        min="0" 
                        max="30" 
                        id="ciaModalDept" 
                        name="summative_dept_marks" 
                        oninput="computeLiveCiaTotals()" 
                        required
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                    >
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="font-semibold text-slate-700">Attendance Marks (Max 15 Marks)</label>
                        <span class="text-[10px] text-sky-600 font-mono font-semibold" id="ciaModalSuggestedAtt">Suggested: 15.0</span>
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
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                    >
                </div>
            </div>

            <!-- Live Totals Summary -->
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                <div>
                    <div class="text-[10px] text-slate-500 uppercase font-semibold">CIA Total (75M)</div>
                    <div class="text-base font-extrabold text-emerald-600 font-mono" id="ciaModalLiveTotal">0.0 / 75</div>
                    <span id="ciaModalSummaryMarks" class="hidden"></span>
                </div>
                <div class="flex items-center gap-2">
                    <span id="ciaModalGradeBadge" class="px-2 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">-</span>
                    <span id="ciaModalPassBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">Pending</span>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                <x-ui.button variant="secondary" size="md" type="button" onclick="closeCiaEvalModal()">
                    <span>Cancel</span>
                </x-ui.button>
                <x-ui.button variant="primary" size="md" type="submit" id="btnSaveCiaModal">
                    <x-ui.icon name="save" class="w-4 h-4 mr-1.5" />
                    <span>Save CIA Evaluation</span>
                </x-ui.button>
            </div>
        </form>
    </div>
</div>


<!-- ========================================== -->
<!-- 2. Group Common CIA Modal                  -->
<!-- ========================================== -->
<div id="groupCiaModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden flex flex-col my-auto max-h-[90vh]">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0">
                    <x-ui.icon name="users" class="w-4 h-4 text-emerald-600" />
                </div>
                <h3 class="text-sm font-bold text-slate-900">Group Common CIA: <span id="grpCiaModalTitle" class="text-emerald-600">Group</span></h3>
            </div>
            <button type="button" onclick="closeGroupCiaModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                <x-ui.icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <form id="groupCiaForm" onsubmit="event.preventDefault(); saveGroupCiaForm();" class="p-5 space-y-4 text-xs overflow-y-auto flex-grow">
            <input type="hidden" name="group_id" id="grpCiaModalGroupId" value="">

            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800">
                Apply uniform Diary and Department Review marks to all students in <strong id="grpCiaModalTitleDesc">this group</strong>. Attendance marks will be calculated individually.
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Common Weekly Diary Marks (Max 30 Marks)</label>
                    <input 
                        type="number" 
                        step="0.1" 
                        min="0" 
                        max="30" 
                        id="grp_cia_diary" 
                        name="formative_diary_marks" 
                        required
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                    >
                    <input type="hidden" id="grpCiaDiary" value="">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Common Department Review Marks (Max 30 Marks)</label>
                    <input 
                        type="number" 
                        step="0.1" 
                        min="0" 
                        max="30" 
                        id="grp_cia_dept" 
                        name="summative_dept_marks" 
                        required
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition"
                    >
                    <input type="hidden" id="grpCiaDept" value="">
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                <x-ui.button variant="secondary" size="md" type="button" onclick="closeGroupCiaModal()">
                    <span>Cancel</span>
                </x-ui.button>
                <x-ui.button variant="primary" size="md" type="submit">
                    <x-ui.icon name="check" class="w-4 h-4 mr-1.5" />
                    <span>Apply to Group</span>
                </x-ui.button>
            </div>
        </form>
    </div>
</div>


<!-- ========================================== -->
<!-- 3. Comprehensive Student Evaluation Modal  -->
<!-- Full Clause 11.2.5 (CIA 75M) + 11.3 (ESE 50M) -->
<!-- ========================================== -->
<div id="evalModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-3xl shadow-2xl overflow-hidden flex flex-col my-auto max-h-[94vh]">
        <!-- Header -->
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center shrink-0">
                    <x-ui.icon name="file-text" class="w-4 h-4 text-blue-600" />
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900" id="modalStudentName">Student Comprehensive Evaluation (125 Marks)</h3>
                    <div class="text-[11px] text-slate-500 flex items-center gap-2">
                        <span id="modalStudentReg" class="font-mono">REG_NO</span>
                        <span>&bull;</span>
                        <span id="modalGroupBadge" class="font-semibold text-slate-700">Group</span>
                    </div>
                </div>
            </div>
            <button type="button" onclick="closeEvalModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                <x-ui.icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <form id="evalForm" onsubmit="saveStudentEval(event)" class="flex flex-col overflow-hidden flex-grow">
            <input type="hidden" id="modalRegNo" name="reg_no" value="">
            <input type="hidden" id="modalGroupId" name="group_id" value="">

            <div class="p-4 sm:p-5 overflow-y-auto space-y-4">
                
                <!-- Project Title Input -->
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Approved Major Project Title</label>
                    <input type="text" id="modalProjectTitle" name="project_title" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:border-blue-500 outline-none transition" placeholder="Enter approved project title...">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Left: CIA Section (75 Marks) -->
                    <div class="space-y-3 p-3.5 bg-emerald-50/40 border border-emerald-200 rounded-xl">
                        <div class="flex items-center justify-between border-b border-emerald-200 pb-2">
                            <span class="font-bold text-emerald-800 uppercase text-[11px]">Part A: Continuous Internal Assessment (75M)</span>
                            <span class="font-mono text-emerald-700 font-bold" id="liveCiaTotal">0.0 / 75</span>
                            <span id="modalCiaSubTotal" class="hidden">0.0 / 75</span>
                        </div>
                        <div>
                            <label class="block text-slate-700 mb-1 font-semibold">Weekly Diary (30M)</label>
                            <input type="number" step="0.1" min="0" max="30" id="modalDiary" name="formative_diary_marks" oninput="computeLiveTotals()" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800 font-mono focus:border-blue-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-slate-700 mb-1 font-semibold">Dept Review (30M)</label>
                            <input type="number" step="0.1" min="0" max="30" id="modalDept" name="summative_dept_marks" oninput="computeLiveTotals()" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800 font-mono focus:border-blue-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-slate-700 mb-1 font-semibold">Attendance (15M)</label>
                            <input type="number" step="0.1" min="0" max="15" id="modalAttd" name="attendance_marks" oninput="computeLiveTotals()" class="w-full bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-800 font-mono focus:border-blue-500 outline-none" required>
                        </div>
                    </div>

                    <!-- Right: ESE Section (50 Marks) -->
                    <div class="space-y-3 p-3.5 bg-sky-50/40 border border-sky-200 rounded-xl">
                        <div class="flex items-center justify-between border-b border-sky-200 pb-2">
                            <span class="font-bold text-sky-800 uppercase text-[11px]">Part B: End Semester Exam Rubrics (50M)</span>
                            <span class="font-mono text-sky-700 font-bold" id="liveEseTotal">0.0 / 50</span>
                            <span id="modalEseSubTotal" class="hidden">0.0 / 50</span>
                        </div>

                        <!-- 8 ESE Rubrics in 4x2 mini-grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px]">
                            <div>
                                <label class="text-slate-600 block mb-0.5 truncate" title="Prototype / Model">Prototype (10)</label>
                                <input type="number" step="0.1" min="0" max="10" id="mProto" name="ese_prototype" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-slate-600 block mb-0.5 truncate" title="Modern Tools & Techniques">Tools (5)</label>
                                <input type="number" step="0.1" min="0" max="5" id="mTools" name="ese_modern_tools" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-slate-600 block mb-0.5 truncate" title="Presentation & Defense">Pres (7.5)</label>
                                <input type="number" step="0.1" min="0" max="7.5" id="mPres" name="ese_presentation" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-slate-600 block mb-0.5 truncate" title="Innovativeness & Novelty">Inno (2.5)</label>
                                <input type="number" step="0.1" min="0" max="2.5" id="mInnov" name="ese_innovativeness" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-slate-600 block mb-0.5 truncate" title="Viva Voce Defense">Viva (7.5)</label>
                                <input type="number" step="0.1" min="0" max="7.5" id="mViva" name="ese_viva" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-slate-600 block mb-0.5 truncate" title="Individual Contribution">Indiv (7.5)</label>
                                <input type="number" step="0.1" min="0" max="7.5" id="mIndiv" name="ese_individual_contrib" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-slate-600 block mb-0.5 truncate" title="Group Activity & Teamwork">Group (5)</label>
                                <input type="number" step="0.1" min="0" max="5" id="mGroup" name="ese_group_activity" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-slate-600 block mb-0.5 truncate" title="Project Report & Documentation">Report (5)</label>
                                <input type="number" step="0.1" min="0" max="5" id="mRep" name="ese_project_report" oninput="computeLiveTotals()" class="w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                            </div>
                        </div>

                        <!-- Direct ESE / Letter Grade Sync -->
                        <div class="grid grid-cols-2 gap-3 pt-2 border-t border-sky-200">
                            <div>
                                <label class="text-slate-700 block mb-1 font-semibold">Direct ESE Mark (50M)</label>
                                <input type="number" step="0.1" min="0" max="50" id="modalEseTotal" name="total_ese_50" oninput="onModalEseDirectInput()" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="text-slate-700 block mb-1 font-semibold">SBTE Grade</label>
                                <select id="modalEseGrade" name="ese_grade" onchange="onModalGradeSelect()" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 font-mono font-bold focus:border-blue-500 outline-none">
                                    <option value="">-- Select Grade --</option>
                                    <option value="S">S (&ge; 45.0 M)</option>
                                    <option value="A+">A+ (42.5 &ndash; 44.9 M)</option>
                                    <option value="A">A (40.0 &ndash; 42.4 M)</option>
                                    <option value="B+">B+ (37.5 &ndash; 39.9 M)</option>
                                    <option value="B">B (35.0 &ndash; 37.4 M)</option>
                                    <option value="C+">C+ (32.5 &ndash; 34.9 M)</option>
                                    <option value="C">C (30.0 &ndash; 32.4 M)</option>
                                    <option value="D">D (25.0 &ndash; 29.9 M)</option>
                                    <option value="F">F (&lt; 25.0 M)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grand Total Summary Strip -->
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase font-semibold">Grand Total (125 Marks)</div>
                        <div class="text-lg font-black text-blue-700 font-mono" id="modalGrandTotal">0.0 / 125</div>
                    </div>
                    <div>
                        <span id="modalPassBadge" class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">Pending</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2 shrink-0">
                <x-ui.button variant="secondary" size="md" type="button" onclick="closeEvalModal()">
                    <span>Cancel</span>
                </x-ui.button>
                <x-ui.button variant="primary" size="md" type="submit">
                    <x-ui.icon name="save" class="w-4 h-4 mr-1.5" />
                    <span>Save Evaluation</span>
                </x-ui.button>
            </div>
        </form>
    </div>
</div>


<!-- ========================================== -->
<!-- 4. Group ESE 8-Rubrics Modal               -->
<!-- ========================================== -->
<div id="groupEseModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden flex flex-col my-auto max-h-[90vh]">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0">
                    <x-ui.icon name="users" class="w-4 h-4 text-amber-600" />
                </div>
                <h3 class="text-sm font-bold text-slate-900">Group ESE Assessment: <span id="grpEseModalTitle" class="text-amber-600">Group</span></h3>
            </div>
            <button type="button" onclick="closeGroupEseModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                <x-ui.icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <form id="groupEseForm" onsubmit="event.preventDefault(); saveGroupEseForm();" class="p-5 space-y-4 text-xs overflow-y-auto flex-grow">
            <input type="hidden" name="group_id" id="grpEseModalGroupId" value="">

            <div class="p-3 bg-sky-50 border border-sky-200 rounded-xl text-xs text-sky-800">
                Award the 8 statutory ESE rubrics (Max 50M) uniformly to all students in <strong id="grpEseModalTitleDesc">this group</strong>.
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Proto (10M)</label>
                    <input type="number" step="0.1" min="0" max="10" id="grp_ese_prototype" name="ese_prototype" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                    <input type="hidden" id="grpEseProto" value="">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Tools (5M)</label>
                    <input type="number" step="0.1" min="0" max="5" id="grp_ese_modern_tools" name="ese_modern_tools" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                    <input type="hidden" id="grpEseTools" value="">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Pres (7.5M)</label>
                    <input type="number" step="0.1" min="0" max="7.5" id="grp_ese_presentation" name="ese_presentation" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                    <input type="hidden" id="grpEsePres" value="">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Inno (2.5M)</label>
                    <input type="number" step="0.1" min="0" max="2.5" id="grp_ese_innovativeness" name="ese_innovativeness" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                    <input type="hidden" id="grpEseInno" value="">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Viva (7.5M)</label>
                    <input type="number" step="0.1" min="0" max="7.5" id="grp_ese_viva" name="ese_viva" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                    <input type="hidden" id="grpEseViva" value="">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Indiv (7.5M)</label>
                    <input type="number" step="0.1" min="0" max="7.5" id="grp_ese_individual_contrib" name="ese_individual_contrib" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                    <input type="hidden" id="grpEseIndiv" value="">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Grp (5M)</label>
                    <input type="number" step="0.1" min="0" max="5" id="grp_ese_group_activity" name="ese_group_activity" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                    <input type="hidden" id="grpEseGrp" value="">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold mb-1">Rep (5M)</label>
                    <input type="number" step="0.1" min="0" max="5" id="grp_ese_project_report" name="ese_project_report" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-mono focus:border-blue-500 outline-none">
                    <input type="hidden" id="grpEseRep" value="">
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                <x-ui.button variant="secondary" size="md" type="button" onclick="closeGroupEseModal()">
                    <span>Cancel</span>
                </x-ui.button>
                <x-ui.button variant="primary" size="md" type="submit">
                    <x-ui.icon name="check" class="w-4 h-4 mr-1.5" />
                    <span>Apply to Group</span>
                </x-ui.button>
            </div>
        </form>
    </div>
</div>


<!-- ========================================== -->
<!-- 5. ESE Examiners Panel Modal (Clause 11.3.4) -->
<!-- ========================================== -->
<div id="examinersModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-xl shadow-2xl overflow-hidden flex flex-col my-auto max-h-[90vh]">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center shrink-0">
                    <x-ui.icon name="user-check" class="w-4 h-4 text-blue-600" />
                </div>
                <h3 class="text-sm font-bold text-slate-900">ESE Examiners Panel (Clause 11.3.4)</h3>
            </div>
            <button type="button" onclick="closeExaminersModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                <x-ui.icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <form id="examinersForm" onsubmit="event.preventDefault(); saveExaminersForm();" class="p-5 space-y-4 text-xs overflow-y-auto flex-grow">
            <!-- Internal Examiner -->
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                    <x-ui.icon name="user" class="w-3.5 h-3.5 text-blue-600" />
                    <span>Internal Examiner</span>
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-700 mb-1 font-semibold">Examiner Name</label>
                        <input type="text" id="ex_internal_name" name="internal_name" value="{{ $examiners['internal_name'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800">
                        <input type="hidden" id="internalExaminerName" value="">
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1 font-semibold">Designation</label>
                        <input type="text" id="ex_internal_designation" name="internal_designation" value="{{ $examiners['internal_designation'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800">
                        <input type="hidden" id="internalExaminerDesig" value="">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-slate-700 mb-1 font-semibold">Institution / College</label>
                        <input type="text" id="ex_internal_college" name="internal_college" value="{{ $examiners['internal_college'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800">
                    </div>
                </div>
            </div>

            <!-- External Examiner -->
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                    <x-ui.icon name="user-check" class="w-3.5 h-3.5 text-sky-600" />
                    <span>External Examiner</span>
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-slate-700 mb-1 font-semibold">Examiner Name</label>
                        <input type="text" id="ex_external_name" name="external_name" value="{{ $examiners['external_name'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800">
                        <input type="hidden" id="externalExaminerName" value="">
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1 font-semibold">Designation</label>
                        <input type="text" id="ex_external_designation" name="external_designation" value="{{ $examiners['external_designation'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800">
                        <input type="hidden" id="externalExaminerDesig" value="">
                    </div>
                    <div>
                        <label class="block text-slate-700 mb-1 font-semibold">Institution / College</label>
                        <input type="text" id="ex_external_college" name="external_college" value="{{ $examiners['external_college'] ?? '' }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800">
                        <input type="hidden" id="externalExaminerCollege" value="">
                    </div>
                </div>
            </div>

            <!-- Exam Date -->
            <div>
                <label class="block text-slate-700 mb-1 font-semibold">Date of ESE Project Examination</label>
                <input type="date" id="ex_exam_date" name="exam_date" value="{{ $examiners['exam_date'] ?? date('Y-m-d') }}" class="w-full max-w-xs bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 font-mono">
                <input type="hidden" id="examinerExamDate" value="">
            </div>

            <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                <x-ui.button variant="secondary" size="md" type="button" onclick="closeExaminersModal()">
                    <span>Cancel</span>
                </x-ui.button>
                <x-ui.button variant="primary" size="md" type="submit">
                    <x-ui.icon name="save" class="w-4 h-4 mr-1.5" />
                    <span>Save Examiners Panel</span>
                </x-ui.button>
            </div>
        </form>
    </div>
</div>


<!-- ========================================== -->
<!-- 6. Delete Group Confirmation Modal         -->
<!-- ========================================== -->
<div id="modalDeleteGroupConfirm" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col my-auto max-h-[90vh]">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between shrink-0">
            <h3 class="text-sm font-bold text-slate-900">Delete Project Group</h3>
            <button type="button" onclick="closeDeleteModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                <x-ui.icon name="x" class="w-5 h-5" />
            </button>
        </div>

        <div class="p-5 space-y-3">
            <input type="hidden" id="deleteGroupIndex">
            <p class="text-xs text-slate-700">
                Are you sure you want to delete this project group?
            </p>
            <p class="text-[11px] text-rose-600 bg-rose-50 p-2.5 rounded-xl border border-rose-200">
                All students assigned to this group will become unassigned. Any existing marks entered for individual students will be retained.
            </p>
        </div>

        <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2 shrink-0">
            <x-ui.button variant="secondary" size="md" type="button" onclick="closeDeleteModal()">
                <span>Cancel</span>
            </x-ui.button>
            <x-ui.button variant="danger" size="md" type="button" onclick="confirmDeleteGroup()">
                <x-ui.icon name="trash" class="w-4 h-4 mr-1.5" />
                <span>Confirm Delete</span>
            </x-ui.button>
        </div>
    </div>
</div>
