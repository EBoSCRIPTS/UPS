<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create a project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
            <div class="container" style="width: 80%">
                <p class="h2">Create a project</p>
                <form action="{{route('create_new_project')}}" method="POST">
                    @csrf
                    <label for="project_name">Project name:</label>
                    <input class="form-control" type="text" name="project_name" placeholder="Project name.." required>

                    <select id="department_id" name="department_id" class="form-control mt-1">
                        <option disabled selected>Select a department</option>
                    </select>

                    <button type="button" class="btn btn-primary mt-1" data-bs-toggle="collapse" data-bs-target="#projectSettings" aria-expanded="false" aria-controls="projectSettings">Create a new project</button>
                <hr class="hr"/>

                    <div class="collapse" id="projectSettings">
                <div class="project-settings">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="h3">Project settings</p>
                                <div class="project-status-fields">
                                <p class="h4">Project status fields: </p>
                                    <div class="row">
                                    <small>The status order must be from top-to-bottom</small>
                                    </div>
                                     <button type="button" class="btn btn-primary mt-2" onclick="addField()">Add field</button>
                                </div>
                        </div>
                        <div class="col-md-6">
                            <p class="h3">Project manager</p>
                            <select id="project_manager_id" name="project_manager_id" class="form-control mt-1" size="5">
                                <option disabled selected>Select a project manager</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary mt-5 float-end" onclick="return confirm('Are you sure?')">Submit</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <p class="h4">Add employees</p>
                        <div class="col-md-6">
                            <select id="project_members" name="project_members[]" class="form-control mt-1" style="max-width: 300px" multiple size="10">
                                @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->id}} {{$employee->user->first_name}} {{$employee->user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                    </div>

                </form>
            </div>
    </div>

</div>
</body>

<script src="{{asset('js/createproject.js')}}"></script>
