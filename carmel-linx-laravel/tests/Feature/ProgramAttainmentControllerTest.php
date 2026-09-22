<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\CourseFile;
use App\Models\ProgramAttainment;
use App\Models\StaffProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProgramAttainmentControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createCohortContext(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. HOD User',
            'email' => 'hod@example.com',
            'password' => 'secret',
            'designation' => 'HOD',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_PROG_ATT_2026',
            'branch' => 'CT',
            'batch_year' => 2026,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'VI',
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
            'subject_name' => 'Computer Networks',
            'subject_type' => 'Theory',
            'semester' => 4,
        ]);

        CourseFile::create([
            'batch_subject_id' => $subj1->id,
            'parsed_copo_data' => [
                'mappings' => [
                    'CO1' => ['PO1'=>'3', 'PO2'=>'2', 'PO3'=>'1', 'PSO1'=>'2'],
                    'CO2' => ['PO1'=>'3', 'PO2'=>'3', 'PO3'=>'2', 'PSO1'=>'3'],
                ]
            ],
            'attainment_settings' => [
                'calculated_cos' => ['CO1' => 2.60, 'CO2' => 2.50]
            ]
        ]);

        return compact('staff', 'classroom', 'subj1', 'subj2');
    }

    public function test_program_attainment_dashboard_loads_successfully()
    {
        $ctx = $this->createCohortContext();

        $response = $this->withSession(['userRole' => 'HOD', 'userBranch' => 'CT'])
            ->get("/hod/program-attainment/{$ctx['classroom']->classroom_id}");

        $response->assertStatus(200);
        $response->assertSee('NBA Criterion 3');
        $response->assertSee('Program Attainment & Outcome Analytics', false);
        $response->assertSee('Operating Systems');
        $response->assertSee('Computer Networks');
        $response->assertSee('PO1');
        $response->assertSee('PSO1');

        $this->assertDatabaseHas('program_attainments', [
            'classroom_id' => $ctx['classroom']->classroom_id,
        ]);
    }

    public function test_program_attainment_save_config_updates_record()
    {
        $ctx = $this->createCohortContext();

        $payload = [
            'po_targets' => [
                'PO1' => 2.2,
                'PO2' => 2.1,
                'PSO1' => 2.3
            ],
            'indirect_surveys' => [
                'PO1' => 2.7,
                'PO2' => 2.6,
                'PSO1' => 2.8
            ],
            'action_plans' => 'Conduct special hands-on workshops for non-attained POs.'
        ];

        $response = $this->withSession(['userRole' => 'HOD'])
            ->postJson("/hod/program-attainment/{$ctx['classroom']->classroom_id}/save", $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'message' => 'Program Attainment configuration saved successfully!'
        ]);

        $record = ProgramAttainment::where('classroom_id', $ctx['classroom']->classroom_id)->first();
        $this->assertNotNull($record);
        $this->assertEquals(2.2, $record->po_targets['PO1']);
        $this->assertEquals(2.7, $record->indirect_surveys['PO1']);
        $this->assertEquals('Conduct special hands-on workshops for non-attained POs.', $record->action_plans);
    }

    public function test_program_attainment_print_report_renders()
    {
        $ctx = $this->createCohortContext();

        $response = $this->withSession(['userRole' => 'HOD', 'userBranch' => 'CT'])
            ->get("/hod/program-attainment/{$ctx['classroom']->classroom_id}/print");

        $response->assertStatus(200);
        $response->assertSee('Carmel Polytechnic College, Alappuzha');
        $response->assertSee('NBA Criterion 3 — Program Outcomes');
        $response->assertSee('Direct (80%)');
        $response->assertSee('Indirect (20%)');
        $response->assertSee('Head of Department');
        $response->assertSee('NBA Coordinator');
    }
}
