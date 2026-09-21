<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\R21MajorProjectCourseFile;
use App\Models\R21MajorProjectEvaluation;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Services\AttainmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class R21MajorProjectControllerTest extends TestCase
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
     * Test that unauthenticated requests to major project endpoints are rejected.
     */
    public function test_unauthenticated_requests_are_rejected(): void
    {
        $response = $this->get('/r21/classroom/project/1');
        $response->assertRedirect('/');

        $jsonResponse = $this->postJson('/r21/classroom/project/1/save-groups', ['groups' => []]);
        $jsonResponse->assertStatus(401);

        $jsonEval = $this->postJson('/r21/classroom/project/1/save-evaluation', ['reg_no' => '2101010001']);
        $jsonEval->assertStatus(401);

        $jsonEse = $this->getJson('/r21/classroom/project/1/ese-marks');
        $jsonEse->assertStatus(401);

        $jsonAttainment = $this->getJson('/r21/classroom/project/1/attainment-summary');
        $jsonAttainment->assertStatus(401);
    }

    /**
     * Test AttainmentService core conversions and attainment level calculations.
     */
    public function test_attainment_service_methods(): void
    {
        $this->assertEquals('S', AttainmentService::percentageToGrade(95.0));
        $this->assertEquals('A', AttainmentService::percentageToGrade(85.0));
        $this->assertEquals('B', AttainmentService::percentageToGrade(75.0));
        $this->assertEquals('C', AttainmentService::percentageToGrade(65.0));
        $this->assertEquals('D', AttainmentService::percentageToGrade(55.0));
        $this->assertEquals('E', AttainmentService::percentageToGrade(45.0));
        $this->assertEquals('F', AttainmentService::percentageToGrade(35.0));

        $this->assertTrue(AttainmentService::isGradeMet('S', 'D'));
        $this->assertTrue(AttainmentService::isGradeMet('D', 'D'));
        $this->assertFalse(AttainmentService::isGradeMet('E', 'D'));
        $this->assertFalse(AttainmentService::isGradeMet('F', 'D'));

        $this->assertEquals(3, AttainmentService::calculateBatchLevel(70.0));
        $this->assertEquals(2, AttainmentService::calculateBatchLevel(60.0));
        $this->assertEquals(1, AttainmentService::calculateBatchLevel(50.0));
        $this->assertEquals(0, AttainmentService::calculateBatchLevel(40.0));

        $this->assertEquals('Level 3 (High)', AttainmentService::getLevelLabel(3));
        $this->assertEquals('Level 2 (Moderate)', AttainmentService::getLevelLabel(2));
        $this->assertEquals('Level 1 (Low)', AttainmentService::getLevelLabel(1));
        $this->assertEquals('Level 0 (Nil)', AttainmentService::getLevelLabel(0));

        // Direct Attainment: 30% CIE + 70% ESE
        $direct = AttainmentService::calculateDirectAttainment(3.0, 2.0);
        $this->assertEquals(2.30, $direct);

        // Overall Attainment: 80% Direct + 20% Indirect
        $overall = AttainmentService::calculateOverallAttainment(2.30, 2.50);
        $this->assertEquals(2.34, $overall);

        // Grade conversions
        $this->assertEquals('A', AttainmentService::convertMarksToGrade(42.5, 50.0));
        $this->assertGreaterThan(0, AttainmentService::convertGradeToMarks('A', 50.0));

        // Default ESE config
        $config = AttainmentService::getDefaultEseConfig('Project', 'REV2021');
        $this->assertEquals(50, $config['max_marks']);
        $this->assertEquals(75, $config['cia_marks']);
        $this->assertEquals('D', $config['ese_threshold_grade']);
    }

    /**
     * Test show endpoint returns dashboard data with course file initialization.
     */
    public function test_show_endpoint_initializes_course_file_and_returns_data(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->getJson("/r21/classroom/project/{$subject->id}");

        $response->assertStatus(200);
        $response->assertJson(['status' => 'SUCCESS']);

        $this->assertDatabaseHas('r21_major_project_course_files', [
            'batch_subject_id' => $subject->id,
            'cia_marks' => 75,
            'ese_marks' => 50,
        ]);
    }

    /**
     * Test save groups endpoint assigns project groups and members.
     */
    public function test_save_groups_endpoint(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        $groups = [
            [
                'id' => 'GRP-1',
                'name' => 'Group 1',
                'title' => 'AI Automated Drone',
                'guide_name' => 'Prof. Guide One',
                'members' => ['2101010001', '2101010002']
            ]
        ];

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->postJson("/r21/classroom/project/{$subject->id}/save-groups", ['groups' => $groups]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'SUCCESS']);

        $this->assertDatabaseHas('r21_major_project_evaluations', [
            'batch_subject_id' => $subject->id,
            'reg_no' => '2101010001',
            'group_id' => 'GRP-1',
            'project_title' => 'AI Automated Drone',
        ]);
    }

    /**
     * Test single student evaluation save and academic marks sync.
     */
    public function test_save_evaluation_endpoint(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        $evalPayload = [
            'reg_no' => '2101010001',
            'formative_diary_marks' => 25.0,
            'summative_dept_marks' => 26.0,
            'attendance_marks' => 14.0,
            'ese_prototype' => 8.0,
            'ese_modern_tools' => 4.0,
            'ese_presentation' => 6.0,
            'ese_innovativeness' => 2.0,
            'ese_viva' => 6.0,
            'ese_individual_contrib' => 6.0,
            'ese_group_activity' => 4.0,
            'ese_project_report' => 4.0,
            'remarks' => 'Good engineering project',
        ];

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->postJson("/r21/classroom/project/{$subject->id}/save-evaluation", $evalPayload);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'data' => [
                'reg_no' => '2101010001',
                'total_cia_75' => 65.0,
                'total_ese_50' => 40.0,
                'grand_total_125' => 105.0,
                'passed' => true,
            ]
        ]);

        $this->assertDatabaseHas('r21_major_project_evaluations', [
            'batch_subject_id' => $subject->id,
            'reg_no' => '2101010001',
            'total_cia_75' => 65.0,
            'total_ese_50' => 40.0,
            'grand_total_125' => 105.0,
            'passed' => 1,
        ]);

        $this->assertDatabaseHas('academic_marks', [
            'reg_no' => '2101010001',
            'subject_code' => $subject->subject_code,
            'category' => 'Major Project CIA',
            'marks_obtained' => 65.0,
        ]);

        $this->assertDatabaseHas('academic_marks', [
            'reg_no' => '2101010001',
            'subject_code' => $subject->subject_code,
            'category' => 'ESE',
            'marks_obtained' => 40.0,
        ]);
    }

    /**
     * Test out of bounds validation for evaluation marks.
     */
    public function test_save_evaluation_validation_bounds(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        $invalidPayload = [
            'reg_no' => '2101010001',
            'formative_diary_marks' => 35.0, // max 30
        ];

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->postJson("/r21/classroom/project/{$subject->id}/save-evaluation", $invalidPayload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['formative_diary_marks']);
    }

    /**
     * Test ESE marks retrieval and bulk update.
     */
    public function test_ese_marks_and_bulk_update(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        // Retrieve initial ESE marks
        $getRes = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->getJson("/r21/classroom/project/{$subject->id}/ese-marks");
        $getRes->assertStatus(200);
        $getRes->assertJson(['status' => 'SUCCESS']);

        // Bulk update
        $bulkPayload = [
            'marks' => [
                '2101010001' => 45.0,
                '2101010002' => 38.0,
            ],
            'grades' => [
                '2101010001' => 'S',
                '2101010002' => 'B',
            ]
        ];

        $bulkRes = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->postJson("/r21/classroom/project/{$subject->id}/ese-marks/bulk-update", $bulkPayload);

        $bulkRes->assertStatus(200);
        $bulkRes->assertJson(['status' => 'SUCCESS']);

        $this->assertDatabaseHas('r21_major_project_evaluations', [
            'batch_subject_id' => $subject->id,
            'reg_no' => '2101010001',
            'total_ese_50' => 45.0,
            'ese_grade' => 'S',
        ]);
    }

    /**
     * Test attainment summary calculation endpoint.
     */
    public function test_attainment_summary_endpoint(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        // Seed an evaluation
        R21MajorProjectEvaluation::create([
            'batch_subject_id' => $subject->id,
            'reg_no' => '2101010001',
            'formative_diary_marks' => 25.0,
            'summative_dept_marks' => 25.0,
            'attendance_marks' => 15.0,
            'total_cia_75' => 65.0,
            'total_ese_50' => 42.0,
            'ese_grade' => 'A',
            'grand_total_125' => 107.0,
            'passed' => true,
        ]);

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->getJson("/r21/classroom/project/{$subject->id}/attainment-summary");

        $response->assertStatus(200);
        $response->assertJson(['status' => 'SUCCESS']);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'subject_id',
                'matrix',
                'average_direct',
                'average_indirect',
                'average_overall',
                'ese_config',
            ]
        ]);
    }

    /**
     * Test examiners save endpoint.
     */
    public function test_save_examiners_endpoint(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        $examinersPayload = [
            'internal_name' => 'Prof. Internal',
            'internal_designation' => 'Lecturer in EEE',
            'internal_college' => 'Carmel Polytechnic College',
            'external_name' => 'Prof. External',
            'external_designation' => 'HOD in EEE',
            'external_college' => 'Govt Polytechnic College',
            'exam_date' => '2026-09-25',
        ];

        $response = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->postJson("/r21/classroom/project/{$subject->id}/examiners", $examinersPayload);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'SUCCESS']);

        $courseFile = R21MajorProjectCourseFile::where('batch_subject_id', $subject->id)->first();
        $this->assertEquals('Prof. Internal', $courseFile->attainment_settings['examiners']['internal_name']);
        $this->assertEquals('Prof. External', $courseFile->attainment_settings['examiners']['external_name']);
    }

    /**
     * Test group-wide ESE and CIA rubrics updates.
     */
    public function test_group_ese_and_cia_updates(): void
    {
        [$subject, $classroom, $staff, $students] = $this->createSubjectAndClassroom();

        // Group ESE
        $groupEsePayload = [
            'group_id' => 'GRP-1',
            'reg_nos' => ['2101010001', '2101010002'],
            'ese_prototype' => 9.0,
            'ese_modern_tools' => 4.5,
            'ese_innovativeness' => 2.0,
            'ese_group_activity' => 4.5,
            'ese_project_report' => 4.5,
        ];

        $resEse = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->postJson("/r21/classroom/project/{$subject->id}/group-ese", $groupEsePayload);

        $resEse->assertStatus(200);
        $resEse->assertJson(['status' => 'SUCCESS', 'updated_students' => 2]);

        // Group CIA
        $groupCiaPayload = [
            'group_id' => 'GRP-1',
            'reg_nos' => ['2101010001', '2101010002'],
            'formative_diary_marks' => 28.0,
            'summative_dept_marks' => 27.0,
            'attendance_marks' => 15.0,
        ];

        $resCia = $this->withSession(['userId' => $staff->mobile_no, 'userRole' => 'Lecturer'])
            ->postJson("/r21/classroom/project/{$subject->id}/group-cia", $groupCiaPayload);

        $resCia->assertStatus(200);
        $resCia->assertJson(['status' => 'SUCCESS', 'updated_students' => 2]);

        $this->assertDatabaseHas('r21_major_project_evaluations', [
            'batch_subject_id' => $subject->id,
            'reg_no' => '2101010001',
            'total_cia_75' => 70.0,
            'total_ese_50' => 24.5,
            'grand_total_125' => 94.5,
        ]);
    }

    /**
     * Test print report endpoint data response.
     */
    public function test_print_report_endpoint(): void
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
                'gradeStats',
                'examiners',
            ]
        ]);
    }
}
