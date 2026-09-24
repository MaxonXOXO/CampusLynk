<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BatchSubject;
use App\Models\Student;
use App\Models\R26PracticalExperimentEvaluation;
use App\Models\R26OpenEndedEvaluation;
use App\Models\R26PracticalSeriesEvaluation;
use App\Models\R26StudentLabBatch;
use App\Models\PracticalExperiment;
use App\Models\PracticalExperimentMark;
use App\Models\PracticalEvaluation;
use App\Models\PracticalTest;
use App\Models\PracticalTestMark;
use DB;

class VirtualClassroomPracticalController extends Controller
{
    private function getStaff()
    {
        $userId = session('userId') ?? \Illuminate\Support\Facades\Session::get('userId');
        if (!$userId) return null;
        return \App\Models\StaffProfile::where('mobile_no', $userId)->first();
    }

    /**
     * Display Practical Virtual Classroom
     */
    public function show($batchSubjectId)
    {
        $staff = $this->getStaff();
        if (!$staff) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::with(['classroom', 'courseFile'])->findOrFail($batchSubjectId);

        // Fetch students enrolled in this classroom
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        // Fetch lab batches designations
        $labBatches = R26StudentLabBatch::where('batch_subject_id', $batchSubjectId)
            ->get()
            ->keyBy('reg_no');

        // Fetch existing experiment logs (Table 2.2)
        $experimentLogs = R26PracticalExperimentEvaluation::where('batch_subject_id', $batchSubjectId)
            ->get()
            ->groupBy('experiment_no');

        // Fetch open ended evaluations (Table 2.3)
        $openEndedLogs = R26OpenEndedEvaluation::where('batch_subject_id', $batchSubjectId)
            ->get()
            ->keyBy('reg_no');

        // Fetch series exam evaluations (Table 3.1)
        $seriesExamLogs = R26PracticalSeriesEvaluation::where('batch_subject_id', $batchSubjectId)
            ->get()
            ->groupBy('series_no');

        // Calculate attendance percentages & Table 2.1 marks
        $attendanceMarks = [];
        foreach ($students as $student) {
            $totalClasses = DB::table('student_attendance')
                ->where('subject_code', $batchSubject->subject_code)
                ->where('reg_no', $student->reg_no)
                ->count();

            $presentClasses = DB::table('student_attendance')
                ->where('subject_code', $batchSubject->subject_code)
                ->where('reg_no', $student->reg_no)
                ->whereIn('status', ['Present', 'Late'])
                ->count();

            $pct = $totalClasses > 0 ? round(($presentClasses / $totalClasses) * 100, 2) : 100.00;
            
            // Table 2.1 Rules
            $mark = 0;
            if ($pct >= 90) $mark = 5;
            elseif ($pct >= 80) $mark = 4;
            elseif ($pct >= 75) $mark = 3;
            elseif ($pct >= 70) $mark = 2;
            elseif ($pct >= 65) $mark = 1;
            else $mark = 0;

            $attendanceMarks[$student->reg_no] = [
                'percentage' => $pct,
                'mark' => $mark
            ];
        }

        // Pre-calculate consolidated scores for all students (Table 1.2 Breakdown out of 60)
        $consolidatedScores = [];
        foreach ($students as $student) {
            $regNo = $student->reg_no;

            // 1. Lab Work (Table 2.2 Continuous Evaluation) - average out of 50, scaled to 30
            $studentExpScores = [];
            foreach ($experimentLogs as $expNo => $logs) {
                $log = $logs->where('reg_no', $regNo)->first();
                if ($log) {
                    $studentExpScores[] = floatval($log->total_score_50);
                }
            }
            $avgExp50 = count($studentExpScores) > 0 ? (array_sum($studentExpScores) / count($studentExpScores)) : 0;
            $scaledLabWork30 = round(($avgExp50 / 50) * 30, 2);

            // 2. Open-Ended Project (Table 2.3) - score out of 50, scaled to 10
            $openLog = $openEndedLogs->get($regNo);
            $openScore50 = $openLog ? floatval($openLog->total_score_50) : 0;
            $scaledOpenEnded10 = round(($openScore50 / 50) * 10, 2);

            // 3. Series Exams (Table 3.1) - average of series 1 and series 2 out of 40, scaled to 15
            $seriesScores = [];
            foreach (['Series 1', 'Series 2'] as $sName) {
                if (isset($seriesExamLogs[$sName])) {
                    $log = $seriesExamLogs[$sName]->where('reg_no', $regNo)->first();
                    if ($log) {
                        $seriesScores[] = floatval($log->total_score_40);
                    }
                }
            }
            $avgSeries40 = count($seriesScores) > 0 ? (array_sum($seriesScores) / count($seriesScores)) : 0;
            $scaledSeries15 = round(($avgSeries40 / 40) * 15, 2);

            // 4. Attendance Marks (Table 2.1) - out of 5
            $attMark5 = $attendanceMarks[$regNo]['mark'] ?? 5;

            // Grand Total CIA (out of 60)
            $totalCIA = $scaledLabWork30 + $scaledOpenEnded10 + $scaledSeries15 + $attMark5;

            $consolidatedScores[$regNo] = [
                'raw_exp_avg' => round($avgExp50, 2),
                'scaled_lab_work_30' => $scaledLabWork30,
                'raw_open_ended' => round($openScore50, 2),
                'scaled_open_ended_10' => $scaledOpenEnded10,
                'raw_series_avg' => round($avgSeries40, 2),
                'scaled_series_15' => $scaledSeries15,
                'attendance_mark_5' => $attMark5,
                'total_cia_60' => round($totalCIA, 2)
            ];
        }

        return view('virtual_classroom_practical', compact(
            'batchSubject',
            'students',
            'labBatches',
            'experimentLogs',
            'openEndedLogs',
            'seriesExamLogs',
            'attendanceMarks',
            'consolidatedScores'
        ));
    }

