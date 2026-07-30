<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26HealthPhysicalFitnessTest extends Model
{
    use HasFactory;

    protected $table = 'r26_health_physical_fitness_tests';

    protected $fillable = [
        'batch_subject_id',
        'test_no',
        'reg_no',
        'criteria_json',
        'total_score_40',
        'is_absent',
    ];

    protected $casts = [
        'criteria_json' => 'array',
        'total_score_40' => 'float',
        'is_absent' => 'boolean',
    ];
}
