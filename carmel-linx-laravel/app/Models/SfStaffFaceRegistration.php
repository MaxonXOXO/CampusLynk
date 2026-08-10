<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SfStaffFaceRegistration extends Model
{
    use HasFactory;

    protected $table = 'sf_staff_face_registrations';

    protected $fillable = [
        'staff_id',
        'mobile_no',
        'staff_name',
        'face_descriptor',
        'photo_url',
    ];
}
