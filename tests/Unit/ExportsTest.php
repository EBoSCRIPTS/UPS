<?php

namespace Tests\Unit;

use App\ExcelExport\TaskExport;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserModel;
use Tests\TestCase;

class ExportsTest extends TestCase
{
    use WithoutMiddleware;

    public function test_xlsx_export_working()
    {
        $user = UserModel::factory()->create(['role_id' => 1]);
        $data = [
            'startDate' => '2023-01-01',
            'endDate' => '2023-01-31',
            'project_id' => 5,
        ];
        Excel::fake();

        $response = $this->actingAs($user)
            ->post('/tasks/projects/5/statistics/generate_excel', $data);

        Excel::assertDownloaded('project_statistics.xlsx');
    }

    public function test_xlsx_wrong_data()
    {
        $user = UserModel::factory()->create(['role_id' => 1]);
        $data = [
            'startDate' => '2023-01-31',
            'endDate' => '2023-01-01',
            'project_id' => 5,
        ];

        Excel::fake();

        $response = $this->actingAs($user)
            ->post('/tasks/projects/5/statistics/generate_excel', $data);

        $response->assertSessionHasErrors(['startDate', 'endDate']);
    }

    public function test_pdf_export_working()
    {
        $data = [
            'employee' => '14',
            'equipmentText' => 'this'
        ];

        $response = $this->post('/equipment/generate_agreement', $data);

        $response->assertStatus(200);
        $this->assertTrue(strlen($response->getContent()) > 0);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }
}
