<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Leave Report - {{ $student->reg_no }}</title>
    <style>
        :root {
            --primary: #1e3a8a;
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
            font-size: 13px;
        }

        .a4-container {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
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
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-print:hover {
            background: #1d4ed8;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            color: var(--primary);
            font-size: 26px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .header h3 {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 600;
        }

        .details-grid {
            display: grid;
            grid-cols: 2;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
            border: 1px solid var(--border);
            padding: 15px;
            border-radius: 8px;
            background-color: var(--bg-light);
        }

        .details-item {
            display: flex;
            flex-direction: column;
        }

        .details-label {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
        }

        .details-val {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            margin-top: 2px;
        }

        .section-title {
            background: var(--primary);
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 25px;
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
            font-size: 11px;
            border-bottom: 2px solid var(--border);
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }

        .summary-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
            padding: 15px;
            border: 2px dashed var(--primary);
            border-radius: 8px;
            background-color: #eff6ff;
        }

        .summary-val {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary);
        }

        .status-badge {
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            text-transform: uppercase;
        }

        .status-Approved {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-Pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-Rejected {
            background-color: #fee2e2;
            color: #991b1b;
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
            <h3>Student Leave & Attendance Summary Report</h3>
        </div>

        <div class="details-grid">
            <div class="details-item">
                <span class="details-label">Student Name</span>
                <span class="details-val">{{ $student->name }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Department / Branch</span>
                <span class="details-val">{{ $fullDepartment }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">SBTE Reg No</span>
                <span class="details-val">{{ $student->sbte_reg_no ?: 'Not Provided' }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">College Admission No</span>
                <span class="details-val">{{ $student->reg_no }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Academic Batch Year</span>
                <span class="details-val">{{ $cleanedBatch }}</span>
            </div>
        </div>

        <div class="section-title">Leave History Details</div>
        <table>
            <thead>
                <tr>
                    <th>Semester</th>
                    <th>Dates</th>
                    <th>No. of Days</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $lv)
                    <tr>
                        <td style="font-weight: 700;">Semester {{ $lv->semester }}</td>
                        <td>{{ $lv->leave_date }}</td>
                        <td style="font-weight: 700;">{{ $lv->no_of_days }} day(s)</td>
                        <td>{{ $lv->reason }}</td>
                        <td>
                            <span class="status-badge status-{{ $lv->status }}">{{ $lv->status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">No leave records submitted.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary-box">
            <div class="details-item">
                <span class="details-label">Total Approved Leaves</span>
                <span class="summary-val">{{ $totalLeaves }} Day(s)</span>
            </div>
            <div class="details-item">
                <span class="details-label">Estimated Attendance Percentage</span>
                <span class="summary-val">{{ $attendancePercentage }} %</span>
            </div>
        </div>

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
