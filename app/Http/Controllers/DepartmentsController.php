<?php

namespace App\Http\Controllers;

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
}
