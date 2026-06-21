<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentoringBatch extends Model
{
    protected $table = 'mentoring_batches';

    protected $fillable = [
        'classroom_id',
        'reg_no',
        'mentor_no',
        'batch_label',
        'assigned_by',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'mentor_no', 'mobile_no');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(ClassManagement::class, 'classroom_id', 'classroom_id');
    }
}
