<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project Board</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        @include('components.tasks_navbar')
        <div class="row">
            <div class="col-lg-6">
                <div class="myTasks mt-2" style="border: 1px solid;
                margin-top: 10px; max-height: 250px; overflow-y: auto; width: 80%; margin: 0 auto">
                    <div class="myTasks_box" style="margin: 5px">
                        <p class="h3 text-center">My tasks</p>
                        @foreach($my_tasks as $task)
                            <a href="{{route('tasks.ticket', $task->id)}}"
                               class="list-group-item list-group-item-action"
                               onmouseover="this.style.backgroundColor='#f8f9fa'"
                               onmouseout="this.style.backgroundColor=''">
                                <p style="border: 1px solid">{{$task->projectName->name}} | {{$task->title}} </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="projParticipants text-center" style="border: 1px solid;
                margin-top: 10px; max-height: 250px; overflow-y: auto; width: 80%; margin: 0 auto">
                    <p class="h3">Participants</p>
                    @foreach($projParticipants as $participant)
                        <a href="/profile/{{$participant->employee->user->id}}">{{$participant->employee->user->first_name}} {{$participant->employee->user->last_name}}</a>
                        <br>
                    @endforeach
                </div>
            </div>
        </div>
        <hr class="hr"/>
        <p class="h2">{{$project_name->name}} board |
            <a href="{{route('tasks.projects.all_tasks', ['project_id' => $project_name->id])}}">See all tasks</a>
            |
            <a href="{{route('tasks.projects.statistics', ['project_id' => $project_name->id])}}">See statistics</a>
            |
            @if(Auth::user()->id == $project_name->leader_employee_id || Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                <a href="/tasks/project_settings/{{$project_name->id}}">Settings</a>
                |
                <a href="/tasks/projects/{{$project_name->id}}/performance_report">Performance Report</a>
            @endif
        </p>
        <table class="table table-striped">
            <thead>
            <tr>
                @foreach($statuses as $status)
                    <th scope="col">
                        {{$status}}
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($tasks as $task)
                <tr>
                    @foreach($statuses as $key => $status)
                        @if($task->status_key == $key)
                            <td>
                                <a href="{{route('tasks.ticket', $task->id)}}" style="text-decoration: none">
                                    <div class="card bg-light" style="max-width: 300px">
                                        <div class="card-body text-center">
                                            {{$task->title}}
                                            <br>
                                            @if(isset($task->userTo->first_name))
                                                <small>{{$task->userTo->first_name}} {{$task->userTo->last_name}}</small>

                                            @else
                                                <small>No assignees</small>
                                            @endif
                                            <br>
                                            <small>Task priority: {{$task->priority}}</small>
                                            <br>
                                            {!! $task->label !!}
                                        </div>
                                    </div>
                                </a>
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
