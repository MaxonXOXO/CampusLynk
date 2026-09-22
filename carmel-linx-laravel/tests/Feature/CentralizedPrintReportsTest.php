<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\StaffProfile;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CentralizedPrintReportsTest extends TestCase
{
    use RefreshDatabase;

    private function createAcademicContext(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Academic In-Charge',
            'email' => 'academic@example.com',
            'password' => 'secret',
            'designation' => 'HOD',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_PRINT_TEST',
            'branch' => 'CT',
            'batch_year' => 2026,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'IV',
        ]);

        $batchSubject = BatchSubject::create([
            'classroom_id' => 'CR_PRINT_TEST',
            'subject_code' => '4001',
            'subject_name' => 'Operating Systems',
            'subject_type' => 'Theory',
            'semester' => 4,
        ]);

        $student = Student::create([
            'reg_no' => '2601019999',
            'adm_no' => 'ADM9999',
            'name' => 'Clark Kent',
            'email' => 'clark@example.com',
            'password' => 'secret',
            'phone' => '9876599999',
            'branch' => 'CT',
            'admission_year' => 2026,
            'admission_type' => 'REGULAR',
            'classroom_id' => 'CR_PRINT_TEST',
            'semester' => 'IV',
            'status' => 'APPROVED',
            'sbte_reg_no' => '2601019999',
            'roll_no' => 1,
        ]);

        return compact('staff', 'classroom', 'batchSubject', 'student');
    }

    public function test_shared_report_layout_components_render(): void
    {
        $viewHeader = view('reports.partials.header', [
            'title' => 'TEST STATUTORY REPORT',
            'subtitle' => 'Test Subtitle',
            'department' => 'Computer Engineering',
            'academicYear' => '2026-2027',
            'semester' => 'Semester IV',
            'documentNo' => 'DOC/TEST/001'
        ])->render();

        $this->assertStringContainsString('TEST STATUTORY REPORT', $viewHeader);
        $this->assertStringContainsString('Department of Computer Engineering', $viewHeader);
        $this->assertStringContainsString('DOC: DOC/TEST/001', $viewHeader);

        $viewSigs = view('reports.partials.signatures', [
            'facultyLabel' => 'Course Faculty',
            'hodLabel' => 'HOD CT',
            'principalLabel' => 'Principal CPC'
        ])->render();

        $this->assertStringContainsString('Course Faculty', $viewSigs);
        $this->assertStringContainsString('HOD CT', $viewSigs);
        $this->assertStringContainsString('Principal CPC', $viewSigs);

        $viewInst = view('reports.partials.institution-details')->render();
        $this->assertStringContainsString('Carmel Polytechnic College', $viewInst);
    }

    public function test_classroom_assignment_print_renders_with_report_layout(): void
    {
        $ctx = $this->createAcademicContext();

        $view = view('classroom_assignment_print', [
            'subject' => $ctx['batchSubject'],
            'coTag' => 'CO1',
            'topicName' => 'Process Scheduling Algorithms',
            'fullDepartment' => 'Computer Engineering',
            'romanSem' => 'IV',
            'cleanedBatch' => '2026-2029',
            'assessmentYear' => '2026-2027',
            'dueDate' => '15-10-2026',
            'questions' => [
                ['q_no' => 1, 'question' => 'Explain Round Robin scheduling with Gantt chart.', 'bt_level' => 'Apply', 'marks' => 10],
                ['q_no' => 2, 'question' => 'Differentiate preemptive vs non-preemptive scheduling.', 'bt_level' => 'Understand', 'marks' => 10],
            ],
            'rememberCount' => 0,
            'understandCount' => 1,
            'applyCount' => 1,
            'totalQuestions' => 2,
        ])->render();

        $this->assertStringContainsString('Assignment Printout', $view);
        $this->assertStringContainsString('Process Scheduling Algorithms', $view);
        $this->assertStringContainsString('Round Robin scheduling', $view);
        $this->assertStringContainsString('SCHEME OF EVALUATION', $view);
        $this->assertStringContainsString('RUBRICS', $view);
    }

    public function test_r26_student_final_results_print_renders_with_report_layout(): void
    {
        $ctx = $this->createAcademicContext();

        $view = view('r26.student_final_results_print', [
            'batchSubject' => $ctx['batchSubject'],
            'classroom' => $ctx['classroom'],
            'studentCiaData' => [
                [
                    'roll_no' => 1,
                    'sbte_reg_no' => '2601019999',
                    'reg_no' => '2601019999',
                    'name' => 'Clark Kent',
                    'attendance_percent' => 95.0,
                    'total_cia' => 38.0,
                    'ese_marks' => 54.0,
                ]
            ]
        ])->render();

        $this->assertStringContainsStringIgnoringCase('Consolidated Theory ESE', $view);
        $this->assertStringContainsString('Clark Kent', $view);
        $this->assertStringContainsString('PASSED', $view);
        $this->assertStringContainsString('Grade Distribution', $view);
    }

    public function test_hod_academic_calendar_print_renders_with_report_layout(): void
    {
        $ctx = $this->createAcademicContext();

        $cal = (object)[
            'semester' => 4,
            'academic_year' => '2026-2027',
            'pdf_path' => null
        ];

        $view = view('hod_academic_calendar_print', [
            'cal' => $cal,
            'branch' => 'CT',
            'activities' => [
                ['month' => 'July', 'date' => 15, 'activity' => 'Commencement of Classes', 'type' => 'Academic'],
                ['month' => 'August', 'date' => 15, 'activity' => 'Independence Day', 'type' => 'Holiday'],
            ]
        ])->render();

        $this->assertStringContainsString('Academic Calendar', $view);
        $this->assertStringContainsString('Commencement of Classes', $view);
        $this->assertStringContainsString('Independence Day', $view);
        $this->assertStringContainsString('Working Days Summary', $view);
    }

    public function test_student_mentoring_diary_print_renders_with_report_layout(): void
    {
        $ctx = $this->createAcademicContext();

        $view = view('student_mentoring_diary_print', [
            'student' => $ctx['student'],
            'extended_profile' => (object)[
                'gender' => 'Male',
                'religion' => 'General',
                'caste' => 'None',
                'special_category' => 'None',
                'reservation' => 'None',
                'quota' => 'General',
                'is_physically_disabled' => false,
                'disability_category' => null,
                'has_vehicle_pass' => false,
                'vehicle_pass_id' => null,
                'communication_address' => 'Metropolis, Planet Earth',
                'father_name' => 'Jonathan Kent',
                'father_occupation' => 'Farmer',
                'mother_name' => 'Martha Kent',
                'mother_occupation' => 'Homemaker',
                'annual_income' => 120000,
            ],
            'education' => [
                (object)[
                    'course' => 'SSLC',
                    'institution' => 'Smallville High',
                    'year_of_completion' => '2024',
                    'total_percentage' => '92.5%',
                ]
            ],
            'board' => [],
            'academics' => [],
            'extracurricular' => [],
            'meetings' => [],
        ])->render();

        $this->assertStringContainsStringIgnoringCase('Student Mentoring Diary', $view);
        $this->assertStringContainsString('Clark Kent', $view);
        $this->assertStringContainsString('Jonathan Kent', $view);
        $this->assertStringContainsString('Smallville High', $view);
    }

    public function test_hod_sbte_audit_print_renders_with_report_layout(): void
    {
        $view = view('hod_sbte_audit_print', [
            'academicYear' => '2026-2027',
            'department' => 'Computer Engineering',
            'audit' => null,
            'auditData' => [
                'nba_accredited' => true,
                'professional_activities' => [
                    'hod_name' => 'Dr. Thomas Mathew',
                    'faculty_count' => 12,
                ],
                'enrollment' => [],
                'perf_no_backlog' => [],
                'perf_with_backlog' => [],
                'placement' => [],
                'sfr' => ['CAY' => '1:15', 'CAY-1' => '1:15'],
                'infrastructure' => [],
                'vision_mission' => [
                    'vision' => 'Excellence in Technical Education',
                    'mission' => 'Empowering rural youth',
                ],
                'teaching_learning' => [],
                'course_files' => [],
                'faculty_training' => [],
                'fdp_conducted' => [],
                'consultancy' => [],
                'achievements' => [],
            ],
            'currentDate' => '22-09-2026'
        ])->render();

        $this->assertStringContainsString('DIRECTORATE OF TECHNICAL EDUCATION', $view);
        $this->assertStringContainsString('Academic Audit', $view);
        $this->assertStringContainsString('Dr. Thomas Mathew', $view);
        $this->assertStringContainsString('Excellence in Technical Education', $view);
    }
}
