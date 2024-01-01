<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeVacationsModel extends Model
{
    protected $table = 'employee_vacations';

    protected $fillable = [
        'user_id',
        'date_from',
        'date_to',
        'is_paid',
        'absence_req_id'
    ];
}
