<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogHoursModel extends Model
{
    use HasFactory;

    protected $table = 'logged_hours';

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'break_time',
        'total_hours',
        'night_hours',
        'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
