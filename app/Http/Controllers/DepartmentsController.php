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
    public function addDepartment(Request $request): \Illuminate\View\View
    {
        $departament = new DepartamentsModel([
            'name' => $request->input('departament'),
            'description' => $request->input('description'),
        ]);

        $departament->save();

        return view('departaments');
    }

    public function showAllDepartments(): \Illuminate\View\View
    {
        $departments = DepartamentsModel::all();
        return view('departaments', ['departments' => DepartamentsModel::all()]);
    }

    public function deleteDepartament(Request $request): RedirectResponse
    {
        $departament = DepartamentsModel::query()->find($request->input('id'));
        $departament->delete();

        return redirect('/departaments');
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
