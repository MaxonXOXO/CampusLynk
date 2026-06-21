<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestConfig extends Model
{
    use HasFactory;

    protected $table = 'test_configs';

    protected $primaryKey = 'test_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'test_id',
        'subject_code',
        'classroom_id',
        'test_name',
        'start_time',
        'end_time',
        'duration',
        'selected_cos',
        'mcq_count',
        'descriptive_count',
        'target_percentage',
        'pass_threshold',
        'is_active',
    ];

    protected $casts = [
        'selected_cos' => 'array',
        'is_active' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Relationship: The subject code syllabus.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(SyllabusRegistry::class, 'subject_code', 'subject_code');
    }

    /**
     * Relationship: Classroom assigned to the exam.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(ClassManagement::class, 'classroom_id', 'classroom_id');
    }

    /**
     * Relationship: All responses filed for this test.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(StudentResponse::class, 'test_id', 'test_id');
    }
}
