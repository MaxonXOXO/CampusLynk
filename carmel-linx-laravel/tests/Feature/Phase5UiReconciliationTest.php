<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\SubjectStaffAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase5UiReconciliationTest extends TestCase
{
    use RefreshDatabase;

    private StaffProfile $staff;
    private BatchSubject $theorySubject;
    private BatchSubject $labSubject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Reconciled Faculty',
            'email' => 'parity@example.com',
            'password' => bcrypt('secret123'),
            'designation' => 'Lecturer',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
            'remember_token' => 'test_token_abc_123'
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_PARITY_2026',
            'branch' => 'CT',
            'batch_year' => 2026,
            'tutor_mobile_no' => $this->staff->mobile_no,
            'mentor_mobile_no' => $this->staff->mobile_no,
            'current_semester' => 'IV',
        ]);

        $this->theorySubject = BatchSubject::create([
            'classroom_id' => 'CR_PARITY_2026',
            'subject_code' => '4001',
            'subject_name' => 'Advanced Operating Systems',
            'subject_type' => 'Theory',
            'syllabus_revision_code' => 'R-2026',
            'semester' => 4,
        ]);

        SubjectStaffAssignment::create([
            'batch_subject_id' => $this->theorySubject->id,
            'staff_mobile_no' => $this->staff->mobile_no,
            'role' => 'Primary',
        ]);

        $this->labSubject = BatchSubject::create([
            'classroom_id' => 'CR_PARITY_2026',
            'subject_code' => '4008',
            'subject_name' => 'Network Programming Lab',
            'subject_type' => 'Practical',
            'syllabus_revision_code' => 'R-2021',
            'semester' => 4,
        ]);

        SubjectStaffAssignment::create([
            'batch_subject_id' => $this->labSubject->id,
            'staff_mobile_no' => $this->staff->mobile_no,
            'role' => 'Primary',
        ]);

        Student::create([
            'reg_no' => 'REG2026001',
            'adm_no' => 'ADM2026001',
            'email' => 'john.doe@example.com',
            'name' => 'John Doe',
            'phone' => '9876540001',
            'branch' => 'CT',
            'admission_year' => 2026,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_PARITY_2026',
            'semester' => 'IV',
            'status' => 'APPROVED',
            'sbte_reg_no' => 'REG2026001',
            'roll_no' => 1,
            'password' => bcrypt('student123'),
        ]);

        \App\Models\SeriesExam::create([
            'batch_subject_id' => $this->theorySubject->id,
            'exam_name' => 'Series Test 1',
            'mode' => 'OFFLINE',
            'max_marks' => 20,
            'locked' => 0
        ]);
    }

    public function test_theory_classroom_ui_contains_reconciled_elements()
    {
        $response = $this->withSession([
            'userId' => $this->staff->mobile_no,
            'staffMobileNo' => $this->staff->mobile_no,
            'userName' => $this->staff->name,
            'userRole' => 'Lecturer',
            'role' => 'Lecturer'
        ])->get("/r26/classroom/theory/{$this->theorySubject->id}");

        $response->assertStatus(200);

        // 1. SBTE Grade Selector & Autosave Badge
        $response->assertSee('ese-grade-select');
        $response->assertSee('eseAutosaveBadge');
        $response->assertSee('SBTE_GRADE_SCALE', false);

        // 2. Attendance & Subject Log
        $response->assertSee('Attendance &amp; Subject Log', false);

        // 3. Lesson plan row deletion action
        $response->assertSee('deleteLessonPlanRow', false);

        // 4. QP Builder status vs action distinction
        $response->assertSee('Build QP', false);
    }

    public function test_practical_classroom_ui_contains_reconciled_elements()
    {
        $response = $this->withSession([
            'userId' => $this->staff->mobile_no,
            'staffMobileNo' => $this->staff->mobile_no,
            'userName' => $this->staff->name,
            'userRole' => 'Lecturer',
            'role' => 'Lecturer'
        ])->get("/classroom/practical/{$this->labSubject->id}");

        $response->assertStatus(200);

        // 1. Lab Batch Setup Modal and trigger
        $response->assertSee('openLabBatchSetupModal', false);
        $response->assertSee('labBatchSetupModal', false);

        // 2. In-place Log Dates Auto-Sync
        $response->assertSee('btnSyncLogDates', false);
        $response->assertSee('syncLessonPlanDatesFromLogs', false);

        // 3. Attendance & Log quick access
        $response->assertSee('Attendance &amp; Log', false);
    }
}
