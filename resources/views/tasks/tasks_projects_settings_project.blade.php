<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project Settings</title>

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
        <div class="container" style="width: 80%">
            @include('components.errors')
            <div class="row">
                <div class="col-md-6">
                    <p class="h2">Projects settings</p>
                </div>
                <div class="col-md-6">
                    <form action="{{route('tasks.project_delete', $project->id)}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm mt-2 float-end">Delete project</button>
                    </form>
                </div>
                <hr class="hr"/>
                <form action="{{route('tasks.project_edit', $project->id)}}" method="POST">
                    @csrf
                    <div class="editButtons">
                        <button id="editButton" type="button" class="btn btn-primary btn-sm float-end"
                                onclick="enableDisabledInputs()">Edit
                        </button>
                    </div>
                    <label class="h4" for="name">Project name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="{{$project->name}}"
                           disabled/>

                    <div class="status-fields">
                        @foreach($statuses as $status)
                            <label for="status">Status</label>
                            <input type="text" class="form-control" id="status" name="status[]"
                                   placeholder="{{$status}}" disabled/>
                        @endforeach
                    </div>
                </form>
                <hr class="hr"/>
                <p class="h4">Project participants</p>
                <ul class="list-group">
                    @foreach($projectUsers as $projectUser)
                        <form action="{{route('tasks.project_remove_user')}}" method="POST">
                            @csrf
                            <li class="list-group-item">{{$projectUser->employee->user->first_name}} {{$projectUser->employee->user->last_name}}
                                <button type="submit" class="btn btn-danger btn-sm float-end">Remove</button>
                                <input type="hidden" name="project_id" value="{{$project->id}}">
                                <input type="hidden" name="user_id" value="{{$projectUser->employee_id}}">
                            </li>
                        </form>
                    @endforeach
                </ul>
                <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal"
                        data-bs-target="#addParticipants">
                    Add participants
                </button>

                <hr>
                <p class="h4">Change project leader</p>
                <form action="{{route('tasks.change_project_leader', $project->id)}}" method="POST">
                    @csrf
                    <select class="form-select" name="project_leader">
                        @if($project->leader_user_id != null)
                            <option value="{{$project->leader_user_id}}" disabled
                                    selected>{{$project->leader->first_name}} {{$project->leader->last_name}}</option>
                        @else
                            <option disabled selected>Select a leader</option>
                        @endif
                        @foreach($employees as $employee)
                            <option
                                value="{{$employee->user_id}}">{{$employee->user->first_name}} {{$employee->user->last_name}}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary mt-2">Change</button>
                </form>

                <div class="modal fade" id="addParticipants" tabindex="-1" aria-labelledby="addParticipantsLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addParticipantsLabel">Add Participants</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('tasks.project_add_user')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="project_id" value="{{$project->id}}">
                                    <select class="form-select" name="participants[]" multiple>
                                        @foreach($employees as $employee)
                                            <option
                                                value="{{$employee->id}}">{{$employee->user->first_name}} {{$employee->user->last_name}}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary mt-2">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</body>

<script src="{{asset('js/editProjectbuttons.js')}}"></script>
