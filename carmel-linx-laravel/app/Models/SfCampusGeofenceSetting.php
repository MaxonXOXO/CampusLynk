<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SfCampusGeofenceSetting extends Model
{
    use HasFactory;

    protected $table = 'sf_campus_geofence_settings';

    protected $fillable = [
        'campus_name',
        'centroid_lat',
        'centroid_lng',
        'radius_meters',
        'max_accuracy_meters',
        'is_active',
    ];
}
