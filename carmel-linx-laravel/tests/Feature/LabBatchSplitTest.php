<?php

namespace Tests\Feature;

use App\Models\BatchSubject;
use App\Models\ClassManagement;
use App\Models\R26StudentLabBatch;
use App\Models\StaffProfile;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabBatchSplitTest extends TestCase
{
    use RefreshDatabase;

    private function createLabBatchContext(): array
    {
        $staff = StaffProfile::create([
            'mobile_no' => '9876543210',
            'name' => 'Prof. Lab Instructor',
            'email' => 'instructor@example.com',
            'password' => 'secret',
            'designation' => 'Demonstrator',
            'branch' => 'CT',
            'account_status' => 'APPROVED',
        ]);

        $classroom = ClassManagement::create([
            'classroom_id' => 'CR_LAB_SPLIT_TEST',
            'branch' => 'CT',
            'batch_year' => 2026,
            'tutor_mobile_no' => $staff->mobile_no,
            'mentor_mobile_no' => $staff->mobile_no,
            'current_semester' => 'I',
        ]);

        $batchSubject = BatchSubject::create([
            'classroom_id' => 'CR_LAB_SPLIT_TEST',
            'subject_code' => '2009',
            'subject_name' => 'Hardware Lab',
            'subject_type' => 'Practical',
            'semester' => 2,
        ]);

        $students = [];
        for ($i = 1; $i <= 4; $i++) {
            $regNo = "26010100" . str_pad((string)$i, 2, '0', STR_PAD_LEFT);
            $students[] = Student::create([
                'reg_no' => $regNo,
                'adm_no' => "ADM00{$i}",
                'name' => "Student {$i}",
                'email' => "student{$i}@example.com",
                'password' => 'secret',
                'phone' => "987650000{$i}",
                'branch' => 'CT',
                'admission_year' => 2026,
                'admission_type' => 'REGULAR',
                'classroom_id' => 'CR_LAB_SPLIT_TEST',
                'semester' => 'I',
                'status' => 'APPROVED',
                'sbte_reg_no' => $regNo,
                'roll_no' => $i,
            ]);
        }

        return compact('staff', 'classroom', 'batchSubject', 'students');
    }

    public function test_get_lab_batch_roster_returns_student_roster_and_counts(): void
    {
        $ctx = $this->createLabBatchContext();

        // Assign one student to Batch A, one to Batch B
        R26StudentLabBatch::create([
            'batch_subject_id' => $ctx['batchSubject']->id,
            'reg_no' => '2601010001',
            'lab_batch' => 'Batch A',
        ]);
        R26StudentLabBatch::create([
            'batch_subject_id' => $ctx['batchSubject']->id,
            'reg_no' => '2601010002',
            'lab_batch' => 'Batch B',
        ]);

        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Demonstrator',
        ])->getJson("/api/classroom/practical/{$ctx['batchSubject']->id}/lab-batch/roster");

        $res->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'total_students' => 4,
                'batch_a_count' => 1,
                'batch_b_count' => 1,
                'unassigned_count' => 2,
            ]);

        $res->assertJsonFragment([
            'reg_no' => '2601010001',
            'lab_batch' => 'Batch A',
        ]);
        $res->assertJsonFragment([
            'reg_no' => '2601010002',
            'lab_batch' => 'Batch B',
        ]);
        $res->assertJsonFragment([
            'reg_no' => '2601010003',
            'lab_batch' => null,
        ]);
    }

    public function test_auto_split_lab_batches_half(): void
    {
        $ctx = $this->createLabBatchContext();

        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Demonstrator',
        ])->postJson("/api/classroom/practical/{$ctx['batchSubject']->id}/lab-batch/auto-split", [
            'method' => 'half',
        ]);

        $res->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'batch_a_count' => 2,
                'batch_b_count' => 2,
            ]);

        // First 2 in Batch A, next 2 in Batch B
        $this->assertEquals('Batch A', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010001')->value('lab_batch'));
        $this->assertEquals('Batch A', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010002')->value('lab_batch'));
        $this->assertEquals('Batch B', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010003')->value('lab_batch'));
        $this->assertEquals('Batch B', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010004')->value('lab_batch'));
    }

    public function test_auto_split_lab_batches_cutoff(): void
    {
        $ctx = $this->createLabBatchContext();

        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Demonstrator',
        ])->postJson("/api/classroom/practical/{$ctx['batchSubject']->id}/lab-batch/auto-split", [
            'method' => 'cutoff',
            'cutoff' => 1,
        ]);

        $res->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'batch_a_count' => 1,
                'batch_b_count' => 3,
            ]);

        // Roll 1 in Batch A, Rolls 2, 3, 4 in Batch B
        $this->assertEquals('Batch A', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010001')->value('lab_batch'));
        $this->assertEquals('Batch B', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010002')->value('lab_batch'));
        $this->assertEquals('Batch B', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010003')->value('lab_batch'));
        $this->assertEquals('Batch B', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010004')->value('lab_batch'));
    }

    public function test_auto_split_lab_batches_alternating(): void
    {
        $ctx = $this->createLabBatchContext();

        $res = $this->withSession([
            'userId' => $ctx['staff']->mobile_no,
            'userRole' => 'Demonstrator',
        ])->postJson("/api/classroom/practical/{$ctx['batchSubject']->id}/lab-batch/auto-split", [
            'method' => 'alternating',
        ]);

        $res->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'batch_a_count' => 2,
                'batch_b_count' => 2,
            ]);

        // 0 -> Batch A, 1 -> Batch B, 2 -> Batch A, 3 -> Batch B
        $this->assertEquals('Batch A', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010001')->value('lab_batch'));
        $this->assertEquals('Batch B', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010002')->value('lab_batch'));
        $this->assertEquals('Batch A', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010003')->value('lab_batch'));
        $this->assertEquals('Batch B', R26StudentLabBatch::where('batch_subject_id', $ctx['batchSubject']->id)->where('reg_no', '2601010004')->value('lab_batch'));
    }
}
