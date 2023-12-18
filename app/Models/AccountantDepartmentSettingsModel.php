<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountantDepartmentSettingsModel extends Model
{
    use HasFactory;

    protected $table = 'accountant_department_settings';

    protected $fillable = [
        'department_id',
        'tax_name',
        'tax_rate',
    ];
}
