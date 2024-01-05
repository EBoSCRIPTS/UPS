<?php

namespace Tests\Unit;

use App\Http\Controllers\DepartmentsController;
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
}
