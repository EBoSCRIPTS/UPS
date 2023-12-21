<?php

namespace App\Http\Controllers;

use App\ExcelExport\TaskExport;
use App\Models\EmployeeInformationModel;
use App\Models\Tasks\TasksParticipantsModel;
use App\Models\Tasks\TasksProjectModel;
use App\Models\Tasks\TasksStatusModel;
use App\Models\Tasks\TasksTaskModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as MaatwebsiteExcel;

class TasksBoardController extends Controller
{
    public function editProject(Request $request)
    {
        $project = TasksProjectModel::query()->where('id', $request->project_id)->first();
        $project->update([
            'name' => $request->input('name'),
        ]);

        $projectStatuses = TasksStatusModel::query()->where('project_id', $request->project_id)->first();

        $editStatus = $request->input('status');
        foreach($editStatus as $key => $value){
            if($value == null){
                unset($editStatus[$key]);
            }
        }

        $editStatus = json_encode($editStatus);

        $projectStatuses->update([
            'statuses' => $editStatus
        ]);

        $loadAllProjectTasks = TasksTaskModel::query()->where('project_id', $request->project_id)->get();
        foreach($loadAllProjectTasks as $loadAllProjectTask) {
            $loadAllProjectTask->update([
                'status_key' => 0
            ]);
        }

        return back()->with('success', 'Project updated!');
    }

    public function getProjectStatistics(Request $request)
    {
        $currentMonth = Carbon::now()->monthName;

        $tasksThisMonth = TasksTaskModel::query()
            ->where('project_id', $request->project_id)
            ->where('is_draft', 0)
            ->where('created_at', '<=', Carbon::now())
            ->where('created_at', '>=', Carbon::now()->startOfMonth())->get()->toArray();

        $createdTasksCount = sizeof($tasksThisMonth);
        $completedThisMonth = 0;

        $completedTaskPoints = 0;
        $allTasksPoints = 0;

        for($i = 0; $i < sizeof($tasksThisMonth); $i++){
            $allTasksPoints += $tasksThisMonth[$i]['task_points'];
            if($tasksThisMonth[$i]['is_completed'] == 1){
                $completedTaskPoints += $tasksThisMonth[$i]['task_points'];
                $completedThisMonth++;
            }
        }

        return view('tasks.tasks_project_statistics',
            ['createdTasksCount' => $createdTasksCount,
                'completedThisMonth' => $completedThisMonth,
                'month' => $currentMonth,
                'completedTaskPoints' => $completedTaskPoints,
                'allTasksPoints' => $allTasksPoints,
                'project_id' => $request->project_id]);
    }

    public function addUserToProject(Request $request)
    {
        for ($i = 0; $i < sizeof($request->input('participants')); $i++) {
            $newUser = new TasksParticipantsModel([
                'project_id' => $request->input('project_id'),
                'employee_id' => $request->input('participants')[$i],
            ]);
            $newUser->save();
        }
        return redirect('/tasks/project_settings/' . $request->input('project_id'));
    }

    public function removeUserFromProject(Request $request)
    {
        TasksParticipantsModel::query()->where('project_id', $request->project_id)->where('employee_id', $request->input('user_id'))->delete();

        return redirect('/tasks/project_settings/' . $request->input('project_id'));
    }

    public function getStatisticsForPeriod(Request $request)
    {
        $currentMonth = Carbon::now()->monthName;

        $tasksThisMonth = TasksTaskModel::query()
            ->where('project_id', $request->project_id)
            ->where('is_draft', 0)
            ->where('created_at', '>=', $request->input('startDate'))
            ->where('created_at', '<=', $request->input('endDate'))->get()->toArray();

        $createdTasksCount = sizeof($tasksThisMonth);
        $completedThisMonth = 0;

        $completedTaskPoints = 0;
        $allTasksPoints = 0;

        for ($i = 0; $i < sizeof($tasksThisMonth); $i++) {
            $allTasksPoints += $tasksThisMonth[$i]['task_points'];
            if ($tasksThisMonth[$i]['is_completed'] == 1) {
                $completedTaskPoints += $tasksThisMonth[$i]['task_points'];
                $completedThisMonth++;
            }
        }

        return view('tasks.tasks_project_statistics',
            ['createdTasksCount' => $createdTasksCount,
                'completedThisMonth' => $completedThisMonth,
                'month' => $currentMonth,
                'completedTaskPoints' => $completedTaskPoints,
                'allTasksPoints' => $allTasksPoints,
                'project_id' => $request->project_id]);
    }

    public function getProjectSettings(Request $request)
    {
        $project = TasksProjectModel::query()->where('id', $request->project_id)->first();
        $projectStatuses = TasksStatusModel::query()->where('project_id', $request->project_id)->select('statuses')->get()->toArray();
        $projectUsers = TasksParticipantsModel::query()->where('project_id', $request->project_id)->select('employee_id')->get();
        $allUsers = EmployeeInformationModel::query()->select('id', 'user_id')->get();

        $projectStatuses = json_decode($projectStatuses[0]['statuses']);

        return view('tasks.tasks_projects_settings_project',
            [   'project' => $project,
                'statuses' => $projectStatuses,
                'projectUsers' => $projectUsers,
                'employees' => $allUsers]);
    }

    public function loadAllProjectTasks(Request $request)
    {
        $allTasks = TasksTaskModel::query()
            ->join('tasks_status', 'tasks_task.project_id', '=', 'tasks_status.project_id')
            ->where('tasks_task.project_id', $request->project_id)->where('is_completed', 0)
            ->where('tasks_task.is_draft', 0)
            ->select('tasks_task.*', 'tasks_status.statuses')
            ->get();

        $tasksCompleted = TasksTaskModel::query()
            ->join('tasks_status', 'tasks_task.project_id', '=', 'tasks_status.project_id')
            ->where('tasks_task.project_id', $request->project_id)->where('is_completed', 1)
            ->select('tasks_task.*', 'tasks_status.statuses')
            ->get();

        $tasksDrafted = TasksTaskModel::query()
            ->join('tasks_status', 'tasks_task.project_id', '=', 'tasks_status.project_id')
            ->where('tasks_task.project_id', $request->project_id)->where('is_draft', 1)
            ->select('tasks_task.*', 'tasks_status.statuses')
            ->get();

        $statuses = TasksStatusModel::query()
            ->where('project_id', $request->project_id)
            ->select('statuses')
            ->get()
            ->toArray();

        $statuses = json_decode($statuses['0']['statuses']);

        return view('tasks.tasks_project_all_tasks',
            ['tasks' => $allTasks,
                'statuses' => $statuses,
                'tasksCompleted' => $tasksCompleted,
                'tasksDrafted' => $tasksDrafted
            ]);
    }

    public function generateExcelForProjectStatistics(Request $request)
    {
        return MaatwebsiteExcel::download(new TaskExport($request->input('startDate'), $request->input('endDate'), $request->input('project_id')), 'project_statistics.xlsx',\Maatwebsite\Excel\Excel::XLSX);
    }
}
