<div id="tab-cia" class="tab-content hidden space-y-4">
    <!-- CIA Header and Action Strip -->
    <div class="bg-white/5 border border-slate-700/60 rounded-xl p-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                Continuous Internal Assessment (CIA) — Clause 11.2.5
            </h2>
            <div class="text-xs text-slate-400 mt-0.5">
                Maximum 75 Marks: Weekly Diary (30M) + Department Evaluation (30M) + Attendance (15M)
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-ui.button 
                variant="secondary" 
                size="sm" 
                icon="users"
                onclick="openGroupCiaModal()"
                title="Apply Common Diary and Dept Marks to Group">
                <span class="ml-1.5">Group Common CIA</span>
            </x-ui.button>
        </div>
    </div>

    <!-- CIA Evaluation Table -->
    <x-ui.table :headers="[
        '#',
        'Roll / Reg No',
        'Student Name',
        'Attd %',
        'Attd Mark (15M)',
        'Weekly Diary (30M)',
        'Dept Review (30M)',
        'CIA Total (75M)',
        'CIA Grade',
        'Status',
        'Action'
    ]">
        @forelse($studentResults as $res)
            <tr class="hover:bg-slate-800/40 transition-colors" data-student-cia-row data-reg-no="{{ $res['reg_no'] }}">
                <td class="py-2.5 px-4 text-xs font-mono text-slate-400">{{ $loop->iteration }}</td>
                <td class="py-2.5 px-4 text-xs font-mono font-semibold text-slate-200">
                    <div>{{ $res['reg_no'] }}</div>
                    @if($res['roll_no'])
                        <div class="text-[10px] text-slate-500">Roll: {{ $res['roll_no'] }}</div>
                    @endif
                </td>
                <td class="py-2.5 px-4 text-xs font-medium text-white">{{ $res['name'] }}</td>
                <td class="py-2.5 px-4 text-xs font-mono text-center">
                    <span class="{{ $res['att_percentage'] >= 75 ? 'text-emerald-400' : 'text-rose-400' }} font-bold">
                        {{ number_format($res['att_percentage'], 1) }}%
                    </span>
                </td>
                <td class="py-2.5 px-4 text-xs font-mono text-right text-slate-200">{{ number_format($res['attendance_marks'], 1) }}</td>
                <td class="py-2.5 px-4 text-xs font-mono text-right text-slate-200">{{ number_format($res['formative_diary_marks'], 1) }}</td>
                <td class="py-2.5 px-4 text-xs font-mono text-right text-slate-200">{{ number_format($res['summative_dept_marks'], 1) }}</td>
                <td class="py-2.5 px-4 text-xs font-mono font-extrabold text-right text-emerald-400">{{ number_format($res['total_cia_75'], 1) }}</td>
                <td class="py-2.5 px-4 text-xs text-center">
                    <x-ui.badge variant="neutral">{{ $res['cia_grade'] }}</x-ui.badge>
                </td>
                <td class="py-2.5 px-4 text-xs text-center">
                    @if($res['total_cia_75'] >= 30.0)
                        <x-ui.badge variant="success">Eligible</x-ui.badge>
                    @elseif($res['has_eval'])
                        <x-ui.badge variant="danger">Below Min</x-ui.badge>
                    @else
                        <x-ui.badge variant="neutral">Pending</x-ui.badge>
                    @endif
                </td>
                <td class="py-2.5 px-4 text-xs text-center">
                    <x-ui.button 
                        variant="secondary" 
                        size="sm" 
                        onclick="openCiaEvalModal('{{ $res['reg_no'] }}')">
                        Evaluate CIA
                    </x-ui.button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="py-8 text-center text-slate-400 text-sm">
                    No students currently enrolled in this Major Project classroom.
                </td>
            </tr>
        @endforelse
    </x-ui.table>
</div>
