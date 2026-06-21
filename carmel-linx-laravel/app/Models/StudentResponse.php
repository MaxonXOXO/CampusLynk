<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentResponse extends Model
{
    use HasFactory;

    protected $table = 'student_responses';

    protected $primaryKey = 'response_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'response_id',
        'reg_no',
        'test_id',
        'question_id',
        'selected_option',
        'descriptive_text',
        'marks_obtained',
        'evaluated_by',
        'status',
    ];

    /**
     * Relationship: The student who submitted the response.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }

    /**
     * Relationship: The online test this response belongs to.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(TestConfig::class, 'test_id', 'test_id');
    }

    /**
     * Relationship: The active question.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(QuestionBank::class, 'question_id', 'question_id');
    }

    /**
     * Relationship: The staff evaluator.
     */
    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'evaluated_by', 'mobile_no');
    }
}
