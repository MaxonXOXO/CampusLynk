<x-layouts.report-layout 
    :title="$title" 
    :orientation="$orientation" 
    :documentNo="'CF-' . $batchSubject->subject_code . '-' . ($classroom->current_semester ?? 'S' . $batchSubject->semester)">

    <div class="space-y-6">
        <!-- Header Information Card -->
        <div class="border border-slate-300 rounded-lg p-4 bg-slate-50">
            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <p><span class="font-bold">Course Title:</span> {{ $batchSubject->subject_name }}</p>
                    <p><span class="font-bold">Course Code:</span> {{ $batchSubject->subject_code }}</p>
                    <p><span class="font-bold">Department:</span> {{ $classroom->branch ?? 'Engineering' }}</p>
                </div>
                <div>
                    <p><span class="font-bold">Semester:</span> {{ $batchSubject->semester }}</p>
                    <p><span class="font-bold">Total Students:</span> {{ $students->count() }}</p>
                    <p><span class="font-bold">Academic Year:</span> {{ $courseFile->academic_year ?? date('Y') . '-' . (date('Y') + 1) }}</p>
                </div>
            </div>
        </div>

        <!-- 1. Course Outcomes -->
        <div>
            <h3 class="font-bold text-sm text-slate-800 border-b border-slate-300 pb-1 mb-2">1. Course Outcomes (COs)</h3>
            <table class="w-full text-xs border border-slate-300">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="border border-slate-300 p-2 text-left w-16">CO ID</th>
                        <th class="border border-slate-300 p-2 text-left">Course Outcome Statement</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $co)
                        <tr>
                            <td class="border border-slate-300 p-2 font-bold">{{ $co }}</td>
                            <td class="border border-slate-300 p-2">Demonstrate proficiency and applied knowledge of {{ $batchSubject->subject_name }} under {{ $co }} domain.</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 2. Lesson Plan Summary -->
        <div>
            <h3 class="font-bold text-sm text-slate-800 border-b border-slate-300 pb-1 mb-2">2. Instructional Schedule &amp; Lesson Plan ({{ $lessonPlans->count() }} Sessions)</h3>
            <table class="w-full text-xs border border-slate-300">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="border border-slate-300 p-1.5 text-center w-12">No.</th>
                        <th class="border border-slate-300 p-1.5 text-left">Topic / Module Content</th>
                        <th class="border border-slate-300 p-1.5 text-center w-16">CO</th>
                        <th class="border border-slate-300 p-1.5 text-center w-24">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lessonPlans->take(15) as $plan)
                        <tr>
                            <td class="border border-slate-300 p-1.5 text-center">{{ $plan->day_no ?? $loop->iteration }}</td>
                            <td class="border border-slate-300 p-1.5">{{ $plan->topic_content }}</td>
                            <td class="border border-slate-300 p-1.5 text-center font-semibold">{{ $plan->co_id ?? 'CO1' }}</td>
                            <td class="border border-slate-300 p-1.5 text-center">{{ $plan->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="border border-slate-300 p-2 text-center text-slate-500">No lesson plan entries recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 3. Student Enrollment List -->
        <div>
            <h3 class="font-bold text-sm text-slate-800 border-b border-slate-300 pb-1 mb-2">3. Enrolled Student Roster ({{ $students->count() }} Students)</h3>
            <table class="w-full text-xs border border-slate-300">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="border border-slate-300 p-1.5 text-center w-12">Roll</th>
                        <th class="border border-slate-300 p-1.5 text-center w-28">Register No</th>
                        <th class="border border-slate-300 p-1.5 text-left">Student Name</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $s)
                        <tr>
                            <td class="border border-slate-300 p-1.5 text-center">{{ $s->roll_no ?? '-' }}</td>
                            <td class="border border-slate-300 p-1.5 text-center font-mono">{{ $s->reg_no }}</td>
                            <td class="border border-slate-300 p-1.5">{{ $s->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border border-slate-300 p-2 text-center text-slate-500">No approved students enrolled.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Signatures Footer -->
        <div class="pt-8 grid grid-cols-3 gap-8 text-center text-xs">
            <div class="border-t border-slate-400 pt-2">
                <p class="font-bold">Course Faculty</p>
                <p class="text-slate-500">Signature &amp; Date</p>
            </div>
            <div class="border-t border-slate-400 pt-2">
                <p class="font-bold">Class Tutor / Advisor</p>
                <p class="text-slate-500">Signature &amp; Date</p>
            </div>
            <div class="border-t border-slate-400 pt-2">
                <p class="font-bold">Head of Department</p>
                <p class="text-slate-500">Signature &amp; Seal</p>
            </div>
        </div>
    </div>

</x-layouts.report-layout>
