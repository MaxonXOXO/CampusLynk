<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\R21MajorProjectCourseFile;
use App\Models\R21MajorProjectEvaluation;
use App\Models\StaffProfile;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class R21MajorProjectPrintReportTest extends TestCase
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
            'classroom_id' => 'CR_TEST_VI_01',
            'semester' => 'VI',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2101010002',
            'roll_no' => 2,
        ]);

        return [$batchSubject, $classroom, $staff, [$student1, $student2]];
    }

    /**
     * Test that unauthenticated print requests are redirected.
     */
    public function test_print_report_unauthenticated_is_redirected(): void
    {
        $response = $this->get('/r21/classroom/project/1/report/print');
        $response->assertRedirect('/');
    }

    /**
     * Test that the print report uses the x-layouts.report-layout Master Shell
     * with shared institutional header and signatures.
     */
    public function test_print_report_uses_report_layout_and_shared_components(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/project/{$subject->id}/report/print?type=consolidated");

        $response->assertStatus(200);

        // Report Layout & Print Controls
        $response->assertSee('Print / Save PDF');
        $response->assertSee('← Back');
        $response->assertSee('A4 landscape');

        // Shared Institutional Header
        $response->assertSee('Carmel Polytechnic College, Alappuzha');
        $response->assertSee('Department of Electronics Engineering');
        $response->assertSee('Revision 2021 Regulation');
        $response->assertSee('6009 — Major Project');

        // Shared Signatures Block
        $response->assertSee('Faculty Guide');
        $response->assertSee('Internal Examiner');
        $response->assertSee('External Examiner');
        $response->assertSee('Head of Department');
    }

    /**
     * Test Report Mode 1: Group-Wise Breakdown (group_breakdown / group_dossier).
     */
    public function test_print_report_group_breakdown_mode(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        R21MajorProjectCourseFile::create([
            'batch_subject_id' => $subject->id,
            'classroom_id' => $classroom->classroom_id,
            'project_groups' => [
                [
                    'id' => 1,
                    'name' => 'Group 1 - IoT',
                    'title' => 'Smart Irrigation System',
                    'guide_name' => 'Prof. Guide One',
                    'members' => ['2101010001']
                ]
            ]
        ]);

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/project/{$subject->id}/report/print?type=group_breakdown");

        $response->assertStatus(200);
        $response->assertSee('Major Project Group-Wise Assessment Record — Group 1 - IoT');
        $response->assertSee('Smart Irrigation System');
        $response->assertSee('Prof. Guide One');
        $response->assertSee('Alice Smith');
        $response->assertSee('page-break');
    }

    /**
     * Test Report Mode 2: Continuous Internal Assessment (cia_register) - Clause 11.2.5.
     */
    public function test_print_report_cia_register_mode(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        R21MajorProjectEvaluation::create([
            'batch_subject_id' => $subject->id,
            'classroom_id' => $classroom->classroom_id,
            'reg_no' => '2101010001',
            'formative_diary_marks' => 25.0,
            'summative_dept_marks' => 26.0,
            'attendance_percentage' => 92.0,
            'attendance_marks' => 14.0,
            'total_cia_75' => 65.0,
        ]);

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/project/{$subject->id}/report/print?type=cia_register");

        $response->assertStatus(200);
        $response->assertSee('Continuous Internal Assessment (CIA) Register (Clause 11.2.5 — Max 75 Marks)');
        $response->assertSee('Statutory Scheme:');
        $response->assertSee('Diary (30M)');
        $response->assertSee('Dept (30M)');
        $response->assertSee('Attd (15M)');
        $response->assertSee('Total CIA');
        $response->assertSee('Alice Smith');
        $response->assertSee('65.0');
        $response->assertSee('Class Avg CIA');
    }

    /**
     * Test Report Mode 3: Official SBTE Final Mark Entry Statement (sbte_submission).
     */
    public function test_print_report_sbte_submission_mode(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/project/{$subject->id}/report/print?type=sbte_submission");

        $response->assertStatus(200);
        $response->assertSee('Diploma Examination (Revision 2021) — Major Project Final Mark Statement');
        $response->assertSee('Assessment Ratio:');
        $response->assertSee('Ratio 3:2');
        $response->assertSee('CIA (75M)');
        $response->assertSee('ESE (50M)');
        $response->assertSee('Total (125M)');
        $response->assertSee('STATUTORY CERTIFICATION &amp; DECLARATION:', false);
        $response->assertSee('Pass Rate');
    }

    /**
     * Test Report Mode 4: Clause 11.3.4 ESE 8-Rubric Score Sheet (ese_rubrics).
     */
    public function test_print_report_ese_rubrics_mode(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        R21MajorProjectEvaluation::create([
            'batch_subject_id' => $subject->id,
            'classroom_id' => $classroom->classroom_id,
            'reg_no' => '2101010001',
            'ese_prototype' => 8.5,
            'ese_modern_tools' => 4.0,
            'ese_presentation' => 6.0,
            'ese_innovativeness' => 2.0,
            'ese_viva' => 6.0,
            'ese_individual_contrib' => 6.0,
            'ese_group_activity' => 4.0,
            'ese_project_report' => 4.0,
            'total_ese_50' => 40.5,
        ]);

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/project/{$subject->id}/report/print?type=ese_rubrics");

        $response->assertStatus(200);
        $response->assertSee('Major Project End Semester Evaluation (ESE) 8-Rubric Register (Clause 11.3.4 — Max 50 Marks)');
        $response->assertSee('Clause 11.3.4 Statutory Evaluation Rubrics');
        $response->assertSee('Model');
        $response->assertSee('Tools');
        $response->assertSee('Pres');
        $response->assertSee('Inno');
        $response->assertSee('Viva');
        $response->assertSee('Indiv');
        $response->assertSee('Grp');
        $response->assertSee('Rep');
        $response->assertSee('Total ESE');
        $response->assertSee('Alice Smith');
        $response->assertSee('40.5');
    }

    /**
     * Test Report Mode 5: Consolidated Broad Register (consolidated).
     */
    public function test_print_report_consolidated_mode(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->get("/r21/classroom/project/{$subject->id}/report/print?type=consolidated");

        $response->assertStatus(200);
        $response->assertSee('Consolidated Major Project Broad Register (Clauses 11.2.5 &amp; 11.3.4 — Total 125 Marks)', false);
        $response->assertSee('Continuous Internal Assessment (75M) + End Semester Examination (50M) = 125 Marks Total');
        $response->assertSee('Alice Smith');
        $response->assertSee('Bob Jones');
        $response->assertSee('CIA (75M)');
        $response->assertSee('ESE (50M)');
        $response->assertSee('Total (125M)');
    }

    /**
     * Test JSON response negotiation for API clients.
     */
    public function test_print_report_json_negotiation(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->getJson("/r21/classroom/project/{$subject->id}/report/print?type=consolidated");

        $response->assertStatus(200);
        $response->assertJson(['status' => 'SUCCESS']);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'subject',
                'classroom',
                'department',
                'groupedProjects',
                'students',
                'attainmentSummary',
                'totalStudents',
                'completedCount',
                'passedCount',
                'failedCount',
                'gradeStats',
                'examiners',
            ]
        ]);
    }
}
