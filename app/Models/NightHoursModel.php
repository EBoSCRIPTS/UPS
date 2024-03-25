<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NightHoursModel extends Model
{
    use HasFactory;
    protected $table = 'night_hours';

    public $timestamps = false;
}
