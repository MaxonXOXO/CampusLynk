@forelse($groupedProjects as $grp)
    <div class="page-break mb-12">
        @include('r21_project.partials.print.header', [
            'reportTitle' => 'Major Project Group-Wise Assessment Record — ' . $grp['name']
        ])

        <!-- Group Dossier Metadata Strip -->
        <div class="border border-slate-300 rounded-lg p-3 mb-4 bg-slate-50 text-xs flex flex-wrap justify-between items-center gap-3">
            <div>
                <span class="text-[10px] text-slate-500 uppercase font-semibold block">Project Group</span>
                <span class="font-extrabold text-slate-900 text-sm">{{ $grp['name'] }}</span>
            </div>
            <div class="flex-1 min-w-[200px] px-4">
                <span class="text-[10px] text-slate-500 uppercase font-semibold block">Approved Project Title</span>
                <span class="font-bold text-slate-900">{{ $grp['title'] ?: 'Pending Title Allocation' }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-500 uppercase font-semibold block">Faculty Project Guide</span>
                <span class="font-bold text-slate-900">{{ $grp['guide_name'] ?: 'Not Assigned' }}</span>
            </div>
            <div>
                <span class="text-[10px] text-slate-500 uppercase font-semibold block">Batch Size</span>
                <span class="font-bold text-slate-900 font-mono">{{ $grp['total_students'] }} Students</span>
            </div>
        </div>

        <!-- Group Students Marks Table -->
        <div class="w-full overflow-hidden border border-slate-300 rounded-lg mb-4">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300 text-slate-800 text-[11px] font-bold">
                        <th class="py-2.5 px-3 border-r border-slate-300 w-10 text-center">#</th>
                        <th class="py-2.5 px-3 border-r border-slate-300 w-20 text-center">Roll No</th>
                        <th class="py-2.5 px-3 border-r border-slate-300 w-28 text-center">Register No</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Student Name</th>
                        <th class="py-2.5 px-3 border-r border-slate-300 w-20 text-right">Diary (30M)</th>
                        <th class="py-2.5 px-3 border-r border-slate-300 w-20 text-right">Dept (30M)</th>
                        <th class="py-2.5 px-3 border-r border-slate-300 w-20 text-right">Attd (15M)</th>
                        <th class="py-2.5 px-3 border-r border-slate-300 w-24 text-right bg-emerald-50 text-emerald-900">CIA (75M)</th>
                        <th class="py-2.5 px-3 border-r border-slate-300 w-24 text-right bg-sky-50 text-sky-900">ESE (50M)</th>
                        <th class="py-2.5 px-3 border-r border-slate-300 w-24 text-right bg-blue-50 text-blue-900">Total (125M)</th>
                        <th class="py-2.5 px-3 border-r border-slate-300 w-16 text-center">Grade</th>
                        <th class="py-2.5 px-3 w-16 text-center">Result</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($grp['students'] as $st)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2 px-3 border-r border-slate-200 text-center font-mono text-slate-500">{{ $loop->iteration }}</td>
                            <td class="py-2 px-3 border-r border-slate-200 text-center font-mono font-semibold">{{ $st['roll_no'] ?: '—' }}</td>
                            <td class="py-2 px-3 border-r border-slate-200 text-center font-mono font-bold">{{ $st['reg_no'] }}</td>
                            <td class="py-2 px-3 border-r border-slate-200 font-medium text-slate-900">{{ $st['name'] }}</td>
                            <td class="py-2 px-3 border-r border-slate-200 text-right font-mono">{{ number_format($st['formative_diary_marks'], 1) }}</td>
                            <td class="py-2 px-3 border-r border-slate-200 text-right font-mono">{{ number_format($st['summative_dept_marks'], 1) }}</td>
                            <td class="py-2 px-3 border-r border-slate-200 text-right font-mono">{{ number_format($st['attendance_marks'], 1) }}</td>
                            <td class="py-2 px-3 border-r border-slate-200 text-right font-mono font-bold text-emerald-700 bg-emerald-50/50">{{ number_format($st['total_cia_75'], 1) }}</td>
                            <td class="py-2 px-3 border-r border-slate-200 text-right font-mono font-bold text-sky-700 bg-sky-50/50">{{ number_format($st['total_ese_50'], 1) }}</td>
                            <td class="py-2 px-3 border-r border-slate-200 text-right font-mono font-extrabold text-blue-900 bg-blue-50/50">{{ number_format($st['grand_total_125'], 1) }}</td>
                            <td class="py-2 px-3 border-r border-slate-200 text-center font-bold">{{ $st['final_grade'] }}</td>
                            <td class="py-2 px-3 text-center font-bold {{ $st['passed'] ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $st['passed'] ? 'PASS' : ($st['has_eval'] ? 'FAIL' : 'PEND') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="py-4 text-center text-slate-400">No students allocated to this group.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-100/80 border-t border-slate-300 font-bold text-[11px] text-slate-800">
                        <td colspan="7" class="py-2 px-3 text-right">Group Average Marks:</td>
                        <td class="py-2 px-3 text-right font-mono text-emerald-800 bg-emerald-100/50">{{ number_format($grp['avg_cia'], 1) }}</td>
                        <td class="py-2 px-3 text-right font-mono text-sky-800 bg-sky-100/50">{{ number_format($grp['avg_ese'], 1) }}</td>
                        <td class="py-2 px-3 text-right font-mono text-blue-900 bg-blue-100/50">{{ number_format($grp['avg_total'], 1) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @include('r21_project.partials.print.signatures', [
            'guideName' => $grp['guide_name']
        ])
    </div>
@empty
    <div class="p-8 text-center text-slate-500">
        No project groups configured for this classroom.
    </div>
@endforelse
