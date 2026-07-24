<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26PracticumEseMark extends Model
{
    use HasFactory;

    protected $table = 'r26_practicum_ese_marks';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'ese_theory_marks',
        'ese_practical_marks',
        'total_ese_marks',
        'theory_absent',
        'practical_absent'
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
