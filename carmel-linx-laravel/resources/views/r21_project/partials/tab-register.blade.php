<div id="tab-register" class="tab-content block space-y-4">
    <!-- Search and Filter Bar -->
    <div class="bg-white/5 border border-slate-700/60 rounded-xl p-3 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2 flex-1 min-w-[240px]">
            <div class="relative w-full max-w-xs">
                <input 
                    type="text" 
                    id="searchRegisterInput" 
                    placeholder="Search by name, reg no, or roll..." 
                    onkeyup="filterTable('mainMarksheetTable', this.value)"
                    class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <span>Showing <strong class="text-white">{{ count($studentResults) }}</strong> students</span>
        </div>
    </div>

    <!-- Master Marksheet Table -->
    <x-ui.table :headers="[
        '#',
        'Roll / Reg No',
        'Student Name',
        'Group',
        'Project Title',
        'Diary (30M)',
        'Dept (30M)',
        'Attd (15M)',
        'CIA Tot (75M)',
        'ESE Tot (50M)',
        'ESE Grade',
        'Grand Tot (125M)',
        'Final Grade',
        'Result',
        'Action'
    ]">
        @forelse($studentResults as $res)
            <tr class="hover:bg-slate-800/40 transition-colors" data-student-row data-reg-no="{{ $res['reg_no'] }}" data-student-name="{{ strtolower($res['name']) }}">
                <td class="py-2.5 px-4 text-xs font-mono text-slate-400">{{ $loop->iteration }}</td>
                <td class="py-2.5 px-4 text-xs font-mono font-semibold text-slate-200">
                    <div>{{ $res['reg_no'] }}</div>
                    @if($res['roll_no'])
                        <div class="text-[10px] text-slate-500">Roll: {{ $res['roll_no'] }}</div>
                    @endif
                </td>
                <td class="py-2.5 px-4 text-xs font-medium text-white">{{ $res['name'] }}</td>
                <td class="py-2.5 px-4 text-xs">
                    <x-ui.badge variant="info">{{ $res['group_name'] }}</x-ui.badge>
                </td>
                <td class="py-2.5 px-4 text-xs text-slate-300 max-w-[180px] truncate" title="{{ $res['project_title'] }}">
                    {{ $res['project_title'] ?: '—' }}
                </td>
                <td class="py-2.5 px-4 text-xs font-mono text-right text-slate-200">{{ number_format($res['formative_diary_marks'], 1) }}</td>
                <td class="py-2.5 px-4 text-xs font-mono text-right text-slate-200">{{ number_format($res['summative_dept_marks'], 1) }}</td>
                <td class="py-2.5 px-4 text-xs font-mono text-right text-slate-200">{{ number_format($res['attendance_marks'], 1) }}</td>
                <td class="py-2.5 px-4 text-xs font-mono font-bold text-right text-emerald-400">{{ number_format($res['total_cia_75'], 1) }}</td>
                <td class="py-2.5 px-4 text-xs font-mono font-bold text-right text-sky-400">{{ number_format($res['total_ese_50'], 1) }}</td>
                <td class="py-2.5 px-4 text-xs font-bold text-center text-slate-200">{{ $res['ese_grade'] }}</td>
                <td class="py-2.5 px-4 text-xs font-mono font-extrabold text-right text-blue-400">{{ number_format($res['grand_total_125'], 1) }}</td>
                <td class="py-2.5 px-4 text-xs text-center">
                    <x-ui.badge variant="neutral">{{ $res['final_grade'] }}</x-ui.badge>
                </td>
                <td class="py-2.5 px-4 text-xs text-center">
                    @if($res['passed'])
                        <x-ui.badge variant="success">PASS</x-ui.badge>
                    @elseif($res['has_eval'])
                        <x-ui.badge variant="danger">FAIL</x-ui.badge>
                    @else
                        <x-ui.badge variant="neutral">PENDING</x-ui.badge>
                    @endif
                </td>
                <td class="py-2.5 px-4 text-xs text-center">
                    <x-ui.button 
                        variant="secondary" 
                        size="sm" 
                        class="btn-eval"
                        data-reg-no="{{ $res['reg_no'] }}"
                        onclick="openEvalModal('{{ $res['reg_no'] }}')">
                        Evaluate
                    </x-ui.button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="15" class="py-8 text-center text-slate-400 text-sm">
                    No students currently enrolled in this Major Project classroom.
                </td>
            </tr>
        @endforelse
    </x-ui.table>
</div>
