<?php

namespace App\ExcelExport;

use App\Models\PerformanceReportsModel;
use App\Models\Tasks\TasksTaskModel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel as MaatwebsiteExcel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerformanceExport implements FromCollection, WithHeadings
{
    public function __construct($project_id){
        $this->project_id = $project_id;
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Rating Text',
            'Rating',
            'Month',
            'Year'
        ];
    }
    public function collection()
    {
        return PerformanceReportsModel::query()->where('project_id', $this->project_id)
            ->where('soft_deleted', 0)
            ->where('year', Carbon::now()->year)
            ->where('month', Carbon::now()->monthName)
            ->select('employee_name', 'rating_text', 'rating', 'month', 'year')
            ->get();
    }
}
