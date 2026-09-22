            <div id="lab-subcontent-series" class="space-y-4 hidden">
                <!-- Section Header -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 font-mono">TABLE 3.1</span>
                                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-100 font-mono">Practical Series Examinations</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mt-1">Practical Series Test — CIA Marks Register</h3>
                            <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Rubric-based series test evaluation. Total out of 40 Marks, scaled to 10 CIA Marks. Avg of best tests.</p>
                        </div>
                        <div class="flex items-center gap-2 no-print">
                            <button onclick="printSubtabReport('Practical Series Examinations Report', 'lab-subcontent-series')" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all shadow-2xs cursor-pointer flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Print Report
                            </button>
                            <button onclick="openSeriesPracticalModal()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-all cursor-pointer flex items-center gap-1.5 border border-indigo-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Enter Lab Series Test Marks
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Practical QP Generator Panel -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs no-print">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Practical Series QP Generator</h3>
                            <p class="text-slate-500 text-xs mt-0.5">Rubrics: Procedure (10M) + Setup (10M) + Result (10M) + Viva (5M) + Record (5M) = 40 Marks | 3 Hours | Scaled to 10 CIA Marks</p>
                        </div>
                    </div>

                    <!-- 2 Practical Series Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(['Practical Series 1' => 'CO1+CO2', 'Practical Series 2' => 'CO3+CO4'] as $series => $co)
                        @php $savedQp = $seriesQps[$series] ?? null; @endphp
                        <div class="rounded-xl border {{ $savedQp ? 'border-emerald-200 bg-emerald-50/60' : 'border-slate-200 bg-slate-50' }} p-4 flex flex-col gap-3">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-sm">{{ $series }}</span>
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold {{ $savedQp ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">{{ $co }}</span>
                            </div>

                            <!-- Status -->
                            @if($savedQp)
                            <div class="text-xs text-emerald-700 font-semibold flex items-center gap-1"><span class="text-emerald-500">✓</span> Practical QP Saved</div>
                            @else
                            <div class="text-xs text-slate-400">Not generated yet</div>
                            @endif

                            <!-- Generate buttons -->
                            <div class="flex flex-col sm:flex-row gap-2 mt-1">
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'ai')"
                                    class="w-full sm:w-1/2 py-2 rounded-lg text-xs font-bold bg-white hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 text-slate-700 hover:text-indigo-700 transition-all text-center shadow-2xs cursor-pointer">
                                    ⚡ AI Generate
                                </button>
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'manual')"
                                    class="w-full sm:w-1/2 py-2 rounded-lg text-xs font-bold bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 transition-all text-center shadow-2xs cursor-pointer">
                                    ✏ Manual Entry
                                </button>
                            </div>

                            <!-- Print buttons (only if saved) -->
                            @if($savedQp)
                            <div class="border-t border-slate-200/70 pt-3 flex flex-col gap-2">
                                <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-qp/{{ rawurlencode($series) }}" target="_blank"
                                    class="w-full py-2 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white text-center block no-underline transition-all cursor-pointer">
                                    🖨️ Print Practical QP
                                </a>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-scheme/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-2 rounded-lg text-xs font-bold bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-center block no-underline transition-all cursor-pointer">
                                        📋 Scheme
                                    </a>
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-key/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-2 rounded-lg text-xs font-bold bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-center block no-underline transition-all cursor-pointer">
                                        🔑 Key
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div><!-- /grid -->
                </div><!-- /Practical QP Generator Panel -->

                <!-- Data Table Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/60">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Series Practical Marks Register</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs table-compact-header">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase bg-slate-50 text-xs tracking-wide">
                                    <th class="p-3 w-12 text-center">Roll</th>
                                    <th class="p-3">SBTE Reg No</th>
                                    <th class="p-3">Student Name</th>
                                    <th class="p-3 text-center">Writeup (10M)</th>
                                    <th class="p-3 text-center">Setup (10M)</th>
                                    <th class="p-3 text-center">Obs/Result (8M)</th>
                                    <th class="p-3 text-center">Viva (8M)</th>
                                    <th class="p-3 text-center">Record (4M)</th>
                                    <th class="p-3 text-center">Test 1 (/40)</th>
                                    <th class="p-3 text-center">Test 2 (/40)</th>
                                    <th class="p-3 text-center">Avg (/40)</th>
                                    <th class="p-3 text-center">CIA (/10M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-normal">
                                @foreach($studentResults as $res)
                                @php
                                    $spEvals = $seriesPracticalEvals->get($res['reg_no'], collect());
                                    $sp1 = $spEvals->whereIn('series_no', ['Series 1', 'Test 1 (CO1+CO2)'])->first();
                                    $sp2 = $spEvals->whereIn('series_no', ['Series 2', 'Test 2 (CO3+CO4)'])->first();
                                    $spCount = $spEvals->count();
                                    $spWriteup = $spCount > 0 ? $spEvals->avg('writeup_procedure') : 0;
                                    $spSetup = $spCount > 0 ? $spEvals->avg('setup_execution') : 0;
                                    $spObs = $spCount > 0 ? $spEvals->avg('observation_result') : 0;
                                    $spViva = $spCount > 0 ? $spEvals->avg('viva_voce') : 0;
                                    $spRecord = $spCount > 0 ? $spEvals->avg('record_completion') : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-3 text-center font-mono font-bold text-slate-500 text-xs">{{ $res['roll_no'] }}</td>
                                    <td class="p-3 font-mono text-slate-700 font-bold text-xs">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-3 font-semibold text-slate-900">{{ $res['name'] }}</td>
                                    <td class="p-3 text-center text-slate-600">{{ number_format($spWriteup, 1) }}</td>
                                    <td class="p-3 text-center text-slate-600">{{ number_format($spSetup, 1) }}</td>
                                    <td class="p-3 text-center text-slate-600">{{ number_format($spObs, 1) }}</td>
                                    <td class="p-3 text-center text-slate-600">{{ number_format($spViva, 1) }}</td>
                                    <td class="p-3 text-center text-slate-600">{{ number_format($spRecord, 1) }}</td>
                                    <td class="p-3 text-center font-mono font-bold text-slate-800">{{ $sp1 ? number_format($sp1->total_score_40, 2) : '-' }}</td>
                                    <td class="p-3 text-center font-mono font-bold text-slate-800">{{ $sp2 ? number_format($sp2->total_score_40, 2) : '-' }}</td>
                                    <td class="p-3 text-center font-mono font-bold text-slate-800">{{ number_format($res['series_practical_marks'] * 4, 2) }}</td>
                                    <td class="p-3 text-center font-mono font-black text-indigo-700">{{ number_format($res['series_practical_marks'], 1) }} / 10.0</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Subtab 5: Practical ESE -->
