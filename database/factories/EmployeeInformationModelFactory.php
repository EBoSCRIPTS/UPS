<?php

namespace Database\Factories;

use App\Models\EmployeeInformationModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeInformationModel>
 */
class EmployeeInformationModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hour_pay' => 10.50,
            'weekly_hours' => 40,
            'position' => 'test'
        ];
    }
}
