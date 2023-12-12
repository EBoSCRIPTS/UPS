<?php

namespace App\Models\Equipment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
