<div id="tab-evaluation" class="tab-pane block space-y-4">
    <!-- Rubric Guide Banner (Clause 11.2.6) -->
    <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-700/70 flex flex-col md:flex-row items-start md:items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-500/20 border border-blue-500/40 text-blue-400 flex items-center justify-center shrink-0">
                <x-ui.icon name="award" class="w-5 h-5" />
            </div>
            <div>
                <div class="text-xs font-bold text-white flex items-center gap-2">
                    <span>Clause 11.2.6 Assessment Rubrics (100% = 75 Marks Total)</span>
                    <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 text-[10px] font-mono border border-slate-700">Committee Averaged</span>
                </div>
                <div class="text-[11px] text-slate-400 mt-1 flex flex-wrap gap-x-3 gap-y-1">
                    <span>1. Relevance: <strong class="text-slate-200">7.5M</strong></span>
                    <span>2. Literature: <strong class="text-slate-200">7.5M</strong></span>
                    <span>3. Presentation: <strong class="text-slate-200">37.5M</strong></span>
                    <span>4. Discussion: <strong class="text-slate-200">7.5M</strong></span>
                    <span>5. Report: <strong class="text-slate-200">7.5M</strong></span>
                    <span>6. Attendance: <strong class="text-slate-200">7.5M</strong></span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=consolidated" target="_blank">
                <x-ui.button variant="secondary" size="sm" icon="printer">
                    <span>Print Register</span>
                </x-ui.button>
            </a>
            <div class="text-[11px] text-slate-300 bg-slate-800/80 px-3 py-1.5 rounded-lg border border-slate-700">
                Assessor: <strong class="text-white">{{ $activeStaff->name ?? 'Faculty' }}</strong>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-1.5 bg-slate-800/80 p-1 rounded-xl border border-slate-700/60">
            <button type="button" onclick="filterBatch('all')" class="batch-filter-btn active px-3 py-1 text-xs font-semibold rounded-lg text-white bg-blue-600 transition" data-batch="all">All</button>
            <button type="button" onclick="filterBatch('1')" class="batch-filter-btn px-3 py-1 text-xs font-semibold rounded-lg text-slate-300 hover:text-white transition" data-batch="1">Batch 1</button>
            <button type="button" onclick="filterBatch('2')" class="batch-filter-btn px-3 py-1 text-xs font-semibold rounded-lg text-slate-300 hover:text-white transition" data-batch="2">Batch 2</button>
            <button type="button" onclick="filterBatch('Unassigned')" class="batch-filter-btn px-3 py-1 text-xs font-semibold rounded-lg text-slate-300 hover:text-white transition" data-batch="Unassigned">Unassigned</button>
        </div>

        <div class="w-full sm:w-72">
            <x-ui.input 
                type="text" 
                id="searchStudentInput" 
                placeholder="Search by name, reg no, roll..." 
                oninput="filterStudents()" />
        </div>
    </div>

    <!-- Seminar Evaluation Register Table -->
    <x-ui.table :headers="['Roll', 'Reg No', 'Student Name', 'Batch', 'Seminar Topic & Guide', 'Rel (7.5)', 'Lit (7.5)', 'Pres (37.5)', 'Disc (7.5)', 'Rep (7.5)', 'Att (7.5)', 'My Score', 'Comm Avg', 'Grade', 'Action']">
        @forelse($studentResults as $st)
            <tr class="student-row hover:bg-slate-800/40 transition" 
                data-reg="{{ $st['reg_no'] }}" 
                data-name="{{ strtolower($st['name']) }}" 
                data-roll="{{ $st['roll_no'] }}" 
                data-batch="{{ $st['batch'] }}">
                <td class="text-center font-mono text-slate-400 py-3 px-3">{{ $st['roll_no'] ?? '—' }}</td>
                <td class="font-mono text-xs font-medium text-slate-300 py-3 px-3">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                <td class="py-3 px-3 font-semibold text-white">
                    {{ $st['name'] }}
                    @if(!empty($st['academic_status']) && $st['academic_status'] !== 'REGULAR')
                        <span class="ml-1 text-[10px] text-amber-400 font-normal">({{ $st['academic_status'] }})</span>
                    @endif
                </td>
                <td class="text-center py-3 px-3">
                    @if($st['batch'] === '1')
                        <x-ui.badge variant="info">B1</x-ui.badge>
                    @elseif($st['batch'] === '2')
                        <x-ui.badge variant="warning">B2</x-ui.badge>
                    @else
                        <span class="text-xs text-slate-500">—</span>
                    @endif
                </td>
                <td class="py-3 px-3 text-xs max-w-[220px]">
                    @if(!empty($st['topic']))
                        <div class="font-medium text-slate-200 truncate" title="{{ $st['topic'] }}">{{ $st['topic'] }}</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Guide: {{ $st['guide_name'] ?? 'Unassigned' }}</div>
                    @else
                        <span class="text-slate-500 italic">Topic not registered</span>
                    @endif
                </td>
                <td class="text-center font-mono text-xs text-slate-300 py-3 px-2 rubric-val-rel-{{ $st['reg_no'] }}">{{ $st['avg_relevance'] ?? '—' }}</td>
                <td class="text-center font-mono text-xs text-slate-300 py-3 px-2 rubric-val-lit-{{ $st['reg_no'] }}">{{ $st['avg_literature'] ?? '—' }}</td>
                <td class="text-center font-mono text-xs text-slate-300 py-3 px-2 rubric-val-pres-{{ $st['reg_no'] }}">{{ $st['avg_presentation'] ?? '—' }}</td>
                <td class="text-center font-mono text-xs text-slate-300 py-3 px-2 rubric-val-disc-{{ $st['reg_no'] }}">{{ $st['avg_interaction'] ?? '—' }}</td>
                <td class="text-center font-mono text-xs text-slate-300 py-3 px-2 rubric-val-rep-{{ $st['reg_no'] }}">{{ $st['avg_report'] ?? '—' }}</td>
                <td class="text-center font-mono text-xs text-slate-300 py-3 px-2 rubric-val-att-{{ $st['reg_no'] }}">{{ $st['avg_attendance'] ?? '—' }}</td>
                
                <!-- My Score -->
                <td class="text-center font-mono text-xs font-bold text-slate-200 py-3 px-2 my-score-{{ $st['reg_no'] }}">
                    {{ isset($st['my_evaluation']) ? number_format($st['my_evaluation']['total_score'], 1) : '—' }}
                </td>

                <!-- Committee Average Score (Clickable for breakdown) -->
                <td class="text-center py-3 px-2">
                    @if($st['eval_count'] > 0)
                        <button type="button" 
                                onclick="openBreakdownModal('{{ $st['reg_no'] }}')" 
                                class="font-mono text-xs font-bold text-blue-400 hover:text-blue-300 underline cursor-pointer comm-avg-{{ $st['reg_no'] }}"
                                title="View Committee Breakdown ({{ $st['eval_count'] }} assessors)">
                            {{ number_format($st['final_score'], 1) }}
                        </button>
                    @else
                        <span class="text-slate-500 font-mono text-xs comm-avg-{{ $st['reg_no'] }}">—</span>
                    @endif
                </td>

                <!-- SBTE Grade -->
                <td class="text-center py-3 px-2 grade-cell-{{ $st['reg_no'] }}">
                    @if($st['is_completed'])
                        @if($st['letter_grade'] === 'S' || $st['letter_grade'] === 'A')
                            <x-ui.badge variant="success">{{ $st['letter_grade'] }}</x-ui.badge>
                        @elseif(in_array($st['letter_grade'], ['B', 'C', 'D']))
                            <x-ui.badge variant="info">{{ $st['letter_grade'] }}</x-ui.badge>
                        @elseif($st['letter_grade'] === 'E')
                            <x-ui.badge variant="warning">{{ $st['letter_grade'] }}</x-ui.badge>
                        @else
                            <x-ui.badge variant="danger">{{ $st['letter_grade'] }}</x-ui.badge>
                        @endif
                    @else
                        <span class="text-xs text-slate-500">—</span>
                    @endif
                </td>

                <!-- Action -->
                <td class="text-center py-3 px-3">
                    <x-ui.button 
                        variant="{{ $st['is_completed'] ? 'secondary' : 'primary' }}" 
                        size="sm" 
                        onclick="openEvaluationModal('{{ $st['reg_no'] }}')">
                        <span>{{ $st['is_completed'] ? 'Re-evaluate' : 'Evaluate' }}</span>
                    </x-ui.button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="15" class="py-8 text-center text-slate-400">
                    No students found in this classroom.
                </td>
            </tr>
        @endforelse
    </x-ui.table>
</div>
