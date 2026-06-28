<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'performed_by',
        'performed_by_name',
        'target_id',
        'target_name',
        'action',
        'details',
        'ip_address',
    ];
}
