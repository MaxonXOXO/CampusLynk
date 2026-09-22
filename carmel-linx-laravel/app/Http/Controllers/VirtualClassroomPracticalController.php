<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BatchSubject;
use App\Models\Student;
use App\Models\R26PracticalExperimentEvaluation;
use App\Models\R26OpenEndedEvaluation;
use App\Models\R26PracticalSeriesEvaluation;
use App\Models\R26StudentLabBatch;
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
}
