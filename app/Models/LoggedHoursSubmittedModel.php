<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoggedHoursSubmittedModel extends Model
{
    use HasFactory;

    protected $table = 'logged_hours_submitted';

    protected $fillable = [
       'user_id',
       'total_hours',
        'month_name',
       'created_at'
    ];

    public $timestamps = false;
}
