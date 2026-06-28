<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSemesterSummary extends Model
{
    use HasFactory;
    
    protected $table = 'student_semester_summary';

    protected $fillable = [
        'reg_no',
        'semester',
        'sgpa',
        'cgpa',
        'activity_points',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
