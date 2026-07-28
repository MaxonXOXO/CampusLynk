<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\R26ClassManagement;
use App\Models\Student;
use App\Models\LessonPlan;
use App\Models\R26DrawingCourseFile;
use App\Models\R26DrawingSlotEvaluation;
use App\Models\R26DrawingPracticalTest;
use App\Models\R26DrawingOeeEvaluation;
use App\Models\R26DrawingEseMark;

class R26VirtualClassroomDrawingController extends Controller
{
    /**
     * Main Virtual Drawing Hall Dashboard (Lab Model - 60 CIE + 40 ESE)
     */
    public function show($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Fetch classroom details
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }
        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }

        // Enrolled Students Query
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Fetch or Create Drawing Course File Record
        $drawingCourseFile = R26DrawingCourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'program' => $classroom->department ?? 'Engineering',
                'course_title' => $batchSubject->subject_name,
                'course_code' => $batchSubject->subject_code,
                'semester' => $classroom->current_semester ?? 'I',
                'type_of_course' => 'Lab',
                'teaching_scheme' => '0:0:3:0',
                'contact_hours' => 45,
                'credits' => 1.5,
                'cie_marks' => 60,
                'ese_marks' => 40,
                'parsed_cos' => [
                    ['id' => 'CO1', 'description' => 'Construct geometrical figures and illustrate development of surfaces.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO2', 'description' => 'Interpret projections of points and lines, orthographic projections and sectional views.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO3', 'description' => 'Familiarization in using CAD software and 2D drafting tools.', 'cognitive_level' => 'Apply'],
                    ['id' => 'CO4', 'description' => 'Develop orthographic projections and sectional views in CAD software.', 'cognitive_level' => 'Apply']
                ],
                'parsed_modules' => [
                    ['module_id' => 'I', 'title' => 'Engineering Graphics Fundamentals & Conic Sections', 'hours' => 9.0, 'content' => 'Regular Polygons, Ellipse, Parabola, Development of Surfaces'],
                    ['module_id' => 'II', 'title' => 'Projections & Sectional Views', 'hours' => 12.0, 'content' => 'Projections of Points, Lines, Orthographic Projections, Sectional Views'],
                    ['module_id' => 'III', 'title' => 'Computer Aided Drafting (CAD) Basics', 'hours' => 12.0, 'content' => 'CAD editor, Draw/Modify commands, Line properties, Text, Dimensions'],
                    ['module_id' => 'IV', 'title' => 'CAD 2D Drafting & Plotting', 'hours' => 12.0, 'content' => 'Orthographic components in CAD, Sectional views in CAD, Printing/Plotting']
                ],
                'parsed_exercises' => [
                    ['exercise_no' => 'EXE-01', 'module' => 'Module I', 'title' => 'Drawing Regular Polygons (Pentagon & Hexagon)', 'co_id' => 'CO1', 'hours' => 3.0],
                    ['exercise_no' => 'EXE-02', 'module' => 'Module I', 'title' => 'Drawing Conic Sections (Ellipse by Rectangular & Concentric Circle Method)', 'co_id' => 'CO1', 'hours' => 3.0],
                    ['exercise_no' => 'EXE-03', 'module' => 'Module I', 'title' => 'Drawing Development of Surfaces (Prism & Cylinder)', 'co_id' => 'CO1', 'hours' => 3.0],
                    ['exercise_no' => 'EXE-04', 'module' => 'Module II', 'title' => 'Drawing Basic Projections of Points & Lines in Quadrants', 'co_id' => 'CO2', 'hours' => 6.0],
                    ['exercise_no' => 'EXE-05', 'module' => 'Module II', 'title' => 'Drawing Orthographic Projections & Sectional Views of Engineering Objects', 'co_id' => 'CO2', 'hours' => 6.0],
                    ['exercise_no' => 'EXE-06', 'module' => 'Module III', 'title' => 'CAD Software Basics & Familiarization of Draw and Modify Commands', 'co_id' => 'CO3', 'hours' => 6.0],
                    ['exercise_no' => 'EXE-07', 'module' => 'Module III', 'title' => 'CAD Line Properties, Layers, Text, and Dimensioning Practice', 'co_id' => 'CO3', 'hours' => 6.0],
                    ['exercise_no' => 'EXE-08', 'module' => 'Module IV', 'title' => 'Developing Orthographic Views of Components in CAD', 'co_id' => 'CO4', 'hours' => 6.0],
                    ['exercise_no' => 'EXE-09', 'module' => 'Module IV', 'title' => 'Developing Sectional Views & Plotting CAD Drawings', 'co_id' => 'CO4', 'hours' => 6.0]
                ],
                'parsed_copo' => [
                    'credit' => 1.5,
                    'l_t_p_r' => '0:0:3:0',
                    'cie_marks' => 60,
                    'ese_marks' => 40,
                    'total_hours' => 45,
                    'mappings' => [
                        'CO1' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'3', 'PO4'=>'-', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'-'],
                        'CO2' => ['PO1'=>'3', 'PO2'=>'3', 'PO3'=>'2', 'PO4'=>'-', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'-'],
                        'CO3' => ['PO1'=>'2', 'PO2'=>'-', 'PO3'=>'2', 'PO4'=>'3', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'-'],
                        'CO4' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'3', 'PO4'=>'3', 'PO5'=>'-', 'PO6'=>'-', 'PO7'=>'-', 'PO8'=>'-', 'PO9'=>'-', 'PO10'=>'-', 'PO11'=>'-']
                    ]
                ],
                'parsed_textbooks' => [
                    'Engineering Graphics with AUTOCAD - P I Varghese (VIP Publishers)',
                    'Engineering Graphics - K. C John (PHI Learning)',
                    'Engineering Drawing with CAD Applications - N. D. Bhatt & V. M. Panchal'
                ]
            ]
        );

        // Fetch / Auto-Generate 45-Hour Drawing Lab Lesson Plan
        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        if ($lessonPlans->count() < 15) {
            $this->generate45HourLabLessonPlan($batchSubject, $drawingCourseFile);
            $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
                ->orderBy('day_no', 'asc')
                ->get();
        }

        // Fetch Attendance Records
        $attendanceData = DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        // Fetch Continuous Practical Evaluations (CE - Max 50 per slot)
        $slotEvals = R26DrawingSlotEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Fetch Practical Tests Marks (CA1 & CA2 - Max 40)
        $practicalTests = R26DrawingPracticalTest::where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Fetch Open-Ended Experiment Evaluations (OEE - Max 50)
        $oeeEvals = R26DrawingOeeEvaluation::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Fetch ESE Marks (Max 40)
        $eseMarks = R26DrawingEseMark::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        // Map Students & Compute CIE (60) + ESE (40) = Total (100)
        $studentResults = $students->map(function ($student) use (
            $attendanceData,
            $slotEvals,
            $practicalTests,
            $oeeEvals,
            $eseMarks,
            $batchSubject
        ) {
            $regNo = $student->reg_no;

            // 1. Attendance Marks (Max 5)
            $stAtt = $attendanceData->get($regNo, collect());
            $totalAtt = $stAtt->count();
            $present = $stAtt->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAtt > 0 ? round(($present / $totalAtt) * 100, 2) : 100.00;

            if ($attPercentage >= 90) { $attMarks = 5; }
            elseif ($attPercentage >= 80) { $attMarks = 4; }
            elseif ($attPercentage >= 75) { $attMarks = 3; }
            elseif ($attPercentage >= 70) { $attMarks = 2; }
            elseif ($attPercentage >= 65) { $attMarks = 1; }
            else { $attMarks = 0; }

            // 2. Continuous Evaluation (CE - Max 30 CIE Marks)
            $stSlots = $slotEvals->get($regNo, collect());
            $avgSlotScore50 = $stSlots->avg('total_score_50') ?: 0.00;
            $ceMarks = round((($avgSlotScore50 / 50.0) * 30.0) * 2) / 2;

            // 3. Practical Tests CA1 & CA2 (Max 15 CIE Marks: CA1 [7.5] + CA2 [7.5])
            $stTests = $practicalTests->get($regNo, collect());
            $ca1 = $stTests->where('test_no', 'CA1')->first();
            $ca2 = $stTests->where('test_no', 'CA2')->first();
            $ca1Score = ($ca1 && !$ca1->is_absent) ? $ca1->total_score_40 : 0.00;
            $ca2Score = ($ca2 && !$ca2->is_absent) ? $ca2->total_score_40 : 0.00;
            
            $ca1Marks = round((($ca1Score / 40.0) * 7.5) * 2) / 2;
            $ca2Marks = round((($ca2Score / 40.0) * 7.5) * 2) / 2;
            $practicalTestMarks = $ca1Marks + $ca2Marks;

            // 4. Open-Ended Experiment (OEE - Max 10 CIE Marks)
            $stOee = $oeeEvals->get($regNo);
            $oeeScore50 = $stOee ? floatval($stOee->total_score_50) : 0.00;
            $oeeMarks = round((($oeeScore50 / 50.0) * 10.0) * 2) / 2;

            // Total CIE Marks (Max 60)
            $totalCieMarks = round(($attMarks + $ceMarks + $practicalTestMarks + $oeeMarks) * 2) / 2;
            if ($totalCieMarks > 60.0) $totalCieMarks = 60.0;

            // ESE Marks (Max 40)
            $stEse = $eseMarks->get($regNo);
            $partA = $stEse ? floatval($stEse->part_a_mcq) : 0.00;
            $partB = $stEse ? floatval($stEse->part_b_cad) : 0.00;
            $partC = $stEse ? floatval($stEse->part_c_viva) : 0.00;
            $partD = $stEse ? floatval($stEse->part_d_record) : 0.00;
            $totalEse = ($stEse && !$stEse->is_absent) ? ($partA + $partB + $partC + $partD) : 0.00;

            $totalCourseMarks = $totalCieMarks + $totalEse;

            // Pass Criteria:
            // 1. Min 40% in ESE = 16 / 40
            // 2. Min 40% in Total Combined = 40 / 100
            $isEsePass = ($totalEse >= 16.0);
            $isTotalPass = ($totalCourseMarks >= 40.0);
            $isPassed = ($isEsePass && $isTotalPass);

            return [
                'reg_no' => $student->reg_no,
                'sbte_reg_no' => $student->sbte_reg_no ?? $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'att_percentage' => $attPercentage,
                'att_marks' => $attMarks,
                'ce_marks' => $ceMarks,
                'ca1_score' => $ca1Score,
                'ca2_score' => $ca2Score,
                'practical_test_marks' => $practicalTestMarks,
                'oee_score' => $oeeScore50,
                'oee_marks' => $oeeMarks,
                'total_cie_marks' => $totalCieMarks,
                'ese_part_a' => $partA,
                'ese_part_b' => $partB,
                'ese_part_c' => $partC,
                'ese_part_d' => $partD,
                'total_ese' => $totalEse,
                'total_course_marks' => $totalCourseMarks,
                'is_passed' => $isPassed
            ];
        });

        // Assigned Staff & HOD
        $assignedStaff = DB::table('subject_staff_assignments')
            ->join('staff_profiles', 'subject_staff_assignments.staff_mobile_no', '=', 'staff_profiles.mobile_no')
            ->where('subject_staff_assignments.batch_subject_id', $subjectId)
            ->select('staff_profiles.name', 'staff_profiles.designation', 'staff_profiles.mobile_no')
            ->get();

        $deptCode = $classroom->department ?? $classroom->branch ?? '';
        $hod = DB::table('staff_profiles')
            ->where(function($q) use ($deptCode) {
                if ($deptCode) {
                    $q->where('branch', $deptCode);
                }
            })
            ->where('designation', 'HOD')
            ->select('name', 'designation', 'mobile_no')
            ->first();

        // Surveys for Indirect Attainment
        $exitSurvey = DB::table('course_exit_surveys')
            ->where('batch_subject_id', $subjectId)
            ->orderBy('created_at', 'desc')
            ->first();

        $exitSurveyResponses = collect();
        if ($exitSurvey) {
            $exitSurveyResponses = DB::table('student_course_exit_responses')
                ->where('exit_survey_id', $exitSurvey->id)
                ->get();
        }

        $midSemSurvey = DB::table('mid_semester_surveys')
            ->where('batch_subject_id', $subjectId)
            ->orderBy('created_at', 'desc')
            ->first();

        $midSemResponses = collect();
        if ($midSemSurvey) {
            $midSemResponses = DB::table('student_survey_responses')
                ->where('survey_id', $midSemSurvey->id)
                ->get();
        }

        // CO-PO Matrix & Attainment Calculation
        $copoPayload = $drawingCourseFile->parsed_copo ?: [];
        $mappings = $copoPayload['mappings'] ?? [];

        if (empty($mappings)) {
            $mappings = [
                'CO1' => ['PO1'=>'3','PO2'=>'2','PO3'=>'3','PO4'=>'-','PO5'=>'-','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-'],
                'CO2' => ['PO1'=>'3','PO2'=>'3','PO3'=>'2','PO4'=>'-','PO5'=>'-','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-'],
                'CO3' => ['PO1'=>'2','PO2'=>'-','PO3'=>'2','PO4'=>'3','PO5'=>'-','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-'],
                'CO4' => ['PO1'=>'3','PO2'=>'2','PO3'=>'3','PO4'=>'3','PO5'=>'-','PO6'=>'-','PO7'=>'-','PO8'=>'-','PO9'=>'-','PO10'=>'-','PO11'=>'-']
            ];
        }

        $totalStudents = max(1, $studentResults->count());
        $directStats = [];
        $indirectStats = [];
        $combinedStats = [];

        foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
            $attainedCount = $studentResults->filter(function($s) {
                return $s['total_course_marks'] >= 50.0; // 50% target
            })->count();

            $percentage = ($attainedCount / $totalStudents) * 100;
            $directLevel = ($percentage >= 70) ? 3.0 : (($percentage >= 60) ? 2.0 : (($percentage >= 50) ? 1.0 : 0.0));
            
            $directStats[$coTag] = [
                'count' => $attainedCount,
                'percentage' => round($percentage, 1),
                'level' => $directLevel
            ];

            // Indirect attainment from surveys
            $indirectLevel = 2.5;
            $indirectAvg = 2.50;
            $indirectPct = 83.3;

            if ($exitSurveyResponses->count() > 0) {
                if ($coTag === 'CO1') {
                    $indirectAvg = ($exitSurveyResponses->avg('co1_q1') + $exitSurveyResponses->avg('co1_q2')) / 2;
                } elseif ($coTag === 'CO2') {
                    $indirectAvg = ($exitSurveyResponses->avg('co2_q3') + $exitSurveyResponses->avg('co2_q4')) / 2;
                } elseif ($coTag === 'CO3') {
                    $indirectAvg = ($exitSurveyResponses->avg('co3_q5') + $exitSurveyResponses->avg('co3_q6')) / 2;
                } else {
                    $indirectAvg = ($exitSurveyResponses->avg('co4_q7') + $exitSurveyResponses->avg('co4_q8') + $exitSurveyResponses->avg('co4_q9')) / 3;
                }
                $indirectPct = ($indirectAvg / 3.0) * 100;
                $indirectLevel = ($indirectPct >= 70) ? 3.0 : (($indirectPct >= 60) ? 2.0 : (($indirectPct >= 50) ? 1.0 : 0.0));
            }

            $indirectStats[$coTag] = [
                'avg_score' => round($indirectAvg, 2),
                'percentage' => round($indirectPct, 1),
                'level' => $indirectLevel
            ];

            $combinedLevel = round((0.80 * $directLevel) + (0.20 * $indirectLevel), 2);
            $combinedStats[$coTag] = $combinedLevel;
        }

        $poAttainments = [];
        for ($p = 1; $p <= 11; $p++) {
            $poName = "PO" . $p;
            $sumWeight = 0;
            $sumAttainment = 0;

            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $correlation = isset($mappings[$coTag][$poName]) && is_numeric($mappings[$coTag][$poName]) ? (int)$mappings[$coTag][$poName] : 0;
                if ($correlation > 0) {
                    $sumWeight += $correlation;
                    $sumAttainment += $combinedStats[$coTag] * $correlation;
                }
            }

            $poAttainments[$poName] = [
                'value' => $sumWeight > 0 ? round($sumAttainment / $sumWeight, 2) : 0.0,
                'weight' => $sumWeight
            ];
        }

        return view('r26_drawing.virtual_classroom_drawing', compact(
            'batchSubject',
            'classroom',
            'students',
            'drawingCourseFile',
            'lessonPlans',
            'studentResults',
            'slotEvals',
            'practicalTests',
            'oeeEvals',
            'eseMarks',
            'assignedStaff',
            'hod',
            'mappings',
            'directStats',
            'indirectStats',
            'combinedStats',
            'poAttainments',
            'midSemSurvey',
            'exitSurvey',
            'midSemResponses',
            'exitSurveyResponses'
        ));
    }

    /**
     * Upload & Parse Drawing Syllabus PDF
     */
    public function uploadSyllabus(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
        }

        $request->validate([
            'syllabus_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $file = $request->file('syllabus_file');
            $path = $file->store('r26_drawing_syllabi', 'public');

            // Execute Python parser
            $pyPath = base_path('app/Services/r26_drawing_syllabus_parser.py');
            $fullPdfPath = storage_path('app/public/' . $path);
            $command = "PYTHONIOENCODING=utf-8 /usr/bin/python3 " . escapeshellarg($pyPath) . " " . escapeshellarg($fullPdfPath) . " 2>&1";
            $jsonOutput = shell_exec($command);

            $parsedResult = json_decode($jsonOutput, true);
            if (!$parsedResult || ($parsedResult['status'] ?? '') === 'ERROR') {
                $errDetail = $parsedResult['message'] ?? trim($jsonOutput ?: 'No output returned from Python parser.');
                throw new \Exception('Failed to parse Drawing syllabus PDF: ' . $errDetail);
            }

            $data = $parsedResult['data'];

            R26DrawingCourseFile::updateOrCreate(
                ['batch_subject_id' => $subjectId],
                [
                    'syllabus_pdf_path' => $path,
                    'program' => $data['program'] ?? 'Engineering',
                    'course_title' => $data['course_title'] ?? $batchSubject->subject_name,
                    'course_code' => $data['course_code'] ?? $batchSubject->subject_code,
                    'semester' => $data['semester'] ?? 'I',
                    'type_of_course' => 'Lab',
                    'teaching_scheme' => $data['teaching_scheme'] ?? '0:0:3:0',
                    'contact_hours' => $data['total_hours'] ?? 45,
                    'credits' => $data['credits'] ?? 1.5,
                    'cie_marks' => $data['cie_marks'] ?? 60,
                    'ese_marks' => $data['ese_marks'] ?? 40,
                    'parsed_cos' => $data['cos'] ?? [],
                    'parsed_modules' => $data['modules'] ?? [],
                    'parsed_exercises' => $data['exercises'] ?? [],
                    'parsed_copo' => [
                        'credit' => $data['credits'] ?? 1.5,
                        'l_t_p_r' => $data['teaching_scheme'] ?? '0:0:3:0',
                        'cie_marks' => $data['cie_marks'] ?? 60,
                        'ese_marks' => $data['ese_marks'] ?? 40,
                        'total_hours' => $data['total_hours'] ?? 45,
                        'mappings' => $data['copo_matrix'] ?? []
                    ],
                    'parsed_textbooks' => [
                        'Engineering Graphics with AUTOCAD - P I Varghese',
                        'Engineering Graphics - K. C John',
                        'Engineering Drawing with CAD Applications - N. D. Bhatt'
                    ]
                ]
            );

            // Automatically regenerate Lesson Plan based on parsed syllabus exercises
            $this->generate45HourLabLessonPlan($batchSubject, $drawingCourseFile);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Drawing syllabus uploaded and parsed successfully!',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Save Continuous Practical Evaluation (CE - Max 50 per slot)
     */
    public function saveSlotMarks(Request $request, $subjectId)
    {
        $request->validate([
            'exercise_no' => 'required|string',
            'exercise_title' => 'nullable|string',
            'marks_data' => 'required|array'
        ]);

        $exNo = $request->input('exercise_no');
        $exTitle = $request->input('exercise_title');
        $marksData = $request->input('marks_data');
        $assessorMobile = Session::get('mobileNo');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $p1 = floatval($row['prep_punctuality'] ?? 0);
            $p2 = floatval($row['setup_procedure'] ?? 0);
            $p3 = floatval($row['observation_recording'] ?? 0);
            $p4 = floatval($row['analysis_interpretation'] ?? 0);
            $p5 = floatval($row['viva_voce'] ?? 0);
            $p6 = floatval($row['workmanship_discipline'] ?? 0);
            $total50 = $p1 + $p2 + $p3 + $p4 + $p5 + $p6;

            R26DrawingSlotEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'exercise_no' => $exNo,
                    'reg_no' => $regNo
                ],
                [
                    'exercise_title' => $exTitle,
                    'prep_punctuality' => $p1,
                    'setup_procedure' => $p2,
                    'observation_recording' => $p3,
                    'analysis_interpretation' => $p4,
                    'viva_voce' => $p5,
                    'workmanship_discipline' => $p6,
                    'total_score_50' => $total50,
                    'assessor_mobile_no' => $assessorMobile
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Slot evaluation marks saved successfully!']);
    }

    /**
     * Save Practical Tests CA1 & CA2 (Max 40)
     */
    public function savePracticalTestMarks(Request $request, $subjectId)
    {
        $request->validate([
            'test_no' => 'required|string',
            'marks_data' => 'required|array'
        ]);

        $testNo = $request->input('test_no');
        $marksData = $request->input('marks_data');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $w = floatval($row['writeup_procedure'] ?? 0);
            $s = floatval($row['setup_execution'] ?? 0);
            $o = floatval($row['observation_result'] ?? 0);
            $v = floatval($row['viva_voce'] ?? 0);
            $r = floatval($row['record_completion'] ?? 0);
            $total40 = $w + $s + $o + $v + $r;
            $isAbsent = !empty($row['is_absent']);

            R26DrawingPracticalTest::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'test_no' => $testNo,
                    'reg_no' => $regNo
                ],
                [
                    'writeup_procedure' => $w,
                    'setup_execution' => $s,
                    'observation_result' => $o,
                    'viva_voce' => $v,
                    'record_completion' => $r,
                    'total_score_40' => $isAbsent ? 0.00 : $total40,
                    'is_absent' => $isAbsent
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Practical Test marks saved successfully!']);
    }

    /**
     * Save Open-Ended Experiment Marks (OEE - Max 50)
     */
    public function saveOeeMarks(Request $request, $subjectId)
    {
        $request->validate([
            'marks_data' => 'required|array'
        ]);

        $marksData = $request->input('marks_data');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $m1 = floatval($row['originality_relevance'] ?? 0);
            $m2 = floatval($row['objectives_plan'] ?? 0);
            $m3 = floatval($row['execution_recording'] ?? 0);
            $m4 = floatval($row['analysis_presentation'] ?? 0);
            $m5 = floatval($row['teamwork_innovation'] ?? 0);
            $total50 = $m1 + $m2 + $m3 + $m4 + $m5;

            R26DrawingOeeEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no' => $regNo
                ],
                [
                    'originality_relevance' => $m1,
                    'objectives_plan' => $m2,
                    'execution_recording' => $m3,
                    'analysis_presentation' => $m4,
                    'teamwork_innovation' => $m5,
                    'total_score_50' => $total50
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Open-ended experiment marks saved successfully!']);
    }

    /**
     * Save End Semester Exam Marks (ESE - Max 40)
     */
    public function saveEseMarks(Request $request, $subjectId)
    {
        $request->validate([
            'marks_data' => 'required|array'
        ]);

        $marksData = $request->input('marks_data');

        foreach ($marksData as $row) {
            $regNo = $row['reg_no'];
            $pa = floatval($row['part_a_mcq'] ?? 0);
            $pb = floatval($row['part_b_cad'] ?? 0);
            $pc = floatval($row['part_c_viva'] ?? 0);
            $pd = floatval($row['part_d_record'] ?? 0);
            $total40 = $pa + $pb + $pc + $pd;
            $isAbsent = !empty($row['is_absent']);

            R26DrawingEseMark::updateOrCreate(
                [
                    'batch_subject_id' => $subjectId,
                    'reg_no' => $regNo
                ],
                [
                    'part_a_mcq' => $pa,
                    'part_b_cad' => $pb,
                    'part_c_viva' => $pc,
                    'part_d_record' => $pd,
                    'total_ese_40' => $isAbsent ? 0.00 : $total40,
                    'is_absent' => $isAbsent
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'ESE CAD practical marks saved successfully!']);
    }

    /**
     * Auto Generate 45-Hour Drawing Lab Lesson Plan
     */
    private function generate45HourLabLessonPlan($batchSubject, $drawingFile)
    {
        LessonPlan::where('batch_subject_id', $batchSubject->id)->delete();

        $exercises = $drawingFile->parsed_exercises ?: [];
        $dayNo = 1;

        foreach ($exercises as $ex) {
            $hrs = intval($ex['hours'] ?? 3);
            for ($h = 1; $h <= $hrs; $h++) {
                LessonPlan::create([
                    'batch_subject_id' => $batchSubject->id,
                    'day_no' => $dayNo,
                    'planned_date' => now()->addDays($dayNo)->toDateString(),
                    'topic_content' => $ex['title'] . " (Hour {$h}/{$hrs})",
                    'slo' => "Demonstrate drafting accuracy for " . $ex['title'],
                    'co_tag' => $ex['co_id'] ?? 'CO1',
                    'taxonomy' => 'Apply',
                    'mode' => 'P',
                    'pedagogy' => 'Drawing Lab Slot (P)',
                    'status' => 'Pending'
                ]);
                $dayNo++;
            }
        }
    }
}
