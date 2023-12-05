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
}
