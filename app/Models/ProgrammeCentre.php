<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammeCentre extends Model
{
    protected $table = 'programme_centres';

    protected $fillable = [
        'name',
        'address',
        'location',
    ];
}
