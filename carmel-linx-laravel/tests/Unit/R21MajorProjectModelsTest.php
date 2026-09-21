<?php

namespace Tests\Unit;

use App\Models\R21MajorProjectCourseFile;
use App\Models\R21MajorProjectEvaluation;
use App\Models\BatchSubject;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class R21MajorProjectModelsTest extends TestCase
{
    /**
     * Test that R21MajorProjectCourseFile has correct table mapping, fillable attributes, and casts.
     */
    public function test_r21_major_project_course_file_model_instantiation_and_attributes(): void
    {
        $courseFile = new R21MajorProjectCourseFile([
            'batch_subject_id' => 101,
            'syllabus_pdf_path' => 'syllabus/project_6009.pdf',
            'course_title' => 'Major Project',
            'course_code' => '6009',
            'semester' => 'VI',
            'cia_marks' => 75,
            'ese_marks' => 50,
            'credits' => 4.0,
            'parsed_cos' => [
                ['id' => 'CO1', 'description' => 'Identify, formulate and analyze problems.'],
                ['id' => 'CO2', 'description' => 'Design and develop prototypes.'],
            ],
            'parsed_copo' => [
                'CO1' => ['PO1' => 3, 'PO2' => 2],
            ],
            'project_groups' => [
                ['id' => 'GRP-01', 'title' => 'Autonomous Rover', 'members' => ['STU001', 'STU002']],
            ],
            'attainment_settings' => [
                'threshold_grade' => 'D',
                'target_percentage' => 60.0,
            ],
        ]);

        $this->assertInstanceOf(R21MajorProjectCourseFile::class, $courseFile);
        $this->assertEquals('r21_major_project_course_files', $courseFile->getTable());
        $this->assertEquals(101, $courseFile->batch_subject_id);
        $this->assertEquals('syllabus/project_6009.pdf', $courseFile->syllabus_pdf_path);
        $this->assertEquals('Major Project', $courseFile->course_title);
        $this->assertEquals('6009', $courseFile->course_code);
        $this->assertEquals('VI', $courseFile->semester);
        $this->assertEquals(75, $courseFile->cia_marks);
        $this->assertEquals(50, $courseFile->ese_marks);
        $this->assertEquals(4.0, $courseFile->credits);

        $this->assertIsArray($courseFile->parsed_cos);
        $this->assertCount(2, $courseFile->parsed_cos);
        $this->assertEquals('CO1', $courseFile->parsed_cos[0]['id']);

        $this->assertIsArray($courseFile->parsed_copo);
        $this->assertEquals(3, $courseFile->parsed_copo['CO1']['PO1']);

        $this->assertIsArray($courseFile->project_groups);
        $this->assertEquals('GRP-01', $courseFile->project_groups[0]['id']);

        $this->assertIsArray($courseFile->attainment_settings);
        $this->assertEquals('D', $courseFile->attainment_settings['threshold_grade']);
    }

    /**
     * Test R21MajorProjectCourseFile relationship definitions.
     */
    public function test_r21_major_project_course_file_relationships(): void
    {
        $courseFile = new R21MajorProjectCourseFile();
        $relation = $courseFile->batchSubject();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('batch_subject_id', $relation->getForeignKeyName());
        $this->assertInstanceOf(BatchSubject::class, $relation->getRelated());
    }

    /**
     * Test that R21MajorProjectEvaluation has correct table mapping, fillable attributes, and casts.
     */
    public function test_r21_major_project_evaluation_model_instantiation_and_attributes(): void
    {
        $evaluation = new R21MajorProjectEvaluation([
            'batch_subject_id' => 101,
            'reg_no' => '2101010001',
            'group_id' => 'GRP-01',
            'project_title' => 'Autonomous Rover',
            'formative_diary_marks' => 28.5,
            'summative_dept_marks' => 27.0,
            'attendance_marks' => 15.0,
            'total_cia_75' => 70.5,
            'ese_prototype' => 9.5,
            'ese_modern_tools' => 4.5,
            'ese_presentation' => 7.0,
            'ese_innovativeness' => 2.5,
            'ese_viva' => 7.0,
            'ese_individual_contrib' => 7.0,
            'ese_group_activity' => 4.5,
            'ese_project_report' => 4.5,
            'total_ese_50' => 46.5,
            'ese_grade' => 'A',
            'grand_total_125' => 117.0,
            'passed' => true,
            'remarks' => 'Excellent engineering prototype.',
        ]);

        $this->assertInstanceOf(R21MajorProjectEvaluation::class, $evaluation);
        $this->assertEquals('r21_major_project_evaluations', $evaluation->getTable());
        $this->assertEquals(101, $evaluation->batch_subject_id);
        $this->assertEquals('2101010001', $evaluation->reg_no);
        $this->assertEquals('GRP-01', $evaluation->group_id);
        $this->assertEquals('Autonomous Rover', $evaluation->project_title);

        $this->assertSame(28.5, $evaluation->formative_diary_marks);
        $this->assertSame(27.0, $evaluation->summative_dept_marks);
        $this->assertSame(15.0, $evaluation->attendance_marks);
        $this->assertSame(70.5, $evaluation->total_cia_75);

        $this->assertSame(9.5, $evaluation->ese_prototype);
        $this->assertSame(4.5, $evaluation->ese_modern_tools);
        $this->assertSame(7.0, $evaluation->ese_presentation);
        $this->assertSame(2.5, $evaluation->ese_innovativeness);
        $this->assertSame(7.0, $evaluation->ese_viva);
        $this->assertSame(7.0, $evaluation->ese_individual_contrib);
        $this->assertSame(4.5, $evaluation->ese_group_activity);
        $this->assertSame(4.5, $evaluation->ese_project_report);
        $this->assertSame(46.5, $evaluation->total_ese_50);

        $this->assertEquals('A', $evaluation->ese_grade);
        $this->assertSame(117.0, $evaluation->grand_total_125);
        $this->assertTrue($evaluation->passed);
        $this->assertEquals('Excellent engineering prototype.', $evaluation->remarks);
    }

    /**
     * Test R21MajorProjectEvaluation relationship definitions.
     */
    public function test_r21_major_project_evaluation_relationships(): void
    {
        $evaluation = new R21MajorProjectEvaluation();

        $batchSubjectRelation = $evaluation->batchSubject();
        $this->assertInstanceOf(BelongsTo::class, $batchSubjectRelation);
        $this->assertEquals('batch_subject_id', $batchSubjectRelation->getForeignKeyName());
        $this->assertInstanceOf(BatchSubject::class, $batchSubjectRelation->getRelated());

        $studentRelation = $evaluation->student();
        $this->assertInstanceOf(BelongsTo::class, $studentRelation);
        $this->assertEquals('reg_no', $studentRelation->getForeignKeyName());
        $this->assertEquals('reg_no', $studentRelation->getOwnerKeyName());
        $this->assertInstanceOf(Student::class, $studentRelation->getRelated());
    }

    /**
     * Test that the migration file exists and defines required tables.
     */
    public function test_r21_major_project_migration_file_structure(): void
    {
        $migrationPath = database_path('migrations/2026_09_12_000001_create_r21_major_project_tables.php');
        $this->assertFileExists($migrationPath);

        $content = file_get_contents($migrationPath);
        $this->assertStringContainsString('r21_major_project_course_files', $content);
        $this->assertStringContainsString('r21_major_project_evaluations', $content);
        $this->assertStringContainsString('formative_diary_marks', $content);
        $this->assertStringContainsString('summative_dept_marks', $content);
        $this->assertStringContainsString('attendance_marks', $content);
        $this->assertStringContainsString('total_cia_75', $content);
        $this->assertStringContainsString('total_ese_50', $content);
        $this->assertStringContainsString('grand_total_125', $content);
    }
}
