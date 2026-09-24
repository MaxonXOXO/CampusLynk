<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\BatchSubject;
use App\Models\SubjectStaffAssignment;
use App\Models\Student;
use App\Models\LessonPlan;

class AttendanceController extends Controller
{
    /**
     * Render the standalone attendance log page.
     */
    public function viewPage()
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return redirect('/login')->with('error', 'Unauthorized access.');
        }

        return view('attendance_log');
    }

    /**
     * Get list of active subjects/batches for the logged-in staff member.
     */
    public function getActiveSubjects()
    {
        $staffMobile = Session::get('userId');
        $role = Session::get('userRole');

        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        // HOD, Workshop Superintendent, Principal can access all subjects
        if (in_array($role, ['HOD', 'Workshop Superintendent', 'Principal'])) {
            $subjects = BatchSubject::orderBy('classroom_id', 'asc')
                ->orderBy('semester', 'asc')
                ->get();
        } else {
            // Other staff members (Lecturer, Demonstrator, Trade Instructor, etc.) see only assigned subjects
            $assignedIds = SubjectStaffAssignment::where('staff_mobile_no', $staffMobile)->pluck('batch_subject_id');
            $subjects = BatchSubject::whereIn('id', $assignedIds)
                ->orderBy('classroom_id', 'asc')
                ->orderBy('semester', 'asc')
                ->get();
        }

        return response()->json([
            'status' => 'SUCCESS',
            'subjects' => $subjects
        ]);
    }

    /**
     * Get students and lesson plans for a specific subject/batch.
     */
    public function getSubjectDetails($id)
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubject = BatchSubject::findOrFail($id);

        // Fetch students ordered by roll number, then name
        $students = Student::where('classroom_id', $batchSubject->classroom_id)
            ->whereIn('status', ['Approved', 'APPROVED', 'approved'])
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no']);

        // Fetch pending/in-progress lesson plans for dropdown selection
        $lessonPlans = LessonPlan::where('batch_subject_id', $id)
            ->orderBy('id', 'asc')
            ->get(['id', 'topic_content', 'co_id', 'status']);

        return response()->json([
            'status' => 'SUCCESS',
            'students' => $students,
            'lesson_plans' => $lessonPlans,
            'classroom_id' => $batchSubject->classroom_id,
            'subject_type' => $batchSubject->subject_type
        ]);
    }

    /**
     * Save the Class Log and Attendance data.
     */
    public function saveAttendance(Request $request)
    {
        $role = Session::get('userRole');
        $recordedBy = Session::get('userId');
        if (!$role || $role === 'Student' || !$recordedBy) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'batch_subject_id' => 'required|exists:batch_subjects,id',
            'date' => 'required|date',
            'periods' => 'required|array|min:1',
            'periods.*' => 'integer|min:1|max:7',
            'lesson_plan_id' => 'nullable|integer',
            'topics_covered' => 'required|string',
            'present_students' => 'nullable|array',
            'absent_students' => 'nullable|array',
            'sub_batch' => 'nullable|string|in:Whole,1,2',
        ]);

        $subBatch = $request->input('sub_batch', 'Whole');

        DB::transaction(function () use ($request, $recordedBy, $subBatch) {
            foreach ($request->periods as $period) {
                // Pre-schedule or update existing class log & attendance
                $exists = DB::table('class_logs_attendance')
                    ->where('batch_subject_id', $request->batch_subject_id)
                    ->where('date', $request->date)
                    ->where('period', $period)
                    ->where('sub_batch', $subBatch)
                    ->first();

                if ($exists) {
                    DB::table('class_logs_attendance')
                        ->where('id', $exists->id)
                        ->update([
                            'lesson_plan_id' => $request->lesson_plan_id,
                            'topics_covered' => $request->topics_covered,
                            'present_students' => json_encode($request->present_students ?? []),
                            'absent_students' => json_encode($request->absent_students ?? []),
                            'recorded_by' => $recordedBy,
                            'updated_at' => now(),
                        ]);
                } else {
                    DB::table('class_logs_attendance')->insert([
                        'batch_subject_id' => $request->batch_subject_id,
                        'date' => $request->date,
                        'period' => $period,
                        'lesson_plan_id' => $request->lesson_plan_id,
                        'topics_covered' => $request->topics_covered,
                        'present_students' => json_encode($request->present_students ?? []),
                        'absent_students' => json_encode($request->absent_students ?? []),
                        'sub_batch' => $subBatch,
                        'recorded_by' => $recordedBy,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });

        // If a lesson plan was selected, mark it as Completed
        if ($request->lesson_plan_id) {
            $lp = LessonPlan::find($request->lesson_plan_id);
            if ($lp && $lp->status === 'Pending') {
                $lp->status = 'Completed';
                $lp->status = 'Completed'; // Set status to Completed when checked off
                $lp->actual_date = $request->date;
                $lp->save();
            }
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Class log and attendance recorded successfully!'
        ]);
    }

    /**
     * Get tutor class students list to assign roll numbers.
     */
    public function getTutorStudents()
    {
        $staffMobile = Session::get('userId');
        $role = Session::get('userRole');

        if (!$staffMobile || !in_array($role, ['Tutor', 'HOD', 'Lecturer', 'Workshop Superintendent'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        // Find classroom managed by this tutor (staff mobile matches classroom advisor/tutor)
        $classroom = DB::table('class_management')
            ->where('tutor_mobile_no', $staffMobile)
            ->first();

        if (!$classroom) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'No classroom assigned as advisor/tutor to your profile.'
            ]);
        }

        $students = Student::where('classroom_id', $classroom->classroom_id)
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no', 'sbte_reg_no']);

        return response()->json([
            'status' => 'SUCCESS',
            'classroom_id' => $classroom->classroom_id,
            'students' => $students
        ]);
    }

    /**
     * Update student roll numbers in bulk.
     */
    public function updateRollNumbers(Request $request)
    {
        $role = Session::get('userRole');
        if (!in_array($role, ['Tutor', 'HOD', 'Lecturer', 'Workshop Superintendent'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'roll_numbers' => 'required|array',
            'roll_numbers.*.reg_no' => 'required|exists:students,reg_no',
            'roll_numbers.*.roll_no' => 'nullable|integer|min:1'
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->roll_numbers as $item) {
                Student::where('reg_no', $item['reg_no'])->update([
                    'roll_no' => $item['roll_no'] ?: null
                ]);
            }
        });

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Student roll numbers updated successfully!'
        ]);
    }

    /**
     * Get attendance reports (logs and matrix) for a specific subject.
     */
    public function getReports($batchSubjectId)
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        // 1. Fetch Class Attendance Logs
        $logs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->orderBy('date', 'desc')
            ->orderBy('period', 'desc')
            ->get();

        // Decode JSON arrays for counts
        foreach ($logs as $log) {
            $log->present_count = count(json_decode($log->present_students ?? '[]'));
            $log->absent_count = count(json_decode($log->absent_students ?? '[]'));
        }

        // 2. Fetch Date-Wise Attendance Matrix
        $batchSubject = BatchSubject::findOrFail($batchSubjectId);
        $students = Student::where('classroom_id', $batchSubject->classroom_id)
            ->whereIn('status', ['Approved', 'APPROVED', 'approved'])
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no']);

        // Gather unique date/periods
        $dates = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->orderBy('date', 'asc')
            ->orderBy('period', 'asc')
            ->get(['date', 'period']);

        $matrix = [];
        foreach ($students as $s) {
            $attendanceData = [];
            foreach ($dates as $d) {
                // Find log record for this date and period
                $log = DB::table('class_logs_attendance')
                    ->where('batch_subject_id', $batchSubjectId)
                    ->where('date', $d->date)
                    ->where('period', $d->period)
                    ->first();
                
                $status = '-'; // Not marked
                if ($log) {
                    $presentList = json_decode($log->present_students ?? '[]', true);
                    $absentList = json_decode($log->absent_students ?? '[]', true);
                    if (in_array($s->reg_no, $presentList)) {
                        $status = 'P';
                    } elseif (in_array($s->reg_no, $absentList)) {
                        $status = 'A';
                    }
                }
                $key = $d->date . ' | P' . $d->period;
                $attendanceData[$key] = $status;
            }
            $matrix[] = [
                'roll_no' => $s->roll_no,
                'name' => $s->name,
                'reg_no' => $s->reg_no,
                'attendance' => $attendanceData
            ];
        }

        return response()->json([
            'status' => 'SUCCESS',
            'logs' => $logs,
            'dates' => $dates,
            'matrix' => $matrix
        ]);
    }

    /**
     * Check if an attendance session already exists for a subject, date, and period.
     */
    public function checkAttendanceSessionExists(Request $request)
    {
        $role = Session::get('userRole');
        $userId = Session::get('userId');
        if (!$role || $role === 'Student' || !$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'batch_subject_id' => 'required|exists:batch_subjects,id',
            'date' => 'required|date',
            'period' => 'required|integer|min:1|max:7',
            'sub_batch' => 'nullable|string|in:Whole,1,2',
        ]);

        $subBatch = $request->input('sub_batch', 'Whole');

        $exists = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $request->batch_subject_id)
            ->where('date', $request->date)
            ->where('period', $request->period)
            ->where('sub_batch', $subBatch)
            ->first();

        return response()->json([
            'status' => 'SUCCESS',
            'exists' => (bool)$exists,
            'log' => $exists ? [
                'id' => $exists->id,
                'batch_subject_id' => $exists->batch_subject_id,
                'date' => $exists->date,
                'period' => $exists->period,
                'lesson_plan_id' => $exists->lesson_plan_id,
                'topics_covered' => $exists->topics_covered,
                'present_count' => count(json_decode($exists->present_students ?? '[]', true) ?: []),
                'absent_count' => count(json_decode($exists->absent_students ?? '[]', true) ?: []),
                'recorded_by' => $exists->recorded_by,
                'sub_batch' => $exists->sub_batch,
            ] : null,
        ]);
    }

    /**
     * Delete a class log and ensure referential safety with student_attendance and lesson_plans.
     */
    public function deleteClassLog(Request $request, $id)
    {
        $role = Session::get('userRole');
        $userId = Session::get('userId');
        if (!$role || $role === 'Student' || !$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $log = DB::table('class_logs_attendance')->where('id', $id)->first();
        if (!$log) {
            return response()->json(['status' => 'ERROR', 'message' => 'Class log entry not found.'], 404);
        }

        // Authorization check: User must be the recorder, assigned staff, HOD, or Principal
        $batchSubject = BatchSubject::find($log->batch_subject_id);
        $isRecorder = ($log->recorded_by === $userId);
        $isAssigned = false;
        if ($batchSubject) {
            $isAssigned = SubjectStaffAssignment::where('batch_subject_id', $batchSubject->id)
                ->where('staff_mobile_no', $userId)
                ->exists();
        }
        $isPrivileged = in_array($role, ['HOD', 'Principal', 'Workshop Superintendent', 'Tutor']);

        if (!$isRecorder && !$isAssigned && !$isPrivileged) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized to delete this log.'], 403);
        }

        DB::transaction(function () use ($log, $id, $batchSubject) {
            // 1. Revert lesson plan status if applicable and no other class log references it
            if ($log->lesson_plan_id) {
                $otherReferences = DB::table('class_logs_attendance')
                    ->where('lesson_plan_id', $log->lesson_plan_id)
                    ->where('id', '!=', $id)
                    ->exists();

                if (!$otherReferences) {
                    LessonPlan::where('id', $log->lesson_plan_id)->update([
                        'status' => 'Pending',
                        'actual_date' => null,
                        'actual_hours' => null,
                    ]);
                }
            }

            // 2. Safe cleanup of corresponding student_attendance records if they exist
            if ($batchSubject) {
                DB::table('student_attendance')
                    ->where('subject_code', $batchSubject->subject_code)
                    ->where('date', $log->date)
                    ->when($log->lesson_plan_id, function ($q, $lpId) {
                        return $q->where('lesson_plan_id', $lpId);
                    })
                    ->when($log->sub_batch && $log->sub_batch !== 'Whole', function ($q) use ($log) {
                        return $q->where('sub_batch', $log->sub_batch);
                    })
                    ->delete();
            }

            // 3. Delete the class log entry
            DB::table('class_logs_attendance')->where('id', $id)->delete();
        });

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Class log and associated attendance deleted successfully.',
        ]);
    }

    /**
     * Get aggregated attendance summary for all students in a classroom across all subjects.
     */
    public function getTutorAttendanceSummary(Request $request, $classroomId)
    {
        $role = Session::get('userRole');
        $userId = Session::get('userId');
        if (!$role || $role === 'Student' || !$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $classroom = DB::table('class_management')->where('classroom_id', $classroomId)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $classroomId)->first();
        }
        if (!$classroom) {
            return response()->json(['status' => 'ERROR', 'message' => 'Classroom not found.'], 404);
        }

        // Authorization: Tutor of the class, or HOD / Principal
        $isTutor = ($classroom->tutor_mobile_no === $userId);
        $isPrivileged = in_array($role, ['HOD', 'Principal', 'Workshop Superintendent']);
        if (!$isTutor && !$isPrivileged) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access to classroom summary.'], 403);
        }

        $students = Student::where('classroom_id', $classroomId)
            ->whereIn('status', ['Approved', 'APPROVED', 'approved'])
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no', 'adm_no']);

        $batchSubjects = BatchSubject::where('classroom_id', $classroomId)->get();
        $subjectIds = $batchSubjects->pluck('id')->toArray();

        // Get all class logs for this classroom's subjects
        $logs = DB::table('class_logs_attendance')
            ->whereIn('batch_subject_id', $subjectIds)
            ->get();

        $totalConductedOverall = $logs->count();

        $studentSummaries = [];
        $lowAttendanceCount = 0;

        foreach ($students as $s) {
            $studentIds = array_filter([$s->reg_no, $s->adm_no]);
            $attendedCount = 0;

            foreach ($logs as $log) {
                $pList = json_decode($log->present_students ?? '[]', true) ?: [];
                if (!empty(array_intersect($studentIds, $pList))) {
                    $attendedCount++;
                }
            }

            $percentage = $totalConductedOverall > 0 
                ? round(($attendedCount / $totalConductedOverall) * 100, 1) 
                : 0.0;

            if ($percentage < 75.0 && $totalConductedOverall > 0) {
                $lowAttendanceCount++;
            }

            $studentSummaries[] = [
                'reg_no' => $s->reg_no,
                'name' => $s->name,
                'roll_no' => $s->roll_no,
                'total_conducted' => $totalConductedOverall,
                'total_attended' => $attendedCount,
                'attendance_percentage' => $percentage,
                'is_shortage' => ($percentage < 75.0 && $totalConductedOverall > 0),
            ];
        }

        return response()->json([
            'status' => 'SUCCESS',
            'classroom_id' => $classroomId,
            'total_students' => $students->count(),
            'total_conducted' => $totalConductedOverall,
            'low_attendance_count' => $lowAttendanceCount,
            'students' => $studentSummaries,
        ]);
    }

    /**
     * Export attendance register as CSV with deterministic ordering, stable headers, and UTF-8 encoding.
     */
    public function exportAttendanceRegisterCsv(Request $request, $subjectId)
    {
        $role = Session::get('userRole');
        $userId = Session::get('userId');
        if (!$role || $role === 'Student' || !$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);

        // Fetch students ordered deterministically
        $students = Student::where('classroom_id', $batchSubject->classroom_id)
            ->whereIn('status', ['Approved', 'APPROVED', 'approved'])
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no', 'adm_no']);

        // Fetch logs ordered deterministically by date asc, period asc, id asc
        $logs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $subjectId)
            ->orderBy('date', 'asc')
            ->orderBy('period', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $filename = 'attendance_register_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $batchSubject->subject_code) . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($students, $logs) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Build Header Row
            $headerRow = ['Roll No', 'Register No', 'Student Name'];
            foreach ($logs as $log) {
                $headerRow[] = $log->date . ' (P' . $log->period . ($log->sub_batch && $log->sub_batch !== 'Whole' ? ' - B' . $log->sub_batch : '') . ')';
            }
            $headerRow[] = 'Total Classes';
            $headerRow[] = 'Classes Attended';
            $headerRow[] = 'Attendance %';

            fputcsv($handle, $headerRow);

            $totalLogs = $logs->count();

            // Build Data Rows
            foreach ($students as $student) {
                $studentIds = array_filter([$student->reg_no, $student->adm_no]);
                $attendedCount = 0;
                $row = [
                    $student->roll_no ?? '-',
                    $student->reg_no,
                    $student->name,
                ];

                foreach ($logs as $log) {
                    $pList = json_decode($log->present_students ?? '[]', true) ?: [];
                    $aList = json_decode($log->absent_students ?? '[]', true) ?: [];

                    if (!empty(array_intersect($studentIds, $pList))) {
                        $row[] = 'P';
                        $attendedCount++;
                    } elseif (!empty(array_intersect($studentIds, $aList))) {
                        $row[] = 'A';
                    } else {
                        $row[] = '-';
                    }
                }

                $pct = $totalLogs > 0 ? round(($attendedCount / $totalLogs) * 100, 1) : 0.0;
                $row[] = $totalLogs;
                $row[] = $attendedCount;
                $row[] = $pct . '%';

                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get lab batch setup details for modal/configuration.
     */
    public function getLabBatchSetup($id)
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubject = BatchSubject::findOrFail($id);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->where(function($q) {
                $q->where('status', 'Approved')->orWhere('status', 'Active');
            })
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no']);

        $labBatches = \App\Models\R26StudentLabBatch::where('batch_subject_id', $id)->pluck('lab_batch', 'reg_no');
        if ($labBatches->isEmpty()) {
            $siblingIds = BatchSubject::where('classroom_id', $batchSubject->classroom_id)
                ->where('semester', $batchSubject->semester)
                ->where('id', '!=', $id)
                ->pluck('id');
            if ($siblingIds->isNotEmpty()) {
                $labBatches = \App\Models\R26StudentLabBatch::whereIn('batch_subject_id', $siblingIds)->pluck('lab_batch', 'reg_no');
            }
        }

        $studentList = $students->map(function($s) use ($labBatches) {
            $b = $labBatches->get($s->reg_no);
            $bNorm = ($b === '1' || $b === 'Batch 1' || $b === 'Batch A') ? '1' : (($b === '2' || $b === 'Batch 2' || $b === 'Batch B') ? '2' : $b);
            return [
                'reg_no' => $s->reg_no,
                'name' => $s->name,
                'roll_no' => $s->roll_no,
                'lab_batch' => $bNorm ?: '1',
            ];
        });

        return response()->json([
            'status' => 'SUCCESS',
            'subject_id' => $batchSubject->id,
            'subject_name' => $batchSubject->subject_name,
            'classroom_id' => $batchSubject->classroom_id,
            'semester' => $batchSubject->semester,
            'lab_batch_mode' => $batchSubject->lab_batch_mode ?: 'split',
            'lab_batch_cutoff' => $batchSubject->lab_batch_cutoff,
            'is_configured' => $labBatches->isNotEmpty() || $batchSubject->lab_batch_cutoff !== null,
            'total_students' => $students->count(),
            'students' => $studentList,
        ]);
    }

    /**
     * Save practical lab batch division (Full vs Split, cutoff roll, or individual assignments).
     */
    public function saveLabBatchAssignments(Request $request, $subjectId)
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubject = BatchSubject::findOrFail($subjectId);
        $mode = $request->input('mode', 'split'); // 'full' or 'split'
        $cutoffRoll = $request->input('cutoff_roll'); // e.g. 25
        $applyToClassroom = (bool)$request->input('apply_to_classroom', true);
        $assignments = $request->input('assignments'); // optional array of { reg_no, lab_batch }

        $targetSubjectIds = [$batchSubject->id];
        if ($applyToClassroom) {
            $siblingIds = BatchSubject::where('classroom_id', $batchSubject->classroom_id)
                ->where('semester', $batchSubject->semester)
                ->pluck('id')
                ->toArray();
            $targetSubjectIds = array_unique(array_merge($targetSubjectIds, $siblingIds));
        }

        // Update mode & cutoff on target batch_subjects
        BatchSubject::whereIn('id', $targetSubjectIds)->update([
            'lab_batch_mode' => $mode,
            'lab_batch_cutoff' => ($cutoffRoll !== null && $cutoffRoll !== '') ? (int)$cutoffRoll : null,
        ]);

        $students = Student::getClassroomStudentsQuery($batchSubject->classroom_id)
            ->where(function($q) {
                $q->where('status', 'Approved')->orWhere('status', 'Active');
            })
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END, roll_no ASC')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'roll_no']);

        foreach ($targetSubjectIds as $tId) {
            if ($mode === 'full') {
                // Delete split assignments or assign 'Whole'
                \App\Models\R26StudentLabBatch::where('batch_subject_id', $tId)->delete();
            } elseif (!empty($assignments) && is_array($assignments)) {
                // Explicit student assignment map
                foreach ($assignments as $item) {
                    $reg = $item['reg_no'] ?? null;
                    $b = $item['lab_batch'] ?? '1';
                    if ($reg) {
                        \App\Models\R26StudentLabBatch::updateOrCreate(
                            ['batch_subject_id' => $tId, 'reg_no' => $reg],
                            ['lab_batch' => $b]
                        );
                    }
                }
            } elseif ($cutoffRoll) {
                // Cutoff roll number split
                $cutoff = (int)$cutoffRoll;
                foreach ($students as $s) {
                    $b = ($s->roll_no !== null && (int)$s->roll_no <= $cutoff) ? '1' : '2';
                    \App\Models\R26StudentLabBatch::updateOrCreate(
                        ['batch_subject_id' => $tId, 'reg_no' => $s->reg_no],
                        ['lab_batch' => $b]
                    );
                }
            } else {
                // Auto 50/50 split
                $mid = (int)ceil($students->count() / 2);
                foreach ($students as $idx => $s) {
                    $b = ($idx < $mid) ? '1' : '2';
                    \App\Models\R26StudentLabBatch::updateOrCreate(
                        ['batch_subject_id' => $tId, 'reg_no' => $s->reg_no],
                        ['lab_batch' => $b]
                    );
                }
            }
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Lab batch configuration saved successfully!' . ($applyToClassroom ? ' Applied to ' . count($targetSubjectIds) . ' subjects.' : '')
        ]);
    }

    /**
     * Cross-reconciles conducted dates on practical_experiments against class_logs_attendance,
     * and clears conducted_date from experiments that were never conducted or have only 0-mark placeholders.
     */
    public static function syncPracticalExperimentsWithLogs($batchSubjectId)
    {
        try {
            if (!$batchSubjectId) return;

            $experiments = \App\Models\PracticalExperiment::where('batch_subject_id', $batchSubjectId)->get();
            if ($experiments->isEmpty()) {
                return;
            }

            $classLogs = DB::table('class_logs_attendance')
                ->where('batch_subject_id', $batchSubjectId)
                ->orderBy('date', 'desc')
                ->get(['id', 'date', 'period', 'topics_covered', 'lesson_plan_id']);

            $expIds = $experiments->pluck('id')->toArray();
            $marksWithScores = DB::table('practical_experiment_marks')
                ->whereIn('practical_experiment_id', $expIds)
                ->where('total_mark', '>', 0)
                ->get(['practical_experiment_id', 'evaluation_date', 'total_mark']);

            foreach ($experiments as $exp) {
                $expNo = trim((string)$exp->experiment_no);
                $expTitle = strtolower(trim((string)($exp->title ?? '')));
                $matchedLogDate = null;

                // 1. Check if class_logs_attendance covers this experiment
                if ($classLogs->isNotEmpty()) {
                    foreach ($classLogs as $l) {
                        $t = trim((string)($l->topics_covered ?? ''));
                        if (empty($t)) continue;

                        $matched = false;

                        // Match "Exp 10", "Experiment 10", "Expt 10", "Ex. 10"
                        if (preg_match('/\b(?:exp|experiment|ex|expt)\.?\s*#?\s*0*' . preg_quote($expNo, '/') . '\b/i', $t)) {
                            $matched = true;
                        }
                        // Match comma/ampersand separated lists e.g. "Exp 10, 11", "Expts 10 & 11"
                        elseif (preg_match('/\b(?:exp|experiment|ex|expt|experiments|expts)s?\.?\s*#?([0-9\s,&-]+)/i', $t, $mList)) {
                            $nums = preg_split('/[\s,&-]+/', $mList[1]);
                            if (in_array($expNo, array_map('trim', $nums))) {
                                $matched = true;
                            }
                        }
                        // Match title substring (at least 6 chars to avoid trivial matches)
                        elseif (!empty($expTitle) && strlen($expTitle) >= 6) {
                            $tLower = strtolower($t);
                            if (str_contains($tLower, $expTitle) || (strlen($tLower) >= 6 && str_contains($expTitle, $tLower))) {
                                $matched = true;
                            }
                        }

                        if ($matched) {
                            $matchedLogDate = $l->date;
                            break; // Most recent date because classLogs is ordered desc
                        }
                    }
                }

                if ($matchedLogDate) {
                    // Update conducted_date to the log date if different
                    if ($exp->conducted_date !== $matchedLogDate) {
                        DB::table('practical_experiments')->where('id', $exp->id)->update([
                            'conducted_date' => $matchedLogDate,
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    // 2. Check if student marks with score > 0 exist for this experiment
                    $expMarks = $marksWithScores->where('practical_experiment_id', $exp->id);
                    if ($expMarks->isNotEmpty()) {
                        $firstDateMark = $expMarks->first(fn($m) => !empty($m->evaluation_date));
                        $evalDate = $firstDateMark ? $firstDateMark->evaluation_date : ($exp->conducted_date ?: date('Y-m-d'));
                        if ($exp->conducted_date !== $evalDate) {
                            DB::table('practical_experiments')->where('id', $exp->id)->update([
                                'conducted_date' => $evalDate,
                                'updated_at' => now(),
                            ]);
                        }
                    } else {
                        // 3. Neither class log nor positive marks exist -> MUST be unconducted (null)
                        if ($exp->conducted_date !== null) {
                            DB::table('practical_experiments')->where('id', $exp->id)->update([
                                'conducted_date' => null,
                                'updated_at' => now(),
                            ]);
                        }
                        // Clean up any orphaned placeholder rows with no actual score > 0
                        DB::table('practical_experiment_marks')
                            ->where('practical_experiment_id', $exp->id)
                            ->where('total_mark', '<=', 0)
                            ->where('rough_record', '<=', 0)
                            ->where('fair_record', '<=', 0)
                            ->where('prerequisites', '<=', 0)
                            ->where('work_done', '<=', 0)
                            ->where('result', '<=', 0)
                            ->delete();
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::warning("syncPracticalExperimentsWithLogs error: " . $e->getMessage());
        }
    }

    /**
     * Check if attendance has already been recorded for a session/slot.
     */
    public function checkSessionAttendance(Request $request)
    {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubjectId = $request->input('batch_subject_id');
        $date = $request->input('date');
        $periodsParam = $request->input('periods');
        $subBatch = $request->input('sub_batch', 'Whole');

        if (!$batchSubjectId || !$date) {
            return response()->json(['status' => 'ERROR', 'message' => 'Missing required parameters.'], 400);
        }

        $periods = [];
        if (is_array($periodsParam)) {
            $periods = array_map('intval', $periodsParam);
        } elseif (!empty($periodsParam)) {
            $periods = array_map('intval', explode(',', $periodsParam));
        }

        $query = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->where('date', $date)
            ->where('sub_batch', $subBatch);

        if (!empty($periods)) {
            $query->whereIn('period', $periods);
        }

        $existingLogs = $query->orderBy('id', 'asc')->get();

        if ($existingLogs->isEmpty()) {
            return response()->json([
                'status' => 'SUCCESS',
                'exists' => false,
                'has_existing' => false,
                'present_students' => [],
                'absent_students' => [],
                'message' => 'No prior attendance recorded for this session.'
            ]);
        }

        $attLog = $existingLogs->first(function ($l) {
            $p = json_decode($l->present_students ?? '[]', true);
            $a = json_decode($l->absent_students ?? '[]', true);
            return (!empty($p) || !empty($a));
        }) ?: $existingLogs->first();

        $presentStudents = json_decode($attLog->present_students ?? '[]', true) ?: [];
        $absentStudents = json_decode($attLog->absent_students ?? '[]', true) ?: [];
        $topics = $existingLogs->pluck('topics_covered')->filter()->unique()->values()->all();

        return response()->json([
            'status' => 'SUCCESS',
            'exists' => true,
            'has_existing' => true,
            'present_students' => $presentStudents,
            'absent_students' => $absentStudents,
            'existing_topics' => $topics,
            'entries_count' => $existingLogs->count(),
            'message' => 'Attendance for this timetable session is already recorded.'
        ]);
    }

    /**
     * Get consolidated semester attendance for a tutor's classroom.
     * Computes subject-wise and overall attendance with eligibility status.
     */
    public function getConsolidatedTutorAttendance(Request $request)
    {
        $staffMobile = Session::get('userId');
        $role = Session::get('userRole');

        if (!$staffMobile || !in_array($role, ['Tutor', 'HOD', 'Lecturer', 'Demonstrator', 'Workshop Superintendent', 'Principal', 'Admin', 'Super_Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $staff = \App\Models\StaffProfile::where('mobile_no', $staffMobile)
            ->orWhere('email', $staffMobile)
            ->orWhere('id', $staffMobile)
            ->first();
        if ($staff && $staff->mobile_no) {
            $staffMobile = $staff->mobile_no;
        }

        $cleanMobile = preg_replace('/[^0-9]/', '', $staffMobile);

        $classes1 = DB::table('class_management')->where(function($q) use ($staffMobile, $cleanMobile) {
            $q->where('tutor_mobile_no', $staffMobile)->orWhere('mentor_mobile_no', $staffMobile);
            if ($cleanMobile) {
                $q->orWhere('tutor_mobile_no', $cleanMobile)->orWhere('mentor_mobile_no', $cleanMobile);
            }
        })->get();

        $classes2 = DB::table('r26_class_management')->where(function($q) use ($staffMobile, $cleanMobile) {
            $q->where('tutor_mobile_no', $staffMobile)->orWhere('mentor_mobile_no', $staffMobile);
            if ($cleanMobile) {
                $q->orWhere('tutor_mobile_no', $cleanMobile)->orWhere('mentor_mobile_no', $cleanMobile);
            }
        })->get();

        $allClasses = $classes1->concat($classes2);

        $requestedClassId = $request->input('classroom_id') ?? $request->input('classroom');
        $classroom = null;
        if ($requestedClassId) {
            $classroom = DB::table('class_management')->where('classroom_id', $requestedClassId)->first();
            if (!$classroom) {
                $classroom = DB::table('r26_class_management')->where('classroom_id', $requestedClassId)->first();
            }
        }
        if (!$classroom) {
            $classroom = $allClasses->first();
        }

        if (!$classroom) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'No classroom assigned as advisor/tutor/mentor to your profile.'
            ]);
        }

        $classroomId = $classroom->classroom_id;

        $subjectsQuery = BatchSubject::where('classroom_id', $classroomId);
        if (!empty($classroom->current_semester)) {
            $subjectsQuery->where('semester', (int)$classroom->current_semester);
        }
        $subjects = $subjectsQuery->orderBy('subject_code', 'asc')
            ->get(['id', 'subject_code', 'subject_name', 'subject_type']);

        $students = Student::getClassroomStudentsQuery($classroomId)
            ->orderByRaw('CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END')
            ->orderBy('roll_no', 'asc')
            ->orderBy('name', 'asc')
            ->get(['reg_no', 'name', 'roll_no', 'sbte_reg_no', 'phone', 'guardian_mobile']);

        $subjectCodes = $subjects->pluck('subject_code')->filter()->unique();
        $subjectIds   = $subjects->pluck('id');

        $studentAttQuery = DB::table('student_attendance')
            ->whereIn('subject_code', $subjectCodes)
            ->get();
        $studentAttGrouped = $studentAttQuery->groupBy('reg_no');

        $classLogs = DB::table('class_logs_attendance')
            ->whereIn('batch_subject_id', $subjectIds)
            ->get();
        $classLogsBySubject = $classLogs->groupBy('batch_subject_id');

        $reportRows     = [];
        $totalEligible  = 0;
        $totalCondonation = 0;
        $totalDetained  = 0;
        $aggregateSum   = 0;

        foreach ($students as $stud) {
            $regNo = $stud->reg_no;
            $stRecords = $studentAttGrouped->get($regNo, collect());

            $subjectBreakdown = [];
            $totalConductedAll = 0;
            $totalAttendedAll  = 0;

            foreach ($subjects as $subj) {
                $sCode = $subj->subject_code;
                $sLogs = $classLogsBySubject->get($subj->id, collect());
                $stSubjAtt = $stRecords->where('subject_code', $sCode);

                $conducted = $sLogs->count();
                if ($conducted == 0) {
                    $conducted = $stSubjAtt->count();
                }

                $attended = 0;
                if ($sLogs->isNotEmpty()) {
                    foreach ($sLogs as $log) {
                        $pArr = json_decode($log->present_students ?? '[]', true) ?: [];
                        if (in_array($regNo, $pArr)) {
                            $attended++;
                        }
                    }
                } else {
                    $attended = $stSubjAtt->whereIn('status', ['Present', 'Late'])->count();
                }

                $pct = $conducted > 0 ? round(($attended / $conducted) * 100, 1) : 100.0;

                $isPractical = stripos($subj->subject_type ?? '', 'pract') !== false || stripos($subj->subject_type ?? '', 'lab') !== false;
                $isSeminar   = stripos($subj->subject_type ?? '', 'seminar') !== false;
                $isProject   = stripos($subj->subject_type ?? '', 'project') !== false;

                $ciaAttMark = 0.0;
                if ($isPractical || $isProject) {
                    if ($pct >= 90) $ciaAttMark = 15.0;
                    elseif ($pct >= 80) $ciaAttMark = 12.0;
                    elseif ($pct >= 75) $ciaAttMark = 9.0;
                    elseif ($pct >= 70) $ciaAttMark = 6.0;
                    elseif ($pct >= 65) $ciaAttMark = 3.0;
                } elseif ($isSeminar) {
                    if ($pct >= 90) $ciaAttMark = 7.5;
                    elseif ($pct >= 80) $ciaAttMark = 6.0;
                    elseif ($pct >= 75) $ciaAttMark = 4.5;
                    elseif ($pct >= 70) $ciaAttMark = 3.0;
                    elseif ($pct >= 65) $ciaAttMark = 1.5;
                } else {
                    if ($pct >= 90) $ciaAttMark = 10.0;
                    elseif ($pct >= 80) $ciaAttMark = 8.0;
                    elseif ($pct >= 75) $ciaAttMark = 6.0;
                    elseif ($pct >= 70) $ciaAttMark = 4.0;
                    elseif ($pct >= 65) $ciaAttMark = 2.0;
                }

                $subjectBreakdown[$subj->id] = [
                    'subject_id'          => $subj->id,
                    'subject_code'        => $sCode,
                    'subject_name'        => $subj->subject_name,
                    'conducted'           => $conducted,
                    'attended'            => $attended,
                    'percentage'          => $pct,
                    'cia_attendance_mark' => $ciaAttMark
                ];

                $totalConductedAll += $conducted;
                $totalAttendedAll  += $attended;
            }

            $overallPct = $totalConductedAll > 0 ? round(($totalAttendedAll / $totalConductedAll) * 100, 1) : 100.0;

            if ($overallPct >= 75.0) {
                $status = 'Eligible';
                $statusBadge = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                $totalEligible++;
            } elseif ($overallPct >= 65.0) {
                $status = 'Condonation';
                $statusBadge = 'bg-amber-500/20 text-amber-400 border border-amber-500/30';
                $totalCondonation++;
            } else {
                $status = 'Detained';
                $statusBadge = 'bg-rose-500/20 text-rose-400 border border-rose-500/30';
                $totalDetained++;
            }

            $aggregateSum += $overallPct;

            $reportRows[] = [
                'roll_no'          => $stud->roll_no,
                'reg_no'           => $regNo,
                'sbte_reg_no'      => $stud->sbte_reg_no ?: $regNo,
                'name'             => $stud->name,
                'phone'            => $stud->guardian_mobile ?: $stud->phone,
                'total_conducted'  => $totalConductedAll,
                'total_attended'   => $totalAttendedAll,
                'overall_percentage' => $overallPct,
                'status'           => $status,
                'status_badge'     => $statusBadge,
                'subjects'         => $subjectBreakdown
            ];
        }

        $totalStudents = count($students);
        $avgAttendance = $totalStudents > 0 ? round($aggregateSum / $totalStudents, 1) : 0.0;

        return response()->json([
            'status' => 'SUCCESS',
            'classroom' => [
                'id'         => $classroom->classroom_id,
                'name'       => $classroom->classroom_id,
                'department' => $classroom->department ?? $classroom->branch ?? '',
                'semester'   => $classroom->current_semester ?? '',
            ],
            'summary' => [
                'total_students'    => $totalStudents,
                'eligible_count'    => $totalEligible,
                'condonation_count' => $totalCondonation,
                'detained_count'    => $totalDetained,
                'average_attendance' => $avgAttendance
            ],
            'subjects' => $subjects,
            'students' => $reportRows
        ]);
    }

    /**
     * Printable Consolidated Semester Attendance Register for a Tutor's classroom.
     */
    public function printTutorAttendanceReport(Request $request)
    {
        $res  = $this->getConsolidatedTutorAttendance($request);
        $data = $res->getData(true);

        if (($data['status'] ?? '') !== 'SUCCESS') {
            abort(404, $data['message'] ?? 'Failed to load report.');
        }

        $classroomId = $data['classroom']['id'];
        $classroom   = DB::table('class_management')->where('classroom_id', $classroomId)->first();
        if (!$classroom) {
            $classroom = DB::table('r26_class_management')->where('classroom_id', $classroomId)->first();
        }

        return view('tutor.attendance_consolidated_print', [
            'classroom' => $classroom,
            'summary'   => $data['summary'],
            'subjects'  => collect($data['subjects'])->map(fn($s) => (object)$s),
            'students'  => $data['students']
        ]);
    }

    /**
     * Delete an accidental or duplicate class log entry and clean up connected records safely.
     */
    public function deleteAttendanceLog(Request $request)
    {
        $role       = Session::get('userRole');
        $recordedBy = Session::get('userId');
        if (!$role || $role === 'Student' || !$recordedBy) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'batch_subject_id' => 'required|exists:batch_subjects,id',
            'log_ids'          => 'nullable',
            'log_id'           => 'nullable|integer',
        ]);

        $batchSubjectId = $request->batch_subject_id;
        $explicitLogIds = $request->input('log_ids');
        if (!empty($explicitLogIds) && !is_array($explicitLogIds)) {
            $explicitLogIds = array_map('intval', explode(',', (string)$explicitLogIds));
        } elseif (!empty($explicitLogIds) && is_array($explicitLogIds)) {
            $explicitLogIds = array_map('intval', $explicitLogIds);
        } else {
            $explicitLogIds = [];
        }
        if ($request->log_id && empty($explicitLogIds)) {
            $explicitLogIds = [(int)$request->log_id];
        }

        if (empty($explicitLogIds)) {
            return response()->json(['status' => 'ERROR', 'message' => 'No log ID specified for deletion.'], 422);
        }

        $batchSubject = \App\Models\BatchSubject::find($batchSubjectId);
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Batch subject not found.'], 404);
        }

        $logsToDelete = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $batchSubjectId)
            ->whereIn('id', $explicitLogIds)
            ->get();

        if ($logsToDelete->isEmpty()) {
            return response()->json(['status' => 'ERROR', 'message' => 'Log entry not found or already deleted.'], 404);
        }

        $firstLog      = $logsToDelete->first();
        $date          = $firstLog->date;
        $subBatch      = $firstLog->sub_batch ?? 'Whole';
        $lessonPlanId  = $firstLog->lesson_plan_id;

        DB::transaction(function () use ($logsToDelete, $batchSubjectId, $batchSubject, $date, $subBatch, $lessonPlanId) {
            $deleteIds = $logsToDelete->pluck('id')->toArray();

            DB::table('class_logs_attendance')->whereIn('id', $deleteIds)->delete();

            $remainingLogs = DB::table('class_logs_attendance')
                ->where('batch_subject_id', $batchSubjectId)
                ->where('date', $date)
                ->where('sub_batch', $subBatch)
                ->get();

            if ($remainingLogs->isEmpty()) {
                if (\Schema::hasTable('student_attendance')) {
                    DB::table('student_attendance')
                        ->where('subject_code', $batchSubject->subject_code)
                        ->where('date', $date)
                        ->where(function ($q) use ($subBatch) {
                            if ($subBatch && $subBatch !== 'Whole') {
                                $q->where('sub_batch', $subBatch);
                            }
                        })
                        ->delete();
                }
            }

            if ($lessonPlanId) {
                $otherLpLogs = DB::table('class_logs_attendance')
                    ->where('batch_subject_id', $batchSubjectId)
                    ->where('lesson_plan_id', $lessonPlanId)
                    ->exists();

                if (!$otherLpLogs) {
                    $lp = \App\Models\LessonPlan::find($lessonPlanId);
                    if ($lp && $lp->actual_date === $date) {
                        $lp->actual_date = null;
                        $lp->status      = 'Pending';
                        $lp->save();
                    }
                }
            }

            try {
                $practicalExps = \App\Models\PracticalExperiment::where('batch_subject_id', $batchSubjectId)
                    ->where('conducted_date', $date)
                    ->get();

                foreach ($practicalExps as $pExp) {
                    $anyRemainingExpLog = DB::table('class_logs_attendance')
                        ->where('batch_subject_id', $batchSubjectId)
                        ->get()
                        ->filter(function ($l) use ($pExp) {
                            $t = strtolower($l->topics_covered ?? '');
                            return (stripos($t, strtolower($pExp->title ?? '')) !== false)
                                || preg_match('/\b(?:exp|experiment|ex)\.?\s*#?\s*0*' . preg_quote($pExp->experiment_no, '/') . '\b/i', $t);
                        });

                    if ($anyRemainingExpLog->isEmpty()) {
                        $pExp->conducted_date = null;
                        $pExp->save();
                    }
                }
            } catch (\Exception $e) {
                \Log::warning("Revert practical experiment notice on log delete: " . $e->getMessage());
            }
        });

        return response()->json([
            'status'  => 'SUCCESS',
            'message' => 'Log entry and connected records removed successfully.'
        ]);
    }
}
