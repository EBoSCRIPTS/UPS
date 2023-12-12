<?php

namespace App\Models\Equipment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentTypeModel extends Model
{
    use HasFactory;

    protected $table = 'equipment_type';

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
