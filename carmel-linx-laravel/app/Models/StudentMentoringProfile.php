<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMentoringProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'reg_no',
        'gender',
        'caste',
        'religion',
        'special_category',
        'reservation',
        'quota',
        'is_physically_disabled',
        'disability_category',
        'guardian_occupation',
        'monthly_family_income',
        'has_vehicle_pass',
        'vehicle_pass_id',
        'communication_address',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
