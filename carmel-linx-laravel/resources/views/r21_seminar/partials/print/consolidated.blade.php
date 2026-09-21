@php
    $reportTitle = 'Consolidated 6-Rubric Seminar Evaluation Register (Clause 11.2.6)';
@endphp

@include('r21_seminar.partials.print.header')

<!-- Evaluation Scheme Notice -->
<div class="mb-3 text-[11px] text-slate-700 bg-slate-100 p-2 rounded border border-slate-300 flex justify-between items-center">
    <div>
        <strong>Statutory Scheme:</strong> 1. Relevance (7.5M) + 2. Literature (7.5M) + 3. Presentation (37.5M) + 4. Discussion (7.5M) + 5. Report (7.5M) + 6. Attendance (7.5M) = <strong>75 Marks Total</strong>
    </div>
    <div>
        <strong>Committee Assessment:</strong> Independent faculty evaluations averaged. Pass minimum: 50% (37.5M).
    </div>
</div>

<!-- Consolidated Register Table -->
<div class="border border-slate-900 rounded overflow-hidden">
    <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-200 border-b border-slate-900">
            <tr>
                <th class="py-2 px-1.5 text-center border-r border-slate-400 w-8">Sl</th>
                <th class="py-2 px-2 border-r border-slate-400 w-24">Reg No</th>
                <th class="py-2 px-2 border-r border-slate-400">Student Name</th>
                <th class="py-2 px-2 border-r border-slate-400 max-w-xs">Seminar Topic &amp; Guide</th>
                <th class="py-2 px-1 text-center border-r border-slate-400 w-10">Rel<br><span class="text-[9px] font-mono font-normal">7.5</span></th>
                <th class="py-2 px-1 text-center border-r border-slate-400 w-10">Lit<br><span class="text-[9px] font-mono font-normal">7.5</span></th>
                <th class="py-2 px-1 text-center border-r border-slate-400 w-12">Pres<br><span class="text-[9px] font-mono font-normal">37.5</span></th>
                <th class="py-2 px-1 text-center border-r border-slate-400 w-10">Disc<br><span class="text-[9px] font-mono font-normal">7.5</span></th>
                <th class="py-2 px-1 text-center border-r border-slate-400 w-10">Rep<br><span class="text-[9px] font-mono font-normal">7.5</span></th>
                <th class="py-2 px-1 text-center border-r border-slate-400 w-10">Att<br><span class="text-[9px] font-mono font-normal">7.5</span></th>
                <th class="py-2 px-1.5 text-center border-r border-slate-400 w-14 font-bold bg-slate-300">Total<br><span class="text-[9px] font-mono font-normal">75</span></th>
                <th class="py-2 px-1.5 text-center border-r border-slate-400 w-10">Grade</th>
                <th class="py-2 px-2 text-center w-12">Result</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-300">
            @forelse($students as $index => $st)
                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }}">
                    <td class="text-center font-mono py-1.5 px-1 border-r border-slate-300">{{ $st['roll_no'] ?? ($index + 1) }}</td>
                    <td class="font-mono text-[11px] font-semibold py-1.5 px-2 border-r border-slate-300">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                    <td class="font-bold text-slate-900 py-1.5 px-2 border-r border-slate-300">{{ $st['name'] }}</td>
                    <td class="py-1.5 px-2 border-r border-slate-300 text-[11px] truncate max-w-xs">
                        <span class="font-medium">{{ $st['topic'] ?? '—' }}</span>
                        @if(!empty($st['guide_name']) && $st['guide_name'] !== '—')
                            <span class="text-slate-500 block text-[10px]">Guide: {{ $st['guide_name'] }}</span>
                        @endif
                    </td>
                    <td class="text-center font-mono py-1.5 px-1 border-r border-slate-300">{{ $st['eval_count'] > 0 ? number_format($st['relevance'], 1) : '—' }}</td>
                    <td class="text-center font-mono py-1.5 px-1 border-r border-slate-300">{{ $st['eval_count'] > 0 ? number_format($st['literature'], 1) : '—' }}</td>
                    <td class="text-center font-mono py-1.5 px-1 border-r border-slate-300">{{ $st['eval_count'] > 0 ? number_format($st['presentation'], 1) : '—' }}</td>
                    <td class="text-center font-mono py-1.5 px-1 border-r border-slate-300">{{ $st['eval_count'] > 0 ? number_format($st['interaction'], 1) : '—' }}</td>
                    <td class="text-center font-mono py-1.5 px-1 border-r border-slate-300">{{ $st['eval_count'] > 0 ? number_format($st['report'], 1) : '—' }}</td>
                    <td class="text-center font-mono py-1.5 px-1 border-r border-slate-300">{{ $st['eval_count'] > 0 ? number_format($st['attendance'], 1) : '—' }}</td>
                    <td class="text-center font-mono font-extrabold text-slate-900 py-1.5 px-1 border-r border-slate-300 bg-slate-100">
                        {{ $st['eval_count'] > 0 ? number_format($st['total_score'], 1) : '—' }}
                    </td>
                    <td class="text-center font-bold py-1.5 px-1 border-r border-slate-300">{{ $st['letter_grade'] }}</td>
                    <td class="text-center font-semibold text-[11px] py-1.5 px-1 {{ $st['result'] === 'Pass' ? 'text-emerald-700' : ($st['result'] === 'Failed' ? 'text-rose-700' : 'text-slate-400') }}">
                        {{ $st['result'] }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="py-4 text-center text-slate-500">No student records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Summary Statistics Box -->
<div class="mt-4 p-3 border border-slate-300 rounded bg-slate-50 text-xs flex flex-wrap justify-between items-center gap-3">
    <div class="flex items-center gap-4">
        <span>Enrolled: <strong>{{ $totalStudents }}</strong></span>
        <span>Evaluated: <strong>{{ $completedCount }}</strong></span>
        <span>Passed: <strong class="text-emerald-700">{{ $passedCount }}</strong></span>
        <span>Failed: <strong class="text-rose-700">{{ $failedCount }}</strong></span>
        <span>Pass Rate: <strong>{{ $passRate }}%</strong></span>
    </div>
    <div class="flex items-center gap-4 font-mono">
        <span>Class Avg: <strong>{{ number_format($avgScoreOverall, 1) }}/75</strong></span>
        <span>Highest: <strong>{{ number_format($highestScore, 1) }}</strong></span>
        <span>Lowest: <strong>{{ number_format($lowestScore, 1) }}</strong></span>
    </div>
</div>

@include('r21_seminar.partials.print.signatures')
