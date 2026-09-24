<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\CourseFile;
use App\Models\LessonPlan;
use App\Models\PrincipalScheduledEvent;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\SubjectStaffAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Phase5ParityReconciliationTest extends TestCase
{
    use RefreshDatabase;

    private function createAcademicFixture(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Reconciled Faculty',
            'email' => 'parity@example.com',
            'password' => bcrypt('secret123'),
            'designation' => 'Lecturer',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
            'remember_token' => 'test_token_abc_123'
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_PARITY_2026',
            'branch' => 'CT',
            'batch_year' => 2026,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'I',
        ]);

        $batchSubject = BatchSubject::create([
            'classroom_id' => 'CR_PARITY_2026',
            'subject_code' => '4001',
            'subject_name' => 'Advanced Operating Systems',
            'subject_type' => 'Theory',
            'semester' => 4,
        ]);

        SubjectStaffAssignment::create([
            'batch_subject_id' => $batchSubject->id,
            'staff_mobile_no' => $staff->mobile_no,
            'role' => 'Primary',
        ]);

        $student = Student::create([
            'reg_no' => 'REG2026001',
            'adm_no' => 'ADM2026001',
            'email' => 'student.parity@example.com',
            'name' => 'Student Parity',
            'phone' => '9876540001',
            'branch' => 'CT',
            'admission_year' => 2026,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_PARITY_2026',
            'semester' => 'I',
            'status' => 'APPROVED',
            'sbte_reg_no' => 'REG2026001',
            'roll_no' => 1,
            'password' => bcrypt('student123'),
        ]);

        return compact('staff', 'classroom', 'batchSubject', 'student');
    }

    public function test_auto_login_via_token(): void
    {
        $fixture = $this->createAcademicFixture();

        $response = $this->withHeader('X-Remember-Token', 'test_token_abc_123')
            ->postJson('/api/auth/auto-login', [
                'token' => 'test_token_abc_123',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'role' => 'Lecturer',
        ]);
        $this->assertEquals($fixture['staff']->mobile_no, session('userId'));
    }

    public function test_today_campus_event_api(): void
    {
        $fixture = $this->createAcademicFixture();

        PrincipalScheduledEvent::create([
            'title' => 'Annual College Tech Fest',
            'event_type' => 'TECH_FEST',
            'event_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'target_audience' => 'ALL_CAMPUS',
            'is_published' => true,
            'suspension_type' => 'full_day',
        ]);

        $response = $this->withSession([
            'userId' => $fixture['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userBranch' => 'CT',
        ])->getJson('/api/campus-event/today');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'has_event' => true,
        ]);
        $this->assertEquals('Annual College Tech Fest', $response->json('event.title'));
    }

    public function test_practical_attendance_log_api(): void
    {
        $fixture = $this->createAcademicFixture();

        DB::table('class_logs_attendance')->insert([
            'batch_subject_id' => $fixture['batchSubject']->id,
            'date' => now()->toDateString(),
            'period' => 1,
            'topics_covered' => 'Kernel Module Compilation',
            'sub_batch' => 'Whole',
            'present_students' => json_encode(['REG2026001']),
            'absent_students' => json_encode([]),
            'recorded_by' => $fixture['staff']->mobile_no,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession([
            'userId' => $fixture['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->getJson("/api/classroom/{$fixture['batchSubject']->id}/practical/attendance-log");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'total_sessions' => 1,
        ]);
    }

    public function test_practical_cia_summary_save(): void
    {
        $fixture = $this->createAcademicFixture();

        $response = $this->withSession([
            'userId' => $fixture['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->postJson("/api/classroom/{$fixture['batchSubject']->id}/practical/cia-summary", [
            'reg_no' => 'REG2026001',
            'open_ended_mark' => 6.5,
            'open_ended_topic' => 'Device Driver Implementation',
            'test1' => 32,
            'test2' => 35,
            'attendance_mark' => 14,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'reg_no' => 'REG2026001',
                'open_ended_mark' => 6.5,
                'open_ended_topic' => 'Device Driver Implementation',
            ]
        ]);
    }

    public function test_theory_ese_marks_and_attainment_summary(): void
    {
        $fixture = $this->createAcademicFixture();

        CourseFile::create([
            'batch_subject_id' => $fixture['batchSubject']->id,
            'academic_year' => '2026-2027',
            'status' => 'Draft',
            'attainment_settings' => [
                'ese_config' => [
                    'max_marks' => 75,
                    'ese_threshold_grade' => 'D',
                    'ese_threshold_percent' => 50,
                    'target_student_percent' => 70,
                ]
            ]
        ]);

        // ESE marks GET
        $responseEse = $this->withSession([
            'userId' => $fixture['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->getJson("/api/classroom/{$fixture['batchSubject']->id}/ese-marks");

        $responseEse->assertStatus(200);
        $responseEse->assertJson([
            'status' => 'SUCCESS',
        ]);

        // Attainment summary GET
        $responseAtt = $this->withSession([
            'userId' => $fixture['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->getJson("/api/classroom/{$fixture['batchSubject']->id}/attainment-summary");

        $responseAtt->assertStatus(200);
        $responseAtt->assertJson([
            'status' => 'SUCCESS',
        ]);
    }

    public function test_delete_lesson_plan_row_in_practical_and_practicum(): void
    {
        $fixture = $this->createAcademicFixture();

        $plan1 = LessonPlan::create([
            'batch_subject_id' => $fixture['batchSubject']->id,
            'day_no' => 1,
            'co_id' => 'CO1',
            'topic_content' => 'Overview of OS Kernels',
            'allocated_hours' => 1,
            'pedagogy' => 'Lecture',
            'status' => 'Pending',
        ]);

        $responseDel1 = $this->withSession([
            'userId' => $fixture['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->deleteJson("/api/r26/classroom/practical/{$fixture['batchSubject']->id}/lesson-plans/{$plan1->id}");

        $responseDel1->assertStatus(200);
        $responseDel1->assertJson(['status' => 'SUCCESS']);
        $this->assertDatabaseMissing('lesson_plans', ['id' => $plan1->id]);

        $plan2 = LessonPlan::create([
            'batch_subject_id' => $fixture['batchSubject']->id,
            'day_no' => 2,
            'co_id' => 'CO2',
            'topic_content' => 'Process Synchronization',
            'allocated_hours' => 1,
            'pedagogy' => 'Lecture',
            'status' => 'Pending',
        ]);

        $responseDel2 = $this->withSession([
            'userId' => $fixture['staff']->mobile_no,
            'userRole' => 'Lecturer',
        ])->postJson("/api/r26/classroom/practicum/{$fixture['batchSubject']->id}/lesson-plan/delete", [
            'ids' => [$plan2->id]
        ]);

        $responseDel2->assertStatus(200);
        $responseDel2->assertJson(['status' => 'SUCCESS']);
        $this->assertDatabaseMissing('lesson_plans', ['id' => $plan2->id]);
    }

    public function test_sf_attendance_routes(): void
    {
        $fixture = $this->createAcademicFixture();

        $responsePunch = $this->withSession([
            'userId' => $fixture['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userName' => $fixture['staff']->name,
        ])->get('/sf-attendance/face-punch');

        $responsePunch->assertStatus(200);

        $responseReport = $this->withSession([
            'userId' => $fixture['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userName' => $fixture['staff']->name,
        ])->get('/sf-attendance/attendance-report');

        $responseReport->assertStatus(200);
    }
}
