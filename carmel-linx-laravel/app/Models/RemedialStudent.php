<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemedialStudent extends Model
{
    use HasFactory;

    protected $table = 'remedial_students';
    public $timestamps = false; // We only have added_at which we manually handle

    protected $fillable = [
        'room_id', 'reg_no', 'added_at'
    ];

    public function profile()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
