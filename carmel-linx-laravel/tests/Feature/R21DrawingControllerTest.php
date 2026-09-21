<?php

namespace Tests\Feature;

use App\Http\Controllers\R21VirtualClassroomDrawingController;
use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\LessonPlan;
use App\Models\R21DrawingAttendanceEvaluation;
use App\Models\R21DrawingCourseFile;
use App\Models\R21DrawingSeriesTest;
use App\Models\R21DrawingSheetEvaluation;
use App\Models\StaffProfile;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class R21DrawingControllerTest extends TestCase
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

        return compact('staff', 'classroom', 'batchSubject', 'student1', 'student2');
    }

    public function test_unauthenticated_user_is_redirected(): void
    {
        $data = $this->createSubjectAndClassroom();
        $response = $this->get("/r21/classroom/drawing/{$data['batchSubject']->id}");
        $response->assertRedirect('/');
    }

    public function test_drawing_hall_dashboard_initialization_and_default_sheets(): void
    {
        $data = $this->createSubjectAndClassroom();

        $response = $this->withSession(['userId' => $data['staff']->mobile_no])
            ->get("/r21/classroom/drawing/{$data['batchSubject']->id}");

        $response->assertStatus(200);

        // Verify Course File auto-created
        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $data['batchSubject']->id)->first();
        $this->assertNotNull($courseFile);
        $this->assertEquals(50, $courseFile->cia_marks);
        $this->assertEquals(100, $courseFile->ese_marks);
        $this->assertCount(8, $courseFile->parsed_sheets);
        $this->assertCount(4, $courseFile->parsed_cos);
        $this->assertCount(4, $courseFile->parsed_modules);

        // Verify 30-Day Lesson Plan auto-generated
        $plansCount = LessonPlan::where('batch_subject_id', $data['batchSubject']->id)->count();
        $this->assertEquals(30, $plansCount);
    }

    public function test_upload_syllabus_pdf(): void
    {
        Storage::fake('public');
        $data = $this->createSubjectAndClassroom();

        $file = UploadedFile::fake()->create('syllabus_drawing.pdf', 500, 'application/pdf');

        $response = $this->withSession(['userId' => $data['staff']->mobile_no])
            ->postJson("/r21/classroom/drawing/{$data['batchSubject']->id}/syllabus", [
                'syllabus_file' => $file,
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $courseFile = R21DrawingCourseFile::where('batch_subject_id', $data['batchSubject']->id)->first();
        $this->assertNotNull($courseFile->syllabus_pdf_path);
        $this->assertStringContainsString('r21_drawing_syllabus_', $courseFile->syllabus_pdf_path);
    }

    public function test_save_formative_sheet_marks(): void
    {
        $data = $this->createSubjectAndClassroom();

        $payload = [
            'sheet_no' => 'Sheet 1',
            'evaluations' => [
                [
                    'reg_no' => $data['student1']->reg_no,
                    'timely_completion' => 45, // out of 50
                    'appearance_organization' => 48, // out of 50
                    'is_absent' => false,
                    'remarks' => 'Good precision',
                ],
                [
                    'reg_no' => $data['student2']->reg_no,
                    'timely_completion' => 60, // clamped to 50
                    'appearance_organization' => -5, // clamped to 0
                    'is_absent' => true,
                    'remarks' => 'Absent',
                ]
            ]
        ];

        $response = $this->withSession(['userId' => $data['staff']->mobile_no])
            ->postJson("/r21/classroom/drawing/{$data['batchSubject']->id}/sheets/save", $payload);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // Verify Student 1
        $eval1 = R21DrawingSheetEvaluation::where('batch_subject_id', $data['batchSubject']->id)
            ->where('sheet_no', 'Sheet 1')
            ->where('reg_no', $data['student1']->reg_no)
            ->first();
        $this->assertNotNull($eval1);
        $this->assertEquals(45.00, $eval1->timely_completion);
        $this->assertEquals(48.00, $eval1->appearance_organization);
        $this->assertEquals(93.00, $eval1->total_score_100);
        $this->assertFalse($eval1->is_absent);

        // Verify Student 2 (Absent -> 0 total)
        $eval2 = R21DrawingSheetEvaluation::where('batch_subject_id', $data['batchSubject']->id)
            ->where('sheet_no', 'Sheet 1')
            ->where('reg_no', $data['student2']->reg_no)
            ->first();
        $this->assertNotNull($eval2);
        $this->assertEquals(0.00, $eval2->total_score_100);
        $this->assertTrue($eval2->is_absent);
    }

    public function test_save_summative_series_test_marks(): void
    {
        $data = $this->createSubjectAndClassroom();

        $payload = [
            'test_no' => 'Test 1',
            'evaluations' => [
                [
                    'reg_no' => $data['student1']->reg_no,
                    'procedure_drawing' => 38, // max 40
                    'final_drawing' => 28,     // max 30
                    'dimensioning' => 19,      // max 20
                    'neatness' => 9,           // max 10
                    'is_absent' => false,
                    'remarks' => 'Excellent work',
                ],
                [
                    'reg_no' => $data['student2']->reg_no,
                    'procedure_drawing' => 50, // clamped to 40
                    'final_drawing' => 40,     // clamped to 30
                    'dimensioning' => 30,      // clamped to 20
                    'neatness' => 20,          // clamped to 10
                    'is_absent' => false,
                    'remarks' => 'Clamped to max',
                ]
            ]
        ];

        $response = $this->withSession(['userId' => $data['staff']->mobile_no])
            ->postJson("/r21/classroom/drawing/{$data['batchSubject']->id}/tests/save", $payload);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // Verify Student 1 (38 + 28 + 19 + 9 = 94)
        $test1 = R21DrawingSeriesTest::where('batch_subject_id', $data['batchSubject']->id)
            ->where('test_no', 'Test 1')
            ->where('reg_no', $data['student1']->reg_no)
            ->first();
        $this->assertNotNull($test1);
        $this->assertEquals(94.00, $test1->total_score_100);

        // Verify Student 2 (Clamped: 40 + 30 + 20 + 10 = 100)
        $test2 = R21DrawingSeriesTest::where('batch_subject_id', $data['batchSubject']->id)
            ->where('test_no', 'Test 1')
            ->where('reg_no', $data['student2']->reg_no)
            ->first();
        $this->assertNotNull($test2);
        $this->assertEquals(100.00, $test2->total_score_100);
    }

    public function test_save_attendance_marks_and_override(): void
    {
        $data = $this->createSubjectAndClassroom();

        $payload = [
            'records' => [
                [
                    'reg_no' => $data['student1']->reg_no,
                    'attendance_percentage' => 95.00,
                    'attendance_mark' => 10.00,
                    'override_mark' => '', // no override
                ],
                [
                    'reg_no' => $data['student2']->reg_no,
                    'attendance_percentage' => 68.00,
                    'attendance_mark' => 2.00,
                    'override_mark' => 8.50, // manual override
                ]
            ]
        ];

        $response = $this->withSession(['userId' => $data['staff']->mobile_no])
            ->postJson("/r21/classroom/drawing/{$data['batchSubject']->id}/attendance/save", $payload);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // Verify Student 1
        $att1 = R21DrawingAttendanceEvaluation::where('batch_subject_id', $data['batchSubject']->id)
            ->where('reg_no', $data['student1']->reg_no)
            ->first();
        $this->assertEquals(10.00, $att1->final_attendance_mark);
        $this->assertNull($att1->override_mark);

        // Verify Student 2
        $att2 = R21DrawingAttendanceEvaluation::where('batch_subject_id', $data['batchSubject']->id)
            ->where('reg_no', $data['student2']->reg_no)
            ->first();
        $this->assertEquals(8.50, $att2->final_attendance_mark);
        $this->assertEquals(8.50, $att2->override_mark);
    }

    public function test_consolidated_student_computations_and_pass_eligibility(): void
    {
        $data = $this->createSubjectAndClassroom();

        // Sheet Evaluation for Student 1: 90/100 -> Formative mark = (90/100)*20 = 18.00
        R21DrawingSheetEvaluation::create([
            'batch_subject_id' => $data['batchSubject']->id,
            'sheet_no' => 'Sheet 1',
            'reg_no' => $data['student1']->reg_no,
            'timely_completion' => 45,
            'appearance_organization' => 45,
            'total_score_100' => 90.00,
            'is_absent' => false,
        ]);

        // Series Tests for Student 1: Test 1 = 90, Test 2 = 90 -> Avg 90 -> Summative mark = (90/100)*20 = 18.00
        R21DrawingSeriesTest::create([
            'batch_subject_id' => $data['batchSubject']->id,
            'test_no' => 'Test 1',
            'reg_no' => $data['student1']->reg_no,
            'procedure_drawing' => 36,
            'final_drawing' => 27,
            'dimensioning' => 18,
            'neatness' => 9,
            'total_score_100' => 90.00,
            'is_absent' => false,
        ]);

        // Attendance for Student 1: 10.00
        R21DrawingAttendanceEvaluation::create([
            'batch_subject_id' => $data['batchSubject']->id,
            'reg_no' => $data['student1']->reg_no,
            'attendance_percentage' => 92.00,
            'attendance_mark' => 10.00,
            'final_attendance_mark' => 10.00,
        ]);

        // Attendance for Student 2: 0.00
        R21DrawingAttendanceEvaluation::create([
            'batch_subject_id' => $data['batchSubject']->id,
            'reg_no' => $data['student2']->reg_no,
            'attendance_percentage' => 0.00,
            'attendance_mark' => 0.00,
            'final_attendance_mark' => 0.00,
        ]);

        // Student 1 Total CIA = 18 + 18 + 10 = 46.00 (>= 20 -> Pass)

        // Student 2: 0 sheets, 0 tests, 0 att -> Total CIA = 0.00 (< 20 -> Fail)

        $response = $this->withSession(['userId' => $data['staff']->mobile_no])
            ->getJson("/r21/classroom/drawing/{$data['batchSubject']->id}");

        $response->assertStatus(200);
        $results = collect($response->json('studentResults'));

        $st1 = $results->where('reg_no', $data['student1']->reg_no)->first();
        $this->assertEquals(18.0, $st1['formative_mark']);
        $this->assertEquals(18.0, $st1['summative_mark']);
        $this->assertEquals(10.0, $st1['att_marks']);
        $this->assertEquals(46.0, $st1['total_cia']);
        $this->assertTrue($st1['is_pass']);

        $st2 = $results->where('reg_no', $data['student2']->reg_no)->first();
        $this->assertEquals(0.0, $st2['formative_mark']);
        $this->assertEquals(0.0, $st2['summative_mark']);
        $this->assertEquals(0.0, $st2['total_cia']);
        $this->assertFalse($st2['is_pass']);
    }

    public function test_print_endpoints_return_successful_responses(): void
    {
        $data = $this->createSubjectAndClassroom();

        $endpoints = [
            "/r21/classroom/drawing/{$data['batchSubject']->id}/print/sheets",
            "/r21/classroom/drawing/{$data['batchSubject']->id}/print/tests",
            "/r21/classroom/drawing/{$data['batchSubject']->id}/print/cia",
            "/r21/classroom/drawing/{$data['batchSubject']->id}/print/lesson-plan",
        ];

        foreach ($endpoints as $url) {
            $response = $this->withSession(['userId' => $data['staff']->mobile_no])->get($url);
            $response->assertStatus(200);
        }
    }

    public function test_sbte_grading_utility(): void
    {
        $this->assertEquals(['grade' => 'S', 'point' => 10, 'result' => 'Pass'], R21VirtualClassroomDrawingController::calculateSbteGrade(95.0));
        $this->assertEquals(['grade' => 'A', 'point' => 9, 'result' => 'Pass'], R21VirtualClassroomDrawingController::calculateSbteGrade(82.0));
        $this->assertEquals(['grade' => 'B', 'point' => 8, 'result' => 'Pass'], R21VirtualClassroomDrawingController::calculateSbteGrade(75.0));
        $this->assertEquals(['grade' => 'C', 'point' => 7, 'result' => 'Pass'], R21VirtualClassroomDrawingController::calculateSbteGrade(64.0));
        $this->assertEquals(['grade' => 'D', 'point' => 6, 'result' => 'Pass'], R21VirtualClassroomDrawingController::calculateSbteGrade(55.0));
        $this->assertEquals(['grade' => 'E', 'point' => 5, 'result' => 'Pass'], R21VirtualClassroomDrawingController::calculateSbteGrade(42.0));
        $this->assertEquals(['grade' => 'F', 'point' => 0, 'result' => 'Failed'], R21VirtualClassroomDrawingController::calculateSbteGrade(35.0));
    }
}
