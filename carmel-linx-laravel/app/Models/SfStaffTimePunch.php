<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SfStaffTimePunch extends Model
{
    use HasFactory;

    protected $table = 'sf_staff_time_punches';

    protected $fillable = [
        'staff_id',
        'staff_name',
        'punch_date',
        'in_time',
        'out_time',
        'in_gps_lat',
        'in_gps_lng',
        'in_gps_distance_meters',
        'in_premises_status',
        'out_gps_lat',
        'out_gps_lng',
        'out_gps_distance_meters',
        'out_premises_status',
        'liveness_type',
        'liveness_score',
        'biometric_confidence',
        'punch_status',
        'in_snapshot_url',
        'out_snapshot_url',
        'remarks',
    ];
}
