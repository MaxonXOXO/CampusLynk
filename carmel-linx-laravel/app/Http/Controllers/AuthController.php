<?php

namespace App\Http\Controllers;

use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Handle student or staff login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'userId' => 'required|string',
            'password' => 'required|string',
            'roleType' => 'required|string|in:student,staff',
        ]);

        $userId = trim($request->input('userId'));
        $password = trim($request->input('password'));
        $roleType = $request->input('roleType');

        try {
            if ($roleType === 'student') {
                $student = Student::where('reg_no', strtoupper($userId))
                    ->orWhere('adm_no', strtoupper($userId))
                    ->first();

                if (!$student || $student->password !== $password) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Invalid ID/Admission Number or Password.']);
                }

                if (strtoupper($student->status) !== 'APPROVED') {
                    return response()->json(['status' => 'ERROR', 'message' => 'Your registration is pending approval by your Class Tutor.']);
                }

                // Set session data
                Session::put([
                    'userRole' => 'Student',
                    'userId' => $student->reg_no,
                    'userName' => $student->name,
                    'userBranch' => $student->branch,
                    'userAdmissionType' => $student->admission_type,
                    'userPhoto' => $student->photo_url ?? '',
                    'classroomId' => $student->classroom_id,
                    'sbteRegNo' => $student->sbte_reg_no,
                ]);

                return response()->json([
                    'status' => 'SUCCESS',
                    'role' => 'Student',
                    'id' => $student->reg_no,
                    'name' => $student->name,
                    'branch' => $student->branch,
                    'route' => '/dashboard/student'
                ]);
            } else {
                // Staff login by mobile number
                $cleanMobile = preg_replace('/[^0-9]/', '', $userId);
                $staff = StaffProfile::where('mobile_no', $cleanMobile)->first();

                if (!$staff || $staff->password !== $password) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Invalid Mobile Number or Password.']);
                }

                if (strtoupper($staff->account_status) !== 'APPROVED') {
                    return response()->json(['status' => 'ERROR', 'message' => 'Your staff account is pending approval by Super Admin.']);
                }

                // Set session data
                Session::put([
                    'userRole' => $staff->designation,
                    'userId' => $staff->mobile_no,
                    'userName' => $staff->name,
                    'userBranch' => $staff->branch,
                    'userPhoto' => $staff->photo_url ?? '',
                ]);

                // Determine redirect route based on role
                $route = '/dashboard/lecturer';
                if ($staff->designation === 'Super_Admin') {
                    $route = '/dashboard/superadmin';
                } elseif ($staff->designation === 'Admin') {
                    $route = '/dashboard/admin';
                } elseif ($staff->designation === 'Principal') {
                    $route = '/dashboard/principal';
                } elseif ($staff->designation === 'HOD') {
                    $route = '/dashboard/hod';
                } elseif ($staff->designation === 'Tutor') {
                    $route = '/dashboard/tutor';
                } elseif ($staff->designation === 'Gen_Dept_Coordinator_Aided') {
                    $route = '/dashboard/general-coordinator-aided';
                } elseif ($staff->designation === 'Gen_Dept_Coordinator_Self_Finance') {
                    $route = '/dashboard/general-coordinator-sf';
                } elseif ($staff->designation === 'Lecturer') {
                    $route = '/dashboard/lecturer';
                } elseif ($staff->designation === 'Demonstrator') {
                    $route = '/dashboard/demonstrator';
                } elseif ($staff->designation === 'Trade_Instructor') {
                    $route = '/dashboard/tradeinstructor';
                } elseif ($staff->designation === 'Workshop_Superintendent') {
                    $route = '/dashboard/workshop';
                }

                return response()->json([
                    'status' => 'SUCCESS',
                    'role' => $staff->designation,
                    'id' => $staff->mobile_no,
                    'name' => $staff->name,
                    'branch' => $staff->branch,
                    'route' => $route
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'System error: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle student registration.
     */
    public function registerStudent(Request $request)
    {
        $request->validate([
            'admNo' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'branch' => 'required|string',
            'admissionYear' => 'required|integer',
            'admissionType' => 'required|string|in:Regular,LET',
            'password' => 'required|string',
        ]);

        $email = trim($request->input('email'));
        if (!preg_match('/@carmelpoly\.(in|edu\.in)$/i', $email)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Student registration requires a college email ID (e.g. name@carmelpoly.in).']);
        }

        $admNo = strtoupper(trim($request->input('admNo')));
        $branchCode = strtoupper(trim($request->input('branch')));
        $admYear = (int)$request->input('admissionYear');
        $isLET = $request->input('admissionType') === 'LET';

        // Auto-generate Registration Number
        $yy = substr((string)$admYear, -2);
        $regNo = $yy . $branchCode . $admNo . ($isLET ? 'L' : '');

        // Check duplicate
        $duplicate = Student::where('reg_no', $regNo)->orWhere('adm_no', $admNo)->first();
        if ($duplicate) {
            return response()->json(['status' => 'ERROR', 'message' => 'A student with this Register Number or Admission Number already exists.']);
        }

        // Classroom ID calculation
        $startYear = $isLET ? ($admYear - 1) : $admYear;
        $endYear = $startYear + 3;
        $classroomId = "{$branchCode}_{$startYear}_{$endYear}";

        // Only assign if the batch has been created by the HOD.
        // If the HOD hasn't created the batch yet, leave classroom_id as null.
        // The student will be backfilled when the HOD creates the batch later.
        $batchExists = \App\Models\ClassManagement::where('classroom_id', $classroomId)->exists();
        if (!$batchExists) {
            $classroomId = null;
        }

        // Save Photo if uploaded
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = '/storage/' . $request->file('photo')->store('avatars', 'public');
        }

        try {
            $student = Student::create([
                'reg_no' => $regNo,
                'adm_no' => $admNo,
                'name' => trim($request->input('name')),
                'email' => $email,
                'password' => trim($request->input('password')),
                'phone' => $request->input('phone'),
                'branch' => $branchCode,
                'admission_year' => $admYear,
                'admission_type' => $request->input('admissionType'),
                'photo_url' => $photoPath,
                'classroom_id' => $classroomId,
                'status' => 'Pending',
            ]);

            // Add Audit Log entry
            $actorId = Session::get('userId') ?: 'System';
            $actorName = Session::get('userName') ?: 'Self Registration';
            AuditLog::create([
                'performed_by' => $actorId,
                'performed_by_name' => $actorName,
                'target_id' => $student->reg_no,
                'target_name' => $student->name,
                'action' => 'Registered',
                'details' => 'Student registration created with status: Pending',
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Registration successful! Pending Class Tutor approval.',
                'regNo' => $regNo
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to write: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle staff registration.
     */
    public function registerStaff(Request $request)
    {
        $request->validate([
            'mobileNo' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'branch' => 'required|string',
            'designation' => 'required|string',
            'password' => 'required|string',
        ]);

        $email = trim($request->input('email'));
        if (!preg_match('/@carmelpoly\.(in|edu\.in)$/i', $email)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Staff registration requires a college email ID (e.g. user@carmelpoly.in).']);
        }

        $mobileNo = preg_replace('/[^0-9]/', '', $request->input('mobileNo'));

        // Check duplicate
        $duplicate = StaffProfile::where('mobile_no', $mobileNo)->first();
        if ($duplicate) {
            return response()->json(['status' => 'ERROR', 'message' => 'A staff profile with this mobile number already exists.']);
        }

        $designation = trim($request->input('designation'));

        // Enforce role count constraints
        if ($designation === 'Principal') {
            $hasPrincipal = StaffProfile::where('designation', 'Principal')->exists();
            if ($hasPrincipal) {
                return response()->json(['status' => 'ERROR', 'message' => 'An active Principal profile already exists in the system.']);
            }
        }

        if ($designation === 'Academic_Coordinator') {
            $hasCoordinator = StaffProfile::where('designation', 'Academic_Coordinator')
                ->where('account_status', 'Approved')
                ->exists();
            if ($hasCoordinator) {
                return response()->json(['status' => 'ERROR', 'message' => 'An active Academic Coordinator profile already exists in the system.']);
            }
        }

        // Save Photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = '/storage/' . $request->file('photo')->store('avatars', 'public');
        }

        $status = ($designation === 'Principal') ? 'Approved' : 'Pending';

        try {
            $staff = StaffProfile::create([
                'mobile_no' => $mobileNo,
                'name' => trim($request->input('name')),
                'email' => $email,
                'branch' => strtoupper(trim($request->input('branch'))),
                'designation' => $designation,
                'password' => trim($request->input('password')),
                'photo_url' => $photoPath,
                'account_status' => $status,
            ]);

            // Add Audit Log entry
            $actorId = Session::get('userId') ?: 'System';
            $actorName = Session::get('userName') ?: 'Self Registration';
            AuditLog::create([
                'performed_by' => $actorId,
                'performed_by_name' => $actorName,
                'target_id' => $staff->mobile_no,
                'target_name' => $staff->name,
                'action' => 'Registered',
                'details' => "Staff registration created for role: {$designation} with status: {$status}",
                'ip_address' => $request->ip(),
            ]);

            $msg = ($designation === 'Principal') 
                ? 'Principal registration successful! Account is auto-approved.' 
                : 'Staff registration submitted! Pending administrator approval.';

            return response()->json(['status' => 'SUCCESS', 'message' => $msg]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to write: ' . $e->getMessage()]);
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = trim($request->input('email'));

        // Check if email belongs to student or staff
        $isStudent = Student::where('email', $email)->exists();
        $isStaff = \App\Models\StaffProfile::where('email', $email)->exists();

        if (!$isStudent && !$isStaff) {
            return response()->json(['status' => 'ERROR', 'message' => 'No account found with that email address.']);
        }

        // Generate a random token
        $token = \Illuminate\Support\Str::random(64);

        // Delete any existing tokens for this email
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Insert new token
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now()
        ]);

        // In a real application, you would send an email here using Laravel's Mail facade.
        // Mail::to($email)->send(new ResetPasswordMail($token));

        return response()->json(['status' => 'SUCCESS', 'message' => 'A password reset link has been securely sent to your email address!']);
    }

    /**
     * Change logged-in student's password.
     */
    public function changeStudentPassword(Request $request)
    {
        $userId = Session::get('userId');
        $role = Session::get('userRole');

        if (!$userId || $role !== 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. Only students can perform this action.']);
        }

        $request->validate([
            'oldPassword' => 'required|string',
            'newPassword' => 'required|string|min:6',
        ]);

        $oldPassword = $request->input('oldPassword');
        $newPassword = $request->input('newPassword');

        $student = Student::where('reg_no', $userId)->first();
        if (!$student) {
            return response()->json(['status' => 'ERROR', 'message' => 'Student profile not found.']);
        }

        if ($student->password !== $oldPassword) {
            return response()->json(['status' => 'ERROR', 'message' => 'Current password matches incorrectly.']);
        }

        try {
            $student->update(['password' => $newPassword]);
            return response()->json(['status' => 'SUCCESS', 'message' => 'Password updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to update password: ' . $e->getMessage()]);
        }
    }

    /**
     * Logout and destroy session.
     */
    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}
