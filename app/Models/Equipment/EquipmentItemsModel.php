<?php

namespace App\Models\Equipment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentItemsModel extends Model
{
    use HasFactory;

    protected $table = 'equipment_items';

    protected $fillable = [
        'type',
        'name',
        'number_of_units',
    ];
}
