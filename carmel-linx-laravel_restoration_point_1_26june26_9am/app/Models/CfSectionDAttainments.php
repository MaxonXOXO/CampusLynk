<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfSectionDAttainments extends Model
{
    protected $table = 'cf_section_d_attainments';
    protected $guarded = [];

    public function courseFile()
    {
        return $this->belongsTo(CfCourseFile::class, 'cf_id');
    }
}
