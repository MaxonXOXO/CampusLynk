<div id="tab-schedule" class="tab-pane hidden space-y-4">
    <!-- Schedule Info Banner -->
    <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-700/70 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-sky-500/20 border border-sky-500/40 text-sky-400 flex items-center justify-center shrink-0">
                <x-ui.icon name="calendar" class="w-5 h-5" />
            </div>
            <div>
                <h4 class="text-sm font-bold text-white">Seminar Presentation Schedule &amp; Topic Log</h4>
                <p class="text-xs text-slate-400 mt-0.5">Manage topic approvals, presentation dates, and department faculty guide assignments.</p>
            </div>
        </div>
        <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=schedule" target="_blank">
            <x-ui.button variant="secondary" size="sm" icon="printer">
                <span>Print Schedule</span>
            </x-ui.button>
        </a>
    </div>

    <!-- Schedule Table -->
    <x-ui.table :headers="['Roll', 'Reg No', 'Student Name', 'Presentation Date', 'Approved Seminar Topic', 'Faculty Guide', 'Status', 'Action']">
        @forelse($studentResults as $st)
            <tr class="hover:bg-slate-800/40 transition">
                <td class="text-center font-mono text-slate-400 py-3.5 px-4">{{ $st['roll_no'] ?? '—' }}</td>
                <td class="font-mono text-xs font-medium text-slate-300 py-3.5 px-4">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                <td class="py-3.5 px-4 font-semibold text-white">{{ $st['name'] }}</td>
                <td class="py-3.5 px-4 text-xs font-mono text-slate-300 sched-date-{{ $st['reg_no'] }}">
                    @if(!empty($st['presentation_date_formatted']))
                        <div class="flex items-center gap-1.5 text-sky-400">
                            <x-ui.icon name="calendar" class="w-3.5 h-3.5" />
                            <span>{{ $st['presentation_date_formatted'] }}</span>
                        </div>
                    @else
                        <span class="text-slate-500 italic">Not scheduled</span>
                    @endif
                </td>
                <td class="py-3.5 px-4 text-xs text-slate-200 max-w-xs sched-topic-{{ $st['reg_no'] }}">
                    {{ $st['topic'] ?? '—' }}
                </td>
                <td class="py-3.5 px-4 text-xs text-slate-300 sched-guide-{{ $st['reg_no'] }}">
                    {{ $st['guide_name'] ?? '—' }}
                </td>
                <td class="text-center py-3.5 px-4 sched-status-{{ $st['reg_no'] }}">
                    @if($st['is_completed'])
                        <x-ui.badge variant="success">Completed</x-ui.badge>
                    @elseif(!empty($st['presentation_date']))
                        <x-ui.badge variant="info">Scheduled</x-ui.badge>
                    @else
                        <x-ui.badge variant="neutral">Pending</x-ui.badge>
                    @endif
                </td>
                <td class="text-center py-3.5 px-4">
                    <x-ui.button 
                        variant="secondary" 
                        size="sm" 
                        icon="edit"
                        onclick="openScheduleModal('{{ $st['reg_no'] }}')">
                        <span>Edit</span>
                    </x-ui.button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="py-8 text-center text-slate-400">
                    No students registered for seminar.
                </td>
            </tr>
        @endforelse
    </x-ui.table>
</div>
