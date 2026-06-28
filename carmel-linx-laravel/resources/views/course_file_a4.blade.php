<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Course File - {{ $courseFile->batchSubject->subject->subject_code ?? '' }}</title>
    <style>
        @page { margin: 2.5cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; line-height: 1.5; color: #333; }
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-xs { font-size: 18px; }
        .text-sm { font-size: 24px; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-8 { margin-bottom: 2rem; }
        .mt-8 { margin-top: 2rem; }
        .uppercase { text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        
        .section-title {
            background-color: #1f2937;
            color: white;
            padding: 10px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 2rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .placeholder-box {
            border: 2px dashed #9ca3af;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            margin: 20px 0;
            color: #6b7280;
        }
    </style>
</head>
<body>

    <!-- Cover Page -->
    <div class="text-center" style="margin-top: 100px;">
        <h1 class="uppercase font-bold mb-4 text-xl">Carmel Polytechnic College</h1>
        <h2 class="uppercase font-bold mb-8 text-lg">Department of {{ $courseFile->batchSubject->batch->branch ?? 'General' }}</h2>
        
        <div style="border: 2px solid #000; padding: 40px; margin: 40px;">
            <h1 class="font-bold uppercase mb-4 text-xl">Course File</h1>
            <h2 class="mb-8 text-lg">Academic Year: {{ $courseFile->academic_year }}</h2>
            
            <table style="width: 80%; margin: 0 auto; border: none;">
                <tr>
                    <td style="border: none; font-weight: bold; width: 40%;">Course Code:</td>
                    <td style="border: none;">{{ $courseFile->batchSubject->subject->subject_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: none; font-weight: bold;">Course Name:</td>
                    <td style="border: none;">{{ $courseFile->batchSubject->subject->subject_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: none; font-weight: bold;">Semester:</td>
                    <td style="border: none;">{{ $courseFile->batchSubject->semester ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: none; font-weight: bold;">Batch Year:</td>
                    <td style="border: none;">{{ $courseFile->batchSubject->batch->batch_year ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="border: none; font-weight: bold;">Faculty Name:</td>
                    <td style="border: none;">_______________________</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Section A: Planning -->
    <div class="section-title">Section A: Course Information & Planning</div>
    
    <h3>1. Gaps Identified (if any)</h3>
    <p>{{ $courseFile->sectionA->gaps_identified ?? 'No gaps identified.' }}</p>

    <h3>2. Bridge Topics to meet outcomes</h3>
    <p>{{ $courseFile->sectionA->bridge_topics ?? 'N/A' }}</p>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert the physical hard copy of the officially approved SBTE Kerala Syllabus here.</p>
    </div>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert the Faculty and Class Timetables here.</p>
    </div>
    
    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert the detailed Day-by-Day Lesson/Lecture Plan here.</p>
    </div>

    <div class="page-break"></div>

    <!-- Section B: Materials -->
    <div class="section-title">Section B: Teaching Materials</div>
    
    <h3>1. NPTEL / Swayam Links</h3>
    <p>{!! nl2br(e($courseFile->sectionB->nptel_swayam_links ?? 'None provided.')) !!}</p>

    <h3>2. Other Resources & Reference Materials</h3>
    <p>{!! nl2br(e($courseFile->sectionB->other_resources ?? 'None provided.')) !!}</p>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert physical Lecture Notes, Printed Handouts, or Lab Manuals here.</p>
    </div>

    <div class="page-break"></div>

    <!-- Section C: Assessments -->
    <div class="section-title">Section C: Assessments & Evaluations</div>
    
    <h3>1. Evaluation Scheme (CIE & End Semester)</h3>
    <p>{!! nl2br(e($courseFile->sectionC->evaluation_scheme ?? 'Standard university evaluation scheme applies.')) !!}</p>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert Mid-term and End-semester Question Papers & Answer Keys here.</p>
    </div>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert Sample Answer Scripts (Best, Average, and Low Performing) here.</p>
    </div>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert Consolidated Attendance & Continuous Internal Evaluation (CIE) Mark Sheets here.</p>
    </div>

    <div class="page-break"></div>

    <!-- Section D: Attainment -->
    <div class="section-title">Section D: Attainment & Continuous Improvement</div>
    
    <h3>1. Action Taken Report</h3>
    <p>{!! nl2br(e($courseFile->sectionD->action_taken_report ?? 'No corrective actions required.')) !!}</p>

    <h3>2. Course Committee Minutes</h3>
    <p>{!! nl2br(e($courseFile->sectionD->committee_minutes ?? 'No committee minutes recorded.')) !!}</p>

    <div class="placeholder-box">
        <h3 style="margin-top:0;">[ATTACHMENT REQUIRED]</h3>
        <p>Please insert CO/PO/PSO Attainment Calculation Charts & Reports here.</p>
    </div>

    <div style="margin-top: 100px;">
        <table style="border: none;">
            <tr>
                <td style="border: none; text-align: left;">
                    ___________________________<br>
                    <strong>Signature of Faculty</strong>
                </td>
                <td style="border: none; text-align: right;">
                    ___________________________<br>
                    <strong>Signature of HOD</strong>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
