<?php

namespace Tests\Unit;

use App\Http\Controllers\DepartmentsController;
use App\Models\EmployeeInformationModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\DepartmentsModel;
use Tests\TestCase;
use Mockery;

class DepartmentsTest extends TestCase
{
    use WithoutMiddleware;

    public function test_department_creation(): void
    {
        $this->withoutExceptionHandling();

        $data = [
            'departament' => 'unit test'.time(),
            'description' => 'unit test description',
        ];

        $response = $this->post('/departments/create', $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Department added successfully');
        $this->assertDatabaseHas('departaments', [
            'name' => $data['departament'],
            'description' => $data['description'],
        ]);
    }

    public function test_department_deletion_with_employees(): void
    {
        $controller = Mockery::mock(DepartmentsController::class . '[checkIfDeptHasEmployees]');
        $controller->shouldReceive('checkIfDeptHasEmployees')->once()->andReturn(true);

        $this->app->instance(DepartmentsController::class, $controller);

        $response = $this->post('/departments/delete', ['id' => 1]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('error', 'There are employees in this department. You can not delete it!');
    }

    public function test_if_loads_user_department_successfully()
    {
        $user = UserModel::factory()->create(['role_id' => 3]);
        $department = DepartmentsModel::factory()->create();
        $employee = EmployeeInformationModel::factory()->create([
            'user_id' => $user->id,
            'department_id' => $department->id
        ]);

        $response = $this->actingAs($user)->get('/departments/my');

        $response->assertStatus(200);
        $response->assertViewIs('my_department');
        $response->assertViewHas('department', $department);
        $returnedDepartment = $response->viewData('department');
        $this->assertEquals($department->id, $returnedDepartment->id);
    }
}
