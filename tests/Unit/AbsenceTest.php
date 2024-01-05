<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Illuminate\Http\UploadedFile;
use App\Models\UserModel;
use App\Http\Controllers\AbsenceController;

class AbsenceTest extends TestCase
{
    use WithoutMiddleware;

    public function test_add_absence(): void
    {
        $user = UserModel::factory()->create([
            'role_id' => 2
        ]);
        $this->actingAs($user);

        Storage::fake('local');

        $data = [
            'user_id' => $user->id,
            'start_date' => '2023-11-05',
            'end_date' => '2023-11-06',
            'reason' => 'Sick',
            'comment' => 'I am sick',
            'attachment' => UploadedFile::fake()->create('attachment.pdf', 1024)
        ];

        $response = $this->post('/absence/create', $data);
        $user->delete();

        $response->assertSessionHas('success');
    }


    public function test_wrong_file_extension(): void
    {
        Storage::fake('local');

        $data = [
            'user_id' => 1,
            'start_date' => '2023-11-05',
            'end_date' => '2023-11-06',
            'reason' => 'sick',
            'attachment' => UploadedFile::fake()->create('badfile.exe', 1024)
        ];

        $response = $this->post('/absence/create', $data);

        $response->assertSessionHasErrors('attachment');
    }

    public function test_file_too_big(): void
    {
        Storage::fake('local');

        $data = [
            'user_id' => 1,
            'start_date' => '2023-11-05',
            'end_date' => '2023-11-06',
            'reason' => 'sick',
            'attachment' => UploadedFile::fake()->create('bigfile.pdf', 15000)
        ];

        $response = $this->post('/absence/create', $data);

        $response->assertSessionHasErrors('attachment');
    }

    public function test_if_can_delete_reviewed()
    {
        $controller = Mockery::mock(AbsenceController::class . '[checkIfAbsenceReviewed]');
        $controller->shouldReceive('checkIfAbsenceReviewed')->once()->andReturn(true);

        $this->app->instance(AbsenceController::class, $controller);

        $response = $this->post('/absence/delete', ['id' => 1]);

        $response->assertSessionHas('error', 'Absence cannot be deleted, it has already been reviewed!');
    }
}
