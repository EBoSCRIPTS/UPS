<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsenceModel extends Model
{
    use HasFactory;

    protected $table = 'req_absence';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'duration',
        'type',
        'reason',
        'status',
        'attachment',
        'approver_id',
        'date_approved'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'approver_id', 'id');
    }
}
