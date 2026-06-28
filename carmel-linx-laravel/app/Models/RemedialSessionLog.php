<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemedialSessionLog extends Model
{
    use HasFactory;

    protected $table = 'remedial_session_logs';
    protected $primaryKey = 'log_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'log_id', 'room_id', 'session_date', 'duration_minutes', 'topic_covered', 'attendance_data'
    ];

    protected $casts = [
        'attendance_data' => 'array',
    ];
}
