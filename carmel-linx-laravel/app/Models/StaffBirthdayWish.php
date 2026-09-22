<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffBirthdayWish extends Model
{
    use HasFactory;

    protected $table = 'staff_birthday_wishes';

    protected $fillable = [
        'wish_date',
        'celebrant_mobile_no',
        'sender_mobile_no',
        'sender_name',
        'emoji',
        'message',
    ];

    /**
     * Celebrant staff profile relationship.
     */
    public function celebrant()
    {
        return $this->belongsTo(StaffProfile::class, 'celebrant_mobile_no', 'mobile_no');
    }

    /**
     * Sender staff profile relationship.
     */
    public function sender()
    {
        return $this->belongsTo(StaffProfile::class, 'sender_mobile_no', 'mobile_no');
    }

    /**
     * Scope query to wishes for a specific date.
     */
    public function scopeForDate($query, string $date)
    {
        return $query->where('wish_date', $date);
    }

    /**
     * Scope query to wishes for a specific celebrant.
     */
    public function scopeForCelebrant($query, string $celebrantMobileNo)
    {
        return $query->where('celebrant_mobile_no', $celebrantMobileNo);
    }
}
