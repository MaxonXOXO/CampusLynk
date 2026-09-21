<x-layouts.report-layout 
    :title="'Drawing Formative Sheet Register — ' . $batchSubject->subject_name" 
    orientation="landscape" 
    :documentNo="'SBTE-R21-DRW-SHT-' . ($batchSubject->formatted_subject_code ?? $batchSubject->subject_code)"
>
    <!-- Document Header & Meta Details -->
    <div class="mb-4 border-b border-slate-300 pb-3">
        <div class="flex justify-between items-start text-xs">
            <div class="space-y-1">
                <div><span class="font-bold text-slate-700">Course:</span> {{ $batchSubject->subject_name }} ({{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }})</div>
                <div><span class="font-bold text-slate-700">Regulation:</span> R-2021 (Kerala State Board of Technical Education)</div>
                <div><span class="font-bold text-slate-700">Evaluation Scheme:</span> Formative Continuous Assessment (Clause 11.2.3.b — 40% Weightage / 20 Marks)</div>
            </div>
            <div class="space-y-1 text-right">
                <div><span class="font-bold text-slate-700">Semester:</span> {{ $courseFile->semester ?? 'I' }}</div>
                <div><span class="font-bold text-slate-700">Total Configured Sheets:</span> {{ count($sheets) }} Sheets</div>
                <div><span class="font-bold text-slate-700">Rubric:</span> Timely Completion (50%) + Appearance &amp; Org (50%) = 100%</div>
            </div>
        </div>
    </div>

    <!-- Sheet Register Table -->
    <table class="w-full text-[11px] border-collapse border border-slate-400">
        <thead>
            <tr class="bg-slate-100 text-slate-800 font-bold text-center">
                <th class="border border-slate-400 py-1.5 px-1 w-8">#</th>
                <th class="border border-slate-400 py-1.5 px-1.5 w-14">Roll</th>
                <th class="border border-slate-400 py-1.5 px-2 w-24">Reg No</th>
                <th class="border border-slate-400 py-1.5 px-2 text-left min-w-[140px]">Student Name</th>
                @foreach($sheets as $sheet)
                    <th class="border border-slate-400 py-1.5 px-1 text-center min-w-[40px]">
                        {{ $sheet['sheet_no'] }}
                        <span class="block text-[9px] font-normal text-slate-500">{{ $sheet['co_id'] ?? '' }}</span>
                    </th>
                @endforeach
                <th class="border border-slate-400 py-1.5 px-1.5 w-16 bg-blue-50/50">Avg % (100)</th>
                <th class="border border-slate-400 py-1.5 px-1.5 w-16 bg-blue-100/60 font-black">Mark (20)</th>
                <th class="border border-slate-400 py-1.5 px-2 w-20">Signature</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $idx => $student)
                @php
                    $stSheets = $sheetEvals->get($student->reg_no, collect());
                    $validSheets = $stSheets->where('is_absent', false);
                    $avgScore = $validSheets->count() > 0 ? round($validSheets->avg('total_score_100'), 1) : 0;
                    $formativeMark = round((($avgScore / 100.0) * 20.0) * 2) / 2;
                @endphp
                <tr class="text-center {{ $idx % 2 === 1 ? 'bg-slate-50/40' : 'bg-white' }}">
                    <td class="border border-slate-300 py-1 px-1 text-slate-500">{{ $idx + 1 }}</td>
                    <td class="border border-slate-300 py-1 px-1.5 font-semibold text-slate-900">{{ $student->roll_no ?? '-' }}</td>
                    <td class="border border-slate-300 py-1 px-2 font-mono text-slate-700">{{ $student->reg_no }}</td>
                    <td class="border border-slate-300 py-1 px-2 text-left font-medium text-slate-900">{{ $student->name }}</td>
                    @foreach($sheets as $sheet)
                        @php
                            $eval = $stSheets->firstWhere('sheet_no', $sheet['sheet_no']);
                        @endphp
                        <td class="border border-slate-300 py-1 px-1 font-mono">
                            @if($eval)
                                @if($eval->is_absent)
                                    <span class="text-rose-600 font-bold">ABS</span>
                                @else
                                    {{ round($eval->total_score_100) }}
                                @endif
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                    @endforeach
                    <td class="border border-slate-300 py-1 px-1.5 font-bold text-blue-900 bg-blue-50/30">
                        {{ $avgScore }}
                    </td>
                    <td class="border border-slate-300 py-1 px-1.5 font-black text-blue-900 bg-blue-100/40">
                        {{ $formativeMark }}
                    </td>
                    <td class="border border-slate-300 py-1 px-2 text-slate-300"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($sheets) + 7 }}" class="border border-slate-300 py-6 text-center text-slate-400">
                        No student evaluation records found.
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
