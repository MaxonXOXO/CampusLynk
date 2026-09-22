            <div id="theory-subcontent-series" class="space-y-4 hidden">

                <!-- QP Generator Panel — 4 Cards -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm no-print">
                    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h3 class="text-base font-bold text-white flex items-center gap-2 flex-wrap">
                                <span>📄 Series Exam QP Generator</span>
                                <span class="px-2.5 py-0.5 rounded-lg bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-semibold">
                                    {{ $subjectType['label'] ?? '💻 Program Core - ESE 100M' }}
                                </span>
                            </h3>
                            <p class="text-slate-400 text-xs mt-1">
                                @if(($subjectType['pattern'] ?? '') === 'table_4_2_design')
                                    Table 4.2 Design Paper: Part A (6×5=30M) + Part B (2×10=20M) = 50 Marks | 2 Hours
                                @else
                                    Single CO Test: Part A (2×1=2M) + Part B (3×3=9M) + Part C (answer any 2 of 3 × 7=14M) = 25 Marks | 1½ Hours
                                @endif
                                | Scaled to 10 CIA Marks
                            </p>
                        </div>
                    </div>

                    <!-- 4 Series Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['Series 1' => 'CO1', 'Series 2' => 'CO2', 'Series 3' => 'CO3', 'Series 4' => 'CO4'] as $series => $co)
                        @php $savedQp = $seriesQps[$series] ?? null; @endphp
                        <div class="rounded-xl border {{ $savedQp ? 'border-emerald-600/50 bg-emerald-50/60' : 'border-slate-200 bg-slate-50' }} p-3 flex flex-col gap-2">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 text-sm">{{ $series }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $savedQp ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">{{ $co }}</span>
                            </div>

                            <!-- Status -->
                            @if($savedQp)
                            <div class="text-xs text-emerald-600 font-semibold">✅ QP Saved</div>
                            @else
                            <div class="text-xs text-slate-500">⬜ Not generated</div>
                            @endif

                            <!-- Generate buttons -->
                            <div class="flex flex-col gap-1.5 mt-1">
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'ai')"
                                    class="w-full py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-800 transition-all text-center">
                                    ⚡ AI Generate
                                </button>
                                <button onclick="openQpPreviewModal('{{ $series }}', '{{ $co }}', 'manual')"
                                    class="w-full py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 transition-all text-center">
                                    ✏ Manual Entry
                                </button>
                            </div>

                            <!-- Print buttons (only if saved) -->
                            @if($savedQp)
                            <div class="border-t border-slate-200/50 pt-2 flex flex-col gap-1.5">
                                <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-qp/{{ rawurlencode($series) }}" target="_blank"
                                    class="w-full py-1.5 rounded-lg text-xs font-semibold bg-indigo-950/40 hover:bg-indigo-900/60 border border-indigo-500/30 text-indigo-300 text-center block">
                                    🖨️ Print QP
                                </a>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-scheme/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-center block">
                                        📋 Scheme
                                    </a>
                                    <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/series-qp/print-key/{{ rawurlencode($series) }}" target="_blank"
                                        class="py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-center block">
                                        🔑 Key
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div><!-- /grid -->

                    <div id="qp-gen-status" class="mt-3 text-xs text-slate-400 hidden"></div>
                </div><!-- /QP Generator Panel -->


                <!-- Theory Series Marks -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-3">
                        <div>
                            <h3 class="text-base font-semibold text-slate-800">Theory Series Examinations</h3>
                            <p class="text-slate-400 text-xs mt-0.5">4 Series Tests (CO1, CO2, CO3, CO4 - 2 Hours each out of 50 marks), averaged and scaled to 10 CIA marks</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="printSubtabReport('Theory Series Examinations Report', 'theory-subcontent-series')" class="header-btn px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 font-semibold text-xs transition-all no-print">🖨️ Print Report</button>
                            <button onclick="openSeriesTheoryModal()" class="header-btn px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-xs shadow-sm">Enter Theory Series Marks</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-700 font-bold bg-slate-50 text-sm border-b border-slate-200">
                                    <th class="p-3">Roll</th>
                                    <th class="p-3">SBTE Reg No</th>
                                    <th class="p-3">Student Name</th>
                                    <th class="p-3 text-center">Test 1 (CO1)</th>
                                    <th class="p-3 text-center">Test 2 (CO2)</th>
                                    <th class="p-3 text-center">Test 3 (CO3)</th>
                                    <th class="p-3 text-center">Test 4 (CO4)</th>
                                    <th class="p-3 text-center">Avg (/50)</th>
                                    <th class="p-3 text-center">Converted CIA (/10M)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-sm">
                                @foreach($studentResults as $res)
                                @php
                                    $stEvals = $seriesTheoryEvals->get($res['reg_no'], collect());
                                    $s1 = $stEvals->whereIn('series_no', ['Series 1', 'CO1'])->first();
                                    $s2 = $stEvals->whereIn('series_no', ['Series 2', 'CO2'])->first();
                                    $s3 = $stEvals->whereIn('series_no', ['Series 3', 'CO3'])->first();
                                    $s4 = $stEvals->whereIn('series_no', ['Series 4', 'CO4'])->first();
                                @endphp
                                <tr>
                                    <td class="p-3 text-slate-700 font-normal">{{ $res['roll_no'] }}</td>
                                    <td class="p-3 font-mono text-slate-700 font-bold">{{ $res['sbte_reg_no'] ?: $res['reg_no'] }}</td>
                                    <td class="p-3 text-slate-700 font-normal">{{ $res['name'] }}</td>
                                    <td class="p-3 text-center text-slate-700 font-normal">{{ $s1 ? number_format($s1->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-700 font-normal">{{ $s2 ? number_format($s2->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-700 font-normal">{{ $s3 ? number_format($s3->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-700 font-normal">{{ $s4 ? number_format($s4->total_score_50, 2) : '-' }}</td>
                                    <td class="p-3 text-center text-slate-700 font-normal">{{ number_format($res['series_theory_marks'] * 5, 2) }}</td>
                                    <td class="p-3 text-center text-slate-400 font-normal">{{ number_format($res['series_theory_marks'], 2) }} / 10.00</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- /inner bg-white border border-slate-200/80 rounded-2xl shadow-sm (marks table) -->
            </div>
            <!-- /outer space-y-4 (theory-subcontent-series) -->

