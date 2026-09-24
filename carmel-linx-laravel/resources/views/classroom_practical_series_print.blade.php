<x-layouts.report-layout 
    title="Practical Series Examination Marksheet — {{ $subject->subject_name }}" 
    orientation="portrait" 
    :documentNo="'SBTE-R21-' . $subject->subject_code . '-SERIES'">

    <!-- Institutional Header -->
    <div class="text-center pb-3 border-b-2 border-slate-900 mb-4">
        <h1 class="text-lg font-bold uppercase tracking-wider text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
        <p class="text-xs text-slate-600 font-medium">Department of {{ $fullDepartment }}</p>
        <h2 class="text-sm font-bold uppercase mt-1 text-slate-800 underline">Practical Series Examination Marksheet (Revision 2021)</h2>
    </div>

    <!-- Meta Information Grid -->
    <div class="grid grid-cols-2 gap-4 rounded border border-slate-300 p-3 text-xs mb-4 bg-slate-50">
        <div class="space-y-1">
            <div><strong>Course Title:</strong> <span class="font-bold text-slate-900">{{ $subject->subject_name }}</span></div>
            <div><strong>Course Code:</strong> <span class="font-mono font-bold text-slate-900">{{ $subject->subject_code }}</span></div>
            <div><strong>Class / Batch:</strong> <span class="font-semibold text-slate-900">{{ $cleanedBatch }}</span></div>
        </div>
        <div class="space-y-1 text-right">
            <div><strong>Semester:</strong> <span class="font-semibold text-slate-900">Semester {{ $subject->semester }}</span></div>
            <div><strong>Max Series Marks:</strong> <span class="font-semibold text-slate-900">15 Marks (Avg of Test 1 &amp; Test 2)</span></div>
            <div><strong>Report Date:</strong> <span class="font-mono text-slate-900">{{ date('d-m-Y') }}</span></div>
        </div>
    </div>

    <!-- Scheme Box -->
    <div class="rounded border border-dashed border-slate-400 p-2.5 mb-4 text-[11px] bg-slate-50 flex flex-wrap justify-between items-center text-slate-700">
        <div><strong>Test 1 Scheme (15M):</strong> CO1 &amp; CO2 Evaluation</div>
        <div><strong>Test 2 Scheme (15M):</strong> CO3 &amp; CO4 Evaluation</div>
        <div><strong>Final Series CIA:</strong> Average of Test 1 and Test 2 (/15)</div>
    </div>

    <!-- Marksheet Table -->
    <div class="mb-6 overflow-hidden">
        <table class="w-full border-collapse border border-slate-400 text-xs">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold uppercase text-[10px]">
                    <th class="border border-slate-400 p-2 text-center w-10">Sl</th>
                    <th class="border border-slate-400 p-2 text-center w-12">Roll</th>
                    <th class="border border-slate-400 p-2 text-center w-28">PRN (SBTE)</th>
                    <th class="border border-slate-400 p-2 text-left">Student Name</th>
                    <th class="border border-slate-400 p-2 text-center w-24">Series Test 1<br>(15M)</th>
                    <th class="border border-slate-400 p-2 text-center w-24">Series Test 2<br>(15M)</th>
                    <th class="border border-slate-400 p-2 text-center w-28 bg-emerald-50 text-emerald-900">Test Average<br>(15M)</th>
                    <th class="border border-slate-400 p-2 text-center w-24">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $idx => $student)
                    @php
                        $t1 = isset($student->tests['Test 1']['total']) ? (float)$student->tests['Test 1']['total'] : 0.0;
                        $t2 = isset($student->tests['Test 2']['total']) ? (float)$student->tests['Test 2']['total'] : 0.0;
                        $avg = isset($student->tests['average']) ? (float)$student->tests['average'] : round(($t1 + $t2) / 2, 2);
                        $status = $avg >= 6.0 ? 'Satisfactory' : 'Needs Practice';
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="border border-slate-400 p-2 text-center text-slate-600 font-medium">{{ $idx + 1 }}</td>
                        <td class="border border-slate-400 p-2 text-center font-mono font-bold text-slate-900">{{ $student->roll_no ?? '-' }}</td>
                        <td class="border border-slate-400 p-2 text-center font-mono text-[11px] text-slate-700">{{ !empty($student->sbte_reg_no) ? $student->sbte_reg_no : $student->reg_no }}</td>
                        <td class="border border-slate-400 p-2 font-bold text-slate-900">{{ $student->name }}</td>
                        <td class="border border-slate-400 p-2 text-center font-mono font-bold text-slate-800">{{ number_format($t1, 1) }}</td>
                        <td class="border border-slate-400 p-2 text-center font-mono font-bold text-slate-800">{{ number_format($t2, 1) }}</td>
                        <td class="border border-slate-400 p-2 text-center font-mono font-bold text-emerald-800 bg-emerald-50 text-sm">{{ number_format($avg, 2) }}</td>
                        <td class="border border-slate-400 p-2 text-center font-semibold text-[11px] {{ $avg >= 6.0 ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $status }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="border border-slate-400 p-4 text-center text-slate-500 italic">No students enrolled.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Signatures -->
    <div class="grid grid-cols-3 gap-6 pt-12 mt-8 text-center text-xs font-bold text-slate-800 border-t border-slate-300 page-break-inside-avoid">
        <div>Name &amp; Signature of Lab Assessor</div>
        <div>Name &amp; Signature of Coordinator</div>
        <div>Head of Department</div>
    </div>

</x-layouts.report-layout>
