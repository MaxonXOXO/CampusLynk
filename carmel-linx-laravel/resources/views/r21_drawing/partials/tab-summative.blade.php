<div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-5">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <x-ui.icon name="clipboard-check" class="w-5 h-5 text-indigo-600" />
                <span>Summative Assessment: Series Tests (Clause 11.2.3.a)</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Two series tests conducted across the semester. Summative Mark = Average of Test 1 &amp; Test 2 (40% of CIA / {{ $summativeMax }}M).
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/tests" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors shadow-2xs no-underline">
                <x-ui.icon name="printer" class="w-4 h-4 text-slate-500" />
                <span>Print Test Register</span>
            </a>
            <button type="button" onclick="saveActiveSeriesTestMarks()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white transition-colors shadow-2xs">
                <x-ui.icon name="save" class="w-4 h-4 text-white" />
                <span>Save Test Marks</span>
            </button>
        </div>
    </div>

    <!-- Test Selector Horizontal Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-100">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider shrink-0 flex items-center gap-1">
            <x-ui.icon name="check-square" class="w-3.5 h-3.5" />
            <span>Tests:</span>
        </span>
        <button type="button" 
                onclick="switchSeriesTest('Test 1', this)" 
                id="btn-test-1"
                class="test-pill shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold border transition-all cursor-pointer bg-indigo-50/80 border-indigo-300 text-indigo-700 shadow-2xs active">
            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
            <span>Series Test 1 (Modules I &amp; II)</span>
        </button>
        <button type="button" 
                onclick="switchSeriesTest('Test 2', this)" 
                id="btn-test-2"
                class="test-pill shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold border transition-all cursor-pointer bg-slate-50 hover:bg-slate-100 border-slate-200 text-slate-600">
            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
            <span>Series Test 2 (Modules III &amp; IV)</span>
        </button>
    </div>

    <!-- Active Test Criteria Banner -->
    <div class="bg-indigo-50/40 border border-indigo-100 rounded-xl p-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div class="flex items-center gap-3">
            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-600 text-white shadow-2xs" id="activeTestPillLabel">Test 1</span>
            <div>
                <h4 class="text-sm font-bold text-slate-900" id="activeTestTitle">Series Test 1 &bull; Modules I &amp; II</h4>
                <p class="text-xs text-slate-500">Criteria: Procedure Drawing (40%) + Final Drawing (30%) + Dimensioning (20%) + Neatness (10%) = Max 100</p>
            </div>
        </div>
        <div class="flex items-center gap-2 self-end sm:self-auto">
            <span class="text-xs text-slate-400">Status:</span>
            <span id="testSaveStatus" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                <x-ui.icon name="check" class="w-3 h-3 text-slate-500" />
                <span>Ready</span>
            </span>
        </div>
    </div>

    <!-- Series Test Marks Table -->
    <div class="overflow-x-auto border border-slate-200 rounded-xl">
        <table class="w-full text-left border-collapse text-xs" id="seriesTestMarksTable">
            <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="py-3 px-3 w-12 text-center">#</th>
                    <th class="py-3 px-3 w-24">Roll No</th>
                    <th class="py-3 px-3 w-32">Reg No</th>
                    <th class="py-3 px-4">Student Name</th>
                    <th class="py-3 px-2 w-28 text-center text-indigo-700">Procedure<br><span class="text-[10px] text-slate-400 font-normal">Max 40</span></th>
                    <th class="py-3 px-2 w-28 text-center text-indigo-700">Final Drawing<br><span class="text-[10px] text-slate-400 font-normal">Max 30</span></th>
                    <th class="py-3 px-2 w-28 text-center text-indigo-700">Dimensioning<br><span class="text-[10px] text-slate-400 font-normal">Max 20</span></th>
                    <th class="py-3 px-2 w-24 text-center text-indigo-700">Neatness<br><span class="text-[10px] text-slate-400 font-normal">Max 10</span></th>
                    <th class="py-3 px-3 w-28 text-center text-amber-700">Total Score<br><span class="text-[10px] text-slate-400 font-normal">Max 100</span></th>
                    <th class="py-3 px-2 w-20 text-center">Absent</th>
                    <th class="py-3 px-3 w-40">Remarks</th>
                </tr>
            </thead>
            <tbody id="testTableBody" class="divide-y divide-slate-100 font-medium text-slate-700">
                @foreach($students as $idx => $student)
                    @php
                        $t1 = $studentResults[$idx]['t1_detail'] ?? null;
                        $procedure = $t1 ? floatval($t1->procedure_drawing) : '';
                        $finalDraw = $t1 ? floatval($t1->final_drawing) : '';
                        $dimen     = $t1 ? floatval($t1->dimensioning) : '';
                        $neat      = $t1 ? floatval($t1->neatness) : '';
                        $total     = ($t1 && !$t1->is_absent) ? floatval($t1->total_score_100) : 0;
                        $isAbsent  = $t1 ? $t1->is_absent : false;
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors" data-reg-no="{{ $student->reg_no }}">
                        <td class="py-2.5 px-3 text-center text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $student->roll_no ?? '-' }}</td>
                        <td class="py-2.5 px-3 font-mono text-slate-500">{{ $student->reg_no }}</td>
                        <td class="py-2.5 px-4 font-semibold text-slate-900">{{ $student->name }}</td>
                        <td class="py-2.5 px-2 text-center">
                            <input type="number" step="0.5" min="0" max="40" 
                                   class="w-20 px-2 py-1 text-center font-semibold bg-white border border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs test-procedure" 
                                   value="{{ $procedure }}" 
                                   data-reg="{{ $student->reg_no }}" 
                                   oninput="calculateTestRowTotal(this)" 
                                   onkeydown="handleGridNavigation(event, this)">
                        </td>
                        <td class="py-2.5 px-2 text-center">
                            <input type="number" step="0.5" min="0" max="30" 
                                   class="w-20 px-2 py-1 text-center font-semibold bg-white border border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs test-final" 
                                   value="{{ $finalDraw }}" 
                                   data-reg="{{ $student->reg_no }}" 
                                   oninput="calculateTestRowTotal(this)" 
                                   onkeydown="handleGridNavigation(event, this)">
                        </td>
                        <td class="py-2.5 px-2 text-center">
                            <input type="number" step="0.5" min="0" max="20" 
                                   class="w-20 px-2 py-1 text-center font-semibold bg-white border border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs test-dimen" 
                                   value="{{ $dimen }}" 
                                   data-reg="{{ $student->reg_no }}" 
                                   oninput="calculateTestRowTotal(this)" 
                                   onkeydown="handleGridNavigation(event, this)">
                        </td>
                        <td class="py-2.5 px-2 text-center">
                            <input type="number" step="0.5" min="0" max="10" 
                                   class="w-16 px-2 py-1 text-center font-semibold bg-white border border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs test-neatness" 
                                   value="{{ $neat }}" 
                                   data-reg="{{ $student->reg_no }}" 
                                   oninput="calculateTestRowTotal(this)" 
                                   onkeydown="handleGridNavigation(event, this)">
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            <span class="inline-flex items-center justify-center min-w-[50px] px-2.5 py-1 rounded-lg text-xs font-bold border test-row-total-badge {{ $isAbsent ? 'bg-rose-50 text-rose-700 border-rose-200' : ($total >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                                {{ $isAbsent ? 'ABS' : $total }}
                            </span>
                        </td>
                        <td class="py-2.5 px-2 text-center">
                            <input type="checkbox" 
                                   class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500 test-absent" 
                                   {{ $isAbsent ? 'checked' : '' }} 
                                   data-reg="{{ $student->reg_no }}" 
                                   onchange="toggleTestAbsent(this)">
                        </td>
                        <td class="py-2.5 px-3">
                            <input type="text" 
                                   class="w-full px-2 py-1 text-xs bg-white border border-slate-200 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 test-remarks" 
                                   placeholder="Remarks..." 
                                   value="{{ $t1->remarks ?? '' }}" 
                                   data-reg="{{ $student->reg_no }}"
                                   onchange="scheduleAutoSave('test')">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
