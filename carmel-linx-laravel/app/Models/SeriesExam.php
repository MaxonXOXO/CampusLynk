<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeriesExam extends Model
{
    protected $fillable = [
        'batch_subject_id',
        'exam_name',
        'mode',
        'co_tags',
        'max_marks',
        'duration_minutes',
        'questions',
        'locked'
    ];

    protected $casts = [
        'co_tags' => 'array',
        'questions' => 'array',
        'locked' => 'boolean'
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
