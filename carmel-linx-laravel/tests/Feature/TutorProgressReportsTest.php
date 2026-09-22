<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\StaffProfile;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TutorProgressReportsTest extends TestCase
{
    use RefreshDatabase;

    private function createTutorContext(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Tutor Master',
            'email' => 'tutor@example.com',
            'password' => 'secret',
            'designation' => 'Lecturer',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_TUTOR_TEST_2026',
            'branch' => 'CT',
            'batch_year' => 2026,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'IV',
        ]);

        $subj1 = BatchSubject::create([
            'classroom_id' => $classroom->classroom_id,
            'subject_code' => '4041',
            'subject_name' => 'Operating Systems',
            'subject_type' => 'Theory',
            'semester' => 4,
        ]);

        $subj2 = BatchSubject::create([
            'classroom_id' => $classroom->classroom_id,
            'subject_code' => '4042',
            'subject_name' => 'Database Management',
            'subject_type' => 'Theory',
            'semester' => 4,
        ]);

        $student1 = Student::create([
            'reg_no' => '2601010001',
            'adm_no' => 'ADM001',
            'roll_no' => 1,
            'name' => 'Alice Student',
            'email' => 'alice@example.com',
            'password' => 'secret',
            'phone' => '9876500001',
            'guardian_mobile' => '9876500099',
            'classroom_id' => $classroom->classroom_id,
            'branch' => 'CT',
            'admission_year' => 2026,
        ]);

        $student2 = Student::create([
            'reg_no' => '2601010002',
            'adm_no' => 'ADM002',
            'roll_no' => 2,
            'name' => 'Bob Student',
            'email' => 'bob@example.com',
            'password' => 'secret',
            'phone' => '9876500002',
            'guardian_mobile' => '9876500098',
            'classroom_id' => $classroom->classroom_id,
            'branch' => 'CT',
            'admission_year' => 2026,
        ]);

        // Add some attendance logs
        DB::table('class_logs_attendance')->insert([
            'batch_subject_id' => $subj1->id,
            'date' => '2026-03-01',
            'period' => 1,
            'topics_covered' => 'Intro to OS',
            'present_students' => json_encode(['2601010001', '2601010002']),
            'recorded_by' => 'Prof. Tutor Master',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('class_logs_attendance')->insert([
            'batch_subject_id' => $subj1->id,
            'date' => '2026-03-02',
            'period' => 2,
            'topics_covered' => 'Process Management',
            'present_students' => json_encode(['2601010001']),
            'recorded_by' => 'Prof. Tutor Master',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return compact('staff', 'classroom', 'subj1', 'subj2', 'student1', 'student2');
    }

    public function test_get_progress_report_data_returns_comprehensive_classroom_metrics()
    {
        $ctx = $this->createTutorContext();

        $response = $this->withSession(['userId' => $ctx['staff']->mobile_no, 'userRole' => 'Faculty'])
            ->getJson("/api/tutor/progress-report?classroom_id={$ctx['classroom']->classroom_id}");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'classroom' => [
                'id' => $ctx['classroom']->classroom_id,
                'branch_code' => 'CT',
                'semester' => 4,
            ],
            'summary' => [
                'total_students' => 2,
                'subjects_count' => 2,
            ]
        ]);

        $data = $response->json();
        $this->assertCount(2, $data['students']);
        // Alice attended 2/2 -> 100%
        $this->assertEquals(100.0, $data['students'][0]['overall_attendance']);
        // Bob attended 1/2 -> 50%
        $this->assertEquals(50.0, $data['students'][1]['overall_attendance']);
        $this->assertEquals('Detained', $data['students'][1]['status']);
    }

    public function test_print_progress_report_consolidated_renders()
    {
        $ctx = $this->createTutorContext();

        $response = $this->withSession(['userId' => $ctx['staff']->mobile_no])
            ->get("/tutor/progress-report/print?classroom_id={$ctx['classroom']->classroom_id}");

        $response->assertStatus(200);
        $response->assertSee('Carmel Polytechnic College, Alappuzha');
        $response->assertSee('Consolidated Student Progress & Academic Performance Broadsheet', false);
        $response->assertSee('Alice Student');
        $response->assertSee('Bob Student');
        $response->assertSee('Class Tutor / Advisor');
    }

    public function test_print_student_progress_card_renders()
    {
        $ctx = $this->createTutorContext();

        $response = $this->withSession(['userId' => $ctx['staff']->mobile_no])
            ->get("/tutor/progress-report/student/{$ctx['student1']->reg_no}/print?classroom_id={$ctx['classroom']->classroom_id}");

        $response->assertStatus(200);
        $response->assertSee('Carmel Polytechnic College, Alappuzha');
        $response->assertSee('Student Continuous Assessment & Progress Report Card', false);
        $response->assertSee('Alice Student');
        $response->assertSee('2601010001');
        $response->assertSee('Parent / Guardian Signature');
    }

    public function test_print_consolidated_attendance_renders()
    {
        $ctx = $this->createTutorContext();

        $response = $this->withSession(['userId' => $ctx['staff']->mobile_no])
            ->get("/tutor/attendance/consolidated-print?classroom_id={$ctx['classroom']->classroom_id}");

        $response->assertStatus(200);
        $response->assertSee('Carmel Polytechnic College, Alappuzha');
        $response->assertSee('Consolidated Student Attendance Register', false);
        $response->assertSee('Alice Student');
        $response->assertSee('Bob Student');
        $response->assertSee('Total Enrolled:');
    }
}
