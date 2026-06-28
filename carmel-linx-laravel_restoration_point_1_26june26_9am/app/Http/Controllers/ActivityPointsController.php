<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityPointClaim;
use App\Models\Student;
use App\Models\ClassManagement;
use Illuminate\Support\Facades\Session;

class ActivityPointsController extends Controller
{
    // === STUDENT ENDPOINTS ===

    public function getStudentPoints()
    {
        $regNo = Session::get('userId');
        if (Session::get('userRole') !== 'Student' || !$regNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $claims = ActivityPointClaim::where('reg_no', $regNo)->orderBy('created_at', 'desc')->get();
        
        $verifiedClaims = $claims->where('status', 'Verified');
        $totalPoints = $verifiedClaims->sum('points_awarded');
        
        $split = [];
        foreach ($verifiedClaims as $c) {
            if (!isset($split[$c->activity_segment])) {
                $split[$c->activity_segment] = 0;
            }
            $split[$c->activity_segment] += $c->points_awarded;
        }

        return response()->json([
            'status' => 'SUCCESS',
            'claims' => $claims,
            'total_points' => $totalPoints,
            'split' => $split
        ]);
    }

    public function submitClaim(Request $request)
    {
        $regNo = Session::get('userId');
        if (Session::get('userRole') !== 'Student' || !$regNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'semester' => 'required|integer',
            'activity_segment' => 'required|string',
            'activity_name' => 'required|string',
            'level' => 'required|string',
            'points_claimed' => 'required|integer',
            'document_reference' => 'nullable|string'
        ]);

        $claim = ActivityPointClaim::create([
            'reg_no' => $regNo,
            'semester' => $request->semester,
            'activity_segment' => $request->activity_segment,
            'activity_name' => $request->activity_name,
            'level' => $request->level,
            'points_claimed' => $request->points_claimed,
            'document_reference' => $request->document_reference,
            'status' => 'Pending'
        ]);

        return response()->json(['status' => 'SUCCESS', 'message' => 'Claim submitted successfully.', 'claim' => $claim]);
    }

    // === TUTOR ENDPOINTS ===

    public function getClassroomClaims()
    {
        $mobileNo = Session::get('userId');
        
        // Find the classroom where this user is the tutor
        $classroom = ClassManagement::where('tutor_mobile_no', $mobileNo)->first();
        if (!$classroom) {
            return response()->json(['status' => 'ERROR', 'message' => 'You are not assigned as a tutor to any classroom.']);
        }

        // Get all students in this classroom
        $studentRegNos = Student::where('classroom_id', $classroom->classroom_id)->pluck('reg_no');

        // Get all claims for these students
        $claims = ActivityPointClaim::with('student:reg_no,name,classroom_id')
            ->whereIn('reg_no', $studentRegNos)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'SUCCESS',
            'claims' => $claims
        ]);
    }

    public function verifyClaim(Request $request, $id)
    {
        $mobileNo = Session::get('userId');
        $claim = ActivityPointClaim::find($id);

        if (!$claim) {
            return response()->json(['status' => 'ERROR', 'message' => 'Claim not found.']);
        }

        $request->validate([
            'status' => 'required|in:Verified,Rejected',
            'points_awarded' => 'required|integer'
        ]);

        $claim->update([
            'status' => $request->status,
            'points_awarded' => $request->status === 'Verified' ? $request->points_awarded : 0,
            'verified_by' => $mobileNo,
            'verified_at' => now()
        ]);

        return response()->json(['status' => 'SUCCESS', 'message' => 'Claim updated successfully.', 'claim' => $claim]);
    }

    public function getStudentSummary($regNo)
    {
        $role = Session::get('userRole');
        if (!in_array($role, ['Tutor', 'HOD', 'Principal', 'Demonstrator', 'Trade_Instructor', 'Workshop_Superintendent', 'Gen_Dept_Coordinator_Aided', 'Gen_Dept_Coordinator_Self_Finance'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $claims = ActivityPointClaim::where('reg_no', $regNo)->where('status', 'Verified')->get();
        $totalPoints = $claims->sum('points_awarded');
        
        $split = [];
        foreach ($claims as $c) {
            if (!isset($split[$c->activity_segment])) {
                $split[$c->activity_segment] = 0;
            }
            $split[$c->activity_segment] += $c->points_awarded;
        }

        return response()->json([
            'status' => 'SUCCESS',
            'total_points' => $totalPoints,
            'split' => $split
        ]);
    }
}