    /**
     * Save Table 2.2 Experiment Marks
     */
    public function saveExperimentMarks(Request $request, $batchSubjectId)
    {
        $staff = $this->getStaff();
        $expNo = $request->input('experiment_no', 'Exp 1');
        $title = $request->input('title', '');
        $marksData = $request->input('marks', []);

        foreach ($marksData as $regNo => $criteria) {
            $c1 = floatval($criteria['c1'] ?? 0); // prep (max 10)
            $c2 = floatval($criteria['c2'] ?? 0); // setup (max 10)
            $c3 = floatval($criteria['c3'] ?? 0); // obs (max 5)
            $c4 = floatval($criteria['c4'] ?? 0); // analysis (max 10)
            $c5 = floatval($criteria['c5'] ?? 0); // viva (max 10)
            $c6 = floatval($criteria['c6'] ?? 0); // teamwork (max 5)
            $total = $c1 + $c2 + $c3 + $c4 + $c5 + $c6; // max 50

            R26PracticalExperimentEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $batchSubjectId,
                    'experiment_no' => $expNo,
                    'reg_no' => $regNo,
                ],
                [
                    'title' => $title,
                    'prep_punctuality' => $c1,
                    'setup_procedure' => $c2,
                    'observation_recording' => $c3,
                    'analysis_interpretation' => $c4,
                    'viva_voce' => $c5,
                    'teamwork_discipline' => $c6,
                    'total_score_50' => $total,
                    'assessor_mobile_no' => $staff->mobile_no ?? null,
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Experiment marks saved successfully!']);
    }

    /**
     * Save Table 2.3 Open Ended Project Marks
     */
    public function saveOpenEndedMarks(Request $request, $batchSubjectId)
    {
        $staff = $this->getStaff();
        $marksData = $request->input('marks', []);

        foreach ($marksData as $regNo => $criteria) {
            $c1 = floatval($criteria['c1'] ?? 0); // max 10
            $c2 = floatval($criteria['c2'] ?? 0); // max 10
            $c3 = floatval($criteria['c3'] ?? 0); // max 10
            $c4 = floatval($criteria['c4'] ?? 0); // max 10
            $c5 = floatval($criteria['c5'] ?? 0); // max 10
            $total = $c1 + $c2 + $c3 + $c4 + $c5; // max 50

            R26OpenEndedEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $batchSubjectId,
                    'reg_no' => $regNo,
                ],
                [
                    'project_title' => $criteria['title'] ?? 'Open-ended Project',
                    'originality_relevance' => $c1,
                    'objectives_plan' => $c2,
                    'execution_recording' => $c3,
                    'analysis_presentation' => $c4,
                    'teamwork_innovation' => $c5,
                    'total_score_50' => $total,
                    'assessor_mobile_no' => $staff->mobile_no ?? null,
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Open-ended experiment evaluation saved!']);
    }

    /**
     * Save Table 3.1 Series Exam Marks
     */
    public function saveSeriesExamMarks(Request $request, $batchSubjectId)
    {
        $staff = $this->getStaff();
        $seriesNo = $request->input('series_no', 'Series 1');
        $marksData = $request->input('marks', []);

        foreach ($marksData as $regNo => $criteria) {
            $c1 = floatval($criteria['c1'] ?? 0); // max 10
            $c2 = floatval($criteria['c2'] ?? 0); // max 10
            $c3 = floatval($criteria['c3'] ?? 0); // max 8
            $c4 = floatval($criteria['c4'] ?? 0); // max 8
            $c5 = floatval($criteria['c5'] ?? 0); // max 4
            $total = $c1 + $c2 + $c3 + $c4 + $c5; // max 40

            R26PracticalSeriesEvaluation::updateOrCreate(
                [
                    'batch_subject_id' => $batchSubjectId,
                    'series_no' => $seriesNo,
                    'reg_no' => $regNo,
                ],
                [
                    'writeup_procedure' => $c1,
                    'setup_execution' => $c2,
                    'observation_result' => $c3,
                    'viva_voce' => $c4,
                    'record_completion' => $c5,
                    'total_score_40' => $total,
                    'assessor_mobile_no' => $staff->mobile_no ?? null,
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Practical Series Exam marks saved!']);
    }

    /**
     * Assign student to specific lab batch
     */
    public function assignLabBatch(Request $request, $batchSubjectId)
    {
        $regNo = $request->input('reg_no');
        $labBatch = $request->input('lab_batch'); // 'Batch A', 'Batch B', or ''

        if (empty($labBatch)) {
            R26StudentLabBatch::where('batch_subject_id', $batchSubjectId)
                ->where('reg_no', $regNo)
                ->delete();
        } else {
            R26StudentLabBatch::updateOrCreate(
                [
                    'batch_subject_id' => $batchSubjectId,
                    'reg_no' => $regNo
                ],
                [
                    'lab_batch' => $labBatch
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Lab batch assigned successfully!']);
    }

    /**
     * Print R2026 Consolidated practical report
     */
    public function printReport($batchSubjectId)
    {
        $batchSubject = BatchSubject::with(['classroom', 'courseFile'])->findOrFail($batchSubjectId);

        if ($batchSubject->syllabus_revision_code === 'REV2021' || !str_starts_with(strtoupper($batchSubject->subject_code ?? ''), '26')) {
            return view('classroom_practical_report_print', $this->getPracticalReportData($batchSubjectId));
        }

        // Fetch students enrolled in this classroom
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        // Fetch lab batches
        $labBatches = R26StudentLabBatch::where('batch_subject_id', $batchSubjectId)
            ->get()
            ->keyBy('reg_no');

        // Fetch existing experiment logs (Table 2.2)
        $experimentLogs = R26PracticalExperimentEvaluation::where('batch_subject_id', $batchSubjectId)
            ->get()
            ->groupBy('experiment_no');

        // Fetch open ended evaluations (Table 2.3)
        $openEndedLogs = R26OpenEndedEvaluation::where('batch_subject_id', $batchSubjectId)
            ->get()
            ->keyBy('reg_no');

        // Fetch series exam evaluations (Table 3.1)
        $seriesExamLogs = R26PracticalSeriesEvaluation::where('batch_subject_id', $batchSubjectId)
            ->get()
            ->groupBy('series_no');

        // Calculate attendance percentages & Table 2.1 marks
        $attendanceMarks = [];
        foreach ($students as $student) {
            $totalClasses = DB::table('student_attendance')
                ->where('subject_code', $batchSubject->subject_code)
                ->where('reg_no', $student->reg_no)
                ->count();

            $presentClasses = DB::table('student_attendance')
                ->where('subject_code', $batchSubject->subject_code)
                ->where('reg_no', $student->reg_no)
                ->whereIn('status', ['Present', 'Late'])
                ->count();

            $pct = $totalClasses > 0 ? round(($presentClasses / $totalClasses) * 100, 2) : 100.00;
            
            // Table 2.1 Rules
            $mark = 0;
            if ($pct >= 90) $mark = 5;
            elseif ($pct >= 80) $mark = 4;
            elseif ($pct >= 75) $mark = 3;
            elseif ($pct >= 70) $mark = 2;
            elseif ($pct >= 65) $mark = 1;
            else $mark = 0;

            $attendanceMarks[$student->reg_no] = [
                'percentage' => $pct,
                'mark' => $mark
            ];
        }

        // Pre-calculate consolidated scores for all students (Table 1.2 Breakdown out of 60)
        $consolidatedScores = [];
        foreach ($students as $student) {
            $regNo = $student->reg_no;

            // 1. Lab Work (Table 2.2 Continuous Evaluation) - average out of 50, scaled to 30
            $studentExpScores = [];
            foreach ($experimentLogs as $expNo => $logs) {
                $log = $logs->where('reg_no', $regNo)->first();
                if ($log) {
                    $studentExpScores[] = floatval($log->total_score_50);
                }
            }
            $avgExp50 = count($studentExpScores) > 0 ? (array_sum($studentExpScores) / count($studentExpScores)) : 0;
            $scaledLabWork30 = round(($avgExp50 / 50) * 30, 2);

            // 2. Open-Ended Project (Table 2.3) - score out of 50, scaled to 10
            $openLog = $openEndedLogs->get($regNo);
            $openScore50 = $openLog ? floatval($openLog->total_score_50) : 0;
            $scaledOpenEnded10 = round(($openScore50 / 50) * 10, 2);

            // 3. Series Exams (Table 3.1) - average of series 1 and series 2 out of 40, scaled to 15
            $seriesScores = [];
            foreach (['Series 1', 'Series 2'] as $sName) {
                if (isset($seriesExamLogs[$sName])) {
                    $log = $seriesExamLogs[$sName]->where('reg_no', $regNo)->first();
                    if ($log) {
                        $seriesScores[] = floatval($log->total_score_40);
                    }
                }
            }
            $avgSeries40 = count($seriesScores) > 0 ? (array_sum($seriesScores) / count($seriesScores)) : 0;
            $scaledSeries15 = round(($avgSeries40 / 40) * 15, 2);

            // 4. Attendance Marks (Table 2.1) - out of 5
            $attMark5 = $attendanceMarks[$regNo]['mark'] ?? 5;

            // Grand Total CIA (out of 60)
            $totalCIA = $scaledLabWork30 + $scaledOpenEnded10 + $scaledSeries15 + $attMark5;

            $consolidatedScores[$regNo] = [
                'raw_exp_avg' => round($avgExp50, 2),
                'scaled_lab_work_30' => $scaledLabWork30,
                'raw_open_ended' => round($openScore50, 2),
                'scaled_open_ended_10' => $scaledOpenEnded10,
                'raw_series_avg' => round($avgSeries40, 2),
                'scaled_series_15' => $scaledSeries15,
                'attendance_mark_5' => $attMark5,
                'total_cia_60' => round($totalCIA, 2)
            ];
        }

        return view('r26_classroom_practical_reports_print', compact(
            'batchSubject',
            'students',
            'labBatches',
            'experimentLogs',
            'openEndedLogs',
            'seriesExamLogs',
            'attendanceMarks',
            'consolidatedScores'
        ));
    }

    /**
     * Get lab batch roster with all enrolled students and assigned batches.
     */
    public function getLabBatchRoster(Request $request, $subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);

        $students = Student::where('classroom_id', $batchSubject->classroom_id)
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no']);

        $assignedBatches = R26StudentLabBatch::where('batch_subject_id', $subjectId)
            ->pluck('lab_batch', 'reg_no')
            ->toArray();

        $roster = $students->map(function ($s) use ($assignedBatches) {
            return [
                'reg_no' => $s->reg_no,
                'name' => $s->name,
                'roll_no' => $s->roll_no,
                'lab_batch' => $assignedBatches[$s->reg_no] ?? null,
            ];
        });

        $batchACount = count(array_filter($assignedBatches, fn($b) => $b === 'Batch A'));
        $batchBCount = count(array_filter($assignedBatches, fn($b) => $b === 'Batch B'));

        return response()->json([
            'status' => 'SUCCESS',
            'total_students' => $students->count(),
            'batch_a_count' => $batchACount,
            'batch_b_count' => $batchBCount,
            'unassigned_count' => $students->count() - ($batchACount + $batchBCount),
            'roster' => $roster,
        ]);
    }

    /**
     * Automatically split students into Batch A and Batch B by half, roll cutoff, or alternating.
     */
    public function autoSplitLabBatches(Request $request, $subjectId)
    {
        $batchSubject = BatchSubject::findOrFail($subjectId);

        $request->validate([
            'method' => 'required|string|in:half,cutoff,alternating',
            'cutoff' => 'nullable|integer',
        ]);

        $method = $request->input('method');
        $cutoff = $request->input('cutoff');

        $students = Student::where('classroom_id', $batchSubject->classroom_id)
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no']);

        $total = $students->count();
        $halfCount = (int)ceil($total / 2);

        $roster = [];
        $batchACount = 0;
        $batchBCount = 0;

        DB::transaction(function () use ($students, $subjectId, $method, $cutoff, $halfCount, &$roster, &$batchACount, &$batchBCount) {
            foreach ($students as $index => $s) {
                $batch = 'Batch A';

                if ($method === 'half') {
                    $batch = ($index < $halfCount) ? 'Batch A' : 'Batch B';
                } elseif ($method === 'cutoff') {
                    $roll = is_numeric($s->roll_no) ? (int)$s->roll_no : ($index + 1);
                    $batch = ($roll <= $cutoff) ? 'Batch A' : 'Batch B';
                } elseif ($method === 'alternating') {
                    $batch = ($index % 2 === 0) ? 'Batch A' : 'Batch B';
                }

                if ($batch === 'Batch A') $batchACount++;
                else $batchBCount++;

                R26StudentLabBatch::updateOrCreate(
                    [
                        'batch_subject_id' => $subjectId,
                        'reg_no' => $s->reg_no,
                    ],
                    [
                        'lab_batch' => $batch,
                    ]
                );

                $roster[] = [
                    'reg_no' => $s->reg_no,
                    'name' => $s->name,
                    'roll_no' => $s->roll_no,
                    'lab_batch' => $batch,
                ];
            }
        });

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Successfully split {$total} student(s) into Batch A ({$batchACount}) and Batch B ({$batchBCount}).",
            'batch_a_count' => $batchACount,
            'batch_b_count' => $batchBCount,
            'roster' => $roster,
        ]);
    }

    /**
     * Save laboratory batch membership assignments for students.
     */
    public function saveLabBatchRoster(Request $request, $subjectId)
    {
        $staff = $this->getStaff();
        if (!$staff) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $request->validate([
            'roster' => 'required|array',
            'roster.*.reg_no' => 'required|string|exists:students,reg_no',
            'roster.*.lab_batch' => 'nullable|string|max:50',
        ]);

        $roster = $request->input('roster', []);
        $savedCount = 0;

        DB::transaction(function () use ($roster, $subjectId, &$savedCount) {
            foreach ($roster as $entry) {
                $regNo = $entry['reg_no'];
                $labBatch = trim((string)($entry['lab_batch'] ?? ''));

                if ($labBatch === '') {
                    R26StudentLabBatch::where('batch_subject_id', $subjectId)
                        ->where('reg_no', $regNo)
                        ->delete();
                } else {
                    R26StudentLabBatch::updateOrCreate(
                        [
                            'batch_subject_id' => $subjectId,
                            'reg_no' => $regNo,
                        ],
                        [
                            'lab_batch' => $labBatch,
                        ]
                    );
                    $savedCount++;
                }
            }
        });

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Lab batch roster saved successfully.',
            'saved_count' => $savedCount,
        ]);
    }

    /**
     * Delete a practical lesson plan row with strict ownership and subject scope verification.
     */
    public function deletePracticalLessonPlanRow(Request $request, $subjectId, $id)
    {
        $staff = $this->getStaff();
        if (!$staff) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        $plan = \App\Models\LessonPlan::where('id', $id)
            ->where('batch_subject_id', $subjectId)
            ->first();

        if (!$plan) {
            return response()->json(['status' => 'ERROR', 'message' => 'Lesson plan row not found in this subject scope.'], 404);
        }

        DB::transaction(function () use ($plan, $id) {
            // Nullify any references in class_logs_attendance
            DB::table('class_logs_attendance')
                ->where('lesson_plan_id', $id)
                ->update(['lesson_plan_id' => null]);

            $plan->delete();
        });

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Practical lesson plan row deleted successfully.',
        ]);
    }

    /**
     * Print Practical Attendance Register using report layout.
     */
    public function printPracticalAttendanceRegister(Request $request, $subjectId)
    {
        $batchSubject = BatchSubject::with(['classroom', 'courseFile'])->findOrFail($subjectId);
        $classroom = $batchSubject->classroom;
        if (!$classroom) {
            $classroom = \App\Models\R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }

        $students = Student::where('classroom_id', $batchSubject->classroom_id)
            ->whereIn('status', ['Approved', 'APPROVED', 'approved'])
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get();

        $labBatches = R26StudentLabBatch::where('batch_subject_id', $subjectId)
            ->get()
            ->keyBy('reg_no');

        $attendanceData = [];
        foreach ($students as $student) {
            $totalClasses = DB::table('student_attendance')
                ->where('subject_code', $batchSubject->subject_code)
                ->where('reg_no', $student->reg_no)
                ->count();

            $presentClasses = DB::table('student_attendance')
                ->where('subject_code', $batchSubject->subject_code)
                ->where('reg_no', $student->reg_no)
                ->whereIn('status', ['Present', 'Late'])
                ->count();

            $pct = $totalClasses > 0 ? round(($presentClasses / $totalClasses) * 100, 1) : 100.0;

            $attendanceData[$student->reg_no] = [
                'conducted' => $totalClasses,
                'attended' => $presentClasses,
                'percentage' => $pct,
            ];
        }

        return view('practical_attendance_register_print', [
            'batchSubject' => $batchSubject,
            'classroom' => $classroom,
            'students' => $students,
            'labBatches' => $labBatches,
            'attendanceData' => $attendanceData,
            'title' => 'Practical Attendance Register - ' . $batchSubject->subject_name,
            'orientation' => 'portrait',
        ]);
    }

    /**
     * Helper to prepare R2021 practical report dataset for printable marksheet views.
     */
    public function getPracticalReportData($batchSubjectId)
    {
        $batchSubject = BatchSubject::with(['classroom', 'courseFile'])->findOrFail($batchSubjectId);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get();

        $experiments = PracticalExperiment::where('batch_subject_id', $batchSubjectId)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED) ASC, experiment_no ASC')
            ->get();
        $expIds = $experiments->pluck('id')->toArray();
        $allExpMarks = PracticalExperimentMark::whereIn('practical_experiment_id', $expIds)->get();

        $evaluations = PracticalEvaluation::where('batch_subject_id', $batchSubjectId)->get()->keyBy('reg_no');
        $tests       = PracticalTest::where('batch_subject_id', $batchSubjectId)->get();
        $testIds     = $tests->pluck('id')->toArray();
        $allTestMarks = PracticalTestMark::whereIn('practical_test_id', $testIds)->get();

        // Attendance from class_logs_attendance
        $classLogs  = DB::table('class_logs_attendance')->where('batch_subject_id', $batchSubjectId)->get(['present_students', 'absent_students']);
        $totalClasses = $classLogs->count();
        $studentAttCounts = [];
        $studentScheduledCounts = [];
        foreach ($classLogs as $log) {
            $pList = json_decode($log->present_students ?? '[]', true);
            $aList = json_decode($log->absent_students ?? '[]', true);
            if (is_array($pList)) {
                foreach ($pList as $rNo) {
                    $studentAttCounts[$rNo] = ($studentAttCounts[$rNo] ?? 0) + 1;
                    $studentScheduledCounts[$rNo] = ($studentScheduledCounts[$rNo] ?? 0) + 1;
                }
            }
            if (is_array($aList)) {
                foreach ($aList as $rNo) {
                    $studentScheduledCounts[$rNo] = ($studentScheduledCounts[$rNo] ?? 0) + 1;
                }
            }
        }

        $t1 = $tests->where('test_name', 'Test 1')->first();
        $t2 = $tests->where('test_name', 'Test 2')->first();

        $conductedExpIds = $experiments->filter(function($e) use ($allExpMarks) {
            return !empty($e->conducted_date) || $allExpMarks->where('practical_experiment_id', $e->id)->where('total_mark', '>', 0)->count() > 0;
        })->pluck('id')->unique();
        $conductedCount = $conductedExpIds->count();
        $totalCompletedExps = ($conductedCount > 0) ? $conductedCount : ($experiments->count() > 0 ? $experiments->count() : 1);

        $mappedStudents = $students->map(function ($student) use ($batchSubject, $experiments, $allExpMarks, $evaluations, $tests, $allTestMarks, $t1, $t2, $totalClasses, $studentAttCounts, $studentScheduledCounts, $totalCompletedExps) {
            $regNo = $student->reg_no;

            // Attendance calculation (proportional out of 15 for R2021)
            $presentClasses = $studentAttCounts[$regNo] ?? 0;
            $scheduledClasses = $studentScheduledCounts[$regNo] ?? 0;
            $totalForStudent = $scheduledClasses > 0 ? $scheduledClasses : $totalClasses;
            $pct = $totalForStudent > 0 ? round(($presentClasses / $totalForStudent) * 100, 2) : 100.00;
            $suggestedAttendance = $totalForStudent > 0 ? round(($presentClasses / $totalForStudent) * 15, 1) : 15.0;

            $eval = $evaluations->get($regNo);
            $microProject = $eval ? (float)$eval->micro_project : 0.00;
            $attendanceMarks = ($eval && $eval->attendance_marks !== null && (float)$eval->attendance_marks > 0) ? (float)$eval->attendance_marks : $suggestedAttendance;
            
            // ESE Board Marks or Grade
            $boardExam = $eval ? ($eval->board_exam_marks !== null ? $eval->board_exam_marks : null) : null;
            if ($boardExam === null) {
                $bGrade = DB::table('student_board_grades')
                    ->where('reg_no', $regNo)
                    ->where('subject_code', $batchSubject->subject_code)
                    ->value('grade');
                if ($bGrade) {
                    $boardExam = $bGrade;
                }
            }

            // 5 Rubrics Continuous Lab Work (Max 37.5)
            $sumRough = 0; $sumFair = 0; $sumObs = 0; $sumProc = 0; $sumViva = 0;
            $gradedExpCount = 0;
            foreach ($experiments as $exp) {
                $mark = $allExpMarks->where('practical_experiment_id', $exp->id)->where('reg_no', $regNo)->first();
                if ($mark && ($mark->total_mark > 0 || $mark->rough_record > 0 || $mark->fair_record > 0 || $mark->prerequisites > 0 || $mark->work_done > 0 || $mark->result > 0)) {
                    $sumRough += (float)$mark->rough_record;
                    $sumFair  += (float)$mark->fair_record;
                    $sumObs   += (float)$mark->prerequisites;
                    $sumProc  += (float)$mark->work_done;
                    $sumViva  += (float)$mark->result;
                    $gradedExpCount++;
                }
            }

            $totalDivisor = max($totalCompletedExps, $gradedExpCount, 1);
            $avgRough = round($sumRough / $totalDivisor, 2);
            $avgFair  = round($sumFair / $totalDivisor, 2);
            $avgObs   = round($sumObs / $totalDivisor, 2);
            $avgProc  = round($sumProc / $totalDivisor, 2);
            $avgViva  = round($sumViva / $totalDivisor, 2);
            $avgLabWork = round($avgRough + $avgFair + $avgObs + $avgProc + $avgViva, 2);

            // Practical Series Tests (Max 15)
            $scoreT1 = $t1 ? (float)$allTestMarks->where('practical_test_id', $t1->id)->where('reg_no', $regNo)->sum('marks_obtained') : 0.0;
            $scoreT2 = $t2 ? (float)$allTestMarks->where('practical_test_id', $t2->id)->where('reg_no', $regNo)->sum('marks_obtained') : 0.0;
            $avgTests = round(($scoreT1 + $scoreT2) / 2, 2);

            // Total Internal Assessment (Max 75)
            $totalInternal = round($avgLabWork + $microProject + $avgTests + $attendanceMarks, 2);

            $student->avg_rough_record = $avgRough;
            $student->avg_fair_record  = $avgFair;
            $student->avg_obs_prep     = $avgObs;
            $student->avg_proc_punct   = $avgProc;
            $student->avg_viva_voce    = $avgViva;
            $student->avg_lab_work     = $avgLabWork;
            $student->tests = [
                'Test 1' => ['total' => $scoreT1],
                'Test 2' => ['total' => $scoreT2],
                'average' => $avgTests
            ];
            $student->micro_project = $microProject;
            $student->attendance_marks = $attendanceMarks;
            $student->attendance_percentage = $pct;
            $student->total_classes = $totalClasses;
            $student->present_classes = $presentClasses;
            $student->total_internal = $totalInternal;
            $student->board_exam_marks = $boardExam;

            return $student;
        });

        $branchMap = [
            'EL' => 'Electronics Engineering',
            'CE' => 'Civil Engineering',
            'ME' => 'Mechanical Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'CH' => 'Chemical Engineering',
            'CS' => 'Computer Engineering',
            'CT' => 'Computer Engineering',
            'AU' => 'Automobile Engineering',
        ];
        $branchKey = strtoupper(explode('_', $batchSubject->classroom_id)[0] ?? '');
        $fullDepartment = $branchMap[$branchKey] ?? $branchKey;
        $cleanedBatch = preg_replace('/^[A-Z]+_/', '', $batchSubject->classroom_id);
        $cleanedBatch = str_replace('_', ' - ', $cleanedBatch);

        return [
            'subject' => $batchSubject,
            'fullDepartment' => $fullDepartment,
            'cleanedBatch' => $cleanedBatch,
            'students' => $mappedStudents,
            'totalStudents' => $mappedStudents->count(),
            'currentYear' => date('Y')
        ];
    }

