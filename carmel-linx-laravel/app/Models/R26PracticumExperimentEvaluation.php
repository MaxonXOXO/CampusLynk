<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26PracticumExperimentEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r26_practicum_experiment_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'experiment_no',
        'experiment_title',
        'reg_no',
        'prep_punctuality',
        'setup_procedure',
        'observation_recording',
        'analysis_interpretation',
        'viva_voce',
        'workmanship_discipline',
        'total_score_50',
        'assessor_mobile_no'
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
