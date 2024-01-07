<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountantFulfilledPayslipsModel extends Model
{
    use HasFactory;

    protected $table = 'accountant_fulfilled_payslips';

    protected $fillable = [
        'employee_id',
        'department_id',
        'month',
        'year',
        'loghours_submitted_id',
        'fulfilled_by',
        'payslip_file',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeInformationModel::class, 'employee_id', 'id');
    }

    public function fulfilledBy():  BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'fulfilled_by', 'id');
    }
}
