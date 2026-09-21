<div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-5">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <x-ui.icon name="user-check" class="w-5 h-5 text-emerald-600" />
                <span>Attendance &amp; Performance Evaluation (20% Weightage / {{ $attMax }} Marks)</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Evaluated based on student attendance percentage as per Kerala SBTE Regulation Clause 11.2.3.c. Slabs: &ge;90% (10M), 80-89% (8M), 75-79% (6M), 70-74% (4M), 65-69% (2M), &lt;65% (0M).
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="saveAllAttendance()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors shadow-2xs">
                <x-ui.icon name="save" class="w-4 h-4 text-white" />
                <span>Save Attendance Marks</span>
            </button>
        </div>
    </div>

    <!-- Attendance Slabs Legend Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-6 gap-2 bg-slate-50 border border-slate-200/80 rounded-xl p-3 text-xs">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            <div>
                <span class="font-bold text-slate-700">&ge; 90%</span>
                <span class="text-slate-500 block text-[11px]">{{ $attMax }} Marks (100%)</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            <div>
                <span class="font-bold text-slate-700">80% - 89%</span>
                <span class="text-slate-500 block text-[11px]">{{ round($attMax * 0.8, 1) }} Marks (80%)</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
            <div>
                <span class="font-bold text-slate-700">75% - 79%</span>
                <span class="text-slate-500 block text-[11px]">{{ round($attMax * 0.6, 1) }} Marks (60%)</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
            <div>
                <span class="font-bold text-slate-700">70% - 74%</span>
                <span class="text-slate-500 block text-[11px]">{{ round($attMax * 0.4, 1) }} Marks (40%)</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
            <div>
                <span class="font-bold text-slate-700">65% - 69%</span>
                <span class="text-slate-500 block text-[11px]">{{ round($attMax * 0.2, 1) }} Marks (20%)</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
            <div>
                <span class="font-bold text-slate-700">&lt; 65%</span>
                <span class="text-slate-500 block text-[11px]">0.0 Marks (0%)</span>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="overflow-x-auto border border-slate-200 rounded-xl shadow-2xs">
        <table class="w-full text-xs text-left text-slate-700 divide-y divide-slate-200" id="r21AttendanceTable">
            <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-[11px]">
                <tr>
                    <th scope="col" class="py-3 px-3 w-12 text-center">Roll</th>
                    <th scope="col" class="py-3 px-3 w-28">Reg No</th>
                    <th scope="col" class="py-3 px-3 min-w-[160px]">Student Name</th>
                    <th scope="col" class="py-3 px-3 w-24 text-center">Att. %</th>
                    <th scope="col" class="py-3 px-3 w-24 text-center">System Mark</th>
                    <th scope="col" class="py-3 px-3 w-28 text-center">Override Mark</th>
                    <th scope="col" class="py-3 px-3 w-44">Override Reason</th>
                    <th scope="col" class="py-3 px-3 w-24 text-center">Final Mark</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($studentResults as $result)
                    @php
                        $attPct = $result['att_percentage'];
                        $badgeBg = $attPct >= 90 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                            ($attPct >= 75 ? 'bg-blue-50 text-blue-700 border-blue-200' :
                            ($attPct >= 65 ? 'bg-amber-50 text-amber-700 border-amber-200' :
                            'bg-rose-50 text-rose-700 border-rose-200'));
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors" data-reg-no="{{ $result['reg_no'] }}">
                        <td class="py-2.5 px-3 text-center font-medium text-slate-500">{{ $result['roll_no'] ?? '-' }}</td>
                        <td class="py-2.5 px-3 font-mono font-bold text-slate-800">{{ $result['reg_no'] }}</td>
                        <td class="py-2.5 px-3 font-medium text-slate-900">{{ $result['name'] }}</td>
                        <td class="py-2.5 px-3 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-md font-bold border {{ $badgeBg }}">
                                {{ number_format($attPct, 1) }}%
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-center font-semibold text-slate-600 system-att-mark">
                            {{ number_format($result['calc_att_marks'], 1) }}
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            <input type="number"
                                   name="override_mark[{{ $result['reg_no'] }}]"
                                   class="att-override-input w-20 px-2 py-1 text-center font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-xs transition-colors"
                                   min="0"
                                   max="{{ $attMax }}"
                                   step="0.5"
                                   placeholder="-"
                                   value="{{ $result['att_marks'] != $result['calc_att_marks'] ? $result['att_marks'] : '' }}"
                                   onchange="recalculateAttendanceRow('{{ $result['reg_no'] }}')" />
                        </td>
                        <td class="py-2.5 px-3">
                            <input type="text"
                                   name="override_reason[{{ $result['reg_no'] }}]"
                                   class="att-reason-input w-full px-2 py-1 text-slate-700 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-emerald-500 text-xs transition-colors"
                                   placeholder="Medical / Condonation..."
                                   value="" />
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            <span class="final-att-mark inline-block px-2.5 py-1 rounded-lg font-bold text-emerald-800 bg-emerald-50 border border-emerald-200">
                                {{ number_format($result['att_marks'], 1) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400">
                            <x-ui.icon name="users" class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                            <p>No enrolled students found for this drawing hall subject.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
