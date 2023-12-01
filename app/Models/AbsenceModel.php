<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'sent_by',
        'attachment',
        'approver_id',
        'date_approved'
    ];
}
