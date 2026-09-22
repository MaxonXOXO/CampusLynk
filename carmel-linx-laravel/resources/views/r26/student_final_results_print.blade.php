<x-layouts.report-layout 
    title="Consolidated Theory ESE & Final Results - {{ $batchSubject->subject_name }}" 
    orientation="portrait" 
    documentNo="{{ $batchSubject->subject_code }}/RESULTS">

    <style>
        .header-meta { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 11px; }
        .header-meta td { padding: 4px 8px; border: 1px solid #cbd5e1; }
        .header-meta td.label { font-weight: bold; background-color: #f8fafc; width: 18%; }
        .grading-scale-table { width: 100%; border-collapse: collapse; text-align: center; font-size: 10px; }
        .grading-scale-table th, .grading-scale-table td { border: 1px solid #cbd5e1; padding: 3px 5px; }
        .grading-scale-table th { background-color: #f1f5f9; font-weight: bold; }
        .marks-table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 15px; font-size: 11px; }
        .marks-table th, .marks-table td { border: 1px solid #1e293b; padding: 5px 6px; }
        .marks-table th { font-weight: bold; text-align: center; background-color: #f1f5f9; text-transform: uppercase; font-size: 10px; }
        .stats-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .stats-table td { padding: 4px 6px; }
    </style>

    @include('reports.partials.header', [
        'title' => 'CONSOLIDATED THEORY ESE & FINAL RESULTS',
        'subtitle' => 'Revision 2026 Scheme · Continuous Assessment + End Semester Examination',
        'department' => getFullBranchName($classroom->branch ?? $classroom->department ?? ''),
        'academicYear' => $classroom->batch_year ?? date('Y'),
        'semester' => "Semester " . ($classroom->current_semester ?? 'N/A'),
        'documentNo' => "{$batchSubject->subject_code}/RESULTS"
    ])

    <table class="header-meta">
        <tr>
            <td class="label">Course Title:</td>
            <td class="font-bold">{{ $batchSubject->subject_name }}</td>
            <td class="label">Course Code:</td>
            <td class="font-mono font-bold">{{ $batchSubject->subject_code }}</td>
        </tr>
        <tr>
            <td class="label">Class / Batch:</td>
            <td>{{ $classroom->classroom_name ?? $batchSubject->classroom_id }}</td>
            <td class="label">Semester / Scheme:</td>
            <td>Semester {{ $classroom->current_semester ?? 'N/A' }} (Rev 2026)</td>
        </tr>
        <tr>
            <td class="label">Faculty In-Charge:</td>
            <td>{{ Session::get('userName') ?? 'Faculty In-Charge' }}</td>
            <td class="label">Date of Report:</td>
            <td class="font-bold">{{ date('d/m/Y') }}</td>
        </tr>
    </table>

    {{-- Official Revision 2026 Grade Scale Legend --}}
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 mb-4">
        <div class="text-[11px] font-bold uppercase text-slate-800 border-b border-slate-200 pb-1 mb-2">
            Official Revision 2026 Grading System Standard
        </div>
        <table class="grading-scale-table">
            <thead>
                <tr>
                    <th>Grade</th>
                    <th>S</th>
                    <th>A</th>
                    <th>B</th>
                    <th>C</th>
                    <th>D</th>
                    <th>E</th>
                    <th>F</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-bold">Percentage</td>
                    <td>≥ 90%</td>
                    <td>80 – 89%</td>
                    <td>70 – 79%</td>
                    <td>60 – 69%</td>
                    <td>50 – 59%</td>
                    <td>40 – 49%</td>
                    <td>&lt; 40%</td>
                </tr>
                <tr>
                    <td class="font-bold">Performance</td>
                    <td>Outstanding</td>
                    <td>Excellent</td>
                    <td>Very Good</td>
                    <td>Good</td>
                    <td>Average</td>
                    <td>Satisfactory</td>
                    <td>Reappearance</td>
                </tr>
                <tr>
                    <td class="font-bold">Grade Points</td>
                    <td>10</td>
                    <td>9</td>
                    <td>8</td>
                    <td>7</td>
                    <td>6</td>
                    <td>5</td>
                    <td>0</td>
                </tr>
            </tbody>
        </table>
    </div>

    <table class="marks-table">
        <thead>
            <tr>
                <th style="width: 5%;">Sl</th>
                <th style="width: 6%;">Roll</th>
                <th style="width: 14%;">SBTE Reg No</th>
                <th>Student Name</th>
                <th style="width: 11%;">Attendance</th>
                <th style="width: 10%;">CIA (40M)</th>
                <th style="width: 10%;">ESE (60M)</th>
                <th style="width: 10%;">Total (100M)</th>
                <th style="width: 8%;">Grade</th>
                <th style="width: 14%;">Result</th>
            </tr>
        </thead>
        <tbody>
            @php
                $slNo = 1;
                $passCount = 0;
                $failCount = 0;
                $grades = ['S' => 0, 'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0];
            @endphp
            @forelse($studentCiaData as $sc)
                @php
                    $tot = $sc['total_cia'] + $sc['ese_marks'];
                    
                    if ($tot >= 90) { $grade = 'S'; }
                    elseif ($tot >= 80) { $grade = 'A'; }
                    elseif ($tot >= 70) { $grade = 'B'; }
                    elseif ($tot >= 60) { $grade = 'C'; }
                    elseif ($tot >= 50) { $grade = 'D'; }
                    elseif ($tot >= 40) { $grade = 'E'; }
                    else { $grade = 'F'; }

                    if ($tot >= 40 && $sc['ese_marks'] >= 24) {
                        $remark = 'PASSED';
                        $passCount++;
                    } else {
                        $remark = 'REAPPEARANCE';
                        $failCount++;
                        $grade = 'F';
                    }
                    $grades[$grade]++;
                @endphp
                <tr>
                    <td class="text-center font-mono">{{ $slNo++ }}</td>
                    <td class="text-center font-mono font-bold">{{ $sc['roll_no'] ?: '—' }}</td>
                    <td class="font-mono text-center">{{ $sc['sbte_reg_no'] ?: ($sc['reg_no'] ?? 'Unassigned') }}</td>
                    <td class="font-bold">{{ $sc['name'] }}</td>
                    <td class="text-center font-mono">{{ $sc['attendance_percent'] }}%</td>
                    <td class="text-center font-mono">{{ number_format($sc['total_cia'], 1) }}</td>
                    <td class="text-center font-mono">{{ number_format($sc['ese_marks'], 1) }}</td>
                    <td class="text-center font-mono font-bold">{{ number_format($tot, 1) }}</td>
                    <td class="text-center font-bold">{{ $grade }}</td>
                    <td class="text-center font-bold text-xs {{ $remark === 'PASSED' ? 'text-emerald-700' : 'text-rose-700' }}">
                        {{ $remark }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-slate-500 italic">No student result records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $totalStudents = $passCount + $failCount;
        $passRate = $totalStudents > 0 ? ($passCount / $totalStudents) * 100 : 0.0;
    @endphp

    <div class="grid grid-cols-2 gap-4 mt-4 break-inside-avoid">
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
            <div class="text-xs font-bold uppercase text-slate-800 border-b border-slate-200 pb-1 mb-2">Overall Performance Summary</div>
            <table class="stats-table">
                <tr>
                    <td class="font-bold">Total Students Evaluated:</td>
                    <td class="text-center font-mono font-bold">{{ $totalStudents }}</td>
                </tr>
                <tr>
                    <td class="font-bold text-emerald-700">Passed:</td>
                    <td class="text-center font-mono font-bold text-emerald-700">{{ $passCount }}</td>
                </tr>
                <tr>
                    <td class="font-bold text-rose-700">Reappearance Required:</td>
                    <td class="text-center font-mono font-bold text-rose-700">{{ $failCount }}</td>
                </tr>
                <tr class="border-t border-slate-300">
                    <td class="font-bold">Class Pass Percentage:</td>
                    <td class="text-center font-mono font-bold text-sm">{{ number_format($passRate, 1) }}%</td>
                </tr>
            </table>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
            <div class="text-xs font-bold uppercase text-slate-800 border-b border-slate-200 pb-1 mb-2">Grade Distribution (Revision 2026)</div>
            <table class="stats-table text-center">
                <thead>
                    <tr class="border-b border-slate-200 bg-white">
                        <th class="p-1 font-bold">S</th>
                        <th class="p-1 font-bold">A</th>
                        <th class="p-1 font-bold">B</th>
                        <th class="p-1 font-bold">C</th>
                        <th class="p-1 font-bold">D</th>
                        <th class="p-1 font-bold">E</th>
                        <th class="p-1 font-bold text-rose-700">F</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-mono font-bold p-1">{{ $grades['S'] }}</td>
                        <td class="font-mono font-bold p-1">{{ $grades['A'] }}</td>
                        <td class="font-mono font-bold p-1">{{ $grades['B'] }}</td>
                        <td class="font-mono font-bold p-1">{{ $grades['C'] }}</td>
                        <td class="font-mono font-bold p-1">{{ $grades['D'] }}</td>
                        <td class="font-mono font-bold p-1">{{ $grades['E'] }}</td>
                        <td class="font-mono font-bold p-1 text-rose-700">{{ $grades['F'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @include('reports.partials.signatures', [
        'facultyLabel' => 'Course Faculty In-Charge',
        'hodLabel' => 'Head of Department',
        'principalLabel' => 'Principal'
    ])
</x-layouts.report-layout>
