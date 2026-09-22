<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\LessonPlan;
use App\Models\R26StudentLabBatch;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\SubjectStaffAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PracticalControllerExtensionsTest extends TestCase
{
    use RefreshDatabase;

    private function createPracticalContext(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Lab Instructor',
            'email' => 'instructor@example.com',
            'password' => 'secret',
            'designation' => 'Demonstrator',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_PRAC_TEST_I',
            'branch' => 'CT',
            'batch_year' => 2026,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'I',
        ]);

        $batchSubject = BatchSubject::create([
            'classroom_id' => 'CR_PRAC_TEST_I',
            'subject_code' => '2009',
            'subject_name' => 'Hardware Lab',
            'subject_type' => 'Practical',
            'semester' => 2,
        ]);

        SubjectStaffAssignment::create([
            'batch_subject_id' => $batchSubject->id,
            'staff_mobile_no' => $staff->mobile_no,
            'role' => 'Lab In-Charge',
        ]);

        $student1 = Student::create([
            'reg_no' => '2601010020',
            'adm_no' => 'ADM020',
            'name' => 'Peter Parker',
            'email' => 'peter@example.com',
            'password' => 'secret',
            'phone' => '9876500020',
            'branch' => 'CT',
            'admission_year' => 2026,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_PRAC_TEST_I',
            'semester' => 'I',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2601010020',
            'roll_no' => 1,
        ]);

        $student2 = Student::create([
            'reg_no' => '2601010021',
            'adm_no' => 'ADM021',
            'name' => 'Gwen Stacy',
            'email' => 'gwen@example.com',
            'password' => 'secret',
            'phone' => '9876500021',
            'branch' => 'CT',
            'admission_year' => 2026,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_PRAC_TEST_I',
            'semester' => 'I',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2601010021',
            'roll_no' => 2,
        ]);

        $lessonPlan = LessonPlan::create([
            'batch_subject_id' => $batchSubject->id,
            'day_no' => 1,
            'topic_content' => 'Logic Gate Testing',
            'co_id' => 'CO1',
            'status' => 'Pending',
        ]);

        return compact('staff', 'classroom', 'batchSubject', 'student1', 'student2', 'lessonPlan');
    }

    public function test_save_lab_batch_roster_idempotent(): void
    {
        $ctx = $this->createPracticalContext();

        // 1. Initial assignment
        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Demonstrator',
        ])->postJson("/api/classroom/practical/{$ctx['batchSubject']->id}/lab-batch/roster", [
            'roster' => [
                ['reg_no' => '2601010020', 'lab_batch' => 'Batch A'],
                ['reg_no' => '2601010021', 'lab_batch' => 'Batch B'],
            ]
        ]);

        $res->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'saved_count' => 2,
            ]);

        $this->assertEquals('Batch A', R26StudentLabBatch::where('reg_no', '2601010020')->where('batch_subject_id', $ctx['batchSubject']->id)->value('lab_batch'));
        $this->assertEquals('Batch B', R26StudentLabBatch::where('reg_no', '2601010021')->where('batch_subject_id', $ctx['batchSubject']->id)->value('lab_batch'));

        // 2. Idempotent re-run with update and removal
        $res2 = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Demonstrator',
        ])->postJson("/api/classroom/practical/{$ctx['batchSubject']->id}/lab-batch/roster", [
            'roster' => [
                ['reg_no' => '2601010020', 'lab_batch' => 'Batch B'], // Changed to Batch B
                ['reg_no' => '2601010021', 'lab_batch' => ''],        // Cleared
            ]
        ]);

        $res2->assertStatus(200);
        $this->assertEquals('Batch B', R26StudentLabBatch::where('reg_no', '2601010020')->where('batch_subject_id', $ctx['batchSubject']->id)->value('lab_batch'));
        $this->assertNull(R26StudentLabBatch::where('reg_no', '2601010021')->where('batch_subject_id', $ctx['batchSubject']->id)->first());
    }

    public function test_delete_practical_lesson_plan_row_scoped(): void
    {
        $ctx = $this->createPracticalContext();

        // 1. Cannot delete row under wrong subject
        $resWrong = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Demonstrator',
        ])->deleteJson("/api/classroom/practical/99999/lesson-plan/{$ctx['lessonPlan']->id}");

        $resWrong->assertStatus(404);

        // 2. Successful scoped deletion
        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Demonstrator',
        ])->deleteJson("/api/classroom/practical/{$ctx['batchSubject']->id}/lesson-plan/{$ctx['lessonPlan']->id}");

        $res->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
            ]);

        $this->assertDatabaseMissing('lesson_plans', ['id' => $ctx['lessonPlan']->id]);
    }

    public function test_print_practical_attendance_register(): void
    {
        $ctx = $this->createPracticalContext();

        // Assign a lab batch
        R26StudentLabBatch::create([
            'batch_subject_id' => $ctx['batchSubject']->id,
            'reg_no' => '2601010020',
            'lab_batch' => 'Batch A',
        ]);

        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Demonstrator',
        ])->get("/classroom/practical/{$ctx['batchSubject']->id}/print-attendance-register");

        $res->assertStatus(200);
        $res->assertSee('Hardware Lab', false);
        $res->assertSee('2009', false);
        $res->assertSee('Practical Attendance Register', false);
        $res->assertSee('Peter Parker', false);
        $res->assertSee('Batch A', false);
        $res->assertSee('Gwen Stacy', false);
    }
}
