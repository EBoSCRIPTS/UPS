<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoggedHoursSubmittedModel extends Model
{
    use HasFactory;

    protected $table = 'logged_hours_submitted';

    protected $fillable = [
       'user_id',
       'total_hours',
        'night_hours',
        'month_name',
       'created_at',
        'is_confirmed'
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
