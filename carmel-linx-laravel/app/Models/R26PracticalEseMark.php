<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26PracticalEseMark extends Model
{
    use HasFactory;

    protected $table = 'r26_practical_ese_marks';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'ese_score',
        'assessor_mobile_no',
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
