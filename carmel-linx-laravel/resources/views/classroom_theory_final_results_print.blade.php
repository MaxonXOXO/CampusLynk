<x-layouts.report-layout 
    title="Consolidated Final CIE &amp; Results Report — {{ $subject->subject_code }}" 
    orientation="landscape" 
    :documentNo="'SBTE-R21-' . $subject->subject_code . '-CIE'">

    <!-- Institutional Header -->
    <div class="text-center pb-3 border-b-2 border-slate-900 mb-3">
        <h1 class="text-base font-bold uppercase tracking-wider text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
        <p class="text-xs text-slate-600 font-medium">Department of {{ $fullDepartment }}</p>
        <h2 class="text-xs font-bold uppercase mt-0.5 text-slate-800 underline">Continuous Internal Evaluation (CIE) &amp; Final Result Register (Revision 2021)</h2>
    </div>

    <!-- Meta Details Grid -->
    <div class="grid grid-cols-4 gap-2 rounded border border-slate-300 p-2.5 text-xs mb-3 bg-slate-50">
        <div><strong>Course:</strong> <span class="font-semibold text-slate-900">{{ $subject->subject_name }}</span></div>
        <div><strong>Code:</strong> <span class="font-mono font-bold text-slate-900">{{ $subject->subject_code }}</span></div>
        <div><strong>Semester:</strong> <span class="font-semibold text-slate-900">Semester {{ $subject->semester }}</span></div>
        <div><strong>Class Batch:</strong> <span class="font-semibold text-slate-900">{{ $cleanedBatch }}</span></div>
        <div><strong>Faculty:</strong> <span class="font-semibold text-slate-900">{{ $lecturerName }}</span></div>
        <div><strong>Enrolled:</strong> <span class="font-semibold text-slate-900">{{ $totalStudents }} Students</span></div>
        <div><strong>Scheme:</strong> <span class="text-slate-700">Revision 2021 (OBE)</span></div>
        <div><strong>Report Date:</strong> <span class="font-mono">{{ $currentDate }}</span></div>
    </div>

    <!-- Main CIE Marksheet Table -->
    <div class="mb-4 overflow-hidden">
        <table class="w-full border-collapse border border-slate-400 text-xs">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold uppercase text-[9px]">
                    <th rowspan="2" class="border border-slate-400 p-1 text-center w-7">Sl</th>
                    <th rowspan="2" class="border border-slate-400 p-1 text-center w-8">Roll</th>
                    <th rowspan="2" class="border border-slate-400 p-1 text-center w-24">Reg No</th>
                    <th rowspan="2" class="border border-slate-400 p-1 text-left">Student Name</th>
                    <th colspan="5" class="border border-slate-400 p-1 text-center bg-amber-50 text-amber-900">Continuous Assignments (Max 20)</th>
                    <th colspan="5" class="border border-slate-400 p-1 text-center bg-blue-50 text-blue-900">Summative Written Tests (Max 20)</th>
                    <th colspan="2" class="border border-slate-400 p-1 text-center bg-emerald-50 text-emerald-900">Attendance</th>
                    <th rowspan="2" class="border border-slate-400 p-1 text-center w-16 bg-indigo-100 text-indigo-950 font-black">Total CIE<br>(50M)</th>
                    <th rowspan="2" class="border border-slate-400 p-1 text-center w-16">Eligibility</th>
                </tr>
                <tr class="bg-slate-50 text-slate-600 font-bold text-[8.5px] uppercase">
                    <th class="border border-slate-400 p-0.5 text-center w-8">CO1</th>
                    <th class="border border-slate-400 p-0.5 text-center w-8">CO2</th>
                    <th class="border border-slate-400 p-0.5 text-center w-8">CO3</th>
                    <th class="border border-slate-400 p-0.5 text-center w-8">CO4</th>
                    <th class="border border-slate-400 p-0.5 text-center w-9 bg-amber-100 text-amber-950 font-bold">Avg</th>
                    <th class="border border-slate-400 p-0.5 text-center w-8">CO1</th>
                    <th class="border border-slate-400 p-0.5 text-center w-8">CO2</th>
                    <th class="border border-slate-400 p-0.5 text-center w-8">CO3</th>
                    <th class="border border-slate-400 p-0.5 text-center w-8">CO4</th>
                    <th class="border border-slate-400 p-0.5 text-center w-9 bg-blue-100 text-blue-950 font-bold">Avg</th>
                    <th class="border border-slate-400 p-0.5 text-center w-10">%</th>
                    <th class="border border-slate-400 p-0.5 text-center w-9">Marks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $s)
                    <tr class="hover:bg-slate-50 text-[10px]">
                        <td class="border border-slate-400 p-1 text-center font-mono text-slate-500">{{ $index + 1 }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-bold">{{ $s->roll_no ?? ($index + 1) }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-semibold text-slate-700">{{ $s->reg_no }}</td>
                        <td class="border border-slate-400 p-1 font-medium text-slate-900">{{ $s->name }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ $s->co_assign['CO1'] ?? '-' }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ $s->co_assign['CO2'] ?? '-' }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ $s->co_assign['CO3'] ?? '-' }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ $s->co_assign['CO4'] ?? '-' }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-bold bg-amber-50 text-amber-900">{{ $s->assign_avg }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ $s->co_summative['CO1'] ?? '-' }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ $s->co_summative['CO2'] ?? '-' }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ $s->co_summative['CO3'] ?? '-' }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ $s->co_summative['CO4'] ?? '-' }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-bold bg-blue-50 text-blue-900">{{ $s->summ_avg }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ $s->att_percent }}%</td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-semibold">{{ $s->att_marks }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-black bg-indigo-50 text-indigo-900 text-xs">{{ $s->total_cie }}</td>
                        <td class="border border-slate-400 p-1 text-center font-bold text-[9px]">
                            @if($s->status === 'ELIGIBLE')
                                <span class="px-1 py-0.2 rounded bg-emerald-100 text-emerald-800">ELIGIBLE</span>
                            @else
                                <span class="px-1 py-0.2 rounded bg-rose-100 text-rose-800">{{ $s->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="17" class="border border-slate-400 p-4 text-center text-slate-500 italic">No students enrolled in this classroom batch.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Signatures Row -->
    <div class="grid grid-cols-3 gap-8 pt-10 text-center text-xs font-bold text-slate-800 border-t border-slate-300 page-break-inside-avoid">
        <div>
            <div class="border-t border-slate-400 pt-1 w-48 mx-auto">Faculty In-Charge</div>
            <div class="text-[10px] text-slate-500 mt-0.5">{{ $lecturerName }}</div>
        </div>
        <div>
            <div class="border-t border-slate-400 pt-1 w-48 mx-auto">Head of Department</div>
            <div class="text-[10px] text-slate-500 mt-0.5">Dept. of {{ $fullDepartment }}</div>
        </div>
        <div>
            <div class="border-t border-slate-400 pt-1 w-48 mx-auto">Principal</div>
            <div class="text-[10px] text-slate-500 mt-0.5">Carmel Polytechnic College</div>
        </div>
    </div>

</x-layouts.report-layout>
