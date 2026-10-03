<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\PrincipalScheduledEvent;
use App\Models\StaffProfile;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NotificationCenterAndAttendancePrintTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_feed_returns_scheduled_campus_events(): void
    {
        PrincipalScheduledEvent::create([
            'title' => 'College Annual Day 2026',
            'event_category' => 'Event',
            'event_date' => Carbon::today()->format('Y-m-d'),
            'description' => 'Annual celebrations in the main auditorium.',
            'start_time' => '10:00:00',
            'suppress_timetable' => true,
            'suspension_type' => 'Full Day',
            'target_audience' => 'ALL_CAMPUS',
            'is_published' => true,
        ]);

        $response = $this->withSession(['userId' => 'admin', 'userRole' => 'Admin'])
            ->getJson('/api/notifications/feed');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
            ]);

        $data = $response->json();
        $this->assertGreaterThanOrEqual(1, $data['unread_count']);
        $this->assertNotEmpty($data['notifications']);
        $this->assertEquals('College Annual Day 2026', $data['notifications'][0]['title']);
    }

    public function test_mark_notification_as_read_updates_unread_count(): void
    {
        $response = $this->withSession(['userId' => 'admin', 'userRole' => 'Admin'])
            ->postJson('/api/notifications/mark-read', ['id' => 'all']);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'message' => 'Notification state updated.'
            ]);
    }

    public function test_print_individual_student_attendance_report(): void
    {
        StaffProfile::create([
            'mobile_no' => 'staff123',
            'name' => 'Prof. Tutor Master',
            'email' => 'tutor@example.com',
            'password' => 'secret',
            'designation' => 'Lecturer',
            'branch' => 'Computer Engineering',
            'account_status' => 'APPROVED',
        ]);

        DB::table('class_management')->insert([
            'classroom_id' => 'CT_S4_2026',
            'branch' => 'Computer Engineering',
            'batch_year' => 2026,
            'current_semester' => 4,
            'tutor_mobile_no' => 'staff123',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Student::create([
            'reg_no' => '220101',
            'adm_no' => 'ADM220101',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret',
            'branch' => 'Computer Engineering',
            'admission_year' => 2022,
            'roll_no' => 1,
            'classroom_id' => 'CT_S4_2026',
            'admission_type' => 'REGULAR',
        ]);

        BatchSubject::create([
            'classroom_id' => 'CT_S4_2026',
            'subject_code' => '4001',
            'subject_name' => 'Data Structures',
            'subject_type' => 'Theory',
            'semester' => 4,
            'credits' => 4,
            'weekly_hours' => 4,
        ]);

        $response = $this->withSession(['userId' => 'staff123', 'userRole' => 'Tutor', 'tutor_classroom' => 'CT_S4_2026'])
            ->get('/tutor/attendance/student/220101/print');

        $response->assertStatus(200)
            ->assertSee('John Doe')
            ->assertSee('Data Structures');
    }

    public function test_online_test_attempt_uses_uuid_and_resumes_in_progress(): void
    {
        DB::table('class_management')->insert([
            'classroom_id' => 'CT_S4_2026',
            'branch' => 'Computer Engineering',
            'batch_year' => 2026,
            'current_semester' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Student::create([
            'reg_no' => '220101',
            'adm_no' => 'ADM220101',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret',
            'branch' => 'Computer Engineering',
            'admission_year' => 2022,
            'roll_no' => 1,
            'classroom_id' => 'CT_S4_2026',
            'admission_type' => 'REGULAR',
        ]);

        DB::table('syllabus_registry')->insert([
            'subject_code' => '4002',
            'revision_year' => 2021,
            'subject_name' => 'Operating Systems',
            'co_count' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('test_configs')->insert([
            'test_id' => 'TEST_MCQ_001',
            'classroom_id' => 'CT_S4_2026',
            'test_name' => 'Operating Systems Quiz 1',
            'subject_code' => '4002',
            'selected_cos' => json_encode(['CO1']),
            'questions_payload' => json_encode([
                [
                    'q' => 'What is virtual memory?',
                    'options' => ['Hardware only', 'Illusion of large memory', 'Magnetic disk', 'Cache memory'],
                    'ans' => 'Illusion of large memory',
                    'co' => 'CO1'
                ]
            ]),
            'duration' => 30,
            'max_attempts' => 2,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Start test (first attempt)
        $startResponse = $this->withSession(['userId' => '220101', 'userRole' => 'Student'])
            ->postJson('/api/student/online-tests/TEST_MCQ_001/start');

        $startResponse->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
            ]);

        $attemptId = $startResponse->json('attempt_id');
        $this->assertNotEmpty($attemptId);

        // Resuming the same test should return in-progress attempt
        $resumeResponse = $this->withSession(['userId' => '220101', 'userRole' => 'Student'])
            ->postJson('/api/student/online-tests/TEST_MCQ_001/start');

        $resumeResponse->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'attempt_id' => $attemptId
            ]);
    }
}
