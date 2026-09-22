<x-layouts.report-layout title="Student Progress Card — {{ $classroom['id'] }}">
    @foreach($students as $st)
        <div class="{{ !$loop->last ? 'page-break-after' : '' }} p-4 mb-8">
            {{-- Institutional Document Header --}}
            <div class="text-center pb-3 border-b-2 border-slate-900 mb-4">
                <h1 class="text-lg font-bold uppercase tracking-wider text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
                <p class="text-xs text-slate-600">Department of {{ $classroom['branch_name'] }}</p>
                <h2 class="text-sm font-bold uppercase mt-1 text-slate-800 underline">Student Continuous Assessment & Progress Report Card</h2>
                <p class="text-[11px] text-slate-500">Academic Batch: {{ $classroom['batch'] }} | Semester: {{ $classroom['semester'] }}</p>
            </div>

            {{-- Student Profile Box --}}
            <div class="grid grid-cols-2 gap-4 rounded border border-slate-300 p-3 text-xs mb-4 bg-slate-50">
                <div class="space-y-1">
                    <div><strong>Student Name:</strong> <span class="uppercase font-bold">{{ $st['name'] }}</span></div>
                    <div><strong>Roll Number:</strong> <span class="font-mono">{{ $st['roll_no'] ?? 'N/A' }}</span></div>
                    <div><strong>SBTE Register No:</strong> <span class="font-mono font-semibold">{{ $st['sbte_reg_no'] }}</span></div>
                </div>
                <div class="space-y-1 text-right">
                    <div><strong>Classroom / Cohort:</strong> <span class="font-mono">{{ $classroom['id'] }}</span></div>
                    <div><strong>Class Tutor:</strong> {{ $classroom['tutor_name'] }}</div>
                    <div><strong>Date Issued:</strong> {{ $classroom['date'] }}</div>
                </div>
            </div>

            {{-- Marks & Subject Performance Table --}}
            <div class="mb-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-2">Subject Performance & Series Test Marks</h3>
                <table class="w-full border-collapse border border-slate-400 text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800 font-bold">
                            <th class="border border-slate-400 p-1.5 text-left">Subject Code & Title</th>
                            <th class="border border-slate-400 p-1.5 text-center w-12">Type</th>
                            <th class="border border-slate-400 p-1.5 text-center w-12 font-mono">CO1</th>
                            <th class="border border-slate-400 p-1.5 text-center w-12 font-mono">CO2</th>
                            <th class="border border-slate-400 p-1.5 text-center w-12 font-mono">CO3</th>
                            <th class="border border-slate-400 p-1.5 text-center w-12 font-mono">CO4</th>
                            <th class="border border-slate-400 p-1.5 text-center w-16">Total</th>
                            <th class="border border-slate-400 p-1.5 text-center w-16">Attd %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subj)
                            @php
                                $sData = $st['subjects'][$subj->id] ?? null;
                                $coMarks = $sData['co_marks'] ?? ['CO1'=>null,'CO2'=>null,'CO3'=>null,'CO4'=>null];
                                $sAtt = $sData['attendance'] ?? null;
                            @endphp
                            <tr>
                                <td class="border border-slate-400 p-1.5 font-medium">
                                    <span class="font-mono text-slate-600">{{ $subj->subject_code }}</span> — {{ $subj->subject_name }}
                                </td>
                                <td class="border border-slate-400 p-1.5 text-center text-[10px] text-slate-600">{{ $subj->subject_type }}</td>
                                <td class="border border-slate-400 p-1.5 text-center font-mono">{{ $coMarks['CO1'] !== null ? number_format($coMarks['CO1'], 1) : '-' }}</td>
                                <td class="border border-slate-400 p-1.5 text-center font-mono">{{ $coMarks['CO2'] !== null ? number_format($coMarks['CO2'], 1) : '-' }}</td>
                                <td class="border border-slate-400 p-1.5 text-center font-mono">{{ $coMarks['CO3'] !== null ? number_format($coMarks['CO3'], 1) : '-' }}</td>
                                <td class="border border-slate-400 p-1.5 text-center font-mono">{{ $coMarks['CO4'] !== null ? number_format($coMarks['CO4'], 1) : '-' }}</td>
                                <td class="border border-slate-400 p-1.5 text-center font-mono font-bold">{{ $sData['subject_total'] !== null ? number_format($sData['subject_total'], 1) : '-' }}</td>
                                <td class="border border-slate-400 p-1.5 text-center font-mono {{ ($sAtt['percentage'] ?? 100) < 75 ? 'text-rose-700 font-bold' : '' }}">
                                    {{ $sAtt ? number_format($sAtt['percentage'], 0) . '%' : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Overall Performance Summary --}}
            <div class="grid grid-cols-4 gap-3 p-3 border border-slate-300 rounded bg-slate-50 text-xs mb-6">
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase">Grand Total Marks</span>
                    <span class="font-mono font-bold text-sm text-slate-900">{{ number_format($st['grand_total_marks'], 1) }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase">Class Rank</span>
                    <span class="font-mono font-bold text-sm text-indigo-700">{{ $st['class_rank'] ? '#' . $st['class_rank'] : 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase">Overall Attendance</span>
                    <span class="font-mono font-bold text-sm {{ $st['overall_attendance'] < 75 ? 'text-rose-700' : 'text-slate-900' }}">
                        {{ number_format($st['overall_attendance'], 1) }}%
                    </span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase">Eligibility Status</span>
                    <span class="font-bold text-sm {{ $st['status'] === 'Eligible' ? 'text-emerald-700' : ($st['status'] === 'Condonation' ? 'text-amber-700' : 'text-rose-700') }}">
                        {{ $st['status'] }}
                    </span>
                </div>
            </div>

            {{-- Parent-Teacher Remarks & Signatures --}}
            <div class="border border-slate-300 rounded p-3 text-xs mb-6 space-y-2">
                <strong>Tutor's Remarks:</strong>
                <p class="text-slate-600 italic">
                    {{ $st['overall_attendance'] < 75 ? 'Attendance shortage flagged. Immediate improvement required to qualify for Board Exams.' : ($st['class_rank'] && $st['class_rank'] <= 10 ? 'Excellent academic performance and good conduct. Keep it up!' : 'Satisfactory progress. Encourage continuous revision and regular submission of assignments.') }}
                </p>
            </div>

            <div class="mt-8 pt-4 grid grid-cols-3 gap-6 text-center text-xs">
                <div>
                    <div class="h-10"></div>
                    <p class="font-bold uppercase text-slate-800">Parent / Guardian Signature</p>
                    <p class="text-[10px] text-slate-500">Mobile: {{ $st['guardian_mobile'] ?: ($st['phone'] ?: 'N/A') }}</p>
                </div>
                <div>
                    <div class="h-10"></div>
                    <p class="font-bold uppercase text-slate-800">Class Tutor / Advisor</p>
                    <p class="text-[10px] text-slate-500">{{ $classroom['tutor_name'] }}</p>
                </div>
                <div>
                    <div class="h-10"></div>
                    <p class="font-bold uppercase text-slate-800">Principal</p>
                    <p class="text-[10px] text-slate-500">Carmel Polytechnic College</p>
                </div>
            </div>
        </div>
    @endforeach
</x-layouts.report-layout>
