<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\R21MajorProjectCourseFile;
use App\Models\StaffProfile;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class R21MajorProjectWorkspaceViewTest extends TestCase
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

        \Illuminate\Support\Facades\DB::table('syllabus_registry')->insert([
            'subject_code' => '6009',
            'revision_year' => 2021,
            'subject_name' => 'Major Project',
            'co_count' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_TEST_VI_01',
            'branch' => 'EL',
            'batch_year' => 2024,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'VI',
        ]);

        $batchSubject = BatchSubject::create([
            'classroom_id' => 'CR_TEST_VI_01',
            'subject_code' => '6009',
            'subject_name' => 'Major Project',
            'subject_type' => 'Project',
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
            'classroom_id' => 'CR_TEST_VI_01',
            'semester' => 'VI',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2101010001',
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
            'classroom_id' => 'CR_TEST_VI_01',
            'semester' => 'VI',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2101010002',
        ]);

        return [$staff, $batchSubject, $student1, $student2];
    }

    public function test_workspace_view_renders_through_workspace_layout(): void
    {
        [$staff, $subject, $st1, $st2] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/project/{$subject->id}");

        $response->assertStatus(200);
        $response->assertSee('Virtual Major Project Room');
        $response->assertSee('6009');
        $response->assertSee('R-2021 Regulation');
        $response->assertSee('Clause 11.2.5 &amp; 11.3.4', false);
        $response->assertSee('CIA: 75 Marks');
        $response->assertSee('ESE: 50 Marks');
        $response->assertSee('Total: 125 Marks');
        $response->assertSee('Alice Smith');
        $response->assertSee('Bob Jones');
    }

    public function test_workspace_view_includes_all_seven_structural_tabs(): void
    {
        [$staff, $subject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/project/{$subject->id}");

        $response->assertStatus(200);
        $response->assertSee('id="tab-register"', false);
        $response->assertSee('id="tab-cia"', false);
        $response->assertSee('id="tab-ese"', false);
        $response->assertSee('id="tab-reports"', false);
        $response->assertSee('id="tab-groups"', false);
        $response->assertSee('id="tab-attainment"', false);
        $response->assertSee('id="tab-rubrics"', false);
    }

    public function test_workspace_view_includes_all_six_modals(): void
    {
        [$staff, $subject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/project/{$subject->id}");

        $response->assertStatus(200);
        $response->assertSee('id="ciaEvalModal"', false);
        $response->assertSee('id="groupCiaModal"', false);
        $response->assertSee('id="evalModal"', false);
        $response->assertSee('id="groupEseModal"', false);
        $response->assertSee('id="examinersModal"', false);
        $response->assertSee('id="modalDeleteGroupConfirm"', false);
    }

    public function test_workspace_view_includes_client_side_scripts_and_bindings(): void
    {
        [$staff, $subject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/project/{$subject->id}");

        $response->assertStatus(200);
        $response->assertSee('window.subjectId = ' . $subject->id, false);
        $response->assertSee('window.studentResults', false);
        $response->assertSee('function switchTab', false);
        $response->assertSee('function computeLiveCiaTotals', false);
        $response->assertSee('function computeLiveTotals', false);
        $response->assertSee('function saveStudentEval', false);
    }

    public function test_show_endpoint_returns_json_when_requested(): void
    {
        [$staff, $subject] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->getJson("/r21/classroom/project/{$subject->id}");

        $response->assertStatus(200);
        $response->assertJson(['status' => 'SUCCESS']);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'batchSubject',
                'classroom',
                'courseFile',
                'studentResults',
                'guides',
                'projectGroups',
                'totalStudents',
                'evaluatedCount',
                'pendingCount',
                'passedCount',
                'avgCia',
                'avgEse',
                'examiners',
            ]
        ]);
    }
}
