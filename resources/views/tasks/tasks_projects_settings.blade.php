<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Projects</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
    <div class="row">
        @include('components.sidebar')
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
            @include('components.tasks_navbar')
                <div class="container" style="width: 80%">
                    <p class="h2 text-center">Pick a project</p>
                    <div class="row">
                        @foreach($projects as $project)
                            <div class="col-lg-6">
                                <div class="p-2">
                                    <a href="{{route('project_settings', ['project_id' => $project->id])}}">
                                        <div class="card" style="height: 150px" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor=''">
                                            <div class="card-body">
                                                <h3 class="text-center">{{$project->name}}</h3>
                                                <br>
                                                <a href="/tasks/projects/{{$project->id}}/performance_report" class="btn btn-primary btn-sm d-flex justify-content-center">Go to performance reports</a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
        </div>
    </div>
</body>
