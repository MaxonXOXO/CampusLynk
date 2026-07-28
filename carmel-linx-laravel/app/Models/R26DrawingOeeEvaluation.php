<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26DrawingOeeEvaluation extends Model
{
    use HasFactory;

    protected $table = 'r26_drawing_oee_evaluations';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'originality_relevance',
        'objectives_plan',
        'execution_recording',
        'analysis_presentation',
        'teamwork_innovation',
        'total_score_50',
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
