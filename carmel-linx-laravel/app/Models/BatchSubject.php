<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchSubject extends Model
{
    protected $fillable = [
        'classroom_id',
        'semester',
        'subject_code',
        'subject_name',
        'subject_type'
    ];

    public function classroom()
    {
        return $this->belongsTo(ClassManagement::class, 'classroom_id', 'classroom_id');
    }

    public function staffAssignments()
    {
        return $this->hasMany(SubjectStaffAssignment::class, 'batch_subject_id', 'id');
    }
}
