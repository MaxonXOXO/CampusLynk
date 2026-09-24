<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\SubjectStaffAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Phase3StatutoryPrintRoutesTest extends TestCase
{
    use RefreshDatabase;

    private function createAcademicEnvironment(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Lab In-Charge',
            'email' => 'labincharge@example.com',
            'password' => 'secret',
            'designation' => 'Lecturer',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CT_2024_2027',
            'branch' => 'CT',
            'batch_year' => 2024,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'IV',
        ]);

        $practicalSubject = BatchSubject::create([
            'classroom_id' => 'CT_2024_2027',
            'subject_code' => '4008',
            'subject_name' => 'System Administration Lab',
            'subject_type' => 'Practical',
            'semester' => 4,
        ]);

        $theorySubject = BatchSubject::create([
            'classroom_id' => 'CT_2024_2027',
            'subject_code' => '4001',
            'subject_name' => 'Operating Systems',
            'subject_type' => 'Theory',
            'semester' => 4,
        ]);

        SubjectStaffAssignment::create([
            'batch_subject_id' => $practicalSubject->id,
            'staff_mobile_no' => $staff->mobile_no,
            'role' => 'Lab In-Charge',
        ]);

        SubjectStaffAssignment::create([
            'batch_subject_id' => $theorySubject->id,
            'staff_mobile_no' => $staff->mobile_no,
            'role' => 'Subject Faculty',
        ]);

        $student = Student::create([
            'reg_no' => '2401010001',
            'adm_no' => 'ADM001',
            'name' => 'Bruce Wayne',
            'email' => 'bruce@example.com',
            'password' => 'secret',
            'phone' => '9876500001',
            'branch' => 'CT',
            'admission_year' => 2024,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CT_2024_2027',
            'semester' => 4,
            'status' => 'APPROVED',
            'sbte_reg_no' => '2401010001',
            'roll_no' => 1,
        ]);

        // Add a practical experiment
        DB::table('practical_experiments')->insert([
            'batch_subject_id' => $practicalSubject->id,
            'experiment_no' => '1',
            'title' => 'Linux Installation and User Management',
            'co_tag' => 'CO1',
            'conducted_date' => '2026-03-10',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add class log for attendance
        DB::table('class_logs_attendance')->insert([
            'batch_subject_id' => $practicalSubject->id,
            'date' => '2026-03-10',
            'period' => 1,
            'topics_covered' => 'Exp 1: Linux Installation and User Management',
            'present_students' => json_encode(['2401010001']),
            'absent_students' => json_encode([]),
            'recorded_by' => $staff->mobile_no,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('class_logs_attendance')->insert([
            'batch_subject_id' => $theorySubject->id,
            'date' => '2026-03-11',
            'period' => 2,
            'topics_covered' => 'Introduction to Operating Systems & Kernels',
            'present_students' => json_encode(['2401010001']),
            'absent_students' => json_encode([]),
            'recorded_by' => $staff->mobile_no,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return compact('staff', 'classroom', 'practicalSubject', 'theorySubject', 'student');
    }

    public function test_practical_series_report_print(): void
    {
        $env = $this->createAcademicEnvironment();

        $res = $this->withSession([
            'userId' => $env['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userName' => 'Prof. Lab In-Charge',
        ])->get("/classroom/practical/{$env['practicalSubject']->id}/series-report/print");

        $res->assertStatus(200);
        $res->assertSee('System Administration Lab');
        $res->assertSee('Practical Series Examination Marksheet');
        $res->assertSee('Bruce Wayne');
    }

    public function test_practical_final_results_print(): void
    {
        $env = $this->createAcademicEnvironment();

        $res = $this->withSession([
            'userId' => $env['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userName' => 'Prof. Lab In-Charge',
        ])->get("/classroom/practical/{$env['practicalSubject']->id}/final-results/print");

        $res->assertStatus(200);
        $res->assertSee('System Administration Lab');
        $res->assertSee('Practical End-Semester Examination &amp; Consolidated Final Results', false);
        $res->assertSee('Bruce Wayne');
    }

    public function test_practical_experiments_log_print(): void
    {
        $env = $this->createAcademicEnvironment();

        $res = $this->withSession([
            'userId' => $env['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userName' => 'Prof. Lab In-Charge',
        ])->get("/classroom/practical/{$env['practicalSubject']->id}/experiments/print");

        $res->assertStatus(200);
        $res->assertSee('System Administration Lab');
        $res->assertSee('Practical Experiments Conducted &amp; Session Log Report', false);
        $res->assertSee('Linux Installation and User Management');
    }

    public function test_practical_student_report_print(): void
    {
        $env = $this->createAcademicEnvironment();

        $res = $this->withSession([
            'userId' => $env['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userName' => 'Prof. Lab In-Charge',
        ])->get("/classroom/practical/{$env['practicalSubject']->id}/student/{$env['student']->reg_no}/print");

        $res->assertStatus(200);
        $res->assertSee('Bruce Wayne');
        $res->assertSee('Individual Student Practical Continuous Evaluation &amp; Attendance Card', false);
        $res->assertSee('System Administration Lab');
    }

    public function test_theory_final_results_print(): void
    {
        $env = $this->createAcademicEnvironment();

        $res = $this->withSession([
            'userId' => $env['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userName' => 'Prof. Lab In-Charge',
        ])->get("/classroom/{$env['theorySubject']->id}/final-results/print");

        $res->assertStatus(200);
        $res->assertSee('Operating Systems');
        $res->assertSee('Continuous Internal Evaluation (CIE) &amp; Final Result Register', false);
        $res->assertSee('Bruce Wayne');
    }

    public function test_theory_class_roster_print(): void
    {
        $env = $this->createAcademicEnvironment();

        $res = $this->withSession([
            'userId' => $env['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userName' => 'Prof. Lab In-Charge',
        ])->get("/classroom/{$env['theorySubject']->id}/class-roster/print");

        $res->assertStatus(200);
        $res->assertSee('Operating Systems');
        $res->assertSee('Consolidated Theory Class Roster &amp; Attainment Register', false);
        $res->assertSee('Bruce Wayne');
    }

    public function test_theory_class_log_print(): void
    {
        $env = $this->createAcademicEnvironment();

        $res = $this->withSession([
            'userId' => $env['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userName' => 'Prof. Lab In-Charge',
        ])->get("/classroom/{$env['theorySubject']->id}/class-log/print");

        $res->assertStatus(200);
        $res->assertSee('Operating Systems');
        $res->assertSee('Official Classroom Teaching &amp; Attendance Log Register', false);
        $res->assertSee('Introduction to Operating Systems &amp; Kernels', false);
    }

    public function test_practical_lab_batch_setup_api(): void
    {
        $env = $this->createAcademicEnvironment();

        $res = $this->withSession([
            'userId' => $env['staff']->mobile_no,
            'userRole' => 'Lecturer',
            'userName' => 'Prof. Lab In-Charge',
        ])->getJson("/api/classroom/{$env['practicalSubject']->id}/practical/batch-setup");

        $res->assertStatus(200);
        $res->assertJsonStructure([
            'status',
            'students',
            'total_students',
        ]);
    }
}