    /**
     * Print Practical Series Examination Marksheet (15M).
     */
    public function printSeriesReport($batchSubjectId)
    {
        return view('classroom_practical_series_print', $this->getPracticalReportData($batchSubjectId));
    }

    /**
     * Print End-Semester Examination & Consolidated Final Results Marksheet (125M).
     */
    public function printFinalResults($batchSubjectId)
    {
        return view('classroom_practical_final_results_print', $this->getPracticalReportData($batchSubjectId));
    }

    /**
     * Print Completed Practical Experiments & Sessions Log Report.
     */
    public function printExperimentsLog($batchSubjectId)
    {
        $batchSubject = BatchSubject::with('classroom')->findOrFail($batchSubjectId);

        // Reconcile and sync practical experiment conducted dates against class logs and graded marks
        \App\Http\Controllers\AttendanceController::syncPracticalExperimentsWithLogs($batchSubjectId);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get();

        $experiments = PracticalExperiment::where('batch_subject_id', $batchSubjectId)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED) ASC, experiment_no ASC')
            ->get();
        $allExpMarks = PracticalExperimentMark::whereIn('practical_experiment_id', $experiments->pluck('id'))->where('total_mark', '>', 0)->get();

        $classLogs = DB::table('class_logs_attendance')->where('batch_subject_id', $batchSubjectId)->get();

