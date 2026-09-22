    <div id="sl-config-modal" class="fixed inset-0 z-50 bg-slate-50 backdrop-blur-xs flex items-center justify-center hidden p-4">
        <div class="bg-white max-w-2xl w-full p-6 rounded-2xl border border-slate-200 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    <h3 class="text-base font-bold text-slate-900">Customize Self-Learning Activities (CA1)</h3>
                </div>
                <button type="button" onclick="closeSlConfigModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all">&times;</button>
            </div>
            
            <p class="text-slate-500 text-xs">Mandatory core activities (<span class="text-amber-700 font-bold">Assignment</span> & <span class="text-emerald-700 font-bold">MCQ</span>) are always evaluated out of 15 Marks. Select optional assessment activities per Course Outcome:</p>

            <form id="sl-config-form" onsubmit="saveSlConfig(event)" class="space-y-4 max-h-[450px] overflow-y-auto pr-1">
                @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2.5">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono font-bold text-xs">{{ $coTag }}</span>
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Assessment Activities</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5 text-xs">
                        <label class="flex items-center space-x-2 text-slate-600 bg-white p-2 rounded-lg border border-slate-200/80 opacity-90 cursor-not-allowed">
                            <input type="checkbox" checked disabled class="rounded text-amber-600 border-slate-300">
                            <span class="font-bold text-amber-800">Assignment (Mandatory)</span>
                        </label>
                        <label class="flex items-center space-x-2 text-slate-600 bg-white p-2 rounded-lg border border-slate-200/80 opacity-90 cursor-not-allowed">
                            <input type="checkbox" checked disabled class="rounded text-emerald-600 border-slate-300">
                            <span class="font-bold text-emerald-800">MCQ (Mandatory)</span>
                        </label>
                        @foreach(['case_study' => 'Case Study', 'quiz' => 'Quiz', 'activity' => 'Activity', 'microproject' => 'Microproject', 'mini_project' => 'Mini Project', 'report' => 'Report', 'exercises' => 'Exercises', 'presentation' => 'Presentation'] as $actKey => $actLabel)
                        <label class="flex items-center space-x-2 text-slate-700 bg-white p-2 rounded-lg border border-slate-200 hover:border-indigo-300 cursor-pointer transition-colors">
                            <input type="checkbox" name="configs[{{ $coTag }}][{{ $actKey }}]" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-0">
                            <span class="font-medium text-slate-800">{{ $actLabel }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeSlConfigModal()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">Save Activities Config</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Enter Self-Learning Marks Modal (CA1 Activity-Wise Sliders) -->
    <div id="sl-marks-modal" class="fixed inset-0 z-50 bg-slate-50 backdrop-blur-xs flex items-center justify-center hidden p-3 sm:p-5">
        <div class="bg-white max-w-3xl w-full p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Continuous Assessment Activity Evaluator</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Adjust sliders or tap +/- steppers to evaluate activity-wise splitup for each student.</p>
                    </div>
                </div>
                <button type="button" onclick="closeSlMarksModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xl font-bold transition-all">&times;</button>
            </div>

            <!-- Student Selection & Stepper Bar -->
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex items-center justify-between gap-2.5 flex-shrink-0">
                <button type="button" onclick="prevSlStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                    <span>◀ Prev</span>
                </button>

                <div class="flex-1 max-w-md">
                    <select id="sl-student-select" onchange="loadSlStudent(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 font-bold text-sm text-slate-900 outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                        @foreach($studentResults as $idx => $res)
                        <option value="{{ $res['reg_no'] }}" data-idx="{{ $idx }}">#{{ $res['roll_no'] }} - {{ $res['name'] }} (SBTE: {{ $res['sbte_reg_no'] ?: $res['reg_no'] }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" onclick="nextSlStudent()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs shadow-2xs transition-colors flex items-center gap-1 cursor-pointer">
                    <span>Next ▶</span>
                </button>
            </div>

            <!-- Live Score Summary Card -->
            <div class="bg-indigo-50/70 p-3.5 rounded-xl border border-indigo-100 flex items-center justify-between text-xs flex-shrink-0">
                <div>
                    <span class="text-slate-600 font-bold">Selected Student Average:</span>
                    <span id="sl-student-total-raw" class="font-extrabold text-slate-900 text-sm ml-1.5 font-mono">0.00 / 15.00 M</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-600 font-bold">Converted CA1 CIA:</span>
                    <span id="sl-student-converted-cia" class="font-black text-emerald-700 text-sm ml-1 px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-200 font-mono">0.00 / 5.00 M</span>
                </div>
            </div>

            <!-- Scrollable Activity Sliders Container -->
            <div id="sl-sliders-container" class="overflow-y-auto space-y-4 flex-1 pr-1">
                <!-- Dynamically populated by JS loadSlStudent() -->
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 flex-shrink-0">
                <button type="button" onclick="closeSlMarksModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition-colors cursor-pointer">Close</button>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="saveAndNextSlStudent()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 font-bold text-xs transition-colors shadow-2xs cursor-pointer">Next Student ▶</button>
                    <button type="button" onclick="saveAllSlMarks()" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">Save All Marks</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Practical ESE Evaluator Modal — CampusLynk Light Theme -->
