<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class R26StudentLabBatch extends Model
{
    use HasFactory;

    protected $table = 'r26_student_lab_batches';

    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'lab_batch'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class, 'batch_subject_id', 'id');
    }
}
