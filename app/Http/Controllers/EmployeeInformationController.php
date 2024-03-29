<?php

namespace App\Http\Controllers;

use App\Models\Equipment\EquipmentAssignmentModel;
use App\Models\VacationPointsModel;
use FontLib\TrueType\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\DepartmentsModel;
use App\Models\EmployeeInformationModel;
use App\Models\EmployeeVacationsModel;
use App\Models\Tasks\TasksParticipantsModel;
use App\Models\UserModel;

class EmployeeInformationController extends Controller
{
    public function getEmployeeInformationData(): \Illuminate\View\View
    {
        $users = UserModel::query()->where('soft_deleted', 0)->get();
        $departments = DepartmentsModel::all();
        $employees = EmployeeInformationModel::all();

        $getNotSigned = []; //keep value of those users that are not registered as employees

        foreach ($users as $user) {
            if (in_array($user->id, $employees->pluck('user_id')->toArray())) {
                continue;
            }
            $getNotSigned[] = $user;
        }

        return view('employee_information', ['users' => $getNotSigned, 'departments' => $departments, 'employees' => $employees]);
    }

    public function insertEmployeeInformation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|unique:employee_information,user_id',
            'department_id' => 'required',
            'hour_pay' => 'sometimes|nullable|decimal:2',
            'salary' => 'sometimes|nullable|decimal:2',
            'position' => 'required|string',
            'hours' => 'numeric|required',
        ]);

        $information = new EmployeeInformationModel([
            'user_id' => $request->input('employee_id'),
            'department_id' => $request->input('department_id'),
            'hour_pay' => $request->input('hour_pay'),
            'salary' => $request->input('salary'),
            'position' => $request->input('position'),
            'weekly_hours' => $request->input('hours'),
        ]);

        $information->save();

        $userRoleUpdate = UserModel::query()->where('id', $request->input('employee_id'))->first();

        if ($userRoleUpdate->role_id == 5) //if it's a user without advanced perms, change it to employee role
        {
            $userRoleUpdate->update([
                'role_id' => 2
            ]);
        }

        $vacation = new VacationPointsModel([
            'user_id' => $request->input('employee_id'),
            'vacation_points' => 0
        ]);

        $vacation->save();

        return redirect('/employee_information')->with('success', 'Employee created successfully');
    }

    public function deleteEmployee(Request $request): RedirectResponse
    {
        if (EquipmentAssignmentModel::query()->where('employee_id', $request->input('employee_id'))->count() > 0) //dont delete employee if we still have equipment assigned to him
        {
            return back()->withInput()->withErrors([
                'equipment' => 'This employee has equipment assigned. Please remove it before deleting.'
            ]);
        }
        $participantDelete = TasksParticipantsModel::query()->where('employee_id', $request->input('employee_id'))->get();

        if ($participantDelete != null) { //remove employee from every project he is assigned to
            foreach ($participantDelete as $participant) {
                $participant->delete();
            }
        }

        $employee = EmployeeInformationModel::query()->find($request->input('employee_id'));
        $user = UserModel::query()->find($employee->user_id);

        if ($user->role_id == 3) //if the user is an employee without advanced perms, change it back to user
        {
            $user->update([
                'role_id' => 5
            ]);
        }

        $employee->delete();

        return redirect('/employee_information')->with('success', 'Employee deleted successfully');
    }

    public function editEmployee(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employee_information,id',
            'hour_pay' => 'sometimes|nullable|decimal:2',
            'salary' => 'sometimes|nullable|decimal:2',
            'weekly_hours' => 'sometimes|nullable|numeric',
            'dept_name' => 'sometimes|nullable|string|exists:departaments,id',
        ]);

        $employee = EmployeeInformationModel::query()->where('id', $request->input('employee_id'))->first();

        $employee->update([
            'department_id' => $request->input('dept_name') ?? $employee->department_id,
            'hour_pay' => $request->input('hour_pay') ?? null,
            'salary' => $request->input('salary') ?? null,
            'position' => $request->input('position') ?? $employee->position,
            'weekly_hours' => $request->input('hours') ?? $employee->weekly_hours
        ]);

        return back()->with('success', 'Employee updated successfully');
    }

    public function getAllEmployees(): \Illuminate\Database\Eloquent\Collection
    {
        return EmployeeInformationModel::query()->select('id', 'user_id')->get();
    }
}
