<div id="tab-reports" class="tab-content hidden space-y-6">
    <!-- Statutory Report Launchers Grid -->
    <div>
        <h2 class="text-sm font-bold text-white mb-3 flex items-center gap-2">
            <x-ui.icon name="printer" class="w-4 h-4 text-blue-400" />
            <span>SBTE Statutory Project Print Reports</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Report 1: Group-Wise Breakdown -->
            <x-ui.card>
                <div class="flex flex-col h-full justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="p-1.5 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                <x-ui.icon name="folder" class="w-4 h-4" />
                            </span>
                            <h3 class="text-xs font-bold text-white">Group-Wise Breakdown</h3>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            1 single page per group with CIA (75M) + ESE (50M) = 125M and Examiner Signatures for separate filing.
                        </p>
                    </div>
                    <a href="/r21/classroom/project/{{ $batchSubject->id }}/report/print?type=group_breakdown" target="_blank">
                        <x-ui.button variant="secondary" size="sm" class="w-full" icon="printer">
                            <span class="ml-1.5">Print Group Breakdown</span>
                        </x-ui.button>
                    </a>
                </div>
            </x-ui.card>

            <!-- Report 2: CIA Register -->
            <x-ui.card>
                <div class="flex flex-col h-full justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <x-ui.icon name="edit" class="w-4 h-4" />
                            </span>
                            <h3 class="text-xs font-bold text-white">CIA Assessment Register</h3>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Clause 11.2.5 statutory split-up: Diary (30M) + Dept (30M) + Attendance (15M) = 75M.
                        </p>
                    </div>
                    <a href="/r21/classroom/project/{{ $batchSubject->id }}/report/print?type=cia_register" target="_blank">
                        <x-ui.button variant="secondary" size="sm" class="w-full" icon="printer">
                            <span class="ml-1.5">Print CIA Register</span>
                        </x-ui.button>
                    </a>
                </div>
            </x-ui.card>

            <!-- Report 3: SBTE Final Mark Statement -->
            <x-ui.card>
                <div class="flex flex-col h-full justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="p-1.5 rounded-lg bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                <x-ui.icon name="check-circle" class="w-4 h-4" />
                            </span>
                            <h3 class="text-xs font-bold text-white">SBTE Official Mark Statement</h3>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Official mark entry sheet for Controller of Exams / SBTE portal submission (125M).
                        </p>
                    </div>
                    <a href="/r21/classroom/project/{{ $batchSubject->id }}/report/print?type=sbte_submission" target="_blank">
                        <x-ui.button variant="secondary" size="sm" class="w-full" icon="printer">
                            <span class="ml-1.5">Print SBTE Statement</span>
                        </x-ui.button>
                    </a>
                </div>
            </x-ui.card>

            <!-- Report 4: ESE 8-Rubrics Score Sheet -->
            <x-ui.card>
                <div class="flex flex-col h-full justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="p-1.5 rounded-lg bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                <x-ui.icon name="award" class="w-4 h-4" />
                            </span>
                            <h3 class="text-xs font-bold text-white">Clause 11.3.4 ESE Score Sheet</h3>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Detailed 8-rubric score sheet signed jointly by Internal and External Examiners (50M).
                        </p>
                    </div>
                    <a href="/r21/classroom/project/{{ $batchSubject->id }}/report/print?type=ese_rubrics" target="_blank">
                        <x-ui.button variant="secondary" size="sm" class="w-full" icon="printer">
                            <span class="ml-1.5">Print ESE Rubrics</span>
                        </x-ui.button>
                    </a>
                </div>
            </x-ui.card>

            <!-- Report 5: Consolidated Broad Register -->
            <x-ui.card>
                <div class="flex flex-col h-full justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="p-1.5 rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                <x-ui.icon name="table" class="w-4 h-4" />
                            </span>
                            <h3 class="text-xs font-bold text-white">Consolidated Broad Register</h3>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Roll-wise broad register with full CIA + ESE split-up, attendance, and final grades.
                        </p>
                    </div>
                    <a href="/r21/classroom/project/{{ $batchSubject->id }}/report/print?type=consolidated" target="_blank">
                        <x-ui.button variant="secondary" size="sm" class="w-full" icon="printer">
                            <span class="ml-1.5">Print Broad Register</span>
                        </x-ui.button>
                    </a>
                </div>
            </x-ui.card>
        </div>
    </div>

    <!-- Consolidated Split-Up Broad Register Preview Table -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-white flex items-center gap-2">
                <x-ui.icon name="table" class="w-4 h-4 text-slate-400" />
                <span>Broad Register Multi-Column Preview</span>
            </h2>
            <span class="text-xs text-slate-400 font-mono">{{ count($studentResults) }} Enrolled</span>
        </div>

        <x-ui.table :headers="[
            '#',
            'Roll / Reg No',
            'Student Name',
            'Diary (30M)',
            'Dept (30M)',
            'Attd (15M)',
            'CIA (75M)',
            'Proto (10M)',
            'Tools (5M)',
            'Pres (7.5M)',
            'Inno (2.5M)',
            'Viva (7.5M)',
            'Indiv (7.5M)',
            'Grp (5M)',
            'Rep (5M)',
            'ESE (50M)',
            'Total (125M)',
            'Result'
        ]">
            @forelse($studentResults as $res)
                <tr class="hover:bg-slate-800/40 transition-colors">
                    <td class="py-2 px-3 text-xs font-mono text-slate-400">{{ $loop->iteration }}</td>
                    <td class="py-2 px-3 text-xs font-mono font-semibold text-slate-200">{{ $res['reg_no'] }}</td>
                    <td class="py-2 px-3 text-xs font-medium text-white">{{ $res['name'] }}</td>
                    <td class="py-2 px-2 text-xs font-mono text-right text-slate-300">{{ number_format($res['formative_diary_marks'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono text-right text-slate-300">{{ number_format($res['summative_dept_marks'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono text-right text-slate-300">{{ number_format($res['attendance_marks'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono font-bold text-right text-emerald-400">{{ number_format($res['total_cia_75'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono text-right text-slate-300">{{ number_format($res['ese_prototype'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono text-right text-slate-300">{{ number_format($res['ese_modern_tools'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono text-right text-slate-300">{{ number_format($res['ese_presentation'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono text-right text-slate-300">{{ number_format($res['ese_innovativeness'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono text-right text-slate-300">{{ number_format($res['ese_viva'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono text-right text-slate-300">{{ number_format($res['ese_individual_contrib'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono text-right text-slate-300">{{ number_format($res['ese_group_activity'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono text-right text-slate-300">{{ number_format($res['ese_project_report'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono font-bold text-right text-sky-400">{{ number_format($res['total_ese_50'], 1) }}</td>
                    <td class="py-2 px-2 text-xs font-mono font-extrabold text-right text-blue-400">{{ number_format($res['grand_total_125'], 1) }}</td>
                    <td class="py-2 px-3 text-xs text-center">
                        <x-ui.badge :variant="$res['passed'] ? 'success' : ($res['has_eval'] ? 'danger' : 'neutral')">
                            {{ $res['passed'] ? 'PASS' : ($res['has_eval'] ? 'FAIL' : 'PENDING') }}
                        </x-ui.badge>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="18" class="py-8 text-center text-slate-400 text-sm">
                        No student evaluations available.
                    </td>
                </tr>
            @endforelse
        </x-ui.table>
    </div>
</div>
