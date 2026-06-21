<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassManagement extends Model
{
    use HasFactory;

    protected $table = 'class_management';

    protected $primaryKey = 'classroom_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'classroom_id',
        'branch',
        'batch_year',
        'tutor_mobile_no',
        'mentor_mobile_no',
    ];

    /**
     * Relationship: The tutor staff member assigned to this class.
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'tutor_mobile_no', 'mobile_no');
    }

    /**
     * Relationship: The mentor staff member assigned to this class.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'mentor_mobile_no', 'mobile_no');
    }

    /**
     * Relationship: All students enrolled in this classroom.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'classroom_id', 'classroom_id');
    }
}
