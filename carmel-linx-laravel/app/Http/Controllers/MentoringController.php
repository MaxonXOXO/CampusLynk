<?php

namespace App\Http\Controllers;

use App\Models\ClassManagement;
use App\Models\MentoringBatch;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\TutorDiary;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MentoringController extends Controller
{
    // ─────────────────────────────────────────────
    //  HELPER: resolve which classrooms the actor mentors
    // ─────────────────────────────────────────────
    private function getMentorClassrooms(): array
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return [];

        // As Tutor (Mentor-1) for classrooms
        $asTutor = ClassManagement::where('tutor_mobile_no', $mobileNo)
            ->pluck('classroom_id')->toArray();

        // As Mentor-2 for classrooms
        $asMentor2 = ClassManagement::where('mentor_mobile_no', $mobileNo)
            ->pluck('classroom_id')->toArray();

        return array_unique(array_merge($asTutor, $asMentor2));
    }

    // ─────────────────────────────────────────────
    //  GET /api/mentoring/my-batches
    //  Returns batches and students assigned to the current mentor
    // ─────────────────────────────────────────────
    public function getMyBatches()
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $classrooms = $this->getMentorClassrooms();

            $result = [];
            foreach ($classrooms as $classroomId) {
                $classroom = ClassManagement::where('classroom_id', $classroomId)->first();
                if (!$classroom) continue;

                // Determine role in this classroom
                $isTutor    = ($classroom->tutor_mobile_no === $mobileNo);
                $isMentor2  = ($classroom->mentor_mobile_no === $mobileNo);
                $batchLabel = $isTutor ? 'A' : 'B';

                // Students assigned to this mentor in mentoring_batches
                $assigned = MentoringBatch::where('classroom_id', $classroomId)
                    ->where('mentor_no', $mobileNo)
                    ->with('student')
                    ->get()
                    ->map(fn($b) => [
                        'reg_no'   => $b->reg_no,
                        'name'     => $b->student->name ?? 'Unknown',
                        'branch'   => $b->student->branch ?? '',
                        'status'   => $b->student->status ?? '',
                        'batch'    => $b->batch_label,
                        'photo'    => $b->student->photo_url ?? null,
                    ]);

                // Unassigned students in this classroom (no batch yet)
                $allStudents = Student::where('classroom_id', $classroomId)->pluck('reg_no')->toArray();
                $assignedIds = MentoringBatch::where('classroom_id', $classroomId)->pluck('reg_no')->toArray();
                $unassignedIds = array_diff($allStudents, $assignedIds);

                // Get partner mentor name
                $partnerName = null;
                if ($isTutor && $classroom->mentor_mobile_no) {
                    $p = StaffProfile::where('mobile_no', $classroom->mentor_mobile_no)->first();
                    $partnerName = $p?->name;
                } elseif ($isMentor2) {
                    $p = StaffProfile::where('mobile_no', $classroom->tutor_mobile_no)->first();
                    $partnerName = $p?->name . ' (Tutor)';
                }

                $result[] = [
                    'classroom_id'   => $classroomId,
                    'branch'         => $classroom->branch,
                    'batch_year'     => $classroom->batch_year,
                    'my_role'        => $isTutor ? 'Mentor-1 (Tutor)' : 'Mentor-2',
                    'my_batch'       => $batchLabel,
                    'partner_name'   => $partnerName,
                    'mentor1_mobile' => $classroom->tutor_mobile_no,
                    'mentor2_mobile' => $classroom->mentor_mobile_no,
                    'my_students'    => $assigned,
                    'unassigned_count' => count($unassignedIds),
                    'total_students'   => count($allStudents),
                ];
            }

            return response()->json(['status' => 'SUCCESS', 'batches' => $result]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  GET /api/mentoring/students/{classroom_id}
    //  Full roster with batch assignments
    // ─────────────────────────────────────────────
    public function getClassroomStudents(string $classroomId)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $classroom = ClassManagement::where('classroom_id', $classroomId)->first();
            if (!$classroom) return response()->json(['status' => 'ERROR', 'message' => 'Classroom not found.']);

            // Authorise: must be tutor or mentor2 of this class
            $allowed = in_array($mobileNo, [$classroom->tutor_mobile_no, $classroom->mentor_mobile_no])
                    || in_array(Session::get('userRole'), ['Super_Admin', 'Principal', 'Admin', 'HOD']);
            if (!$allowed) return response()->json(['status' => 'ERROR', 'message' => 'Not authorised for this classroom.']);

            $students = Student::where('classroom_id', $classroomId)->get();
            $batches  = MentoringBatch::where('classroom_id', $classroomId)->get()->keyBy('reg_no');

            $data = $students->map(function ($s) use ($batches) {
                $batch = $batches->get($s->reg_no);
                return [
                    'reg_no'       => $s->reg_no,
                    'name'         => $s->name,
                    'branch'       => $s->branch,
                    'status'       => $s->status,
                    'photo'        => $s->photo_url,
                    'batch_label'  => $batch?->batch_label ?? null,
                    'mentor_no'    => $batch?->mentor_no ?? null,
                ];
            });

            return response()->json([
                'status'    => 'SUCCESS',
                'classroom' => [
                    'id'       => $classroomId,
                    'branch'   => $classroom->branch,
                    'year'     => $classroom->batch_year,
                    'tutor'    => $classroom->tutor_mobile_no,
                    'mentor2'  => $classroom->mentor_mobile_no,
                ],
                'students'  => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  POST /api/mentoring/assign-batch
    //  Tutor assigns a student to Batch A or B
    //  Body: { classroom_id, reg_no, batch_label ('A'|'B') }
    // ─────────────────────────────────────────────
    public function assignBatch(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        $request->validate([
            'classroom_id' => 'required|string',
            'reg_no'       => 'required|string',
            'batch_label'  => 'required|in:A,B',
        ]);

        try {
            $classroom = ClassManagement::where('classroom_id', $request->classroom_id)->first();
            if (!$classroom) return response()->json(['status' => 'ERROR', 'message' => 'Classroom not found.']);

            // Only the Tutor (Mentor-1) can split batches
            if ($classroom->tutor_mobile_no !== $mobileNo) {
                return response()->json(['status' => 'ERROR', 'message' => 'Only the Class Tutor can assign mentoring batches.']);
            }

            $mentorNo = ($request->batch_label === 'A')
                ? $classroom->tutor_mobile_no
                : $classroom->mentor_mobile_no;

            if (!$mentorNo) {
                return response()->json(['status' => 'ERROR', 'message' => 'Mentor-2 has not been assigned to this class yet. Ask your HOD to assign one first.']);
            }

            // Upsert the batch assignment
            MentoringBatch::updateOrCreate(
                ['classroom_id' => $request->classroom_id, 'reg_no' => strtoupper($request->reg_no)],
                ['mentor_no'    => $mentorNo, 'batch_label' => $request->batch_label, 'assigned_by' => $mobileNo]
            );

            // Update student's mentor_mobile_no field too
            Student::where('reg_no', strtoupper($request->reg_no))->update(['mentor_mobile_no' => $mentorNo]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Student assigned to Batch ' . $request->batch_label . '.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  POST /api/mentoring/assign-mentor2
    //  HOD assigns a Mentor-2 to a classroom
    //  Body: { classroom_id, mentor_mobile_no }
    // ─────────────────────────────────────────────
    public function assignMentor2(Request $request)
    {
        $role     = Session::get('userRole');
        $branch   = Session::get('userBranch');
        $mobileNo = Session::get('userId');

        if (!in_array($role, ['HOD', 'Super_Admin', 'Principal', 'Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Only HOD or Admin can assign Mentor-2.']);
        }

        $request->validate([
            'classroom_id'     => 'required|string',
            'mentor_mobile_no' => 'required|string',
        ]);

        try {
            $classroom = ClassManagement::where('classroom_id', $request->classroom_id)->first();
            if (!$classroom) return response()->json(['status' => 'ERROR', 'message' => 'Classroom not found.']);

            // HOD can only assign for their branch
            if ($role === 'HOD' && $classroom->branch !== $branch) {
                return response()->json(['status' => 'ERROR', 'message' => 'You can only assign mentors for classrooms in your branch.']);
            }

            $mentor = StaffProfile::where('mobile_no', $request->mentor_mobile_no)->first();
            if (!$mentor) return response()->json(['status' => 'ERROR', 'message' => 'Staff member not found.']);

            $oldMentor = $classroom->mentor_mobile_no;
            $classroom->update(['mentor_mobile_no' => $request->mentor_mobile_no]);

            // Audit
            AuditLog::create([
                'performed_by'      => $mobileNo,
                'performed_by_name' => Session::get('userName'),
                'target_id'         => $request->classroom_id,
                'target_name'       => 'Classroom ' . $request->classroom_id,
                'action'            => 'Mentor-2 Assigned',
                'details'           => "Assigned {$mentor->name} ({$mentor->mobile_no}) as Mentor-2. Previous: " . ($oldMentor ?? 'None'),
                'ip_address'        => request()->ip(),
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => "{$mentor->name} assigned as Mentor-2 for {$request->classroom_id}."]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  GET /api/mentoring/diary/{reg_no}
    //  Diary entries for a student (staff view)
    // ─────────────────────────────────────────────
    public function getStudentDiary(string $regNo)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $entries = TutorDiary::where('reg_no', strtoupper($regNo))
                ->orderByDesc('date')
                ->get()
                ->map(function ($e) {
                    $loggedBy = $e->logged_by
                        ? (StaffProfile::where('mobile_no', $e->logged_by)->value('name') ?? $e->logged_by)
                        : 'System';
                    $approvedBy = $e->approved_by
                        ? (StaffProfile::where('mobile_no', $e->approved_by)->value('name') ?? $e->approved_by)
                        : null;
                    return [
                        'diary_id'        => $e->diary_id,
                        'date'            => $e->date,
                        'category'        => $e->category,
                        'discussion_notes'=> $e->discussion_notes,
                        'action_taken'    => $e->action_taken,
                        'remarks'         => $e->remarks,
                        'student_remarks' => $e->student_remarks,
                        'entry_source'    => $e->entry_source,
                        'approval_status' => $e->approval_status,
                        'logged_by_name'  => $loggedBy,
                        'approved_by_name'=> $approvedBy,
                        'created_at'      => $e->created_at,
                    ];
                });

            $student = Student::where('reg_no', strtoupper($regNo))->first();

            return response()->json([
                'status'  => 'SUCCESS',
                'student' => $student ? ['name' => $student->name, 'branch' => $student->branch] : null,
                'entries' => $entries,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  POST /api/mentoring/diary/add
    //  Staff adds a diary entry for a student
    //  Body: { reg_no, date, category, discussion_notes, action_taken, remarks }
    // ─────────────────────────────────────────────
    public function addDiaryEntry(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        $request->validate([
            'reg_no'            => 'required|string',
            'date'              => 'required|date',
            'category'          => 'required|string|max:100',
            'discussion_notes'  => 'required|string',
        ]);

        try {
            TutorDiary::create([
                'reg_no'            => strtoupper($request->reg_no),
                'date'              => $request->date,
                'category'          => $request->category,
                'discussion_notes'  => $request->discussion_notes,
                'action_taken'      => $request->action_taken,
                'remarks'           => $request->remarks,
                'logged_by'         => $mobileNo,
                'entry_source'      => 'Staff',
                'approval_status'   => 'Approved', // Staff entries auto-approved
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Diary entry saved.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  POST /api/mentoring/diary/approve
    //  Mentor approves or rejects a student self-entry
    //  Body: { diary_id, decision ('Approved'|'Rejected') }
    // ─────────────────────────────────────────────
    public function approveDiaryEntry(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        $request->validate([
            'diary_id' => 'required|string',
            'decision' => 'required|in:Approved,Rejected',
        ]);

        try {
            $entry = TutorDiary::where('diary_id', $request->diary_id)->first();
            if (!$entry) return response()->json(['status' => 'ERROR', 'message' => 'Entry not found.']);

            $entry->update([
                'approval_status' => $request->decision,
                'approved_by'     => $mobileNo,
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => "Entry {$request->decision}."]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  GET /api/mentoring/report/{classroom_id}
    //  Full mentoring report — both batches, both mentor names
    // ─────────────────────────────────────────────
    public function getMentoringReport(string $classroomId)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        try {
            $classroom = ClassManagement::where('classroom_id', $classroomId)->first();
            if (!$classroom) return response()->json(['status' => 'ERROR', 'message' => 'Classroom not found.']);

            $mentor1 = StaffProfile::where('mobile_no', $classroom->tutor_mobile_no)->first();
            $mentor2 = StaffProfile::where('mobile_no', $classroom->mentor_mobile_no)->first();

            $students = Student::where('classroom_id', $classroomId)->get();
            $batches  = MentoringBatch::where('classroom_id', $classroomId)->get()->keyBy('reg_no');

            $batchA = []; $batchB = []; $unassigned = [];

            foreach ($students as $s) {
                $batch = $batches->get($s->reg_no);
                $diaryCount = TutorDiary::where('reg_no', $s->reg_no)->count();
                $row = [
                    'reg_no'       => $s->reg_no,
                    'name'         => $s->name,
                    'status'       => $s->status,
                    'diary_count'  => $diaryCount,
                    'batch_label'  => $batch?->batch_label,
                ];
                if (!$batch) { $unassigned[] = $row; }
                elseif ($batch->batch_label === 'A') { $batchA[] = $row; }
                else { $batchB[] = $row; }
            }

            return response()->json([
                'status'    => 'SUCCESS',
                'classroom' => [
                    'id'         => $classroomId,
                    'branch'     => $classroom->branch,
                    'batch_year' => $classroom->batch_year,
                ],
                'mentor1'    => ['mobile' => $classroom->tutor_mobile_no,   'name' => $mentor1?->name ?? 'Not Assigned', 'designation' => $mentor1?->designation ?? ''],
                'mentor2'    => ['mobile' => $classroom->mentor_mobile_no,  'name' => $mentor2?->name ?? 'Not Assigned', 'designation' => $mentor2?->designation ?? ''],
                'batch_a'    => $batchA,
                'batch_b'    => $batchB,
                'unassigned' => $unassigned,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  POST /api/student/mentoring/self-entry
    //  Student adds a self-reflection entry (Pending approval)
    // ─────────────────────────────────────────────
    public function studentSelfEntry(Request $request)
    {
        $regNo = Session::get('userId');
        $role  = Session::get('userRole');
        if ($role !== 'Student' || !$regNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Only students can submit self-entries.'], 403);
        }

        $request->validate([
            'category'        => 'required|string|max:100',
            'student_remarks' => 'required|string',
        ]);

        try {
            TutorDiary::create([
                'reg_no'           => $regNo,
                'date'             => now()->toDateString(),
                'category'         => $request->category,
                'discussion_notes' => '(Student Self Entry)',
                'student_remarks'  => $request->student_remarks,
                'logged_by'        => null,
                'entry_source'     => 'Student',
                'approval_status'  => 'Pending',
            ]);

            return response()->json(['status' => 'SUCCESS', 'message' => 'Your entry has been submitted and is pending mentor approval.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  GET /api/student/mentoring/diary
    //  Student views their own diary
    // ─────────────────────────────────────────────
    public function studentViewDiary()
    {
        $regNo = Session::get('userId');
        $role  = Session::get('userRole');
        if ($role !== 'Student' || !$regNo) {
            return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated as student.'], 403);
        }

        try {
            // Get mentor info
            $student  = Student::where('reg_no', $regNo)->first();
            $mentorNo = $student?->mentor_mobile_no;
            $mentor   = $mentorNo ? StaffProfile::where('mobile_no', $mentorNo)->first() : null;

            // Get classroom mentors
            $classroom = $student?->classroom_id
                ? ClassManagement::where('classroom_id', $student->classroom_id)->first()
                : null;
            $mentor1 = $classroom?->tutor_mobile_no
                ? StaffProfile::where('mobile_no', $classroom->tutor_mobile_no)->first()
                : null;
            $mentor2 = $classroom?->mentor_mobile_no
                ? StaffProfile::where('mobile_no', $classroom->mentor_mobile_no)->first()
                : null;

            $entries = TutorDiary::where('reg_no', $regNo)
                ->orderByDesc('date')
                ->get()
                ->map(function ($e) {
                    return [
                        'diary_id'        => $e->diary_id,
                        'date'            => $e->date,
                        'category'        => $e->category,
                        'discussion_notes'=> $e->discussion_notes,
                        'student_remarks' => $e->student_remarks,
                        'entry_source'    => $e->entry_source,
                        'approval_status' => $e->approval_status,
                        'created_at'      => $e->created_at,
                    ];
                });

            return response()->json([
                'status'   => 'SUCCESS',
                'my_mentor'=> $mentor  ? ['name' => $mentor->name, 'designation' => $mentor->designation] : null,
                'mentor1'  => $mentor1 ? ['name' => $mentor1->name, 'designation' => $mentor1->designation] : null,
                'mentor2'  => $mentor2 ? ['name' => $mentor2->name, 'designation' => $mentor2->designation] : null,
                'entries'  => $entries,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()]);
        }
    }
}
