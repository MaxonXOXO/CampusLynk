<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Student;
use App\Models\BatchSubject;

class ParentDashboardController extends Controller
{
    /**
     * Show Passwordless Parent Login Page.
     */
    public function showLoginPage()
    {
        return view('parent_login');
    }

    /**
     * Handle Passwordless Login verification using Student Reg No + Guardian Mobile No.
     */
    public function verifyLogin(Request $request)
    {
        $request->validate([
            'regNo' => 'required|string',
            'guardianMobile' => 'required|string',
        ]);

        $regNo = strtoupper(trim($request->input('regNo')));
        $cleanMobile = preg_replace('/[^0-9]/', '', $request->input('guardianMobile'));

        // Match student record
        $student = Student::where('reg_no', $regNo)
            ->orWhere('adm_no', $regNo)
            ->orWhere('sbte_reg_no', $regNo)
            ->first();

        if (!$student) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'No student profile found with the provided Register/Admission Number.'
            ], 404);
        }

        // Clean registered phone numbers
        $registeredGuardianMobile = preg_replace('/[^0-9]/', '', $student->guardian_mobile ?? '');
        $registeredStudentMobile = preg_replace('/[^0-9]/', '', $student->phone ?? '');

        // If no mobile is registered in DB yet, allow access for initial parent onboarding
        $hasRegisteredMobile = (!empty($registeredGuardianMobile) || !empty($registeredStudentMobile));

        $isMobileMatched = !$hasRegisteredMobile || 
                           ($cleanMobile === $registeredGuardianMobile) || 
                           ($cleanMobile === $registeredStudentMobile) ||
                           (strlen($cleanMobile) >= 10 && (($registeredGuardianMobile !== '' && str_contains($registeredGuardianMobile, $cleanMobile)) || ($registeredStudentMobile !== '' && str_contains($registeredStudentMobile, $cleanMobile))));

        if (!$isMobileMatched) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'The mobile number provided does not match college guardian records for this student. Please contact Class Tutor.'
            ], 401);
        }

        // Store Parent Session
        Session::put([
            'userRole' => 'Parent',
            'parentRegNo' => $student->reg_no,
            'studentName' => $student->name,
            'guardianMobile' => $cleanMobile
        ]);

        return response()->json([
            'status' => 'SUCCESS',
            'redirect' => '/parent/dashboard/' . $student->reg_no
        ]);
    }

    /**
     * Display the Parent Dashboard for a specific student.
     */
    public function showDashboard(Request $request, $regNo)
    {
        $regNo = strtoupper(trim($regNo));

        // Check if access is via token in query string or session
        $token = $request->query('token');
        $sessionRegNo = Session::get('parentRegNo');
        $sessionRole = Session::get('userRole');

        $student = Student::where('reg_no', $regNo)
            ->orWhere('adm_no', $regNo)
            ->firstOrFail();

        // Optional token security verification (MD5 hash of reg_no + guardian_mobile)
        $expectedToken = substr(md5($student->reg_no . ($student->guardian_mobile ?: $student->phone) . 'CarmelR26ParentSecret'), 0, 12);
        
        if ($token && $token === $expectedToken) {
            // Direct SMS link access - auto create parent session
            Session::put([
                'userRole' => 'Parent',
                'parentRegNo' => $student->reg_no,
                'studentName' => $student->name,
                'guardianMobile' => $student->guardian_mobile ?: $student->phone
            ]);
        } elseif ($sessionRole === 'Parent' && $sessionRegNo === $student->reg_no) {
            // Valid session
        } elseif (in_array($sessionRole, ['Tutor', 'HOD', 'Lecturer', 'Principal', 'Super_Admin'])) {
            // Staff previewing parent view
        } else {
            // Redirect to login if unauthorized
            return redirect('/parent')->with('error', 'Please verify student details with guardian mobile number to access Parent Portal.');
        }

        // 1. Classroom & Batch details
        $classroom = DB::table('class_management')
            ->where('classroom_id', $student->classroom_id)
            ->first();

        $tutor = null;
        if ($classroom && $classroom->tutor_mobile_no) {
            $tutor = DB::table('staff_profiles')
                ->where('mobile_no', $classroom->tutor_mobile_no)
                ->first();
        }

        // 2. Fetch all subjects for student's classroom
        $batchSubjects = BatchSubject::where('classroom_id', $student->classroom_id)
            ->orderBy('subject_code', 'asc')
            ->get();

        $subjectIds = $batchSubjects->pluck('id')->toArray();

        // 3. Today's Hour-Wise Attendance Grid
        $todayDate = now()->toDateString();
        $todayLogs = DB::table('class_logs_attendance')
            ->whereIn('batch_subject_id', $subjectIds)
            ->where('date', $todayDate)
            ->orderBy('period', 'asc')
            ->get();

        $hourlyStatus = [];
        for ($p = 1; $p <= 7; $p++) {
            $hourlyStatus[$p] = [
                'period' => $p,
                'status' => 'Not Marked',
                'subject_name' => 'Free Period',
                'subject_code' => '',
                'topic' => '',
                'badge_class' => 'bg-secondary text-light'
            ];
        }

        foreach ($todayLogs as $log) {
            $period = (int)$log->period;
            if ($period >= 1 && $period <= 7) {
                $subj = $batchSubjects->firstWhere('id', $log->batch_subject_id);
                $pList = json_decode($log->present_students ?? '[]', true) ?: [];
                $aList = json_decode($log->absent_students ?? '[]', true) ?: [];

                $statusText = 'Absent';
                $badgeClass = 'bg-danger text-white';

                if (in_array($student->reg_no, $pList)) {
                    $statusText = 'Present';
                    $badgeClass = 'bg-success text-white';
                } elseif (in_array($student->reg_no, $aList)) {
                    $statusText = 'Absent';
                    $badgeClass = 'bg-danger text-white';
                }

                $hourlyStatus[$period] = [
                    'period' => $period,
                    'status' => $statusText,
                    'subject_name' => $subj->subject_name ?? 'Class Session',
                    'subject_code' => $subj->subject_code ?? '',
                    'topic' => $log->topics_covered ?? 'Regular Session',
                    'badge_class' => $badgeClass
                ];
            }
        }

        // 4. Calculate Overall Attendance Percentage across all subjects
        $totalConductedClasses = 0;
        $totalAttendedClasses = 0;

        foreach ($subjectIds as $sId) {
            $logs = DB::table('class_logs_attendance')
                ->where('batch_subject_id', $sId)
                ->get(['present_students', 'absent_students']);

            foreach ($logs as $l) {
                $pList = json_decode($l->present_students ?? '[]', true) ?: [];
                $aList = json_decode($l->absent_students ?? '[]', true) ?: [];

                if (in_array($student->reg_no, $pList) || in_array($student->reg_no, $aList)) {
                    $totalConductedClasses++;
                    if (in_array($student->reg_no, $pList)) {
                        $totalAttendedClasses++;
                    }
                }
            }
        }

        $overallAttendancePct = $totalConductedClasses > 0 
            ? round(($totalAttendedClasses / $totalConductedClasses) * 100, 1) 
            : 100.0;

        // 5. Today's Assignments & Pending Works
        $assignments = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('assignments')) {
            $assignments = DB::table('assignments')
                ->whereIn('batch_subject_id', $subjectIds)
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get();
        }

        // 6. Scheduled Tests & Evaluations
        $practicalTests = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('r26_drawing_practical_tests')) {
            $practicalTests = DB::table('r26_drawing_practical_tests')
                ->whereIn('batch_subject_id', $subjectIds)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    $item->test_name = 'Practical Test #' . ($item->test_no ?? $item->id);
                    $item->test_date = $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') : now()->toDateString();
                    $item->max_marks = 40;
                    return $item;
                });
        } elseif (\Illuminate\Support\Facades\Schema::hasTable('practical_tests')) {
            $practicalTests = DB::table('practical_tests')
                ->whereIn('batch_subject_id', $subjectIds)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();
        }

        // 7. Tutor Mentoring Comments & Staff Remarks
        $mentoringNotes = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('student_mentoring_diaries')) {
            $mentoringNotes = DB::table('student_mentoring_diaries')
                ->where('reg_no', $student->reg_no)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        } elseif (\Illuminate\Support\Facades\Schema::hasTable('student_mentoring_profiles')) {
            $mentoringNotes = DB::table('student_mentoring_profiles')
                ->where('reg_no', $student->reg_no)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        }

        // 8. Generate shareable SMS Link Token for parent
        $shareableToken = $expectedToken;
        $smsShareUrl = url('/parent/dashboard/' . $student->reg_no . '?token=' . $shareableToken);

        return view('parent_dashboard', compact(
            'student',
            'classroom',
            'tutor',
            'hourlyStatus',
            'totalConductedClasses',
            'totalAttendedClasses',
            'overallAttendancePct',
            'assignments',
            'practicalTests',
            'mentoringNotes',
            'smsShareUrl'
        ));
    }
}
