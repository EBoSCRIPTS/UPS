<?php

namespace Tests\Unit;

use App\Models\DepartmentsModel;
use App\Models\EmployeeInformationModel;
use App\Models\Equipment\EquipmentAssignmentModel;
use App\Models\Equipment\EquipmentTypeModel;
use App\Models\UserModel;
use App\Models\Equipment\EquipmentItemsModel;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic unit test example.
     */
    public function test_employee_creation(): void
    {
        $user = UserModel::factory()->create(['role_id' => 1]);
        $department = DepartmentsModel::factory()->create();

        $response = $this->actingAs($user)
            ->post('/employee_information/create', ['employee_id' => $user->id,
                'department_id' => $department->id,
                'hour_pay' => '10.00',
                'hours' => 40,
                'position' => 'test']);

        $user->delete();
        $department->delete();

        $response->assertRedirect('/employee_information');

        $response->assertSessionHas('success', 'Employee created successfully');
    }

    public function test_employee_deletion_with_equipment (): void
    {
        $user = UserModel::factory()->create(['role_id' => 2]);
        $employee = EmployeeInformationModel::factory()->create(['user_id' => $user->id, 'department_id' => 99999]);
        $equipmentType = EquipmentTypeModel::factory()->create(['name' => 'unit_test']);
        $equipmentItem = EquipmentItemsModel::factory()->create(['type_id' => $equipmentType->id, 'name' => 'unit_test', 'serial_number' => 'unit_test', 'is_assigned' => 1]);
        $equipmentAssign = EquipmentAssignmentModel::factory()->create(['equipment_id' => $equipmentItem->id, 'employee_id' => $employee->id]);

        $response = $this->actingAs($user)
            ->post('/employee_information/delete/', ['id' => $employee->id]);

        $response->assertSessionHasErrors('equipment', 'This employee has equipment assigned. Please remove it before deleting');

        $equipmentAssign->delete();
        $equipmentItem->delete();
        $equipmentType->delete();
        $employee->delete();
    }
}
