<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NBA Course File - {{ $practicumCourseFile->course_code }} {{ $practicumCourseFile->course_title }}</title>
    <style>
        @page { size: A4; margin: 15mm; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11pt; color: #1e293b; line-height: 1.5; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header-table td { border: 1px solid #94a3b8; padding: 8px 12px; font-size: 10pt; }
        .header-title { font-size: 14pt; font-weight: bold; text-align: center; background-color: #f1f5f9; text-transform: uppercase; }
        .section-title { font-size: 12pt; font-weight: bold; margin-top: 20px; margin-bottom: 8px; border-bottom: 2px solid #0f172a; padding-bottom: 4px; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .data-table th, .data-table td { border: 1px solid #cbd5e1; padding: 6px 10px; font-size: 10pt; text-align: left; }
        .data-table th { background-color: #f8fafc; font-weight: bold; }
        .text-center { text-align: center; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    <!-- Cover Page Header -->
    <table class="header-table">
        <tr>
            <td colspan="4" class="header-title">
                STATE BOARD OF TECHNICAL EDUCATION, KERALA<br>
                DEPARTMENT OF TECHNICAL EDUCATION<br>
                <span style="font-size:11pt; font-weight:normal;">NBA COURSE FILE - REVISION 2026 (PRACTICUM COURSE)</span>
            </td>
        </tr>
        <tr>
            <td><strong>Course Title:</strong></td>
            <td>{{ $practicumCourseFile->course_title }}</td>
            <td><strong>Course Code:</strong></td>
            <td>{{ $practicumCourseFile->course_code }}</td>
        </tr>
        <tr>
            <td><strong>Semester:</strong></td>
            <td>{{ $practicumCourseFile->semester }}</td>
            <td><strong>Type of Course:</strong></td>
            <td>Practicum (Theory + Lab)</td>
        </tr>
        <tr>
            <td><strong>Teaching Scheme:</strong></td>
            <td>{{ $practicumCourseFile->teaching_scheme }} (L:T:P:R)</td>
            <td><strong>Credits / Contact Hrs:</strong></td>
            <td>{{ $practicumCourseFile->credits }} Credits / {{ $practicumCourseFile->contact_hours }} Hours</td>
        </tr>
        <tr>
            <td><strong>CIE Marks:</strong></td>
            <td>{{ $practicumCourseFile->cie_marks }} Marks</td>
            <td><strong>ESE Marks:</strong></td>
            <td>{{ $practicumCourseFile->ese_marks }} Marks</td>
        </tr>
    </table>

    <div class="section-title">1. Course Outcomes (COs)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:10%;">CO ID</th>
                <th>Course Outcome Description</th>
                <th style="width:20%;">Cognitive Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($practicumCourseFile->parsed_cos as $co)
            <tr>
                <td><strong>{{ $co['id'] }}</strong></td>
                <td>{{ $co['description'] }}</td>
                <td>{{ $co['cognitive_level'] ?? 'Apply' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">2. CO-PO Mapping Articulation Matrix</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>CO</th>
                @for($p=1; $p<=11; $p++)
                <th class="text-center">PO{{ $p }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($practicumCourseFile->parsed_cos as $co)
            @php $mappings = $practicumCourseFile->parsed_copo['mappings'][$co['id']] ?? []; @endphp
            <tr>
                <td><strong>{{ $co['id'] }}</strong></td>
                @for($p=1; $p<=11; $p++)
                <td class="text-center">{{ $mappings["PO$p"] ?? '-' }}</td>
                @endfor
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">3. Theory Modules Overview (Lecture L)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:15%;">Module</th>
                <th>Module Title & Syllabus Content</th>
                <th style="width:15%;">Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach($practicumCourseFile->parsed_modules as $mod)
            <tr>
                <td><strong>Module {{ $mod['module_id'] }}</strong></td>
                <td><strong>{{ $mod['title'] }}</strong><br><span style="color:#475569;">{{ $mod['content'] }}</span></td>
                <td>{{ $mod['hours'] ?? 15 }} Hours</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">4. Practical Experiments Roster (Practical P)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:15%;">Exp No</th>
                <th>Experiment Title</th>
                <th style="width:15%;">Mapped CO</th>
                <th style="width:15%;">Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach($practicumCourseFile->parsed_experiments as $exp)
            <tr>
                <td><strong>{{ $exp['experiment_no'] }}</strong></td>
                <td>{{ $exp['title'] }}</td>
                <td>{{ $exp['co_id'] }}</td>
                <td>{{ $exp['hours'] ?? 3 }} Hours</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">5. Consolidated Continuous Internal Assessment (CIA - 40 Marks Summary)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:8%;">Roll</th>
                <th style="width:18%;">Register No</th>
                <th>Student Name</th>
                <th class="text-center">Att (5M)</th>
                <th class="text-center">SL (5M)</th>
                <th class="text-center">Lab (10M)</th>
                <th class="text-center">Ser Th (10M)</th>
                <th class="text-center">Ser Pr (10M)</th>
                <th class="text-center">Total CIA (40M)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentResults as $res)
            <tr>
                <td>{{ $res['roll_no'] }}</td>
                <td>{{ $res['reg_no'] }}</td>
                <td><strong>{{ $res['name'] }}</strong></td>
                <td class="text-center">{{ $res['att_marks'] }}</td>
                <td class="text-center">{{ number_format($res['sl_marks'], 2) }}</td>
                <td class="text-center">{{ number_format($res['continuous_eval_marks'], 2) }}</td>
                <td class="text-center">{{ number_format($res['series_theory_marks'], 2) }}</td>
                <td class="text-center">{{ number_format($res['series_practical_marks'], 2) }}</td>
                <td class="text-center"><strong>{{ number_format($res['total_cia_marks'], 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br><br><br>
    <table style="width:100%; border:none; margin-top:30px;">
        <tr>
            <td style="border:none; text-align:left;"><strong>Faculty In-Charge</strong><br><br>Signature: ______________</td>
            <td style="border:none; text-align:center;"><strong>Head of Department (HOD)</strong><br><br>Signature: ______________</td>
            <td style="border:none; text-align:right;"><strong>Principal</strong><br><br>Signature: ______________</td>
        </tr>
    </table>

</body>
</html>
