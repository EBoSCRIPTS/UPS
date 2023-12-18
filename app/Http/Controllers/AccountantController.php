<?php

namespace App\Http\Controllers;

use App\Models\LogHoursModel;
use Illuminate\Http\Request;
use App\Models\DepartamentsModel;
use App\Http\Controllers\DepartmentsController;
use App\Models\EmployeeInformationModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AccountantController extends Controller
{
    public function showAll()
    {
        $departments  = DepartamentsModel::all();
        $departmentCounts = [];
        foreach ($departments as $department) {
            $departmentCounts[$department->name] = $department->employeeInformation()->count();
        }

        return view('accountant.accountant_view', ['departments' => $departments, 'departmentCounts' => $departmentCounts]);
    }

    public function showDept(Request $request)
    {
        $showDept = DepartamentsModel::query()->find($request->id);
        $showEmployees = EmployeeInformationModel::query()->where('department_id', $request->id)->get();

        $employeeReports = [];

        foreach ($showEmployees as $employee) {
            $employeeReports[$employee->user_id] = $this->getEmployeeWorkedHoursThisMonth($employee->user_id);
        }

        return view('accountant.accountant_view_department',
            ['department' => $showDept,
                'employees' => $showEmployees,
                'employeeReports' => $employeeReports,
                'month' => Carbon::now()->monthName]);
    }

    public function loadEmployeeInformation(Request $request)
    {
        $employee = EmployeeInformationModel::query()->where('user_id', $request->employee)->first();
        $showEmployees = EmployeeInformationModel::query()->where('department_id', $request->id)->get();

        $getHours = $this->getEmployeeWorkedHoursThisMonth($request->employee);
        $expectedPay = $employee->hour_pay * $getHours;

        return view('accountant.accountant_view_department', ['employeeInformation' => $employee, 'employees' => $showEmployees, 'loadEmployee' => true, 'hours' => $getHours, 'expectedPay' => $expectedPay]);

    }

    private function getEmployeeWorkedHoursThisMonth($userId)
    {
        $logHours = LogHoursModel::query()
            ->where('user_id', $userId)
            ->where('date', '<=', Carbon::now())
            ->where('date', '>=', Carbon::now()->startOfMonth())
            ->pluck('total_hours')->toArray();


        $totalHours = 0;
        for ($i = 0; $i < sizeOf($logHours); $i++) {
            $time = $logHours[$i];
            $time = explode(':', $time);
            $hoursToSeconds = $time[0] * 3600;
            $minutesToSeconds = $time[1] * 60;
            $time = ceil(($hoursToSeconds + $minutesToSeconds) / 3600);
            $totalHours += $time;
        }
        return $totalHours;
    }
}
