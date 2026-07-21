<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class R26CourseFileDocument extends Model
{
    protected $table = 'r26_course_file_documents';

    protected $fillable = [
        'r26_course_file_id',
        'document_number',
        'document_name',
        'is_checked',
        'remarks',
        'data_payload',
    ];

    public function courseFile()
    {
        return $this->belongsTo(R26CourseFile::class, 'r26_course_file_id');
    }
}
