<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26PracticumSeriesTheory extends Model
{
    use HasFactory;

    protected $table = 'r26_practicum_series_theory';

    protected $fillable = [
        'batch_subject_id',
        'series_no',
        'reg_no',
        'part_a_score',
        'part_b_score',
        'part_c_score',
        'total_score_50',
        'is_absent'
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
