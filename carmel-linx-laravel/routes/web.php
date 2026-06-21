<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\BackupController;
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
        if ($role === 'Faculty') return redirect('/dashboard/faculty');
        if ($role === 'Demonstrator') return redirect('/dashboard/demonstrator');
        if ($role === 'Trade_Instructor') return redirect('/dashboard/tradeinstructor');
        if ($role === 'Workshop_Superintendent') return redirect('/dashboard/workshop');
        return redirect('/dashboard/faculty');
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

    Route::get('/dashboard/faculty', function () {
        if (Session::get('userRole') !== 'Faculty') return redirect('/');
        return view('faculty_dashboard');
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
});

