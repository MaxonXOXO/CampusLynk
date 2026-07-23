<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * R26PracticalCourseFile
 *
 * Dedicated model for Revision 2026 Practical/Lab course files.
 * Completely isolated from the R2021 'course_files' table.
 * Storage path prefix: r26_practical_syllabi/
 */
class R26PracticalCourseFile extends Model
{
    use HasFactory;

    protected $table = 'r26_practical_course_files';

    protected $fillable = [
        'batch_subject_id',
        'syllabus_pdf_path',
        'course_title',
        'course_code',
        'credits',
        'teaching_scheme',
        'cie_marks',
        'ese_marks',
        'total_hours',
        'parsed_cos',
        'parsed_copo',
        'parsed_experiments',
        'manual_experiments',
        'status',
    ];

    // NO automatic JSON casting - we manage JSON encoding/decoding manually
    // to prevent double-encoding issues

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }

    /**
     * Helper: get parsed COs as PHP array
     */
    public function getCosArray(): array
    {
        return $this->parsed_cos ? json_decode($this->parsed_cos, true) ?? [] : [];
    }

    /**
     * Helper: get CO-PO mapping data as PHP array
     */
    public function getCoPoArray(): array
    {
        return $this->parsed_copo ? json_decode($this->parsed_copo, true) ?? [] : [];
    }

    /**
     * Helper: get active experiments list (manual overrides PDF-parsed)
     */
    public function getExperimentsArray(): array
    {
        $manual = $this->manual_experiments ? json_decode($this->manual_experiments, true) : null;
        if ($manual && count($manual) > 0) {
            return $manual;
        }
        return $this->parsed_experiments ? json_decode($this->parsed_experiments, true) ?? [] : [];
    }
}
