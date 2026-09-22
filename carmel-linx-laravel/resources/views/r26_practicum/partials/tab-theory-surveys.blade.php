            <div id="theory-subcontent-surveys" class="space-y-5 hidden">
                
                <!-- Top Header Card -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-bold text-white tracking-tight">
                            Online Feedback Surveys & Indirect CO-PO Attainment
                        </h3>
                        <p class="text-slate-400 text-[11px] leading-snug mt-1">
                            Manage Mid-Semester Online Surveys (SAR Criterion 2)<br>
                            End-Semester Course Exit Surveys for Indirect CO Attainment (20% Weightage).
                        </p>
                    </div>
                    <div class="flex items-center space-x-2 whitespace-nowrap flex-shrink-0">
                        <a href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/35 text-indigo-300 border border-indigo-500/40 font-semibold text-xs transition-all flex items-center space-x-1.5 no-print whitespace-nowrap">
                            <span>🖨️ Course Exit Report</span>
                        </a>
                        <a href="/classroom/{{ $batchSubject->id }}/survey/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all flex items-center space-x-1.5 no-print whitespace-nowrap">
                            <span>🖨️ MidSem Report</span>
                        </a>
                    </div>
                </div>

                <!-- Dual Surveys Control Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Mid-Semester Survey Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                            <div class="flex items-center space-x-3">
                                <div class="p-2.5 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-600">
                                    <span class="material-symbols-rounded text-2xl">rate_review</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white text-base">Mid-Semester Online Survey</h4>
                                    <p class="text-xs text-slate-400">SAR Criterion 2 Evaluation</p>
                                </div>
                            </div>
                            <span id="midsem-practicum-status-badge" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 border border-slate-200">
                                Checking...
                            </span>
                        </div>

                        <p class="text-slate-700 text-xs leading-relaxed">
                            Captures early student feedback on syllabus delivery pace, concept clarity, ICT tools, classroom interaction, and evaluation fairness. Sends active task notification to student portal.
                        </p>

                        <div class="flex items-center space-x-2 pt-2 border-t border-slate-200/60 whitespace-nowrap">
                            <button id="btn-open-midsem-practicum" onclick="openMidsemInitModal()" class="px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/35 text-emerald-300 border border-emerald-500/40 font-semibold text-xs transition-all shadow-sm flex items-center space-x-1.5 whitespace-nowrap">
                                <span>Initiate / Open Survey</span>
                            </button>
                            <button id="btn-close-midsem-practicum" onclick="controlPracticumSurvey('midsem', 'close')" class="px-3 py-1.5 rounded-lg bg-rose-600/20 hover:bg-rose-600/35 text-rose-300 border border-rose-500/40 font-semibold text-xs transition-all shadow-sm hidden whitespace-nowrap">
                                <span>Close & Lock Survey</span>
                            </button>
                            <a href="/classroom/{{ $batchSubject->id }}/survey/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all flex items-center space-x-1 whitespace-nowrap">
                                <span>Print Report</span>
                            </a>
                        </div>
                    </div>

                    <!-- Course Exit Survey Card -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                            <div class="flex items-center space-x-3">
                                <div class="p-2.5 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400">
                                    <span class="material-symbols-rounded text-2xl">assignment_turned_in</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white text-base">Course Exit Survey</h4>
                                    <p class="text-xs text-slate-400">Indirect CO Attainment Assessment (20% Weightage)</p>
                                </div>
                            </div>
                            <span id="exit-practicum-status-badge" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-700 border border-slate-200">
                                Checking...
                            </span>
                        </div>

                        <p class="text-slate-700 text-xs leading-relaxed">
                            Evaluates student perception of Course Outcomes (CO1–CO4) at semester completion. Results automatically feed into Indirect CO Attainment (20% weightage).
                        </p>

                        <div class="flex items-center space-x-2 pt-2 border-t border-slate-200/60 whitespace-nowrap">
                            <button id="btn-open-exit-practicum" onclick="openExitInitModal()" class="px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/35 text-emerald-300 border border-emerald-500/40 font-semibold text-xs transition-all shadow-sm flex items-center space-x-1.5 whitespace-nowrap">
                                <span>Initiate / Open Survey</span>
                            </button>
                            <button id="btn-close-exit-practicum" onclick="controlPracticumSurvey('exit', 'close')" class="px-3 py-1.5 rounded-lg bg-rose-600/20 hover:bg-rose-600/35 text-rose-300 border border-rose-500/40 font-semibold text-xs transition-all shadow-sm hidden whitespace-nowrap">
                                <span>Close & Lock Survey</span>
                            </button>
                            <a href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all flex items-center space-x-1 whitespace-nowrap">
                                <span>Print Report</span>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Indirect CO Attainment Summary Grid -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                        <h4 class="font-bold text-white text-base flex items-center space-x-2">
                            <span class="material-symbols-rounded text-emerald-600">analytics</span>
                            <span>Calculated Indirect CO Attainment Scores (Scale 1–3 & High/Med/Low Rating)</span>
                        </h4>
                        <span class="text-xs text-slate-400">Computed from Course Exit Survey Responses</span>
                    </div>

                    <!-- NBA Scaling Standard Box -->
                    <div class="p-3 rounded-xl bg-white/90 border border-slate-200 text-xs text-slate-700 flex flex-wrap items-center gap-4">
                        <span class="font-bold text-indigo-600 uppercase tracking-wide">Attainment Scaling Standard:</span>
                        <span><strong class="text-emerald-600">Level 3 (High):</strong> &ge; 70%</span>
                        <span><strong class="text-amber-600">Level 2 (Medium):</strong> 60% – 69%</span>
                        <span><strong class="text-orange-400">Level 1 (Low):</strong> 50% – 59%</span>
                        <span><strong class="text-rose-600">Level 0 (Nil):</strong> &lt; 50%</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                        @php
                            $lvl = (int)($indirectStats[$coTag]['level'] ?? 3);
                            $rtg = $indirectStats[$coTag]['rating'] ?? ($lvl == 3 ? 'High' : ($lvl == 2 ? 'Medium' : ($lvl == 1 ? 'Low' : 'Nil')));
                        @endphp
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 text-sm">{{ $coTag }}</span>
                                <span class="px-2.5 py-0.5 rounded text-xs font-bold border {{ $lvl == 3 ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : ($lvl == 2 ? 'bg-amber-500/10 text-amber-600 border-amber-500/20' : ($lvl == 1 ? 'bg-orange-500/10 text-orange-400 border-orange-500/20' : 'bg-rose-500/10 text-rose-600 border-rose-500/20')) }}">
                                    Level {{ $lvl }} ({{ $rtg }})
                                </span>
                            </div>
                            <div class="text-slate-400 text-xs space-y-1">
                                <div>Survey Avg Rating: <span class="font-bold text-slate-800">{{ number_format($indirectStats[$coTag]['avg_score'] ?? 2.50, 2) }} / 3.0</span></div>
                                <div>Attainment Pct: <span class="font-bold text-emerald-600">{{ number_format($indirectStats[$coTag]['percentage'] ?? 83.3, 1) }}%</span></div>
                                <div class="text-[10px] text-slate-500 mt-1">Weightage in PO Calculation: 20%</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- PO Attainment Matrix Box (Direct 80% + Indirect 20%) -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <h4 class="font-bold text-white text-base flex items-center space-x-2">
                        <span class="material-symbols-rounded text-indigo-600">grid_on</span>
                        <span>Final Program Outcome (PO1–PO11) Attainment Scores</span>
                    </h4>
                    <p class="text-slate-400 text-xs">Overall PO Attainment = 80% Direct Attainment (Series/Lab/ESE) + 20% Indirect Attainment (Exit Survey)</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-2 text-center">
                        @for($p = 1; $p <= 11; $p++)
                        @php $po = "PO" . $p; @endphp
                        <div class="p-2.5 rounded-lg bg-white border border-slate-200">
                            <div class="text-xs text-slate-400 font-semibold">{{ $po }}</div>
                            <div class="font-bold text-emerald-600 text-sm mt-0.5">{{ $poAttainments[$po]['value'] ?? 0.0 }}</div>
                        </div>
                        @endfor
                    </div>
                </div>

            </div>

