<?php

namespace App\Models\Equipment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentItemsModel extends Model
{
    use HasFactory;

    protected $table = 'equipment_items';

    protected $fillable = [
        'type_id',
        'name',
        'serial_number',
        'is_assigned'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(EquipmentTypeModel::class, 'type', 'id');
    }
}
