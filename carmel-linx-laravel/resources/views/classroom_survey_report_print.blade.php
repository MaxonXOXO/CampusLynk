<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mid-Semester Feedback Report - {{ $subject->subject_code }}</title>
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
            font-size: 13px;
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
            font-size: 9px;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
        }

        .details-val {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-main);
            margin-top: 1px;
        }

        .section-title {
            background: var(--primary);
            color: white;
            padding: 6px 10px;
            font-size: 12px;
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

        .notes-box {
            border: 1px solid var(--border);
            padding: 12px;
            border-radius: 6px;
            background-color: var(--bg-light);
            min-height: 50px;
            margin-bottom: 15px;
            font-size: 11px;
            line-height: 1.6;
        }

        .signature-section {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }

        .signature-box {
            text-align: center;
            border-top: 1px dashed var(--text-muted);
            padding-top: 10px;
            font-weight: bold;
            color: var(--text-main);
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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>

    <div class="print-controls">
        <button onclick="window.print()" class="btn-print">Print Report</button>
        <button onclick="window.close()" class="btn-print" style="background:#475569;">Close</button>
    </div>

    <div class="a4-container">
        <div class="header">
            <h1>CARMEL POLYTECHNIC COLLEGE</h1>
            <h3>Mid-Semester Feedback Evaluation Report (SAR Criterion 2)</h3>
        </div>

        <div class="details-grid">
            <div class="details-item">
                <span class="details-label">Subject Code / Title</span>
                <span class="details-val">{{ $subject->subject_code }} - {{ $subject->subject_name }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Faculty Member</span>
                <span class="details-val">{{ $survey->faculty_name ?? 'Faculty Member' }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Batch Code / Semester</span>
                <span class="details-val">{{ $subject->classroom->batch ?? 'N/A' }} / S{{ $subject->semester }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Class Management ID</span>
                <span class="details-val">{{ $subject->classroom->classroom_name ?? 'N/A' }}</span>
            </div>
            <div class="details-item">
                <span class="details-label">Total Class Strength</span>
                <span class="details-val">{{ $totalStudents }} Students</span>
            </div>
            <div class="details-item">
                <span class="details-label">Students Responded</span>
                <span class="details-val">{{ $respondedCount }} Students</span>
            </div>
            <div class="details-item">
                <span class="details-label">Participation Rate</span>
                <span class="details-val">
                    @if($totalStudents > 0)
                        {{ round(($respondedCount / $totalStudents) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </span>
            </div>
        </div>

        <div class="section-title">Teaching-Learning Process Feedback Evaluation (1-3 Scale)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">Sl.</th>
                    <th>Evaluation Criteria Item</th>
                    <th style="text-align: center; width: 150px;">Avg Score (3-Point Scale)</th>
                    <th style="text-align: center; width: 150px;">Satisfaction Rate (%)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">5</td>
                    <td><strong>Course Outcomes Communication:</strong> Communicates COs and learning goals at the start of new topics.</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px;">{{ $averages['q5_co_communication'] }}</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px; color: var(--primary);">{{ $satisfaction['q5_co_communication'] }}%</td>
                </tr>
                <tr>
                    <td style="text-align: center;">6</td>
                    <td><strong>Syllabus Delivery Pace:</strong> The pace, speed, and coverage of the syllabus is appropriate.</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px;">{{ $averages['q6_syllabus_pace'] }}</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px; color: var(--primary);">{{ $satisfaction['q6_syllabus_pace'] }}%</td>
                </tr>
                <tr>
                    <td style="text-align: center;">7</td>
                    <td><strong>Concept Clarity & Application:</strong> Explains concepts clearly and links theory to industrial/field applications.</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px;">{{ $averages['q7_concept_clarity'] }}</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px; color: var(--primary);">{{ $satisfaction['q7_concept_clarity'] }}%</td>
                </tr>
                <tr>
                    <td style="text-align: center;">8</td>
                    <td><strong>Effectiveness of ICT/PPT Tools:</strong> Effective use of animations, models, PPTs, or ICT tools.</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px;">{{ $averages['q8_teaching_tools'] }}</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px; color: var(--primary);">{{ $satisfaction['q8_teaching_tools'] }}%</td>
                </tr>
                <tr>
                    <td style="text-align: center;">9</td>
                    <td><strong>Doubt Clearing & Interaction:</strong> Encourages questions, manages discussions, and clears doubts.</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px;">{{ $averages['q9_student_interaction'] }}</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px; color: var(--primary);">{{ $satisfaction['q9_student_interaction'] }}%</td>
                </tr>
                <tr>
                    <td style="text-align: center;">10</td>
                    <td><strong>Test & Assignment Relevance:</strong> Assessment questions and assignments match topics taught.</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px;">{{ $averages['q10_assessment_alignment'] }}</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px; color: var(--primary);">{{ $satisfaction['q10_assessment_alignment'] }}%</td>
                </tr>
                <tr>
                    <td style="text-align: center;">11</td>
                    <td><strong>Fairness in Evaluation:</strong> Evaluation of tests and submissions is fair, timely, and transparent.</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px;">{{ $averages['q11_evaluation_fairness'] }}</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px; color: var(--primary);">{{ $satisfaction['q11_evaluation_fairness'] }}%</td>
                </tr>
                <tr>
                    <td style="text-align: center;">12</td>
                    <td><strong>Guidance for Slow Learners:</strong> Provides extra guidance, remedial tips, or support.</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px;">{{ $averages['q12_slow_learner_support'] }}</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px; color: var(--primary);">{{ $satisfaction['q12_slow_learner_support'] }}%</td>
                </tr>
                @if($averages['q13_branch_specific'] > 0)
                <tr>
                    <td style="text-align: center;">13</td>
                    <td><strong>Branch-Specific Lab / Practical Evaluation:</strong> Demonstrates coding, machinery, circuit handling, or field surveying.</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px;">{{ $averages['q13_branch_specific'] }}</td>
                    <td style="text-align: center; font-weight: bold; font-size: 12px; color: var(--primary);">{{ $satisfaction['q13_branch_specific'] }}%</td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="section-title">Course Outcome (CO) Indirect Attainment Calculation</div>
        <table>
            <thead>
                <tr>
                    <th>Target Course Outcome (CO)</th>
                    <th>Mapped Survey Items</th>
                    <th style="text-align: center; width: 200px;">Indirect Attainment (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coAttainments as $co => $data)
                <tr>
                    <td><strong>{{ $data['name'] }}</strong></td>
                    <td>
                        @if($co == 'CO1') Q5, Q6 @endif
                        @if($co == 'CO2') Q7, Q8 @endif
                        @if($co == 'CO3') Q9, Q13 @endif
                        @if($co == 'CO4') Q10, Q11, Q12 @endif
                    </td>
                    <td style="text-align: center; font-weight: bold; font-size: 13px; color: #0d9488;">{{ $data['percent'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(!empty($difficultTopics))
        <div class="section-title">Difficult Topics Indicated by Students</div>
        <div class="notes-box" style="max-height: 150px; overflow-y: auto;">
            <ul style="padding-left: 15px; margin: 0;">
                @foreach(array_slice($difficultTopics, 0, 15) as $topic)
                    @if(trim($topic))
                        <li style="margin-bottom: 4px;">{{ $topic }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif

        @if(!empty($suggestions))
        <div class="section-title">Constructive Suggestions for Course Delivery</div>
        <div class="notes-box" style="max-height: 150px; overflow-y: auto;">
            <ul style="padding-left: 15px; margin: 0;">
                @foreach(array_slice($suggestions, 0, 15) as $suggestion)
                    @if(trim($suggestion))
                        <li style="margin-bottom: 4px;">{{ $suggestion }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif

        <div class="section-title">Improvements Noted by Faculty</div>
        <div class="notes-box">
            {{ $survey->improvements_noted ?: 'No improvements noted registered yet.' }}
        </div>

        <div class="section-title">Action Taken on Feedback (Faculty Member)</div>
        <div class="notes-box">
            {{ $survey->action_taken ?: 'No action taken details registered yet.' }}
        </div>

        <div class="section-title">Action Taken on Feedback (Class Tutor Notes)</div>
        <div class="notes-box">
            {{ $survey->action_taken_by_tutor ?: 'No action taken notes recorded by the Class Tutor yet.' }}
        </div>

        <div class="section-title">Action Taken on Feedback (Head of Department Remarks)</div>
        <div class="notes-box">
            {{ $survey->action_taken_by_hod ?: 'No action taken notes recorded by the HOD yet.' }}
        </div>

        <div class="signature-section">
            <div class="signature-box" style="margin-top: 40px;">
                Faculty Member Signature
            </div>
            <div class="signature-box" style="margin-top: 40px;">
                Head of Department (HOD)
            </div>
        </div>
    </div>

</body>
</html>
