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
            <div class="card-body">
                <h3 class="card-title text-center">My tasks</h3>
                <ul class="list-group">
                    @foreach($tasks as $task)
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

        <hr class="hr"/>
        <p class="h2">My projects</p>
        @foreach($myProjects as $project)
            <ul class="list-group">
                <li class="list-group-item"><a href="/tasks/projects/{{$project->project_id}}">{{$project->projectName->name}}</li>
            </ul>
        @endforeach

    </div>

</div>
</body>
