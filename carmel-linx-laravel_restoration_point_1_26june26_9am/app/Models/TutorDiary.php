<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TutorDiary extends Model
{
    protected $table = 'tutor_diaries';

    protected $primaryKey = 'diary_id';
    public    $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'diary_id',
        'reg_no',
        'date',
        'category',
        'discussion_notes',
        'action_taken',
        'remarks',
        'student_remarks',
        'logged_by',
        'entry_source',
        'approval_status',
        'approved_by',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->diary_id)) {
                $model->diary_id = (string) Str::uuid();
            }
        });
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }

    public function loggedByStaff(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'logged_by', 'mobile_no');
    }

    public function approvedByStaff(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'approved_by', 'mobile_no');
    }
}
