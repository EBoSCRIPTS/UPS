<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInformationModel;
use App\Models\Tasks\TasksParticipantsModel;
use App\Models\Tasks\TasksProjectModel;
use App\Models\Tasks\TasksStatusModel;
use App\Models\Tasks\TasksTaskCommentsModel;
use App\Models\Tasks\TasksTaskModel;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DepartmentsModel;

class TasksController extends Controller
{
    public function loadNewProjectsPage(): \Illuminate\View\View
    {
        $users = UserModel::query()->select('id', 'first_name', 'last_name')->get();
        $employees = EmployeeInformationModel::query()->select('id', 'user_id')->get();
        $dept = DepartmentsModel::query()->select('id', 'name')->get();

        return view('tasks.tasks_create_project', ['users' => $users, 'employees' => $employees, 'depts' => $dept]);
    }

    public function createNewProject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:100|unique:tasks_project,name',
            'department_id' => 'required|integer|exists:departaments,id',
            'project_manager_id' => 'sometimes|nullable|integer|exists:users,id',
        ]);

        $newProject = new TasksProjectModel([
            'name' => $request->input('project_name'),
            'department_id' => $request->input('department_id'),
            'leader_user_id' => $request->input('project_manager_id') ?? null,
        ]);

        $newProject->save();

        $statuses = [];
        for ($i = 1; $i <= $request->input('counter'); $i++) {
            $statuses[] = $request->input('project_status_field' . $i);
        }

        $projectStatusFields = new TasksStatusModel([
            'project_id' => $newProject->id,
            'statuses' => json_encode($statuses),
        ]);

        $projectStatusFields->save();

        if ($request->input('project_members') == null) return back('/tasks/')->with('success', 'Project created successfully');

        else {
            foreach ($request->input('project_members') as $pm) {
                $projectMember = new TasksParticipantsModel([
                    'project_id' => $newProject->id,
                    'employee_id' => $pm,
                ]);

                $projectMember->save();
            }
        }

        return back('/tasks/')->with('success', 'Project created successfully');
    }


    //this method loads in all projects in project_settings view (only called for higher roles)

    public function loadAvailableProjects(): \Illuminate\View\View
    {
        return view('tasks.tasks_projects_settings', ['projects' => TasksProjectModel::all()]);
    }


    public function projectsApi(): JsonResponse  //important to keep this, helps reduce amount of code needed for loading data for each page that requires if user is assigned information
    {
        $getEmployeeId = EmployeeInformationModel::query()->where('user_id', Auth::user()->id)->pluck('id')->first();
        $projects = TasksParticipantsModel::query()
            ->where('employee_id', $getEmployeeId)
            ->join('tasks_project', 'tasks_project.id', '=', 'tasks_participants.project_id')
            ->get();

        return response()->json($projects);
    }

    public function newTask(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'description' => 'required|string',
            'project' => 'required|integer|exists:tasks_project,id',
            'priority' => 'required|string|in:low,medium,high,critical',
            'task_points' => 'required|numeric|min:0|max:100',
            'task_label' => 'required|string|in:feature,bug,ticket'
        ]);

        $getTaskStatusesForProject = TasksStatusModel::query()->where('project_id', $request->input('project'))->select('id')->get()->toArray();

        if ($request->input('task_label') == 'feature') //store html values to reduce cluster in blade
        {
            $label = '<span class="badge bg-success">Feature</span>';
        } elseif ($request->input('task_label') == 'bug') {
            $label = '<span class="badge bg-warning">Bug</span>';
        } elseif ($request->input('task_label') == 'ticket') {
            $label = '<span class="badge bg-info">Ticket</span>';
        }

        $newTask = new TasksTaskModel([
            'title' => $request->input('task_name'),
            'description' => $request->input('description'),
            'project_id' => $request->input('project'),
            'made_by' => Auth::user()->id,
            'assigned_to' => substr($request->input('assign_to'), 0, 1) ?? null, //get the ID value from input field
            'status_id' => $getTaskStatusesForProject[0]['id'],
            'status_key' => 0,
            'priority' => $request->input('priority'),
            'label' => $label,
            'is_draft' => $request->input('is_draft') ?? 0,
            'task_points' => $request->input('task_points'),
        ]);

        $newTask->save();

        return redirect('/tasks/ticket/' . $newTask->id)->with('success', 'Task created successfully');
    }


    //loads in user projects
    public function loadMyTasks(Request $request): \Illuminate\View\View
    {
        $getEmployeeId = EmployeeInformationModel::query()->where('user_id', $request->user()->id)->pluck('id')->first();
        $myTasks = TasksTaskModel::query()->where('assigned_to', $request->user()->id)->get();
        $myProjects = TasksParticipantsModel::query()->where('employee_id', $getEmployeeId)->select('project_id')->get();

        return view('tasks.tasks_landing', ['tasks' => $myTasks, 'myProjects' => $myProjects]);
    }

    public function loadTicket(Request $request): \Illuminate\View\View
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

    public function updateTaskDescription(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_description' => 'required|string',
        ]);

        $ticket = TasksTaskModel::query()->where('id', $request->input('ticket_id'))->first();

        $ticket->update([
            'description' => $request->input('ticket_description'),
        ]);

        return back()->with('success', 'Ticket description updated successfully');
    }


    public function loadProjectTasks(Request $request): \Illuminate\View\View //used in project board page
    {
        $projectTasks = TasksTaskModel::query()
            ->where('project_id', $request->project_id)
            ->where('is_completed', 0)
            ->where('is_draft', 0)
            ->orderBy('priority', 'asc')
            ->get();

        $myTasks = TasksTaskModel::query()->where('assigned_to', $request->user()->id)->get();
        $projectStatus = TasksStatusModel::query()->where('project_id', $request->project_id)->select('statuses')->get()->toArray();
        $projectName = TasksProjectModel::query()->where('id', $request->project_id)->select('id', 'name', 'leader_user_id')->first();
        $projectStatus = json_decode($projectStatus['0']['statuses']);
        $projectParticipants = TasksParticipantsModel::query()->where('project_id', $request->project_id)->get();

        if ($projectTasks->toArray() != null) {
            $currentStatus = $projectTasks[0]['status_key'];
        } else {
            $currentStatus = null;
        }

        return view('tasks.tasks_project_board', ['tasks' => $projectTasks,
            'my_tasks' => $myTasks,
            'statuses' => $projectStatus,
            'project_name' => $projectName,
            'currentStatus' => $currentStatus,
            'projParticipants' => $projectParticipants]);
    }

    public function updateStatus(Request $request): RedirectResponse
    {
        $ticket = TasksTaskModel::query()->where('id', $request->ticket_id)->first();
        $ticketAllStatuses = TasksStatusModel::query()->where('project_id', $ticket['project_id'])->select('statuses')->get()->toArray();

        $stats = json_decode($ticketAllStatuses['0']['statuses']);

        $currentStatus = $ticket->status_key;

        if ($request->next && $currentStatus < sizeof($stats) - 1) { //check if we can hop forward
            $status = $currentStatus + 1;

            $ticket->update([
                'status_key' => $status
            ]);

            return redirect('/tasks/ticket/' . $request->ticket_id);
        }
        if ($request->back && $currentStatus > 0) { //check if we can hop backwards
            $status = $currentStatus - 1;

            $ticket->update([
                'status_key' => $status
            ]);

            return redirect('/tasks/ticket/' . $request->ticket_id);
        }

        return redirect('/tasks/ticket/' . $request->ticket_id);
    }

    public function loadCommentsForTicket($ticket_id): Collection
    {
        return TasksTaskCommentsModel::query()->where('task_id', $ticket_id)->get();
    }

    public function addComment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'task_id' => 'required|integer|exists:tasks_task,id',
            'comment' => 'required|string',
            'comment_author' => 'required|integer|exists:users,id',
        ]);

        $newComment = new TasksTaskCommentsModel([
            'task_id' => $request->input('task_id'),
            'comment_author_id' => $request->input('comment_author'),
            'comment_text' => $request->input('comment'),
        ]);

        $newComment->save();

        return redirect('/tasks/ticket/' . $request->input('task_id'));
    }

    public function updateAssignee(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'assignee_select' => 'required|integer|exists:users,id',
        ]);

        $ticket = TasksTaskModel::query()->where('id', $request->ticket_id)->first();
        $ticket->update([
            'assigned_to' => $request->input('assignee_select'),
        ]);

        return redirect('/tasks/ticket/' . $request->ticket_id);
    }

    public function deleteTicket(Request $request): RedirectResponse
    {
        $ticket = TasksTaskModel::query()->where('id', $request->ticket_id)->first();

        $getComments = TasksTaskCommentsModel::query()->where('task_id', $ticket->id)->get(); //delete all made comments under the task
        if ($getComments->count() > 0){
            foreach($getComments as $comment){
                $comment->delete();
            }
        }

        $ticket->delete();

        return redirect('/tasks');
    }

    public function completeTicket(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'sometimes|integer|exists:users,id',
            'ticket_id' => 'required|integer|exists:tasks_task,id',
        ]);

        $ticket = TasksTaskModel::query()->where('id', $request->ticket_id)->first();

        if ($ticket->is_completed == 1) {
            $ticket->update([
                'assigned_to' => $request->input('user_id'),
                'is_completed' => 0
            ]);
        } else {
            $ticket->update([
                'assigned_to' => null,
                'is_completed' => 1,
            ]);
        }

        return back()->with('success', 'Ticket completed!');
    }


    public function updateTaskDraftStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_id' => 'required|integer|exists:tasks_task,id',
        ]);

        $task = TasksTaskModel::query()->where('id', $request->input('ticket_id'))->first();
        if ($task->is_draft == 1) {
            $task->update([
                'is_draft' => 0
            ]);
            $task->save();
        } else {
            $task->update([
                'is_draft' => 1
            ]);
            $task->save();
        }

        return back()->with('success', 'Draft status updated!');
    }

    public function updatePriority(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_id' => 'required|integer|exists:tasks_task,id',
        ]);

        $task = TasksTaskModel::query()->where('id', $request->input('ticket_id'))->first();
        $priorities = ['low', 'medium', 'high', 'critical'];

        if ($request->input('back') && $task->priority != 'low') //check if we can hop backwards because all priorities are hardcoded
        {
            $curPriority = $task->priority;

            for ($i = 0; $i < sizeof($priorities); $i++) {
                if ($curPriority == $priorities[$i]) {
                    $curPriority = $priorities[$i - 1];
                    $task->update([
                        'priority' => $curPriority
                    ]);
                    break;
                }
            }
        }

        if ($request->input('next') && $task->priority != 'critical') //check if we can hop forward
        {
            $curPriority = $task->priority;

            for ($i = 0; $i < sizeof($priorities); $i++) {
                if ($curPriority == $priorities[$i]) {
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

    public function updateTitle(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_id' => 'sometimes|integer|exists:tasks_task,id',
            'title' => 'required|string|min:3|max:255',
        ]);

        $task = TasksTaskModel::query()->where('id', $request->ticket_id)->first();

        $task->update([
            'title' => $request->input('title')
        ]);

        return back()->with('success', 'Title updated!');
    }
}
