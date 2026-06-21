<?php

namespace App\Http\Controllers;

use App\Models\ClassManagement;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\StudentResponse;
use App\Models\AcademicMark;
use App\Models\AuditLog;
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
                                ->whereIn('designation', ['Faculty', 'Demonstrator', 'Trade_Instructor']);
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
}
