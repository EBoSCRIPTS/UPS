<?php

namespace Tests\Unit;

use App\Http\Controllers\LogHoursController;
use App\Models\LogHoursModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LogHoursTest extends TestCase
{
    use WithoutMiddleware;
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

        $this->assertEquals('8:00', $time[0]);
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

    public function test_night_hours_calculation_start_same_end_same_before_midnight(): void
    {
        $controller = new LogHoursController();

        $startTime = strtotime('20:00');
        $endTime = strtotime('23:45');

        $time = $controller->calculateHours($startTime, $endTime, 0);

        $this->assertEquals(2, $time[1]);
    }

    public function test_if_can_log_hours(): void
    {
        $user = UserModel::factory()->create(['role_id' => 3]);

        $data = [
            'user_id' => $user->id,
            '2024-01-01_start_time' => '09:00',
            '2024-01-01_end_time' => '18:00',
            '2024-01-01_date' => '2024-01-01',
        ];

        $response = $this->actingAs($user)
            ->post('/loghours/create', $data);

        $response->assertRedirect('/loghours');
        $response->assertSessionHas('success', 'Hours inserted!');
    }

    public function test_delete_logged_hours()
    {
        $user = UserModel::factory()->create(['role_id' => 3]);

        $loggedHour = LogHoursModel::factory()->create([
            'user_id' => $user->id,
            'start_time' => '09:00',
            'end_time' => '18:00',
            'date' => '2023-01-01',
            'total_hours' => '8:00',
            'night_hours' => 0
        ]);

        $deleteData = ['id' => $loggedHour->id];

        $response = $this->actingAs($user)->post('/loghours/view/delete', $deleteData);

        $this->assertDatabaseMissing('logged_hours', [
            'id' => $loggedHour->id,
            'user_id' => $user->id,
        ]);

        $response->assertRedirect('/loghours');
        $response->assertSessionHas('success', 'Hours deleted!');
    }
}
