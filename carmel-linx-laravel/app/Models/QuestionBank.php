<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionBank extends Model
{
    use HasFactory;

    protected $table = 'question_bank';

    protected $primaryKey = 'question_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'question_id',
        'branch_code',
        'subject_code',
        'type',
        'part_type',
        'cognitive_level',
        'question_text',
        'options',
        'correct_answer',
        'co_tag',
        'marks',
        'rubric',
    ];

    protected $casts = [
        'options' => 'array',
        'rubric'  => 'array',
    ];

    /**
     * Relationship: The syllabus subject this question is tagged to.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(SyllabusRegistry::class, 'subject_code', 'subject_code');
    }
}
