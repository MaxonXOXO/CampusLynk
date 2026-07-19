<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\R26ClassManagement;
use App\Models\Student;
use App\Models\CourseFile;
use App\Models\LessonPlan;

class R26ClassroomController extends Controller
{
    /**
     * View Virtual Classroom (Theory) for Revision 2026.
     */
    public function viewTheoryClassroom($subjectId)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        // Fetch classroom (check standard table first, then fallback to R26)
        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }

        if (!$classroom) {
            abort(404, 'Classroom association not found.');
        }

        // Get enrolled students
        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'sbte_reg_no', 'roll_no', 'academic_status']);

        // Course file data
        $courseFile = CourseFile::firstOrCreate(
            ['batch_subject_id' => $subjectId],
            [
                'parsed_cos' => json_encode([
                    ['id' => 'CO1', 'description' => 'Understand and explain the core concepts of the subject.', 'duration' => 15, 'cognitive_level' => 'Understanding'],
                    ['id' => 'CO2', 'description' => 'Apply theoretical methodologies to solve problems.', 'duration' => 15, 'cognitive_level' => 'Applying'],
                    ['id' => 'CO3', 'description' => 'Analyze systems and evaluate outcomes.', 'duration' => 15, 'cognitive_level' => 'Analyzing'],
                    ['id' => 'CO4', 'description' => 'Formulate, design, or optimize solutions.', 'duration' => 15, 'cognitive_level' => 'Creating']
                ]),
                'parsed_modules' => json_encode([
                    ['module_id' => 'I', 'content' => 'Module 1 Course Contents'],
                    ['module_id' => 'II', 'content' => 'Module 2 Course Contents'],
                    ['module_id' => 'III', 'content' => 'Module 3 Course Contents'],
                    ['module_id' => 'IV', 'content' => 'Module 4 Course Contents']
                ]),
                'parsed_textbooks' => json_encode([
                    'Textbook Reference 1',
                    'Textbook Reference 2'
                ])
            ]
        );

        $lessonPlans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        // Compute student attendance & marks
        $attendanceData = \DB::table('student_attendance')
            ->where('subject_code', $batchSubject->subject_code)
            ->get()
            ->groupBy('reg_no');

        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        // Self-Learning Configurations
        $selfLearningConfigs = $courseFile->self_learning_configs ?: [
            'CO1' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO2' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO3' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO4' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
        ];

        // Fetch MCQ Test Configurations & Attempts for Automation
        $testConfigs = \DB::table('test_configs')
            ->where('subject_code', $batchSubject->subject_code)
            ->where('classroom_id', $batchSubject->classroom_id)
            ->get();
            
        $testIds = $testConfigs->pluck('test_id')->toArray();
        
        $testAttempts = \DB::table('test_attempts')
            ->whereIn('test_id', $testIds)
            ->where('status', 'Completed')
            ->get()
            ->groupBy('reg_no');

        $submissions = \DB::table('student_task_submissions')
            ->where('subject_code', $batchSubject->subject_code)
            ->where('category', 'Assignment')
            ->get()
            ->groupBy('reg_no');

        $studentCiaData = $students->map(function ($student) use ($attendanceData, $academicMarks, $subjectId, $batchSubject, $selfLearningConfigs, $testConfigs, $testAttempts, $submissions) {
            $studentSubmissions = $submissions->get($student->reg_no, collect());
            $studentAttendance = $attendanceData->get($student->reg_no, collect());
            $totalAttendance = $studentAttendance->count();
            $present = $studentAttendance->whereIn('status', ['Present', 'Late'])->count();
            $attPercentage = $totalAttendance > 0 ? ($present / $totalAttendance) * 100 : 100.00;
            
            // Table 2.1 Attendance Marks Conversion
            $attMarks = 0;
            if ($attPercentage >= 90) {
                $attMarks = 5;
            } elseif ($attPercentage >= 85) {
                $attMarks = 4;
            } elseif ($attPercentage >= 80) {
                $attMarks = 3;
            } elseif ($attPercentage >= 75) {
                $attMarks = 2;
            } else {
                $attMarks = 0;
            }
            
            $studentMarks = $academicMarks->get($student->reg_no, collect());
            $studentAttempts = $testAttempts->get($student->reg_no, collect());
            
            $coDetails = [];
            $totalAvgSum = 0.0;
            
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $coMarks = $studentMarks->where('co_tag', $coTag);
                
                $assignmentMark = $coMarks->where('category', 'Self Study: Assignment')->first();
                $mcqMark        = $coMarks->where('category', 'Self Study: MCQ')->first();
                $act3Mark       = $coMarks->where('category', 'Self Study: Act 3')->first();
                $act4Mark       = $coMarks->where('category', 'Self Study: Act 4')->first();
                $act5Mark       = $coMarks->where('category', 'Self Study: Act 5')->first();
                
                $valAssignment = $assignmentMark ? (float)$assignmentMark->marks_obtained : 0.0;
                $valAct3       = $act3Mark ? (float)$act3Mark->marks_obtained : 0.0;
                $valAct4       = $act4Mark ? (float)$act4Mark->marks_obtained : 0.0;
                $valAct5       = $act5Mark ? (float)$act5Mark->marks_obtained : 0.0;

                // MCQ Automated Mark logic
                if ($mcqMark) {
                    $valMcq = (float)$mcqMark->marks_obtained;
                } else {
                    $valMcq = 0.0;
                    $coTest = $testConfigs->filter(function($tc) use ($coTag) {
                        $selected = json_decode($tc->selected_cos, true) ?: [];
                        return in_array($coTag, $selected);
                    })->first();
                    if ($coTest) {
                        $attemptsForTest = $studentAttempts->where('test_id', $coTest->test_id);
                        $maxScore = $attemptsForTest->max('total_score');
                        $mcqMax = (float)($coTest->mcq_count ?: 10);
                        $configMaxMCQ = (float)($selfLearningConfigs[$coTag]['mcq'] ?? 5.0);
                        if ($mcqMax > 0 && $maxScore !== null) {
                            $valMcq = round(($maxScore / $mcqMax) * $configMaxMCQ, 2);
                        }
                    }
                }
                
                $coTotal = $valAssignment + $valMcq + $valAct3 + $valAct4 + $valAct5;
                $totalAvgSum += $coTotal;
                
                $subRecord = $studentSubmissions->where('co_tag', $coTag)->first();
                $subStatus = $subRecord ? $subRecord->status : 'Not Assigned';

                $coDetails[$coTag] = [
                    'assignment' => $valAssignment,
                    'mcq' => $valMcq,
                    'act3' => $valAct3,
                    'act4' => $valAct4,
                    'act5' => $valAct5,
                    'total' => $coTotal,
                    'submission_status' => $subStatus
                ];
            }
            
            $selfLearningMarks = round($totalAvgSum / 4, 2);
            $seriesExamRecord = $studentMarks->where('category', 'Series Exam')->first();
            $seriesExamMarks = $seriesExamRecord ? (float)$seriesExamRecord->marks_obtained : 0.0;
            
            return [
                'reg_no' => $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'attendance_percent' => round($attPercentage, 2),
                'attendance_marks' => $attMarks,
                'self_learning_marks' => $selfLearningMarks,
                'series_exam_marks' => $seriesExamMarks,
                'total_cia' => $attMarks + $selfLearningMarks + $seriesExamMarks,
                'co_details' => $coDetails
            ];
        });

        return view('r26.virtual_classroom_theory', compact('batchSubject', 'classroom', 'students', 'courseFile', 'lessonPlans', 'studentCiaData', 'selfLearningConfigs'));
    }

    /**
     * Upload and parse Revision 2026 Syllabus PDF locally.
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
            $path = $file->store('r26_syllabi', 'public');

            // Execute local Python parser service
            $pyPath = base_path('app/Services/r26_syllabus_parser.py');
            $command = "py " . escapeshellarg($pyPath) . " " . escapeshellarg(storage_path('app/public/' . $path));
            $jsonOutput = shell_exec($command);
            
            $parsedResult = json_decode($jsonOutput, true);
            if (!$parsedResult || $parsedResult['status'] === 'ERROR') {
                throw new \Exception($parsedResult['message'] ?? 'Failed to execute local syllabus parser.');
            }
            
            $data = $parsedResult['data'];
            
            $totalHours = $data['total_hours'] ?: 60;
            $cos = $data['cos'];
            $modules = $data['modules'];
            $packedCopo_matrix = $data['copo_matrix'];
            $topics = $data['detailed_topics'];

            // Calculate durations
            $coCount = count($cos) ?: 4;
            $avgDuration = floor($totalHours / $coCount);
            foreach ($cos as &$co) {
                $co['duration'] = $avgDuration;
            }
            unset($co);

            // Pack credit, ltpr, cie, ese, totalHours and mappings inside parsed_copo JSON
            $packedCopo = [
                'credit' => $data['credits'] ?: 4,
                'l_t_p_r' => $data['teaching_scheme'] ?: '3:1:0:0',
                'cie_marks' => $data['cie_marks'] ?: 40,
                'ese_marks' => $data['ese_marks'] ?: 60,
                'total_hours' => $totalHours,
                'mappings' => $packedCopo_matrix
            ];

            // Save details to course_files table
            $courseFile = CourseFile::updateOrCreate(
                ['batch_subject_id' => $subjectId],
                [
                    'syllabus_pdf_path' => $path,
                    'parsed_cos' => json_encode($cos),
                    'parsed_modules' => json_encode($modules),
                    'parsed_copo' => json_encode($packedCopo),
                    'parsed_textbooks' => json_encode(['Textbook Reference 1', 'Textbook Reference 2'])
                ]
            );

            // Populate Lesson Plans
            LessonPlan::where('batch_subject_id', $subjectId)->delete();

            // Check if cross-batch template already exists for this subject code
            $templateRows = \DB::table('lesson_plan_templates')
                ->where('subject_code', $batchSubject->subject_code)
                ->orderBy('day_no', 'asc')
                ->get();

            if ($templateRows->isNotEmpty()) {
                foreach ($templateRows as $index => $tmp) {
                    LessonPlan::create([
                        'batch_subject_id' => $subjectId,
                        'day_no'           => $tmp->day_no ?? ($index + 1),
                        'co_id'            => $tmp->co_id,
                        'topic_content'    => $tmp->topic_content,
                        'allocated_hours'  => 1,
                        'pedagogy'         => $tmp->pedagogy ?? 'Lecture',
                        'status'           => 'Pending'
                    ]);
                }
            } else {
                $dayNo = 1;
                
                // Loop through parsed topics and generate day rows
                if (!empty($topics)) {
                    foreach ($topics as $t) {
                        for ($h = 1; $h <= $t['hours']; $h++) {
                            if ($dayNo > $totalHours) break 2;
                            
                            LessonPlan::create([
                                'batch_subject_id' => $subjectId,
                                'day_no'           => $dayNo,
                                'co_id'            => $t['co_id'],
                                'topic_content'    => $t['topic'],
                                'allocated_hours'  => 1,
                                'pedagogy'         => $t['pedagogy'],
                                'taxonomy'         => $t['taxonomy'] ?? null,
                                'status'           => 'Pending'
                            ]);
                            $dayNo++;
                        }
                    }
                }
                
                // Pad remaining days if we have fewer than total hours
                while ($dayNo <= $totalHours) {
                    $coTag = !empty($cos) ? $cos[($dayNo - 1) % count($cos)]['id'] : 'CO1';
                    LessonPlan::create([
                        'batch_subject_id' => $subjectId,
                        'day_no'           => $dayNo,
                        'co_id'            => $coTag,
                        'topic_content'    => 'Revision & Doubt Clearing Session',
                        'allocated_hours'  => 1,
                        'pedagogy'         => 'Lecture',
                        'status'           => 'Pending'
                    ]);
                    $dayNo++;
                }
                
                // AUTOMATICALLY APPEND EXACTLY 4 SERIES TESTS SEQUENTIALLY AT THE END
                for ($testIdx = 1; $testIdx <= 4; $testIdx++) {
                    $coTag = !empty($cos) ? $cos[count($cos) - 1]['id'] : 'CO4';
                    LessonPlan::create([
                        'batch_subject_id' => $subjectId,
                        'day_no'           => $dayNo,
                        'co_id'            => $coTag,
                        'topic_content'    => "Series Test $testIdx / Module Evaluation",
                        'allocated_hours'  => 1,
                        'pedagogy'         => 'Exam',
                        'status'           => 'Pending'
                    ]);
                    $dayNo++;
                }
            }

            return response()->json([
                'status' => 'SUCCESS', 
                'message' => 'Syllabus uploaded and parsed locally successfully. Lesson planner initialized.',
                'course_file' => $courseFile
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Print lesson plan for Revision 2026.
     */
    public function printLessonPlan($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return redirect('/')->with('error', 'Please log in to continue.');
        }

        $subject = BatchSubject::find($subjectId);
        if (!$subject) {
            abort(404, 'Subject not found.');
        }

        $plans = LessonPlan::where('batch_subject_id', $subjectId)
            ->orderBy('day_no', 'asc')
            ->get();

        $branchMapping = [
            'CS' => 'Computer Engineering',
            'EL' => 'Electronics Engineering',
            'EE' => 'Electrical & Electronics Engineering',
            'ME' => 'Mechanical Engineering',
            'CE' => 'Civil Engineering'
        ];
        
        $branchCode = strtoupper(Session::get('userBranch', ''));
        $branchName = $branchMapping[$branchCode] ?? 'Engineering';
        $lecturerName = Session::get('userName', 'Assigned Faculty');

        return view('r26.lesson_plan_print', compact('subject', 'plans', 'branchName', 'lecturerName'));
    }

    /**
     * Bulk update lesson plans for Revision 2026.
     */
    public function bulkUpdateLessonPlans(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $rows = $request->input('rows', []);
        $updated = 0;
        foreach ($rows as $row) {
            $id = $row['id'] ?? null;
            if (!$id) continue;
            
            $plan = LessonPlan::where('id', $id)
                ->where('batch_subject_id', $subjectId)
                ->first();
                
            if (!$plan) continue;

            $plan->topic_content   = $row['topic_content']   ?? $plan->topic_content;
            $plan->proposed_date   = $row['proposed_date']   ?? $plan->proposed_date;
            $plan->actual_date     = $row['actual_date']     ?? $plan->actual_date;
            $plan->allocated_hours = $row['allocated_hours'] ?? $plan->allocated_hours;
            $plan->pedagogy        = $row['pedagogy']        ?? $plan->pedagogy;
            $plan->taxonomy        = $row['taxonomy']        ?? $plan->taxonomy;
            $plan->status          = $row['status']          ?? $plan->status;
            $plan->save();
            $updated++;
        }

        return response()->json(['status' => 'SUCCESS', 'message' => "{$updated} lesson plan rows saved successfully."]);
    }

    /**
     * Bulk update Continuous Internal Assessment (CIA) marks for Revision 2026.
     */
    public function bulkUpdateCiaMarks(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.'], 404);
        }

        $rows = $request->input('rows', []);
        $updated = 0;

        foreach ($rows as $row) {
            $regNo = $row['reg_no'] ?? null;
            if (!$regNo) continue;

            $selfLearningVal = isset($row['self_learning_marks']) ? (float)$row['self_learning_marks'] : 0.0;
            $seriesExamVal = isset($row['series_exam_marks']) ? (float)$row['series_exam_marks'] : 0.0;

            // Save Self Learning Marks
            \DB::table('academic_marks')->updateOrInsert(
                [
                    'reg_no' => $regNo,
                    'batch_subject_id' => $subjectId,
                    'category' => 'Self Learning',
                ],
                [
                    'subject_code' => $batchSubject->subject_code,
                    'max_marks' => 15,
                    'marks_obtained' => $selfLearningVal,
                    'entered_by' => $userId,
                    'updated_at' => now(),
                ]
            );

            // Save Series Exam Marks
            \DB::table('academic_marks')->updateOrInsert(
                [
                    'reg_no' => $regNo,
                    'batch_subject_id' => $subjectId,
                    'category' => 'Series Exam',
                ],
                [
                    'subject_code' => $batchSubject->subject_code,
                    'max_marks' => 20,
                    'marks_obtained' => $seriesExamVal,
                    'entered_by' => $userId,
                    'updated_at' => now(),
                ]
            );

            $updated++;
        }

        return response()->json(['status' => 'SUCCESS', 'message' => "{$updated} student CIA marks sheets saved successfully."]);
    }

    public function bulkUpdateSelfLearningMarks(Request $request, $subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.'], 404);
        }

        // Save Configurations
        $configs = $request->input('configs');
        if ($configs) {
            $courseFile = CourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
            $courseFile->self_learning_configs = $configs;
            $courseFile->save();
        }

        $rows = $request->input('rows', []);
        $updated = 0;

        foreach ($rows as $row) {
            $regNo = $row['reg_no'] ?? null;
            if (!$regNo) continue;

            $coData = $row['co_details'] ?? [];
            foreach ($coData as $coTag => $marks) {
                // Clear old Self Study marks to prevent duplicate accumulation
                \DB::table('academic_marks')
                    ->where('reg_no', $regNo)
                    ->where('batch_subject_id', $subjectId)
                    ->where('co_tag', $coTag)
                    ->where('category', 'like', 'Self Study:%')
                    ->delete();

                foreach (['assignment', 'mcq', 'act3', 'act4', 'act5'] as $field) {
                    $val = isset($marks[$field]) ? (float)$marks[$field] : 0.0;
                    
                    if ($val > 0 || in_array($field, ['assignment', 'mcq'])) {
                        \DB::table('academic_marks')->insert([
                            'reg_no' => $regNo,
                            'batch_subject_id' => $subjectId,
                            'subject_code' => $batchSubject->subject_code,
                            'category' => 'Self Study: ' . ucfirst($field),
                            'co_tag' => $coTag,
                            'max_marks' => 15,
                            'marks_obtained' => $val,
                            'entered_by' => $userId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        if ($field === 'assignment') {
                            \DB::table('student_task_submissions')
                                ->where('reg_no', $regNo)
                                ->where('subject_code', $batchSubject->subject_code)
                                ->where('co_tag', $coTag)
                                ->where('category', 'Assignment')
                                ->where('status', 'Submitted')
                                ->update(['status' => 'Graded', 'updated_at' => now()]);
                        }
                    }
                }
            }
            $updated++;
        }

        return response()->json(['status' => 'SUCCESS', 'message' => "{$updated} student self-learning sheets updated successfully."]);
    }

    /**
     * Print the Self-Learning Activities evaluation report.
     */
    public function printSelfLearningReport($subjectId)
    {
        $userId = Session::get('userId');
        if (!$userId) {
            abort(401, 'Unauthorized.');
        }

        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }
        
        $students = Student::where('classroom_id', $batchSubject->classroom_id)
            ->orderByRaw('CAST(roll_no AS UNSIGNED) ASC')
            ->orderBy('name', 'asc')
            ->get();

        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        if (!$courseFile) {
            $courseFile = new CourseFile();
        }

        // Self-Learning Configurations
        $selfLearningConfigs = $courseFile->self_learning_configs ?: [
            'CO1' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO2' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO3' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
            'CO4' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
        ];

        // Fetch MCQ Test Configurations & Attempts for Automation
        $testConfigs = \DB::table('test_configs')
            ->where('subject_code', $batchSubject->subject_code)
            ->where('classroom_id', $batchSubject->classroom_id)
            ->get();
            
        $testIds = $testConfigs->pluck('test_id')->toArray();
        
        $testAttempts = \DB::table('test_attempts')
            ->whereIn('test_id', $testIds)
            ->where('status', 'Completed')
            ->get()
            ->groupBy('reg_no');

        $academicMarks = \DB::table('academic_marks')
            ->where('batch_subject_id', $subjectId)
            ->get()
            ->groupBy('reg_no');

        $studentCiaData = $students->map(function ($student) use ($academicMarks, $selfLearningConfigs, $testConfigs, $testAttempts) {
            $studentMarks = $academicMarks->get($student->reg_no, collect());
            $studentAttempts = $testAttempts->get($student->reg_no, collect());
            
            $coDetails = [];
            $totalAvgSum = 0.0;
            
            foreach (['CO1', 'CO2', 'CO3', 'CO4'] as $coTag) {
                $coMarks = $studentMarks->where('co_tag', $coTag);
                
                $assignmentMark = $coMarks->where('category', 'Self Study: Assignment')->first();
                $mcqMark        = $coMarks->where('category', 'Self Study: MCQ')->first();
                $act3Mark       = $coMarks->where('category', 'Self Study: Act 3')->first();
                $act4Mark       = $coMarks->where('category', 'Self Study: Act 4')->first();
                $act5Mark       = $coMarks->where('category', 'Self Study: Act 5')->first();
                
                $valAssignment = $assignmentMark ? (float)$assignmentMark->marks_obtained : 0.0;
                $valAct3       = $act3Mark ? (float)$act3Mark->marks_obtained : 0.0;
                $valAct4       = $act4Mark ? (float)$act4Mark->marks_obtained : 0.0;
                $valAct5       = $act5Mark ? (float)$act5Mark->marks_obtained : 0.0;

                // MCQ Automated Mark logic
                if ($mcqMark) {
                    $valMcq = (float)$mcqMark->marks_obtained;
                } else {
                    $valMcq = 0.0;
                    $coTest = $testConfigs->filter(function($tc) use ($coTag) {
                        $selected = json_decode($tc->selected_cos, true) ?: [];
                        return in_array($coTag, $selected);
                    })->first();
                    if ($coTest) {
                        $attemptsForTest = $studentAttempts->where('test_id', $coTest->test_id);
                        $maxScore = $attemptsForTest->max('total_score');
                        $mcqMax = (float)($coTest->mcq_count ?: 10);
                        $configMaxMCQ = (float)($selfLearningConfigs[$coTag]['mcq'] ?? 5.0);
                        if ($mcqMax > 0 && $maxScore !== null) {
                            $valMcq = round(($maxScore / $mcqMax) * $configMaxMCQ, 2);
                        }
                    }
                }
                
                $coTotal = $valAssignment + $valMcq + $valAct3 + $valAct4 + $valAct5;
                $totalAvgSum += $coTotal;
                
                $coDetails[$coTag] = [
                    'assignment' => $valAssignment,
                    'mcq' => $valMcq,
                    'act3' => $valAct3,
                    'act4' => $valAct4,
                    'act5' => $valAct5,
                    'total' => $coTotal
                ];
            }
            
            return [
                'reg_no' => $student->reg_no,
                'name' => $student->name,
                'roll_no' => $student->roll_no,
                'self_learning_marks' => round($totalAvgSum / 4, 2),
                'co_details' => $coDetails
            ];
        });

        return view('r26.self_learning_print', compact('batchSubject', 'classroom', 'students', 'studentCiaData', 'selfLearningConfigs'));
    }

    /**
     * Save Assignment questions for a specific Course Outcome (CO).
     */
    public function saveAssignment(Request $request, $subjectId, $coTag)
    {
        $batchSubject = BatchSubject::with('classroom')->find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
        }

        $courseFile = CourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        
        $questions = $request->input('questions', []);
        $savedQuestions = $courseFile->assignment_questions ?? [];
        $savedQuestions[$coTag] = $questions;
        $courseFile->assignment_questions = $savedQuestions;

        // Save due date
        $dueDate = $request->input('due_date');
        $deadlines = $courseFile->assignment_deadlines ?? [];
        $deadlines[$coTag] = [
            'deadline' => $dueDate,
            'locked' => isset($deadlines[$coTag]['locked']) ? $deadlines[$coTag]['locked'] : false
        ];
        $courseFile->assignment_deadlines = $deadlines;

        $courseFile->save();

        // Register in the general question bank
        $branchCode = $batchSubject->classroom->branch ?? 'General';
        
        \DB::table('question_bank')
            ->where('batch_subject_id', $subjectId)
            ->where('co_tag', $coTag)
            ->where('type', 'Descriptive')
            ->delete();

        $btMapping = [
            'Remember' => 'R',
            'Understand' => 'U',
            'Apply' => 'Ap',
            'Analyze' => 'An',
            'Evaluate' => 'E',
            'Create' => 'C'
        ];

        foreach ($questions as $q) {
            $rawBt = $q['bt_level'] ?? 'Understand';
            $mappedBt = $btMapping[$rawBt] ?? substr($rawBt, 0, 5);

            try {
                \DB::table('question_bank')->insert([
                    'branch_code' => $branchCode,
                    'subject_code' => $batchSubject->subject_code,
                    'batch_subject_id' => $subjectId,
                    'type' => 'Descriptive',
                    'co_tag' => $coTag,
                    'cognitive_level' => $mappedBt,
                    'question_text' => $q['question'],
                    'marks' => intval($q['marks'] ?? 5),
                    'options' => json_encode([]),
                    'rubric' => json_encode([
                        [
                            'desc' => $q['scheme'] ?: 'Evaluation guidelines',
                            'mark' => intval($q['marks'] ?? 5)
                        ]
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } catch (\Exception $e) {
                // If subject_code is not registered in syllabus_registry yet, log it and proceed (saving to course file succeeds)
                \Illuminate\Support\Facades\Log::info("Skipped question_bank entry for {$batchSubject->subject_code} because: " . $e->getMessage());
            }
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Assignment questions saved successfully.']);
    }

    /**
     * Notify students on their dashboard regarding the assigned CO assignment.
     */
    public function notifyAssignment(Request $request, $subjectId, $coTag)
    {
        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.']);
        }

        $courseFile = CourseFile::firstOrCreate(['batch_subject_id' => $subjectId]);
        $deadlines = $courseFile->assignment_deadlines ?? [];
        if (!isset($deadlines[$coTag])) {
            $deadlines[$coTag] = ['deadline' => date('Y-m-d'), 'locked' => true];
        } else {
            $deadlines[$coTag]['locked'] = true;
        }
        $courseFile->assignment_deadlines = $deadlines;
        $courseFile->save();

        $students = Student::where('classroom_id', $batchSubject->classroom_id)->get();
        
        foreach ($students as $st) {
            \DB::table('student_task_submissions')->updateOrInsert(
                [
                    'reg_no' => $st->reg_no,
                    'subject_code' => $batchSubject->subject_code,
                    'co_tag' => $coTag,
                    'category' => 'Assignment',
                ],
                [
                    'status' => 'Assigned',
                    'updated_at' => now(),
                ]
            );
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Assignment activity notifications sent to student dashboards.']);
    }

    /**
     * Print Assignment Question Paper (QP) for a specific Course Outcome.
     */
    public function printAssignmentQp($subjectId, $coTag)
    {
        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }
        
        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        $questions = $courseFile ? ($courseFile->assignment_questions[$coTag] ?? []) : [];

        return view('r26.assignment_qp_print', compact('batchSubject', 'classroom', 'questions', 'coTag', 'courseFile'));
    }

    /**
     * Print Assignment Evaluation Scheme for a specific Course Outcome.
     */
    public function printAssignmentScheme($subjectId, $coTag)
    {
        $batchSubject = BatchSubject::find($subjectId);
        if (!$batchSubject) {
            abort(404, 'Subject not found.');
        }

        $classroom = ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        if (!$classroom) {
            $classroom = R26ClassManagement::where('classroom_id', $batchSubject->classroom_id)->first();
        }
        
        $courseFile = CourseFile::where('batch_subject_id', $subjectId)->first();
        $questions = $courseFile ? ($courseFile->assignment_questions[$coTag] ?? []) : [];

        return view('r26.assignment_scheme_print', compact('batchSubject', 'classroom', 'questions', 'coTag', 'courseFile'));
    }
}
