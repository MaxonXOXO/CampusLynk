            <div id="theory-subcontent-ese" class="space-y-5 hidden">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-white">Written Theory End Semester Exam (60 Marks)</h3>
                            <p class="text-slate-400 text-xs mt-0.5">Board Theory ESE Grades evaluated per Official Revision 2026 Grading System Standard</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="printSubtabReport('Theory ESE & Overall Results Report', 'theory-subcontent-ese')" class="header-btn px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                            <button onclick="openEseTheoryModal()" class="header-btn px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs shadow-sm">Enter Theory ESE Grades</button>
                        </div>
                    </div>

                    <!-- Official R2026 Grade Scale Legend Box -->
                    <div class="p-3 mb-4 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                        <div class="font-bold text-slate-700 mb-2 uppercase tracking-wide">Revision 2026 Official Grading System Standard (Theory ESE)</div>
                        <div class="grid grid-cols-7 gap-1 text-center font-mono">
                            <div class="p-1.5 rounded bg-emerald-500/10 border border-emerald-500/30 text-emerald-600">
                                <div class="font-bold text-sm">S</div>
                                <div class="text-[10px] opacity-80">≥90%</div>
                                <div class="text-[10px] text-slate-400">GP: 10</div>
                            </div>
                            <div class="p-1.5 rounded bg-blue-50 text-blue-700 border border-blue-200">
                                <div class="font-bold text-sm">A</div>
                                <div class="text-[10px] opacity-80">80–89%</div>
                                <div class="text-[10px] text-slate-400">GP: 9</div>
                            </div>
                            <div class="p-1.5 rounded bg-indigo-500/10 border border-indigo-500/30 text-indigo-600">
                                <div class="font-bold text-sm">B</div>
                                <div class="text-[10px] opacity-80">70–79%</div>
                                <div class="text-[10px] text-slate-400">GP: 8</div>
                            </div>
                            <div class="p-1.5 rounded bg-purple-500/10 border border-purple-500/30 text-violet-600">
                                <div class="font-bold text-sm">C</div>
                                <div class="text-[10px] opacity-80">60–69%</div>
                                <div class="text-[10px] text-slate-400">GP: 7</div>
                            </div>
                            <div class="p-1.5 rounded bg-amber-500/10 border border-amber-500/30 text-amber-600">
                                <div class="font-bold text-sm">D</div>
                                <div class="text-[10px] opacity-80">50–59%</div>
                                <div class="text-[10px] text-slate-400">GP: 6</div>
                            </div>
                            <div class="p-1.5 rounded bg-orange-500/10 border border-orange-500/30 text-orange-400">
                                <div class="font-bold text-sm">E</div>
                                <div class="text-[10px] opacity-80">40–49%</div>
                                <div class="text-[10px] text-slate-400">GP: 5</div>
                            </div>
                            <div class="p-1.5 rounded bg-rose-500/10 border border-rose-500/30 text-rose-600">
                                <div class="font-bold text-sm">F</div>
                                <div class="text-[10px] opacity-80">&lt;40%</div>
                                <div class="text-[10px] text-slate-400">GP: 0</div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs table-compact-header">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-700 font-bold bg-slate-50 border-b border-slate-200">
                                    <th class="p-2.5 w-12 text-center">Roll</th>
                                    <th class="p-2.5">SBTE Reg No</th>
                                    <th class="p-2.5">Student Name</th>
                                    <th class="p-2.5 text-center">Board Theory ESE Grade</th>
                                    <th class="p-2.5 text-center">Pass / Fail Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-xs">
                                @foreach($studentResults as $res)
                                @php
                                    $grade = strtoupper($res['ese_theory_grade'] ?? '-');
                                    if ($grade === 'S') { $gc = 'text-emerald-600 bg-emerald-500/10 border-emerald-500/30'; }
                                    elseif ($grade === 'A') { $gc = 'text-blue-700 bg-blue-50 border-blue-200'; }
                                    elseif ($grade === 'B') { $gc = 'text-indigo-600 bg-indigo-500/10 border-indigo-500/30'; }
                                    elseif ($grade === 'C') { $gc = 'text-violet-600 bg-purple-500/10 border-purple-500/30'; }
                                    elseif ($grade === 'D') { $gc = 'text-amber-600 bg-amber-500/10 border-amber-500/30'; }
                                    elseif ($grade === 'E') { $gc = 'text-orange-400 bg-orange-500/10 border-orange-500/30'; }
                                    elseif (in_array($grade, ['F', 'FE', 'ABSENT', 'ABS'])) { $gc = 'text-rose-600 bg-rose-500/10 border-rose-500/30'; }
                                    else { $gc = 'text-slate-400 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 border-slate-200'; }

                                    $isFail = in_array($grade, ['F', 'FE', 'ABSENT', 'ABS']);
                                @endphp
                                <tr class="hover:bg-slate-50 transition-all">
                                    <td class="p-2.5 text-center text-slate-700">{{ $res['roll_no'] }}</td>
                                    <td class="p-2.5 font-mono text-slate-700 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-2.5 font-bold text-white">{{ $res['name'] }}</td>
                                    <td class="p-2.5 text-center font-bold">
                                        <span class="px-3 py-0.5 rounded-full border text-xs font-bold {{ $gc }}">{{ $grade !== '-' ? $grade : 'Not Entered' }}</span>
                                    </td>
                                    <td class="p-2.5 text-center font-semibold {{ !$isFail && $grade !== '-' ? 'text-emerald-600' : ($isFail ? 'text-rose-600' : 'text-slate-400') }}">
                                        {{ $grade === '-' ? '-' : (!$isFail ? 'PASSED' : 'REAPPEAR / FAIL') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Consolidated Results -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-white">🏆 NBA Attainment Summary (Direct 80% + Indirect 20%)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-normal text-slate-800 text-sm">{{ $coTag }}</span>
                                <span class="px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-slate-600 text-xs font-normal border border-slate-200">Level {{ $combinedStats[$coTag] ?? 0.0 }} / 3.0</span>
                            </div>
                            <div class="text-slate-600 text-xs space-y-1">
                                <div>Direct Attainment: <span class="font-normal text-slate-700">{{ $directStats[$coTag]['level'] ?? 0 }}</span> ({{ $directStats[$coTag]['percentage'] ?? 0 }}% Students)</div>
                                <div>Indirect Attainment: <span class="font-normal text-slate-700">{{ $indirectStats[$coTag]['level'] ?? 0 }}</span></div>
                                <div>Overall (80:20): <span class="font-normal text-slate-800">{{ $combinedStats[$coTag] ?? 0 }}</span></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- PO Attainment Row -->
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <h4 class="font-normal text-slate-700 text-sm mb-3">Calculated Program Outcome (PO) Attainment Scores</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-2 text-center">
                            @for($p = 1; $p <= 11; $p++)
                            @php $po = "PO" . $p; @endphp
                            <div class="p-2.5 rounded-lg bg-white border border-slate-200">
                                <div class="text-xs text-slate-400 font-normal">{{ $po }}</div>
                                <div class="font-normal text-slate-800 text-sm mt-0.5">{{ $poAttainments[$po]['value'] ?? 0.0 }}</div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- CIA Summary Card -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <h3 class="text-lg font-bold text-white mb-1">Consolidated Continuous Internal Assessment (CIA - 40 Marks Table 1.4)</h3>
                    <p class="text-slate-400 text-xs mb-3">Attendance (5M) + CA1 Self Learning (5M) + CE Continuous Lab (10M) + CA2/CA3 Practical Tests (10M) + CA4/CA5 Theory Tests (10M) = 40 CIA Marks</p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-700 font-bold bg-slate-50 text-sm border-b border-slate-200">
                                    <th class="p-2.5">Roll</th>
                                    <th class="p-2.5">SBTE Reg No</th>
                                    <th class="p-2.5">Student Name</th>
                                    <th class="p-2.5 text-center">Att (5M)</th>
                                    <th class="p-2.5 text-center">CA1 SL (5M)</th>
                                    <th class="p-2.5 text-center">CE Lab (10M)</th>
                                    <th class="p-2.5 text-center">CA4/5 Th Tests (10M)</th>
                                    <th class="p-2.5 text-center">CA2/3 Pr Tests (10M)</th>
                                    <th class="p-2.5 text-center">Total CIA (40M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-sm">
                                @foreach($studentResults as $res)
                                <tr class="hover:bg-slate-50 transition-all">
                                    <td class="p-2.5 text-slate-700 font-normal">{{ $res['roll_no'] }}</td>
                                    <td class="p-2.5 font-mono text-slate-700 font-bold text-xs">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-2.5 text-slate-700 font-normal">{{ $res['name'] }}</td>
                                    <td class="p-2.5 text-center text-slate-700 font-normal">{{ $res['att_marks'] }}</td>
                                    <td class="p-2.5 text-center text-slate-700 font-normal">{{ number_format($res['sl_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-700 font-normal">{{ number_format($res['continuous_eval_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-700 font-normal">{{ number_format($res['series_theory_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-700 font-normal">{{ number_format($res['series_practical_marks'], 2) }}</td>
                                    <td class="p-2.5 text-center text-slate-800 font-normal">
                                        {{ number_format($res['total_cia_marks'], 2) }} / 40.00
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

