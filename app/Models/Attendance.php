<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Attendance extends Model
{
    protected $fillable = [
        'application_id',
        'recorded_by',
        'centre',
        'attendance_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function participant()
    {
        return $this->belongsTo(
            Application::class,
            'application_id',
            'APL_ID'
        );
    }

    public function recorder()
    {
        return $this->belongsTo(
            User::class,
            'recorded_by'
        );
    }
    
}