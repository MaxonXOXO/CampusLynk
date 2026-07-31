<?php

namespace App\Http\Controllers;

use App\Models\StaffLeaveRequest;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class StaffLeaveController extends Controller
{
    /**
     * Submit a new staff leave application.
     */
    public function applyLeave(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        $staff = StaffProfile::where('mobile_no', $mobileNo)->first();
        $staffName = $staff ? $staff->name : Session::get('userName', 'Staff Member');
        $designation = $staff ? ($staff->designation ?? Session::get('userRole', 'Lecturer')) : Session::get('userRole', 'Lecturer');
        $department = $staff ? ($staff->department ?? Session::get('userBranch', 'General')) : Session::get('userBranch', 'General');

        $request->validate([
            'leave_type'        => 'required|string',
            'from_date'         => 'required|date',
            'to_date'           => 'required|date|after_or_equal:from_date',
            'session_type'      => 'required|string|in:Full Day,FN,AN',
            'total_days'        => 'required|numeric|min:0.5',
            'reason'            => 'required|string',
            'work_arrangement'  => 'nullable|array',
        ]);

        try {
            // Generate unique leave code
            $leaveCode = 'SLV-' . date('Y') . '-' . strtoupper(Str::random(6));

            // Generate digital signature hash
            $sigPayload = $mobileNo . '|' . $leaveCode . '|' . $request->from_date . '|' . microtime();
            $sigHash = hash('sha256', $sigPayload);

            $leave = StaffLeaveRequest::create([
                'leave_code'           => $leaveCode,
                'staff_mobile'         => $mobileNo,
                'staff_name'           => $staffName,
                'designation'          => $designation,
                'department'           => $department,
                'leave_type'           => $request->leave_type,
                'from_date'            => $request->from_date,
                'to_date'              => $request->to_date,
                'session_type'         => $request->session_type,
                'total_days'           => $request->total_days,
                'reason'               => $request->reason,
                'work_arrangement'     => $request->work_arrangement ?? [],
                'staff_signature_hash' => $sigHash,
                'submitted_at'         => now(),
                'hod_status'           => 'Pending',
                'coordinator_status'   => 'Pending',
                'principal_status'     => 'Pending',
                'overall_status'       => 'Pending_HOD',
            ]);

            return response()->json([
                'status'     => 'SUCCESS',
                'message'    => 'Leave application submitted successfully and sent to HOD for approval.',
                'leave_code' => $leaveCode,
                'request'    => $leave
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch leave application history for the logged-in staff member.
     */
    public function getMyLeaveHistory()
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        try {
            $history = StaffLeaveRequest::where('staff_mobile', $mobileNo)
                ->orderByDesc('id')
                ->get();

            return response()->json([
                'status'  => 'SUCCESS',
                'leaves'  => $history
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get pending approvals based on logged-in user role (HOD, Coordinator, Principal).
     */
    public function getPendingApprovals()
    {
        $mobileNo = Session::get('userId');
        $userRole = Session::get('userRole');
        $department = Session::get('userBranch');

        if (!$mobileNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        try {
            $query = StaffLeaveRequest::query();

            if ($userRole === 'HOD') {
                $query->where('department', $department)
                      ->where('overall_status', 'Pending_HOD');
            } elseif ($userRole === 'Academic_Coordinator' || str_contains(strtolower($userRole), 'coordinator')) {
                $query->where('overall_status', 'Pending_Coordinator');
            } elseif (in_array($userRole, ['Principal', 'Super_Admin', 'Admin'])) {
                $query->where(function($q) {
                    $q->where('overall_status', 'Pending_Principal')
                      ->orWhere('overall_status', 'Pending_HOD')
                      ->orWhere('overall_status', 'Pending_Coordinator');
                });
            } else {
                return response()->json(['status' => 'SUCCESS', 'approvals' => []]);
            }

            $approvals = $query->orderByDesc('id')->get();

            return response()->json([
                'status'    => 'SUCCESS',
                'role'      => $userRole,
                'approvals' => $approvals
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Process multi-stage leave approval (HOD -> Coordinator -> Principal).
     */
    public function processApproval(Request $request)
    {
        $mobileNo = Session::get('userId');
        $userRole = Session::get('userRole');
        $actorName = Session::get('userName', 'Approver');

        if (!$mobileNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);
        }

        $request->validate([
            'leave_id'  => 'required|exists:staff_leave_requests,id',
            'stage'     => 'required|in:HOD,Coordinator,Principal',
            'action'    => 'required|in:Approved,Rejected',
            'remarks'   => 'nullable|string',
        ]);

        try {
            $leave = StaffLeaveRequest::findOrFail($request->leave_id);

            if ($request->action === 'Rejected') {
                if ($request->stage === 'HOD') {
                    $leave->hod_status = 'Rejected';
                    $leave->hod_mobile = $mobileNo;
                    $leave->hod_name = $actorName;
                    $leave->hod_remarks = $request->remarks;
                    $leave->hod_action_at = now();
                } elseif ($request->stage === 'Coordinator') {
                    $leave->coordinator_status = 'Rejected';
                    $leave->coordinator_mobile = $mobileNo;
                    $leave->coordinator_name = $actorName;
                    $leave->coordinator_remarks = $request->remarks;
                    $leave->coordinator_action_at = now();
                } elseif ($request->stage === 'Principal') {
                    $leave->principal_status = 'Rejected';
                    $leave->principal_mobile = $mobileNo;
                    $leave->principal_name = $actorName;
                    $leave->principal_remarks = $request->remarks;
                    $leave->principal_action_at = now();
                }
                $leave->overall_status = 'Rejected';
                $leave->save();

                return response()->json(['status' => 'SUCCESS', 'message' => 'Leave application rejected.']);
            }

            // Processing APPROVAL
            if ($request->stage === 'HOD') {
                $leave->hod_status = 'Approved';
                $leave->hod_mobile = $mobileNo;
                $leave->hod_name = $actorName;
                $leave->hod_remarks = $request->remarks;
                $leave->hod_action_at = now();
                $leave->overall_status = 'Pending_Coordinator';
            } elseif ($request->stage === 'Coordinator') {
                $leave->coordinator_status = 'Approved';
                $leave->coordinator_mobile = $mobileNo;
                $leave->coordinator_name = $actorName;
                $leave->coordinator_remarks = $request->remarks;
                $leave->coordinator_action_at = now();
                $leave->overall_status = 'Pending_Principal';
            } elseif ($request->stage === 'Principal') {
                $leave->principal_status = 'Approved';
                $leave->principal_mobile = $mobileNo;
                $leave->principal_name = $actorName;
                $leave->principal_remarks = $request->remarks;
                $leave->principal_action_at = now();
                $leave->overall_status = 'Approved';
            }

            $leave->save();

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Leave application approved successfully.',
                'leave'   => $leave
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate printable A4 PDF formal Leave Application view.
     */
    public function generateLeavePDF($id)
    {
        $leave = StaffLeaveRequest::where('id', $id)
            ->orWhere('leave_code', $id)
            ->firstOrFail();

        return view('staff_leave_pdf', compact('leave'));
    }

    /**
     * View Consolidated Staff Leave Reports Page / Data.
     */
    public function getLeaveReports(Request $request)
    {
        $department = $request->query('department');
        $leaveType = $request->query('leave_type');
        $status = $request->query('status');

        $query = StaffLeaveRequest::query();

        if (!empty($department)) {
            $query->where('department', $department);
        }
        if (!empty($leaveType)) {
            $query->where('leave_type', $leaveType);
        }
        if (!empty($status)) {
            $query->where('overall_status', $status);
        }

        $leaves = $query->orderByDesc('id')->get();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'SUCCESS', 'leaves' => $leaves]);
        }

        return view('staff_leave_reports', compact('leaves'));
    }
}
