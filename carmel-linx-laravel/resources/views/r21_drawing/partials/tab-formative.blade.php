<div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-5">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <x-ui.icon name="pen-tool" class="w-5 h-5 text-blue-600" />
                <span>Formative Continuous Evaluation: Drawing Sheets</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Continuous evaluation of drawing sheets (minimum two sheets evaluated per module). Rubric: <strong>Timely Completion (50%)</strong> &amp; <strong>Appearance and Organization (50%)</strong>.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/sheets" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors shadow-2xs no-underline">
                <x-ui.icon name="printer" class="w-4 h-4 text-slate-500" />
                <span>Print Sheet Register</span>
            </a>
            <button type="button" onclick="saveActiveSheetMarks()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition-colors shadow-2xs">
                <x-ui.icon name="save" class="w-4 h-4 text-white" />
                <span>Save Sheet Marks</span>
            </button>
        </div>
    </div>

    <!-- Sheet Selector Horizontal Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-100 scrollbar-thin">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider shrink-0 flex items-center gap-1">
            <x-ui.icon name="layers" class="w-3.5 h-3.5" />
            <span>Sheets:</span>
        </span>
        @foreach($drawingCourseFile->parsed_sheets ?? [] as $index => $sheet)
            @php
                $modText = $sheet['module'] ?? 'Mod';
                $modBadgeClass = str_contains($modText, '1') ? 'bg-blue-50 text-blue-700 border-blue-200' :
                    (str_contains($modText, '2') ? 'bg-indigo-50 text-indigo-700 border-indigo-200' :
                    (str_contains($modText, '3') ? 'bg-amber-50 text-amber-700 border-amber-200' :
                    'bg-emerald-50 text-emerald-700 border-emerald-200'));
            @endphp
            <button type="button"
                    onclick="switchSheet('{{ $sheet['sheet_no'] }}', this)" 
                    data-sheet-no="{{ $sheet['sheet_no'] }}"
                    data-sheet-title="{{ $sheet['title'] ?? '' }}"
                    data-sheet-module="{{ $sheet['module'] ?? '' }}"
                    data-sheet-co="{{ $sheet['co_id'] ?? '' }}"
                    class="sheet-pill shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold border transition-all cursor-pointer {{ $index === 0 ? 'bg-blue-50/80 border-blue-300 text-blue-700 shadow-2xs active' : 'bg-slate-50 hover:bg-slate-100 border-slate-200 text-slate-600' }}">
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold border {{ $modBadgeClass }}">{{ $modText }}</span>
                <span>{{ $sheet['sheet_no'] }}</span>
            </button>
        @endforeach
    </div>

    <!-- Selected Sheet Info Banner -->
    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div class="flex items-center gap-3">
            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-600 text-white shadow-2xs" id="activeSheetPillLabel">Sheet 1</span>
            <div>
                <h4 class="text-sm font-bold text-slate-900" id="activeSheetTitle">Lettering, Numbering &amp; Dimensioning Practice</h4>
                <p class="text-xs text-slate-500" id="activeSheetMeta">Module 1 &bull; Mapped to Outcome: <strong class="text-blue-600">CO1</strong> &bull; Max Score: 100 (Timely 50 + Appearance 50)</p>
            </div>
        </div>
        <div class="flex items-center gap-2 self-end sm:self-auto">
            <span class="text-xs text-slate-400">Status:</span>
            <span id="sheetSaveStatus" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                <x-ui.icon name="check" class="w-3 h-3 text-slate-500" />
                <span>Ready</span>
            </span>
        </div>
    </div>

    <!-- Sheet Marks Table -->
    <div class="overflow-x-auto border border-slate-200 rounded-xl">
        <table class="w-full text-left border-collapse text-xs" id="sheetMarksTable">
            <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="py-3 px-3 w-12 text-center">#</th>
                    <th class="py-3 px-3 w-24">Roll No</th>
                    <th class="py-3 px-3 w-32">Reg No</th>
                    <th class="py-3 px-4">Student Name</th>
                    <th class="py-3 px-3 w-36 text-center text-blue-700">Timely (50%)<br><span class="text-[10px] text-slate-400 font-normal">Max 50</span></th>
                    <th class="py-3 px-3 w-36 text-center text-blue-700">Appearance (50%)<br><span class="text-[10px] text-slate-400 font-normal">Max 50</span></th>
                    <th class="py-3 px-3 w-28 text-center text-amber-700">Total Score<br><span class="text-[10px] text-slate-400 font-normal">Max 100</span></th>
                    <th class="py-3 px-3 w-20 text-center">Absent</th>
                    <th class="py-3 px-4 w-48">Faculty Remarks</th>
                </tr>
            </thead>
            <tbody id="sheetTableBody" class="divide-y divide-slate-100 font-medium text-slate-700">
                @foreach($students as $idx => $student)
                    @php
                        $eval = $studentResults[$idx]['sheets_detail']['Sheet 1'] ?? null;
                        $timely = $eval ? floatval($eval->timely_completion) : '';
                        $appearance = $eval ? floatval($eval->appearance_organization) : '';
                        $total = ($eval && !$eval->is_absent) ? floatval($eval->total_score_100) : 0;
                        $isAbsent = $eval ? $eval->is_absent : false;
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors" data-reg-no="{{ $student->reg_no }}">
                        <td class="py-2.5 px-3 text-center text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $student->roll_no ?? '-' }}</td>
                        <td class="py-2.5 px-3 font-mono text-slate-500">{{ $student->reg_no }}</td>
                        <td class="py-2.5 px-4 font-semibold text-slate-900">{{ $student->name }}</td>
                        <td class="py-2.5 px-3 text-center">
                            <input type="number" step="0.5" min="0" max="50" 
                                   class="w-24 px-2 py-1 text-center font-semibold bg-white border border-slate-200 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-xs sheet-timely" 
                                   value="{{ $timely }}" 
                                   data-reg="{{ $student->reg_no }}" 
                                   oninput="calculateSheetRowTotal(this)" 
                                   onkeydown="handleGridNavigation(event, this)">
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            <input type="number" step="0.5" min="0" max="50" 
                                   class="w-24 px-2 py-1 text-center font-semibold bg-white border border-slate-200 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-xs sheet-appearance" 
                                   value="{{ $appearance }}" 
                                   data-reg="{{ $student->reg_no }}" 
                                   oninput="calculateSheetRowTotal(this)" 
                                   onkeydown="handleGridNavigation(event, this)">
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            <span class="inline-flex items-center justify-center min-w-[50px] px-2.5 py-1 rounded-lg text-xs font-bold border row-total-badge {{ $isAbsent ? 'bg-rose-50 text-rose-700 border-rose-200' : ($total >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                                {{ $isAbsent ? 'ABS' : $total }}
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            <input type="checkbox" 
                                   class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500 sheet-absent" 
                                   {{ $isAbsent ? 'checked' : '' }} 
                                   data-reg="{{ $student->reg_no }}" 
                                   onchange="toggleSheetAbsent(this)">
                        </td>
                        <td class="py-2.5 px-4">
                            <input type="text" 
                                   class="w-full px-2 py-1 text-xs bg-white border border-slate-200 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500 sheet-remarks" 
                                   placeholder="Remarks..." 
                                   value="{{ $eval->remarks ?? '' }}" 
                                   data-reg="{{ $student->reg_no }}"
                                   onchange="scheduleAutoSave('sheet')">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
