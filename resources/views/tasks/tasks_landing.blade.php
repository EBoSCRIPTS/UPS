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
        <div class="container" style="width: 80%">
        <div class="myTasks" style="border: 1px solid; border-radius: 10px; margin-top: 10px">
            <div class="myTasks_box" style="margin: 5px">
                <p class="h3 text-center">My tasks</p>
                    @foreach($tasks as $task)
                            <a href="{{route('tasks.ticket', $task->id)}}" class="list-group-item list-group-item-action" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor=''">
                                <p style="border: 1px solid">{{$task->projectName->name}} | {{$task->title}} </p>
                            </a>
                    @endforeach
            </div>
        </div>

        <hr class="hr"/>
        <p class="h2 text-center">My projects</p>
        <div class="row">
            @foreach($myProjects as $project)
        <div class="col-lg-6">
            <div class="p-2">
                <a href="/tasks/projects/{{$project->project_id}}">
                <div class="card" style="height: 200px" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor=''">
                    <div class="card-body">
                        <p class="h1 text-center">{{$project->projectName->name}}</p>
                    </div>
                </div>
                </a>
            </div>
        </div>
        @endforeach
        </div>
        </div>

    </div>

</div>
</body>
