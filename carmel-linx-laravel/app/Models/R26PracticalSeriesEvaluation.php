<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26PracticalSeriesEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r26_practical_series_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'series_no',
        'reg_no',
        'writeup_procedure',
        'setup_execution',
        'observation_result',
        'viva_voce',
        'record_completion',
        'total_score_40',
        'assessor_mobile_no',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
