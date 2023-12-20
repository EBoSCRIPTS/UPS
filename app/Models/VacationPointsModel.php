<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationPointsModel extends Model
{
    use HasFactory;

    protected $table = 'vacation_points';

    protected $fillable = [
        'user_id',
        'vacation_points'
    ];

    public $timestamps = false;
}
