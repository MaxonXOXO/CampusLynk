<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>45-Hour Drawing Lab Lesson Plan - {{ $batchSubject->subject_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            color: #555;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px 6px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        .bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Carmel Polytechnic College, Alappuzha</h1>
        <h2>45-Hour Engineering Drawing Lab Lesson Plan - Revision 2026</h2>
    </div>

    <div class="meta-info">
        <div>
            <strong>Course:</strong> {{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})<br>
            <strong>Class/Sem:</strong> Sem I - Diploma in Engineering
        </div>
        <div style="text-align: right;">
            <strong>Academic Year:</strong> 2026-2027<br>
            <strong>Faculty:</strong> {{ $staff->name ?? 'Lecturer In Charge' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">Hour</th>
                <th style="width: 80px;">Sub-Batch</th>
                <th style="width: 90px;">Proposed Date</th>
                <th style="width: 90px;">Actual Date</th>
                <th class="text-left">Topic & Exercise Content</th>
                <th style="width: 60px;">CO</th>
                <th style="width: 110px;">Pedagogy / Exam</th>
                <th style="width: 80px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lessonPlans as $lp)
            <tr>
                <td>#{{ $lp->day_no }}</td>
                <td><strong style="color: #444;">{{ $lp->sub_batch ?? 'Whole Class' }}</strong></td>
                <td>{{ $lp->proposed_date ?: ($lp->planned_date ?: '-') }}</td>
                <td>{{ $lp->actual_date ?: '-' }}</td>
                <td class="text-left bold">{{ $lp->topic_content }}</td>
                <td>{{ $lp->co_tag ?: ($lp->co_id ?: 'CO1') }}</td>
                <td>{{ $lp->pedagogy }}</td>
                <td style="font-weight: bold; color: {{ $lp->status == 'Completed' ? 'green' : 'orange' }};">{{ $lp->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="padding: 20px; text-align: center; color: #777;">No planner generated yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-line">
            Faculty In Charge
        </div>
        <div class="signature-line">
            Head of Department
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
