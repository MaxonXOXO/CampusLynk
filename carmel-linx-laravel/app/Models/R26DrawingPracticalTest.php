<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26DrawingPracticalTest extends Model
{
    use HasFactory;

    protected $table = 'r26_drawing_practical_tests';

    protected $fillable = [
        'batch_subject_id',
        'test_no',
        'reg_no',
        'writeup_procedure',
        'setup_execution',
        'observation_result',
        'viva_voce',
        'record_completion',
        'total_score_40',
        'is_absent',
    ];

    protected $casts = [
        'is_absent' => 'boolean',
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
