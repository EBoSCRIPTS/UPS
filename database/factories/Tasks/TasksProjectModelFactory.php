<?php

namespace Database\Factories\Tasks;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tasks\TasksProjectModel;


class TasksProjectModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'unit test name'.time(),
            'department_id' => 1,
            'leader_user_id' => NULL,
        ];
    }
}
