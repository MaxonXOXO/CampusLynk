<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutiveFlashNotice extends Model
{
    use HasFactory;

    protected $table = 'executive_flash_notices';

    protected $fillable = [
        'sender_id',
        'sender_role',
        'sender_name',
        'title',
        'content',
        'priority',
        'target_audience',
        'target_department',
        'target_semester',
        'attachment_path',
        'attachment_type',
        'dispatch_type',
        'scheduled_at',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'scheduled_at' => 'datetime',
    ];
}
