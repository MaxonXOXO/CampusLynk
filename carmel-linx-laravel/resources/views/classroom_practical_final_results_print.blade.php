<x-layouts.report-layout 
    title="Practical ESE &amp; Final Results — {{ $subject->subject_name }}" 
    orientation="portrait" 
    :documentNo="'SBTE-R21-' . $subject->subject_code . '-ESE'">

    <!-- Institutional Header -->
    <div class="text-center pb-3 border-b-2 border-slate-900 mb-4">
        <h1 class="text-lg font-bold uppercase tracking-wider text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
        <p class="text-xs text-slate-600 font-medium">Department of {{ $fullDepartment }}</p>
        <h2 class="text-sm font-bold uppercase mt-1 text-slate-800 underline">Practical End-Semester Examination &amp; Consolidated Final Results (Revision 2021)</h2>
    </div>

    <!-- Meta Information Grid -->
    <div class="grid grid-cols-2 gap-4 rounded border border-slate-300 p-3 text-xs mb-4 bg-slate-50">
        <div class="space-y-1">
            <div><strong>Course Title:</strong> <span class="font-semibold text-slate-900">{{ $subject->subject_name }}</span></div>
            <div><strong>Course Code:</strong> <span class="font-mono font-bold text-slate-900">{{ $subject->subject_code }}</span></div>
            <div><strong>Class / Batch:</strong> <span class="font-semibold text-slate-900">{{ $cleanedBatch }}</span></div>
        </div>
        <div class="space-y-1 text-right">
            <div><strong>Semester / Scheme:</strong> <span class="font-semibold text-slate-900">Semester {{ $subject->semester }} (Revision 2021)</span></div>
            <div><strong>Mark Scheme:</strong> <span class="font-semibold text-slate-900">CIA: 75M + ESE: 50M = 125M</span></div>
            <div><strong>Date of Report:</strong> <span class="font-mono">{{ date('d-m-Y') }}</span></div>
        </div>
    </div>

    <!-- Official SBTE Revision 2021 Grading Scale Standard -->
    <div class="mb-4 p-2.5 rounded border border-slate-300 bg-slate-50 text-[10px]">
        <div class="font-bold uppercase tracking-wider text-slate-700 mb-1.5 border-b border-slate-200 pb-1">
            Official SBTE Revision 2021 Grading Scale Standard
        </div>
        <table class="w-full text-center border-collapse border border-slate-300">
            <thead>
                <tr class="bg-slate-100 font-bold text-slate-700">
                    <th class="border border-slate-300 p-1">Grade</th>
                    <th class="border border-slate-300 p-1">S</th>
                    <th class="border border-slate-300 p-1">A+</th>
                    <th class="border border-slate-300 p-1">A</th>
                    <th class="border border-slate-300 p-1">B+</th>
                    <th class="border border-slate-300 p-1">B</th>
                    <th class="border border-slate-300 p-1">C+</th>
                    <th class="border border-slate-300 p-1">C</th>
                    <th class="border border-slate-300 p-1">D+</th>
                    <th class="border border-slate-300 p-1">D</th>
                    <th class="border border-slate-300 p-1">P</th>
                    <th class="border border-slate-300 p-1 text-rose-700">F</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-slate-600">
                    <td class="border border-slate-300 p-1 font-bold">Range</td>
                    <td class="border border-slate-300 p-1">&ge;90%</td>
                    <td class="border border-slate-300 p-1">85-89%</td>
                    <td class="border border-slate-300 p-1">80-84%</td>
                    <td class="border border-slate-300 p-1">75-79%</td>
                    <td class="border border-slate-300 p-1">70-74%</td>
                    <td class="border border-slate-300 p-1">65-69%</td>
                    <td class="border border-slate-300 p-1">60-64%</td>
                    <td class="border border-slate-300 p-1">55-59%</td>
                    <td class="border border-slate-300 p-1">50-54%</td>
                    <td class="border border-slate-300 p-1">40-49%</td>
                    <td class="border border-slate-300 p-1 text-rose-700">&lt;40%</td>
                </tr>
                <tr class="text-slate-600 font-mono">
                    <td class="border border-slate-300 p-1 font-sans font-bold">Points</td>
                    <td class="border border-slate-300 p-1">10</td>
                    <td class="border border-slate-300 p-1">9</td>
                    <td class="border border-slate-300 p-1">8.5</td>
                    <td class="border border-slate-300 p-1">8</td>
                    <td class="border border-slate-300 p-1">7.5</td>
                    <td class="border border-slate-300 p-1">7</td>
                    <td class="border border-slate-300 p-1">6.5</td>
                    <td class="border border-slate-300 p-1">6</td>
                    <td class="border border-slate-300 p-1">5.5</td>
                    <td class="border border-slate-300 p-1">4</td>
                    <td class="border border-slate-300 p-1 text-rose-700">0</td>
                </tr>
            </tbody>
        </table>
    </div>

    @php
        $calcGrade = function($marks, $maxMarks) {
            if ($marks === null || $marks === '' || $marks === '-') return '-';
            if (!is_numeric($marks)) return strtoupper(trim($marks));
            $pct = ($marks / $maxMarks) * 100;
            if ($pct >= 90) return 'S';
            if ($pct >= 85) return 'A+';
            if ($pct >= 80) return 'A';
            if ($pct >= 75) return 'B+';
            if ($pct >= 70) return 'B';
            if ($pct >= 65) return 'C+';
            if ($pct >= 60) return 'C';
            if ($pct >= 55) return 'D+';
            if ($pct >= 50) return 'D';
            if ($pct >= 40) return 'P';
            return 'F';
        };

        $gradeToNumeric = function($grade, $maxMarks) {
            if ($grade === null || $grade === '' || $grade === '-') return null;
            if (is_numeric($grade)) return (float)$grade;
            $g = strtoupper(trim($grade));
            switch($g) {
                case 'S': return $maxMarks * 0.95;
                case 'A+': return $maxMarks * 0.875;
                case 'A': return $maxMarks * 0.825;
                case 'B+': return $maxMarks * 0.775;
                case 'B': return $maxMarks * 0.725;
                case 'C+': return $maxMarks * 0.675;
                case 'C': return $maxMarks * 0.625;
                case 'D+': return $maxMarks * 0.575;
                case 'D': return $maxMarks * 0.525;
                case 'P': return $maxMarks * 0.45;
                case 'F': return 0.0;
                default: return null;
            }
        };

        $passCount = 0;
        $failCount = 0;
        $gradesDist = ['S' => 0, 'A+' => 0, 'A' => 0, 'B+' => 0, 'B' => 0, 'C+' => 0, 'C' => 0, 'D+' => 0, 'D' => 0, 'P' => 0, 'F' => 0];
    @endphp

    <!-- Results Table -->
    <div class="mb-6 overflow-hidden">
        <table class="w-full border-collapse border border-slate-400 text-xs">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold uppercase text-[9.5px]">
                    <th class="border border-slate-400 p-1 text-center w-8">Sl</th>
                    <th class="border border-slate-400 p-1 text-center w-10">Roll</th>
                    <th class="border border-slate-400 p-1 text-center w-28">PRN (SBTE)</th>
                    <th class="border border-slate-400 p-1 text-left">Student Name</th>
                    <th class="border border-slate-400 p-1 text-center w-16">Attn %</th>
                    <th class="border border-slate-400 p-1 text-center w-20 bg-teal-50">Final CIA<br>(75M)</th>
                    <th class="border border-slate-400 p-1 text-center w-24 bg-blue-50">Practical ESE<br>(50M)</th>
                    <th class="border border-slate-400 p-1 text-center w-20 bg-purple-50">Total<br>(125M)</th>
                    <th class="border border-slate-400 p-1 text-center w-14">Grade</th>
                    <th class="border border-slate-400 p-1 text-center w-24">Result</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $idx => $student)
                    @php
                        $boardVal = $student->board_exam_marks !== null ? $student->board_exam_marks : null;
                        $boardNum = $gradeToNumeric($boardVal, 50);
                        $eseDisplay = '-';
                        $totalDisplay = '-';
                        $finalGrade = '-';
                        $resultStatus = '-';

                        if ($boardVal !== null && $boardVal !== '') {
                            if (is_numeric($boardVal)) {
                                $eseNum = (float)$boardVal;
                                $gLet = $calcGrade($eseNum, 50);
                                $eseDisplay = number_format($eseNum, 1) . " ({$gLet})";
                                $tot = $student->total_internal + $eseNum;
                                $totalDisplay = number_format($tot, 1);
                                $finalGrade = $calcGrade($tot, 125);
                                if ($tot >= 50 && $eseNum >= 20) {
                                    $resultStatus = 'PASSED';
                                    $passCount++;
                                } else {
                                    $resultStatus = 'REAPPEAR';
                                    $failCount++;
                                    $finalGrade = 'F';
                                }
                            } else {
                                $gLet = strtoupper(trim($boardVal));
                                $eseDisplay = "Grade {$gLet}";
                                if ($boardNum !== null) {
                                    $tot = $student->total_internal + $boardNum;
                                    $totalDisplay = number_format($tot, 1);
                                    $finalGrade = $calcGrade($tot, 125);
                                    if ($tot >= 50 && $boardNum >= 20) {
                                        $resultStatus = 'PASSED';
                                        $passCount++;
                                    } else {
                                        $resultStatus = 'REAPPEAR';
                                        $failCount++;
                                        $finalGrade = 'F';
                                    }
                                } else {
                                    $finalGrade = $gLet;
                                    $resultStatus = ($gLet !== 'F' && $gLet !== 'ABS') ? 'PASSED' : 'REAPPEAR';
                                    if ($resultStatus === 'PASSED') $passCount++; else $failCount++;
                                }
                            }
                            if (isset($gradesDist[$finalGrade])) {
                                $gradesDist[$finalGrade]++;
                            }
                        }
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ $idx + 1 }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-bold">{{ $student->roll_no ?? '-' }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ !empty($student->sbte_reg_no) ? $student->sbte_reg_no : $student->reg_no }}</td>
                        <td class="border border-slate-400 p-1 font-medium text-slate-900">{{ $student->name }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono">{{ number_format($student->attendance_percentage ?? 100, 1) }}%</td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-bold text-teal-800 bg-teal-50/50">{{ number_format($student->total_internal, 2) }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-bold text-blue-800 bg-blue-50/50">{{ $eseDisplay }}</td>
                        <td class="border border-slate-400 p-1 text-center font-mono font-bold text-purple-900 bg-purple-50/50">{{ $totalDisplay }}</td>
                        <td class="border border-slate-400 p-1 text-center font-bold {{ $finalGrade === 'F' ? 'text-rose-700' : 'text-slate-900' }}">{{ $finalGrade }}</td>
                        <td class="border border-slate-400 p-1 text-center font-bold text-[10px]">
                            @if($resultStatus === 'PASSED')
                                <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800">PASSED</span>
                            @elseif($resultStatus === 'REAPPEAR')
                                <span class="px-1.5 py-0.5 rounded bg-rose-100 text-rose-800">REAPPEAR</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="border border-slate-400 p-4 text-center text-slate-500 italic">No student evaluation records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @php
        $totalEvaluated = $passCount + $failCount;
        $passRate = $totalEvaluated > 0 ? ($passCount / $totalEvaluated) * 100 : 0.0;
    @endphp

    <!-- Statistics & Grade Distribution Grid -->
    <div class="grid grid-cols-2 gap-4 mb-8 page-break-inside-avoid text-xs">
        <div class="p-3 rounded border border-slate-300 bg-slate-50">
            <h4 class="font-bold uppercase text-[10px] text-slate-700 mb-2 border-b border-slate-200 pb-1">Overall Performance Summary</h4>
            <div class="space-y-1.5">
                <div class="flex justify-between"><span>Total Students Evaluated:</span><span class="font-mono font-bold">{{ $totalEvaluated }}</span></div>
                <div class="flex justify-between text-emerald-700 font-medium"><span>Passed:</span><span class="font-mono font-bold">{{ $passCount }}</span></div>
                <div class="flex justify-between text-rose-700 font-medium"><span>Reappearance Required:</span><span class="font-mono font-bold">{{ $failCount }}</span></div>
                <div class="flex justify-between font-bold border-t border-slate-300 pt-1"><span>Practical Pass Percentage:</span><span class="font-mono text-blue-700">{{ number_format($passRate, 1) }}%</span></div>
            </div>
        </div>

        <div class="p-3 rounded border border-slate-300 bg-slate-50">
            <h4 class="font-bold uppercase text-[10px] text-slate-700 mb-2 border-b border-slate-200 pb-1">SBTE Grade Distribution (Revision 2021)</h4>
            <table class="w-full text-center border-collapse border border-slate-300 text-[9.5px]">
                <thead>
                    <tr class="bg-slate-100 font-bold text-slate-700">
                        <th class="border border-slate-300 p-0.5">S</th>
                        <th class="border border-slate-300 p-0.5">A+</th>
                        <th class="border border-slate-300 p-0.5">A</th>
                        <th class="border border-slate-300 p-0.5">B+</th>
                        <th class="border border-slate-300 p-0.5">B</th>
                        <th class="border border-slate-300 p-0.5">C+</th>
                        <th class="border border-slate-300 p-0.5">C</th>
                        <th class="border border-slate-300 p-0.5">D+</th>
                        <th class="border border-slate-300 p-0.5">D</th>
                        <th class="border border-slate-300 p-0.5">P</th>
                        <th class="border border-slate-300 p-0.5 text-rose-700">F</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="font-mono font-bold">
                        <td class="border border-slate-300 p-1">{{ $gradesDist['S'] }}</td>
                        <td class="border border-slate-300 p-1">{{ $gradesDist['A+'] }}</td>
                        <td class="border border-slate-300 p-1">{{ $gradesDist['A'] }}</td>
                        <td class="border border-slate-300 p-1">{{ $gradesDist['B+'] }}</td>
                        <td class="border border-slate-300 p-1">{{ $gradesDist['B'] }}</td>
                        <td class="border border-slate-300 p-1">{{ $gradesDist['C+'] }}</td>
                        <td class="border border-slate-300 p-1">{{ $gradesDist['C'] }}</td>
                        <td class="border border-slate-300 p-1">{{ $gradesDist['D+'] }}</td>
                        <td class="border border-slate-300 p-1">{{ $gradesDist['D'] }}</td>
                        <td class="border border-slate-300 p-1">{{ $gradesDist['P'] }}</td>
                        <td class="border border-slate-300 p-1 text-rose-700">{{ $gradesDist['F'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Institutional Signatures Footer -->
    <div class="grid grid-cols-3 gap-6 pt-12 text-center text-xs font-bold text-slate-800 border-t border-slate-300 page-break-inside-avoid">
        <div>Signature of Subject Faculty</div>
        <div>Signature of Lab Coordinator</div>
        <div>Head of Department</div>
    </div>

</x-layouts.report-layout>
