<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26OpenEndedEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r26_open_ended_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'project_title',
        'reg_no',
        'originality_relevance',
        'objectives_plan',
        'execution_recording',
        'analysis_presentation',
        'teamwork_innovation',
        'total_score_50',
        'assessor_mobile_no',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
