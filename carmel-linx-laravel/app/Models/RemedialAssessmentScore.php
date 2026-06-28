<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemedialAssessmentScore extends Model
{
    use HasFactory;

    protected $table = 'remedial_assessment_scores';

    protected $fillable = [
        'assessment_id', 'reg_no', 'score'
    ];
}
