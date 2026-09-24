<x-layouts.report-layout 
    title="Class Roster &amp; Attainment Register — {{ $subject->subject_code }}" 
    orientation="portrait" 
    :documentNo="'SBTE-R21-' . $subject->subject_code . '-ROSTER'">

    <!-- Institutional Header -->
    <div class="text-center pb-3 border-b-2 border-slate-900 mb-3">
        <h1 class="text-base font-bold uppercase tracking-wider text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
        <p class="text-xs text-slate-600 font-medium">Department of {{ $fullDepartment }}</p>
        <h2 class="text-xs font-bold uppercase mt-0.5 text-slate-800 underline">Consolidated Theory Class Roster &amp; Attainment Register (Revision 2021)</h2>
    </div>

    <!-- Meta Details Grid -->
    <div class="grid grid-cols-2 gap-3 rounded border border-slate-300 p-2.5 text-xs mb-3 bg-slate-50">
        <div class="space-y-1">
            <div><strong>Department:</strong> <span class="font-semibold text-slate-900">{{ $fullDepartment }}</span></div>
            <div><strong>Course:</strong> <span class="font-semibold text-slate-900">{{ $subject->subject_code }} — {{ $subject->subject_name }}</span></div>
            <div><strong>Conducted Hours:</strong> <span class="font-bold text-slate-900">{{ $totalConductedHours }} Hours</span> (Total Sessions)</div>
        </div>
        <div class="space-y-1 text-right">
            <div><strong>Semester &amp; Batch:</strong> <span class="font-semibold text-slate-900">Semester {{ $subject->semester }} (Batch {{ $cleanedBatch }})</span></div>
            <div><strong>Faculty In-Charge:</strong> <span class="font-semibold text-slate-900">{{ $lecturerName }}</span></div>
            <div><strong>Students Enrolled:</strong> <span class="font-bold text-slate-900">{{ $students->count() }} Candidates</span></div>
        </div>
    </div>

    <!-- Main Consolidated Register Table -->
    <div class="mb-4 overflow-hidden">
        <table class="w-full border-collapse border border-slate-400 text-xs">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold uppercase text-[8.5px]">
                    <th rowspan="2" class="border border-slate-400 p-1 text-center w-7">Roll</th>
                    <th rowspan="2" class="border border-slate-400 p-1 text-left">Student Name</th>
                    <th rowspan="2" class="border border-slate-400 p-1 text-center w-20">SBTE Reg No</th>
                    <th colspan="4" class="border border-slate-400 p-0.5 text-center bg-indigo-50 text-indigo-950">Attendance Hours</th>
                    <th colspan="4" class="border border-slate-400 p-0.5 text-center bg-amber-50 text-amber-950">Assignments (20)</th>
                    <th colspan="4" class="border border-slate-400 p-0.5 text-center bg-blue-50 text-blue-950">Summative Tests (20)</th>
                    <th colspan="2" class="border border-slate-400 p-0.5 text-center bg-emerald-50 text-emerald-950">Attainment</th>
                </tr>
                <tr class="bg-slate-50 text-slate-600 font-bold text-[8px] uppercase">
                    <th class="border border-slate-400 p-0.5 text-center w-6">Tot</th>
                    <th class="border border-slate-400 p-0.5 text-center w-6 text-emerald-700">Pres</th>
                    <th class="border border-slate-400 p-0.5 text-center w-6 text-rose-700">Abs</th>
                    <th class="border border-slate-400 p-0.5 text-center w-8">Attn%</th>
                    <th class="border border-slate-400 p-0.5 text-center w-6">CO1</th>
                    <th class="border border-slate-400 p-0.5 text-center w-6">CO2</th>
                    <th class="border border-slate-400 p-0.5 text-center w-6">CO3</th>
                    <th class="border border-slate-400 p-0.5 text-center w-6">CO4</th>
                    <th class="border border-slate-400 p-0.5 text-center w-6">T1</th>
                    <th class="border border-slate-400 p-0.5 text-center w-6">T2</th>
                    <th class="border border-slate-400 p-0.5 text-center w-6">T3</th>
                    <th class="border border-slate-400 p-0.5 text-center w-6">T4</th>
                    <th class="border border-slate-400 p-0.5 text-center w-8">Level</th>
                    <th class="border border-slate-400 p-0.5 text-center w-12">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $stud)
                <tr class="hover:bg-slate-50 text-[9.5px]">
                    <td class="border border-slate-400 p-1 text-center font-mono font-bold">{{ $stud->roll_no ?: '-' }}</td>
                    <td class="border border-slate-400 p-1 font-medium text-slate-900">{{ $stud->name }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono text-[9px]">{{ $stud->sbte_reg_no ?: $stud->reg_no }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $stud->total_hours }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono font-bold text-emerald-700">{{ $stud->present_hours }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono text-rose-700">{{ $stud->absent_hours }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono font-bold {{ $stud->att_percent < 75 ? 'text-rose-700 bg-rose-50' : 'text-emerald-800' }}">
                        {{ number_format($stud->att_percent, 1) }}%
                    </td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $stud->assignments['CO1'] ?? '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $stud->assignments['CO2'] ?? '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $stud->assignments['CO3'] ?? '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $stud->assignments['CO4'] ?? '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $stud->tests['T1'] ?? '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $stud->tests['T2'] ?? '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $stud->tests['T3'] ?? '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $stud->tests['T4'] ?? '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-bold text-blue-900">{{ $stud->attainment_level ?: '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-bold text-[8.5px]">
                        @if($stud->att_percent >= 75)
                            <span class="px-1 py-0.2 rounded bg-emerald-100 text-emerald-800">ELIGIBLE</span>
                        @else
                            <span class="px-1 py-0.2 rounded bg-rose-100 text-rose-800">SHORTAGE</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="17" class="border border-slate-400 p-4 text-center text-slate-500 italic">No enrolled students found for this classroom.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Summary Statistics & CO Attainment Card -->
    <div class="p-3 rounded border border-slate-300 bg-slate-50 mb-6 text-xs page-break-inside-avoid">
        <div class="font-bold uppercase tracking-wider text-slate-700 mb-2 border-b border-slate-200 pb-1 text-[10px]">
            Class Roster &amp; Outcome Attainment Summary
        </div>
        <div class="grid grid-cols-4 gap-3 text-center mb-2">
            <div class="p-2 bg-white rounded border border-slate-200">
                <div class="text-sm font-bold font-mono text-blue-800">{{ $students->count() }}</div>
                <div class="text-[9px] uppercase font-bold text-slate-500">Total Enrolled</div>
            </div>
            <div class="p-2 bg-white rounded border border-slate-200">
                <div class="text-sm font-bold font-mono text-slate-800">{{ $totalConductedHours }}</div>
                <div class="text-[9px] uppercase font-bold text-slate-500">Hours Conducted</div>
            </div>
            <div class="p-2 bg-white rounded border border-slate-200">
                <div class="text-sm font-bold font-mono text-emerald-700">{{ number_format($overallAvgAttn, 1) }}%</div>
                <div class="text-[9px] uppercase font-bold text-slate-500">Avg Attendance</div>
            </div>
            <div class="p-2 bg-white rounded border border-slate-200">
                <div class="text-sm font-bold font-mono {{ $shortageCount > 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                    {{ $eligibleCount }} / {{ $students->count() }}
                </div>
                <div class="text-[9px] uppercase font-bold text-slate-500">Eligible ({{ $shortageCount }} Shortage)</div>
            </div>
        </div>

        @if(!empty($coAttainmentSummary))
        <div class="flex justify-between items-center text-[10px] text-slate-700 pt-2 border-t border-dashed border-slate-300">
            <div><strong>Direct CO Attainment:</strong></div>
            <div>CO1: <strong>{{ $coAttainmentSummary['CO1'] ?? 'Level 3' }}</strong></div>
            <div>CO2: <strong>{{ $coAttainmentSummary['CO2'] ?? 'Level 3' }}</strong></div>
            <div>CO3: <strong>{{ $coAttainmentSummary['CO3'] ?? 'Level 3' }}</strong></div>
            <div>CO4: <strong>{{ $coAttainmentSummary['CO4'] ?? 'Level 3' }}</strong></div>
            <div>PO Mapping Level: <strong>{{ $poLevelAverage ?? '2.8' }} / 3.0</strong></div>
        </div>
        @endif
    </div>

    <!-- Official Signatures Row -->
    <div class="grid grid-cols-3 gap-8 pt-10 text-center text-xs font-bold text-slate-800 border-t border-slate-300 page-break-inside-avoid">
        <div>
            <div class="border-t border-slate-400 pt-1 w-44 mx-auto">{{ $lecturerName }}</div>
            <div class="text-[10px] text-slate-500 mt-0.5">Faculty In-Charge</div>
        </div>
        <div>
            <div class="border-t border-slate-400 pt-1 w-44 mx-auto">{{ $fullDepartment }}</div>
            <div class="text-[10px] text-slate-500 mt-0.5">Head of Department</div>
        </div>
        <div>
            <div class="border-t border-slate-400 pt-1 w-44 mx-auto">Principal</div>
            <div class="text-[10px] text-slate-500 mt-0.5">Carmel Polytechnic College</div>
        </div>
    </div>

</x-layouts.report-layout>
