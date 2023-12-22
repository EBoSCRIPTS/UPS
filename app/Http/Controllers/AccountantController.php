<?php

namespace App\Http\Controllers;

use App\Models\LoggedHoursSubmittedModel;
use App\Models\LogHoursModel;
use Illuminate\Http\Request;
use App\Models\DepartamentsModel;
use App\Http\Controllers\DepartmentsController;
use App\Models\EmployeeInformationModel;
use App\Models\AccountantDepartmentSettingsModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\AccountantFulfilledPayslipsModel;


class AccountantController extends Controller
{
    public function showAll()
    {
        return view('accountant.accountant_view', ['departments' => DepartamentsModel::all()]);
    }

    //returns information to accountant department view
    public function showDept(Request $request)
    {
        $showDept = DepartamentsModel::query()->find($request->id);
        $showEmployees = EmployeeInformationModel::query()->where('department_id', $request->id)->get();
        $logHours = new LogHoursController();

        $employeeReports = [];
        $status = [];
        $expectedSalary = 0;
        $realSalary = 0;

        foreach ($showEmployees as $employee) {
            $expectedSalary += $employee->hour_pay * $employee->monthly_hours;
            $realSalary += $employee->hour_pay * $this->getEmployeeWorkedHoursThisMonth($employee->user_id);
            $status[$employee->user_id] = $logHours->getSubmitedAndConfirmed($employee->user_id, Carbon::now()->monthName);
            $employeeReports[$employee->user_id] = $this->getEmployeeWorkedHoursThisMonth($employee->user_id);
        }

        return view('accountant.accountant_view_department',
            ['department' => $showDept,
                'employees' => $showEmployees,
                'employeeReports' => $employeeReports,
                'month' => Carbon::now()->monthName,
                'status' => $status,
                'expectedPay' => $expectedSalary,
                'realPay' => $realSalary]);
    }

    public function loadEmployeeInformation(Request $request)
    {
        $employee = EmployeeInformationModel::query()->where('user_id', $request->employee)->first();
        $showEmployees = EmployeeInformationModel::query()->where('department_id', $request->id)->get();

        $getHours = $this->getEmployeeWorkedHoursThisMonth($request->employee);
        $expectedPay = $employee->hour_pay * $getHours;

        return view('accountant.accountant_view_department', ['employeeInformation' => $employee, 'employees' => $showEmployees, 'loadEmployee' => true, 'hours' => $getHours, 'expectedPay' => $expectedPay]);

    }

    //handles currently logged hours for current month(not associated with submitted hours)
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

    public function getDepartmentSettings(Request $request)
    {
        $settings = AccountantDepartmentSettingsModel::query()->where('department_id', $request->department_id)->get();

        return view('accountant.accountant_department_settings', ['settings' => $settings, 'department' => $request->department_id] );
    }

    public function addTax(Request $request)
    {
        $newTax = new AccountantDepartmentSettingsModel([
            'department_id' => $request->department_id,
            'tax_name' => $request->input('tax_name'),
            'tax_rate' => $request->input('tax_rate'),
            'tax_salary_from' => $request->input('tax_salary_from'),
        ]);

        $newTax->save();

        return back()->with('success', 'Tax added!');
    }

    public function deleteTax(Request $request)
    {
        $tax = AccountantDepartmentSettingsModel::query()->find($request->tax_id);
        $tax->delete();

        return back()->with('success', 'Deleted successfully');
    }


