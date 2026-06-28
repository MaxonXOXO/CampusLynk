<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfSectionBMaterials extends Model
{
    protected $table = 'cf_section_b_materials';
    protected $guarded = [];

    public function courseFile()
    {
        return $this->belongsTo(CfCourseFile::class, 'cf_id');
    }
}
