<div class="mb-8">
    @include('r21_project.partials.print.header', [
        'reportTitle' => 'Diploma Examination (Revision 2021) — Major Project Final Mark Statement (CIA 75M + ESE 50M)'
    ])

    <!-- Scheme & Assessment Ratio Strip -->
    <div class="border border-slate-300 rounded-lg p-2.5 mb-4 bg-slate-50 text-xs flex justify-between items-center text-slate-700">
        <div>
            <strong>Assessment Ratio:</strong> Ratio 3:2 | Continuous Assessment: 75 Marks | End Semester Exam: 50 Marks | Grand Total: 125 Marks
        </div>
        <div>
            <strong>Passing Criteria:</strong> CIA &ge; 30.0M, ESE &ge; 20.0M, Grand Total &ge; 50.0M
        </div>
    </div>

    <!-- Official SBTE Final Marksheet Table -->
    <div class="w-full overflow-hidden border border-slate-300 rounded-lg mb-4">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 text-slate-800 text-[11px] font-bold">
                    <th class="py-2.5 px-2 border-r border-slate-300 w-8 text-center">#</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-12 text-center">Roll</th>
                    <th class="py-2.5 px-2.5 border-r border-slate-300 w-24 text-center">Reg No</th>
                    <th class="py-2.5 px-2.5 border-r border-slate-300">Name of Candidate</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-16 text-center">Group</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-12 text-center">Att. %</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-16 text-right bg-emerald-50 text-emerald-900">CIA (75M)</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-16 text-right bg-sky-50 text-sky-900">ESE (50M)</th>
                    <th class="py-2.5 px-2.5 border-r border-slate-300 w-20 text-right bg-blue-50 text-blue-900 font-extrabold">Total (125M)</th>
                    <th class="py-2.5 px-2.5 border-r border-slate-300 w-40 text-left">Total Marks in Words</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-12 text-center">Grade</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-12 text-center">Point</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-14 text-center">Result</th>
                    <th class="py-2.5 px-2 w-24 text-center">Signature</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($students as $st)
                    <tr class="hover:bg-slate-50">
                        <td class="py-2 px-2 border-r border-slate-200 text-center font-mono text-slate-500">{{ $loop->iteration }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-center font-mono font-semibold">{{ $st['roll_no'] ?: '—' }}</td>
                        <td class="py-2 px-2.5 border-r border-slate-200 text-center font-mono font-bold">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                        <td class="py-2 px-2.5 border-r border-slate-200 font-medium text-slate-900">{{ $st['name'] }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-center text-[10px] font-medium">{{ $st['group_name'] }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-center font-mono">{{ $st['att_percentage'] }}%</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-right font-mono font-bold text-emerald-700 bg-emerald-50/50">{{ number_format($st['total_cia_75'], 1) }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-right font-mono font-bold text-sky-700 bg-sky-50/50">{{ number_format($st['total_ese_50'], 1) }}</td>
                        <td class="py-2 px-2.5 border-r border-slate-200 text-right font-mono font-extrabold text-blue-900 bg-blue-50/60">{{ number_format($st['grand_total_125'], 1) }}</td>
                        <td class="py-2 px-2.5 border-r border-slate-200 text-left text-[10px] capitalize font-medium text-slate-700">{{ $st['score_in_words'] }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-center font-bold">{{ $st['final_grade'] }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-center font-mono">{{ $st['final_points'] }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-center font-bold {{ $st['passed'] ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $st['has_eval'] ? ($st['passed'] ? 'PASS' : 'FAIL') : 'PEND' }}
                        </td>
                        <td class="py-2 px-2 text-center text-slate-300"></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="py-4 text-center text-slate-400">No students recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Statistical Summary Grid -->
    <div class="grid grid-cols-2 md:grid-cols-7 gap-2.5 mb-4">
        <div class="border border-slate-200 rounded-lg p-2 bg-slate-50 text-center">
            <div class="text-[9px] text-slate-500 uppercase font-semibold">Registered</div>
            <div class="text-xs font-extrabold text-slate-900 mt-0.5">{{ $totalStudents }}</div>
        </div>
        <div class="border border-slate-200 rounded-lg p-2 bg-slate-50 text-center">
            <div class="text-[9px] text-slate-500 uppercase font-semibold">Appeared</div>
            <div class="text-xs font-extrabold text-slate-900 mt-0.5">{{ $completedCount }}</div>
        </div>
        <div class="border border-slate-200 rounded-lg p-2 bg-slate-50 text-center">
            <div class="text-[9px] text-slate-500 uppercase font-semibold">Passed / Failed</div>
            <div class="text-xs font-extrabold text-slate-900 mt-0.5">{{ $passedCount }} / {{ $failedCount }}</div>
        </div>
        <div class="border border-slate-200 rounded-lg p-2 bg-slate-50 text-center">
            <div class="text-[9px] text-slate-500 uppercase font-semibold">Pass Rate</div>
            <div class="text-xs font-extrabold text-emerald-700 mt-0.5">{{ $passRate }}%</div>
        </div>
        <div class="border border-slate-200 rounded-lg p-2 bg-slate-50 text-center">
            <div class="text-[9px] text-slate-500 uppercase font-semibold">Avg CIA / 75</div>
            <div class="text-xs font-extrabold text-slate-800 mt-0.5">{{ $avgCiaOverall }}</div>
        </div>
        <div class="border border-slate-200 rounded-lg p-2 bg-slate-50 text-center">
            <div class="text-[9px] text-slate-500 uppercase font-semibold">Avg ESE / 50</div>
            <div class="text-xs font-extrabold text-slate-800 mt-0.5">{{ $avgEseOverall }}</div>
        </div>
        <div class="border border-slate-200 rounded-lg p-2 bg-slate-50 text-center">
            <div class="text-[9px] text-slate-500 uppercase font-semibold">Avg Grand / 125</div>
            <div class="text-xs font-extrabold text-blue-900 mt-0.5">{{ $avgGrandOverall }}</div>
        </div>
    </div>

    <!-- Statutory Certification & Declaration -->
    <div class="border border-dashed border-slate-300 rounded-lg p-3 mb-6 bg-slate-50/50 text-[11px] text-slate-700 leading-relaxed">
        <strong>STATUTORY CERTIFICATION &amp; DECLARATION:</strong><br>
        Certified that the Continuous Internal Assessment (CIA - 75 Marks) and End Semester Examination (ESE - 50 Marks) for the Major Project course have been evaluated strictly as per SBTE Kerala Diploma Curriculum (Revision 2021) Clauses 11.2.5 and 11.3.4. The marks and grades entered above are authentic and verified against the department project evaluation records.
    </div>

    @include('r21_project.partials.print.signatures')
</div>
