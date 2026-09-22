    <div id="series-theory-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm max-w-2xl w-full p-5 rounded-2xl border border-slate-200 shadow-2xl space-y-4 max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-200 pb-3 flex-shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-white">Theory Series Exam Marks Evaluator</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Select a series test and enter Part A, B, and C scores for each student.</p>
                </div>
                <button onclick="closeSeriesTheoryModal()" class="text-slate-400 hover:text-white text-2xl font-bold leading-none">&times;</button>
            </div>

            <!-- Series Selection & Student Selection Bar -->
            <div class="bg-white/90 p-3 rounded-xl border border-slate-200 space-y-3 flex-shrink-0">
                <div class="flex items-center justify-between gap-3">
                    <label class="text-slate-700 text-xs font-semibold">Select Series Test:</label>
                    <select id="series-theory-test-select" onchange="onSeriesTheoryTestChange(this.value)" class="bg-white border border-slate-200 rounded px-2.5 py-1 text-xs text-amber-600 font-bold outline-none focus:border-amber-500">
                        <option value="Series 1">Test 1 (CO1)</option>
                        <option value="Series 2">Test 2 (CO2)</option>
                        <option value="Series 3">Test 3 (CO3)</option>
                        <option value="Series 4">Test 4 (CO4)</option>
                    </select>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <button type="button" onclick="prevSeriesTheoryStudent()" class="header-btn px-3 py-1.5 rounded bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 font-bold text-xs">
                        <span>◀ Prev</span>
                    </button>

                    <div class="flex-1">
                        <select id="series-theory-student-select" onchange="loadSeriesTheoryStudent(this.value)" class="w-full bg-white border border-slate-200 rounded px-3 py-1.5 font-bold text-xs text-white outline-none focus:border-emerald-500">
                            @foreach($studentResults as $idx => $res)
                            <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" onclick="nextSeriesTheoryStudent()" class="header-btn px-3 py-1.5 rounded bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 font-bold text-xs">
                        <span>Next ▶</span>
                    </button>
                </div>
            </div>

            <!-- Marks Form Card -->
            <div class="p-4 rounded-xl bg-white/90 border border-slate-200 space-y-4 flex-1 overflow-y-auto">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-400">Student:</span>
                    <span id="series-theory-student-display" class="font-bold text-white"></span>
                </div>

                <div class="border-t border-slate-200/80 pt-3 space-y-3">
                    <div>
                        <label class="block text-slate-400 text-xs font-semibold mb-1">Total Series Test Mark (Max 50):</label>
                        <input type="number" id="series-theory-total" min="0" max="50" step="0.5" oninput="onSeriesTheoryMarksInput()" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 font-bold text-sm text-white text-center focus:border-emerald-500 outline-none">
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="series-theory-absent" onchange="toggleSeriesTheoryAbsent(this.checked)" class="rounded bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 border-slate-200 text-rose-500 focus:ring-0">
                        <label for="series-theory-absent" class="text-xs text-slate-600 font-semibold cursor-pointer">Mark Student as Absent</label>
                    </div>
                </div>

                <!-- Live Total Display -->
                <div class="bg-white p-3 rounded-xl border border-slate-200 flex justify-between items-center text-xs">
                    <span class="text-slate-400 font-semibold">Total Series Test Score:</span>
                    <span id="series-theory-live-total" class="font-bold text-emerald-600 text-sm">0.00 / 50.00</span>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-200 flex-shrink-0">
                <button type="button" onclick="closeSeriesTheoryModal()" class="header-btn px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">Close</button>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="saveAndNextSeriesTheoryStudent()" class="header-btn px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-sm">Next Student ▶</button>
                    <button type="button" onclick="saveAllSeriesTheoryMarks()" class="header-btn px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-sm">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================
         Continuous Lab Experiment Evaluation Modal — CampusLynk Light Theme
    ================================================================= -->

    <div id="series-practical-modal" class="fixed inset-0 z-50 bg-slate-50 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white max-w-2xl w-full rounded-2xl border border-slate-200 shadow-2xl max-h-[95vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Practical Series Test Marks Evaluator (Table 3.1)</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Grade on 5 practical criteria. Total out of 40, scaled to 10 CIA marks.</p>
                    </div>
                </div>
                <button onclick="closeSeriesPracticalModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all leading-none">&times;</button>
            </div>

            <!-- Series and Student Selection -->
            <div class="bg-slate-50 px-5 py-3 border-b border-slate-100 space-y-2.5 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <label class="text-slate-600 text-xs font-bold whitespace-nowrap">Select Series Test:</label>
                    <select id="series-pr-test-select" onchange="onSeriesPrTestChange(this.value)" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-900 font-bold outline-none focus:border-amber-500 shadow-2xs cursor-pointer">
                        <option value="Series 1">Practical Test 1 (CO1+CO2)</option>
                        <option value="Series 2">Practical Test 2 (CO3+CO4)</option>
                    </select>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <button type="button" onclick="prevSeriesPrStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                        <span>◀ Prev</span>
                    </button>
                    <div class="flex-1">
                        <select id="series-pr-student-select" onchange="loadSeriesPrStudent(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold text-sm text-slate-900 outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                            @foreach($studentResults as $idx => $res)
                            <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" onclick="nextSeriesPrStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                        <span>Next ▶</span>
                    </button>
                </div>
            </div>

            <!-- Rubrics Form Card -->
            <div class="overflow-y-auto flex-1 px-5 py-4" id="series-pr-rubrics-container">
                <!-- Javascript will populate criterion rows here -->
            </div>

            <!-- Live Converted Result Display -->
            <div class="bg-slate-50 px-5 py-3 border-t border-slate-100 flex justify-between items-center text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-500 font-semibold block text-xs uppercase tracking-wider">Total Exam Score</span>
                    <span id="series-pr-live-total" class="font-bold text-slate-900 text-base font-mono">0.00 / 40.00 M</span>
                </div>
                <div class="text-right">
                    <span class="text-slate-500 font-semibold block text-xs uppercase tracking-wider">Scaled CIA Marks</span>
                    <span id="series-pr-live-cia" class="font-black text-indigo-700 text-base px-3 py-1 rounded-lg bg-indigo-50 border border-indigo-200 font-mono">0.00 / 10.00 M</span>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100 flex-shrink-0">
                <button type="button" onclick="closeSeriesPracticalModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors cursor-pointer">Close</button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="saveAndNextSeriesPrStudent()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 font-bold text-xs transition-colors shadow-2xs cursor-pointer">Next Student ▶</button>
                    <button type="button" onclick="saveAllSeriesPrMarks()" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>
 
