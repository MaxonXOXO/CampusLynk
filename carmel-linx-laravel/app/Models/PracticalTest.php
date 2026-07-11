<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticalTest extends Model
{
    protected $fillable = [
        'batch_subject_id',
        'test_name',
        'questions'
    ];

    protected $casts = [
        'questions' => 'array'
    ];

    public function batchSubject()
    {
        return $this->belongsTo(BatchSubject::class);
    }

    public function testMarks()
    {
        return $this->hasMany(PracticalTestMark::class);
    }
}
