<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemedialRoom extends Model
{
    use HasFactory;

    protected $table = 'remedial_rooms';
    protected $primaryKey = 'room_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'room_id', 'classroom_id', 'subject_code', 'created_by_mobile', 'status'
    ];

    public function students()
    {
        return $this->hasMany(RemedialStudent::class, 'room_id', 'room_id');
    }

    public function logs()
    {
        return $this->hasMany(RemedialSessionLog::class, 'room_id', 'room_id')->orderBy('session_date', 'desc');
    }

    public function assessments()
    {
        return $this->hasMany(RemedialAssessment::class, 'room_id', 'room_id')->orderBy('created_at', 'desc');
    }
}
