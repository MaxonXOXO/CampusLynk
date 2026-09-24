<x-layouts.report-layout 
    title="Class Teaching &amp; Attendance Log Register — {{ $subject->subject_code }}" 
    orientation="portrait" 
    :documentNo="'SBTE-R21-' . $subject->subject_code . '-LOG'">

    <!-- Institutional Header -->
    <div class="text-center pb-3 border-b-2 border-slate-900 mb-3">
        <h1 class="text-base font-bold uppercase tracking-wider text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
        <p class="text-xs text-slate-600 font-medium">Department of {{ $fullDepartment }}</p>
        <h2 class="text-xs font-bold uppercase mt-0.5 text-slate-800 underline">Official Classroom Teaching &amp; Attendance Log Register (Revision 2021)</h2>
    </div>

    <!-- Meta Details Grid -->
    <div class="grid grid-cols-2 gap-3 rounded border border-slate-300 p-2.5 text-xs mb-3 bg-slate-50">
        <div class="space-y-1">
            <div><strong>Department:</strong> <span class="font-semibold text-slate-900">{{ $fullDepartment }}</span></div>
            <div><strong>Course:</strong> <span class="font-semibold text-slate-900">{{ $subject->subject_code }} — {{ $subject->subject_name }}</span></div>
            <div><strong>Sessions Recorded:</strong> <span class="font-bold text-slate-900">{{ $logs->count() }} Sessions ({{ $totalHours }} Conducted Hours)</span></div>
        </div>
        <div class="space-y-1 text-right">
            <div><strong>Semester &amp; Batch:</strong> <span class="font-semibold text-slate-900">Semester {{ $subject->semester }} (Batch {{ $cleanedBatch }})</span></div>
            <div><strong>Faculty In-Charge:</strong> <span class="font-semibold text-slate-900">{{ $lecturerName }}</span></div>
            <div><strong>Report Date:</strong> <span class="font-mono text-slate-900">{{ $currentDate }}</span></div>
        </div>
    </div>

    <!-- Main Log Table -->
    <div class="mb-4 overflow-hidden">
        <table class="w-full border-collapse border border-slate-400 text-xs">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold uppercase text-[9px]">
                    <th class="border border-slate-400 p-1 text-center w-8">Sl</th>
                    <th class="border border-slate-400 p-1 text-center w-20">Date</th>
                    <th class="border border-slate-400 p-1 text-center w-16">Period</th>
                    <th class="border border-slate-400 p-1 text-left">Syllabus Topic Covered / Log Entry</th>
                    <th class="border border-slate-400 p-1 text-center w-12 text-emerald-800">Pres</th>
                    <th class="border border-slate-400 p-1 text-center w-12 text-rose-800">Abs</th>
                    <th class="border border-slate-400 p-1 text-center w-14">Attn %</th>
                    <th class="border border-slate-400 p-1 text-center w-12">Sign</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $idx => $log)
                <tr class="hover:bg-slate-50 text-[10px]">
                    <td class="border border-slate-400 p-1 text-center font-mono text-slate-500">{{ $idx + 1 }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono font-semibold">{{ $log->formatted_date }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono text-slate-600">{{ $log->period_label }}</td>
                    <td class="border border-slate-400 p-1 font-medium text-slate-900">{{ $log->topics_covered ?: 'Syllabus lecture session' }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono font-bold text-emerald-700">{{ $log->present_count }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono text-rose-700">{{ $log->absent_count }}</td>
                    <td class="border border-slate-400 p-1 text-center font-mono font-bold {{ $log->attendance_pct < 75 ? 'text-rose-700 bg-rose-50' : 'text-emerald-800' }}">
                        {{ number_format($log->attendance_pct, 1) }}%
                    </td>
                    <td class="border border-slate-400 p-1 text-center text-slate-400 text-xs">✓</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="border border-slate-400 p-4 text-center text-slate-500 italic">No class logs recorded yet for this subject.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Summary Statistics Card -->
    <div class="p-3 rounded border border-slate-300 bg-slate-50 mb-6 text-xs page-break-inside-avoid">
        <div class="font-bold uppercase tracking-wider text-slate-700 mb-2 border-b border-slate-200 pb-1 text-[10px]">
            Class Log &amp; Syllabus Coverage Summary
        </div>
        <div class="grid grid-cols-4 gap-3 text-center">
            <div class="p-2 bg-white rounded border border-slate-200">
                <div class="text-sm font-bold font-mono text-blue-800">{{ $logs->count() }}</div>
                <div class="text-[9px] uppercase font-bold text-slate-500">Sessions Recorded</div>
            </div>
            <div class="p-2 bg-white rounded border border-slate-200">
                <div class="text-sm font-bold font-mono text-slate-800">{{ $totalHours }}</div>
                <div class="text-[9px] uppercase font-bold text-slate-500">Hours Conducted</div>
            </div>
            <div class="p-2 bg-white rounded border border-slate-200">
                <div class="text-sm font-bold font-mono text-emerald-700">{{ number_format($overallAvgAttn, 1) }}%</div>
                <div class="text-[9px] uppercase font-bold text-slate-500">Cumulative Attendance</div>
            </div>
            <div class="p-2 bg-white rounded border border-slate-200">
                <div class="text-sm font-bold font-mono text-indigo-700">{{ $completedTopicsCount }} / {{ $totalPlannedTopics }}</div>
                <div class="text-[9px] uppercase font-bold text-slate-500">Syllabus Progress</div>
            </div>
        </div>
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
