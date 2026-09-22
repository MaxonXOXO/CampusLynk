            <div id="lab-subcontent-eval" class="space-y-5 hidden">
                <!-- 1. Header & Actions Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-2.5 py-1 rounded-md border border-purple-100 font-mono">PRACTICAL ASSESSMENT</span>
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100 font-mono">10 CIA Marks</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mt-1">Continuous Practical Evaluation (Table 2.2 Rubrics)</h3>
                            <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">
                                Grade student performance across 6 defined criteria: Prep (10M), Setup (10M), Observation (5M), Analysis (10M), Viva (10M), and Workmanship (5M). Total raw score of 50 is automatically scaled to 10 CIA Marks.
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <button type="button" onclick="printSubtabReport('Continuous Lab Evaluation (CE - 10M) Report', 'lab-subcontent-eval')" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all no-print flex items-center gap-1.5 shadow-2xs cursor-pointer">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Print Report</span>
                            </button>

                            <button type="button" onclick="openExperimentEvalModal()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs flex items-center gap-1.5 transition-all cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                <span>Evaluate Experiment</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. Table Workspace Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Continuous Lab Work Rubric Evaluation Register</span>
                        </div>
                        <span class="text-xs font-bold text-slate-500 font-mono">{{ count($studentResults) }} Enrolled</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[980px]">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/80 text-slate-700 font-bold text-xs uppercase tracking-wider">
                                    <th class="p-3.5 pl-4 text-center w-16">Roll</th>
                                    <th class="p-3.5 w-36">SBTE Reg No</th>
                                    <th class="p-3.5">Student Name</th>
                                    <th class="p-3 text-center w-24">Prep (10M)</th>
                                    <th class="p-3 text-center w-24">Setup (10M)</th>
                                    <th class="p-3 text-center w-20">Obs (5M)</th>
                                    <th class="p-3 text-center w-24">Analysis (10M)</th>
                                    <th class="p-3 text-center w-24">Viva (10M)</th>
                                    <th class="p-3 text-center w-20">Work (5M)</th>
                                    <th class="p-3 text-center w-28">Total Avg (/50)</th>
                                    <th class="p-3.5 text-center w-36">Converted CIA (10M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm font-normal text-slate-700">
                                @forelse($studentResults as $res)
                                @php
                                    $stExps = $experimentEvals->get($res['reg_no'], collect());
                                    $count = $stExps->count();
                                    $avgPrep = $count > 0 ? $stExps->avg('prep_punctuality') : 0;
                                    $avgSetup = $count > 0 ? $stExps->avg('setup_procedure') : 0;
                                    $avgObs = $count > 0 ? $stExps->avg('observation_recording') : 0;
                                    $avgAnalysis = $count > 0 ? $stExps->avg('analysis_interpretation') : 0;
                                    $avgViva = $count > 0 ? $stExps->avg('viva_voce') : 0;
                                    $avgWorkmanship = $count > 0 ? $stExps->avg('workmanship_discipline') : 0;
                                    $totalAvg50 = $avgPrep + $avgSetup + $avgObs + $avgAnalysis + $avgViva + $avgWorkmanship;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-3 pl-4 text-center font-mono font-bold text-slate-900">{{ $res['roll_no'] ?: '—' }}</td>
                                    <td class="p-3 font-mono font-bold text-indigo-700 text-xs">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-3 text-slate-900 font-medium">{{ $res['name'] }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgPrep, 1) }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgSetup, 1) }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgObs, 1) }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgAnalysis, 1) }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgViva, 1) }}</td>
                                    <td class="p-3 text-center font-mono text-slate-700">{{ number_format($avgWorkmanship, 1) }}</td>
                                    <td class="p-3 text-center font-mono font-bold text-slate-900">{{ number_format($totalAvg50, 2) }}</td>
                                    <td class="p-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono border shadow-2xs bg-purple-50 text-purple-700 border-purple-200/80">
                                            {{ number_format($res['continuous_eval_marks'], 1) }} / 10.0
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="p-8 text-center text-slate-400 font-normal">No student experiment evaluation records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Subtab 4: Practical Series Examinations -->
