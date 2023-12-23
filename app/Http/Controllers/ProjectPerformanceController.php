<?php

namespace App\Http\Controllers;

use App\ExcelExport\PerformanceExport;
use App\ExcelExport\TaskExport;
use App\Models\EmployeeInformationModel;
use App\Models\Tasks\TasksParticipantsModel;
use App\Models\PerformanceReportsModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel as MaatwebsiteExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProjectPerformanceController extends Controller
{
    public function loadProjectPerformance(Request $request): \Illuminate\View\View //loads page for project performance(specific project)
    {
        $projectMembers = TasksParticipantsModel::query()->where('project_id', $request->project_id)->get();
        $reportedMonthPerformance = PerformanceReportsModel::query()->where('project_id', $request->project_id)
            ->where('soft_deleted', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $userPerformance = [];
        $addedValuesCount= [];

        foreach($reportedMonthPerformance as $performance){
            if(!isset($userPerformance[$performance->user_id]) && !isset($addedValuesCount[$performance->user_id])){
                $userPerformance[$performance->user_id] = $performance->rating;
                $addedValuesCount[$performance->user_id] = 1;
            }
            else {
                $userPerformance[$performance->user_id] += $performance->rating;
                $addedValuesCount[$performance->user_id]++;
            }
        }

        foreach($userPerformance as $key => $value){
            $userPerformance[$key] = $value / $addedValuesCount[$key];
            $userPerformance[$key] = intval($userPerformance[$key], 0);
        }

        return view('tasks.tasks_employee_performance', ['projectId' => $request->project_id,
            'projectMembers' => $projectMembers,
            'reportedMonthPerformance' => $reportedMonthPerformance,
            'userPerformance' => $userPerformance]);
    }

    public function makeReport(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'sometimes|exists:tasks_project,id|integer',
            'employee_id' => 'required|exists:employee_information,id|integer',
            'performance_report' => 'required',
            'performance_rating' => 'required|integer|between:0,100',
        ]);

        $getEmployeeId = EmployeeInformationModel::query()->where('id', $request->input('employee_id'))->pluck('user_id')->first();
        $getName = UserModel::query()->where('id', $getEmployeeId)->select('id', 'first_name', 'last_name')->first();

        $report = new PerformanceReportsModel([
            'project_id' => $request->project_id,
            'user_id' => $getName->id,
            'employee_name' => $getName->first_name . ' ' . $getName->last_name,
            'rating_text' => $request->input('performance_report'),
            'rating' => $request->input('performance_rating'),
            'year' => Carbon::now()->year,
            'month' => Carbon::now()->monthName,
        ]);

        $report->save();

        return back()->with('success', 'Performance report created successfully!');
    }

    public function softDeleteReport(Request $request): RedirectResponse
    {
        $getReport = PerformanceReportsModel::query()->where('id', $request->report_id)->first();

        $getReport->update([
            'soft_deleted' => 1
        ]);

        return back()->with('success', 'Performance report deleted successfully!');
    }

    public function generatePerformanceReportXlsx(Request $request): BinaryFileResponse
    {
        return MaatwebsiteExcel::download(new PerformanceExport($request->project_id), 'performance_report.xlsx',\Maatwebsite\Excel\Excel::XLSX);

    }
}