        // Group class logs by (date, sub_batch, topics_covered)
        $groupedLogs = $classLogs->groupBy(function($l) {
            return $l->date . '###' . ($l->sub_batch ?? 'Whole') . '###' . trim($l->topics_covered ?? '');
        });

        $studentRollMap = $students->pluck('roll_no', 'reg_no');

        $logSessions = [];
        foreach ($groupedLogs as $groupKey => $logs) {
            $first = $logs->first();
            $topic = trim($first->topics_covered ?? '');
            if (!$topic && !$first->lesson_plan_id) continue;

            $date = $first->date;
            $subBatchVal = $first->sub_batch ?? 'Whole';
            $batchLabel = ($subBatchVal === '1' || $subBatchVal === 1) ? 'Batch 1' : (($subBatchVal === '2' || $subBatchVal === 2) ? 'Batch 2' : 'Whole Class');
            $periods = $logs->pluck('period')->unique()->sort()->values()->all();
            $hoursCount = count($periods);
            $periodStr = $hoursCount > 0 ? implode(', ', array_map(fn($p) => 'P' . $p, $periods)) : 'Session';
            $hoursText = "{$hoursCount} " . ($hoursCount === 1 ? 'hr' : 'hrs') . " ({$periodStr})";
            $pList = json_decode($first->present_students ?? '[]', true) ?: [];
            $aList = json_decode($first->absent_students ?? '[]', true) ?: [];
            $totalInLog = count($pList) + count($aList);
            $presentCount = count($pList);
            $absentCount = count($aList);

            $absentRolls = collect($aList)->map(fn($r) => $studentRollMap->get($r))->filter(fn($r) => $r !== null)->sort()->values()->all();
            $absentRollsStr = !empty($absentRolls) ? implode(', ', $absentRolls) : ($presentCount > 0 ? 'None' : '-');

            $logSessions[] = [
                'date' => $date,
                'sub_batch' => $subBatchVal,
                'batch_label' => $batchLabel,
                'periods' => $periods,
                'hours_count' => $hoursCount,
                'hours_text' => $hoursText,
                'topic' => $topic,
                'lesson_plan_id' => $first->lesson_plan_id,
                'present_count' => $presentCount,
                'absent_count'  => $absentCount,
                'absent_roll_nos' => $absentRollsStr,
                'total_count' => $totalInLog > 0 ? $totalInLog : $students->count(),
                'attendance_pct' => $totalInLog > 0 ? round(($presentCount / $totalInLog) * 100, 1) : 100.0,
            ];
        }

