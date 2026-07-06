<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Exit Feedback Report - {{ $subject->subject_code }}</title>
    <style>
        :root {
            --primary: #0f766e;
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
            font-size: 14px;
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
            background: #0d5e58;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 12px;
            margin-bottom: 20px;
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
            font-size: 14px;
            font-weight: 600;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
            border: 1px solid var(--border);
            padding: 12px;
            border-radius: 6px;
            background-color: var(--bg-light);
        }

        .details-item {
            display: flex;
            flex-direction: column;
        }

        .details-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
        }

        .details-val {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            margin-top: 1px;
        }

        .section-title {
            background: var(--primary);
            color: white;
            padding: 6px 10px;
            font-size: 15px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 12px;
            text-transform: uppercase;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: var(--bg-light);
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            border-bottom: 2px solid var(--border);
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: var(--text-main);
        }

        .score-badge {
            background-color: var(--bg-light);
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 700;
            border: 1px solid var(--border);
        }

        .attainment-value {
            font-size: 14px;
            font-weight: 800;
            color: var(--primary);
        }

        .signature-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 50px;
        }

        .sig-box {
            border-top: 1px dashed var(--border);
            text-align: center;
            padding-top: 8px;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-top: 30px;
        }

        @media print {
            body {
                background: white;
                color: black;
            }
            .a4-container {
                margin: 0;
                padding: 0;
                box-shadow: none;
                width: 100%;
            }
            .print-controls {
                display: none !important;
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
        <!-- Header -->
        <div class="header">
            <h1>Carmel Polytechnic College, Alappuzha</h1>
            <h3>DEPARTMENT OF COURSE EVALUATION & ACCREDITATION</h3>
            <h3 style="margin-top: 5px; font-size: 12px; color: var(--primary); font-weight: 800;">INDIRECT COURSE OUTCOME (CO) ATTAINMENT REPORT</h3>
        </div>

        <!-- Subject/Survey details -->
        <div class="details-grid">
            <div class="details-item">
                <span class="details-label">Subject & Code</span>
                <span class="details-val">{{ $subject->subject_code }} — {{ $subject->subject_name }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Faculty Member</span>
                <span class="details-val">{{ $survey->faculty_name ?? 'Faculty Member' }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Semester / Batch</span>
                <span class="details-val">Semester {{ $subject->semester }} / {{ $subject->classroom->batch_year ?? 'N/A' }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Total Student Strength</span>
                <span class="details-val">{{ $totalStudents }} Students</span>
            </div>
            <div class="details-item">
                <span class="details-label">Feedback Responded</span>
                <span class="details-val">{{ $respondedCount }} Students</span>
            </div>
            <div class="details-item">
                <span class="details-label">Response Participation Rate</span>
                <span class="details-val" style="color: var(--primary); font-weight: 800;">
                    {{ $totalStudents > 0 ? round(($respondedCount / $totalStudents) * 100, 1) : 0 }}%
                </span>
            </div>
        </div>

        <!-- CO Attainments Summary -->
        <div class="section-title">Indirect Course Outcome (CO) Attainment Summaries</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">CO Identifier</th>
                    <th style="width: 55%;">Course Outcome Statement & Mapping Description</th>
                    <th style="width: 15%; text-align: center;">Average (Scale 1-3)</th>
                    <th style="width: 15%; text-align: center;">Attainment (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coAttainments as $coKey => $coData)
                <tr>
                    <td style="font-weight: 800; color: var(--primary);">{{ $coKey }}</td>
                    <td style="font-weight: 500;">{{ $coData['name'] }}</td>
                    <td style="text-align: center;">
                        <span class="score-badge">
                            @if($coKey === 'CO1')
                                {{ round(($averages['co1_q1'] + $averages['co1_q2']) / 2, 2) }}
                            @elseif($coKey === 'CO2')
                                {{ round(($averages['co2_q3'] + $averages['co2_q4']) / 2, 2) }}
                            @elseif($coKey === 'CO3')
                                {{ round(($averages['co3_q5'] + $averages['co3_q6']) / 2, 2) }}
                            @elseif($coKey === 'CO4')
                                {{ round(($averages['co4_q7'] + $averages['co4_q8'] + $averages['co4_q9']) / 3, 2) }}
                            @endif
                        </span>
                    </td>
                    <td style="text-align: center;" class="attainment-value">{{ $coData['percent'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Detailed Question Averages & Satisfaction Rates -->
        <div class="section-title">Question-wise Breakdown Analysis</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">No.</th>
                    <th style="width: 62%;">Evaluation Criterion Question Context</th>
                    <th style="width: 15%; text-align: center;">Average Score</th>
                    <th style="width: 15%; text-align: center;">Satisfaction Rate</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $qContexts = [
                        'co1_q1' => 'Q1 (CO1): Course knowledge, core academic principles & fundamentals.',
                        'co1_q2' => 'Q2 (CO1): Outcome mapping, scope definitions, and basic terms.',
                        'co2_q3' => 'Q3 (CO2): Analytical reasoning and logical analysis capabilities.',
                        'co2_q4' => 'Q4 (CO2): Troubleshooting models and drafting sub-system designs.',
                        'co3_q5' => 'Q5 (CO3): Practical operations, laboratory kits & programming labs.',
                        'co3_q6' => 'Q6 (CO3): Safety standards, limits, and instrumentation regulations.',
                        'co4_q7' => 'Q7 (CO4): Thorough continuous assessments, assignments & exams.',
                        'co4_q8' => 'Q8 (CO4): Professional ethics, environmental & social concerns.',
                        'co4_q9' => 'Q9 (CO4): Motivation for self-learning and modern advancements.',
                        'co_overall_q10' => 'Q10 (Overall): Overall course delivery satisfaction & guidance.'
                    ];
                    $idx = 1;
                @endphp
                @foreach($qContexts as $fieldKey => $descText)
                <tr>
                    <td style="font-weight: 800;">{{ $idx++ }}</td>
                    <td>{{ $descText }}</td>
                    <td style="text-align: center;"><span class="score-badge">{{ $averages[$fieldKey] }} / 3</span></td>
                    <td style="text-align: center; font-weight: 700; color: #0d5e58;">{{ $satisfaction[$fieldKey] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Signatures block -->
        <div class="signature-section">
            <div class="sig-box">
                Signature of Faculty Member<br>
                <span style="font-size: 9px; font-weight: 500; text-transform: none;">Name: {{ $survey->faculty_name ?? 'Faculty Member' }}</span>
            </div>
            <div class="sig-box">
                Verified By<br>
                <span style="font-size: 9px; font-weight: 500;">Head of Department (HOD)</span>
            </div>
        </div>
    </div>

</body>
</html>
