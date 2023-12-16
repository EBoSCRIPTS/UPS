<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartamentsModel extends Model
{
    use HasFactory;

    protected $table = 'departaments';

    protected $fillable = [
        'name',
        'description'
    ];

    public function employeeInformation(): BelongsTo
    {
        return $this->belongsTo(EmployeeInformationModel::class, 'id', 'department_id');
    }
}
