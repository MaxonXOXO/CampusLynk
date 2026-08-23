<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidated Practical Evaluation Register (R2026)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: normal;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .meta-table td {
            padding: 4px 8px;
            border: none;
        }
        .meta-table td.label {
            font-weight: bold;
            width: 15%;
        }
        .meta-table td.value {
            width: 35%;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .report-table th, .report-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
        }
        .report-table th {
            background-color: #f2f2f2;
            font-size: 11px;
            font-weight: bold;
        }
        .report-table td.student-name {
            text-align: left;
        }
        .report-table tr.total-row {
            font-weight: bold;
            background-color: #fafafa;
        }
        .footer-sig {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .footer-sig div {
            width: 30%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-weight: bold;
        }
        @media print {
            body {
                margin: 10px;
                font-size: 11px;
            }
            .report-table th {
                background-color: #e6e6e6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
        }
    </style>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>

    <div class="header">
        <h1>Carmel Polytechnic College</h1>
        <h2>Consolidated Practical Evaluation Register (R2026)</h2>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Course Name:</td>
            <td class="value">{{ $batchSubject->subject_name }}</td>
            <td class="label">Regulation:</td>
            <td class="value">REV2026</td>
        </tr>
        <tr>
            <td class="label">Course Code:</td>
            <td class="value">{{ $batchSubject->subject_code }}</td>
            <td class="label">Class / Batch:</td>
            <td class="value">{{ $batchSubject->classroom->class_name ?? $batchSubject->classroom_id }}</td>
        </tr>
        <tr>
            <td class="label">Semester:</td>
            <td class="value">Semester {{ $batchSubject->semester }}</td>
            <td class="label">Total Max CIA:</td>
            <td class="value">60 Marks</td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th rowspan="2">Roll No</th>
                <th rowspan="2">Register No</th>
                <th rowspan="2">Student Name</th>
                <th rowspan="2">Lab Batch</th>
                <th colspan="2">Continuous Evaluation (Table 2.2)</th>
                <th colspan="2">Series Exam (Table 3.1)</th>
                <th colspan="2">Open-Ended (Table 2.3)</th>
                <th colspan="2">Attendance (Table 2.1)</th>
                <th rowspan="2">Final CIA<br>(60 Marks)</th>
            </tr>
            <tr>
                <th>Avg (50M)</th>
                <th>Scaled (30M)</th>
                <th>Avg (40M)</th>
                <th>Scaled (15M)</th>
                <th>Score (50M)</th>
                <th>Scaled (10M)</th>
                <th>Attendance %</th>
                <th>Mark (5M)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            @php
                $score = $consolidatedScores[$student->reg_no] ?? [];
                $savedBatch = $labBatches->get($student->reg_no)->lab_batch ?? null;
                $batchDesignation = $savedBatch ?: ($index < ($students->count() / 2) ? 'Batch A' : 'Batch B');
                $att = $attendanceMarks[$student->reg_no] ?? ['percentage' => 100.00, 'mark' => 5];
            @endphp
            <tr>

                <td>{{ $student->roll_no ?? ($index + 1) }}</td>
                <td style="font-family: monospace;">{{ $student->reg_no }}</td>
                <td class="student-name">{{ $student->name }}</td>
                <td>{{ $batchDesignation }}</td>
                <td>{{ $score['raw_exp_avg'] ?? '0.00' }}</td>
                <td style="font-weight: bold;">{{ $score['scaled_lab_work_30'] ?? '0.00' }}</td>
                <td>{{ $score['raw_series_avg'] ?? '0.00' }}</td>
                <td style="font-weight: bold;">{{ $score['scaled_series_15'] ?? '0.00' }}</td>
                <td>{{ $score['raw_open_ended'] ?? '0.00' }}</td>
                <td style="font-weight: bold;">{{ $score['scaled_open_ended_10'] ?? '0.00' }}</td>
                <td>{{ $att['percentage'] }}%</td>
                <td style="font-weight: bold;">{{ $att['mark'] }}</td>
                <td style="font-size: 14px; font-weight: bold; background-color: #fafafa;">{{ $score['total_cia_60'] ?? '0.00' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-sig">
        <div>Faculty-in-Charge</div>
        <div>Head of Department</div>
        <div>Principal</div>
    </div>

    <script>
        // Auto trigger window print dialog when view loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
