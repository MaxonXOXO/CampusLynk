<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26HealthPhysicalEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r26_health_physical_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'activity_no',
        'activity_title',
        'reg_no',
        'criteria_json',
        'c1',
        'c2',
        'c3',
        'c4',
        'c5',
        'c6',
        'total_score_50',
        'assessor_mobile_no',
    ];

    protected $casts = [
        'criteria_json' => 'array',
        'total_score_50' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
