<?php

namespace Tests\Unit;

use App\Http\Controllers\TasksBoardController;
use App\Models\DepartmentsModel;
use App\Models\EmployeeInformationModel;
use App\Models\Tasks\TasksProjectModel;
use App\Models\Tasks\TasksStatusModel;
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
        $setTime = time();
        $setTime = strval($setTime);

        $response = $this->actingAs($user)->post('/tasks/create_new_project/insert', [
            'project_name' => 'Test Project' . $setTime,
            'department_id' => $department->id,
            'project_manager_id' => $user->id,
            'counter' => 1,
            'project_status_field1' => 'Status 1',
        ]);

        $this->assertDatabaseHas('tasks_project', [
            'name' => 'Test Project' . $setTime,
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
        $controller = Mockery::mock(TasksBoardController::class. '[checkIfProjectSettingsAccess]');
        $controller->shouldReceive('checkIfProjectSettingsAccess')->once()->andReturn(true);


        $this->app->instance(TasksBoardController::class, $controller);

        $user = UserModel::factory()->create([
            'role_id' => 2,
        ]);

        $response = $this->actingAs($user)
            ->post('/tasks/project_settings/update_leader/1', [
            'project_leader' => $user->id,
        ]);

        $this->assertDatabaseHas('tasks_project', [
            'id' => 1,
            'leader_user_id' => $user->id,
        ]);
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
        $response->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks_project', ['id' => $project->id]);
    }
}

