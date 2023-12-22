<?php

namespace App\Http\Controllers;

use App\ExcelExport\PerformanceExport;
use App\ExcelExport\TaskExport;
use App\Models\EmployeeInformationModel;
use App\Models\Tasks\TasksParticipantsModel;
use App\Models\PerformanceReportsModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel as MaatwebsiteExcel;

class ProjectPerformanceController extends Controller
{
    public function loadProjectPerformance(Request $request) //loads page for project performance(specific project)
    {
        $projectMembers = TasksParticipantsModel::query()->where('project_id', $request->project_id)->get();

        return view('tasks.tasks_employee_performance', ['projectId' => $request->project_id, 'projectMembers' => $projectMembers]);
    }

    public function makeReport(Request $request)
    {
        $getName = UserModel::query()->where('id', $request->input('employee_id'))->select('first_name', 'last_name')->first();
        $report = new PerformanceReportsModel([
            'project_id' => $request->project_id,
            'employee_id' => $request->input('employee_id'),
            'employee_name' => $getName->first_name . ' ' . $getName->last_name,
            'rating_text' => $request->input('performance_report'),
            'rating' => $request->input('performance_rating'),
            'year' => Carbon::now()->year,
            'month' => Carbon::now()->monthName,
        ]);

        $report->save();

        return back()->with('success', 'Performance report created successfully!');
    }

    public function generatePerformanceReportXlsx(Request $request)
    {
        return MaatwebsiteExcel::download(new PerformanceExport($request->project_id), 'performance_report.xlsx',\Maatwebsite\Excel\Excel::XLSX);

    }
}
