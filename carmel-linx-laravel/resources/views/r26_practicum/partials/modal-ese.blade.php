    <div id="ese-practical-modal" class="fixed inset-0 z-50 bg-slate-50 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white max-w-3xl w-full rounded-2xl border border-slate-200 shadow-2xl max-h-[92vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Institutional Practical ESE Evaluator</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Adjust rubric sliders for procedure, setup, result, viva, and record.</p>
                    </div>
                </div>
                <button onclick="closeEsePracticalModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all">&times;</button>
            </div>

            <!-- Student Selection & Stepper Bar -->
            <div class="bg-slate-50 px-5 py-3 border-b border-slate-100 flex items-center justify-between gap-2.5 flex-shrink-0">
                <button type="button" onclick="prevEseStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                    <span>◀ Prev</span>
                </button>
                <div class="flex-1 max-w-md">
                    <select id="ese-student-select" onchange="loadEseStudent(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold text-sm text-slate-900 outline-none focus:border-blue-500 shadow-2xs cursor-pointer">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="nextEseStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                    <span>Next ▶</span>
                </button>
            </div>

            <!-- Live Score Summary Card -->
            <div class="bg-blue-50/70 px-5 py-3 border-b border-blue-100 flex items-center justify-between text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-600 font-bold">Practical ESE Score:</span>
                    <span id="ese-student-total-raw" class="font-extrabold text-blue-700 text-sm ml-1.5 font-mono">0.00 / 40.00 Marks</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-600 font-bold">Evaluated Grade:</span>
                    <span id="ese-student-grade-badge" class="font-black text-blue-700 text-sm px-3 py-1 rounded-full bg-blue-100 border border-blue-200 font-mono">S</span>
                </div>
            </div>

            <!-- Scrollable Rubric Sliders Container -->
            <div id="ese-sliders-container" class="overflow-y-auto space-y-3 flex-1 px-5 py-4 pr-4">
                <!-- Dynamically populated by JS loadEseStudent() -->
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100 flex-shrink-0">
                <button type="button" onclick="closeEsePracticalModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors cursor-pointer">Close</button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="saveAndNextEseStudent()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 font-bold text-xs transition-colors shadow-2xs cursor-pointer">Next Student ▶</button>
                    <button type="button" onclick="saveAllEseMarks()" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">Save All ESE Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Board Theory ESE Grade Entry Modal -->

    <div id="ese-theory-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm max-w-2xl w-full p-5 rounded-2xl border border-slate-200 shadow-2xl space-y-4 max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-200 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-white">End Semester Exam (ESE) Theory Grade Evaluator</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Select ESE Grade for each student. Mapped score and status will update automatically.</p>
                </div>
                <button onclick="closeEseTheoryModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Student Selection & Stepper Bar -->
            <div class="bg-white/90 p-3 rounded-xl border border-slate-200 flex items-center justify-between gap-2 flex-shrink-0">
                <button type="button" onclick="prevEseStudent()" class="header-btn px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 font-bold text-xs flex items-center space-x-1">
                    <span>◀ Prev</span>
                </button>

                <div class="flex-1 max-w-md">
                    <select id="ese-student-select" onchange="loadEseStudent(this.value)" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 font-bold text-xs text-white outline-none focus:border-emerald-500">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="nextEseStudent()" class="header-btn px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 font-bold text-xs flex items-center space-x-1">
                    <span>Next ▶</span>
                </button>
            </div>

            <!-- Grading Content Card -->
            <div class="p-4 rounded-xl bg-white/90 border border-slate-200 space-y-4 flex-1 overflow-y-auto">
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-xs font-semibold">Reg No:</span>
                    <span id="ese-student-reg" class="font-mono text-xs font-bold text-slate-800"></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400 text-xs font-semibold">Student Name:</span>
                    <span id="ese-student-name" class="text-xs font-bold text-white"></span>
                </div>

                <div class="border-t border-slate-200/80 pt-3 space-y-3">
                    <div>
                        <label class="block text-slate-400 text-xs font-semibold mb-1">Theory ESE Grade:</label>
                        <select id="ese-grade-select" onchange="onEseGradeChange(this.value)" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 font-bold text-sm text-amber-600 outline-none">
                            <option value="">- Select Grade -</option>
                            <option value="S">S (90% - 100%)</option>
                            <option value="A">A (80% - 89%)</option>
                            <option value="B">B (70% - 79%)</option>
                            <option value="C">C (60% - 69%)</option>
                            <option value="D">D (50% - 59%)</option>
                            <option value="P">P (40% - 49% - Pass)</option>
                            <option value="F">F (Fail)</option>
                            <option value="FE">FE (Absent / Shortage)</option>
                            <option value="I">I (Incomplete)</option>
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="ese-absent-check" onchange="toggleEseAbsent(this.checked)" class="rounded bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 border-slate-200 text-rose-500 focus:ring-0">
                        <label for="ese-absent-check" class="text-xs text-slate-700 font-semibold cursor-pointer">Mark Student as Absent (Grade FE)</label>
                    </div>
                </div>

                <!-- Live Conversion Display -->
                <div class="bg-white p-3 rounded-xl border border-slate-200 space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Equivalent Marks:</span>
                        <span id="ese-mapped-score" class="font-bold text-indigo-600">0.00 / 60.00</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Theory Pass Status:</span>
                        <span id="ese-pass-status" class="font-bold text-rose-600">REAPPEAR</span>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-200 flex-shrink-0">
                <button type="button" onclick="closeEseTheoryModal()" class="header-btn px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextEseStudent()" class="header-btn px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Next Student ▶</button>
                    <button type="button" onclick="saveAllEseGrades()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm">Save ESE Grades</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================
         Enter Theory Series Marks Modal
    ================================================================= -->
