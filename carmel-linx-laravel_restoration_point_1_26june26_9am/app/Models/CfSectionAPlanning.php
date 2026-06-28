<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfSectionAPlanning extends Model
{
    protected $table = 'cf_section_a_planning';
    protected $guarded = [];

    public function courseFile()
    {
        return $this->belongsTo(CfCourseFile::class, 'cf_id');
    }
}
