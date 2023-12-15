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
        <p class="h3">Backlog</p>
        @foreach($tasks as $task)
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="{{route('tasks.ticket', $task->id)}}" class="list-group-item list-group-item-action">
                        {{$task->title}}
                        <br>
                        Priority: {{$task->priority}}
                        <br>
                        Status: {{$statuses[$task->status_key]}}
                        <br>
                        Made by: {{$task->userTo->first_name}} {{$task->userTo->last_name}}
                        | Project: {{$task->projectName->name}}
                    </a>
                </li>
            </ul>
        @endforeach
        <hr class="hr"/>
        <p class="h3">Completed tasks</p>
         @foreach($tasksCompleted as $taskComplete)
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="{{route('tasks.ticket', $taskComplete->id)}}" class="list-group-item list-group-item-action">
                        {{$taskComplete->title}}
                        <br>
                        Priority: {{$taskComplete->priority}}
                        <br>
                        Status: {{$statuses[$taskComplete->status_key]}}
                        <br>
                        {{$taskComplete->projectName->name}}
                    </a>
                </li>
            </ul>
        @endforeach
    </div>

</div>
</body>
