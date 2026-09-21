<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\BatchSubject;
use App\Models\LessonPlan;
use App\Models\R26PracticumCourseFile;

class R26PracticumExtensionsTest extends TestCase
{
    use RefreshDatabase;

    protected $batchSubject;

    protected function setUp(): void
    {
        parent::setUp();

        if (Schema::hasTable('class_management')) {
            DB::table('class_management')->updateOrInsert(
                ['classroom_id' => 'CR_PRACTICUM_01'],
                [
                    'branch' => 'ME',
                    'batch_year' => 2026,
                    'current_semester' => 1,
                    'tutor_mobile_no' => null
                ]
            );
        }

        if (Schema::hasTable('batch_subjects')) {
            $this->batchSubject = BatchSubject::create([
                'classroom_id' => 'CR_PRACTICUM_01',
                'subject_code' => '2001P',
                'subject_name' => 'Engineering Physics Practicum',
                'semester' => 1,
                'subject_type' => 'Practicum'
            ]);
        }
    }

    public function test_save_custom_experiments_roster_requires_auth()
    {
        $id = $this->batchSubject->id;
        $response = $this->postJson("/api/r26/classroom/practicum/{$id}/experiments/save", [
            'experiments' => [
                ['title' => 'Torsion Pendulum', 'code' => 'EXP-01', 'co_id' => 'CO1', 'hours' => 3]
            ]
        ]);

        $response->assertStatus(401);
    }

    public function test_save_custom_experiments_roster_saves_and_syncs()
    {
        $this->withSession(['userId' => 'FACULTY_01', 'userRole' => 'Lecturer']);
        $id = $this->batchSubject->id;

        $response = $this->postJson("/api/r26/classroom/practicum/{$id}/experiments/save", [
            'experiments' => [
                [
                    'session_code' => 'Sess 01',
                    'code' => 'EXP-01',
                    'title' => 'Torsion Pendulum Experiment',
                    'co_id' => 'CO1',
                    'hours' => 3
                ],
                [
                    'session_code' => 'Sess 02',
                    'code' => 'EXP-02',
                    'title' => 'Compound Pendulum Experiment',
                    'co_id' => 'CO2',
                    'hours' => 3
                ]
            ]
        ]);

        $response->assertStatus(200);
        $this->assertEquals('SUCCESS', $response->json('status'));
        $this->assertCount(2, $response->json('experiments'));

        // Check PracticumCourseFile record
        if (Schema::hasTable('r26_practicum_course_files')) {
            $file = R26PracticumCourseFile::where('batch_subject_id', $id)->first();
            $this->assertNotNull($file);
            $this->assertCount(2, $file->parsed_experiments);
        }
    }

    public function test_delete_lesson_plan_rows()
    {
        $this->withSession(['userId' => 'FACULTY_01', 'userRole' => 'Lecturer']);
        $id = $this->batchSubject->id;

        if (Schema::hasTable('lesson_plans')) {
            $lp = LessonPlan::create([
                'batch_subject_id' => $id,
                'day_no' => 999,
                'mode' => 'P',
                'pedagogy' => 'Practical Lab (P)',
                'proposed_date' => '2026-09-25',
                'topic_content' => 'Test row to delete',
                'co_id' => 'CO1',
                'allocated_hours' => 1,
                'status' => 'Pending'
            ]);

            $response = $this->postJson("/api/r26/classroom/practicum/{$id}/lesson-plan/delete-rows", [
                'ids' => [$lp->id]
            ]);

            $response->assertStatus(200);
            $this->assertEquals('SUCCESS', $response->json('status'));
            $this->assertDatabaseMissing('lesson_plans', ['id' => $lp->id]);
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_get_practicum_experiments_logs_returns_structure()
    {
        $this->withSession(['userId' => 'FACULTY_01', 'userRole' => 'Lecturer']);
        $id = $this->batchSubject->id;

        $response = $this->getJson("/api/r26/classroom/practicum/{$id}/experiments-log");
        $response->assertStatus(200);
        $this->assertArrayHasKey('logs', $response->json());
        $this->assertArrayHasKey('totalEnrolled', $response->json());
        $this->assertArrayHasKey('avgAttnPct', $response->json());
    }

    public function test_print_views_render_successfully()
    {
        $this->withSession(['userId' => 'FACULTY_01', 'userRole' => 'Lecturer']);
        $id = $this->batchSubject->id;

        // 1. Experiment List
        $r1 = $this->get("/r26/classroom/practicum/{$id}/print-experiment-list");
        $r1->assertStatus(200);
        $r1->assertSee('List of Practical Experiments');

        // 2. Experiments Log
        $r2 = $this->get("/r26/classroom/practicum/{$id}/print-experiments-log");
        $r2->assertStatus(200);
        $r2->assertSee('Practical Experiments Conducted & Attendance Log');

        // 3. Attendance Log Report
        $r3 = $this->get("/r26/classroom/practicum/{$id}/attendance-log-report");
        $r3->assertStatus(200);
        $r3->assertSee('Teaching & Attendance Log Register');
    }

    public function test_export_experiments_log_csv_streams_csv()
    {
        $this->withSession(['userId' => 'FACULTY_01', 'userRole' => 'Lecturer']);
        $id = $this->batchSubject->id;

        $response = $this->get("/r26/classroom/practicum/{$id}/export-experiments-log-csv");
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment;', $response->headers->get('Content-Disposition'));
    }
}