        $conductedDetails = [];

        if ($experiments->isEmpty()) {
            foreach ($logSessions as $idx => $s) {
                $conductedDetails[] = [
                    'experiment_id' => null,
                    'experiment_no' => 'Exp ' . ($idx + 1),
                    'title'         => $s['topic'] ?: 'Practical Session',
                    'co_tag'        => 'CO1',
                    'date'          => $s['date'],
                    'periods'       => $s['periods'],
                    'hours_count'   => $s['hours_count'],
                    'hours_text'    => $s['hours_text'],
                    'batch'         => $s['batch_label'],
                    'sub_batch'     => $s['sub_batch'],
                    'present_count' => $s['present_count'],
                    'absent_count'  => $s['absent_count'],
                    'absent_roll_nos' => $s['absent_roll_nos'],
                    'total_count'   => $s['total_count'],
                    'attendance_pct'=> $s['attendance_pct'],
                ];
            }
        } else {
            foreach ($experiments as $exp) {
                $hasMarks = $allExpMarks->where('practical_experiment_id', $exp->id)->where('total_mark', '>', 0)->count() > 0;
                $expNo = trim((string)$exp->experiment_no);
                $expTitle = strtolower(trim((string)($exp->title ?? '')));

                $matchingSessions = [];

                foreach ($logSessions as $s) {
                    $t = trim((string)($s['topic'] ?? ''));
                    if (empty($t)) continue;

                    $matches = false;
                    // Match experiment number e.g. "Exp 10", "Experiment 10", "Expt 10"
                    if (preg_match('/\b(?:exp|experiment|ex|expt)\.?\s*#?\s*0*' . preg_quote($expNo, '/') . '\b/i', $t)) {
                        $matches = true;
                    } elseif (preg_match('/\b(?:exp|experiment|ex|expt|experiments|expts)s?\.?\s*#?([0-9\s,&-]+)/i', $t, $mList)) {
                        $nums = preg_split('/[\s,&-]+/', $mList[1]);
                        if (in_array($expNo, array_map('trim', $nums))) {
                            $matches = true;
                        }
                    } elseif (!empty($expTitle) && strlen($expTitle) >= 6) {
                        $tLower = strtolower($t);
                        if (str_contains($tLower, $expTitle) || (strlen($tLower) >= 6 && str_contains($expTitle, $tLower))) {
                            $matches = true;
                        }
                    }

                    if ($matches) {
                        $matchingSessions[] = $s;
                    }
                }

                if (!empty($matchingSessions)) {
                    foreach ($matchingSessions as $mSession) {
                        $conductedDetails[] = [
                            'experiment_id' => $exp->id,
                            'experiment_no' => 'Exp ' . $exp->experiment_no,
                            'title'         => $exp->title,
                            'co_tag'        => $exp->co_tag ?? 'CO1',
                            'date'          => $mSession['date'],
                            'periods'       => $mSession['periods'],
                            'hours_count'   => $mSession['hours_count'],
                            'hours_text'    => $mSession['hours_text'],
                            'batch'         => $mSession['batch_label'],
                            'sub_batch'     => $mSession['sub_batch'],
                            'present_count' => $mSession['present_count'],
                            'absent_count'  => $mSession['absent_count'],
                            'absent_roll_nos' => $mSession['absent_roll_nos'],
                            'total_count'   => $mSession['total_count'],
                            'attendance_pct'=> $mSession['attendance_pct'],
                        ];
                    }
                } elseif ($hasMarks || !empty($exp->conducted_date)) {
                    $mSession = collect($logSessions)->firstWhere('date', $exp->conducted_date);
                    $gradedCount = $allExpMarks->where('practical_experiment_id', $exp->id)->where('total_mark', '>', 0)->count();
                    $conductedDetails[] = [
                        'experiment_id' => $exp->id,
                        'experiment_no' => 'Exp ' . $exp->experiment_no,
                        'title'         => $exp->title,
                        'co_tag'        => $exp->co_tag ?? 'CO1',
                        'date'          => $mSession ? $mSession['date'] : ($exp->conducted_date ?: 'Conducted'),
                        'periods'       => $mSession ? $mSession['periods'] : [1, 2, 3],
                        'hours_count'   => $mSession ? $mSession['hours_count'] : 3,
                        'hours_text'    => $mSession ? $mSession['hours_text'] : '3 hrs (Lab)',
                        'batch'         => $mSession ? $mSession['batch_label'] : 'Whole Class',
                        'sub_batch'     => $mSession ? $mSession['sub_batch'] : 'Whole',
                        'present_count' => $mSession ? $mSession['present_count'] : $gradedCount,
                        'absent_count'  => $mSession ? $mSession['absent_count'] : 0,
                        'absent_roll_nos' => $mSession ? $mSession['absent_roll_nos'] : 'None',
                        'total_count'   => $mSession ? $mSession['total_count'] : $students->count(),
                        'attendance_pct'=> $mSession ? $mSession['attendance_pct'] : ($students->count() > 0 ? round(($gradedCount / $students->count()) * 100, 1) : 100.0),
                    ];
                }
            }
        }

