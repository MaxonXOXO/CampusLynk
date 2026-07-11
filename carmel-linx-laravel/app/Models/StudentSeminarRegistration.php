<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSeminarRegistration extends Model
{
    protected $fillable = [
        'batch_subject_id',
        'reg_no',
        'topic',
        'presentation_date',
        'guide_mobile_no'
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }

    public function guide()
    {
        return $this->belongsTo(StaffProfile::class, 'guide_mobile_no', 'mobile_no');
    }

    public function acceptances()
    {
        return $this->hasMany(SeminarAcceptance::class, 'seminar_registration_id');
    }
}
