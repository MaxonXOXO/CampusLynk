<div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-5">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <x-ui.icon name="award" class="w-5 h-5 text-amber-500" />
                <span>Consolidated Continuous Internal Assessment (CIA — {{ $ciaMax }} Marks)</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Statutory CIA compilation according to R-2021 Clause 11.2.3: <strong>Formative (40% / {{ $formativeMax }}M)</strong> + <strong>Summative (40% / {{ $summativeMax }}M)</strong> + <strong>Attendance (20% / {{ $attMax }}M)</strong>. Minimum qualifying mark: <strong>{{ round($ciaMax * 0.40, 1) }} Marks (40%)</strong>.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/cia" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-amber-500 hover:bg-amber-600 text-white transition-colors shadow-2xs no-underline">
                <x-ui.icon name="printer" class="w-4 h-4 text-white" />
                <span>Print Official Marksheet</span>
            </a>
        </div>
    </div>

    <!-- CIA Summary Stats Strip -->
    @php
        $totalStudents = $studentResults->count();
        $passedStudents = $studentResults->where('is_pass', true)->count();
        $failedStudents = $totalStudents - $passedStudents;
        $passPercentage = $totalStudents > 0 ? round(($passedStudents / $totalStudents) * 100, 1) : 0;
        $avgCiaScore = $totalStudents > 0 ? round($studentResults->avg('total_cia'), 1) : 0;
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3">
            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Enrolled Students</span>
            <span class="text-xl font-black text-slate-900 mt-0.5 block">{{ $totalStudents }}</span>
        </div>
        <div class="bg-emerald-50/60 border border-emerald-200/80 rounded-xl p-3">
            <span class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider block">Pass Eligibility (&ge;40%)</span>
            <div class="flex items-baseline gap-2 mt-0.5">
                <span class="text-xl font-black text-emerald-800">{{ $passedStudents }}</span>
                <span class="text-xs font-bold text-emerald-600">({{ $passPercentage }}%)</span>
            </div>
        </div>
        <div class="bg-rose-50/60 border border-rose-200/80 rounded-xl p-3">
            <span class="text-[11px] font-semibold text-rose-700 uppercase tracking-wider block">Need Improvement (&lt;40%)</span>
            <div class="flex items-baseline gap-2 mt-0.5">
                <span class="text-xl font-black text-rose-800">{{ $failedStudents }}</span>
                <span class="text-xs font-bold text-rose-600">({{ $totalStudents > 0 ? round(($failedStudents / $totalStudents) * 100, 1) : 0 }}%)</span>
            </div>
        </div>
        <div class="bg-blue-50/60 border border-blue-200/80 rounded-xl p-3">
            <span class="text-[11px] font-semibold text-blue-700 uppercase tracking-wider block">Class Average CIA</span>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-xl font-black text-blue-900">{{ $avgCiaScore }}</span>
                <span class="text-xs font-bold text-blue-600">/ {{ $ciaMax }}</span>
            </div>
        </div>
    </div>

    <!-- Consolidated Table -->
    <div class="overflow-x-auto border border-slate-200 rounded-xl shadow-2xs">
        <table class="w-full text-xs text-left text-slate-700 divide-y divide-slate-200" id="r21CiaTable">
            <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-[11px]">
                <tr>
                    <th scope="col" class="py-3 px-3 w-12 text-center">Roll</th>
                    <th scope="col" class="py-3 px-3 w-28">Reg No</th>
                    <th scope="col" class="py-3 px-3 min-w-[160px]">Student Name</th>
                    <th scope="col" class="py-3 px-3 w-28 text-center bg-blue-50/50">Formative ({{ $formativeMax }})</th>
                    <th scope="col" class="py-3 px-3 w-28 text-center bg-indigo-50/50">Summative ({{ $summativeMax }})</th>
                    <th scope="col" class="py-3 px-3 w-28 text-center bg-emerald-50/50">Attendance ({{ $attMax }})</th>
                    <th scope="col" class="py-3 px-3 w-28 text-center bg-amber-50/70 font-bold text-slate-800">Total CIA ({{ $ciaMax }})</th>
                    <th scope="col" class="py-3 px-3 w-24 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($studentResults as $result)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-2.5 px-3 text-center font-medium text-slate-500">{{ $result['roll_no'] ?? '-' }}</td>
                        <td class="py-2.5 px-3 font-mono font-bold text-slate-800">{{ $result['reg_no'] }}</td>
                        <td class="py-2.5 px-3 font-medium text-slate-900">{{ $result['name'] }}</td>
                        <td class="py-2.5 px-3 text-center font-semibold text-blue-800 bg-blue-50/30">
                            {{ number_format($result['formative_mark'], 1) }}
                        </td>
                        <td class="py-2.5 px-3 text-center font-semibold text-indigo-800 bg-indigo-50/30">
                            {{ number_format($result['summative_mark'], 1) }}
                        </td>
                        <td class="py-2.5 px-3 text-center font-semibold text-emerald-800 bg-emerald-50/30">
                            {{ number_format($result['att_marks'], 1) }}
                        </td>
                        <td class="py-2.5 px-3 text-center font-black text-slate-900 bg-amber-50/40 text-sm">
                            {{ number_format($result['total_cia'], 1) }}
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            @if($result['is_pass'])
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <x-ui.icon name="check" class="w-3 h-3 text-emerald-600" />
                                    <span>PASS</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-rose-50 text-rose-700 border border-rose-200">
                                    <x-ui.icon name="alert-triangle" class="w-3 h-3 text-rose-600" />
                                    <span>FAIL</span>
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400">
                            <x-ui.icon name="users" class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                            <p>No student results calculated yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
