<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfSectionCAssessments extends Model
{
    protected $table = 'cf_section_c_assessments';
    protected $guarded = [];

    public function courseFile()
    {
        return $this->belongsTo(CfCourseFile::class, 'cf_id');
    }
}
