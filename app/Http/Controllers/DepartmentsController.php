<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartamentsModel;

class DepartmentsController extends Controller
{
    public function addDepartment(Request $request)
    {
        $departament = new DepartamentsModel([
            'name' => $request->input('departament'),
            'description' => $request->input('description'),
        ]);

        $departament->save();

        return view('departaments');
    }

    public function showDepartments(Request $request)
    {
        return DepartamentsModel::all();
    }

    public function showAllDepartments()
    {
        $departments = DepartamentsModel::all();
        return view('departaments', ['departments' => $departments]);
    }

    public function deleteDepartament(Request $request)
    {
        $departament = DepartamentsModel::query()->find($request->input('departament_id'));
        $departament->delete();

        return redirect('/departaments');
    }

    public function departmentsApi()
    {
        $departments = DepartamentsModel::query()->select('id', 'name')->get();
        return response()->json($departments);
    }
}
