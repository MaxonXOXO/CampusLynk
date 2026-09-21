<div id="tab-ese" class="tab-content hidden space-y-4">
    <!-- ESE Header and Action Strip -->
    <div class="bg-white/5 border border-slate-700/60 rounded-xl p-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-white flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-sky-400"></span>
                End Semester Examination (ESE) — Clause 11.3.4 (8 Rubrics)
            </h2>
            <div class="text-xs text-slate-400 mt-0.5">
                Maximum 50 Marks: Evaluated jointly by Internal and External Examiners
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-ui.button 
                variant="secondary" 
                size="sm" 
                icon="award"
                onclick="openExaminersModal()"
                title="Configure Internal & External Examiners Panel">
                <span class="ml-1.5">Examiners Panel</span>
            </x-ui.button>
            <x-ui.button 
                variant="secondary" 
                size="sm" 
                icon="users"
                onclick="openGroupEseModal()"
                title="Batch Apply ESE 8-Rubrics to Group">
                <span class="ml-1.5">Group ESE Assessment</span>
            </x-ui.button>
        </div>
    </div>

    <!-- ESE 8-Rubrics Table -->
    <x-ui.table :headers="[
        '#',
        'Roll / Reg No',
        'Student Name',
        'Proto (10M)',
        'Tools (5M)',
        'Pres (7.5M)',
        'Inno (2.5M)',
        'Viva (7.5M)',
        'Indiv (7.5M)',
        'Grp (5M)',
        'Rep (5M)',
        'ESE Tot (50M)',
        'Grade',
        'Status',
        'Action'
    ]">
        @forelse($studentResults as $res)
            <tr class="hover:bg-slate-800/40 transition-colors" data-student-ese-row data-reg-no="{{ $res['reg_no'] }}">
                <td class="py-2.5 px-3 text-xs font-mono text-slate-400">{{ $loop->iteration }}</td>
                <td class="py-2.5 px-3 text-xs font-mono font-semibold text-slate-200">
                    <div>{{ $res['reg_no'] }}</div>
                    @if($res['roll_no'])
                        <div class="text-[10px] text-slate-500">Roll: {{ $res['roll_no'] }}</div>
                    @endif
                </td>
                <td class="py-2.5 px-3 text-xs font-medium text-white">{{ $res['name'] }}</td>
                <td class="py-2.5 px-3 text-xs font-mono text-right text-slate-200">{{ number_format($res['ese_prototype'], 1) }}</td>
                <td class="py-2.5 px-3 text-xs font-mono text-right text-slate-200">{{ number_format($res['ese_modern_tools'], 1) }}</td>
                <td class="py-2.5 px-3 text-xs font-mono text-right text-slate-200">{{ number_format($res['ese_presentation'], 1) }}</td>
                <td class="py-2.5 px-3 text-xs font-mono text-right text-slate-200">{{ number_format($res['ese_innovativeness'], 1) }}</td>
                <td class="py-2.5 px-3 text-xs font-mono text-right text-slate-200">{{ number_format($res['ese_viva'], 1) }}</td>
                <td class="py-2.5 px-3 text-xs font-mono text-right text-slate-200">{{ number_format($res['ese_individual_contrib'], 1) }}</td>
                <td class="py-2.5 px-3 text-xs font-mono text-right text-slate-200">{{ number_format($res['ese_group_activity'], 1) }}</td>
                <td class="py-2.5 px-3 text-xs font-mono text-right text-slate-200">{{ number_format($res['ese_project_report'], 1) }}</td>
                <td class="py-2.5 px-3 text-xs font-mono font-extrabold text-right text-sky-400">{{ number_format($res['total_ese_50'], 1) }}</td>
                <td class="py-2.5 px-3 text-xs font-bold text-center text-slate-200">{{ $res['ese_grade'] }}</td>
                <td class="py-2.5 px-3 text-xs text-center">
                    @if($res['total_ese_50'] >= 20.0)
                        <x-ui.badge variant="success">Pass (Min 20)</x-ui.badge>
                    @elseif($res['total_ese_50'] > 0)
                        <x-ui.badge variant="danger">Below Min</x-ui.badge>
                    @else
                        <x-ui.badge variant="neutral">Not Evaluated</x-ui.badge>
                    @endif
                </td>
                <td class="py-2.5 px-3 text-xs text-center">
                    <x-ui.button 
                        variant="secondary" 
                        size="sm" 
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
