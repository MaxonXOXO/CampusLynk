<x-layouts.report-layout 
    :title="'Consolidated Continuous Internal Assessment (CIA) Marksheet — ' . $batchSubject->subject_name" 
    orientation="portrait" 
    :documentNo="'SBTE-R21-DRW-CIA-' . ($batchSubject->formatted_subject_code ?? $batchSubject->subject_code)"
>
    <!-- Document Header & Meta Details -->
    <div class="mb-4 border-b border-slate-300 pb-3">
        <div class="flex justify-between items-start text-xs">
            <div class="space-y-1">
                <div><span class="font-bold text-slate-700">Course:</span> {{ $batchSubject->subject_name }} ({{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }})</div>
                <div><span class="font-bold text-slate-700">Program / Branch:</span> {{ $classroom->department ?? $classroom->branch ?? 'Engineering' }}</div>
                <div><span class="font-bold text-slate-700">Regulation:</span> R-2021 (Kerala State Board of Technical Education)</div>
            </div>
            <div class="space-y-1 text-right">
                <div><span class="font-bold text-slate-700">Semester:</span> {{ $courseFile->semester ?? 'I' }}</div>
                <div><span class="font-bold text-slate-700">Max CIA Marks:</span> {{ $ciaMax }} Marks</div>
                <div><span class="font-bold text-slate-700">Pass Criteria:</span> Minimum 40% ({{ round($ciaMax * 0.40, 1) }} Marks)</div>
            </div>
        </div>
    </div>

    <!-- Statutory Component Weights Banner -->
    <div class="mb-4 bg-slate-50 border border-slate-300 rounded p-2 text-[10px] text-slate-700 grid grid-cols-3 text-center">
        <div><strong>Formative Assessment (40%):</strong> Max {{ $formativeMax }} Marks</div>
        <div><strong>Summative Tests (40%):</strong> Max {{ $summativeMax }} Marks</div>
        <div><strong>Attendance &amp; Performance (20%):</strong> Max {{ $attMax }} Marks</div>
    </div>

    <!-- CIA Marksheet Table -->
    <table class="w-full text-xs border-collapse border border-slate-400">
        <thead>
            <tr class="bg-slate-100 text-slate-800 font-bold text-center">
                <th class="border border-slate-400 py-1.5 px-1 w-8">#</th>
                <th class="border border-slate-400 py-1.5 px-1.5 w-12">Roll</th>
                <th class="border border-slate-400 py-1.5 px-2 w-24">Reg No</th>
                <th class="border border-slate-400 py-1.5 px-2 text-left">Student Name</th>
                <th class="border border-slate-400 py-1.5 px-1.5 w-16 bg-blue-50/40">Formative ({{ $formativeMax }})</th>
                <th class="border border-slate-400 py-1.5 px-1.5 w-16 bg-indigo-50/40">Summative ({{ $summativeMax }})</th>
                <th class="border border-slate-400 py-1.5 px-1.5 w-16 bg-emerald-50/40">Att. ({{ $attMax }})</th>
                <th class="border border-slate-400 py-1.5 px-1.5 w-16 bg-amber-100/60 font-black">Total ({{ $ciaMax }})</th>
                <th class="border border-slate-400 py-1.5 px-1.5 w-12">Grade</th>
                <th class="border border-slate-400 py-1.5 px-1.5 w-14">Result</th>
            </tr>
        </thead>
        <tbody>
            @forelse($studentResults as $idx => $res)
                @php
                    $student = $res['student'] ?? null;
                    $regNo = $student ? $student->reg_no : ($res['reg_no'] ?? '');
                    $name = $student ? $student->name : ($res['name'] ?? '');
                    $rollNo = $student ? $student->roll_no : ($res['roll_no'] ?? '-');
                    $gradeInfo = \App\Http\Controllers\R21VirtualClassroomDrawingController::calculateSbteGrade($res['total_cia'], $ciaMax);
                @endphp
                <tr class="text-center {{ $idx % 2 === 1 ? 'bg-slate-50/40' : 'bg-white' }}">
                    <td class="border border-slate-300 py-1 px-1 text-slate-500">{{ $idx + 1 }}</td>
                    <td class="border border-slate-300 py-1 px-1.5 font-semibold text-slate-900">{{ $rollNo }}</td>
                    <td class="border border-slate-300 py-1 px-2 font-mono text-slate-700">{{ $regNo }}</td>
                    <td class="border border-slate-300 py-1 px-2 text-left font-medium text-slate-900">{{ $name }}</td>
                    <td class="border border-slate-300 py-1 px-1.5 font-semibold text-blue-900 bg-blue-50/20">
                        {{ number_format($res['formative_mark'], 1) }}
                    </td>
                    <td class="border border-slate-300 py-1 px-1.5 font-semibold text-indigo-900 bg-indigo-50/20">
                        {{ number_format($res['summative_mark'], 1) }}
                    </td>
                    <td class="border border-slate-300 py-1 px-1.5 font-semibold text-emerald-900 bg-emerald-50/20">
                        {{ number_format($res['attendance_mark'], 1) }}
                    </td>
                    <td class="border border-slate-300 py-1 px-1.5 font-black text-slate-900 bg-amber-100/50">
                        {{ number_format($res['total_cia'], 1) }}
                    </td>
                    <td class="border border-slate-300 py-1 px-1.5 font-bold">
                        {{ $gradeInfo['grade'] }}
                    </td>
                    <td class="border border-slate-300 py-1 px-1.5 font-bold {{ $res['is_pass'] ? 'text-emerald-700' : 'text-rose-600' }}">
                        {{ $res['is_pass'] ? 'PASS' : 'FAIL' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="border border-slate-300 py-6 text-center text-slate-400">
                        No continuous internal assessment marks compiled yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Statutory Declaration -->
    <div class="mt-6 text-[10px] text-slate-500 italic">
        Certified that the continuous internal assessment marks entered above are verified against formative drawing sheet records, series test registers, and attendance sheets in compliance with Kerala SBTE R-2021 Regulation Clause 11.2.3.
    </div>

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
