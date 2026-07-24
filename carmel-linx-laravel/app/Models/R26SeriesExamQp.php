<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26SeriesExamQp extends Model
{
    use HasFactory;

    protected $table = 'r26_series_exam_qps';

    protected $fillable = [
        'batch_subject_id',
        'series_no',
        'co_tag',
        'pattern_type',
        'max_marks',
        'duration_minutes',
        'qp_data',
        'scheme_data',
        'answer_key',
        'created_by'
    ];

    protected $casts = [
        'qp_data' => 'array',
        'scheme_data' => 'array',
        'answer_key' => 'array',
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id');
    }
}
