<?php

namespace App\Http\Controllers;

use App\Models\TasksParticipantsModel;
use App\Models\TasksTaskCommentsModel;
use Illuminate\Http\Request;
use App\Models\TasksProjectModel;
use App\Models\TasksStatusModel;
use App\Models\TasksTaskModel;
use App\Models\UserModel;

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
        $projectUsers = TasksParticipantsModel::query()->where('project_id', $request->project_id)->select('employee_id')->get();
        $allUsers = UserModel::query()->select('id', 'first_name', 'last_name')->get();

        return view('tasks_projects_settings_project',
            [   'project' => $project,
                'statuses' => $projectStatuses,
                'projectUsers' => $projectUsers,
                'users' => $allUsers]);
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

    public function loadMyTasks(Request $request)
    {
        $myTasks = TasksTaskModel::query()->where('assigned_to', $request->user()->id)->get();
        $myProjects = TasksParticipantsModel::query()->where('employee_id', $request->user()->id)->select('project_id')->get();

        return view(' tasks_landing', ['tasks' => $myTasks, 'myProjects' => $myProjects]);
    }

    public function loadTicket(Request $request)
    {
        $ticket = TasksTaskModel::query()->where('id', $request->ticket_id)->first();
        $statuses = TasksStatusModel::query()->where('project_id', $ticket->project_id)->get();
        $getComments = $this->loadCommentsForTicket($request->ticket_id);

        return view('tasks_ticket', ['ticket' => $ticket, 'statuses' => $statuses, 'comments' => $getComments]);
    }

    public function loadProjectTasks(Request $request)
    {
        $projectTasks = TasksTaskModel::query()->where('project_id', $request->project_id)->get();
        $myTasks = TasksTaskModel::query()->where('assigned_to', $request->user()->id)->get();
        $projectStatus = TasksStatusModel::query()->where('project_id', $request->project_id)->get();
        $projectName = TasksProjectModel::query()->where('id', $request->project_id)->select('name')->first();

        return view('tasks_project_board', ['tasks' => $projectTasks, 'my_tasks' => $myTasks, 'statuses' => $projectStatus, 'project_name' => $projectName]);
    }

    public function updateStatus(Request $request)
    {
        $ticket = TasksTaskModel::query()->where('id', $request->ticket_id)->first();
        $currentStatus = $ticket->status_id;
        if ($request->next == 'next') {
            $status = $currentStatus + 1;
        } else {
            $status = $currentStatus - 1;
        }

        $ticket->update([
            'status_id' => $status
        ]);

        return redirect('/tasks/ticket/' . $request->ticket_id);
    }

    public function loadCommentsForTicket($ticket_id)
    {
        return TasksTaskCommentsModel::query()->where('task_id', $ticket_id)->get();
    }

    public function addComment(Request $request){
        $newComment = new TasksTaskCommentsModel([
            'task_id' => $request->input('task_id'),
            'comment_author_id' => $request->input('comment_author'),
            'comment_text' => $request->input('comment'),
        ]);

        $newComment->save();

        return redirect('/tasks/ticket/' . $request->input('task_id'));
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
}
