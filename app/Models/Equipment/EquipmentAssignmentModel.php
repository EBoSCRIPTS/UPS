<?php

namespace App\Models\Equipment;

use App\Models\EmployeeInformationModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentAssignmentModel extends Model
{
    use HasFactory;

    protected $table = 'equipment_assignment';

    protected $fillable = [
        'equipment_id',
        'employee_id',
        'date_given',
    ];

    public $timestamps = false;

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(EquipmentItemsModel::class, 'equipment_id', 'id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeInformationModel::class, 'employee_id', 'id');
    }
}
