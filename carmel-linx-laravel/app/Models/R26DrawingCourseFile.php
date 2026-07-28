<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26DrawingCourseFile extends Model
{
    use HasFactory;

    protected $table = 'r26_drawing_course_files';

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
        'parsed_exercises',
        'parsed_copo',
        'parsed_textbooks',
        'self_learning_configs',
    ];

    protected $casts = [
        'parsed_cos' => 'array',
        'parsed_modules' => 'array',
        'parsed_exercises' => 'array',
        'parsed_copo' => 'array',
        'parsed_textbooks' => 'array',
        'self_learning_configs' => 'array',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
