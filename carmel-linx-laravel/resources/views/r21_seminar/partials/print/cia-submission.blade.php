@php
    $reportTitle = 'Official SBTE Final CIA Mark Entry Statement (75M)';
@endphp

@include('r21_seminar.partials.print.header')

<!-- SBTE Mark Entry Statement Table -->
<div class="border border-slate-900 rounded overflow-hidden">
    <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-200 border-b border-slate-900">
            <tr>
                <th class="py-2.5 px-2 text-center border-r border-slate-400 w-10">Sl</th>
                <th class="py-2.5 px-3 border-r border-slate-400 w-28">Reg No</th>
                <th class="py-2.5 px-3 border-r border-slate-400">Student Name</th>
                <th class="py-2.5 px-2 text-center border-r border-slate-400 w-20">Seminar<br><span class="text-[9px] font-mono font-normal">Max 67.5</span></th>
                <th class="py-2.5 px-2 text-center border-r border-slate-400 w-16">Attd<br><span class="text-[9px] font-mono font-normal">Max 7.5</span></th>
                <th class="py-2.5 px-2 text-center border-r border-slate-400 w-20 font-bold bg-slate-300">Total CIA<br><span class="text-[9px] font-mono font-normal">Max 75</span></th>
                <th class="py-2.5 px-3 border-r border-slate-400">Total in Words</th>
                <th class="py-2.5 px-2 text-center border-r border-slate-400 w-14">Grade</th>
                <th class="py-2.5 px-2 text-center border-r border-slate-400 w-12">Point</th>
                <th class="py-2.5 px-2 text-center w-16">Result</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-300">
            @forelse($students as $index => $st)
                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }}">
                    <td class="text-center font-mono py-2 px-2 border-r border-slate-300">{{ $st['roll_no'] ?? ($index + 1) }}</td>
                    <td class="font-mono text-xs font-bold py-2 px-3 border-r border-slate-300">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                    <td class="font-bold text-slate-900 py-2 px-3 border-r border-slate-300">{{ $st['name'] }}</td>
                    <td class="text-center font-mono py-2 px-2 border-r border-slate-300">
                        {{ $st['eval_count'] > 0 ? number_format($st['seminar_score'], 1) : '—' }}
                    </td>
                    <td class="text-center font-mono py-2 px-2 border-r border-slate-300">
                        {{ $st['eval_count'] > 0 ? number_format($st['attendance_score'], 1) : '—' }}
                    </td>
                    <td class="text-center font-mono font-extrabold text-slate-900 py-2 px-2 border-r border-slate-300 bg-slate-100">
                        {{ $st['eval_count'] > 0 ? number_format($st['total_score'], 1) : '—' }}
                    </td>
                    <td class="py-2 px-3 border-r border-slate-300 text-[11px] italic text-slate-700">
                        {{ $st['score_in_words'] }}
                    </td>
                    <td class="text-center font-bold py-2 px-2 border-r border-slate-300">{{ $st['letter_grade'] }}</td>
                    <td class="text-center font-mono py-2 px-2 border-r border-slate-300">{{ $st['grade_point'] }}</td>
                    <td class="text-center font-semibold text-xs py-2 px-2 {{ $st['result'] === 'Pass' ? 'text-emerald-700' : ($st['result'] === 'Failed' ? 'text-rose-700' : 'text-slate-400') }}">
                        {{ $st['result'] }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="py-4 text-center text-slate-500">No student marks available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- SBTE Grade Statistics Summary -->
<div class="mt-4 p-3 border border-slate-300 rounded bg-slate-50 text-xs">
    <div class="font-bold text-slate-900 mb-2 uppercase tracking-wide">Grade Distribution Summary (Clause 11.2.6)</div>
    <div class="grid grid-cols-7 gap-2 text-center font-mono">
        @foreach(['S', 'A', 'B', 'C', 'D', 'E', 'F'] as $g)
            <div class="p-2 rounded border border-slate-300 bg-white">
                <div class="font-extrabold text-slate-900 text-sm">{{ $g }}</div>
                <div class="text-xs text-slate-600 mt-0.5">{{ $gradeStats[$g] ?? 0 }}</div>
            </div>
        @endforeach
    </div>
</div>

<!-- Statutory Certification Declaration -->
<div class="mt-4 p-3 border border-slate-300 rounded bg-slate-100 text-[11px] text-slate-700 leading-relaxed">
    <strong>Certification:</strong> Certified that the continuous internal assessment marks entered above have been evaluated by the duly constituted faculty committee in accordance with Regulation Clause 11.2.6 of the State Board of Technical Education (SBTE) Kerala Revision 2021 curriculum. The rubrics and attendance marks are based on verified institutional academic records.
</div>

@include('r21_seminar.partials.print.signatures')
