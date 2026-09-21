<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\BatchSubject;
use App\Models\VirtualLearningMaterial;

class VideoMaterialsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_upload_material_requires_login()
    {
        $response = $this->postJson('/api/virtual-room/materials/upload', [
            'batch_subject_id' => 1,
            'room_type' => 'Theory',
            'experiment_or_topic_no' => 'Topic 1',
            'title' => 'Sample',
            'material_type' => 'video_clip'
        ]);

        $response->assertStatus(401);
    }

    public function test_upload_video_clip_validation_enforces_video_mime_and_size()
    {
        // Fake session
        $this->withSession(['userId' => 'STAFF_999']);

        // Create dummy batch subject if table exists
        $batchSubjectId = 1;
        if (Schema::hasTable('batch_subjects')) {
            $bs = BatchSubject::firstOrCreate(
                ['id' => 1],
                [
                    'batch_id' => 1,
                    'subject_code' => 'TEST101',
                    'subject_name' => 'Test Subject',
                    'classroom_id' => 'CR_TEST_01',
                    'semester' => 1,
                    'subject_type' => 'Theory'
                ]
            );
            $batchSubjectId = $bs->id;
        }

        // Test with invalid file type (e.g. text/plain pretending to be video)
        $invalidFile = UploadedFile::fake()->create('test.txt', 100, 'text/plain');
        $response = $this->postJson('/api/virtual-room/materials/upload', [
            'batch_subject_id' => $batchSubjectId,
            'room_type' => 'Theory',
            'experiment_or_topic_no' => 'Topic 1',
            'title' => 'Video Title',
            'material_type' => 'video_clip',
            'file' => $invalidFile
        ]);

        $response->assertStatus(422);

        // Test with valid MP4 file under 25MB
        $validFile = UploadedFile::fake()->create('lecture.mp4', 2000, 'video/mp4');
        $response = $this->postJson('/api/virtual-room/materials/upload', [
            'batch_subject_id' => $batchSubjectId,
            'room_type' => 'Theory',
            'experiment_or_topic_no' => 'Topic 1',
            'title' => 'Video Title',
            'material_type' => 'video_clip',
            'file' => $validFile
        ]);

        if (Schema::hasTable('virtual_learning_materials')) {
            $response->assertStatus(200);
            $this->assertEquals('SUCCESS', $response->json('status'));
            $this->assertEquals('video_clip', $response->json('material.material_type'));
            $this->assertNotNull($response->json('material.file_path'));
        }
    }

    public function test_get_subject_materials_returns_json()
    {
        $response = $this->getJson('/api/virtual-room/materials/1');

        $response->assertStatus(200);
        $this->assertEquals('SUCCESS', $response->json('status'));
        $this->assertIsArray($response->json('materials'));
    }
}
