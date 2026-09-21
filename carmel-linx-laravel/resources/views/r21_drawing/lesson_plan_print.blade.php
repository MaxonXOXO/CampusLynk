<x-layouts.report-layout 
    :title="'Drawing Course Lesson Plan & Syllabus Compliance — ' . $batchSubject->subject_name" 
    orientation="portrait" 
    :documentNo="'SBTE-R21-DRW-LP-' . ($batchSubject->formatted_subject_code ?? $batchSubject->subject_code)"
>
    <!-- Document Header & Meta Details -->
    <div class="mb-4 border-b border-slate-300 pb-3">
        <div class="flex justify-between items-start text-xs">
            <div class="space-y-1">
                <div><span class="font-bold text-slate-700">Course:</span> {{ $batchSubject->subject_name }} ({{ $batchSubject->formatted_subject_code ?? $batchSubject->subject_code }})</div>
                <div><span class="font-bold text-slate-700">Program / Branch:</span> {{ $classroom->department ?? $classroom->branch ?? 'Engineering' }}</div>
                <div><span class="font-bold text-slate-700">Teaching Scheme (L:T:P:R):</span> {{ $courseFile->teaching_scheme ?? '0:0:3:0' }} &bull; Credits: {{ $courseFile->credits ?? '2.0' }}</div>
            </div>
            <div class="space-y-1 text-right">
                <div><span class="font-bold text-slate-700">Semester:</span> {{ $courseFile->semester ?? 'I' }}</div>
                <div><span class="font-bold text-slate-700">Total Contact Hours:</span> {{ $courseFile->contact_hours ?? 60 }} Hours</div>
                <div><span class="font-bold text-slate-700">Curriculum Regulation:</span> R-2021 (Kerala SBTE)</div>
            </div>
        </div>
    </div>

    <!-- Lesson Plan Table -->
    <table class="w-full text-xs border-collapse border border-slate-400">
        <thead>
            <tr class="bg-slate-100 text-slate-800 font-bold text-center">
                <th class="border border-slate-400 py-1.5 px-1 w-10">Day</th>
                <th class="border border-slate-400 py-1.5 px-2 w-24">Planned Date</th>
                <th class="border border-slate-400 py-1.5 px-1 w-12">Hours</th>
                <th class="border border-slate-400 py-1.5 px-2 text-left min-w-[200px]">Topics / Drawing Exercises Planned</th>
                <th class="border border-slate-400 py-1.5 px-1 w-14">Mod</th>
                <th class="border border-slate-400 py-1.5 px-1 w-12">CO</th>
                <th class="border border-slate-400 py-1.5 px-2 w-28">Pedagogy</th>
                <th class="border border-slate-400 py-1.5 px-1.5 w-20">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lessonPlans as $idx => $plan)
                @php
                    $mod = $plan->module ?? '1';
                    $co = $plan->co_mapping ?? $plan->co_id ?? 'CO1';
                    $status = $plan->status ?? 'Completed';
                @endphp
                <tr class="text-center {{ $idx % 2 === 1 ? 'bg-slate-50/40' : 'bg-white' }}">
                    <td class="border border-slate-300 py-1 px-1 font-bold text-slate-800">{{ $plan->day_no }}</td>
                    <td class="border border-slate-300 py-1 px-2 font-mono text-slate-600">
                        {{ $plan->planned_date ? \Carbon\Carbon::parse($plan->planned_date)->format('d-m-Y') : 'Session ' . $plan->day_no }}
                    </td>
                    <td class="border border-slate-300 py-1 px-1 font-semibold text-slate-700">{{ $plan->hours ?? 3 }}</td>
                    <td class="border border-slate-300 py-1 px-2 text-left font-medium text-slate-900">{{ $plan->topics ?? $plan->topic_name }}</td>
                    <td class="border border-slate-300 py-1 px-1 text-slate-700">Mod {{ $mod }}</td>
                    <td class="border border-slate-300 py-1 px-1 font-bold text-blue-900">{{ $co }}</td>
                    <td class="border border-slate-300 py-1 px-2 text-slate-600">{{ $plan->pedagogy ?? 'Demonstration & Practice' }}</td>
                    <td class="border border-slate-300 py-1 px-1.5 font-bold {{ $status === 'Completed' ? 'text-emerald-700' : 'text-slate-600' }}">
                        {{ $status }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="border border-slate-300 py-6 text-center text-slate-400">
                        No lesson plan entries recorded.
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
