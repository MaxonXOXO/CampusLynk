<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Classroom Results Report - {{ $batchSubject->subject_name }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        @page {
            size: A4 portrait;
            margin: 15mm 15mm;
        }

        body {
            background-color: #fff;
            color: #000;
            font-size: 12px;
            line-height: 1.4;
            padding: 10px;
        }

        .a4-page {
            width: 100%;
            margin: 0 auto;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 12px 18px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: 1px solid #e2e8f0;
            z-index: 50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-print {
            background: #0f172a;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-print:hover {
            background: #334155;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px double #000;
            padding-bottom: 8px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }

        .header h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header h3 {
            font-size: 12px;
            font-weight: normal;
            margin-bottom: 4px;
        }

        .header-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-meta td {
            padding: 5px 8px;
            font-size: 12px;
            border: 1px solid #000;
        }
        
        .header-meta td.label {
            font-weight: bold;
            background-color: #f8fafc;
            width: 18%;
        }
        
        .header-meta td.value {
            font-weight: normal;
            width: 32%;
        }

        .report-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin: 15px 0 10px 0;
            letter-spacing: 0.5px;
            text-decoration: underline;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .marks-table th, .marks-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 11px;
            vertical-align: middle;
        }

        .marks-table th {
            font-weight: bold;
            text-align: center;
            background-color: #f1f5f9;
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .font-mono {
            font-family: monospace;
        }

        .font-bold {
            font-weight: bold;
        }

        .stats-grid {
            margin-top: 20px;
            display: grid;
            grid-template-cols: 1fr 1fr;
            gap: 20px;
        }

        .stats-card {
            border: 1px solid #000;
            padding: 10px;
        }

        .stats-card h4 {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stats-table td {
            padding: 4px 6px;
            font-size: 11px;
        }

        .footer-signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: bold;
            padding: 0 10px;
        }

        @media print {
            .print-controls {
                display: none !important;
            }
            .header-meta td.label, .marks-table th {
                background-color: transparent !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">Print Report</button>
        <button class="btn-print" onclick="window.close()" style="background:#dc2626;">Close Window</button>
    </div>

    <div class="a4-page">
        
        <div class="header">
            <h1>Carmel College of Engineering</h1>
            <h2>Department of {{ getFullBranchName($classroom->branch ?? '') }}</h2>
            <h3>Revision 2026 Scheme - Consolidated Final Results</h3>
        </div>

        <table class="header-meta">
            <tr>
                <td class="label">Course Title:</td>
                <td class="value">{{ $batchSubject->subject_name }}</td>
                <td class="label">Course Code:</td>
                <td class="value">{{ $batchSubject->subject_code }}</td>
            </tr>
            <tr>
                <td class="label">Class / Batch:</td>
                <td class="value">{{ $classroom->classroom_name ?? $batchSubject->classroom_id }}</td>
                <td class="label">Semester:</td>
                <td class="value">Semester {{ $classroom->current_semester ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Academic Year:</td>
                <td class="value">2026 Revision</td>
                <td class="label">Report Date:</td>
                <td class="value font-bold">{{ date('d/m/Y') }}</td>
            </tr>
        </table>

        <div class="report-title">
            CONSOLIDATED STUDENT RESULTS SHEET (CIE & ESE)
        </div>

        <table class="marks-table">
            <thead>
                <tr>
                    <th style="width: 6%;">Roll No</th>
                    <th style="width: 14%;">SBTE ID</th>
                    <th>Student Name</th>
                    <th class="text-center" style="width: 12%;">Attendance %</th>
                    <th class="text-center" style="width: 10%;">CIA<br>(40M Max)</th>
                    <th class="text-center" style="width: 10%;">ESE<br>(60M Max)</th>
                    <th class="text-center" style="width: 10%;">Total<br>(100M Max)</th>
                    <th class="text-center" style="width: 10%;">Grade</th>
                    <th class="text-center" style="width: 12%;">Remark</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $passCount = 0;
                    $failCount = 0;
                    $grades = ['S' => 0, 'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0];
                @endphp
                @forelse($studentCiaData as $sc)
                    @php
                        $tot = $sc['total_cia'] + $sc['ese_marks'];
                        
                        // Grade mapping
                        if ($tot >= 90) { $grade = 'S'; }
                        elseif ($tot >= 80) { $grade = 'A'; }
                        elseif ($tot >= 70) { $grade = 'B'; }
                        elseif ($tot >= 60) { $grade = 'C'; }
                        elseif ($tot >= 50) { $grade = 'D'; }
                        elseif ($tot >= 40) { $grade = 'E'; }
                        else { $grade = 'F'; }

                        // Remark mapping
                        if ($tot >= 40 && $sc['ese_marks'] >= 24) {
                            $remark = 'PASS';
                            $passCount++;
                        } else {
                            $remark = 'FAIL';
                            $failCount++;
                            $grade = 'F';
                        }
                        $grades[$grade]++;
                    @endphp
                    <tr>
                        <td class="text-center font-mono font-bold">{{ $sc['roll_no'] ?: '—' }}</td>
                        <td class="font-mono text-center">{{ $sc['sbte_reg_no'] ?: 'Unassigned' }}</td>
                        <td class="font-bold">{{ $sc['name'] }}</td>
                        <td class="text-center font-mono">{{ $sc['attendance_percent'] }}%</td>
                        <td class="text-center font-mono">{{ number_format($sc['total_cia'], 1) }}</td>
                        <td class="text-center font-mono">{{ number_format($sc['ese_marks'], 1) }}</td>
                        <td class="text-center font-mono font-bold">{{ number_format($tot, 1) }}</td>
                        <td class="text-center font-bold">{{ $grade }}</td>
                        <td class="text-center font-bold {{ $remark === 'PASS' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $remark }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center italic">No student result records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @php
            $totalStudents = $passCount + $failCount;
            $passRate = $totalStudents > 0 ? ($passCount / $totalStudents) * 100 : 0.0;
        @endphp

        <div class="stats-grid">
            <div class="stats-card">
                <h4>Performance Summary</h4>
                <table class="stats-table">
                    <tr>
                        <td class="font-bold">Total Students Evaluated:</td>
                        <td class="text-center font-mono">{{ $totalStudents }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold text-emerald-600">Passed:</td>
                        <td class="text-center font-mono font-bold">{{ $passCount }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold text-rose-600">Failed / Absent:</td>
                        <td class="text-center font-mono font-bold">{{ $failCount }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #000;">
                        <td class="font-bold text-lg">Pass Percentage:</td>
                        <td class="text-center font-mono font-bold text-lg">{{ number_format($passRate, 1) }}%</td>
                    </tr>
                </table>
            </div>

            <div class="stats-card">
                <h4>Grade Distribution</h4>
                <table class="stats-table" style="text-align: center;">
                    <thead>
                        <tr style="border-bottom: 1px solid #000;">
                            <th class="font-bold">S</th>
                            <th class="font-bold">A</th>
                            <th class="font-bold">B</th>
                            <th class="font-bold">C</th>
                            <th class="font-bold">D</th>
                            <th class="font-bold">E</th>
                            <th class="font-bold">F</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-mono font-bold">{{ $grades['S'] }}</td>
                            <td class="font-mono font-bold">{{ $grades['A'] }}</td>
                            <td class="font-mono font-bold">{{ $grades['B'] }}</td>
                            <td class="font-mono font-bold">{{ $grades['C'] }}</td>
                            <td class="font-mono font-bold">{{ $grades['D'] }}</td>
                            <td class="font-mono font-bold">{{ $grades['E'] }}</td>
                            <td class="font-mono font-bold text-rose-600">{{ $grades['F'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer-signatures">
            <div>Faculty Signature</div>
            <div>Head of Department Signature</div>
            <div>Principal Signature</div>
        </div>

    </div>

</body>
</html>
