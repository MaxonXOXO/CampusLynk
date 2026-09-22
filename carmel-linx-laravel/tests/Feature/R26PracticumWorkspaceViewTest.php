<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\SubjectStaffAssignment;
use App\Models\R26PracticumCourseFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class R26PracticumWorkspaceViewTest extends TestCase
{
    use RefreshDatabase;

    private function createPracticumContext(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Practicum Guide',
            'email' => 'guide@example.com',
            'password' => 'secret',
            'designation' => 'Lecturer',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_TEST_PRACTICUM_I',
            'branch' => 'CT',
            'batch_year' => 2026,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'I',
        ]);

        $batchSubject = BatchSubject::create([
            'classroom_id' => 'CR_TEST_PRACTICUM_I',
            'subject_code' => '2008',
            'subject_name' => 'Basic Science Practicum',
            'subject_type' => 'Practicum',
            'semester' => 1,
            'syllabus_revision_code' => 'R26',
        ]);

        SubjectStaffAssignment::create([
            'batch_subject_id' => $batchSubject->id,
            'staff_mobile_no' => $staff->mobile_no,
            'role' => 'Subject Teacher',
        ]);

        $student = Student::create([
            'reg_no' => '2601010001',
            'adm_no' => 'ADM2601',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret',
            'phone' => '9876500001',
            'branch' => 'CT',
            'admission_year' => 2026,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_TEST_PRACTICUM_I',
            'semester' => 'I',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2601010001',
            'roll_no' => 1,
        ]);

        $courseFile = R26PracticumCourseFile::create([
            'batch_subject_id' => $batchSubject->id,
            'subject_type' => 'Practicum',
            'co_count' => 4,
            'theory_hours' => 45,
            'practical_hours' => 45,
            'cia_marks' => 60,
            'ese_marks' => 40,
        ]);

        return compact('staff', 'classroom', 'batchSubject', 'student', 'courseFile');
    }

    public function test_workspace_renders_successfully_for_authorized_staff(): void
    {
        $context = $this->createPracticumContext();

        $response = $this->withSession([
            'userId' => $context['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userName' => $context['staff']->name,
            'userBranch' => 'CT',
        ])->get("/r26/classroom/practicum/{$context['batchSubject']->id}");

        $response->assertStatus(200);

        // Verify top-level layout & title
        $response->assertSee('Practicum Virtual Classroom', false);
        $response->assertSee('Basic Science Practicum', false);
        $response->assertSee('2008', false);

        // Verify Dual Mode Switcher
        $response->assertSee('mode-btn-theory', false);
        $response->assertSee('mode-btn-lab', false);
        $response->assertSee('Virtual Theory Classroom', false);
        $response->assertSee('Virtual Lab Workspace', false);

        // Verify Theory Subtabs
        $response->assertSee('theory-tab-overview', false);
        $response->assertSee('theory-tab-planner', false);
        $response->assertSee('theory-tab-sl', false);
        $response->assertSee('theory-tab-series', false);
        $response->assertSee('theory-tab-ese', false);
        $response->assertSee('theory-tab-surveys', false);
        $response->assertSee('theory-tab-attendance', false);

        // Verify Lab Subtabs
        $response->assertSee('lab-tab-roster', false);
        $response->assertSee('lab-tab-planner', false);
        $response->assertSee('lab-tab-eval', false);
        $response->assertSee('lab-tab-series', false);
        $response->assertSee('lab-tab-ese', false);

        // Verify Modals
        $response->assertSee('modal-midsem-survey-init-practicum', false);
        $response->assertSee('modal-exit-survey-init-practicum', false);
        $response->assertSee('syllabus-modal', false);
        $response->assertSee('experiment-eval-modal', false);
        $response->assertSee('sl-config-modal', false);
        $response->assertSee('sl-marks-modal', false);
    }
}
