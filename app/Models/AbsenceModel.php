<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenceModel extends Model
{
    use HasFactory;

    protected $table = 'req_absence';

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'duration',
        'type',
        'reason',
        'status',
        'comment',
        'sent_by',
        'created_at',
        'attachment',
        'approver_id',
        'date_approved'
    ];
}
