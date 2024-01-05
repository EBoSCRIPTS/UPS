<?php

namespace Database\Factories\Tasks;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TasksStatusModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'statuses' => '["todo", "in progress", "on hold", "done"]',
        ];
    }
}
