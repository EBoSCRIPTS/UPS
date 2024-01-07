<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AbsenceModel>
 */
class AbsenceModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_date' => '2023-11-01',
            'end_date' => '2023-11-03',
            'reason' => 'Sick',
            'comment' => 'I am sick',
            'attachment' => null
        ];
    }
}
