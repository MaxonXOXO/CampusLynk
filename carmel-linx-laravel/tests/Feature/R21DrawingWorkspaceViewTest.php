<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\R21DrawingCourseFile;
use App\Models\R21DrawingSheetEvaluation;
use App\Models\R21DrawingSeriesTest;
use App\Models\R21DrawingAttendanceEvaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class R21DrawingWorkspaceViewTest extends TestCase
{
    use RefreshDatabase;

    private function createSubjectAndClassroom(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Drawing Instructor',
            'email' => 'drawing@example.com',
            'password' => 'secret',
            'designation' => 'Lecturer',
            'branch' => 'ME',
            'account_status' => 'APPROVED',
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_TEST_ME_SEM1',
            'branch' => 'ME',
            'batch_year' => 2024,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'I',
        ]);

        $batchSubject = BatchSubject::create([
            'classroom_id' => 'CR_TEST_ME_SEM1',
            'subject_code' => '1008',
            'subject_name' => 'Engineering Drawing',
            'subject_type' => 'Drawing',
            'semester' => 1,
        ]);

        $student1 = Student::create([
            'reg_no' => '2101010001',
            'adm_no' => 'ADM001',
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'password' => 'secret',
            'phone' => '9876500001',
            'branch' => 'ME',
            'admission_year' => 2021,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_TEST_ME_SEM1',
            'semester' => 'I',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2101010001',
            'roll_no' => 1,
        ]);

        $student2 = Student::create([
            'reg_no' => '2101010002',
            'adm_no' => 'ADM002',
            'name' => 'Bob Jones',
            'email' => 'bob@example.com',
            'password' => 'secret',
            'phone' => '9876500002',
            'branch' => 'ME',
            'admission_year' => 2021,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_TEST_ME_SEM1',
            'semester' => 'I',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2101010002',
            'roll_no' => 2,
        ]);

        return [$staff, $classroom, $batchSubject, $student1, $student2];
    }

    public function test_workspace_view_renders_successfully_for_authenticated_faculty(): void
    {
        [$staff, $classroom, $batchSubject, $student1, $student2] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no])
            ->get("/r21/classroom/drawing/{$batchSubject->id}");

        $response->assertStatus(200);
        $response->assertSee('Virtual Drawing Hall (R-2021)');
        $response->assertSee('Formative Assessment (Sheets');
        $response->assertSee('Summative Tests (Avg 2 Tests');
        $response->assertSee('Attendance');
        $response->assertSee('Consolidated CIA');
        $response->assertSee('Lesson Plan');
        $response->assertSee('Syllabus, COs');
        $response->assertSee('Alice Smith');
        $response->assertSee('Bob Jones');
        $response->assertSee('Sheet 1');
        $response->assertSee('Sheet 8');
        $response->assertSee('Series Test 1');
        $response->assertSee('Series Test 2');
    }

    public function test_workspace_redirects_unauthenticated_user(): void
    {
        [$staff, $classroom, $batchSubject] = $this->createSubjectAndClassroom();

        $response = $this->get("/r21/classroom/drawing/{$batchSubject->id}");
        $response->assertRedirect('/');
    }

    public function test_print_formative_sheet_register_renders_view(): void
    {
        [$staff, $classroom, $batchSubject, $student1, $student2] = $this->createSubjectAndClassroom();

        // Seed a sheet evaluation
        R21DrawingSheetEvaluation::create([
            'batch_subject_id' => $batchSubject->id,
            'sheet_no' => 'Sheet 1',
            'reg_no' => $student1->reg_no,
            'timely_completion' => 45.0,
            'appearance_organization' => 45.0,
            'total_score_100' => 90.0,
            'is_absent' => false,
        ]);

        $response = $this->withSession(['userId' => $staff->mobile_no])
            ->get("/r21/classroom/drawing/{$batchSubject->id}/print/sheets");

        $response->assertStatus(200);
        $response->assertSee('Drawing Formative Sheet Register');
        $response->assertSee('Clause 11.2.3.b');
        $response->assertSee('Alice Smith');
        $response->assertSee('90');
    }

    public function test_print_summative_test_register_renders_view(): void
    {
        [$staff, $classroom, $batchSubject, $student1, $student2] = $this->createSubjectAndClassroom();

        // Seed series test evaluation
        R21DrawingSeriesTest::create([
            'batch_subject_id' => $batchSubject->id,
            'test_no' => 'Test 1',
            'reg_no' => $student1->reg_no,
            'procedure_drawing' => 35.0,
            'final_drawing' => 25.0,
            'dimensioning' => 18.0,
            'neatness' => 8.0,
            'total_score_100' => 86.0,
            'is_absent' => false,
        ]);

        $response = $this->withSession(['userId' => $staff->mobile_no])
            ->get("/r21/classroom/drawing/{$batchSubject->id}/print/tests");

        $response->assertStatus(200);
        $response->assertSee('Drawing Summative Series Test Register');
        $response->assertSee('Series Test 1');
        $response->assertSee('Series Test 2');
        $response->assertSee('Alice Smith');
        $response->assertSee('86');
    }

    public function test_print_consolidated_cia_marksheet_renders_view(): void
    {
        [$staff, $classroom, $batchSubject, $student1, $student2] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no])
            ->get("/r21/classroom/drawing/{$batchSubject->id}/print/cia");

        $response->assertStatus(200);
        $response->assertSee('Consolidated Continuous Internal Assessment (CIA) Marksheet');
        $response->assertSee('Formative Assessment (40%)');
        $response->assertSee('Summative Tests (40%)');
        $response->assertSee('Attendance');
        $response->assertSee('Alice Smith');
    }

    public function test_print_lesson_plan_renders_view(): void
    {
        [$staff, $classroom, $batchSubject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no])
            ->get("/r21/classroom/drawing/{$batchSubject->id}/print/lesson-plan");

        $response->assertStatus(200);
        $response->assertSee('Drawing Course Lesson Plan & Syllabus Compliance');
        $response->assertSee('Engineering Drawing');
    }
}
