<?php

namespace Database\Factories\Tasks;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TasksTaskModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => 'task'.time(),
            'description' => 'task description'.time(),
            'made_by' => 1,
            'assigned_to' => 1,
            'status_key' => 0,
            'priority' => 'critical',
            'task_points' => 5,
            'is_draft' => 0,
            'project_id' => 1,
            'label' => 'feature',
        ];
    }
}
