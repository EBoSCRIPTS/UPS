<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\DepartmentsModel;
use Tests\TestCase;

class DepartmentsTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic unit test example.
     */
    public function test_department_creation(): void
    {
        $time = time();
        $deptName = 'unit_test' . strval($time);
        $deptDesc = 'unit_test' . strval($time);
        $data = ['departament' => $deptName, 'description' => $deptName];

        $response = $this->post('/departments/create', $data);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Department added successfully');

        $this->assertDatabaseHas('departaments', [
            'name' => 'unit_test' . $time,
            'description' => 'unit_test' . $time,
        ]);
    }
    public function test_department_deletion(): void
    {
        $getDept = DepartmentsModel::query()->orderBy('id', 'desc')->first();

        $data =['id' => $getDept->id];
        $response = $this->post('/departments/delete', $data);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Department deleted successfully');
    }

    public function test_department_deletion_with_employees()
    {
        $time = time();
        $deptName = 'unit_test' . strval($time);
        $deptDesc = 'unit_test' . strval($time);
        $data = ['departament' => $deptName, 'description' => $deptName];

        $response = $this->post('/departments/create', $data);

        $getDept = DepartmentsModel::query()->orderBy('id', 'desc')->first();


    }
}
