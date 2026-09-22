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
}
