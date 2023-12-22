<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmittedTicketsModel extends Model
{
    use HasFactory;

    protected $table = 'submitted_tickets';

    protected $fillable = [
        'user_id',
        'ticket_title',
        'ticket_text',
        'department_id',
        'is_registered',
        'registered_by_user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

}
