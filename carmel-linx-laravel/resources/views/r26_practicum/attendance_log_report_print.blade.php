<x-layouts.report-layout 
    title="Teaching & Attendance Log Register — {{ $batchSubject->subject_name }}"
    documentNo="DOC-R26-P-ATTN-LOG"
    orientation="portrait"
>
    <!-- Header -->
    <div class="text-center border-b-2 border-slate-800 pb-3 mb-4">
        <h1 class="text-xl font-bold uppercase tracking-wide text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
        <h2 class="text-sm font-semibold text-slate-700 mt-1">Teaching & Attendance Log Register — Revision 2026 Practicum</h2>
        <p class="text-xs text-slate-500 mt-0.5">Affiliated to SBTE Kerala | Approved by AICTE, New Delhi</p>
    </div>

    <!-- Metadata Grid -->
    <div class="grid grid-cols-3 gap-3 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs mb-4">
        <div>
            <span class="text-slate-500 block font-medium">Subject:</span>
            <span class="font-bold text-slate-900">{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Class / Semester:</span>
            <span class="font-bold text-slate-900">{{ $batchName ?? 'Batch' }} &bull; Sem {{ $classroom->current_semester ?? $batchSubject->semester ?? '1' }}</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Department:</span>
            <span class="font-bold text-slate-900">{{ $departmentName ?? 'Engineering' }}</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Faculty In-Charge:</span>
            <span class="font-bold text-slate-900">{{ $lecturerName ?? 'Faculty' }}</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Total Enrolled:</span>
            <span class="font-bold text-slate-900">{{ $totalEnrolled }} Students</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Overall Average Attn:</span>
            <span class="font-bold {{ $overallAvgAttn >= 75 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $overallAvgAttn }}%</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Planned / Completed Topics:</span>
            <span class="font-bold text-slate-900">{{ $completedTopicsCount }} / {{ $totalPlannedTopics }} Hours</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Sessions Logged:</span>
            <span class="font-bold text-slate-900">{{ $totalHours }} Sessions</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Generated On:</span>
            <span class="font-bold text-slate-900">{{ date('d M Y, h:i A') }}</span>
        </div>
    </div>

    <!-- Attendance Log Table -->
    <div class="overflow-x-auto mb-6">
        <table class="w-full text-left border-collapse text-[11px]">
            <thead>
                <tr class="bg-slate-800 text-white font-semibold">
                    <th class="border border-slate-700 px-2 py-1.5 text-center w-8">#</th>
                    <th class="border border-slate-700 px-2 py-1.5 w-20 text-center">Date</th>
                    <th class="border border-slate-700 px-2 py-1.5 w-16 text-center">Period</th>
                    <th class="border border-slate-700 px-2 py-1.5 w-16 text-center">Sub-Batch</th>
                    <th class="border border-slate-700 px-2 py-1.5">Topics Covered / Activity</th>
                    <th class="border border-slate-700 px-2 py-1.5 text-center w-12">Present</th>
                    <th class="border border-slate-700 px-2 py-1.5 text-center w-12">Absent</th>
                    <th class="border border-slate-700 px-2 py-1.5">Absentees (Roll Nos)</th>
                    <th class="border border-slate-700 px-2 py-1.5 text-center w-14">Attn %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }} hover:bg-slate-100/80">
                    <td class="border border-slate-200 px-2 py-1 text-center font-medium text-slate-600">{{ $index + 1 }}</td>
                    <td class="border border-slate-200 px-2 py-1 text-center whitespace-nowrap">{{ $log->formatted_date }}</td>
                    <td class="border border-slate-200 px-2 py-1 text-center">{{ $log->period_label ?? ('Hour ' . $log->period) }}</td>
                    <td class="border border-slate-200 px-2 py-1 text-center font-mono">{{ $log->sub_batch ?: 'All' }}</td>
                    <td class="border border-slate-200 px-2 py-1 font-medium text-slate-800">{{ $log->topics_covered }}</td>
                    <td class="border border-slate-200 px-2 py-1 text-center font-semibold text-emerald-700">{{ $log->present_count }}</td>
                    <td class="border border-slate-200 px-2 py-1 text-center font-semibold {{ $log->absent_count > 0 ? 'text-rose-600' : 'text-slate-400' }}">{{ $log->absent_count }}</td>
                    <td class="border border-slate-200 px-2 py-1 text-xs text-rose-700 font-mono">{{ $log->absent_display ?: 'NIL' }}</td>
                    <td class="border border-slate-200 px-2 py-1 text-center font-bold {{ $log->attendance_pct >= 75 ? 'text-emerald-700' : 'text-rose-600' }}">
                        {{ $log->attendance_pct }}%
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="border border-slate-200 px-4 py-8 text-center text-slate-400 italic">
                        No teaching and attendance logs have been recorded for this Practicum course yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Signatures -->
    <div class="grid grid-cols-3 gap-8 mt-12 pt-4 border-t border-slate-300 text-center text-xs">
        <div>
            <div class="border-b border-slate-400 h-10 mb-2"></div>
            <p class="font-bold text-slate-900">{{ $lecturerName ?? 'Faculty In-Charge' }}</p>
            <p class="text-slate-500 text-[10px]">Lecturer / Course Coordinator</p>
        </div>
        <div>
            <div class="border-b border-slate-400 h-10 mb-2"></div>
            <p class="font-bold text-slate-900">Head of Department</p>
            <p class="text-slate-500 text-[10px]">{{ $departmentName ?? 'Department' }}</p>
        </div>
        <div>
            <div class="border-b border-slate-400 h-10 mb-2"></div>
            <p class="font-bold text-slate-900">Principal</p>
            <p class="text-slate-500 text-[10px]">Carmel Polytechnic College</p>
        </div>
    </div>
</x-layouts.report-layout>
