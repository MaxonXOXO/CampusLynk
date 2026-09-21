<x-layouts.report-layout 
    title="List of Practical Experiments — {{ $batchSubject->subject_name }}"
    documentNo="DOC-R26-P-EXP-LIST"
    orientation="portrait"
>
    <!-- Header -->
    <div class="text-center border-b-2 border-slate-800 pb-3 mb-4">
        <h1 class="text-xl font-bold uppercase tracking-wide text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
        <h2 class="text-sm font-semibold text-slate-700 mt-1">List of Practical Experiments & Competency Roster — Revision 2026 Practicum</h2>
        <p class="text-xs text-slate-500 mt-0.5">Affiliated to SBTE Kerala | Approved by AICTE, New Delhi</p>
    </div>

    <!-- Metadata Grid -->
    <div class="grid grid-cols-3 gap-3 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs mb-4">
        <div>
            <span class="text-slate-500 block font-medium">Course Title:</span>
            <span class="font-bold text-slate-900">{{ $batchSubject->subject_name }}</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Course Code:</span>
            <span class="font-bold text-slate-900 font-mono">{{ $batchSubject->subject_code }}</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Teaching Scheme:</span>
            <span class="font-bold text-slate-900">{{ $practicumCourseFile->teaching_scheme ?? '3:0:3:0' }} &bull; 4.5 Credits</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Department:</span>
            <span class="font-bold text-slate-900">{{ $departmentName ?? 'Engineering' }}</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Batch / Semester:</span>
            <span class="font-bold text-slate-900">{{ $batchName ?? 'Batch' }} &bull; Sem {{ $classroom->current_semester ?? $batchSubject->semester ?? '1' }}</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Faculty In-Charge:</span>
            <span class="font-bold text-slate-900">{{ $lecturerName ?? 'Faculty' }}</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Total Experiments:</span>
            <span class="font-bold text-slate-900">{{ count($experiments) }} Prescribed</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Allocated Practical Hours:</span>
            <span class="font-bold text-blue-700">{{ $totalPracticalHours }} Hours (Lab Component)</span>
        </div>
        <div>
            <span class="text-slate-500 block font-medium">Generated On:</span>
            <span class="font-bold text-slate-900">{{ date('d M Y, h:i A') }}</span>
        </div>
    </div>

    <!-- Experiments Table -->
    <div class="overflow-x-auto mb-6">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-800 text-white font-semibold">
                    <th class="border border-slate-700 px-3 py-2 text-center w-12">#</th>
                    <th class="border border-slate-700 px-3 py-2 w-24 text-center">Exp Code</th>
                    <th class="border border-slate-700 px-3 py-2 w-24 text-center">Session</th>
                    <th class="border border-slate-700 px-3 py-2">Practical Experiment Title & Core Skill Description</th>
                    <th class="border border-slate-700 px-3 py-2 text-center w-20">Mapped CO</th>
                    <th class="border border-slate-700 px-3 py-2 text-center w-20">Hours</th>
                </tr>
            </thead>
            <tbody>
                @forelse($experiments as $idx => $exp)
                <tr class="{{ $idx % 2 === 0 ? 'bg-white' : 'bg-slate-50' }} hover:bg-slate-100/80">
                    <td class="border border-slate-200 px-3 py-2 text-center font-medium text-slate-600">{{ $idx + 1 }}</td>
                    <td class="border border-slate-200 px-3 py-2 text-center font-mono font-semibold text-blue-800">
                        {{ $exp['code'] ?? ($exp['experiment_no'] ?? ('EXP-' . sprintf('%02d', $idx + 1))) }}
                    </td>
                    <td class="border border-slate-200 px-3 py-2 text-center text-slate-700">
                        {{ $exp['session_code'] ?? ('Sess ' . ($idx + 1)) }}
                    </td>
                    <td class="border border-slate-200 px-3 py-2 font-medium text-slate-900">
                        {{ $exp['title'] ?? 'Practical Experiment' }}
                    </td>
                    <td class="border border-slate-200 px-3 py-2 text-center">
                        <span class="inline-block px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-bold text-[10px]">
                            {{ $exp['co_id'] ?? 'CO1' }}
                        </span>
                    </td>
                    <td class="border border-slate-200 px-3 py-2 text-center font-bold text-slate-800">
                        {{ $exp['hours'] ?? 3 }} Hrs
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="border border-slate-200 px-4 py-8 text-center text-slate-400 italic">
                        No experiments roster has been defined or uploaded in the Practicum Course File.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-slate-100 font-bold text-slate-900">
                    <td colspan="5" class="border border-slate-300 px-3 py-2 text-right">Total Practical Hours Allocated:</td>
                    <td class="border border-slate-300 px-3 py-2 text-center text-blue-800">{{ $totalPracticalHours }} Hrs</td>
                </tr>
            </tfoot>
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
            <p class="font-bold text-slate-900">{{ $hod->name ?? 'Head of Department' }}</p>
            <p class="text-slate-500 text-[10px]">{{ $departmentName ?? 'Department' }}</p>
        </div>
        <div>
            <div class="border-b border-slate-400 h-10 mb-2"></div>
            <p class="font-bold text-slate-900">Principal</p>
            <p class="text-slate-500 text-[10px]">Carmel Polytechnic College</p>
        </div>
    </div>
</x-layouts.report-layout>
