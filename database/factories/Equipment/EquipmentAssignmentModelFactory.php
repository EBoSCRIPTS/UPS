<?php

namespace Database\Factories\Equipment;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EquipmentAssignmentsModel>
 */
class EquipmentAssignmentModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date_given' => Carbon::now()->toDate(),
        ];
    }
}
