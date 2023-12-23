<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInformationModel;
use App\Models\SubmittedTicketsModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\DepartamentsModel;
use Illuminate\Support\Facades\View;

class DepartmentsController extends Controller
{
    public function addDepartment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'required|string',
        ]);

        $departament = new DepartamentsModel([
            'name' => $request->input('departament'),
            'description' => $request->input('description'),
        ]);

        $departament->save();

        return back();
    }

    public function showAllDepartments(): \Illuminate\View\View
    {
        $departments = DepartamentsModel::all();

        return view('departaments', ['departments' => DepartamentsModel::all()]);
    }

    public function deleteDepartment(Request $request): RedirectResponse
    {
        $department = DepartamentsModel::query()->find($request->input('id'));

        if (EmployeeInformationModel::query()->where('department_id', $request->input('id'))->get()->count() > 0) { //check if any employees are assigned to the department
            return back()->withInput()->withErrors([
                'error' => 'There are employees in this department. You can not delete it!'
            ]);
        }

        $department->delete();

        return back();
    }

    public function loadUserDepartment(Request $request): \Illuminate\View\View
    {
        $employeeDept = EmployeeInformationModel::query()->where('user_id', $request->user()->id)->pluck('department_id')->first();
        $department = DepartamentsModel::query()->find($employeeDept)->select('id', 'name')->first();

        $getAllDeptEmployees = EmployeeInformationModel::query()
            ->where('department_id', $department->id)
            ->select('id', 'user_id', 'position')
            ->orderBy('position', 'desc')
            ->get();

        $getTickets = SubmittedTicketsModel::query()->where('department_id', $department->id)
            ->where('is_registered', 0)
            ->get();

        return view('my_department', ['department' => $department,
            'employees' => $getAllDeptEmployees,
            'tickets' => $getTickets]);
    }
}