        // Order completed experiments: Batch 1 in initial rows, then Batch 2, then Whole Class / others
        usort($conductedDetails, function($a, $b) {
            $batchRank = function($item) {
                $sb = (string)($item['sub_batch'] ?? '');
                $b = strtolower((string)($item['batch'] ?? ''));
                if ($sb === '1' || str_contains($b, 'batch 1') || $b === 'b1') return 1;
                if ($sb === '2' || str_contains($b, 'batch 2') || $b === 'b2') return 2;
                return 3;
            };
            $rA = $batchRank($a);
            $rB = $batchRank($b);
            if ($rA !== $rB) return $rA <=> $rB;

            preg_match('/\d+/', (string)($a['experiment_no'] ?? ''), $mA);
            preg_match('/\d+/', (string)($b['experiment_no'] ?? ''), $mB);
            $numA = isset($mA[0]) ? (int)$mA[0] : 0;
            $numB = isset($mB[0]) ? (int)$mB[0] : 0;
            if ($numA !== $numB) return $numA <=> $numB;

            return strcmp((string)($a['date'] ?? ''), (string)($b['date'] ?? ''));
        });

        $actualLabHours = $classLogs->map(function($l) {
            return $l->date . '_P' . $l->period;
        })->unique()->count();
        if ($actualLabHours === 0 && count($conductedDetails) > 0) {
            $actualLabHours = count($conductedDetails) * 3;
        }

        $totalExperiments = $experiments->count();
        $conductedCount = $experiments->isEmpty()
            ? count($conductedDetails)
            : collect($conductedDetails)->pluck('experiment_id')->filter()->unique()->count();
        $coveragePct = $totalExperiments > 0 ? round(($conductedCount / $totalExperiments) * 100) : 0;

        $cleanedBatch = preg_replace('/^([A-Z]+)_(\d{4})_(\d{4})$/', '$1 ($2-$3)', $batchSubject->classroom_id ?? '');
        $branch = explode('_', $batchSubject->classroom_id ?? '')[0] ?? 'Engineering';
        $deptMap = [
            'CT' => 'Computer Engineering',
            'EL' => 'Electronics Engineering',
            'ME' => 'Mechanical Engineering',
            'CE' => 'Civil Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'AU' => 'Automobile Engineering',
        ];
        $fullDepartment = $deptMap[$branch] ?? ($branch . ' Department');

