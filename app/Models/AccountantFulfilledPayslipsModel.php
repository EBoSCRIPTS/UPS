<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountantFulfilledPayslipsModel extends Model
{
    use HasFactory;

    protected $table = 'accountant_fulfilled_payslips';

    protected $fillable = [
        'employee_id',
        'department_id',
        'month',
        'loghours_submitted_id',
    ];
}
