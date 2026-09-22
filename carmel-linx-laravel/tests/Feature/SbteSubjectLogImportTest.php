<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\StaffProfile;
use App\Services\SbteSubjectLogImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SbteSubjectLogImportTest extends TestCase
{
    use RefreshDatabase;

    private function createSubjectContext(): BatchSubject
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Dr. Jane Teacher',
            'email' => 'jane@example.com',
            'password' => 'secret',
            'designation' => 'Lecturer',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_SBTE_TEST',
            'branch' => 'CT',
            'batch_year' => 2026,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'IV',
        ]);

        return BatchSubject::create([
            'classroom_id' => $classroom->classroom_id,
            'subject_code' => '4041',
            'subject_name' => 'Operating Systems',
            'subject_type' => 'Theory',
            'semester' => 4,
        ]);
    }

    public function test_service_parse_text_correctly_extracts_metadata_and_sessions()
    {
        $rawText = <<<TEXT
Programme: Computer Engineering
Course: Operating Systems (4041) Semester: 4
Faculty: Dr. Jane Teacher
1. 15-01-2026 1, 2 Introduction to Operating Systems, Kernel vs User Mode
2. 16-01-2026 3 Process Concept, PCB, and Process States
3. 18-01-2026 4, 5 CPU Scheduling Algorithms (FCFS, SJF, Round Robin)
TEXT;

        $service = new SbteSubjectLogImportService();
        $result = $service->parseText($rawText);

        $this->assertEquals('SUCCESS', $result['status']);
        $this->assertEquals('Computer Engineering', $result['programme']);
        $this->assertEquals('Operating Systems', $result['course_title']);
        $this->assertEquals('4041', $result['course_code']);
        $this->assertEquals('4', $result['semester']);
        $this->assertEquals('Dr. Jane Teacher', $result['faculty']);
        $this->assertEquals(3, $result['total_sessions']);
        $this->assertEquals(5, $result['total_hours']);

        $sessions = $result['sessions'];
        $this->assertCount(3, $sessions);

        // Session 1
        $this->assertEquals(1, $sessions[0]['sl_no']);
        $this->assertEquals('2026-01-15', $sessions[0]['date']);
        $this->assertEquals([1, 2], $sessions[0]['hours']);
        $this->assertEquals(2, $sessions[0]['hours_count']);
        $this->assertStringContainsString('Introduction to Operating Systems', $sessions[0]['contents']);

        // Session 2
        $this->assertEquals(2, $sessions[1]['sl_no']);
        $this->assertEquals('2026-01-16', $sessions[1]['date']);
        $this->assertEquals([3], $sessions[1]['hours']);
        $this->assertEquals(1, $sessions[1]['hours_count']);
        $this->assertStringContainsString('Process Concept', $sessions[1]['contents']);

        // Session 3
        $this->assertEquals(3, $sessions[2]['sl_no']);
        $this->assertEquals('2026-01-18', $sessions[2]['date']);
        $this->assertEquals([4, 5], $sessions[2]['hours']);
        $this->assertEquals(2, $sessions[2]['hours_count']);
        $this->assertStringContainsString('CPU Scheduling Algorithms', $sessions[2]['contents']);
    }

    public function test_parse_api_endpoint_with_valid_text()
    {
        $rawText = <<<TEXT
Programme: Computer Engineering
Course: Operating Systems (4041) Semester: 4
Faculty: Dr. Jane Teacher
1. 10-02-2026 1 Memory Management Architecture
TEXT;

        $response = $this->postJson('/api/sbte-log/parse', [
            'raw_text' => $rawText
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'status' => 'SUCCESS',
                'course_code' => '4041',
                'total_sessions' => 1,
            ]
        ]);
    }

    public function test_parse_api_endpoint_validation_error()
    {
        $response = $this->postJson('/api/sbte-log/parse', []);
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Please provide a PDF file or raw text to parse.'
        ]);
    }

    public function test_import_sessions_creates_class_logs_records()
    {
        $subject = $this->createSubjectContext();
        $service = new SbteSubjectLogImportService();

        $sessions = [
            [
                'sl_no' => 1,
                'date' => '2026-01-15',
                'hours' => [1, 2],
                'contents' => 'Introduction to Operating Systems',
            ],
            [
                'sl_no' => 2,
                'date' => '2026-01-16',
                'hours' => [3],
                'contents' => 'Process States',
            ]
        ];

        $result = $service->importSessions($subject->id, $sessions, 'Prof. Jane', false);

        $this->assertTrue($result['success']);
        $this->assertEquals(3, $result['imported']); // 2 hours for session 1 + 1 hour for session 2
        $this->assertEquals(0, $result['updated']);
        $this->assertEquals(0, $result['skipped']);

        // Verify records in class_logs_attendance
        $logs = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $subject->id)
            ->orderBy('date')
            ->orderBy('period')
            ->get();

        $this->assertCount(3, $logs);
        $this->assertEquals('2026-01-15', $logs[0]->date);
        $this->assertEquals(1, $logs[0]->period);
        $this->assertEquals('Introduction to Operating Systems', $logs[0]->topics_covered);
        $this->assertEquals('Prof. Jane', $logs[0]->recorded_by);

        $this->assertEquals('2026-01-15', $logs[1]->date);
        $this->assertEquals(2, $logs[1]->period);

        $this->assertEquals('2026-01-16', $logs[2]->date);
        $this->assertEquals(3, $logs[2]->period);
        $this->assertEquals('Process States', $logs[2]->topics_covered);
    }

    public function test_import_sessions_handles_overwrite_flag_correctly()
    {
        $subject = $this->createSubjectContext();
        $service = new SbteSubjectLogImportService();

        // Pre-insert an existing class log
        DB::table('class_logs_attendance')->insert([
            'batch_subject_id' => $subject->id,
            'date' => '2026-01-15',
            'period' => 1,
            'topics_covered' => 'Old Topic',
            'recorded_by' => 'Old Faculty',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sessions = [
            [
                'sl_no' => 1,
                'date' => '2026-01-15',
                'hours' => [1, 2],
                'contents' => 'Updated Topic via SBTE',
            ]
        ];

        // 1. Overwrite = false -> should skip period 1, insert period 2
        $resultNoOverwrite = $service->importSessions($subject->id, $sessions, 'New Faculty', false);
        $this->assertEquals(1, $resultNoOverwrite['imported']);
        $this->assertEquals(1, $resultNoOverwrite['skipped']);
        $this->assertEquals(0, $resultNoOverwrite['updated']);

        $logPeriod1 = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $subject->id)
            ->where('date', '2026-01-15')
            ->where('period', 1)
            ->first();
        $this->assertEquals('Old Topic', $logPeriod1->topics_covered);

        // 2. Overwrite = true -> should update period 1
        $resultOverwrite = $service->importSessions($subject->id, $sessions, 'New Faculty', true);
        $this->assertEquals(0, $resultOverwrite['imported']);
        $this->assertEquals(0, $resultOverwrite['skipped']);
        $this->assertEquals(2, $resultOverwrite['updated']); // both period 1 and 2 updated

        $updatedLogPeriod1 = DB::table('class_logs_attendance')
            ->where('batch_subject_id', $subject->id)
            ->where('date', '2026-01-15')
            ->where('period', 1)
            ->first();
        $this->assertEquals('Updated Topic via SBTE', $updatedLogPeriod1->topics_covered);
        $this->assertEquals('New Faculty', $updatedLogPeriod1->recorded_by);
    }

    public function test_import_api_endpoint_validation_and_persistence()
    {
        $subject = $this->createSubjectContext();

        $payload = [
            'sessions' => [
                [
                    'date' => '2026-02-01',
                    'hours' => [1],
                    'contents' => 'Deadlocks and Starvation',
                ]
            ],
            'recorded_by' => 'API Tester',
            'overwrite' => true,
        ];

        $response = $this->postJson("/classroom/{$subject->id}/sbte-log/import", $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'result' => [
                'imported' => 1,
                'updated' => 0,
                'skipped' => 0,
            ]
        ]);

        $this->assertDatabaseHas('class_logs_attendance', [
            'batch_subject_id' => $subject->id,
            'date' => '2026-02-01',
            'period' => 1,
            'topics_covered' => 'Deadlocks and Starvation',
            'recorded_by' => 'API Tester',
        ]);
    }
}
