<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualLearningMaterial extends Model
{
    protected $table = 'virtual_learning_materials';

    protected $fillable = [
        'batch_subject_id',
        'subject_code',
        'classroom_id',
        'room_type',
        'experiment_or_topic_no',
        'title',
        'pre_class_instruction',
        'material_type',
        'file_path',
        'video_url',
        'is_pre_class_notice',
        'target_date',
        'uploaded_by',
    ];

    protected $casts = [
        'is_pre_class_notice' => 'boolean',
        'target_date' => 'date',
    ];
}
