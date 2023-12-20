<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\UserModel;

class EmployeeInformationModel extends Model
{
    use HasFactory;

    protected $table = 'employee_information';

    protected $fillable = [
        'user_id',
        'department_id',
        'hour_pay',
        'salary',
        'position',
        'monthly_hours',
        'bank_name',
        'bank_account_name',
        'bank_account'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(DepartamentsModel::class, 'department_id', 'id');
    }
}
