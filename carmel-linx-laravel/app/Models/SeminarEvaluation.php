<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarEvaluation extends Model
{
    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'assessor_mobile_no',
        'relevance',
        'literature',
        'presentation',
        'interaction',
        'report',
        'attendance',
        'total_score'
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
