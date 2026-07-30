<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26HealthPhysicalEseMark extends Model
{
    use HasFactory;

    protected $table = 'r26_health_physical_ese_marks';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'fitness_test_score',
        'skill_demo_score',
        'viva_score',
        'record_score',
        'total_ese_40',
        'is_absent',
    ];

    protected $casts = [
        'fitness_test_score' => 'float',
        'skill_demo_score' => 'float',
        'viva_score' => 'float',
        'record_score' => 'float',
        'total_ese_40' => 'float',
        'is_absent' => 'boolean',
    ];
}
