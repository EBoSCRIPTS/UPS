<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\Equipment\EquipmentTypeModel;
use App\Http\Controllers\EquipmentController;
use Illuminate\Http\Request;
use Mockery;


class EquipmentTest extends TestCase
{
    use WithoutMiddleware;
    public function test_add_equipment_type()
    {
        $data = ['type' => 'NewEquipmentType'.time()];

        $response = $this->post( '/equipment/register/insert', $data);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Type of equipment added successfully');

        $this->assertDatabaseHas('equipment_type', [
            'name' => 'NewEquipmentType'
        ]);
    }

    public function test_add_equipment_item()
    {
        $mockedType = Mockery::mock(EquipmentTypeModel::class);
        $mockedType->shouldReceive('orderBy')->andReturnSelf();
        $mockedType->shouldReceive('first')->andReturn((object)['id' => 1]); // assuming the id is 1
        $this->app->instance(EquipmentTypeModel::class, $mockedType);

        $data = ['type' => 1, 'name' => 'NewEquipmentItem', 'serial_number' => strval(time())];

        $mockedEquipmentItem = Mockery::mock(EquipmentController::class)->makePartial();
        $mockedEquipmentItem->shouldReceive('save')->andReturnTrue();
        $this->app->instance(EquipmentController::class, $mockedEquipmentItem);

        $response = $this->post('/equipment/register/add', $data);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Equipment item added successfully');
    }

    public function test_delete_equipment_type_with_equipment()
    {
        $mockedType = Mockery::mock(EquipmentTypeModel::class);
        $mockedType->shouldReceive('orderBy')->andReturnSelf();
        $mockedType->shouldReceive('first')->andReturn((object)['id' => 1]); // assuming the id is 1
        $mockedType->shouldReceive('delete')->andReturn(true); // Mock the delete operation
        $this->app->instance(EquipmentTypeModel::class, $mockedType);

        $data = ['id' => 1];

        $response = $this->post('/equipment/delete_type/', $data);

        $response->assertStatus(302);
    }
}
