<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\MentoringController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth Gates
Route::get('/', function () {
    if (Session::has('userId')) {
        $role = Session::get('userRole');
        if ($role === 'Student') return redirect('/dashboard/student');
        if ($role === 'Super_Admin') return redirect('/dashboard/superadmin');
        if ($role === 'Admin') return redirect('/dashboard/admin');
        if ($role === 'Principal') return redirect('/dashboard/principal');
        if ($role === 'HOD') return redirect('/dashboard/hod');
        if ($role === 'Gen_Dept_Coordinator_Aided') return redirect('/dashboard/general-coordinator-aided');
        if ($role === 'Gen_Dept_Coordinator_Self_Finance') return redirect('/dashboard/general-coordinator-sf');
        if ($role === 'Lecturer') return redirect('/dashboard/lecturer');
        if ($role === 'Demonstrator') return redirect('/dashboard/demonstrator');
        if ($role === 'Trade_Instructor') return redirect('/dashboard/tradeinstructor');
        if ($role === 'Workshop_Superintendent') return redirect('/dashboard/workshop');
        return redirect('/dashboard/lecturer');
    }
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register/student', [AuthController::class, 'registerStudent']);
Route::post('/register/staff', [AuthController::class, 'registerStaff']);
Route::get('/logout', [AuthController::class, 'logout']);

// Protected Dashboard Renders
Route::middleware(['web'])->group(function () {
    
    Route::get('/dashboard/student', function () {
        if (Session::get('userRole') !== 'Student') return redirect('/');
        return view('student_dashboard');
    });

    Route::get('/student/mentoring-diary', function () {
        if (Session::get('userRole') !== 'Student') return redirect('/');
        return view('student_mentoring_diary_full');
    });

    Route::get('/tutor/mentoring-diary/{regNo}', [\App\Http\Controllers\MentoringController::class, 'tutorViewFullDiary']);

    Route::get('/dashboard/superadmin', function () {
        $role = Session::get('userRole');
        if ($role !== 'Super_Admin' && $role !== 'Principal') return redirect('/');
        return view('admin_control_desk');
    });

    Route::get('/dashboard/admin', function () {
        if (Session::get('userRole') !== 'Admin') return redirect('/');
        return view('admin_dashboard');
    });

    Route::get('/dashboard/principal', function () {
        $role = Session::get('userRole');
        if ($role !== 'Super_Admin' && $role !== 'Principal') return redirect('/');
        return view('admin_control_desk');
    });

    Route::get('/dashboard/hod', function () {
        if (Session::get('userRole') !== 'HOD') return redirect('/');
        return view('hod_dashboard');
    });

    Route::get('/dashboard/general-coordinator-aided', function () {
        if (Session::get('userRole') !== 'Gen_Dept_Coordinator_Aided') return redirect('/');
        return view('general_coordinator_aided_dashboard');
    });

    Route::get('/dashboard/general-coordinator-sf', function () {
        if (Session::get('userRole') !== 'Gen_Dept_Coordinator_Self_Finance') return redirect('/');
        return view('general_coordinator_sf_dashboard');
    });

    Route::get('/dashboard/lecturer', function () {
        if (Session::get('userRole') !== 'Lecturer') return redirect('/');
        return view('lecturer_dashboard');
    });

    Route::get('/dashboard/demonstrator', function () {
        if (Session::get('userRole') !== 'Demonstrator') return redirect('/');
        return view('demonstrator_dashboard');
    });

    Route::get('/dashboard/tradeinstructor', function () {
        if (Session::get('userRole') !== 'Trade_Instructor') return redirect('/');
        return view('trade_instructor_dashboard');
    });

    Route::get('/dashboard/tutor', function () {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') return redirect('/');
        return view('tutor_dashboard');
    });

    Route::get('/dashboard/workshop', function () {
        if (Session::get('userRole') !== 'Workshop_Superintendent') return redirect('/');
        return view('workshop_superintendent_dashboard');
    });

    // Core Data Actions
    Route::post('/api/approve-account', [DataController::class, 'approveAccount']);
    Route::post('/api/student/update-sbte-reg', [DataController::class, 'updateSbteRegNo']);
    Route::post('/api/student/update/{regNo}', [DataController::class, 'updateStudentProfile']);
    Route::delete('/api/student/delete/{regNo}', [DataController::class, 'deleteStudentProfile']);
    Route::get('/api/tutor/classroom/{tutorMobile}', [DataController::class, 'getTutorClassroomRoster']);
    Route::post('/api/system/backup', [BackupController::class, 'backupDatabaseToDrive']);

    // Admin/Super Admin Endpoints
    Route::get('/api/admin/stats', [DataController::class, 'getAdminStats']);
    Route::get('/api/admin/users', [DataController::class, 'getUsersList']);
    Route::post('/api/admin/user/toggle-status', [DataController::class, 'toggleUserStatus']);
    Route::post('/api/admin/user/reset-password', [DataController::class, 'resetUserPassword']);
    Route::post('/api/admin/user/change-role', [DataController::class, 'changeUserRole']);
    Route::post('/api/admin/user/delete', [DataController::class, 'deleteUser']);
    Route::get('/api/audit-logs', [DataController::class, 'getAuditLogs']);

    // HOD Batch Management
    Route::get('/api/hod/batches', [DataController::class, 'getHodBatches']);
    Route::post('/api/hod/batches', [DataController::class, 'createHodBatch']);
    Route::post('/api/hod/batches/assign-tutor', [DataController::class, 'assignBatchTutor']);
    Route::post('/api/hod/batches/assign-mentor', [DataController::class, 'assignBatchMentor']);
    Route::get('/api/hod/batches/{classroomId}/students', [DataController::class, 'getBatchStudents']);
    Route::get('/api/hod/dept-staff', [DataController::class, 'getDeptStaff']);

    // HOD Subject Allocation
    Route::get('/api/hod/batches/{classroomId}/subjects', [DataController::class, 'getBatchSubjects']);
    Route::post('/api/hod/batches/subjects/create', [DataController::class, 'createBatchSubject']);
    Route::post('/api/hod/batches/subjects/{subjectId}/assign-staff', [DataController::class, 'assignSubjectStaff']);
    Route::delete('/api/hod/batches/subjects/{subjectId}', [DataController::class, 'deleteBatchSubject']);

    // Lecturer Endpoints
    Route::get('/api/lecturer/my-batches', [DataController::class, 'getLecturerBatches']);
    Route::get('/course-files', function () {
        $role = Session::get('userRole');
        if (!$role || $role === 'Student') return redirect('/');
        return view('course_files_dashboard');
    });

    // Course File API Routes
    Route::get('/api/course-files/subjects', [App\Http\Controllers\CourseFileController::class, 'getStaffSubjects']);
    Route::get('/api/course-files/{id}', [App\Http\Controllers\CourseFileController::class, 'getCourseFile']);
    Route::post('/api/course-files/{id}', [App\Http\Controllers\CourseFileController::class, 'saveCourseFile']);
    Route::get('/api/course-files/{id}/preview/{docNo}', [App\Http\Controllers\CourseFileController::class, 'previewDocument']);
    Route::post('/api/course-files/{id}/document/{docNo}/save', [App\Http\Controllers\CourseFileController::class, 'saveDocumentPayload']);
    Route::post('/api/course-files/{id}/document/5/upload-cis', [App\Http\Controllers\CourseFileController::class, 'uploadCisPdf']);
    Route::post('/api/course-files/{id}/document/6/save-copo', [App\Http\Controllers\CourseFileController::class, 'saveCoPoMapping']);
    Route::get('/api/course-files/{id}/pdf', [App\Http\Controllers\CourseFileController::class, 'generatePdf']);

    // Academic Reports
    Route::get('/api/student/academic-report', [DataController::class, 'getAcademicReport']);

    Route::post('/api/classroom/{subjectId}/syllabus', [App\Http\Controllers\ClassroomController::class, 'uploadSyllabus']);
    Route::get('/api/classroom/{subjectId}/details', [App\Http\Controllers\ClassroomController::class, 'getCourseDetails']);
    Route::get('/api/classroom/{subjectId}/generate-questions', [App\Http\Controllers\ClassroomController::class, 'generateAssignmentQuestions']);
    Route::post('/api/classroom/{subjectId}/save-assignment-deadline', [App\Http\Controllers\ClassroomController::class, 'saveAssignmentDeadline']);
    Route::post('/api/classroom/{subjectId}/save-assignment-marks', [App\Http\Controllers\ClassroomController::class, 'saveAssignmentMarks']);
    Route::post('/api/classroom/{subjectId}/generate-summative-paper', [App\Http\Controllers\ClassroomController::class, 'generateSummativePaper']);
    Route::post('/api/classroom/{subjectId}/save-summative-config', [App\Http\Controllers\ClassroomController::class, 'saveSummativeConfig']);
    Route::post('/api/classroom/{subjectId}/save-written-test-marks', [App\Http\Controllers\ClassroomController::class, 'saveWrittenTestMarks']);
    Route::post('/api/classroom/{subjectId}/publish-online-test', [App\Http\Controllers\TestEngineController::class, 'publishOnlineTest']);
    Route::get('/api/classroom/{subjectId}/active-online-tests', [App\Http\Controllers\TestEngineController::class, 'getActiveTestsLecturer']);
    Route::get('/api/test-engine/report/{testId}', [App\Http\Controllers\TestEngineController::class, 'generateTestReport']);
    Route::get('/classroom/{subjectId}/assignment-report', [App\Http\Controllers\ClassroomController::class, 'printAssignmentReport']);
    Route::get('/classroom/{subjectId}/summative-report', [App\Http\Controllers\ClassroomController::class, 'printSummativeReport']);

    Route::get('/api/classroom/{subjectId}/question-bank', [App\Http\Controllers\ClassroomController::class, 'getQuestionBank']);
    Route::get('/api/classroom/question-bank/template', [App\Http\Controllers\ClassroomController::class, 'downloadQuestionTemplate']);
    Route::post('/api/classroom/{subjectId}/question-bank/upload', [App\Http\Controllers\ClassroomController::class, 'uploadQuestionBank']);
    Route::post('/api/classroom/{subjectId}/question-bank/seed-ai', [App\Http\Controllers\ClassroomController::class, 'seedQuestionBankWithAi']);
    Route::post('/api/classroom/{subjectId}/question-bank/upload-json', [App\Http\Controllers\ClassroomController::class, 'uploadQuestionBankJson']);

    // Mentoring Endpoints
    Route::get('/api/mentoring/my-batches', [MentoringController::class, 'getMyBatches']);
    Route::get('/api/mentoring/students/{classroomId}', [MentoringController::class, 'getClassroomStudents']);
    Route::get('/api/mentoring/classroom/{classroomId}/leaves', [MentoringController::class, 'getClassroomLeaves']);
    Route::post('/api/mentoring/assign-batch', [MentoringController::class, 'assignBatch']);
    Route::post('/api/mentoring/assign-mentor2', [MentoringController::class, 'assignMentor2']);
    Route::get('/api/mentoring/diary/{regNo}', [MentoringController::class, 'getStudentDiary']);
    Route::post('/api/mentoring/diary/add', [MentoringController::class, 'addDiaryEntry']);
    Route::post('/api/mentoring/diary/approve', [MentoringController::class, 'approveDiaryEntry']);
    Route::post('/api/mentoring/diary/delete', [MentoringController::class, 'deleteDiaryEntry']);
    Route::post('/api/mentoring/leave/save', [MentoringController::class, 'saveLeaveRecord']);
    Route::post('/api/mentoring/leave/approve', [MentoringController::class, 'approveLeaveRecord']);
    Route::post('/api/mentoring/disciplinary/save', [MentoringController::class, 'saveDisciplinary']);
    Route::post('/api/mentoring/disciplinary/delete', [MentoringController::class, 'deleteDisciplinary']);
    Route::post('/api/student/mentoring/extra-curricular/save', [MentoringController::class, 'studentSaveExtraCurricular']);
    Route::get('/api/mentoring/report/{classroomId}', [MentoringController::class, 'getMentoringReport']);
    Route::get('/api/mentoring/backlog-report/{classroomId}', [MentoringController::class, 'getBacklogReport']);
    
    Route::get('/diary/{regNo}/print', [MentoringController::class, 'printDiary']);
    Route::get('/diary/{regNo}/leave-report', [MentoringController::class, 'printLeaveReport']);
    Route::get('/classroom/{classroomId}/condonation-report', [MentoringController::class, 'printCondonationReport']);

    // Student Self-Service Mentoring
    Route::post('/api/student/mentoring/self-entry', [MentoringController::class, 'studentSelfEntry']);
    Route::post('/api/student/mentoring/extended-profile', [MentoringController::class, 'saveExtendedProfile']);
    Route::get('/api/student/mentoring/diary', [MentoringController::class, 'studentViewDiary']);
    Route::post('/api/student/mentoring/save-all', [MentoringController::class, 'saveStudentMentoringData']);

    // Activity Points Endpoints
    Route::get('/api/student/activity-points', [App\Http\Controllers\ActivityPointsController::class, 'getStudentPoints']);
    Route::post('/api/student/activity-points', [App\Http\Controllers\ActivityPointsController::class, 'submitClaim']);
    Route::get('/api/student/activity-points/summary/{regNo}', [App\Http\Controllers\ActivityPointsController::class, 'getStudentSummary']);
    Route::get('/api/tutor/activity-points', [App\Http\Controllers\ActivityPointsController::class, 'getClassroomClaims']);
    Route::post('/api/tutor/activity-points/{id}/verify', [App\Http\Controllers\ActivityPointsController::class, 'verifyClaim']);

    // Student Online Tests
    Route::get('/api/student/online-tests', [App\Http\Controllers\TestEngineController::class, 'getAvailableTests']);
    Route::post('/api/student/online-tests/{testId}/start', [App\Http\Controllers\TestEngineController::class, 'startTest']);
    Route::post('/api/student/online-tests/{testId}/submit', [App\Http\Controllers\TestEngineController::class, 'submitTest']);
    Route::delete('/api/classroom/online-tests/{testId}', [App\Http\Controllers\TestEngineController::class, 'deleteOnlineTest']);
    Route::get('/api/classroom/online-tests/{testId}/key', [App\Http\Controllers\TestEngineController::class, 'getLecturerAnswerKey']);

    // Remedial Sessions
    Route::get('/remedial-sessions', function () {
        $role = Session::get('userRole');
        if (!$role || !in_array($role, ['Lecturer', 'Tutor', 'HOD'])) return redirect('/');
        return view('remedial_dashboard');
    });
    Route::get('/remedial/rooms/{roomId}/assessments/{assessmentId}/report', [App\Http\Controllers\RemedialController::class, 'printAssessmentReport']);


    Route::prefix('api/remedial')->group(function () {
        Route::get('/assigned-subjects', [App\Http\Controllers\RemedialController::class, 'getAssignedSubjects']);
        Route::get('/student-performance', [App\Http\Controllers\RemedialController::class, 'getStudentPerformance']);
        Route::post('/rooms', [App\Http\Controllers\RemedialController::class, 'createRoom']);
        Route::get('/rooms', [App\Http\Controllers\RemedialController::class, 'getRooms']);
        Route::get('/rooms/{roomId}', [App\Http\Controllers\RemedialController::class, 'getRoomDetails']);
        Route::post('/rooms/{roomId}/students', [App\Http\Controllers\RemedialController::class, 'addStudent']);
        Route::delete('/rooms/{roomId}/students', [App\Http\Controllers\RemedialController::class, 'removeStudent']);
        Route::post('/rooms/{roomId}/logs', [App\Http\Controllers\RemedialController::class, 'saveLog']);
        Route::get('/rooms/{roomId}/assessments', [App\Http\Controllers\RemedialController::class, 'getAssessments']);
        Route::post('/rooms/{roomId}/assessments', [App\Http\Controllers\RemedialController::class, 'createAssessment']);
        Route::post('/rooms/{roomId}/assessments/{assessmentId}/scores', [App\Http\Controllers\RemedialController::class, 'saveAssessmentScores']);
        Route::post('/rooms/{roomId}/assessments/{assessmentId}/sync', [App\Http\Controllers\RemedialController::class, 'syncOnlineScores']);
    });

    // Live Class Log & Attendance System
    Route::get('/staff/attendance-log', [App\Http\Controllers\AttendanceController::class, 'viewPage']);
    Route::get('/api/staff/attendance/subjects', [App\Http\Controllers\AttendanceController::class, 'getActiveSubjects']);
    Route::get('/api/staff/attendance/subjects/{id}/details', [App\Http\Controllers\AttendanceController::class, 'getSubjectDetails']);
    Route::post('/api/staff/attendance/save', [App\Http\Controllers\AttendanceController::class, 'saveAttendance']);
    Route::get('/api/tutor/attendance/students', [App\Http\Controllers\AttendanceController::class, 'getTutorStudents']);
    Route::post('/api/tutor/attendance/roll-numbers', [App\Http\Controllers\AttendanceController::class, 'updateRollNumbers']);
    Route::get('/api/staff/attendance/subjects/{id}/reports', [App\Http\Controllers\AttendanceController::class, 'getReports']);
});
