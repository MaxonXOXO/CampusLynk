<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\LessonPlan;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\SubjectStaffAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AttendanceControllerExtensionsTest extends TestCase
{
    use RefreshDatabase;

    private function createAttendanceContext(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Teacher One',
            'email' => 'teacher@example.com',
            'password' => 'secret',
            'designation' => 'Lecturer',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_ATT_TEST_I',
            'branch' => 'CT',
            'batch_year' => 2026,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'I',
        ]);

        $batchSubject = BatchSubject::create([
            'classroom_id' => 'CR_ATT_TEST_I',
            'subject_code' => '1001',
            'subject_name' => 'Programming Basics',
            'subject_type' => 'Theory',
            'semester' => 1,
        ]);

        SubjectStaffAssignment::create([
            'batch_subject_id' => $batchSubject->id,
            'staff_mobile_no' => $staff->mobile_no,
            'role' => 'Subject Teacher',
        ]);

        $student1 = Student::create([
            'reg_no' => '2601010001',
            'adm_no' => 'ADM001',
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'password' => 'secret',
            'phone' => '9876500001',
            'branch' => 'CT',
            'admission_year' => 2026,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_ATT_TEST_I',
            'semester' => 'I',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2601010001',
            'roll_no' => 1,
        ]);

        $student2 = Student::create([
            'reg_no' => '2601010002',
            'adm_no' => 'ADM002',
            'name' => 'Bob Jones',
            'email' => 'bob@example.com',
            'password' => 'secret',
            'phone' => '9876500002',
            'branch' => 'CT',
            'admission_year' => 2026,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_ATT_TEST_I',
            'semester' => 'I',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2601010002',
            'roll_no' => 2,
        ]);

        $lessonPlan = LessonPlan::create([
            'batch_subject_id' => $batchSubject->id,
            'day_no' => 1,
            'topic_content' => 'Introduction to Algorithms',
            'co_id' => 'CO1',
            'status' => 'Completed',
            'actual_date' => '2026-09-20',
            'actual_hours' => 1,
        ]);

        $logId = DB::table('class_logs_attendance')->insertGetId([
            'batch_subject_id' => $batchSubject->id,
            'date' => '2026-09-20',
            'period' => 1,
            'lesson_plan_id' => $lessonPlan->id,
            'topics_covered' => 'Introduction to Algorithms',
            'present_students' => json_encode(['2601010001']),
            'absent_students' => json_encode(['2601010002']),
            'recorded_by' => $staff->mobile_no,
            'sub_batch' => 'Whole',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('student_attendance')->insert([
            [
                'reg_no' => '2601010001',
                'subject_code' => '1001',
                'date' => '2026-09-20',
                'status' => 'Present',
                'lesson_plan_id' => $lessonPlan->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'reg_no' => '2601010002',
                'subject_code' => '1001',
                'date' => '2026-09-20',
                'status' => 'Absent',
                'lesson_plan_id' => $lessonPlan->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        return compact('staff', 'classroom', 'batchSubject', 'student1', 'student2', 'lessonPlan', 'logId');
    }

    public function test_check_attendance_session_exists(): void
    {
        $ctx = $this->createAttendanceContext();

        // 1. Existing session
        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->postJson('/api/staff/attendance/check-session', [
            'batch_subject_id' => $ctx['batchSubject']->id,
            'date' => '2026-09-20',
            'period' => 1,
            'sub_batch' => 'Whole',
        ]);

        $res->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'exists' => true,
            ]);
        $this->assertEquals($ctx['logId'], $res->json('log.id'));

        // 2. Non-existent session
        $res2 = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->postJson('/api/staff/attendance/check-session', [
            'batch_subject_id' => $ctx['batchSubject']->id,
            'date' => '2026-09-21',
            'period' => 2,
            'sub_batch' => 'Whole',
        ]);

        $res2->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'exists' => false,
                'log' => null,
            ]);
    }

    public function test_delete_class_log_reverts_lesson_plan_and_cleans_student_attendance(): void
    {
        $ctx = $this->createAttendanceContext();

        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->deleteJson("/api/staff/attendance/class-log/{$ctx['logId']}");

        $res->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
            ]);

        // Verify log is deleted
        $this->assertDatabaseMissing('class_logs_attendance', ['id' => $ctx['logId']]);

        // Verify lesson plan was reverted to Pending
        $lp = LessonPlan::find($ctx['lessonPlan']->id);
        $this->assertEquals('Pending', $lp->status);
        $this->assertNull($lp->actual_date);

        // Verify student_attendance records for this session were cleaned up
        $this->assertEquals(0, DB::table('student_attendance')->where('date', '2026-09-20')->where('subject_code', '1001')->count());
    }

    public function test_get_tutor_attendance_summary(): void
    {
        $ctx = $this->createAttendanceContext();

        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Tutor',
        ])->getJson("/api/tutor/attendance/summary/{$ctx['classroom']->classroom_id}");

        $res->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'classroom_id' => $ctx['classroom']->classroom_id,
                'total_students' => 2,
                'total_conducted' => 1,
            ]);

        $students = $res->json('students');
        $this->assertCount(2, $students);
        // Alice attended 1/1 = 100%
        $this->assertEquals('2601010001', $students[0]['reg_no']);
        $this->assertEquals(100.0, $students[0]['attendance_percentage']);
        $this->assertFalse($students[0]['is_shortage']);

        // Bob attended 0/1 = 0% (shortage)
        $this->assertEquals('2601010002', $students[1]['reg_no']);
        $this->assertEquals(0.0, $students[1]['attendance_percentage']);
        $this->assertTrue($students[1]['is_shortage']);
    }

    public function test_export_attendance_register_csv(): void
    {
        $ctx = $this->createAttendanceContext();

        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->get("/staff/attendance/export-register-csv/{$ctx['batchSubject']->id}");

        $res->assertStatus(200);
        $res->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $content = $res->streamedContent();
        $this->assertStringContainsString('Roll No', $content);
        $this->assertStringContainsString('Register No', $content);
        $this->assertStringContainsString('Student Name', $content);
        $this->assertStringContainsString('2601010001', $content);
        $this->assertStringContainsString('Alice Smith', $content);
        $this->assertStringContainsString('2601010002', $content);
        $this->assertStringContainsString('Bob Jones', $content);
        $this->assertStringContainsString('100%', $content);
        $this->assertStringContainsString('0%', $content);
    }
}
