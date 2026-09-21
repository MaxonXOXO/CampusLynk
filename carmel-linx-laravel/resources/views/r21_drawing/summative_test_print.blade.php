<x-layouts.report-layout 
    :title="'Drawing Summative Series Test Register — ' . $batchSubject->subject_name" 
    orientation="landscape" 
    :documentNo="'SBTE-R21-DRW-TEST-' . ($batchSubject->formatted_subject_code ?? $batchSubject->subject_code)"
>
    <!-- Document Header & Meta Details -->
    <div class="mb-4 border-b border-slate-300 pb-3">
        <div class="flex justify-between items-start text-xs">
            <div class="space-y-1">
                <div><span class="font-bold text-slate-700">Course:</span> {{ $batchSubject->subject_name }} ({{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }})</div>
                <div><span class="font-bold text-slate-700">Regulation:</span> R-2021 (Kerala State Board of Technical Education)</div>
                <div><span class="font-bold text-slate-700">Evaluation Scheme:</span> Summative Series Tests (Clause 11.2.3.a — 40% Weightage / 20 Marks)</div>
            </div>
            <div class="space-y-1 text-right">
                <div><span class="font-bold text-slate-700">Semester:</span> {{ $courseFile->semester ?? 'I' }}</div>
                <div><span class="font-bold text-slate-700">Test Scheme:</span> Test 1 (Modules I &amp; II) &bull; Test 2 (Modules III &amp; IV)</div>
                <div><span class="font-bold text-slate-700">Criteria:</span> Procedure (40%) + Final (30%) + Dimensioning (20%) + Neatness (10%) = 100%</div>
            </div>
        </div>
    </div>

    <!-- Series Test Table -->
    <table class="w-full text-[11px] border-collapse border border-slate-400">
        <thead>
            <tr class="bg-slate-100 text-slate-800 font-bold text-center">
                <th rowspan="2" class="border border-slate-400 py-1 px-1 w-8">#</th>
                <th rowspan="2" class="border border-slate-400 py-1 px-1.5 w-14">Roll</th>
                <th rowspan="2" class="border border-slate-400 py-1 px-2 w-24">Reg No</th>
                <th rowspan="2" class="border border-slate-400 py-1 px-2 text-left min-w-[140px]">Student Name</th>
                <th colspan="5" class="border border-slate-400 py-1 px-1 bg-indigo-50/60 text-indigo-900">Series Test 1 (Modules I &amp; II)</th>
                <th colspan="5" class="border border-slate-400 py-1 px-1 bg-violet-50/60 text-violet-900">Series Test 2 (Modules III &amp; IV)</th>
                <th rowspan="2" class="border border-slate-400 py-1 px-1.5 w-16 bg-slate-100">Avg % (100)</th>
                <th rowspan="2" class="border border-slate-400 py-1 px-1.5 w-16 bg-indigo-100/70 font-black">Mark (20)</th>
            </tr>
            <tr class="bg-slate-50 text-[10px] text-slate-700 font-semibold text-center">
                <!-- Test 1 Breakdown -->
                <th class="border border-slate-400 py-0.5 px-1 w-12">Proc (40)</th>
                <th class="border border-slate-400 py-0.5 px-1 w-12">Fin (30)</th>
                <th class="border border-slate-400 py-0.5 px-1 w-12">Dim (20)</th>
                <th class="border border-slate-400 py-0.5 px-1 w-12">Neat (10)</th>
                <th class="border border-slate-400 py-0.5 px-1.5 w-14 font-bold bg-indigo-100/50">T1 (100)</th>
                <!-- Test 2 Breakdown -->
                <th class="border border-slate-400 py-0.5 px-1 w-12">Proc (40)</th>
                <th class="border border-slate-400 py-0.5 px-1 w-12">Fin (30)</th>
                <th class="border border-slate-400 py-0.5 px-1 w-12">Dim (20)</th>
                <th class="border border-slate-400 py-0.5 px-1 w-12">Neat (10)</th>
                <th class="border border-slate-400 py-0.5 px-1.5 w-14 font-bold bg-violet-100/50">T2 (100)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $idx => $student)
                @php
                    $stTests = $seriesTests->get($student->reg_no, collect());
                    $t1 = $stTests->firstWhere('test_no', 'Test 1');
                    $t2 = $stTests->firstWhere('test_no', 'Test 2');

                    $t1Total = ($t1 && !$t1->is_absent) ? floatval($t1->total_score_100) : null;
                    $t2Total = ($t2 && !$t2->is_absent) ? floatval($t2->total_score_100) : null;

                    if ($t1Total !== null && $t2Total !== null) {
                        $avg = round(($t1Total + $t2Total) / 2.0, 1);
                    } elseif ($t1Total !== null) {
                        $avg = round($t1Total, 1);
                    } elseif ($t2Total !== null) {
                        $avg = round($t2Total, 1);
                    } else {
                        $avg = 0.0;
                    }
                    $summativeMark = round((($avg / 100.0) * 20.0) * 2) / 2;
                @endphp
                <tr class="text-center {{ $idx % 2 === 1 ? 'bg-slate-50/40' : 'bg-white' }}">
                    <td class="border border-slate-300 py-1 px-1 text-slate-500">{{ $idx + 1 }}</td>
                    <td class="border border-slate-300 py-1 px-1.5 font-semibold text-slate-900">{{ $student->roll_no ?? '-' }}</td>
                    <td class="border border-slate-300 py-1 px-2 font-mono text-slate-700">{{ $student->reg_no }}</td>
                    <td class="border border-slate-300 py-1 px-2 text-left font-medium text-slate-900">{{ $student->name }}</td>
                    
                    <!-- Test 1 -->
                    @if($t1 && $t1->is_absent)
                        <td colspan="4" class="border border-slate-300 py-1 px-1 text-rose-600 font-bold text-center">ABSENT</td>
                        <td class="border border-slate-300 py-1 px-1.5 font-bold text-rose-600 bg-rose-50/40">ABS</td>
                    @elseif($t1)
                        <td class="border border-slate-300 py-1 px-1">{{ round($t1->procedure_drawing, 1) }}</td>
                        <td class="border border-slate-300 py-1 px-1">{{ round($t1->final_drawing, 1) }}</td>
                        <td class="border border-slate-300 py-1 px-1">{{ round($t1->dimensioning, 1) }}</td>
                        <td class="border border-slate-300 py-1 px-1">{{ round($t1->neatness, 1) }}</td>
                        <td class="border border-slate-300 py-1 px-1.5 font-bold text-indigo-900 bg-indigo-50/30">{{ round($t1->total_score_100, 1) }}</td>
                    @else
                        <td colspan="5" class="border border-slate-300 py-1 px-1 text-slate-300 text-center">-</td>
                    @endif

                    <!-- Test 2 -->
                    @if($t2 && $t2->is_absent)
                        <td colspan="4" class="border border-slate-300 py-1 px-1 text-rose-600 font-bold text-center">ABSENT</td>
                        <td class="border border-slate-300 py-1 px-1.5 font-bold text-rose-600 bg-rose-50/40">ABS</td>
                    @elseif($t2)
                        <td class="border border-slate-300 py-1 px-1">{{ round($t2->procedure_drawing, 1) }}</td>
                        <td class="border border-slate-300 py-1 px-1">{{ round($t2->final_drawing, 1) }}</td>
                        <td class="border border-slate-300 py-1 px-1">{{ round($t2->dimensioning, 1) }}</td>
                        <td class="border border-slate-300 py-1 px-1">{{ round($t2->neatness, 1) }}</td>
                        <td class="border border-slate-300 py-1 px-1.5 font-bold text-violet-900 bg-violet-50/30">{{ round($t2->total_score_100, 1) }}</td>
                    @else
                        <td colspan="5" class="border border-slate-300 py-1 px-1 text-slate-300 text-center">-</td>
                    @endif

                    <td class="border border-slate-300 py-1 px-1.5 font-bold text-slate-800 bg-slate-50/50">{{ $avg }}</td>
                    <td class="border border-slate-300 py-1 px-1.5 font-black text-indigo-900 bg-indigo-100/50">{{ $summativeMark }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="border border-slate-300 py-6 text-center text-slate-400">
                        No summative test records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Statutory Signatures Block -->
    <div class="mt-12 flex justify-between items-end text-xs text-slate-800 pt-6">
        <div class="text-center w-52">
            <div class="border-t border-slate-500 pt-1 font-bold">Subject Faculty-in-Charge</div>
            <div class="text-[10px] text-slate-500">Signature &amp; Date</div>
        </div>
        <div class="text-center w-52">
            <div class="border-t border-slate-500 pt-1 font-bold">Head of Department (HOD)</div>
            <div class="text-[10px] text-slate-500">Verified &amp; Approved</div>
        </div>
        <div class="text-center w-52">
            <div class="border-t border-slate-500 pt-1 font-bold">Principal / Academic Head</div>
            <div class="text-[10px] text-slate-500">Institutional Seal</div>
        </div>
    </div>
</x-layouts.report-layout>
