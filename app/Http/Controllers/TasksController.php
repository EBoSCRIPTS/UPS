<?php

namespace App\Http\Controllers;

use App\Models\Tasks\TasksParticipantsModel;
use App\Models\Tasks\TasksProjectModel;
use App\Models\Tasks\TasksStatusModel;
use App\Models\Tasks\TasksTaskCommentsModel;
use App\Models\Tasks\TasksTaskModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TasksController extends Controller
{
    public function createNewProject(Request $request)
    {
        $newProject = new TasksProjectModel([
            'name' => $request->input('project_name'),
            'department_id' => $request->input('department_id'),
        ]);
        $newProject->save();

        $statuses= [];
        for($i = 1; $i <= $request->input('counter'); $i++){
            $statuses[] = $request->input('project_status_field' . $i);
        }

        $projectStatusFields = new TasksStatusModel([
            'project_id' => $newProject->id,
            'statuses' => json_encode($statuses),
        ]);
        $projectStatusFields->save();

        return redirect('/tasks/create_new_project');
    }

    public function loadAvailableProjects()
    {
        $projects = TasksProjectModel::all();
        return view('tasks.tasks_projects_settings', ['projects' => $projects]);
    }


    public function projectsApi()
    {
        $projects = TasksParticipantsModel::where('employee_id', 7)
            ->join('tasks_project', 'tasks_project.id', '=', 'tasks_participants.project_id')
            ->get();
        return response()->json($projects);
    }

    public function newTask(Request $request)
    {
        $getTaskStatusesForProject = TasksStatusModel::query()->where('project_id', $request->input('project'))->select('id')->get()->toArray();

        if ($request->input('task_label') == 'feature')
        {
            $label = '<span class="badge bg-success">Feature</span>';
        }
        elseif ($request->input('task_label') == 'bug')
        {
            $label = '<span class="badge bg-warning">Bug</span>';
        }
        elseif ($request->input('task_label') == 'ticket')
        {
            $label = '<span class="badge bg-info">Ticket</span>';
        }

        $newTask = new TasksTaskModel([
            'title' => $request->input('task_name'),
            'description' => $request->input('description'),
            'project_id' => $request->input('project'),
            'made_by' => Auth::user()->id,
            'assigned_to' => substr($request->input('assign_to'), 0,1),
            'status_id' => $getTaskStatusesForProject[0]['id'],
            'status_key' => 0,
            'priority' => $request->input('priority'),
            'label' => $label,
            'is_draft' => $request->input('is_draft') ?? 0,
            'task_points' => $request->input('task_points'),
        ]);

        $newTask->save();

        return redirect('/tasks/ticket/' . $newTask->id);
    }

    public function loadMyTasks(Request $request)
    {
        $myTasks = TasksTaskModel::query()->where('assigned_to', $request->user()->id)->get();
        $myProjects = TasksParticipantsModel::query()->where('employee_id', $request->user()->id)->select('project_id')->get();

        return view('tasks.tasks_landing', ['tasks' => $myTasks, 'myProjects' => $myProjects]);
    }

    public function loadTicket(Request $request)
    {
        $ticket = TasksTaskModel::query()->where('id', $request->ticket_id)->first();
        $statuses = TasksStatusModel::query()->where('project_id', $ticket->project_id)->select('statuses')->get()->toArray();
        $users = UserModel::query()->select('id', 'first_name', 'last_name')->get();

        $statusKey = $ticket['status_key'];
        $decoded = json_decode($statuses['0']['statuses']);
        $statusKeyDecoded = $decoded[$statusKey];


        $getComments = $this->loadCommentsForTicket($request->ticket_id);

        return view('tasks.tasks_ticket',
            ['ticket' => $ticket,
                'statuses' => $statuses,
                'comments' => $getComments,
                'currentStatus' => $statusKeyDecoded,
                'users' => $users]);
    }

    public function updateTaskDescription (Request $request)
    {
        $ticket = TasksTaskModel::query()->where('id', $request->input('ticket_id'))->first();

        $ticket->update([
            'description' => $request->input('ticket_description'),
        ]);

        return redirect('/tasks/ticket/' . $request->ticket_id);
    }


    public function loadProjectTasks(Request $request)
    {
        $projectTasks = TasksTaskModel::query()->where('project_id', $request->project_id)->where('is_completed', 0)->where('is_draft', 0)->get();
        $myTasks = TasksTaskModel::query()->where('assigned_to', $request->user()->id)->get();
        $projectStatus = TasksStatusModel::query()->where('project_id', $request->project_id)->select('statuses')->get()->toArray();
        $projectName = TasksProjectModel::query()->where('id', $request->project_id)->select('id','name')->first();

        $projectStatus = json_decode($projectStatus['0']['statuses']);

        if($projectTasks->toArray() != null) {
            $currentStatus = $projectTasks[0]['status_key'];
        }
        else{
            $currentStatus = null;
        }

        return view('tasks.tasks_project_board', ['tasks' => $projectTasks, 'my_tasks' => $myTasks, 'statuses' => $projectStatus, 'project_name' => $projectName, 'currentStatus' => $currentStatus]);
    }

    public function updateStatus(Request $request)
    {
        $ticket = TasksTaskModel::query()->where('id', $request->ticket_id)->first();
        $ticketAllStatuses = TasksStatusModel::query()->where('project_id', $ticket['project_id'])->select('statuses')->get()->toArray();

        $stats = json_decode($ticketAllStatuses['0']['statuses']);

        $currentStatus = $ticket->status_key;

        if ($request->next && $currentStatus < sizeof($stats) - 1) {
            $status = $currentStatus + 1;

            $ticket->update([
                'status_key' => $status
            ]);

            return redirect('/tasks/ticket/' . $request->ticket_id);
        }
        if ($request->back && $currentStatus > 0){
            $status = $currentStatus - 1;

            $ticket->update([
                'status_key' => $status
            ]);

            return redirect('/tasks/ticket/' . $request->ticket_id);
        }

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

    public function updateAssignee(Request $request)
    {
        $ticket = TasksTaskModel::query()->where('id', $request->ticket_id)->first();
        $ticket->update([
            'assigned_to' => $request->input('assignee_select'),
        ]);

        return redirect('/tasks/ticket/' . $request->ticket_id);
    }

    public function deleteTicket(Request $request)
    {
        $ticket = TasksTaskModel::query()->where('id', $request->ticket_id)->first();
        $ticket->delete();

        return redirect('/tasks');
    }

    public function completeTicket(Request $request)
    {
        $ticket = TasksTaskModel::query()->where('id', $request->ticket_id)->first();

        if ($ticket->is_completed == 1) {
            $ticket->update([
                'assigned_to' => $request->input('user_id'),
                'is_completed' => 0
            ]);
        }

        else {
            $ticket->update([
                'assigned_to' => null,
                'is_completed' => 1,
            ]);
        }

        return back()->with('success', 'Ticket completed!');
    }


    public function updateTaskDraftStatus(Request $request)
    {
        $task = TasksTaskModel::query()->where('id', $request->input('ticket_id'))->first();
        if($task->is_draft == 1){
            $task->update([
                'is_draft' => 0
            ]);
            $task->save();
        }
        else{
            $task->update([
                'is_draft' => 1
            ]);
            $task->save();
        }

        return back()->with('success', 'Draft status updated!');
    }

    public function updatePriority(Request $request)
    {
        $task = TasksTaskModel::query()->where('id', $request->input('ticket_id'))->first();
        $priorities = ['low', 'medium', 'high', 'critical'];

        if ($request->input('back') && $task->priority != 'low')
        {
            $curPriority = $task->priority;

            for ($i = 0; $i < sizeof($priorities); $i++)
            {
                if ($curPriority == $priorities[$i])
                {
                    $curPriority = $priorities[$i - 1];
                    $task->update([
                        'priority' => $curPriority
                    ]);
                    break;
                }
            }
        }

        if ($request->input('next') && $task->priority != 'critical')
        {
            $curPriority = $task->priority;

            for ($i = 0; $i < sizeof($priorities); $i++)
            {
                if ($curPriority == $priorities[$i])
                {
                    $curPriority = $priorities[$i + 1];
                    $task->update([
                        'priority' => $curPriority
                    ]);
                    break;
                }
            }
        }

        return back()->with('success', 'Priority updated!');
    }

    public function updateTitle(Request $request)
    {
        $task = TasksTaskModel::query()->where('id', $request->ticket_id)->first();

        $task->update([
            'title' => $request->input('title')
        ]);

        return back()->with('success', 'Title updated!');
    }
}
