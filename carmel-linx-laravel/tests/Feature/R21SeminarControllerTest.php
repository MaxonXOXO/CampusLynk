<?php

namespace Tests\Feature;

use App\Http\Controllers\R21VirtualClassroomSeminarController;
use App\Models\AcademicMark;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\CourseFile;
use App\Models\SeminarEvaluation;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\StudentSeminarRegistration;
use App\Services\AttainmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class R21SeminarControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createSubjectAndClassroom(): array
    {
        $staff1 = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Guide One',
            'email' => 'guide1@example.com',
            'password' => 'secret',
            'designation' => 'Lecturer',
            'branch' => 'EL',
            'account_status' => 'APPROVED',
        ]);

        $staff2 = StaffProfile::create([
            'mobile_no' => '9876543211',
            'name' => 'Prof. Guide Two',
            'email' => 'guide2@example.com',
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
            'tutor_mobile_no' => $staff1->mobile_no,
            'mentor_mobile_no' => $staff1->mobile_no,
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

        return [$batchSubject, $classroom, $staff1, $staff2, [$student1, $student2]];
    }

    /**
     * Test that unauthenticated requests to seminar endpoints are rejected.
     */
    public function test_unauthenticated_requests_are_rejected(): void
    {
        $response = $this->get('/r21/classroom/seminar/1');
        $response->assertRedirect('/');

        $jsonShow = $this->getJson('/r21/classroom/seminar/1');
        $jsonShow->assertStatus(401);

        $jsonSyllabus = $this->postJson('/r21/classroom/seminar/1/syllabus');
        $jsonSyllabus->assertStatus(401);

        $jsonEval = $this->postJson('/r21/classroom/seminar/1/evaluate', ['reg_no' => '2101010001']);
        $jsonEval->assertStatus(401);

        $jsonSchedule = $this->postJson('/r21/classroom/seminar/1/schedule', ['reg_no' => '2101010001']);
        $jsonSchedule->assertStatus(401);

        $responsePrint = $this->get('/r21/classroom/seminar/1/print');
        $responsePrint->assertRedirect('/');

        $jsonPrint = $this->getJson('/r21/classroom/seminar/1/print');
        $jsonPrint->assertStatus(401);

        $jsonAttainment = $this->getJson('/r21/classroom/seminar/1/attainment-summary');
        $jsonAttainment->assertStatus(401);
    }

    /**
     * Test show endpoint returns dashboard data with course file initialization.
     */
    public function test_show_endpoint_returns_json_and_initializes_course_file(): void
    {
        [$subject, $classroom, $staff1, $staff2, $students] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff1->mobile_no, 'userRole' => 'Lecturer'])
            ->getJson("/r21/classroom/seminar/{$subject->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'data' => [
                'totalStudents' => 2,
                'completedCount' => 0,
                'pendingCount' => 2,
                'classAvg' => 0.0,
            ]
        ]);

        $this->assertDatabaseHas('course_files', [
            'batch_subject_id' => $subject->id,
        ]);
    }

    /**
     * Test upload syllabus endpoint validates and stores file.
     */
    public function test_upload_syllabus_endpoint(): void
    {
        Storage::fake('public');
        [$subject, $classroom, $staff1, $staff2, $students] = $this->createSubjectAndClassroom();

        // 1. Validation failure: no file
        $failResponse = $this->withSession(['userId' => $staff1->mobile_no])
            ->postJson("/r21/classroom/seminar/{$subject->id}/syllabus", []);
        $failResponse->assertStatus(422);

        // 2. Successful upload
        $file = UploadedFile::fake()->create('seminar_syllabus.pdf', 300, 'application/pdf');

        $response = $this->withSession(['userId' => $staff1->mobile_no])
            ->postJson("/r21/classroom/seminar/{$subject->id}/syllabus", [
                'syllabus_file' => $file,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'SUCCESS']);

        $courseFile = CourseFile::where('batch_subject_id', $subject->id)->first();
        $this->assertNotNull($courseFile);
        $this->assertStringContainsString('syllabi/r21_seminar_syllabus_', $courseFile->syllabus_pdf_path);
    }

    /**
     * Test save evaluation validates statutory rubric bounds (Clause 11.2.6).
     */
    public function test_save_evaluation_validation_bounds(): void
    {
        [$subject, $classroom, $staff1, $staff2, $students] = $this->createSubjectAndClassroom();

        // Out-of-bounds evaluation (relevance max is 7.5, presentation max is 37.5)
        $invalidPayload = [
            'reg_no' => '2101010001',
            'relevance' => 10.0, // Invalid: max 7.5
            'literature' => 7.5,
            'presentation' => 45.0, // Invalid: max 37.5
            'interaction' => 7.5,
            'report' => 7.5,
            'attendance' => 7.5,
        ];

        $response = $this->withSession(['userId' => $staff1->mobile_no])
            ->postJson("/r21/classroom/seminar/{$subject->id}/evaluate", $invalidPayload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['relevance', 'presentation']);
    }

    /**
     * Test single student evaluation save and academic mark upsert.
     */
    public function test_save_evaluation_success_and_academic_mark_sync(): void
    {
        [$subject, $classroom, $staff1, $staff2, $students] = $this->createSubjectAndClassroom();

        $evalPayload = [
            'reg_no' => '2101010001',
            'relevance' => 7.0,
            'literature' => 6.5,
            'presentation' => 35.0,
            'interaction' => 7.0,
            'report' => 7.0,
            'attendance' => 7.5,
            'topic' => 'Machine Learning in Power Systems',
            'presentation_date' => '2026-10-15',
            'guide_mobile_no' => $staff1->mobile_no,
        ];

        $response = $this->withSession(['userId' => $staff1->mobile_no])
            ->postJson("/r21/classroom/seminar/{$subject->id}/evaluate", $evalPayload);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'data' => [
                'reg_no' => '2101010001',
                'my_total' => 70.0,
                'average_score' => 70.0,
                'eval_count' => 1,
                'letter_grade' => 'S', // >=90% of 75 = 67.5 -> 70.0 is S
                'grade_point' => 10,
                'result' => 'Pass',
                'completed_count' => 1,
                'topic' => 'Machine Learning in Power Systems',
                'presentation_date' => '2026-10-15',
                'guide_name' => 'Prof. Guide One',
            ]
        ]);

        $this->assertDatabaseHas('seminar_evaluations', [
            'batch_subject_id' => $subject->id,
            'reg_no' => '2101010001',
            'assessor_mobile_no' => $staff1->mobile_no,
            'total_score' => 70.0,
        ]);

        $this->assertDatabaseHas('student_seminar_registrations', [
            'batch_subject_id' => $subject->id,
            'reg_no' => '2101010001',
            'topic' => 'Machine Learning in Power Systems',
            'guide_mobile_no' => $staff1->mobile_no,
        ]);

        $this->assertDatabaseHas('academic_marks', [
            'reg_no' => '2101010001',
            'subject_code' => $subject->subject_code,
            'category' => 'Seminar',
            'co_tag' => 'CO1',
            'max_marks' => 75,
            'marks_obtained' => 70.0,
        ]);
    }

    /**
     * Test multi-assessor evaluation averaging and academic mark update.
     */
    public function test_multi_assessor_evaluation_averaging(): void
    {
        [$subject, $classroom, $staff1, $staff2, $students] = $this->createSubjectAndClassroom();

        // Assessor 1 evaluates student: total 70.0
        $this->withSession(['userId' => $staff1->mobile_no])
            ->postJson("/r21/classroom/seminar/{$subject->id}/evaluate", [
                'reg_no' => '2101010001',
                'relevance' => 7.0,
                'literature' => 6.5,
                'presentation' => 35.0,
                'interaction' => 7.0,
                'report' => 7.0,
                'attendance' => 7.5,
            ])->assertStatus(200);

        // Assessor 2 evaluates same student: total 60.0
        $response2 = $this->withSession(['userId' => $staff2->mobile_no])
            ->postJson("/r21/classroom/seminar/{$subject->id}/evaluate", [
                'reg_no' => '2101010001',
                'relevance' => 6.0,
                'literature' => 5.5,
                'presentation' => 30.0,
                'interaction' => 6.0,
                'report' => 6.5,
                'attendance' => 6.0,
            ]);

        $response2->assertStatus(200);
        $response2->assertJson([
            'status' => 'SUCCESS',
            'data' => [
                'reg_no' => '2101010001',
                'my_total' => 60.0,
                'average_score' => 65.0, // (70.0 + 60.0) / 2
                'eval_count' => 2,
                'letter_grade' => 'A', // 65/75 = 86.67% -> A
                'grade_point' => 9,
            ]
        ]);

        $this->assertEquals(2, SeminarEvaluation::where('batch_subject_id', $subject->id)->where('reg_no', '2101010001')->count());

        $this->assertDatabaseHas('academic_marks', [
            'reg_no' => '2101010001',
            'subject_code' => $subject->subject_code,
            'category' => 'Seminar',
            'marks_obtained' => 65.0,
        ]);
    }

    /**
     * Test update seminar schedule endpoint.
     */
    public function test_update_seminar_schedule_endpoint(): void
    {
        [$subject, $classroom, $staff1, $staff2, $students] = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $staff1->mobile_no])
            ->postJson("/r21/classroom/seminar/{$subject->id}/schedule", [
                'reg_no' => '2101010002',
                'topic' => 'Quantum Computing Architectures',
                'presentation_date' => '2026-11-20',
                'guide_mobile_no' => $staff2->mobile_no,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'data' => [
                'reg_no' => '2101010002',
                'topic' => 'Quantum Computing Architectures',
                'presentation_date' => '2026-11-20',
                'guide_name' => 'Prof. Guide Two',
                'guide_mobile_no' => $staff2->mobile_no,
            ]
        ]);

        $this->assertDatabaseHas('student_seminar_registrations', [
            'batch_subject_id' => $subject->id,
            'reg_no' => '2101010002',
            'topic' => 'Quantum Computing Architectures',
            'guide_mobile_no' => $staff2->mobile_no,
        ]);
    }

    /**
     * Test print report endpoint across all report modes.
     */
    public function test_print_report_endpoint_modes_and_negotiation(): void
    {
        [$subject, $classroom, $staff1, $staff2, $students] = $this->createSubjectAndClassroom();

        // 1. Consolidated mode
        $respConsolidated = $this->withSession(['userId' => $staff1->mobile_no])
            ->getJson("/r21/classroom/seminar/{$subject->id}/print?type=consolidated");

        $respConsolidated->assertStatus(200);
        $respConsolidated->assertJson([
            'status' => 'SUCCESS',
            'data' => [
                'reportType' => 'consolidated',
                'totalStudents' => 2,
            ]
        ]);

        // 2. CIA submission mode
        $respCia = $this->withSession(['userId' => $staff1->mobile_no])
            ->getJson("/r21/classroom/seminar/{$subject->id}/print?type=cia_submission");

        $respCia->assertStatus(200);
        $respCia->assertJson([
            'status' => 'SUCCESS',
            'data' => [
                'reportType' => 'cia_submission',
            ]
        ]);

        // 3. Schedule mode
        $respSchedule = $this->withSession(['userId' => $staff1->mobile_no])
            ->getJson("/r21/classroom/seminar/{$subject->id}/print?type=schedule");

        $respSchedule->assertStatus(200);
        $respSchedule->assertJson([
            'status' => 'SUCCESS',
            'data' => [
                'reportType' => 'schedule',
            ]
        ]);
    }

    /**
     * Test get attainment summary endpoint.
     */
    public function test_get_attainment_summary_endpoint(): void
    {
        [$subject, $classroom, $staff1, $staff2, $students] = $this->createSubjectAndClassroom();

        // Save an evaluation to test attainment calculations
        $this->withSession(['userId' => $staff1->mobile_no])
            ->postJson("/r21/classroom/seminar/{$subject->id}/evaluate", [
                'reg_no' => '2101010001',
                'relevance' => 7.0,
                'literature' => 7.0,
                'presentation' => 35.0,
                'interaction' => 7.0,
                'report' => 7.0,
                'attendance' => 7.0,
            ])->assertStatus(200);

        $response = $this->withSession(['userId' => $staff1->mobile_no])
            ->getJson("/r21/classroom/seminar/{$subject->id}/attainment-summary");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'data' => [
                'subject_id' => $subject->id,
                'subject_code' => $subject->subject_code,
                'revision' => 'REV2021',
                'subject_type' => 'Seminar',
            ]
        ]);

        $data = $response->json('data');
        $this->assertNotEmpty($data['matrix']);
        $this->assertEquals(3, count($data['matrix'])); // CO1, CO2, CO3
        $this->assertArrayHasKey('average_direct', $data);
        $this->assertArrayHasKey('average_indirect', $data);
        $this->assertArrayHasKey('average_overall', $data);
    }

    /**
     * Test static grading and number-to-words helpers.
     */
    public function test_sbte_grade_and_number_to_words_helpers(): void
    {
        // Grade Scale (Max 75):
        // S: >= 67.5 (90%)
        // A: >= 60.0 (80%)
        // B: >= 52.5 (70%)
        // C: >= 45.0 (60%)
        // D: >= 37.5 (50%)
        // E: >= 30.0 (40%)
        // F: < 30.0
        $this->assertEquals(['grade' => 'S', 'point' => 10, 'result' => 'Pass'], R21VirtualClassroomSeminarController::calculateSbteGrade(70.0));
        $this->assertEquals(['grade' => 'A', 'point' => 9, 'result' => 'Pass'], R21VirtualClassroomSeminarController::calculateSbteGrade(62.0));
        $this->assertEquals(['grade' => 'B', 'point' => 8, 'result' => 'Pass'], R21VirtualClassroomSeminarController::calculateSbteGrade(55.0));
        $this->assertEquals(['grade' => 'C', 'point' => 7, 'result' => 'Pass'], R21VirtualClassroomSeminarController::calculateSbteGrade(48.0));
        $this->assertEquals(['grade' => 'D', 'point' => 6, 'result' => 'Pass'], R21VirtualClassroomSeminarController::calculateSbteGrade(40.0));
        $this->assertEquals(['grade' => 'E', 'point' => 5, 'result' => 'Pass'], R21VirtualClassroomSeminarController::calculateSbteGrade(32.0));
        $this->assertEquals(['grade' => 'F', 'point' => 0, 'result' => 'Failed'], R21VirtualClassroomSeminarController::calculateSbteGrade(25.0));

        // Number to Words:
        $this->assertEquals('Seventy', R21VirtualClassroomSeminarController::numberToWords(70.0));
        $this->assertEquals('Sixty Five Point Five', R21VirtualClassroomSeminarController::numberToWords(65.5));
        $this->assertEquals('Zero', R21VirtualClassroomSeminarController::numberToWords(0));
    }
}
