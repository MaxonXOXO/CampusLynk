<div class="mb-8">
    @include('r21_project.partials.print.header', [
        'reportTitle' => 'Major Project End Semester Evaluation (ESE) 8-Rubric Register (Clause 11.3.4 — Max 50 Marks)'
    ])

    <!-- Clause 11.3.4 Statutory Rule Strip -->
    <div class="border border-slate-300 rounded-lg p-2.5 mb-4 bg-slate-50 text-xs flex justify-between items-center text-slate-700">
        <div>
            <strong>Statutory Rule (Clause 11.3.4):</strong> Internal and External Examiners shall jointly conduct the End Semester Evaluation based on the 8 prescribed rubrics (Total: 50 Marks).
        </div>
        <div>
            <strong>Pass Minimum:</strong> 40% (20.0 Marks)
        </div>
    </div>

    <!-- 8-Rubric Evaluation Table -->
    <div class="w-full overflow-hidden border border-slate-300 rounded-lg mb-4">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 text-slate-800 text-[11px] font-bold">
                    <th rowspan="2" class="py-2 px-2 border-r border-slate-300 w-8 text-center">#</th>
                    <th rowspan="2" class="py-2 px-2 border-r border-slate-300 w-12 text-center">Roll</th>
                    <th rowspan="2" class="py-2 px-2.5 border-r border-slate-300 w-24 text-center">Reg No</th>
                    <th rowspan="2" class="py-2 px-2.5 border-r border-slate-300">Candidate Name</th>
                    <th rowspan="2" class="py-2 px-2 border-r border-slate-300 w-16 text-center">Group</th>
                    <th colspan="8" class="py-1.5 px-2 border-r border-slate-300 text-center bg-amber-100/60 text-amber-950 text-[10px]">
                        Clause 11.3.4 Statutory Evaluation Rubrics (Max 50 Marks)
                    </th>
                    <th rowspan="2" class="py-2 px-2.5 border-r border-slate-300 w-20 text-right bg-amber-50 text-amber-900 font-extrabold">Total ESE<br>(50M)</th>
                    <th rowspan="2" class="py-2 px-2 border-r border-slate-300 w-12 text-center">Grade</th>
                    <th rowspan="2" class="py-2 px-2 w-14 text-center">Result</th>
                </tr>
                <tr class="bg-slate-50 border-b border-slate-300 text-slate-700 text-[10px] font-semibold">
                    <th class="py-1 px-1.5 border-r border-slate-300 w-12 text-right" title="Working Model / Prototype (10M)">Model<br>(10M)</th>
                    <th class="py-1 px-1.5 border-r border-slate-300 w-12 text-right" title="Modern Tools & Technology (5M)">Tools<br>(5M)</th>
                    <th class="py-1 px-1.5 border-r border-slate-300 w-12 text-right" title="Presentation (7.5M)">Pres<br>(7.5M)</th>
                    <th class="py-1 px-1.5 border-r border-slate-300 w-12 text-right" title="Innovativeness (2.5M)">Inno<br>(2.5M)</th>
                    <th class="py-1 px-1.5 border-r border-slate-300 w-12 text-right" title="Viva Voce (7.5M)">Viva<br>(7.5M)</th>
                    <th class="py-1 px-1.5 border-r border-slate-300 w-12 text-right" title="Individual Contribution (7.5M)">Indiv<br>(7.5M)</th>
                    <th class="py-1 px-1.5 border-r border-slate-300 w-12 text-right" title="Group Activity (5M)">Grp<br>(5M)</th>
                    <th class="py-1 px-1.5 border-r border-slate-300 w-12 text-right" title="Project Report & Documentation (5M)">Rep<br>(5M)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($students as $st)
                    @php
                        $esePassed = ($st['total_ese_50'] >= 20.0);
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="py-1.5 px-2 border-r border-slate-200 text-center font-mono text-slate-500">{{ $loop->iteration }}</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-center font-mono font-semibold">{{ $st['roll_no'] ?: '—' }}</td>
                        <td class="py-1.5 px-2.5 border-r border-slate-200 text-center font-mono font-bold">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                        <td class="py-1.5 px-2.5 border-r border-slate-200 font-medium text-slate-900">{{ $st['name'] }}</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-center text-[10px] font-medium">{{ $st['group_name'] }}</td>
                        <td class="py-1.5 px-1.5 border-r border-slate-200 text-right font-mono">{{ number_format($st['ese_prototype'], 1) }}</td>
                        <td class="py-1.5 px-1.5 border-r border-slate-200 text-right font-mono">{{ number_format($st['ese_modern_tools'], 1) }}</td>
                        <td class="py-1.5 px-1.5 border-r border-slate-200 text-right font-mono">{{ number_format($st['ese_presentation'], 1) }}</td>
                        <td class="py-1.5 px-1.5 border-r border-slate-200 text-right font-mono">{{ number_format($st['ese_innovativeness'], 1) }}</td>
                        <td class="py-1.5 px-1.5 border-r border-slate-200 text-right font-mono">{{ number_format($st['ese_viva'], 1) }}</td>
                        <td class="py-1.5 px-1.5 border-r border-slate-200 text-right font-mono">{{ number_format($st['ese_individual_contrib'], 1) }}</td>
                        <td class="py-1.5 px-1.5 border-r border-slate-200 text-right font-mono">{{ number_format($st['ese_group_activity'], 1) }}</td>
                        <td class="py-1.5 px-1.5 border-r border-slate-200 text-right font-mono">{{ number_format($st['ese_project_report'], 1) }}</td>
                        <td class="py-1.5 px-2.5 border-r border-slate-200 text-right font-mono font-bold text-amber-900 bg-amber-50/70">{{ number_format($st['total_ese_50'], 1) }}</td>
                        <td class="py-1.5 px-2 border-r border-slate-200 text-center font-bold">{{ $st['ese_grade'] }}</td>
                        <td class="py-1.5 px-2 text-center font-bold {{ $esePassed ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $st['has_ese'] ? ($esePassed ? 'PASS' : 'FAIL') : 'PEND' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="16" class="py-4 text-center text-slate-400">No candidates recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('r21_project.partials.print.signatures')
</div>
