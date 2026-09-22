<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\CourseFile;
use App\Models\LessonPlan;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\SubjectStaffAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ClassroomControllerExtensionsTest extends TestCase
{
    use RefreshDatabase;

    private function createClassroomContext(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Classroom Teacher',
            'email' => 'teacher@example.com',
            'password' => 'secret',
            'designation' => 'Lecturer',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_CLS_TEST_I',
            'branch' => 'CT',
            'batch_year' => 2026,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'I',
        ]);

        DB::table('syllabus_registry')->insert([
            'subject_code' => '3001',
            'revision_year' => 2021,
            'subject_name' => 'Data Structures',
            'co_count' => 4,
            'cia_marks' => 40,
            'ese_marks' => 60,
            'credits' => 3.0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $batchSubject = BatchSubject::create([
            'classroom_id' => 'CR_CLS_TEST_I',
            'subject_code' => '3001',
            'subject_name' => 'Data Structures',
            'subject_type' => 'Theory',
            'semester' => 3,
        ]);

        SubjectStaffAssignment::create([
            'batch_subject_id' => $batchSubject->id,
            'staff_mobile_no' => $staff->mobile_no,
            'role' => 'Subject Teacher',
        ]);

        $student1 = Student::create([
            'reg_no' => '2601010010',
            'adm_no' => 'ADM010',
            'name' => 'Carol Danvers',
            'email' => 'carol@example.com',
            'password' => 'secret',
            'phone' => '9876500010',
            'branch' => 'CT',
            'admission_year' => 2026,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_CLS_TEST_I',
            'semester' => 'I',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2601010010',
            'roll_no' => 1,
        ]);

        $student2 = Student::create([
            'reg_no' => '2601010011',
            'adm_no' => 'ADM011',
            'name' => 'David Banner',
            'email' => 'david@example.com',
            'password' => 'secret',
            'phone' => '9876500011',
            'branch' => 'CT',
            'admission_year' => 2026,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_CLS_TEST_I',
            'semester' => 'I',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2601010011',
            'roll_no' => 2,
        ]);

        $courseFile = CourseFile::create([
            'batch_subject_id' => $batchSubject->id,
            'academic_year' => '2026-2027',
            'status' => 'Draft',
            'parsed_copo' => [
                'mappings' => [
                    'CO1' => ['PO1' => '3', 'PO2' => '2', 'PO3' => '1'],
                    'CO2' => ['PO1' => '2', 'PO2' => '3', 'PO3' => '2'],
                    'CO3' => ['PO1' => '1', 'PO2' => '2', 'PO3' => '3'],
                    'CO4' => ['PO1' => '2', 'PO2' => '2', 'PO3' => '2'],
                ]
            ],
        ]);

        $lessonPlan = LessonPlan::create([
            'batch_subject_id' => $batchSubject->id,
            'day_no' => 1,
            'topic_content' => 'Arrays and Linked Lists',
            'co_id' => 'CO1',
            'status' => 'Completed',
        ]);

        return compact('staff', 'classroom', 'batchSubject', 'student1', 'student2', 'courseFile', 'lessonPlan');
    }

    public function test_bulk_update_ese_marks(): void
    {
        $ctx = $this->createClassroomContext();

        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->postJson("/api/classroom/{$ctx['batchSubject']->id}/ese-marks/bulk-update", [
            'entry_mode' => 'marks',
            'max_marks' => 60,
            'ese_threshold_grade' => 'D',
            'marks_data' => [
                [
                    'reg_no' => '2601010010',
                    'marks' => 54, // 90% -> Grade S
                ],
                [
                    'reg_no' => '2601010011',
                    'is_absent' => true, // Absent -> Grade FE
                ],
            ],
        ]);

        $res->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'updated_count' => 2,
                'max_marks' => 60,
            ]);

        // Verify database records in academic_marks and student_board_grades
        $r1 = DB::table('academic_marks')->where('reg_no', '2601010010')->where('batch_subject_id', $ctx['batchSubject']->id)->first();
        $this->assertNotNull($r1);
        $this->assertEquals(54.0, $r1->marks_obtained);

        $g1 = DB::table('student_board_grades')->where('reg_no', '2601010010')->where('subject_code', $ctx['batchSubject']->subject_code)->first();
        $this->assertNotNull($g1);
        $this->assertEquals('S', $g1->grade);

        $r2 = DB::table('academic_marks')->where('reg_no', '2601010011')->where('batch_subject_id', $ctx['batchSubject']->id)->first();
        $this->assertNotNull($r2);
        $this->assertEquals(0.0, $r2->marks_obtained);

        $g2 = DB::table('student_board_grades')->where('reg_no', '2601010011')->where('subject_code', $ctx['batchSubject']->subject_code)->first();
        $this->assertNotNull($g2);
        $this->assertEquals('FE', $g2->grade);
    }

    public function test_get_subject_attainment_data(): void
    {
        $ctx = $this->createClassroomContext();

        // Seed some student board grades
        DB::table('student_board_grades')->insert([
            [
                'subject_code' => $ctx['batchSubject']->subject_code,
                'semester' => 3,
                'reg_no' => '2601010010',
                'grade' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_code' => $ctx['batchSubject']->subject_code,
                'semester' => 3,
                'reg_no' => '2601010011',
                'grade' => 'C',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->getJson("/api/classroom/{$ctx['batchSubject']->id}/attainment-data");

        $res->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'subject_id' => $ctx['batchSubject']->id,
                'total_students' => 2,
            ]);

        $this->assertArrayHasKey('direct_attainment', $res->json());
        $this->assertArrayHasKey('combined_attainment', $res->json());
        $this->assertArrayHasKey('po_attainments', $res->json());

        // Both students meet threshold grade D (A and C), so met percentage is 100% -> Level 3
        $this->assertEquals(3, $res->json('direct_attainment.CO1'));
    }

    public function test_print_course_file_complete_pdf(): void
    {
        $ctx = $this->createClassroomContext();

        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->get("/classroom/{$ctx['batchSubject']->id}/course-file/print-complete");

        $res->assertStatus(200);
        $res->assertSee('Course Title:', false);
        $res->assertSee('Data Structures', false);
        $res->assertSee('3001', false);
        $res->assertSee('Course Outcomes (COs)', false);
        $res->assertSee('Arrays and Linked Lists', false);
        $res->assertSee('Carol Danvers', false);
        $res->assertSee('David Banner', false);
    }
}
