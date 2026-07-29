<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'application_id',
        'centre',
        'attendance_date',
        'status',
        'remarks',
    ];


    public function participant()
    {
        return $this->belongsTo(
            Application::class,
            'application_id',
            'APL_ID'
        );
    }
}
