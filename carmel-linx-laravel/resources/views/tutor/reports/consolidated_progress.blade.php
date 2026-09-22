<x-layouts.report-layout title="Consolidated Progress Report — {{ $classroom['id'] }}">
    {{-- Institutional Document Header --}}
    <div class="text-center pb-4 border-b-2 border-slate-900 mb-6">
        <h1 class="text-xl font-bold uppercase tracking-wider text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
        <p class="text-xs text-slate-600">Department of {{ $classroom['branch_name'] }}</p>
        <h2 class="text-sm font-bold uppercase mt-2 text-slate-800 underline">Consolidated Student Progress & Academic Performance Broadsheet (Semester {{ $classroom['semester'] }})</h2>
        <div class="mt-2 flex justify-center gap-6 text-xs text-slate-700 font-medium">
            <span><strong>Cohort ID:</strong> {{ $classroom['id'] }}</span>
            <span><strong>Academic Batch:</strong> {{ $classroom['batch'] }}</span>
            <span><strong>Class Tutor:</strong> {{ $classroom['tutor_name'] }}</span>
            <span><strong>Date Generated:</strong> {{ $classroom['date'] }}</span>
        </div>
    </div>

    {{-- Consolidated Progress Broadsheet Table --}}
    <div class="mb-6">
        <table class="w-full border-collapse border border-slate-400 text-[11px]">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold">
                    <th class="border border-slate-400 p-1 text-center w-8">Roll</th>
                    <th class="border border-slate-400 p-1 text-left">Reg No</th>
                    <th class="border border-slate-400 p-1 text-left">Student Name</th>
                    @foreach($subjects as $subj)
                        <th class="border border-slate-400 p-1 text-center font-mono">
                            {{ $subj->subject_code }}
                        </th>
                    @endforeach
                    <th class="border border-slate-400 p-1 text-center">Grand Total</th>
                    <th class="border border-slate-400 p-1 text-center">Rank</th>
                    <th class="border border-slate-400 p-1 text-center">Attd %</th>
                    <th class="border border-slate-400 p-1 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $st)
                    <tr>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ $st['roll_no'] ?? '-' }}</td>
                        <td class="border border-slate-400 p-1 font-mono text-[10px]">{{ $st['sbte_reg_no'] }}</td>
                        <td class="border border-slate-400 p-1 font-medium truncate max-w-[140px]">{{ $st['name'] }}</td>
                        @foreach($subjects as $subj)
                            @php
                                $sData = $st['subjects'][$subj->id] ?? null;
                            @endphp
                            <td class="border border-slate-400 p-1 text-center font-mono text-[10px]">
                                {{ $sData && $sData['subject_total'] !== null ? number_format($sData['subject_total'], 1) : '-' }}
                            </td>
                        @endforeach
                        <td class="border border-slate-400 p-1 text-center font-mono font-bold">{{ number_format($st['grand_total_marks'], 1) }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-bold text-indigo-800">
                            {{ $st['class_rank'] ? '#' . $st['class_rank'] : '-' }}
                        </td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-semibold {{ $st['overall_attendance'] < 75.0 ? 'text-rose-700' : '' }}">
                            {{ number_format($st['overall_attendance'], 1) }}%
                        </td>
                        <td class="border border-slate-400 p-1 text-center font-bold text-[10px] {{ $st['status'] === 'Eligible' ? 'text-emerald-700' : ($st['status'] === 'Condonation' ? 'text-amber-700' : 'text-rose-700') }}">
                            {{ $st['status'] }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Performance Summary Metrics --}}
    <div class="grid grid-cols-4 gap-4 p-3 border border-slate-300 rounded bg-slate-50 text-xs mb-8">
        <div><strong>Total Students:</strong> {{ $summary['total_students'] }}</div>
        <div><strong>Average Marks:</strong> {{ number_format($summary['average_marks'], 1) }}</div>
        <div><strong>Average Attendance:</strong> {{ number_format($summary['average_attendance'], 1) }}%</div>
        <div><strong>Class Topper:</strong> {{ $summary['topper']['name'] ?? 'N/A' }} ({{ $summary['topper']['marks'] ?? 0 }} M)</div>
    </div>

    {{-- Statutory Signatures Block --}}
    <div class="mt-8 pt-6 grid grid-cols-3 gap-8 text-center text-xs border-t border-slate-300">
        <div>
            <div class="h-12"></div>
            <p class="font-bold uppercase text-slate-800">Class Tutor / Advisor</p>
            <p class="text-[10px] text-slate-500">{{ $classroom['tutor_name'] }}</p>
        </div>
        <div>
            <div class="h-12"></div>
            <p class="font-bold uppercase text-slate-800">Head of Department</p>
            <p class="text-[10px] text-slate-500">Department of {{ $classroom['branch_name'] }}</p>
        </div>
        <div>
            <div class="h-12"></div>
            <p class="font-bold uppercase text-slate-800">Principal</p>
            <p class="text-[10px] text-slate-500">Carmel Polytechnic College</p>
        </div>
    </div>
</x-layouts.report-layout>
