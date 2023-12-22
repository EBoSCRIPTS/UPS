<?php

namespace App\Http\Controllers;

use App\Models\VacationPointsModel;
use Illuminate\Http\Request;
use App\Models\DepartamentsModel;
use App\Models\EmployeeInformationModel;
use App\Models\EmployeeVacationsModel;
use App\Models\UserModel;

class EmployeeInformationController extends Controller
{
    public function getEmployeeInformationData()
    {
        $users = UserModel::all();
        $departments = DepartamentsModel::all();
        $employees = EmployeeInformationModel::all();

        $getNotSigned = []; //keep value of those users that are not registered as employees

        foreach ($users as $user) {
            if(in_array($user->id, $employees->pluck('user_id')->toArray())) {
                continue;
            }
            $getNotSigned[] = $user;
        }

        return view('employee_information', ['users' => $getNotSigned, 'departments' => $departments, 'employees' => $employees]);
    }

    public function insertEmployeeInformation(Request $request)
    {
        $information = new EmployeeInformationModel([
            'user_id' => $request->input('employee_id'),
            'department_id' => $request->input('department_id'),
            'hour_pay' => $request->input('hour_pay'),
            'salary' => $request->input('salary'),
            'position' => $request->input('position'),
            'monthly_hours' => $request->input('hours'),
        ]);
        $information->save();

        $vacation = new VacationPointsModel([
            'user_id' => $request->input('employee_id'),
            'vacation_points' => 0
        ]);

        $vacation->save();

        return redirect('/employee_information');
    }

    public function deleteEmployee(Request $request)
    {
        $employee = EmployeeInformationModel::query()->find($request->input('employee_id'));
        $employee->delete();

        return redirect('/employee_information');
    }

    public function getAllEmployees()
    {
        return EmployeeInformationModel::query()->select('id', 'user_id')->get();
    }
}
