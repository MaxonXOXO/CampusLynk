<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26HealthPhysicalCourseFile extends Model
{
    use HasFactory;

    protected $table = 'r26_health_physical_course_files';

    protected $fillable = [
        'batch_subject_id',
        'syllabus_pdf_path',
        'program',
        'course_title',
        'course_code',
        'semester',
        'type_of_course',
        'teaching_scheme',
        'contact_hours',
        'credits',
        'cie_marks',
        'ese_marks',
        'parsed_cos',
        'parsed_modules',
        'parsed_activities',
        'parsed_copo',
        'parsed_eval_scheme',
        'parsed_textbooks',
    ];

    protected $casts = [
        'parsed_cos' => 'array',
        'parsed_modules' => 'array',
        'parsed_activities' => 'array',
        'parsed_copo' => 'array',
        'parsed_eval_scheme' => 'array',
        'parsed_textbooks' => 'array',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
