<x-layouts.report-layout 
    :title="$title" 
    :orientation="$orientation" 
    :documentNo="'PAR-' . $batchSubject->subject_code . '-' . ($classroom->current_semester ?? 'S' . $batchSubject->semester)">

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
                    <p><span class="font-bold">Subject Type:</span> Practical / Laboratory</p>
                </div>
            </div>
        </div>

        <!-- Practical Attendance Matrix Table -->
        <div>
            <h3 class="font-bold text-sm text-slate-800 border-b border-slate-300 pb-1 mb-2">Practical Attendance Register</h3>
            <table class="w-full text-xs border border-slate-300">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="border border-slate-300 p-1.5 text-center w-10">Roll</th>
                        <th class="border border-slate-300 p-1.5 text-center w-24">Register No</th>
                        <th class="border border-slate-300 p-1.5 text-left">Student Name</th>
                        <th class="border border-slate-300 p-1.5 text-center w-16">Lab Batch</th>
                        <th class="border border-slate-300 p-1.5 text-center w-20">Conducted</th>
                        <th class="border border-slate-300 p-1.5 text-center w-20">Attended</th>
                        <th class="border border-slate-300 p-1.5 text-center w-20">Attendance %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $s)
                        @php
                            $att = $attendanceData[$s->reg_no] ?? ['conducted' => 0, 'attended' => 0, 'percentage' => 100.0];
                            $batchName = $labBatches[$s->reg_no]->lab_batch ?? 'Whole';
                        @endphp
                        <tr>
                            <td class="border border-slate-300 p-1.5 text-center">{{ $s->roll_no ?? '-' }}</td>
                            <td class="border border-slate-300 p-1.5 text-center font-mono">{{ $s->reg_no }}</td>
                            <td class="border border-slate-300 p-1.5">{{ $s->name }}</td>
                            <td class="border border-slate-300 p-1.5 text-center font-semibold">{{ $batchName }}</td>
                            <td class="border border-slate-300 p-1.5 text-center">{{ $att['conducted'] }}</td>
                            <td class="border border-slate-300 p-1.5 text-center">{{ $att['attended'] }}</td>
                            <td class="border border-slate-300 p-1.5 text-center font-bold {{ $att['percentage'] < 75 ? 'text-rose-600' : 'text-emerald-700' }}">
                                {{ $att['percentage'] }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border border-slate-300 p-3 text-center text-slate-500">No students enrolled.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Signatures Footer -->
        <div class="pt-8 grid grid-cols-3 gap-8 text-center text-xs">
            <div class="border-t border-slate-400 pt-2">
                <p class="font-bold">Lab In-Charge / Faculty</p>
                <p class="text-slate-500">Signature &amp; Date</p>
            </div>
            <div class="border-t border-slate-400 pt-2">
                <p class="font-bold">Class Tutor</p>
                <p class="text-slate-500">Signature &amp; Date</p>
            </div>
            <div class="border-t border-slate-400 pt-2">
                <p class="font-bold">Head of Department</p>
                <p class="text-slate-500">Signature &amp; Seal</p>
            </div>
        </div>
    </div>

</x-layouts.report-layout>
