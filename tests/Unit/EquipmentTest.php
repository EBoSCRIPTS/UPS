<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Equipment\EquipmentTypeModel;
use App\Http\Controllers\EquipmentController;
use Illuminate\Http\Request;


class EquipmentTest extends TestCase
{
    /**
     * Test equipment type creation
     */
    public function test_add_equipment_type()
    {
        $data = ['type' => 'NewEquipmentType'];

        $response = $this->post( '/equipment/register/insert', $data);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Type of equipment added successfully');

        $this->assertDatabaseHas('equipment_type', [
            'name' => 'NewEquipmentType'
        ]);
    }

    public function test_add_equipment_item()
    {
        $getTypeId = EquipmentTypeModel::query()->orderBy('id', 'desc')->first();
        $data = ['type' => $getTypeId->id, 'name' => 'NewEquipmentItem', 'serial_number' => '123456789'];

        $response = $this->post( '/equipment/register/add', $data);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Equipment item added successfully');

        $this->assertDatabaseHas('equipment_items', [
            'name' => 'NewEquipmentItem',
            'serial_number' => '123456789',
        ]);
    }

    public function test_delete_equipment_type_with_equipment()
    {
        $getLatestCreated = EquipmentTypeModel::query()->orderBy('id', 'desc')->first();
        $data = ['id' => $getLatestCreated->id];

        $response = $this->post('/equipment/delete_type/', $data);

        $response->assertStatus(302);
    }
}
