            <div id="lab-subcontent-ese" class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-5 rounded-xl border border-blue-600/40 bg-gradient-to-br from-slate-900 via-slate-900/95 to-blue-950/20 hidden">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-blue-300 flex items-center space-x-2">
                            <span>🏆 Institutional Practical End Semester Exam (40 Marks)</span>
                        </h3>
                        <p class="text-slate-400 text-xs mt-0.5">
                            Rubrics splitup: Procedure (10M) + Setup (10M) + Result (8M) + Viva (8M) + Record (4M) = 40 Marks
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="printSubtabReport('Practical End Semester Exam (ESE) Report', 'lab-subcontent-ese')" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                        <button onclick="openEsePracticalModal()" class="px-3 py-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/35 text-indigo-300 border border-indigo-500/40 font-semibold text-xs shadow-sm transition-all flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Enter Practical ESE Marks</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs table-compact-header">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-700 font-bold bg-slate-50 border-b border-slate-200">
                                <th class="p-2.5 w-12 text-center">Roll</th>
                                <th class="p-2.5">SBTE Reg No</th>
                                <th class="p-2.5">Student Name</th>
                                <th class="p-2.5 text-center">Writeup (10M)</th>
                                <th class="p-2.5 text-center">Setup (10M)</th>
                                <th class="p-2.5 text-center">Obs/Result (8M)</th>
                                <th class="p-2.5 text-center">Viva (8M)</th>
                                <th class="p-2.5 text-center">Record (4M)</th>
                                <th class="p-2.5 text-center">Practical ESE Total</th>
                                <th class="p-2.5 text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60" id="ese-practical-table-body">
                            @foreach($studentResults as $res)
                            @php
                                $score = $res['ese_practical'] ?? 0;
                                $pct = ($score / 40) * 100;
                                if ($pct >= 90) { $g = 'S'; $gc = 'text-emerald-600 bg-emerald-500/10 border-emerald-500/30'; }
                                elseif ($pct >= 80) { $g = 'A'; $gc = 'text-blue-700 bg-blue-50 border border-blue-200'; }
                                elseif ($pct >= 70) { $g = 'B'; $gc = 'text-indigo-600 bg-indigo-500/10 border-indigo-500/30'; }
                                elseif ($pct >= 60) { $g = 'C'; $gc = 'text-violet-600 bg-purple-500/10 border-purple-500/30'; }
                                elseif ($pct >= 50) { $g = 'D'; $gc = 'text-amber-600 bg-amber-500/10 border-amber-500/30'; }
                                elseif ($pct >= 40) { $g = 'E'; $gc = 'text-orange-400 bg-orange-500/10 border-orange-500/30'; }
                                else { $g = 'F'; $gc = 'text-rose-600 bg-rose-500/10 border-rose-500/30'; }
                                
                                $wInit = number_format(($score / 40.0) * 10.0, 1);
                                $sInit = number_format(($score / 40.0) * 10.0, 1);
                                $rInit = number_format(($score / 40.0) * 8.0, 1);
                                $vInit = number_format(($score / 40.0) * 8.0, 1);
                                $recInit = number_format(($score / 40.0) * 4.0, 1);
                            @endphp
                            <tr class="hover:bg-slate-50 transition-all" id="ese-row-{{ $res['reg_no'] }}">
                                <td class="p-2.5 text-center text-slate-700">{{ $res['roll_no'] }}</td>
                                <td class="p-2.5 font-mono text-slate-700 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                <td class="p-2.5 font-bold text-white">{{ $res['name'] }}</td>
                                <td class="p-2.5 text-center text-slate-700 ese-val-writeup">{{ $score > 0 ? $wInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-700 ese-val-setup">{{ $score > 0 ? $sInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-700 ese-val-result">{{ $score > 0 ? $rInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-700 ese-val-viva">{{ $score > 0 ? $vInit : '-' }}</td>
                                <td class="p-2.5 text-center text-slate-700 ese-val-record">{{ $score > 0 ? $recInit : '-' }}</td>
                                <td class="p-2.5 text-center font-bold text-blue-700 ese-val-total">{{ round($score) }}</td>
                                <td class="p-2.5 text-center ese-val-grade">
                                    <span class="px-2.5 py-0.5 rounded-full border text-xs font-bold {{ $gc }}">{{ $g }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>

    <!-- Customize Self-Learning Activities Modal -->
