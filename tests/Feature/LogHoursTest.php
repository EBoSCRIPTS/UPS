<?php

namespace Tests\Feature;

use App\Http\Controllers\LogHoursController;
use App\Models\LogHoursModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogHoursTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_total_time_calculation(): void
    {
        $controller = new LogHoursController();

        $startTime = strtotime('9:00');
        $endTime = strtotime('18:00');
        $breakTime = 3600;

        $time = $controller->calculateHours($startTime, $endTime, $breakTime);

        $this->assertEquals('08:00', $time[0]);
    }

    public function test_night_hours_calculation_start_prev_end_next(): void
    {
        $controller = new LogHoursController();

        $startTime = strtotime('23:00');
        $endTime = strtotime('06:00');
        $breakTime = 3600;

        $time = $controller->calculateHours($startTime, $endTime, $breakTime);

        $this->assertEquals(6, $time[1]);
    }

    public function test_night_hours_calculation_start_same_end_same(): void
    {
        $controller = new LogHoursController();

        $startTime = strtotime('01:00');
        $endTime = strtotime('07:00');

        $time = $controller->calculateHours($startTime, $endTime, 0);

        $this->assertEquals(5, $time[1]);
    }
}
