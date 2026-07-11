<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticalTestMark extends Model
{
    protected $fillable = [
        'practical_test_id',
        'reg_no',
        'co_tag',
        'marks_obtained'
    ];

    public function practicalTest()
    {
        return $this->belongsTo(PracticalTest::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'reg_no', 'reg_no');
    }
}