    //calculations for payslips, we also handle taxes here, could try to combine 2 ifs
    public function getEmployeePayslipDetails(Request $request)
    {
        $employee = EmployeeInformationModel::query()->where('user_id', $request->user_id)->first();
        $getHours = LoggedHoursSubmittedModel::query()
            ->where('user_id', $request->user_id)
            ->where('month_name', $request->month)
            ->first();

        if ($employee->hour_pay != null){
            $overtimeHours = 0;
            if($employee->monthly_hours < $getHours->total_hours)
            {
                $overtimeHours = $getHours->total_hours - $employee->monthly_hours;
            }

            if (AccountantFulfilledPayslipsModel::query()->where('loghours_submitted_id', $getHours->id)->first() != null)
            {
                $isFullfilled = true;
            }
            else{
                $isFullfilled = false;
            }

            $baseWithoutNights = $getHours->total_hours - $getHours->night_hours - $overtimeHours;
            $baseSalary = $employee->hour_pay * $baseWithoutNights;
            $nightPay = ($employee->hour_pay * $getHours->night_hours) * 1.5;

            $getTaxes = AccountantDepartmentSettingsModel::query()->where('department_id', $request->department_id)
                ->where('tax_salary_from', '<=', $baseSalary + $nightPay + $overtimeHours)
                ->orderBy('tax_salary_from', 'desc') //important to sort descending, otherwise employee might get with the same tax more than once
                ->get()
                ->toArray();

            $uniqueTaxes = [];

            $employeeTaxes = array_filter($getTaxes, function ($item) use (&$uniqueTaxes) { //filter out so we keep first unique values from taxes
                $taxName = $item['tax_name'];
                if (!in_array($taxName, $uniqueTaxes)) {
                    $uniqueTaxes[] = $taxName;
                    return true;
                }
                return false;
            });

            $totalTaxPrec = 0;
            foreach($employeeTaxes as $tax)
            {
                $totalTaxPrec += $tax['tax_rate'];
            }
            $totalTaxPrec = $totalTaxPrec / 100;

            return view('accountant.accountant_payslip', ['taxes' => $employeeTaxes, 'employee' => $employee,
                'hours' => $getHours,
                'month' => $request->month,
                'baseSalary' => $baseSalary,
                'overtimeHours' => $overtimeHours,
                'overtimeSalary' => $overtimeHours * $employee->hour_pay * 1.5,
                'nightSalary' => $nightPay,
                'totalTaxPrec' => $totalTaxPrec,
                'isFullfilled' => $isFullfilled]);
        }

        else if ($employee->salary != null) {
            $getTaxes = AccountantDepartmentSettingsModel::query()->where('department_id', $request->department_id)
                ->where('tax_salary_from', '<=', $employee->salary)
                ->orderBy('tax_salary_from', 'desc')
                ->get()
                ->toArray();

            $uniqueTaxes = [];

            $employeeTaxes = array_filter($getTaxes, function ($item) use (&$uniqueTaxes) {
                $taxName = $item['tax_name'];
                if (!in_array($taxName, $uniqueTaxes)) {
                    $uniqueTaxes[] = $taxName;
                    return true;
                }
                return false;
            });

            $totalTaxPrec = 0;
            foreach ($employeeTaxes as $tax) {
                $totalTaxPrec += $tax['tax_rate'];
            }
            $totalTaxPrec = $totalTaxPrec / 100;


            return view('accountant.accountant_payslip', ['taxes' => $employeeTaxes, 'employee' => $employee,
                'hours' => $getHours,
                'month' => $request->month,
                'baseSalary' => $employee->salary,
                'nightSalary' => 0,
                'overtimeHours' => 0,
                'overtimeSalary' => 0,
                'totalTaxPrec' => $totalTaxPrec]);
        }
    }

    //function that checks if accountant has already fulfilled specific payslip
    public function employeePayslipFulfill(Request $request)
    {
        if (AccountantFulfilledPayslipsModel::query()->where('loghours_submitted_id', $request->hours_id)->first()) {
            return back()->with('error', 'Already fulfilled');
        }

        $fulfill = new AccountantFulfilledPayslipsModel([
            'employee_id' => $request->employee_id,
            'month' => $request->month,
            'department_id' => $request->department_id,
            'loghours_submitted_id' => $request->hours_id
        ]);

        $fulfill->save();

        return back()->with('success', 'Fulfilled successfully');
    }
}
