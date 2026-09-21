<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\BatchSubject;
use App\Models\CourseFile;

class R26TheorySyncTest extends TestCase
{
    use RefreshDatabase;

    protected $batchSubject;

    protected function setUp(): void
    {
        parent::setUp();

        if (Schema::hasTable('class_management')) {
            DB::table('class_management')->updateOrInsert(
                ['classroom_id' => 'CR_THEORY_01'],
                [
                    'branch' => 'CE',
                    'batch_year' => 2026,
                    'current_semester' => 1,
                    'tutor_mobile_no' => null
                ]
            );
        }

        if (Schema::hasTable('batch_subjects')) {
            $this->batchSubject = BatchSubject::create([
                'classroom_id' => 'CR_THEORY_01',
                'subject_code' => '2001T',
                'subject_name' => 'Engineering Mechanics',
                'semester' => 1,
                'subject_type' => 'Theory'
            ]);
        }
    }

    public function test_save_copo_matrix_requires_auth()
    {
        $id = $this->batchSubject->id;
        $response = $this->postJson("/api/r26/classroom/{$id}/copo-matrix/save", [
            'mappings' => [
                'CO1' => ['PO1' => 3, 'PO2' => 2],
                'CO2' => ['PO1' => 2, 'PO3' => 3]
            ]
        ]);

        $response->assertStatus(401);
    }

    public function test_save_copo_matrix_saves_to_course_file_and_syllabus_registry()
    {
        $this->withSession(['userId' => 'FACULTY_THEORY', 'userRole' => 'Lecturer']);
        $id = $this->batchSubject->id;

        $mappings = [
            'CO1' => ['PO1' => 3, 'PO2' => 2, 'PSO1' => 3],
            'CO2' => ['PO1' => 2, 'PO3' => 3, 'PSO1' => 2],
            'CO3' => ['PO4' => 3, 'PO5' => 1, 'PSO2' => 3],
            'CO4' => ['PO8' => 2, 'PO11' => 3, 'PSO3' => 2]
        ];

        $response = $this->postJson("/api/r26/classroom/{$id}/copo-matrix/save", [
            'mappings' => $mappings
        ]);

        $response->assertStatus(200);
        $this->assertEquals('SUCCESS', $response->json('status'));
        $this->assertEquals($mappings, $response->json('mappings'));

        // Check CourseFile record
        if (Schema::hasTable('course_files')) {
            $cf = CourseFile::where('batch_subject_id', $id)->first();
            $this->assertNotNull($cf);
            $copo = $cf->parsed_copo;
            $this->assertEquals($mappings, $copo['mappings']);
        }

        // Check syllabus_registry record
        if (Schema::hasTable('syllabus_registry')) {
            $reg = DB::table('syllabus_registry')->where('subject_code', '2001T')->first();
            $this->assertNotNull($reg);
            $this->assertEquals($mappings, json_decode($reg->co_po_mapping, true));
        }
    }

    public function test_attainment_and_cie_print_reports_render()
    {
        $this->withSession(['userId' => 'FACULTY_THEORY', 'userRole' => 'Lecturer']);
        $id = $this->batchSubject->id;

        // Ensure course file exists for print views
        if (Schema::hasTable('course_files')) {
            CourseFile::firstOrCreate(
                ['batch_subject_id' => $id],
                [
                    'parsed_copo' => ['mappings' => ['CO1' => ['PO1' => 3]]],
                    'self_learning_configs' => [
                        'CO1' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
                        'CO2' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
                        'CO3' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
                        'CO4' => ['assignment' => 5.0, 'mcq' => 5.0, 'act3' => 5.0, 'act3_mode' => 'Case Study', 'act4' => 0.0, 'act4_mode' => 'MCQ', 'act5' => 0.0, 'act5_mode' => 'Exercise'],
                    ]
                ]
            );
        }

        $r1 = $this->get("/r26/classroom/{$id}/nba/attainment-report");
        $r1->assertStatus(200);

        $r2 = $this->get("/r26/classroom/{$id}/internals/print-cie");
        $r2->assertStatus(200);
    }
}
