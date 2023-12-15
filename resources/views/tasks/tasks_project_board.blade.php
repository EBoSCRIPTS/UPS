<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        @include('components.tasks_navbar')
        <div class="card">
            <div class="card-title">
                <p class="h3">My Tasks</p>
            </div>
            <div class="card-box">
                <ul class="list-group">
                @foreach($my_tasks as $task)
                    <li class="list-group-item">
                    <a href="{{route('tasks.ticket', $task->id)}}" class="list-group-item list-group-item-action">
                        {{$task->title}}
                        <br>
                        {{$task->projectName->name}}
                    </a>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
        <hr class="hr" />
        <p class="h2">{{$project_name->name}} board |
            <a href="{{route('tasks.projects.all_tasks', ['project_id' => $project_name->id])}}">See all tasks</a>
            |
            <a href="{{route('tasks.projects.statistics', ['project_id' => $project_name->id])}}">See statistics</a>
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
                                <div class="card bg-light">
                                    <div class="card-body">
                                        {{$task->title}}
                                    <br>
                                    <small>{{$task->userTo->first_name}} {{$task->userTo->last_name}}</small>
                                        <br>
                                        <small>Task priority: {{$task->priority}}</small>
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
