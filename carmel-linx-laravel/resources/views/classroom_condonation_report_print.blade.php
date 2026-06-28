<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Condonation & Attendance Shortage Report - {{ $classroomId }}</title>
    <style>
        :root {
            --primary: #851414;
            --border: #cbd5e1;
            --bg-light: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            background-color: #f1f5f9;
            color: var(--text-main);
            line-height: 1.5;
            font-size: 12px;
        }

        .a4-container {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            position: relative;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            z-index: 50;
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-print:hover {
            background: #600d0d;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            color: var(--primary);
            font-size: 22px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .header h3 {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .section-title {
            background: var(--primary);
            color: white;
            padding: 6px 10px;
            font-size: 13px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th {
            background-color: var(--bg-light);
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            border-bottom: 2px solid var(--border);
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }

        .highlight-shortage {
            background-color: #fef2f2;
            font-weight: bold;
        }

        .shortage-badge {
            background-color: #fee2e2;
            color: #991b1b;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }

        @media print {
            body {
                background-color: white;
            }
            .print-controls {
                display: none;
            }
            .a4-container {
                width: auto;
                min-height: auto;
                padding: 0;
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button onclick="window.print()" class="btn-print">Print Document</button>
        <button onclick="window.close()" class="btn-print" style="background:#475569;">Close</button>
    </div>

    <div class="a4-container">
        <div class="header">
            <h1>CARMEL POLYTECHNIC COLLEGE</h1>
            <h3>Attendance Shortage & Condonation Eligibility Report</h3>
            <p style="font-weight: bold; margin-top: 5px; color: var(--text-main);">
                Department: {{ $fullDepartment }} &nbsp;&nbsp;|&nbsp;&nbsp; Batch: {{ $cleanedBatch }}
            </p>
        </div>

        <div class="section-title">Students Condonation & Attendance List</div>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Admission No</th>
                    <th>SBTE Reg No</th>
                    <th>Approved Leaves</th>
                    <th>Attendance %</th>
                    <th>Status / Remarks</th>
                    <th>Action/Chance Required</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                    <tr class="{{ $s->attendance_percentage < 75 ? 'highlight-shortage' : '' }}">
                        <td>{{ $s->name }}</td>
                        <td style="font-family: monospace;">{{ $s->reg_no }}</td>
                        <td style="font-family: monospace;">{{ $s->sbte_reg_no ?: '-' }}</td>
                        <td style="text-align: center;">{{ $s->total_leaves }} Day(s)</td>
                        <td>{{ $s->attendance_percentage }} %</td>
                        <td>
                            @if($s->attendance_percentage < 75)
                                <span class="shortage-badge">Shortage (< 75%)</span>
                            @else
                                <span style="color: #166534; font-weight: bold;">Normal</span>
                            @endif
                        </td>
                        <td>
                            {{ $s->condonation_action }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 20px;">No student records found in this classroom.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 80px; display: flex; justify-content: space-between;">
            <div style="text-align: center; width: 200px; border-top: 1px solid var(--text-muted); padding-top: 5px;">
                <p style="font-weight: bold;">Class Tutor Signature</p>
            </div>
            <div style="text-align: center; width: 200px; border-top: 1px solid var(--text-muted); padding-top: 5px;">
                <p style="font-weight: bold;">Head of Department</p>
            </div>
        </div>
    </div>

</body>
</html>
