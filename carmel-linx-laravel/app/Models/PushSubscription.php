<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    use HasFactory;

    protected $table = 'push_subscriptions';

    protected $fillable = [
        'user_id',
        'role',
        'endpoint',
        'p256dh_key',
        'auth_key',
        'device_type',
    ];

    /**
     * Scope query to subscriptions belonging to a specific user.
     */
    public function scopeForUser($query, string $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope query to subscriptions belonging to a specific role.
     */
    public function scopeForRole($query, string $role)
    {
        return $query->where('role', strtolower($role));
    }
}
