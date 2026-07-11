<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeminarAcceptance extends Model
{
    protected $fillable = [
        'seminar_registration_id',
        'staff_mobile_no',
        'status'
    ];

    public function seminarRegistration()
    {
        return $this->belongsTo(StudentSeminarRegistration::class, 'seminar_registration_id');
    }

    public function staff()
    {
        return $this->belongsTo(StaffProfile::class, 'staff_mobile_no', 'mobile_no');
    }
}
