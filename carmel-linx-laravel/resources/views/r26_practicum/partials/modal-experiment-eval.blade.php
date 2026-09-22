    <div id="experiment-eval-modal" class="fixed inset-0 z-50 bg-slate-50 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white max-w-2xl w-full rounded-2xl border border-slate-200 shadow-2xl max-h-[95vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Continuous Lab Work Evaluator (Table 2.2)</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Grade the student on 6 criteria. Total out of 50, scaled to 10 CIA marks.</p>
                    </div>
                </div>
                <button onclick="closeExperimentEvalModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all leading-none">&times;</button>
            </div>

            <!-- Experiment & Student Selection -->
            <div class="bg-slate-50 px-5 py-3 border-b border-slate-100 space-y-2.5 flex-shrink-0">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                    <label class="text-slate-600 text-xs font-bold whitespace-nowrap">Select Experiment:</label>
                    <select id="eval-exp-select" onchange="onEvalExpChange(this.value)" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 font-bold text-xs text-slate-900 outline-none w-full sm:max-w-sm focus:border-purple-500 shadow-2xs cursor-pointer">
                        @foreach(($practicumCourseFile->parsed_experiments ?? []) as $exp)
                        <option value="{{ $exp['experiment_no'] }}">{{ $exp['experiment_no'] }} - {{ $exp['title'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <button type="button" onclick="prevExpStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                        <span>◀ Prev</span>
                    </button>
                    <div class="flex-1">
                        <select id="eval-student-select" onchange="loadExpStudent(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold text-sm text-slate-900 outline-none focus:border-purple-500 shadow-2xs cursor-pointer">
                            @foreach($studentResults as $idx => $res)
                            <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" onclick="nextExpStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                        <span>Next ▶</span>
                    </button>
                </div>
            </div>

            <!-- Rubrics Form Card -->
            <div class="overflow-y-auto flex-1 px-5 py-4" id="exp-rubrics-container">
                <!-- Javascript will populate criterion rows here -->
            </div>

            <!-- Live Converted Result Display -->
            <div class="bg-slate-50 px-5 py-3 border-t border-slate-100 flex justify-between items-center text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-500 font-semibold block text-xs uppercase tracking-wider">Total Evaluation Score</span>
                    <span id="exp-live-total" class="font-bold text-slate-900 text-base font-mono">0.00 / 50.00 M</span>
                </div>
                <div class="text-right">
                    <span class="text-slate-500 font-semibold block text-xs uppercase tracking-wider">Scaled CIA Marks</span>
                    <span id="exp-live-cia" class="font-black text-emerald-700 text-base px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-200 font-mono">0.00 / 10.00 M</span>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100 flex-shrink-0">
                <button type="button" onclick="closeExperimentEvalModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors cursor-pointer">Close</button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="saveAndNextExpStudent()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 font-bold text-xs transition-colors shadow-2xs cursor-pointer">Next Student ▶</button>
                    <button type="button" onclick="saveAllExpMarks()" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>
 
    <!-- ================================================================
         Practical Series Exam Evaluation Modal — CampusLynk Light Theme
    ================================================================= -->
