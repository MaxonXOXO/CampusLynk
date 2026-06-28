<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSemesterMarks extends Model
{
    use HasFactory;

    protected $fillable = [
        'reg_no',
        'semester',
        'subject_code',
        'subject_name',
        'internal_marks',
        'board_marks',
        'total_marks',
        'grade',
        'attendance_percentage',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
