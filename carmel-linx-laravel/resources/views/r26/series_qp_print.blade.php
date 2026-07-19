<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exam->exam_name }} - Question Paper</title>
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
            margin: 20mm 15mm;
        }

        body {
            background-color: #fff;
            color: #000;
            font-size: 13px;
            line-height: 1.4;
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
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            z-index: 50;
            display: flex;
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
            font-size: 13px;
        }

        .btn-print:hover {
            background: #334155;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header h2 {
            font-size: 13px;
            font-weight: normal;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .header-meta {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .header-meta td {
            padding: 4px 8px;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #000;
        }

        .part-header {
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1.5px solid #000;
            padding-bottom: 3px;
            font-size: 14px;
        }

        .questions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .questions-table th, .questions-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 13px;
            vertical-align: top;
        }

        .questions-table th {
            font-weight: bold;
            text-align: center;
        }

        @media print {
            .print-controls {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">Print Paper</button>
        <button class="btn-print" onclick="window.close()" style="background:#dc2626;">Close Window</button>
    </div>

    <div class="a4-page">
        
        <div class="header">
            <h1>Carmel College of Engineering</h1>
            <h2>Department of {{ getFullBranchName($classroom->branch ?? '') }}</h2>
            <h2>Revision 2026 Scheme - Theory Examination</h2>
            <h2 style="font-weight: bold; margin-top: 8px;">{{ $exam->exam_name }}</h2>
        </div>

        <table class="header-meta">
            <tr>
                <td style="width: 25%;">Course Title:</td>
                <td style="width: 45%; font-weight: normal;">{{ $batchSubject->subject_name }}</td>
                <td style="width: 15%;">Course Code:</td>
                <td style="width: 15%; font-weight: normal;">{{ $batchSubject->subject_code }}</td>
            </tr>
            <tr>
                <td>Batch ID / Classroom:</td>
                <td style="font-weight: normal;">{{ $batchSubject->classroom_id }}</td>
                <td>Max Marks:</td>
                <td style="font-weight: normal;">{{ $exam->max_marks }} Marks</td>
            </tr>
            <tr>
                <td>Academic Year:</td>
                <td style="font-weight: normal;">2026 Revision</td>
                <td>Duration:</td>
                <td style="font-weight: normal;">{{ $exam->duration_minutes }} Minutes</td>
            </tr>
        </table>

        @php
            $parts = ['Part A' => 1, 'Part B' => 3, 'Part C' => 7];
            $questions = is_string($exam->questions) ? json_decode($exam->questions, true) : $exam->questions;
        @endphp

        @foreach($parts as $partName => $defaultMarks)
            @php
                $partQ = $questions[$partName] ?? [];
            @endphp
            @if(count($partQ) > 0)
                <div class="part-header">
                    {{ $partName }} (Answer all questions, each carries {{ $defaultMarks }} marks)
                </div>

                <table class="questions-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th>Question</th>
                            <th style="width: 10%;">CO Tag</th>
                            <th style="width: 15%;">BT Level</th>
                            <th style="width: 10%;">Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($partQ as $idx => $q)
                            <tr>
                                <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                                <td>{{ $q['question'] }}</td>
                                <td style="text-align: center;">{{ $q['co_tag'] ?? 'CO1' }}</td>
                                <td style="text-align: center;">{{ $q['bt_level'] ?? 'Understand' }}</td>
                                <td style="text-align: center; font-weight: bold;">{{ $q['marks'] ?? $defaultMarks }}M</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach

    </div>

</body>
</html>
