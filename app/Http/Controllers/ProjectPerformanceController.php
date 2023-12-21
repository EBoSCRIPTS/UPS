<?php

namespace App\Http\Controllers;

use App\Models\Tasks\TasksParticipantsModel;
use Illuminate\Http\Request;

class ProjectPerformanceController extends Controller
{
    public function loadProjectPerformance(Request $request)
    {
        $projectMembers = TasksParticipantsModel::query()->where('project_id', $request->project_id)->get();

        return view('tasks.tasks_employee_performance', ['projectMembers' => $projectMembers]);
    }
}
