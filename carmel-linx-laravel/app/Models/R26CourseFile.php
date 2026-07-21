<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class R26CourseFile extends Model
{
    protected $table = 'r26_course_files';

    protected $fillable = [
        'batch_subject_id',
        'academic_year',
        'status',
        'generated_pdf_path',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }

    public function documents()
    {
        return $this->hasMany(R26CourseFileDocument::class, 'r26_course_file_id');
    }
}
