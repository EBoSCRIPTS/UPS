<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TasksProjectModel;
use App\Models\TasksStatusModel;
use App\Models\TasksTaskModel;

class TasksController extends Controller
{
    public function createNewProject(Request $request)
    {
        $newProject = new TasksProjectModel([
            'name' => $request->input('project_name'),
            'department_id' => $request->input('department_id'),
        ]);
        $newProject->save();

        for ($i = 1; $i <= $request->input('counter'); $i++) {
            $projectStatusFields = new TasksStatusModel([
                'project_id' => $newProject->id,
                'status_name' => $request->input('project_status_field' . $i),
            ]);
            $projectStatusFields->save();
        }


        return redirect('/tasks/create_new_project');
    }

    public function loadAvailableProjects()
    {
        $projects = TasksProjectModel::all();
        return view('tasks_projects_settings', ['projects' => $projects]);
    }

    public function getProjectSettings(Request $request)
    {
        $project = TasksProjectModel::query()->where('id', $request->project_id)->first();

        $projectStatuses = TasksStatusModel::query()->where('project_id', $request->project_id)->get();

        return view('tasks_projects_settings_project', ['project' => $project, 'statuses' => $projectStatuses]);
    }

    public function projectsApi()
    {
        return TasksProjectModel::query()->select('id', 'name')->get();
    }

    public function newTask(Request $request)
    {
        $newTask = new TasksTaskModel([
            'title' => $request->input('task_name'),
            'description' => $request->input('description'),
            'project_id' => $request->input('project'),
            'made_by' => $request->input('made_by'),
            'assigned_to' => $request->input('assign_to'),
            'status_id' => 4,
            'priority' => $request->input('priority'),
        ]);

        $newTask->save();

        return redirect('/');
    }
}
