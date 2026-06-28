<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemedialAssessment extends Model
{
    use HasFactory;

    protected $table = 'remedial_assessments';
    protected $primaryKey = 'assessment_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'assessment_id', 'room_id', 'type', 'title', 'max_marks', 'questions_payload'
    ];

    protected $casts = [
        'questions_payload' => 'array',
    ];

    public function scores()
    {
        return $this->hasMany(RemedialAssessmentScore::class, 'assessment_id', 'assessment_id');
    }
}
