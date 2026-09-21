<div class="mb-8">
    @include('r21_project.partials.print.header', [
        'reportTitle' => 'Continuous Internal Assessment (CIA) Register (Clause 11.2.5 — Max 75 Marks)'
    ])

    <!-- CIA Scheme Explanation Strip -->
    <div class="border border-slate-300 rounded-lg p-2.5 mb-4 bg-slate-50 text-xs flex justify-between items-center text-slate-700">
        <div>
            <strong>Statutory Scheme:</strong> Formative Weekly Diary 40% (30M) + Summative Dept Review 40% (30M) + Attendance 20% (15M) = 75 Marks Total
        </div>
        <div>
            <strong>Pass Minimum:</strong> 40% (30.0 Marks)
        </div>
    </div>

    <!-- CIA Students Marksheet Table -->
    <div class="w-full overflow-hidden border border-slate-300 rounded-lg mb-4">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 text-slate-800 text-[11px] font-bold">
                    <th rowspan="2" class="py-2 px-2 border-r border-slate-300 w-8 text-center">#</th>
                    <th rowspan="2" class="py-2 px-2 border-r border-slate-300 w-12 text-center">Roll</th>
                    <th rowspan="2" class="py-2 px-2.5 border-r border-slate-300 w-24 text-center">Reg No</th>
                    <th rowspan="2" class="py-2 px-2.5 border-r border-slate-300">Candidate Name</th>
                    <th rowspan="2" class="py-2 px-2 border-r border-slate-300 w-16 text-center">Group</th>
                    <th rowspan="2" class="py-2 px-2 border-r border-slate-300 w-12 text-center">Att. %</th>
                    <th colspan="3" class="py-1.5 px-2 border-r border-slate-300 text-center bg-slate-200/70 text-[10px]">CIA Assessment Split-up</th>
                    <th rowspan="2" class="py-2 px-2.5 border-r border-slate-300 w-20 text-right bg-emerald-50 text-emerald-900 font-extrabold">Total CIA<br>(75M)</th>
                    <th rowspan="2" class="py-2 px-2.5 border-r border-slate-300 w-36 text-left">Marks in Words</th>
                    <th rowspan="2" class="py-2 px-2 border-r border-slate-300 w-12 text-center">Grade</th>
                    <th rowspan="2" class="py-2 px-2 border-r border-slate-300 w-12 text-center">Point</th>
                    <th rowspan="2" class="py-2 px-2 border-r border-slate-300 w-14 text-center">Result</th>
                    <th rowspan="2" class="py-2 px-2 w-24 text-center">Signature</th>
                </tr>
                <tr class="bg-slate-50 border-b border-slate-300 text-slate-700 text-[10px] font-semibold">
                    <th class="py-1 px-2 border-r border-slate-300 w-16 text-right">Attd (15M)</th>
                    <th class="py-1 px-2 border-r border-slate-300 w-16 text-right">Diary (30M)</th>
                    <th class="py-1 px-2 border-r border-slate-300 w-16 text-right">Dept (30M)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @php
                    $ciaPassCount = 0;
                    $ciaFailCount = 0;
                @endphp
                @forelse($students as $st)
                    @php
                        $ciaPass = ($st['total_cia_75'] >= 30.0);
                        if ($st['has_eval']) {
                            if ($ciaPass) $ciaPassCount++;
                            else $ciaFailCount++;
                        }
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="py-1.5 px-2 border-r border-slate-200 text-center font-mono text-slate-500">{{ $loop->iteration }}</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-center font-mono font-semibold">{{ $st['roll_no'] ?: '—' }}</td>
                        <td class="py-1.5 px-2.5 border-r border-slate-200 text-center font-mono font-bold">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                        <td class="py-1.5 px-2.5 border-r border-slate-200 font-medium text-slate-900">{{ $st['name'] }}</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-center text-[10px] font-medium">{{ $st['group_name'] }}</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-center font-mono">{{ $st['att_percentage'] }}%</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-right font-mono">{{ number_format($st['attendance_marks'], 1) }}</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-right font-mono">{{ number_format($st['formative_diary_marks'], 1) }}</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-right font-mono">{{ number_format($st['summative_dept_marks'], 1) }}</td>
                        <td class="py-1.5 px-2.5 border-r border-slate-200 text-right font-mono font-bold text-emerald-800 bg-emerald-50/60">{{ number_format($st['total_cia_75'], 1) }}</td>
                        <td class="py-1.5 px-2.5 border-r border-slate-200 text-left text-[10px] capitalize font-medium text-slate-700">{{ $st['cia_in_words'] }}</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-center font-bold">{{ $st['cia_grade'] }}</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-center font-mono">{{ $st['cia_points'] }}</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-center font-bold {{ $ciaPass ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $st['has_eval'] ? ($ciaPass ? 'PASS' : 'FAIL') : 'PEND' }}
                        </td>
                        <td class="py-1.5 px-2 text-center text-slate-300"></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="py-4 text-center text-slate-400">No students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Summary Statistics Grid -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        <div class="border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-center">
            <div class="text-[10px] text-slate-500 uppercase font-semibold">Total Enrolled</div>
            <div class="text-sm font-extrabold text-slate-900 mt-0.5">{{ $totalStudents }}</div>
        </div>
        <div class="border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-center">
            <div class="text-[10px] text-slate-500 uppercase font-semibold">Evaluated</div>
            <div class="text-sm font-extrabold text-slate-900 mt-0.5">{{ $completedCount }}</div>
        </div>
        <div class="border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-center">
            <div class="text-[10px] text-slate-500 uppercase font-semibold">Passed (&ge; 30.0M)</div>
            <div class="text-sm font-extrabold text-emerald-700 mt-0.5">{{ $ciaPassCount }}</div>
        </div>
        <div class="border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-center">
            <div class="text-[10px] text-slate-500 uppercase font-semibold">Failed (&lt; 30.0M)</div>
            <div class="text-sm font-extrabold text-rose-700 mt-0.5">{{ $ciaFailCount }}</div>
        </div>
        <div class="border border-slate-200 rounded-lg p-2.5 bg-slate-50 text-center">
            <div class="text-[10px] text-slate-500 uppercase font-semibold">Class Avg CIA</div>
            <div class="text-sm font-extrabold text-blue-900 mt-0.5">{{ $avgCiaOverall }} / 75</div>
        </div>
    </div>

    @include('r21_project.partials.print.signatures')
</div>
