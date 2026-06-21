<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $primaryKey = 'reg_no';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'reg_no',
        'adm_no',
        'name',
        'email',
        'password',
        'phone',
        'branch',
        'admission_year',
        'admission_type',
        'photo_url',
        'classroom_id',
        'status',
        'sbte_reg_no',
        'mentor_mobile_no',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Relationship: The classroom this student belongs to.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(ClassManagement::class, 'classroom_id', 'classroom_id');
    }

    /**
     * Relationship: The assigned staff mentor.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'mentor_mobile_no', 'mobile_no');
    }

    /**
     * Relationship: Student's online test responses.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(StudentResponse::class, 'reg_no', 'reg_no');
    }

    /**
     * Relationship: Student's academic marks list.
     */
    public function academicMarks(): HasMany
    {
        return $this->hasMany(AcademicMark::class, 'reg_no', 'reg_no');
    }
}
