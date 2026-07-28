<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26DrawingEseMark extends Model
{
    use HasFactory;

    protected $table = 'r26_drawing_ese_marks';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'part_a_mcq',
        'part_b_cad',
        'part_c_viva',
        'part_d_record',
        'total_ese_40',
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
