<?php

namespace Tests\Unit;

use App\Http\Controllers\TasksController;
use App\Models\DepartmentsModel;
use App\Models\EmployeeInformationModel;
use App\Models\Tasks\TasksProjectModel;
use App\Models\Tasks\TasksStatusModel;
use App\Models\Tasks\TasksTaskModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Mockery;
use App\Models\Tasks\TasksParticipantsModel;


class TasksTest extends TestCase
{
    use WithoutMiddleware;

    public function test_create_project()
    {
        $user = UserModel::factory()->create(['role_id' => 2]);
        $department = DepartmentsModel::factory()->create();
        $employee = EmployeeInformationModel::factory()->create(['user_id' => $user->id, 'department_id' => $department->id]);

        $response = $this->actingAs($user)->post('/tasks/create_new_project/insert', [
            'project_name' => 'Test Project' . time(),
            'department_id' => $department->id,
            'project_manager_id' => $user->id,
            'counter' => 1,
            'project_status_field1' => 'Status 1',
        ]);

        $this->assertDatabaseHas('tasks_project', [
            'name' => 'Test Project' . time(),
            'department_id' => $department->id,
        ]);
    }

    public function test_access_to_project_settings_as_user()
    {
        $user = UserModel::factory()->create(['role_id' => 5]);

        $this->actingAs($user);

        $response = $this->get('/tasks/project_settings/5');

        $response->assertRedirect('/');
    }

    public function test_update_project_leader()
    {
        $data = [
            'project_id' => 1,
            'project_leader' => 2,
        ];

        $mockedProjectModel = Mockery::mock(TasksProjectModel::class);

        $mockedProjectModel->shouldReceive('where')
            ->with('id', $data['project_id'])
            ->andReturnSelf();
        $mockedProjectModel->shouldReceive('first')->andReturnSelf();
        $mockedProjectModel->shouldReceive('update')
            ->with(['leader_user_id' => $data['project_leader']])
            ->andReturn(true);
        $this->app->instance(TasksProjectModel::class, $mockedProjectModel);

        $response = $this->post('/tasks/project_settings/update_leader/1', $data);

        $response->assertRedirect();
    }

    public function test_update_project_leader_with_invalid_user()
    {
        $data = [
            'project_id' => 1,
            'project_leader' => 9999999, //big ID that doesn't exist in our test database(hopefully)
        ];

        $response = $this->post('/tasks/project_settings/update_leader/1', $data);

        $response->assertSessionHasErrors('project_leader');
    }

    public function test_creates_new_task()
    {
        $user = UserModel::factory()->create(['role_id' => 2]);
        $project = TasksProjectModel::factory()->create();
        $statuses = TasksStatusModel::factory()->create(['project_id' => $project->id]);

        $this->actingAs($user);

        $data = [
            'task_name' => 'Test Task' . $user->id,
            'description' => 'Test Description',
            'project' => $project->id,
            'made_by' => $user->id,
            'assign_to' => '4 UNIT TEST',
            'priority' => 'medium',
            'task_label' => 'feature',
            'task_points' => 1,
        ];

        $response = $this->post('/tasks/create_new_task/create', $data);

        $response->assertSessionHas('success', 'Task created successfully');

        $user->delete();
        $statuses->delete();
        $project->delete();
    }

    /** @test */
    public function test_fails_if_project_has_participants()
    {
        $project = TasksProjectModel::factory()->create();
        $user = UserModel::factory()->create(['role_id' => 1]);
        TasksParticipantsModel::factory()->create(['project_id' => $project->id, 'employee_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post('/tasks/project_settings/delete/' . $project->id);

        $response->assertSessionHasErrors('project');
    }

    public function test_deletes_the_project_successfully()
    {
        $user = UserModel::factory()->create(['role_id' => 1]);
        $project = TasksProjectModel::factory()->create(['id' => random_int(1000, 9000)]);


        $response = $this->actingAs($user)
            ->post('/tasks/project_settings/delete/' . $project->id);
        $response->assertRedirect('/tasks/projects/');
        $this->assertDatabaseMissing('tasks_project', ['id' => $project->id]);
    }
}

