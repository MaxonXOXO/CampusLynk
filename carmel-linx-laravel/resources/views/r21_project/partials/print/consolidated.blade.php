<div class="mb-8">
    @include('r21_project.partials.print.header', [
        'reportTitle' => 'Consolidated Major Project Broad Register (Clauses 11.2.5 & 11.3.4 — Total 125 Marks)'
    ])

    <!-- Scheme Summary Strip -->
    <div class="border border-slate-300 rounded-lg p-2.5 mb-4 bg-slate-50 text-xs flex justify-between items-center text-slate-700">
        <div>
            <strong>Evaluation Scheme:</strong> Continuous Internal Assessment (75M) + End Semester Examination (50M) = 125 Marks Total
        </div>
        <div>
            <strong>Curriculum Regulation:</strong> State Board of Technical Education, Kerala (Revision 2021)
        </div>
    </div>

    <!-- Consolidated Broad Register Table -->
    <div class="w-full overflow-hidden border border-slate-300 rounded-lg mb-4">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 text-slate-800 text-[11px] font-bold">
                    <th class="py-2.5 px-2 border-r border-slate-300 w-8 text-center">#</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-12 text-center">Roll</th>
                    <th class="py-2.5 px-2.5 border-r border-slate-300 w-24 text-center">Reg No</th>
                    <th class="py-2.5 px-2.5 border-r border-slate-300">Student Name</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-16 text-center">Group</th>
                    <th class="py-2.5 px-2.5 border-r border-slate-300">Project Title</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-12 text-center">Att. %</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-16 text-right bg-emerald-50 text-emerald-900">CIA (75M)</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-16 text-right bg-sky-50 text-sky-900">ESE (50M)</th>
                    <th class="py-2.5 px-2.5 border-r border-slate-300 w-20 text-right bg-blue-50 text-blue-900 font-extrabold">Total (125M)</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-12 text-center">Grade</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-12 text-center">Point</th>
                    <th class="py-2.5 px-2 border-r border-slate-300 w-14 text-center">Result</th>
                    <th class="py-2.5 px-2.5 w-28 text-left">Guide</th>
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
                        <td class="py-2 px-2.5 border-r border-slate-200 text-slate-700 text-[11px] leading-tight">{{ $st['project_title'] }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-center font-mono">{{ $st['att_percentage'] }}%</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-right font-mono font-bold text-emerald-700 bg-emerald-50/50">{{ number_format($st['total_cia_75'], 1) }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-right font-mono font-bold text-sky-700 bg-sky-50/50">{{ number_format($st['total_ese_50'], 1) }}</td>
                        <td class="py-2 px-2.5 border-r border-slate-200 text-right font-mono font-extrabold text-blue-900 bg-blue-50/60">{{ number_format($st['grand_total_125'], 1) }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-center font-bold">{{ $st['final_grade'] }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-center font-mono">{{ $st['final_points'] }}</td>
                        <td class="py-2 px-2 border-r border-slate-200 text-center font-bold {{ $st['passed'] ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $st['has_eval'] ? ($st['passed'] ? 'PASS' : 'FAIL') : 'PEND' }}
                        </td>
                        <td class="py-2 px-2.5 text-slate-700 text-[11px]">{{ $st['guide_name'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="py-4 text-center text-slate-400">No students recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('r21_project.partials.print.signatures')
</div>
