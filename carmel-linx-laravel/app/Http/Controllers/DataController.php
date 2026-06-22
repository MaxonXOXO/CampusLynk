<?php

namespace App\Http\Controllers;

use App\Models\ClassManagement;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\StudentResponse;
use App\Models\AcademicMark;
use App\Models\AuditLog;
use App\Models\BatchSubject;
use App\Models\SubjectStaffAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DataController extends Controller
{
    /**
     * Approve a pending student or staff member.
     */
    public function approveAccount(Request $request)
    {
        $request->validate([
            'targetId' => 'required|string',
            'userType' => 'required|string|in:student,staff',
        ]);

        $targetId = $request->input('targetId');
        $userType = $request->input('userType');

        try {
            if ($userType === 'student') {
                $student = Student::where('reg_no', strtoupper($targetId))->first();
                if (!$student) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Student registration lookup not found.']);
                }

                $student->update(['status' => 'Approved']);
                return response()->json(['status' => 'SUCCESS', 'message' => 'Student approved successfully.']);
            } else {
                $staff = StaffProfile::where('mobile_no', $targetId)->first();
                if (!$staff) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Staff profile lookup not found.']);
                }

                if ($staff->designation === 'Principal') {
                    $hasPrincipal = StaffProfile::where('designation', 'Principal')
                        ->where('account_status', 'Approved')
                        ->exists();
                    if ($hasPrincipal) {
                        return response()->json(['status' => 'ERROR', 'message' => 'Another Principal is already approved. Cannot approve multiple active Principals.']);
                    }
                }

                $staff->update(['account_status' => 'Approved']);
                return response()->json(['status' => 'SUCCESS', 'message' => 'Staff member approved successfully.']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Operation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/HOD: update a student profile.
     */
    public function updateStudentProfile(Request $request, $regNo)
    {
        $student = Student::where('reg_no', strtoupper($regNo))->first();
        if (!$student) {
            return response()->json(['status' => 'ERROR', 'message' => 'Student profile not found.']);
        }

        $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'password' => 'nullable|string',
            'phone' => 'nullable|string',
            'sbte_reg_no' => 'nullable|string',
        ]);

        try {
            $fields = array_filter($request->only(['name', 'email', 'password', 'phone', 'sbte_reg_no']));
            if ($request->hasFile('photo')) {
                $fields['photo_url'] = '/storage/' . $request->file('photo')->store('avatars', 'public');
            }

            $student->update($fields);
            return response()->json(['status' => 'SUCCESS', 'message' => 'Student profile updated.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/HOD: delete a student.
     */
    public function deleteStudentProfile($regNo)
    {
        try {
            $student = Student::where('reg_no', strtoupper($regNo))->first();
            if (!$student) {
                return response()->json(['status' => 'ERROR', 'message' => 'Student lookup registry record not found.']);
            }

            $student->delete();
            return response()->json(['status' => 'SUCCESS', 'message' => 'Student removed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Delete failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Tutor: fetch classroom and students.
     */
    public function getTutorClassroomRoster($tutorMobile)
    {
        try {
            // Find the class this tutor is supervising
            $class = ClassManagement::where('tutor_mobile_no', $tutorMobile)
                ->orWhere('mentor_mobile_no', $tutorMobile)
                ->first();

            if (!$class) {
                return response()->json(['status' => 'ERROR', 'message' => 'You are not assigned as a Tutor or Mentor to any classroom.']);
            }

            $students = Student::where('classroom_id', $class->classroom_id)->get();

            return response()->json([
                'status' => 'SUCCESS',
                'classroomId' => $class->classroom_id,
                'batchYear' => $class->batch_year,
                'isClassTutor' => ($class->tutor_mobile_no === $tutorMobile),
                'students' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin: Get system metrics/statistics.
     */
    public function getAdminStats()
    {
        try {
            $totalStaff = StaffProfile::count();
            $totalStudents = Student::count();
            $pendingStaff = StaffProfile::where('account_status', 'Pending')->count();
            $pendingStudents = Student::where('status', 'Pending')->count();
            $totalClassrooms = ClassManagement::count();

            return response()->json([
                'status' => 'SUCCESS',
                'stats' => [
                    'totalStaff' => $totalStaff,
                    'totalStudents' => $totalStudents,
                    'pendingApprovals' => $pendingStaff + $pendingStudents,
                    'pendingStaff' => $pendingStaff,
                    'pendingStudents' => $pendingStudents,
                    'totalClassrooms' => $totalClassrooms,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch stats: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper: Check if the logged-in user has permission to manage the target user.
     */
    private function checkUserManagementPermission($targetId, $targetType)
    {
        $currentUserId = Session::get('userId');
        $currentRole = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId) return false;

        // Super Admin, Principal, Admin, and Workshop Superintendent have elevated access
        if (in_array($currentRole, ['Super_Admin', 'Principal', 'Admin', 'Workshop_Superintendent'])) {
            return true;
        }

        // HOD check
        if ($currentRole === 'HOD') {
            if ($targetType === 'student') {
                $student = Student::where('reg_no', strtoupper($targetId))->first();
                return $student && strtoupper($student->branch) === strtoupper($currentBranch);
            } else {
                $staff = StaffProfile::where('mobile_no', $targetId)->first();
                if (!$staff) return false;
                
                // HOD can manage themselves
                if ($staff->mobile_no === $currentUserId) return true;

                // HOD can manage Faculty, Demonstrator, and Trade Instructor in their branch
                return strtoupper($staff->branch) === strtoupper($currentBranch) &&
                       in_array($staff->designation, ['Faculty', 'Demonstrator', 'Trade_Instructor']);
            }
        }

        // Tutor check
        $supervisedClass = ClassManagement::where('tutor_mobile_no', $currentUserId)
            ->orWhere('mentor_mobile_no', $currentUserId)
            ->first();
            
        if ($supervisedClass && $targetType === 'student') {
            $student = Student::where('reg_no', strtoupper($targetId))->first();
            return $student && $student->classroom_id === $supervisedClass->classroom_id;
        }

        // Default: can only manage their own staff profile password
        if ($targetType === 'staff' && $targetId === $currentUserId) {
            return true;
        }

        return false;
    }

    /**
     * Admin/Super Admin/HOD/Tutor: Fetch users scoped by permissions and filters.
     */
    public function getUsersList(Request $request)
    {
        $currentUserId = Session::get('userId');
        $currentRole = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.']);
        }

        $search = trim($request->query('search', ''));
        $branch = trim($request->query('branch', ''));
        $role = trim($request->query('role', '')); // 'student' or staff designation
        $status = trim($request->query('status', '')); // 'Pending' or 'Approved'

        // Determine supervised class (Tutor scope check)
        $supervisedClass = ClassManagement::where('tutor_mobile_no', $currentUserId)
            ->orWhere('mentor_mobile_no', $currentUserId)
            ->first();

        try {
            $users = [];

            // 1. Query Students
            $canQueryStudents = true;
            $studentScopeField = null;
            $studentScopeValue = null;

            if (in_array($currentRole, ['Super_Admin', 'Principal', 'Admin', 'Workshop_Superintendent'])) {
                // Full visibility
            } elseif ($currentRole === 'HOD') {
                $studentScopeField = 'branch';
                $studentScopeValue = strtoupper($currentBranch);
            } elseif ($supervisedClass) {
                $studentScopeField = 'classroom_id';
                $studentScopeValue = $supervisedClass->classroom_id;
            } else {
                $canQueryStudents = false;
            }

            if ($canQueryStudents && (empty($role) || strtolower($role) === 'student')) {
                $studentQuery = Student::query();

                if ($studentScopeField) {
                    $studentQuery->where($studentScopeField, $studentScopeValue);
                }

                if (!empty($search)) {
                    $studentQuery->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('reg_no', 'like', "%{$search}%")
                          ->orWhere('adm_no', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                }

                if (!empty($branch)) {
                    $studentQuery->where('branch', strtoupper($branch));
                }

                if (!empty($status)) {
                    $studentQuery->where('status', $status);
                }

                $students = $studentQuery->get()->map(function ($s) {
                    return [
                        'id' => $s->reg_no,
                        'name' => $s->name,
                        'email' => $s->email,
                        'role' => 'Student',
                        'branch' => $s->branch,
                        'status' => $s->status,
                        'photo_url' => $s->photo_url,
                        'type' => 'student',
                    ];
                })->toArray();

                $users = array_merge($users, $students);
            }

            // 2. Query Staff
            $canQueryStaff = true;
            $staffScopeFilter = null;

            if (in_array($currentRole, ['Super_Admin', 'Principal', 'Admin', 'Workshop_Superintendent'])) {
                // Full visibility
            } elseif ($currentRole === 'HOD') {
                $staffScopeFilter = 'hod';
            } else {
                $staffScopeFilter = 'self';
            }

            if ($canQueryStaff && (empty($role) || strtolower($role) !== 'student')) {
                $staffQuery = StaffProfile::query();

                if ($staffScopeFilter === 'hod') {
                    $staffQuery->where(function($q) use ($currentBranch, $currentUserId) {
                        $q->where(function($sub) use ($currentBranch) {
                            $sub->where('branch', strtoupper($currentBranch))
                                ->whereIn('designation', ['Lecturer', 'Demonstrator', 'Trade_Instructor', 'Tradesman', 'Laboratory_Assistant', 'Workshop_Instructor']);
                        })->orWhere('mobile_no', $currentUserId);
                    });
                } elseif ($staffScopeFilter === 'self') {
                    $staffQuery->where('mobile_no', $currentUserId);
                }

                if (!empty($search)) {
                    $staffQuery->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('mobile_no', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                }

                if (!empty($branch)) {
                    $staffQuery->where('branch', strtoupper($branch));
                }

                if (!empty($role)) {
                    $staffQuery->where('designation', $role);
                }

                if (!empty($status)) {
                    $staffQuery->where('account_status', $status);
                }

                $staff = $staffQuery->get()->map(function ($f) {
                    return [
                        'id' => $f->mobile_no,
                        'name' => $f->name,
                        'email' => $f->email,
                        'role' => $f->designation,
                        'branch' => $f->branch,
                        'status' => $f->account_status,
                        'photo_url' => $f->photo_url,
                        'type' => 'staff',
                    ];
                })->toArray();

                $users = array_merge($users, $staff);
            }

            // Sort users by name alphabetically
            usort($users, function ($a, $b) {
                return strcasecmp($a['name'], $b['name']);
            });

            return response()->json([
                'status' => 'SUCCESS',
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch user directory: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin/HOD/Tutor: Toggle user status (Approve, Suspend, Pending) and log to AuditTrail.
     */
    public function toggleUserStatus(Request $request)
    {
        $request->validate([
            'userId' => 'required|string',
            'userType' => 'required|string|in:student,staff',
            'newStatus' => 'required|string',
        ]);

        $userId = $request->input('userId');
        $userType = $request->input('userType');
        $newStatus = $request->input('newStatus'); // e.g. 'Approved', 'Pending', 'Suspended'

        if (!$this->checkUserManagementPermission($userId, $userType)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized action on this profile.']);
        }

        try {
            $targetName = '';
            if ($userType === 'student') {
                $student = Student::where('reg_no', strtoupper($userId))->first();
                if (!$student) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Student record not found.']);
                }
                $targetName = $student->name;
                $student->update(['status' => $newStatus]);
            } else {
                $staff = StaffProfile::where('mobile_no', $userId)->first();
                if (!$staff) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
                }
                $targetName = $staff->name;
                
                // Enforce single Principal constraint if trying to approve another Principal
                if ($staff->designation === 'Principal' && $newStatus === 'Approved') {
                    $hasOtherPrincipal = StaffProfile::where('designation', 'Principal')
                        ->where('mobile_no', '!=', $userId)
                        ->where('account_status', 'Approved')
                        ->exists();
                    if ($hasOtherPrincipal) {
                        return response()->json(['status' => 'ERROR', 'message' => 'Cannot approve multiple active Principals.']);
                    }
                }

                $staff->update(['account_status' => $newStatus]);
            }

            // Create Audit Log
            AuditLog::create([
                'performed_by' => Session::get('userId'),
                'performed_by_name' => Session::get('userName'),
                'target_id' => $userId,
                'target_name' => $targetName,
                'action' => $newStatus,
                'details' => "Account status changed to: " . $newStatus,
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'User status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to toggle status: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin/HOD/Tutor: Reset a user's password directly and log.
     */
    public function resetUserPassword(Request $request)
    {
        $request->validate([
            'userId' => 'required|string',
            'userType' => 'required|string|in:student,staff',
            'newPassword' => 'required|string|min:4',
        ]);

        $userId = $request->input('userId');
        $userType = $request->input('userType');
        $newPassword = $request->input('newPassword');

        if (!$this->checkUserManagementPermission($userId, $userType)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized action on this profile.']);
        }

        try {
            $targetName = '';
            if ($userType === 'student') {
                $student = Student::where('reg_no', strtoupper($userId))->first();
                if (!$student) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Student record not found.']);
                }
                $targetName = $student->name;
                $student->update(['password' => $newPassword]);
            } else {
                $staff = StaffProfile::where('mobile_no', $userId)->first();
                if (!$staff) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
                }
                $targetName = $staff->name;
                $staff->update(['password' => $newPassword]);
            }

            // Create Audit Log
            AuditLog::create([
                'performed_by' => Session::get('userId'),
                'performed_by_name' => Session::get('userName'),
                'target_id' => $userId,
                'target_name' => $targetName,
                'action' => 'Password Reset',
                'details' => 'Account password reset directly by administrator/supervisor',
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Password reset successful.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to reset password: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin/HOD: Change a staff member's role designation and log.
     */
    public function changeUserRole(Request $request)
    {
        $request->validate([
            'userId' => 'required|string',
            'newRole' => 'required|string',
        ]);

        $userId = $request->input('userId');
        $newRole = $request->input('newRole'); // designation string

        if (!$this->checkUserManagementPermission($userId, 'staff')) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized action on this profile.']);
        }

        try {
            $staff = StaffProfile::where('mobile_no', $userId)->first();
            if (!$staff) {
                return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
            }

            $oldRole = $staff->designation;

            // Enforce role checks
            if ($newRole === 'Principal') {
                $hasOtherPrincipal = StaffProfile::where('designation', 'Principal')
                    ->where('mobile_no', '!=', $userId)
                    ->where('account_status', 'Approved')
                    ->exists();
                if ($hasOtherPrincipal) {
                    return response()->json(['status' => 'ERROR', 'message' => 'An active Principal already exists in the system.']);
                }
            }

            $staff->update(['designation' => $newRole]);

            // Create Audit Log
            AuditLog::create([
                'performed_by' => Session::get('userId'),
                'performed_by_name' => Session::get('userName'),
                'target_id' => $userId,
                'target_name' => $staff->name,
                'action' => 'Role Changed',
                'details' => "Role designation changed from {$oldRole} to {$newRole}",
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Staff designation changed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to change role: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin/HOD/Tutor: Delete a user permanently.
     */
    public function deleteUser(Request $request)
    {
        $request->validate([
            'targetId' => 'required|string',
            'userType' => 'required|string|in:student,staff',
        ]);

        $targetId = $request->input('targetId');
        $userType = $request->input('userType');

        if (!$this->checkUserManagementPermission($targetId, $userType)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized action on this profile.']);
        }

        try {
            $targetName = '';
            if ($userType === 'student') {
                $student = Student::where('reg_no', strtoupper($targetId))->first();
                if (!$student) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Student record not found.']);
                }
                $targetName = $student->name;
                $student->delete();
            } else {
                $staff = StaffProfile::where('mobile_no', $targetId)->first();
                if (!$staff) {
                    return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
                }
                $targetName = $staff->name;
                $staff->delete();
            }

            // Create Audit Log
            AuditLog::create([
                'performed_by' => Session::get('userId'),
                'performed_by_name' => Session::get('userName'),
                'target_id' => $targetId,
                'target_name' => $targetName,
                'action' => 'Deleted',
                'details' => "Account permanently removed from database ({$userType})",
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'User profile deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Delete failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin/Super Admin/HOD/Tutor/Staff: Retrieve audit logs scoped by credentials.
     */
    public function getAuditLogs(Request $request)
    {
        $currentUserId = Session::get('userId');
        $currentRole = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized access.']);
        }

        $targetId = trim($request->query('targetId', ''));

        try {
            $query = AuditLog::query();

            // Scope query according to credentials:
            if (in_array($currentRole, ['Super_Admin', 'Principal', 'Admin', 'Workshop_Superintendent'])) {
                if (!empty($targetId)) {
                    $query->where('target_id', $targetId);
                }
            } elseif ($currentRole === 'HOD') {
                $query->where(function($q) use ($currentBranch, $currentUserId) {
                    $q->whereIn('target_id', function($sub) use ($currentBranch) {
                        $sub->select('mobile_no')->from('staff_profiles')->where('branch', strtoupper($currentBranch))
                            ->union(
                                \DB::table('students')->select('reg_no')->where('branch', strtoupper($currentBranch))
                            );
                    })->orWhere('performed_by', $currentUserId)
                      ->orWhere('target_id', $currentUserId);
                });

                if (!empty($targetId)) {
                    $query->where('target_id', $targetId);
                }
            } else {
                $supervisedClass = ClassManagement::where('tutor_mobile_no', $currentUserId)
                    ->orWhere('mentor_mobile_no', $currentUserId)
                    ->first();

                if ($supervisedClass) {
                    // Class Tutor - can see logs of classroom students, actions they performed, or their own profile
                    $query->where(function($q) use ($supervisedClass, $currentUserId) {
                        $q->whereIn('target_id', function($sub) use ($supervisedClass) {
                            $sub->select('reg_no')->from('students')->where('classroom_id', $supervisedClass->classroom_id);
                        })->orWhere('performed_by', $currentUserId)
                          ->orWhere('target_id', $currentUserId);
                    });

                    if (!empty($targetId)) {
                        $query->where('target_id', $targetId);
                    }
                } else {
                    // Regular staff members can only inspect logs involving themselves
                    $query->where(function($q) use ($currentUserId) {
                        $q->where('target_id', $currentUserId)
                          ->orWhere('performed_by', $currentUserId);
                    });
                }
            }

            $logs = $query->orderBy('created_at', 'desc')->take(200)->get();

            return response()->json([
                'status' => 'SUCCESS',
                'logs' => $logs
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to query audit logs: ' . $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------------------
    // HOD BATCH MANAGEMENT METHODS
    // -------------------------------------------------------------------------

    /**
     * HOD: List all batches/classrooms for this HOD's department branch.
     */
    public function getHodBatches()
    {
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId || $currentRole !== 'HOD') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD access required.']);
        }

        try {
            $batches = ClassManagement::where('branch', strtoupper($currentBranch))
                ->orderBy('batch_year', 'desc')
                ->get()
                ->map(function ($cls) {
                    $tutorName  = null;
                    $mentorName = null;

                    if ($cls->tutor_mobile_no) {
                        $tutor     = StaffProfile::where('mobile_no', $cls->tutor_mobile_no)->first();
                        $tutorName = $tutor ? $tutor->name : null;
                    }
                    if ($cls->mentor_mobile_no) {
                        $mentor     = StaffProfile::where('mobile_no', $cls->mentor_mobile_no)->first();
                        $mentorName = $mentor ? $mentor->name : null;
                    }

                    $studentCount = Student::where('classroom_id', $cls->classroom_id)->count();

                    return [
                        'classroom_id'      => $cls->classroom_id,
                        'branch'            => $cls->branch,
                        'batch_year'        => $cls->batch_year,
                        'tutor_mobile_no'   => $cls->tutor_mobile_no,
                        'tutor_name'        => $tutorName,
                        'mentor_mobile_no'  => $cls->mentor_mobile_no,
                        'mentor_name'       => $mentorName,
                        'student_count'     => $studentCount,
                    ];
                });

            return response()->json(['status' => 'SUCCESS', 'batches' => $batches]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch batches: ' . $e->getMessage()]);
        }
    }

    /**
     * HOD: Create a new batch/classroom for this department.
     * Also backfills any unassigned students (classroom_id IS NULL) that match
     * the derived classroom_id (by branch + admission_year).
     */
    public function createHodBatch(\Illuminate\Http\Request $request)
    {
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId || $currentRole !== 'HOD') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD access required.']);
        }

        $request->validate([
            'admission_year'    => 'required|integer|min:2000|max:2100',
            'tutor_mobile_no'   => 'nullable|string',
            'mentor_mobile_no'  => 'nullable|string',
        ]);

        $admYear    = (int) $request->input('admission_year');
        $branchCode = strtoupper($currentBranch);
        $startYear  = $admYear;
        $endYear    = $admYear + 3;
        $classroomId = "{$branchCode}_{$startYear}_{$endYear}";

        // Validate optional tutor/mentor belong to same branch
        $tutorMobile  = $request->input('tutor_mobile_no');
        $mentorMobile = $request->input('mentor_mobile_no');

        if ($tutorMobile) {
            $tutor = StaffProfile::where('mobile_no', $tutorMobile)->first();
            if (!$tutor || strtoupper($tutor->branch) !== $branchCode) {
                return response()->json(['status' => 'ERROR', 'message' => 'Selected Tutor does not belong to your department.']);
            }
        }
        if ($mentorMobile) {
            $mentor = StaffProfile::where('mobile_no', $mentorMobile)->first();
            if (!$mentor || strtoupper($mentor->branch) !== $branchCode) {
                return response()->json(['status' => 'ERROR', 'message' => 'Selected Mentor does not belong to your department.']);
            }
        }

        // Check if batch already exists
        $existing = ClassManagement::where('classroom_id', $classroomId)->first();
        if ($existing) {
            return response()->json([
                'status'  => 'ERROR',
                'message' => "Batch {$classroomId} already exists for this department.",
            ]);
        }

        try {
            $batch = ClassManagement::create([
                'classroom_id'     => $classroomId,
                'branch'           => $branchCode,
                'batch_year'       => $startYear,
                'tutor_mobile_no'  => $tutorMobile  ?: null,
                'mentor_mobile_no' => $mentorMobile ?: null,
            ]);

            // Backfill: assign any already-registered students that computed this
            // classroom_id but were left with classroom_id = NULL because the batch
            // didn't exist at time of registration.
            $backfilledCount = Student::where('branch', $branchCode)
                ->where('admission_year', $admYear)
                ->whereNull('classroom_id')
                ->update(['classroom_id' => $classroomId]);

            // Also handle LET students (they join in year 2 → admYear = startYear+1)
            $letBackfilled = Student::where('branch', $branchCode)
                ->where('admission_year', $admYear + 1)
                ->where('admission_type', 'LET')
                ->whereNull('classroom_id')
                ->update(['classroom_id' => $classroomId]);

            $backfilledCount += $letBackfilled;

            AuditLog::create([
                'performed_by'      => $currentUserId,
                'performed_by_name' => Session::get('userName'),
                'target_id'         => $classroomId,
                'target_name'       => "Batch {$classroomId}",
                'action'            => 'Batch Created',
                'details'           => "HOD created batch {$classroomId} for admission year {$admYear}. Backfilled {$backfilledCount} student(s).",
                'ip_address'        => $request->ip(),
            ]);

            return response()->json([
                'status'          => 'SUCCESS',
                'message'         => "Batch {$classroomId} created successfully. {$backfilledCount} existing student(s) auto-assigned.",
                'classroom_id'    => $classroomId,
                'backfilled'      => $backfilledCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to create batch: ' . $e->getMessage()]);
        }
    }

    /**
     * HOD: Assign (or change) a Tutor for an existing batch.
     */
    public function assignBatchTutor(\Illuminate\Http\Request $request)
    {
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId || $currentRole !== 'HOD') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD access required.']);
        }

        $request->validate([
            'classroom_id'    => 'required|string',
            'tutor_mobile_no' => 'nullable|string',
        ]);

        $classroomId = $request->input('classroom_id');
        $tutorMobile = $request->input('tutor_mobile_no');

        $batch = ClassManagement::where('classroom_id', $classroomId)
            ->where('branch', $currentBranch)
            ->first();
        if (!$batch) {
            return response()->json(['status' => 'ERROR', 'message' => 'Batch not found or not in your department.']);
        }

        $oldTutor = $batch->tutor_mobile_no;

        if (empty($tutorMobile)) {
            $batch->update(['tutor_mobile_no' => null]);
            AuditLog::create([
                'performed_by'      => $currentUserId,
                'performed_by_name' => Session::get('userName'),
                'target_id'         => $classroomId,
                'target_name'       => "Batch {$classroomId}",
                'action'            => 'Tutor Removed',
                'details'           => "Tutor removed. Previous: " . ($oldTutor ?: 'None'),
                'ip_address'        => $request->ip(),
            ]);
            return response()->json([
                'status'     => 'SUCCESS',
                'message'    => "Tutor has been removed for batch {$classroomId}.",
                'tutor_name' => null,
            ]);
        }

        $tutor = StaffProfile::where('mobile_no', $tutorMobile)->first();
        if (!$tutor || strtoupper($tutor->branch) !== strtoupper($currentBranch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Selected staff member does not belong to your department.']);
        }

        $batch->update(['tutor_mobile_no' => $tutorMobile]);

        AuditLog::create([
            'performed_by'      => $currentUserId,
            'performed_by_name' => Session::get('userName'),
            'target_id'         => $classroomId,
            'target_name'       => "Batch {$classroomId}",
            'action'            => 'Tutor Assigned',
            'details'           => "Tutor set to {$tutor->name} ({$tutorMobile}). Previous: " . ($oldTutor ?: 'None'),
            'ip_address'        => $request->ip(),
        ]);

        return response()->json([
            'status'     => 'SUCCESS',
            'message'    => "{$tutor->name} has been set as Tutor for batch {$classroomId}.",
            'tutor_name' => $tutor->name,
        ]);
    }

    /**
     * HOD: Assign (or change) a Mentor for an existing batch.
     */
    public function assignBatchMentor(\Illuminate\Http\Request $request)
    {
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId || $currentRole !== 'HOD') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD access required.']);
        }

        $request->validate([
            'classroom_id'     => 'required|string',
            'mentor_mobile_no' => 'nullable|string',
        ]);

        $classroomId  = $request->input('classroom_id');
        $mentorMobile = $request->input('mentor_mobile_no');

        $batch = ClassManagement::where('classroom_id', $classroomId)
            ->where('branch', $currentBranch)
            ->first();
        if (!$batch) {
            return response()->json(['status' => 'ERROR', 'message' => 'Batch not found or not in your department.']);
        }

        $oldMentor = $batch->mentor_mobile_no;

        if (empty($mentorMobile)) {
            $batch->update(['mentor_mobile_no' => null]);
            AuditLog::create([
                'performed_by'      => $currentUserId,
                'performed_by_name' => Session::get('userName'),
                'target_id'         => $classroomId,
                'target_name'       => "Batch {$classroomId}",
                'action'            => 'Mentor Removed',
                'details'           => "Mentor removed. Previous: " . ($oldMentor ?: 'None'),
                'ip_address'        => $request->ip(),
            ]);
            return response()->json([
                'status'      => 'SUCCESS',
                'message'     => "Mentor has been removed for batch {$classroomId}.",
                'mentor_name' => null,
            ]);
        }

        $mentor = StaffProfile::where('mobile_no', $mentorMobile)->first();
        if (!$mentor || strtoupper($mentor->branch) !== strtoupper($currentBranch)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Selected staff member does not belong to your department.']);
        }

        $batch->update(['mentor_mobile_no' => $mentorMobile]);

        AuditLog::create([
            'performed_by'      => $currentUserId,
            'performed_by_name' => Session::get('userName'),
            'target_id'         => $classroomId,
            'target_name'       => "Batch {$classroomId}",
            'action'            => 'Mentor Assigned',
            'details'           => "Mentor set to {$mentor->name} ({$mentorMobile}). Previous: " . ($oldMentor ?: 'None'),
            'ip_address'        => $request->ip(),
        ]);

        return response()->json([
            'status'      => 'SUCCESS',
            'message'     => "{$mentor->name} has been set as Mentor for batch {$classroomId}.",
            'mentor_name' => $mentor->name,
        ]);
    }

    /**
     * HOD: Get all students enrolled in a specific batch/classroom.
     */
    public function getBatchStudents(\Illuminate\Http\Request $request, $classroomId)
    {
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId || $currentRole !== 'HOD') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD access required.']);
        }

        $batch = ClassManagement::where('classroom_id', $classroomId)
            ->where('branch', strtoupper($currentBranch))
            ->first();
        if (!$batch) {
            return response()->json(['status' => 'ERROR', 'message' => 'Batch not found or not in your department.']);
        }

        try {
            $students = Student::where('classroom_id', $classroomId)
                ->orderBy('name')
                ->get()
                ->map(function ($s) {
                    return [
                        'reg_no'         => $s->reg_no,
                        'adm_no'         => $s->adm_no,
                        'name'           => $s->name,
                        'email'          => $s->email,
                        'phone'          => $s->phone,
                        'admission_year' => $s->admission_year,
                        'admission_type' => $s->admission_type,
                        'status'         => $s->status,
                        'photo_url'      => $s->photo_url,
                    ];
                });

            return response()->json(['status' => 'SUCCESS', 'students' => $students]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch students: ' . $e->getMessage()]);
        }
    }

    /**
     * HOD: Get all staff members in the HOD's department (for tutor/mentor dropdowns).
     */
    public function getDeptStaff()
    {
        $currentUserId = Session::get('userId');
        $currentRole   = Session::get('userRole');
        $currentBranch = Session::get('userBranch');

        if (!$currentUserId || $currentRole !== 'HOD') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. HOD access required.']);
        }

        try {
            $staff = StaffProfile::where('branch', $currentBranch)
                ->where('account_status', 'Approved')
                ->whereNotIn('designation', ['HOD', 'Principal', 'Super_Admin', 'Admin'])
                ->orderBy('name')
                ->get()
                ->map(function ($f) {
                    return [
                        'mobile_no'   => $f->mobile_no,
                        'name'        => $f->name,
                        'designation' => $f->designation,
                        'photo_url'   => $f->photo_url,
                    ];
                });

            return response()->json(['status' => 'SUCCESS', 'staff' => $staff]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to fetch department staff: ' . $e->getMessage()]);
        }
    }

    /**
     * HOD: Get Subjects for a Batch
     */
    public function getBatchSubjects(Request $request, $classroomId)
    {
        $currentRole = Session::get('userRole');
        if ($currentRole !== 'HOD') return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);

        $semester = $request->query('semester');
        try {
            $query = BatchSubject::with('staffAssignments.staffProfile')->where('classroom_id', $classroomId);
            if ($semester) {
                $query->where('semester', $semester);
            }
            
            $subjects = $query->get()->map(function ($subj) {
                return [
                    'id' => $subj->id,
                    'semester' => $subj->semester,
                    'subject_code' => $subj->subject_code,
                    'subject_name' => $subj->subject_name,
                    'subject_type' => $subj->subject_type,
                    'staff' => $subj->staffAssignments->map(function ($sa) {
                        return [
                            'mobile_no' => $sa->staff_mobile_no,
                            'name' => $sa->staffProfile ? $sa->staffProfile->name : 'Unknown',
                            'branch' => $sa->staffProfile ? $sa->staffProfile->branch : '',
                        ];
                    })
                ];
            });

            // Also fetch ALL approved staff across the college for the assignment dropdown
            // To support inter-department lecturer allocation
            $allStaff = StaffProfile::where('account_status', 'Approved')
                ->whereNotIn('designation', ['Principal', 'Super_Admin', 'Admin'])
                ->orderBy('branch')
                ->orderBy('name')
                ->get()
                ->map(function ($f) {
                    return [
                        'mobile_no' => $f->mobile_no,
                        'name' => $f->name,
                        'branch' => $f->branch,
                        'designation' => $f->designation,
                    ];
                });

            return response()->json([
                'status' => 'SUCCESS', 
                'subjects' => $subjects,
                'all_staff' => $allStaff
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * HOD: Create a Subject for a Batch
     */
    public function createBatchSubject(Request $request)
    {
        $currentRole = Session::get('userRole');
        if ($currentRole !== 'HOD') return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);

        $request->validate([
            'classroom_id' => 'required|string',
            'semester' => 'required|integer|min:1|max:6',
            'subject_code' => 'required|string',
            'subject_name' => 'required|string',
            'subject_type' => 'required|string'
        ]);

        try {
            // Verify HOD branch vs classroom branch
            $classroom = ClassManagement::where('classroom_id', $request->classroom_id)->first();
            if (!$classroom || $classroom->branch !== Session::get('userBranch')) {
                return response()->json(['status' => 'ERROR', 'message' => 'Invalid classroom.']);
            }

            BatchSubject::create([
                'classroom_id' => $request->classroom_id,
                'semester' => $request->semester,
                'subject_code' => strtoupper($request->subject_code),
                'subject_name' => $request->subject_name,
                'subject_type' => $request->subject_type
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Subject created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * HOD: Assign Staff to Subject
     */
    public function assignSubjectStaff(Request $request, $subjectId)
    {
        $currentRole = Session::get('userRole');
        if ($currentRole !== 'HOD') return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);

        $request->validate([
            'staff_mobile_nos' => 'array',
            'staff_mobile_nos.*' => 'string'
        ]);

        try {
            $subject = BatchSubject::with('classroom')->find($subjectId);
            if (!$subject || $subject->classroom->branch !== Session::get('userBranch')) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found or unauthorized.']);
            }

            // Sync the assignments
            SubjectStaffAssignment::where('batch_subject_id', $subjectId)->delete();
            
            $staffNos = $request->staff_mobile_nos ?? [];
            foreach ($staffNos as $staffNo) {
                SubjectStaffAssignment::create([
                    'batch_subject_id' => $subjectId,
                    'staff_mobile_no' => $staffNo
                ]);
            }

            return response()->json(['status' => 'SUCCESS', 'message' => 'Staff assigned successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * HOD: Delete Subject
     */
    public function deleteBatchSubject($subjectId)
    {
        $currentRole = Session::get('userRole');
        if ($currentRole !== 'HOD') return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);

        try {
            $subject = BatchSubject::with('classroom')->find($subjectId);
            if (!$subject || $subject->classroom->branch !== Session::get('userBranch')) {
                return response()->json(['status' => 'ERROR', 'message' => 'Subject not found or unauthorized.']);
            }

            $subject->delete();
            return response()->json(['status' => 'SUCCESS', 'message' => 'Subject deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    /**
     * LECTURER: Get all batches assigned to the lecturer (Tutor, Mentor, Subject Staff)
     */
    public function getLecturerBatches()
    {
        $userId = \Illuminate\Support\Facades\Session::get('userId');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.']);
        }

        try {
            // 1. Get batches where user is Tutor or Mentor
            $managedBatches = \App\Models\ClassManagement::where('tutor_mobile_no', $userId)
                ->orWhere('mentor_mobile_no', $userId)
                ->get();

            // 2. Get batches where user is assigned to a subject
            $subjectAssignments = \App\Models\SubjectStaffAssignment::with(['batchSubject.classroom'])
                ->where('staff_mobile_no', $userId)
                ->get();

            $batchesMap = [];

            // Add managed batches
            foreach ($managedBatches as $batch) {
                $cid = $batch->classroom_id;
                if (!isset($batchesMap[$cid])) {
                    $batchesMap[$cid] = [
                        'classroom_id' => $batch->classroom_id,
                        'batch_year' => $batch->batch_year,
                        'branch' => $batch->branch,
                        'roles' => [],
                        'subjects' => []
                    ];
                }
                if ($batch->tutor_mobile_no === $userId) $batchesMap[$cid]['roles'][] = 'Tutor';
                if ($batch->mentor_mobile_no === $userId) $batchesMap[$cid]['roles'][] = 'Mentor';
            }

            // Add subject assignments
            foreach ($subjectAssignments as $sa) {
                if ($sa->batchSubject && $sa->batchSubject->classroom) {
                    $batch = $sa->batchSubject->classroom;
                    $cid = $batch->classroom_id;
                    if (!isset($batchesMap[$cid])) {
                        $batchesMap[$cid] = [
                            'classroom_id' => $batch->classroom_id,
                            'batch_year' => $batch->batch_year,
                            'branch' => $batch->branch,
                            'roles' => [],
                            'subjects' => []
                        ];
                    }
                    if (!in_array('Subject Staff', $batchesMap[$cid]['roles'])) {
                        $batchesMap[$cid]['roles'][] = 'Subject Staff';
                    }
                    $batchesMap[$cid]['subjects'][] = [
                        'id' => $sa->batchSubject->id,
                        'code' => $sa->batchSubject->subject_code,
                        'name' => $sa->batchSubject->subject_name,
                        'semester' => $sa->batchSubject->semester,
                        'type' => $sa->batchSubject->subject_type
                    ];
                }
            }

            // Sort subjects by semester
            foreach ($batchesMap as &$b) {
                usort($b['subjects'], function($a, $b_item) {
                    return $a['semester'] <=> $b_item['semester'];
                });
            }

            return response()->json(['status' => 'SUCCESS', 'batches' => array_values($batchesMap)]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }
}
