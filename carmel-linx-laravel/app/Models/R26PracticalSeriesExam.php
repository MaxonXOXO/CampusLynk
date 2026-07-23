<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26PracticalSeriesExam extends Model
{
    use HasFactory;

    protected $table = 'r26_practical_series_exams';

    protected $fillable = [
        'batch_subject_id',
        'exam_name',
        'co_tags',
        'max_marks',
        'duration_minutes',
        'question_outline',
        'locked',
    ];

    protected $casts = [
        'co_tags' => 'array',
        'question_outline' => 'array',
        'locked' => 'boolean',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