        return view('classroom_practical_experiments_print', compact(
            'batchSubject', 'students', 'conductedDetails', 'totalExperiments',
            'conductedCount', 'actualLabHours', 'coveragePct', 'cleanedBatch', 'fullDepartment'
        ));
    }

    /**
     * Print Individual Student Practical Evaluation & Attendance Record (Revision 2021).
     */
    public function printStudentReport($batchSubjectId, $regNo)
    {
        $batchSubject = BatchSubject::with(['classroom', 'courseFile'])->findOrFail($batchSubjectId);

        // Sync practical experiments with logs
        \App\Http\Controllers\AttendanceController::syncPracticalExperimentsWithLogs($batchSubjectId);

        $student = Student::where('reg_no', $regNo)
            ->orWhere('sbte_reg_no', $regNo)
            ->firstOrFail();

        $experiments = PracticalExperiment::where('batch_subject_id', $batchSubjectId)
            ->orderByRaw('CAST(experiment_no AS UNSIGNED) ASC, experiment_no ASC')
            ->get();
        $expIds = $experiments->pluck('id')->toArray();
        $totalExperiments = $experiments->count();

        // Student marks
        $allExpMarks = PracticalExperimentMark::whereIn('practical_experiment_id', $expIds)
            ->where('reg_no', $student->reg_no)
            ->get();

        // Conducted experiments identification across class
        $allClassExpMarks = PracticalExperimentMark::whereIn('practical_experiment_id', $expIds)
            ->where('total_mark', '>', 0)
            ->get();
        $conductedExpIds = $experiments->filter(function($e) use ($allClassExpMarks) {
            return !empty($e->conducted_date) || $allClassExpMarks->where('practical_experiment_id', $e->id)->count() > 0;
        })->pluck('id')->unique();
        $conductedCount = $conductedExpIds->count();
        $totalCompletedExps = ($conductedCount > 0) ? $conductedCount : ($totalExperiments > 0 ? $totalExperiments : 1);

        // Class logs for attendance tracking
        $classLogs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->orderBy('date', 'desc')
            ->get();

        $totalClasses = $classLogs->count();
        $presentClasses = 0;
        $totalForStudent = 0;

        foreach ($classLogs as $cl) {
            $pList = json_decode($cl->present_students ?? '[]', true) ?: [];
            $aList = json_decode($cl->absent_students ?? '[]', true) ?: [];
            if (in_array($student->reg_no, $pList)) {
                $presentClasses++;
                $totalForStudent++;
            } elseif (in_array($student->reg_no, $aList)) {
                $totalForStudent++;
            }
        }
        if ($totalForStudent === 0) $totalForStudent = $totalClasses;
        $attendancePercentage = $totalForStudent > 0 ? round(($presentClasses / $totalForStudent) * 100, 1) : 100.0;
        $suggestedAttendance = $totalForStudent > 0 ? round(($presentClasses / $totalForStudent) * 15, 1) : 15.0;

        // Lab batch assignment
        $labBatchRec = R26StudentLabBatch::where('batch_subject_id', $batchSubjectId)
            ->where('reg_no', $student->reg_no)
            ->first();
        $labBatch = $labBatchRec ? ($labBatchRec->lab_batch == '1' ? 'Batch 1' : ($labBatchRec->lab_batch == '2' ? 'Batch 2' : $labBatchRec->lab_batch)) : 'Whole Class';

        // Evaluations record
        $eval = PracticalEvaluation::where('batch_subject_id', $batchSubjectId)->where('reg_no', $student->reg_no)->first();
        $microProject = $eval ? (float)$eval->micro_project : 0.0;
        $openEndedTopic = $eval ? $eval->open_ended_topic : null;
        $attendanceMarks = ($eval && $eval->attendance_marks !== null && (float)$eval->attendance_marks > 0) ? (float)$eval->attendance_marks : $suggestedAttendance;
        $boardExam = $eval ? $eval->board_exam_marks : null;

        // Series tests marks
        $tests = PracticalTest::where('batch_subject_id', $batchSubjectId)->get();
        $t1 = $tests->where('test_name', 'Test 1')->first();
        $t2 = $tests->where('test_name', 'Test 2')->first();
        $scoreT1 = $t1 ? (float)PracticalTestMark::where('practical_test_id', $t1->id)->where('reg_no', $student->reg_no)->sum('marks_obtained') : 0.0;
        $scoreT2 = $t2 ? (float)PracticalTestMark::where('practical_test_id', $t2->id)->where('reg_no', $student->reg_no)->sum('marks_obtained') : 0.0;
        $avgTests = round(($scoreT1 + $scoreT2) / 2, 2);

        $expRecords = [];
        $sumRough = 0; $sumFair = 0; $sumObs = 0; $sumProc = 0; $sumViva = 0; $sumTotal = 0;
        $attendedCount = 0;
        $gradedCount = 0;

        foreach ($experiments as $exp) {
            $mark = $allExpMarks->where('practical_experiment_id', $exp->id)->first();
            $hasScore = $mark && ($mark->total_mark > 0 || $mark->rough_record > 0 || $mark->fair_record > 0 || $mark->prerequisites > 0 || $mark->work_done > 0 || $mark->result > 0);
            
            $evalDate = ($mark && !empty($mark->evaluation_date)) ? $mark->evaluation_date : null;
            $isAttended = false;

            $expNo = trim((string)$exp->experiment_no);
            $expTitle = strtolower(trim((string)($exp->title ?? '')));

            foreach ($classLogs as $l) {
                $pList = json_decode($l->present_students ?? '[]', true) ?: [];
                if (in_array($student->reg_no, $pList)) {
                    $t = trim((string)($l->topics_covered ?? ''));
                    $matched = false;
                    if (preg_match('/\b(?:exp|experiment|ex|expt)\.?\s*#?\s*0*' . preg_quote($expNo, '/') . '\b/i', $t)) {
                        $matched = true;
                    } elseif (preg_match('/\b(?:exp|experiment|ex|expt|experiments|expts)s?\.?\s*#?([0-9\s,&-]+)/i', $t, $mList)) {
                        $nums = preg_split('/[\s,&-]+/', $mList[1]);
                        if (in_array($expNo, array_map('trim', $nums))) $matched = true;
                    } elseif (!empty($expTitle) && strlen($expTitle) >= 6 && str_contains(strtolower($t), $expTitle)) {
                        $matched = true;
                    } elseif (!empty($exp->conducted_date) && $l->date === $exp->conducted_date) {
                        $matched = true;
                    }

                    if ($matched) {
                        $isAttended = true;
                        $evalDate = $l->date;
                        break;
                    }
                }
            }

            if ($hasScore) {
                $isAttended = true;
                if (!$evalDate && $exp->conducted_date) $evalDate = $exp->conducted_date;
            }

            if ($isAttended) $attendedCount++;

            $rMark = $hasScore ? (float)$mark->rough_record : 0.0;
            $fMark = $hasScore ? (float)$mark->fair_record : 0.0;
            $oMark = $hasScore ? (float)$mark->prerequisites : 0.0;
            $pMark = $hasScore ? (float)$mark->work_done : 0.0;
            $vMark = $hasScore ? (float)$mark->result : 0.0;
            $tMark = $hasScore ? (float)$mark->total_mark : 0.0;

            if ($hasScore) {
                $sumRough += $rMark;
                $sumFair  += $fMark;
                $sumObs   += $oMark;
                $sumProc  += $pMark;
                $sumViva  += $vMark;
                $sumTotal += $tMark;
                $gradedCount++;
            }

            $expRecords[] = [
                'experiment_no'   => $exp->experiment_no,
                'title'           => $exp->title,
                'co_tag'          => $exp->co_tag ?: 'CO1',
                'conducted_date'  => $exp->conducted_date,
                'evaluation_date' => $evalDate,
                'is_attended'     => $isAttended,
                'has_score'       => $hasScore,
                'rough_record'    => $rMark,
                'fair_record'     => $fMark,
                'obs_prep'        => $oMark,
                'proc_punct'      => $pMark,
                'viva_voce'       => $vMark,
                'total_mark'      => $tMark,
            ];
        }

        // Consolidated averages across all conducted experiments (consistent criteria)
        $totalDivisor = max($totalCompletedExps, $attendedCount, 1);
        $avgRoughRecord = round($sumRough / $totalDivisor, 2);
        $avgFairRecord  = round($sumFair / $totalDivisor, 2);
        $avgObsPrep     = round($sumObs / $totalDivisor, 2);
        $avgProcPunct   = round($sumProc / $totalDivisor, 2);
        $avgVivaVoce    = round($sumViva / $totalDivisor, 2);
        $avgLabWork     = round($avgRoughRecord + $avgFairRecord + $avgObsPrep + $avgProcPunct + $avgVivaVoce, 2);

        // Total Internal CIA (Max 75)
        $totalInternal = round($avgLabWork + $microProject + $avgTests + $attendanceMarks, 2);

        // ESE and Final Results calculation
        $eseDisplay = '-';
        $finalResultDisplay = '-';
        $eseNumeric = null;
        if ($boardExam !== null) {
            if (is_numeric($boardExam)) {
                $eseNumeric = (float)$boardExam;
                $eseDisplay = number_format($eseNumeric, 1) . ' / 50';
                $totalScore = $totalInternal + $eseNumeric;
                $pct = ($totalScore / 125) * 100;
                $grade = $pct >= 90 ? 'S' : ($pct >= 80 ? 'A' : ($pct >= 70 ? 'B' : ($pct >= 60 ? 'C' : ($pct >= 50 ? 'D' : ($pct >= 40 ? 'E' : 'F')))));
                $finalResultDisplay = number_format($totalScore, 1) . " / 125 (Grade {$grade})";
            } else {
                $gradeLetter = strtoupper(trim($boardExam));
                $eseDisplay = "Grade {$gradeLetter}";
                $finalResultDisplay = "Grade {$gradeLetter}";
            }
        }

        $branchMap = [
            'EL' => 'Electronics Engineering',
            'CE' => 'Civil Engineering',
            'ME' => 'Mechanical Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'CH' => 'Chemical Engineering',
            'CS' => 'Computer Engineering',
            'CT' => 'Computer Engineering',
            'AU' => 'Automobile Engineering',
        ];
        $branchKey = strtoupper(explode('_', $batchSubject->classroom_id)[0] ?? '');
        $fullDepartment = $branchMap[$branchKey] ?? ($branchKey . ' Department');
        $cleanedBatch = preg_replace('/^([A-Z]+)_(\d{4})_(\d{4})$/', '$1 ($2-$3)', $batchSubject->classroom_id ?? '');

        return view('classroom_practical_student_report_print', compact(
            'batchSubject',
            'student',
            'fullDepartment',
            'cleanedBatch',
            'labBatch',
            'totalExperiments',
            'conductedCount',
            'totalCompletedExps',
            'attendedCount',
            'gradedCount',
            'expRecords',
            'sumRough',
            'sumFair',
            'sumObs',
            'sumProc',
            'sumViva',
            'sumTotal',
            'avgRoughRecord',
            'avgFairRecord',
            'avgObsPrep',
            'avgProcPunct',
            'avgVivaVoce',
            'avgLabWork',
            'microProject',
            'openEndedTopic',
            'attendanceMarks',
            'presentClasses',
            'totalForStudent',
            'attendancePercentage',
            'scoreT1',
            'scoreT2',
            'avgTests',
            'totalInternal',
            'boardExam',
            'eseDisplay',
            'finalResultDisplay'
        ));
    }

    /**
     * Attendance log API — returns per-session (date+period+topic) attendance
     * for the given batch_subject_id. Client-side filters by student reg_no.
     */
    public function getAttendanceLog(Request $request, $subjectId)
    {
        $logs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $subjectId)
            ->orderBy('date', 'asc')
            ->orderBy('period', 'asc')
            ->get(['id', 'date', 'period', 'topics_covered', 'sub_batch', 'present_students', 'absent_students']);

        $result = $logs->map(function($log) {
            return [
                'date'      => $log->date,
                'period'    => $log->period,
                'topic'     => $log->topics_covered ?? '—',
                'sub_batch' => $log->sub_batch ?? 'Whole',
                'present'   => json_decode($log->present_students ?? '[]', true) ?: [],
                'absent'    => json_decode($log->absent_students  ?? '[]', true) ?: [],
            ];
        });

        return response()->json([
            'status'         => 'SUCCESS',
            'total_sessions' => $logs->count(),
            'logs'           => $result,
        ]);
    }

    /**
     * Save/update CIA summary (open-ended, series tests, attendance marks) for a single student.
     * Can be invoked from both Desktop and Mobile student detail modals.
     */
    public function saveStudentCiaSummary(Request $request, $batchSubjectId)
    {
        $staff = $this->getStaff();
        $assessorMobile = $staff->mobile_no ?? \Illuminate\Support\Facades\Session::get('userId');

        $regNo          = $request->input('reg_no');
        $openEndedMark  = $request->has('open_ended_mark') && $request->input('open_ended_mark') !== '' && $request->input('open_ended_mark') !== null ? (float)$request->input('open_ended_mark') : null;
        $openEndedTopic = $request->input('open_ended_topic');
        $test1          = $request->has('test1') && $request->input('test1') !== '' && $request->input('test1') !== null ? (float)$request->input('test1') : null;
        $test2          = $request->has('test2') && $request->input('test2') !== '' && $request->input('test2') !== null ? (float)$request->input('test2') : null;
        $attendanceMark = $request->has('attendance_mark') && $request->input('attendance_mark') !== '' && $request->input('attendance_mark') !== null ? (float)$request->input('attendance_mark') : null;

        if (!$regNo) {
            return response()->json(['success' => false, 'message' => 'Student registration number is required.'], 400);
        }

        // 1. Update PracticalEvaluation (open-ended & attendance marks)
        $eval = PracticalEvaluation::firstOrNew([
            'batch_subject_id' => $batchSubjectId,
            'reg_no'           => $regNo
        ]);
        $eval->assessor_mobile_no = $assessorMobile;
        if ($openEndedMark !== null) {
            $eval->micro_project = min(7.5, max(0, $openEndedMark));
        }
        if ($openEndedTopic !== null) {
            $eval->open_ended_topic = $openEndedTopic;
        }
        if ($attendanceMark !== null) {
            $eval->attendance_marks = min(15, max(0, $attendanceMark));
        }
        $eval->save();

        // 2. Update Practical Tests
        if ($test1 !== null) {
            $t1 = PracticalTest::firstOrCreate(
                ['batch_subject_id' => $batchSubjectId, 'test_name' => 'Test 1'],
                ['questions' => []]
            );
            PracticalTestMark::updateOrCreate(
                ['practical_test_id' => $t1->id, 'reg_no' => $regNo, 'co_tag' => 'CO1'],
                ['marks_obtained' => min(40, max(0, $test1)), 'assessor_mobile_no' => $assessorMobile]
            );
        }

        if ($test2 !== null) {
            $t2 = PracticalTest::firstOrCreate(
                ['batch_subject_id' => $batchSubjectId, 'test_name' => 'Test 2'],
                ['questions' => []]
            );
            PracticalTestMark::updateOrCreate(
                ['practical_test_id' => $t2->id, 'reg_no' => $regNo, 'co_tag' => 'CO3'],
                ['marks_obtained' => min(40, max(0, $test2)), 'assessor_mobile_no' => $assessorMobile]
            );
        }

        // 3. Compute updated consolidated CIA
        $experiments = PracticalExperiment::where('batch_subject_id', $batchSubjectId)->get();
        $expIds = $experiments->pluck('id')->toArray();
        $allExpMarks = PracticalExperimentMark::whereIn('practical_experiment_id', $expIds)
            ->where('reg_no', $regNo)
            ->get();

        $sumExp = $allExpMarks->sum('total_mark');
        $cntExp = $allExpMarks->count();
        $avgLabWork = $cntExp > 0 ? round($sumExp / $cntExp, 2) : 0.0;

        $t1Score = $test1;
        $t2Score = $test2;
        if ($t1Score === null) {
            $t1Obj = PracticalTest::where('batch_subject_id', $batchSubjectId)->where('test_name', 'Test 1')->first();
            if ($t1Obj) {
                $m = PracticalTestMark::where('practical_test_id', $t1Obj->id)->where('reg_no', $regNo)->first();
                $t1Score = $m ? (float)$m->marks_obtained : 0.0;
            } else {
                $t1Score = 0.0;
            }
        }
        if ($t2Score === null) {
            $t2Obj = PracticalTest::where('batch_subject_id', $batchSubjectId)->where('test_name', 'Test 2')->first();
            if ($t2Obj) {
                $m = PracticalTestMark::where('practical_test_id', $t2Obj->id)->where('reg_no', $regNo)->first();
                $t2Score = $m ? (float)$m->marks_obtained : 0.0;
            } else {
                $t2Score = 0.0;
            }
        }

        $avgTest40 = ($t1Score + $t2Score) / 2;
        $scaledTests15 = round(($avgTest40 / 40) * 15, 2);

        $attVal = $attendanceMark ?? (float)($eval->attendance_marks ?? 0);
        $oeVal  = $openEndedMark ?? (float)($eval->micro_project ?? 0);
        $totalCIA = round($avgLabWork + $oeVal + $scaledTests15 + $attVal, 2);

        // Sync StudentSemesterMarks
        try {
            $batchSubj = BatchSubject::find($batchSubjectId);
            if ($batchSubj) {
                \App\Models\StudentSemesterMarks::updateOrCreate(
                    [
                        'reg_no'       => $regNo,
                        'subject_code' => $batchSubj->subject_code,
                        'semester'     => $batchSubj->semester
                    ],
                    [
                        'subject_name'   => $batchSubj->subject_name,
                        'internal_marks' => $totalCIA,
                    ]
                );
            }
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'message' => 'CIA Summary updated successfully!',
            'data'    => [
                'reg_no'               => $regNo,
                'open_ended_mark'      => $oeVal,
                'open_ended_topic'     => $eval->open_ended_topic ?? '',
                'test1_score'          => $t1Score,
                'test2_score'          => $t2Score,
                'avg_test_40'          => round($avgTest40, 2),
                'scaled_series_15'     => $scaledTests15,
                'att_mark_15'          => $attVal,
                'avg_lab_work_375'     => $avgLabWork,
                'total_cia'            => $totalCIA,
                'total_cia_75'         => $totalCIA,
            ]
        ]);
    }
}
