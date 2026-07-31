<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentNotice extends Model
{
    use HasFactory;

    protected $table = 'department_notices';

    protected $fillable = [
        'department',
        'title',
        'content',
        'target_audience',
        'priority',
        'created_by',
        'author_name',
    ];
}
