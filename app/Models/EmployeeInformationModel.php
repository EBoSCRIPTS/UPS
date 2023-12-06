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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
