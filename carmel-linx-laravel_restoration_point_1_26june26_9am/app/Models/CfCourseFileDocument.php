<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfCourseFileDocument extends Model
{
    protected $fillable = [
        'course_file_id',
        'document_number',
        'document_name',
        'is_checked',
        'remarks'
    ];

    public function courseFile()
    {
        return $this->belongsTo(CfCourseFile::class, 'course_file_id');
    }
}
