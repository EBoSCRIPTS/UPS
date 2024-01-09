<?php

namespace App\Http\Controllers;

use App\ExcelExport\TaskExport;
use App\Models\EmployeeInformationModel;
use App\Models\PerformanceReportsModel;
use App\Models\Tasks\TasksParticipantsModel;
use App\Models\Tasks\TasksProjectModel;
use App\Models\Tasks\TasksStatusModel;
use App\Models\Tasks\TasksTaskModel;
use App\Models\UserModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as MaatwebsiteExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TasksBoardController extends Controller
{
    public function editProject(Request $request): RedirectResponse
    {
        if ($this->checkIfProjectSettingsAccess($request->project_id) == false) { //check if user is either manager/admin project leader
            return redirect('/');
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'status' => 'sometimes|array',
        ]);

        $project = TasksProjectModel::query()->where('id', $request->project_id)->first();
        $project->update([
            'name' => $request->input('name'),
        ]);

        $projectStatuses = TasksStatusModel::query()->where('project_id', $request->project_id)->first();

        $editStatus = $request->input('status');
        foreach ($editStatus as $key => $value) {
            if ($value == null) {
                unset($editStatus[$key]);
            }
        }

        $editStatus = json_encode($editStatus); //store it in json

        $projectStatuses->update([
            'statuses' => $editStatus
        ]);

        $loadAllProjectTasks = TasksTaskModel::query()->where('project_id', $request->project_id)->get();
        foreach ($loadAllProjectTasks as $loadAllProjectTask) { //update all status keys to 0 to avoid any conflicts when editing
            $loadAllProjectTask->update([
                'status_key' => 0
            ]);
        }

        return back()->with('success', 'Project updated!');
    }

    public function getProjectStatistics(Request $request): \Illuminate\View\View
    {
        $currentMonth = Carbon::now()->monthName;

        $tasksThisMonth = TasksTaskModel::query()
            ->where('project_id', $request->project_id)
            ->where('is_draft', 0)
            ->where('created_at', '<=', Carbon::now())
            ->where('created_at', '>=', Carbon::now()->startOfMonth())->get()->toArray();

        $avgPerformanceScore = $this->checkAverageRating($request->project_id);

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
            ['createdTasksCount' => sizeof($tasksThisMonth),
                'completedThisMonth' => $completedThisMonth,
                'month' => $currentMonth,
                'completedTaskPoints' => $completedTaskPoints,
                'allTasksPoints' => $allTasksPoints,
                'project_id' => $request->project_id,
                'avgPerformanceScore' => $avgPerformanceScore]);
    }

    public function addUserToProject(Request $request): RedirectResponse
    {
        if ($this->checkIfProjectSettingsAccess($request->project_id) == false) { //check if user is either manager/admin project leader
            return redirect('/');
        }

        $validated = $request->validate([
            'project_id' => 'required|integer|exists:tasks_project,id',
            'participants' => 'required|array',
            'participants.*' => 'required|integer|exists:employee_information,id',
        ]);

        for ($i = 0; $i < sizeof($request->input('participants')); $i++) {
            $newUser = new TasksParticipantsModel([
                'project_id' => $request->input('project_id'),
                'employee_id' => $request->input('participants')[$i],
            ]);
            $newUser->save();
        }

        return redirect('/tasks/project_settings/' . $request->input('project_id'));
    }

    public function removeUserFromProject(Request $request): RedirectResponse
    {
        if ($this->checkIfProjectSettingsAccess($request->project_id) == false) { //check if user is either manager/admin project leader
            return redirect('/');
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:tasks_participants,employee_id',
            'project_id' => 'required|integer|exists:tasks_project,id',
        ]);

        TasksParticipantsModel::query()->where('project_id', $request->project_id)->where('employee_id', $request->input('user_id'))->delete();

        return redirect('/tasks/project_settings/' . $request->input('project_id'));
    }

    public function getStatisticsForPeriod(Request $request): \Illuminate\View\View//if we want to load in a specific period
    {
        $currentMonth = Carbon::now()->monthName;

        $tasksThisMonth = TasksTaskModel::query()
            ->where('project_id', $request->project_id)
            ->where('is_draft', 0)
            ->where('created_at', '>=', $request->input('startDate'))
            ->where('created_at', '<=', $request->input('endDate'))->get()->toArray();

        $completedThisMonth = 0;
        $completedTaskPoints = 0;
        $allTasksPoints = 0;

        $avgPerformanceScore = $this->checkAverageRating($request->project_id);

        for ($i = 0; $i < sizeof($tasksThisMonth); $i++) {
            $allTasksPoints += $tasksThisMonth[$i]['task_points'];
            if ($tasksThisMonth[$i]['is_completed'] == 1) {
                $completedTaskPoints += $tasksThisMonth[$i]['task_points'];
                $completedThisMonth++;
            }
        }

        return view('tasks.tasks_project_statistics',
            ['createdTasksCount' => sizeof($tasksThisMonth),
                'completedThisMonth' => $completedThisMonth,
                'month' => $currentMonth,
                'completedTaskPoints' => $completedTaskPoints,
                'allTasksPoints' => $allTasksPoints,
                'avgPerformanceScore' => $avgPerformanceScore,
                'project_id' => $request->project_id]);
    }

    public function getProjectSettings(Request $request): \Illuminate\View\View|RedirectResponse
    {
        if ($this->checkIfProjectSettingsAccess($request->project_id) == false) { //check if user is either manager/admin project leader
            return redirect('/');
        }

        $project = TasksProjectModel::query()->where('id', $request->project_id)->first();
        $projectStatuses = TasksStatusModel::query()->where('project_id', $request->project_id)->select('statuses')->get()->toArray();
        $projectUsers = TasksParticipantsModel::query()->where('project_id', $request->project_id)->select('employee_id')->get();
        $allUsers = EmployeeInformationModel::query()->select('id', 'user_id')->get();

        $projectStatuses = json_decode($projectStatuses[0]['statuses']);

        return view('tasks.tasks_projects_settings_project',
            ['project' => $project,
                'statuses' => $projectStatuses,
                'projectUsers' => $projectUsers,
                'employees' => $allUsers]);
    }

    public function loadAllProjectTasks(Request $request): \Illuminate\View\View
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

    public function updateProjectLeader(Request $request): RedirectResponse
    {
        if ($this->checkIfProjectSettingsAccess($request->project_id) == false) { //check if user is either manager/admin project leader
            return redirect('/');
        }

        $validated = $request->validate([
            'project_leader' => 'required|integer|exists:employee_information,user_id',
        ]);

        $project = TasksProjectModel::query()->where('id', $request->project_id)->first();
        $project->update([
            'leader_user_id' => $request->input('project_leader')
        ]);

        return back()->with('success', 'Project leader updated');
    }

    public function deleteProject(Request $request, $id): RedirectResponse
    {
        if ($this->checkIfProjectSettingsAccess($request->project_id) == false) {
            return back()->withInput()->withErrors([
                'project' => 'Project not found or you don\'t have permissions to delete it'
            ]);
        }

        $project = TasksProjectModel::query()->where('id', $request->project_id)->first();

        if (TasksParticipantsModel::query()->where('project_id', $request->project_id)->first() != null) {
            return back()->withInput()->withErrors([
                'project' => 'Project has participants. Please remove them before deleting'
            ]);
        }

        //if project gets deleted, remove task assignees
        $getAllTasks = TasksTaskModel::query()->where('project_id', $request->project_id)->get();
        foreach ($getAllTasks as $task) {
            $task->update([
                'assigned_to' => null
            ]);
        }

        $project->delete();

        return redirect('/tasks/projects/');
    }

    public function generateExcelForProjectStatistics(Request $request): BinaryFileResponse|RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|integer|exists:tasks_project,id',
            'startDate' => 'required|date|before:endDate',
            'endDate' => 'required|date|after:startDate',
        ]);

        if (!$this->checkIfProjectSettingsAccess($request->input('project_id'))) {
            return back()->withInput()->withErrors([
                'project' => 'Project not found or you don\'t have permissions to export performance reports!'
            ]);
        }

        return MaatwebsiteExcel::download(new TaskExport($request->input('startDate'), $request->input('endDate'), $request->input('project_id')), 'project_statistics.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    //use this to check if user is either manager/admin project leader
    public function checkIfProjectSettingsAccess($project_id): bool
    {
        if (request()->user()->role_id == 1 || request()->user()->role_id == 3
            || TasksProjectModel::query()
                ->where('id', $project_id)
                ->pluck('leader_user_id')
                ->first() == request()->user()->id) { //admin, manager or proj leader
            return true;
        } else {
            return false;
        }
    }

    private function checkAverageRating($project_id): int|null
    {
        return PerformanceReportsModel::query()->where('project_id', $project_id)
            ->where('soft_deleted', 0)
            ->where('month', Carbon::now()->monthName)
            ->orWhere('month', Carbon::now()->subMonth()->monthName)
            ->where('year', Carbon::now()->year)
            ->orWhere('year', Carbon::now()->subYear()->year)
            ->avg('rating');
    }
}
