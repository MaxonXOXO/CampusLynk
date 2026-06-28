<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfCourseFile extends Model
{
    protected $table = 'cf_course_files';
    protected $guarded = [];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }

    public function sectionA()
    {
        return $this->hasOne(CfSectionAPlanning::class, 'cf_id');
    }

    public function sectionB()
    {
        return $this->hasOne(CfSectionBMaterials::class, 'cf_id');
    }

    public function sectionC()
    {
        return $this->hasOne(CfSectionCAssessments::class, 'cf_id');
    }

    public function sectionD()
    {
        return $this->hasOne(CfSectionDAttainments::class, 'cf_id');
    }
    public function documents()
    {
        return $this->hasMany(CfCourseFileDocument::class, 'course_file_id');
    }
}

