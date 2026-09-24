<x-layouts.report-layout 
    title="Practical Evaluation Card — {{ $student->name }} ({{ $student->sbte_reg_no ?: $student->reg_no }})" 
    orientation="portrait" 
    :documentNo="'SBTE-R21-' . $batchSubject->subject_code . '-CARD'">

    <!-- Institutional Header -->
    <div class="text-center pb-3 border-b-2 border-slate-900 mb-4">
        <h1 class="text-lg font-bold uppercase tracking-wider text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
        <p class="text-xs text-slate-600 font-medium">Department of {{ $fullDepartment }}</p>
        <h2 class="text-sm font-bold uppercase mt-1 text-slate-800 underline">Individual Student Practical Continuous Evaluation &amp; Attendance Card (Revision 2021)</h2>
    </div>

    <!-- Student and Subject Metadata Grid -->
    <div class="grid grid-cols-2 gap-3 rounded border border-slate-300 p-3 text-xs mb-4 bg-slate-50">
        <div class="space-y-1">
            <div><strong>Student Name:</strong> <span class="font-bold text-slate-900 text-sm">{{ $student->name }}</span></div>
            <div><strong>Register No (PRN):</strong> <span class="font-mono font-bold text-blue-700">{{ $student->sbte_reg_no ?: $student->reg_no }}</span></div>
            <div><strong>Course / Subject:</strong> <span class="font-semibold text-slate-900">{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</span></div>
            <div><strong>Curriculum:</strong> <span class="text-slate-700">Revision 2021 (OBE Standard)</span></div>
        </div>
        <div class="space-y-1 text-right">
            <div><strong>Roll No:</strong> <span class="font-mono font-bold text-slate-900">{{ $student->roll_no ?: '-' }}</span></div>
            <div><strong>Lab Batch:</strong> <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-bold text-[10px]">{{ !empty($labBatch) ? $labBatch : 'Whole Class' }}</span></div>
            <div><strong>Class / Semester:</strong> <span class="font-semibold text-slate-900">{{ $cleanedBatch }} &bull; Sem {{ $batchSubject->semester }}</span></div>
            <div><strong>Conducted Exps:</strong> <span class="font-bold text-slate-900">{{ $attendedCount }} Done</span> / {{ $totalCompletedExps }} Total Conducted</div>
        </div>
    </div>

    <!-- Continuous Internal Assessment (CIA 75M) Summary -->
    <div class="mb-4">
        <div class="flex justify-between items-center mb-1 text-[11px] font-bold uppercase text-slate-800 border-b border-blue-600 pb-1">
            <span>Continuous Internal Assessment (CIA 75 Marks) — Formative &amp; Summative Summary</span>
            <span class="text-[10px] text-slate-500 font-normal normal-case">Divisor: {{ $totalCompletedExps }} completed experiments</span>
        </div>
        <table class="w-full border-collapse border border-slate-400 text-center text-xs">
            <thead>
                <tr class="text-[9px] uppercase font-bold">
                    <th colspan="6" class="border border-slate-400 p-1 bg-blue-50 text-blue-800">Continuous Lab Work Rubrics (Max 37.5)</th>
                    <th rowspan="2" class="border border-slate-400 p-1 bg-amber-50 text-amber-800 w-16">Open Proj<br>(7.5)</th>
                    <th rowspan="2" class="border border-slate-400 p-1 bg-emerald-50 text-emerald-800 w-16">Attendance<br>(15)</th>
                    <th colspan="3" class="border border-slate-400 p-1 bg-purple-50 text-purple-800">Practical Tests (Max 15)</th>
                    <th rowspan="2" class="border border-slate-400 p-1 bg-teal-100 text-teal-900 w-20 font-black">Total CIA<br>(75M)</th>
                </tr>
                <tr class="text-[8.5px] uppercase text-slate-600 bg-slate-100">
                    <th class="border border-slate-400 p-1">Rough (5)</th>
                    <th class="border border-slate-400 p-1">Fair (7.5)</th>
                    <th class="border border-slate-400 p-1">Obs (7.5)</th>
                    <th class="border border-slate-400 p-1">Proc (7.5)</th>
                    <th class="border border-slate-400 p-1">Viva (10)</th>
                    <th class="border border-slate-400 p-1 bg-blue-100 text-blue-900 font-bold">Lab Avg (37.5)</th>
                    <th class="border border-slate-400 p-1">T1 (15)</th>
                    <th class="border border-slate-400 p-1">T2 (15)</th>
                    <th class="border border-slate-400 p-1 bg-purple-100 text-purple-900 font-bold">Test Avg</th>
                </tr>
            </thead>
            <tbody>
                <tr class="font-mono font-bold text-slate-800">
                    <td class="border border-slate-400 p-1.5 text-amber-700">{{ number_format($avgRoughRecord, 2) }}</td>
                    <td class="border border-slate-400 p-1.5 text-emerald-700">{{ number_format($avgFairRecord, 2) }}</td>
                    <td class="border border-slate-400 p-1.5 text-blue-700">{{ number_format($avgObsPrep, 2) }}</td>
                    <td class="border border-slate-400 p-1.5 text-purple-700">{{ number_format($avgProcPunct, 2) }}</td>
                    <td class="border border-slate-400 p-1.5 text-rose-700">{{ number_format($avgVivaVoce, 2) }}</td>
                    <td class="border border-slate-400 p-1.5 bg-blue-50 text-blue-900 font-black text-sm">{{ number_format($avgLabWork, 2) }}</td>
                    <td class="border border-slate-400 p-1.5 text-amber-800">{{ number_format($microProject, 1) }}</td>
                    <td class="border border-slate-400 p-1.5 text-emerald-800">
                        {{ number_format($attendanceMarks, 1) }}
                        <span class="block text-[8px] font-sans text-slate-500 font-normal">({{ $attendancePercentage }}%)</span>
                    </td>
                    <td class="border border-slate-400 p-1.5 text-purple-800">{{ number_format($scoreT1, 1) }}</td>
                    <td class="border border-slate-400 p-1.5 text-purple-800">{{ number_format($scoreT2, 1) }}</td>
                    <td class="border border-slate-400 p-1.5 bg-purple-50 text-purple-900 font-bold">{{ number_format($avgTests, 2) }}</td>
                    <td class="border border-slate-400 p-1.5 bg-teal-50 text-teal-900 font-black text-base">{{ number_format($totalInternal, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if(!empty($openEndedTopic))
    <div class="mb-4 text-xs p-2 rounded bg-amber-50 border border-amber-200 text-amber-900">
        <strong>Open-Ended Project / Micro-Project Topic:</strong> {{ $openEndedTopic }}
    </div>
    @endif

    <!-- Detailed Experiment Log Table -->
    <div class="mb-4">
        <div class="flex justify-between items-center mb-1 text-[11px] font-bold uppercase text-slate-800 border-b border-blue-600 pb-1">
            <span>Day-to-Day Practical Experiment Marks &amp; Attendance Log</span>
            <span class="text-[10px] text-slate-500 font-normal normal-case">Total Exps: {{ count($expRecords) }} &bull; Conducted: {{ $conductedCount }}</span>
        </div>
        <table class="w-full border-collapse border border-slate-400 text-xs">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold uppercase text-[8.5px]">
                    <th class="border border-slate-400 p-1 text-center w-8">Exp #</th>
                    <th class="border border-slate-400 p-1 text-left">Experiment Title</th>
                    <th class="border border-slate-400 p-1 text-center w-10">CO</th>
                    <th class="border border-slate-400 p-1 text-center w-16">Date</th>
                    <th class="border border-slate-400 p-1 text-center w-12">Attn</th>
                    <th class="border border-slate-400 p-1 text-center w-10">Rough (5)</th>
                    <th class="border border-slate-400 p-1 text-center w-10">Fair (7.5)</th>
                    <th class="border border-slate-400 p-1 text-center w-10">Obs (7.5)</th>
                    <th class="border border-slate-400 p-1 text-center w-10">Proc (7.5)</th>
                    <th class="border border-slate-400 p-1 text-center w-10">Viva (10)</th>
                    <th class="border border-slate-400 p-1 text-center w-12 bg-blue-50 font-bold">Total (37.5)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expRecords as $r)
                <tr class="hover:bg-slate-50 text-[10px]">
                    <td class="border border-slate-400 p-1 text-center font-mono font-bold">{{ $r['experiment_no'] }}</td>
                    <td class="border border-slate-400 p-1 font-medium text-slate-900">{{ \Illuminate\Support\Str::limit($r['title'], 55) }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono text-[9px] text-slate-600">{{ $r['co_tag'] }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono text-[9px] text-slate-700">
                        {{ $r['conducted_date'] ? date('d-m-Y', strtotime($r['conducted_date'])) : '-' }}
                    </td>
                    <td class="border border-slate-400 p-1 text-center">
                        @if($r['is_attended'])
                            <span class="px-1 py-0.2 rounded bg-emerald-100 text-emerald-800 text-[8.5px] font-bold">Pres</span>
                        @else
                            <span class="px-1 py-0.2 rounded bg-rose-100 text-rose-800 text-[8.5px] font-bold">Abs</span>
                        @endif
                    </td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $r['has_score'] ? number_format($r['rough_record'], 1) : '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $r['has_score'] ? number_format($r['fair_record'], 1) : '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $r['has_score'] ? number_format($r['obs_prep'], 1) : '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $r['has_score'] ? number_format($r['proc_punct'], 1) : '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ $r['has_score'] ? number_format($r['viva_voce'], 1) : '-' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono font-bold bg-blue-50/50 text-blue-900">
                        {{ $r['has_score'] ? number_format($r['total_mark'], 1) : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="border border-slate-400 p-3 text-center text-slate-500 italic">No experiments found for this subject.</td>
                </tr>
                @endforelse

                <!-- Total Marks Obtained Row -->
                <tr class="bg-slate-100 font-bold text-slate-900 border-t-2 border-slate-600 text-[10px]">
                    <td colspan="5" class="border border-slate-400 p-1 text-right uppercase tracking-wider">
                        Total Marks Obtained (Across {{ $gradedCount }} Graded Exps):
                    </td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ number_format($sumRough, 1) }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ number_format($sumFair, 1) }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ number_format($sumObs, 1) }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ number_format($sumProc, 1) }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ number_format($sumViva, 1) }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono font-black text-blue-900 bg-blue-100">{{ number_format($sumTotal, 1) }}</td>
                </tr>

                <!-- Consolidated Formative Rubric Averages Row -->
                <tr class="bg-blue-100 font-black text-blue-950 border-b-2 border-blue-700 text-[10.5px]">
                    <td colspan="5" class="border border-slate-400 p-1 text-right uppercase tracking-wider">
                        Consolidated Formative Average (/ {{ $totalCompletedExps }} Exps):
                    </td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ number_format($avgRoughRecord, 2) }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ number_format($avgFairRecord, 2) }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ number_format($avgObsPrep, 2) }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ number_format($avgProcPunct, 2) }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono">{{ number_format($avgVivaVoce, 2) }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono font-black text-blue-900 bg-blue-200 text-sm">{{ number_format($avgLabWork, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Final Result Status Box -->
    <div class="grid grid-cols-3 gap-3 p-3 rounded border border-slate-300 bg-slate-50 text-xs mb-8 page-break-inside-avoid">
        <div>
            <div class="text-[9px] uppercase font-bold text-slate-500">Continuous Internal Assessment</div>
            <div class="text-sm font-mono font-bold text-emerald-800">{{ number_format($totalInternal, 2) }} / 75.00</div>
        </div>
        <div>
            <div class="text-[9px] uppercase font-bold text-slate-500">Board Exam (ESE - 50M)</div>
            <div class="text-sm font-mono font-bold text-blue-800">{{ $eseDisplay }}</div>
        </div>
        <div>
            <div class="text-[9px] uppercase font-bold text-slate-500">Total Result (125M) &amp; Grade</div>
            <div class="text-sm font-mono font-bold text-purple-900">{{ $finalResultDisplay }}</div>
        </div>
    </div>

    <!-- Signatures Block -->
    <div class="grid grid-cols-3 gap-6 pt-10 text-center text-xs font-bold text-slate-800 border-t border-slate-300 page-break-inside-avoid">
        <div>Signature of Student</div>
        <div>Signature of Lab Assessor / Faculty</div>
        <div>Head of Department</div>
    </div>

</x-layouts.report-layout>
