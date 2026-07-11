<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticalExperiment extends Model
{
    protected $fillable = [
        'batch_subject_id',
        'experiment_no',
        'title',
        'co_tag',
        'conducted_date'
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class);
    }

    public function marks()
    {
        return $this->hasMany(PracticalExperimentMark::class);
    }
}
