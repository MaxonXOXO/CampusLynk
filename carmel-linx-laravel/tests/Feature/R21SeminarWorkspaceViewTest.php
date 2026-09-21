<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\CourseFile;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\StudentSeminarRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class R21SeminarWorkspaceViewTest extends TestCase
{
    use RefreshDatabase;

    private function createSubjectAndClassroom(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Guide One',
            'email' => 'guide@example.com',
            'password' => 'secret',
            'designation' => 'Lecturer',
            'branch' => 'EL',
            'account_status' => 'APPROVED',
        ]);

        DB::table('syllabus_registry')->insert([
            'subject_code' => '6008',
            'revision_year' => 2021,
            'subject_name' => 'Seminar',
            'co_count' => 3,
            'cia_marks' => 75,
            'ese_marks' => 0,
            'credits' => 1.5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_TEST_VI_SEM',
            'branch' => 'EL',
            'batch_year' => 2024,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'VI',
        ]);

        $batchSubject = BatchSubject::create([
            'classroom_id' => 'CR_TEST_VI_SEM',
            'subject_code' => '6008',
            'subject_name' => 'Seminar',
            'subject_type' => 'Seminar',
            'semester' => 6,
        ]);

        $student1 = Student::create([
            'reg_no' => '2101010001',
            'adm_no' => 'ADM001',
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'password' => 'secret',
            'phone' => '9876500001',
            'branch' => 'EL',
            'admission_year' => 2021,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_TEST_VI_SEM',
            'semester' => 'VI',
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
            'branch' => 'EL',
            'admission_year' => 2021,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_TEST_VI_SEM',
            'semester' => 'VI',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2101010002',
            'roll_no' => 2,
        ]);

        StudentSeminarRegistration::create([
            'batch_subject_id' => $batchSubject->id,
            'reg_no' => '2101010001',
            'topic' => 'AI in Smart Grids',
            'presentation_date' => '2026-10-15',
            'guide_mobile_no' => $staff->mobile_no,
        ]);

        return [$staff, $batchSubject, [$student1, $student2]];
    }

    /**
     * Test that workspace view renders through workspace-layout and shows metadata.
     */
    public function test_workspace_view_renders_through_workspace_layout_and_shows_metadata(): void
    {
        [$staff, $subject, $students] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/seminar/{$subject->id}");

        $response->assertStatus(200);
        $response->assertSee('Virtual Seminar Room');
        $response->assertSee('6008');
        $response->assertSee('R-2021 Regulation');
        $response->assertSee('Clause 11.2.6 Seminar Assessment');
        $response->assertSee('CIA: 75 Marks (100% CIE)');
        $response->assertSee('67.5M Academic + 7.5M Attendance');
        $response->assertSee('Alice Smith');
        $response->assertSee('Bob Jones');
        $response->assertSee('AI in Smart Grids');
    }

    /**
     * Test that workspace view contains all five structural tabs.
     */
    public function test_workspace_view_includes_all_five_structural_tabs(): void
    {
        [$staff, $subject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/seminar/{$subject->id}");

        $response->assertStatus(200);
        $response->assertSee('id="tab-evaluation"', false);
        $response->assertSee('id="tab-schedule"', false);
        $response->assertSee('id="tab-consolidated"', false);
        $response->assertSee('id="tab-attainment"', false);
        $response->assertSee('id="tab-rubrics"', false);
    }

    /**
     * Test that workspace view includes all modals using x-ui.modal.
     */
    public function test_workspace_view_includes_all_modals_using_ui_modal(): void
    {
        [$staff, $subject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/seminar/{$subject->id}");

        $response->assertStatus(200);
        $response->assertSee('id="evaluationModal"', false);
        $response->assertSee('id="scheduleModal"', false);
        $response->assertSee('id="breakdownModal"', false);
        $response->assertSee('id="syllabusModal"', false);
    }

    /**
     * Test that workspace view includes client-side scripts, live scoring, and bindings.
     */
    public function test_workspace_view_includes_client_side_scripts_and_bindings(): void
    {
        [$staff, $subject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/seminar/{$subject->id}");

        $response->assertStatus(200);
        $response->assertSee('const studentData =', false);
        $response->assertSee('const subjectId = ' . $subject->id, false);
        $response->assertSee('function switchTab', false);
        $response->assertSee('function filterBatch', false);
        $response->assertSee('function filterStudents', false);
        $response->assertSee('function openEvaluationModal', false);
        $response->assertSee('function calculateLiveScore', false);
        $response->assertSee('function submitEvaluation', false);
        $response->assertSee('function openScheduleModal', false);
        $response->assertSee('function submitSchedule', false);
        $response->assertSee('function openBreakdownModal', false);
        $response->assertSee('function openSyllabusModal', false);
        $response->assertSee('function submitSyllabus', false);
        $response->assertSee('function loadAttainmentData', false);
    }

    /**
     * Test that workspace references M2.4 Seminar endpoint contracts.
     */
    public function test_workspace_references_m24_endpoints(): void
    {
        [$staff, $subject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/seminar/{$subject->id}");

        $response->assertStatus(200);
        $response->assertSee('/r21/classroom/seminar/${subjectId}/evaluate', false);
        $response->assertSee('/r21/classroom/seminar/${subjectId}/schedule', false);
        $response->assertSee('/r21/classroom/seminar/${subjectId}/syllabus', false);
        $response->assertSee('/r21/classroom/seminar/${subjectId}/attainment-summary', false);
        $response->assertSee("/r21/classroom/seminar/{$subject->id}/print?type=consolidated", false);
        $response->assertSee("/r21/classroom/seminar/{$subject->id}/print?type=schedule", false);
        $response->assertSee("/r21/classroom/seminar/{$subject->id}/print?type=cia_submission", false);
    }

    /**
     * Test print report renders consolidated mode with report-layout.
     */
    public function test_print_report_renders_consolidated_mode_with_report_layout(): void
    {
        [$staff, $subject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/seminar/{$subject->id}/print?type=consolidated");

        $response->assertStatus(200);
        $response->assertSee('Carmel Polytechnic College, Alappuzha');
        $response->assertSee('Consolidated 6-Rubric Seminar Evaluation Register (Clause 11.2.6)');
        $response->assertSee('State Board of Technical Education (SBTE) Kerala');
        $response->assertSee('Faculty Guide / Assessor');
        $response->assertSee('Head of Department');
        $response->assertSee('Alice Smith');
        $response->assertSee('Bob Jones');
        $response->assertSee('AI in Smart Grids');
    }

    /**
     * Test print report renders official SBTE CIA submission statement.
     */
    public function test_print_report_renders_cia_submission_mode_with_report_layout(): void
    {
        [$staff, $subject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/seminar/{$subject->id}/print?type=cia_submission");

        $response->assertStatus(200);
        $response->assertSee('Official SBTE Final CIA Mark Entry Statement (75M)');
        $response->assertSee('Grade Distribution Summary (Clause 11.2.6)');
        $response->assertSee('Certification:');
        $response->assertSee('Max 67.5');
        $response->assertSee('Max 7.5');
        $response->assertSee('Max 75');
    }

    /**
     * Test print report renders seminar presentation schedule mode.
     */
    public function test_print_report_renders_schedule_mode_with_report_layout(): void
    {
        [$staff, $subject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/seminar/{$subject->id}/print?type=schedule");

        $response->assertStatus(200);
        $response->assertSee('Seminar Presentation Schedule & Topic Log');
        $response->assertSee('Approved Seminar Topic');
        $response->assertSee('Presentation Date');
        $response->assertSee('Faculty Guide');
        $response->assertSee('Total Candidates:');
    }
}
