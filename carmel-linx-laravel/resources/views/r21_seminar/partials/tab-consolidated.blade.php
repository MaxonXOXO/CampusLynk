<div id="tab-consolidated" class="tab-pane hidden space-y-5">
    <!-- Grade Distribution Cards (SBTE 9-Point Scale) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
        @php
            $completedStudents = $studentResults->where('is_completed', true);
            $totalCompleted = $completedStudents->count();
            $gradeColors = [
                'S' => 'text-emerald-400 border-emerald-500/30 bg-emerald-500/10',
                'A' => 'text-sky-400 border-sky-500/30 bg-sky-500/10',
                'B' => 'text-blue-400 border-blue-500/30 bg-blue-500/10',
                'C' => 'text-indigo-400 border-indigo-500/30 bg-indigo-500/10',
                'D' => 'text-purple-400 border-purple-500/30 bg-purple-500/10',
                'E' => 'text-amber-400 border-amber-500/30 bg-amber-500/10',
                'F' => 'text-rose-400 border-rose-500/30 bg-rose-500/10',
            ];
            $gradeLabels = [
                'S' => '>= 67.5 (90%)',
                'A' => '60 - 67M',
                'B' => '52.5 - 59.5M',
                'C' => '45 - 52M',
                'D' => '37.5 - 44.5M',
                'E' => '30 - 37M',
                'F' => '< 30M',
            ];
        @endphp

        @foreach(['S', 'A', 'B', 'C', 'D', 'E', 'F'] as $g)
            @php
                $count = $completedStudents->where('letter_grade', $g)->count();
                $pct = $totalCompleted > 0 ? round(($count / $totalCompleted) * 100, 1) : 0;
            @endphp
            <div class="rounded-2xl border p-3.5 {{ $gradeColors[$g] }} shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xl font-black">{{ $g }}</span>
                    <span class="text-xs font-mono font-bold">{{ $count }}</span>
                </div>
                <div class="text-[10px] text-slate-400 mt-1">{{ $gradeLabels[$g] }}</div>
                <div class="text-[11px] font-semibold mt-0.5">{{ $pct }}%</div>
            </div>
        @endforeach
    </div>

    <!-- Print Launcher & Quick Controls -->
    <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-700/70 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div class="text-xs text-slate-300">
            Official SBTE Kerala Continuous Internal Assessment (Clause 11.2.6) • <strong class="text-white">75 Marks Total</strong>
        </div>
        <div class="flex items-center gap-2">
            <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=cia_submission" target="_blank">
                <x-ui.button variant="secondary" size="sm" icon="award">
                    <span>SBTE Statement (75M)</span>
                </x-ui.button>
            </a>
            <a href="/r21/classroom/seminar/{{ $batchSubject->id }}/print?type=consolidated" target="_blank">
                <x-ui.button variant="secondary" size="sm" icon="printer">
                    <span>Consolidated Register</span>
                </x-ui.button>
            </a>
        </div>
    </div>

    <!-- Consolidated Marks Table -->
    <x-ui.table :headers="['Roll', 'Reg No', 'Student Name', 'Seminar (67.5M)', 'Attd (7.5M)', 'Total CIA (75M)', 'Marks in Words', 'Grade', 'Point', 'Result']">
        @forelse($studentResults as $st)
            @php
                $seminarComponent = round((float)($st['avg_relevance'] ?? 0) + (float)($st['avg_literature'] ?? 0) + (float)($st['avg_presentation'] ?? 0) + (float)($st['avg_interaction'] ?? 0) + (float)($st['avg_report'] ?? 0), 2);
                $attComponent = round((float)($st['avg_attendance'] ?? 0), 2);
            @endphp
            <tr class="hover:bg-slate-800/40 transition">
                <td class="text-center font-mono text-slate-400 py-3 px-3">{{ $st['roll_no'] ?? '—' }}</td>
                <td class="font-mono text-xs font-medium text-slate-300 py-3 px-3">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                <td class="py-3 px-3 font-semibold text-white">{{ $st['name'] }}</td>
                <td class="text-center font-mono text-xs text-slate-300 py-3 px-2">
                    {{ $st['is_completed'] ? number_format($seminarComponent, 1) : '—' }}
                </td>
                <td class="text-center font-mono text-xs text-slate-300 py-3 px-2">
                    {{ $st['is_completed'] ? number_format($attComponent, 1) : '—' }}
                </td>
                <td class="text-center font-mono text-xs font-bold text-white py-3 px-2">
                    {{ $st['is_completed'] ? number_format($st['final_score'], 1) : '—' }}
                </td>
                <td class="py-3 px-3 text-xs text-slate-300 italic">
                    {{ $st['is_completed'] ? \App\Http\Controllers\R21VirtualClassroomSeminarController::numberToWords($st['final_score']) : '—' }}
                </td>
                <td class="text-center py-3 px-2">
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
                <td class="text-center font-mono text-xs text-slate-300 py-3 px-2">
                    {{ $st['is_completed'] ? $st['grade_point'] : '—' }}
                </td>
                <td class="text-center py-3 px-3">
                    @if($st['is_completed'])
                        @if($st['result'] === 'Pass')
                            <x-ui.badge variant="success">Pass</x-ui.badge>
                        @else
                            <x-ui.badge variant="danger">Failed</x-ui.badge>
                        @endif
                    @else
                        <span class="text-xs text-slate-500">—</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="py-8 text-center text-slate-400">
                    No student marks available.
                </td>
            </tr>
        @endforelse
    </x-ui.table>
</div>
