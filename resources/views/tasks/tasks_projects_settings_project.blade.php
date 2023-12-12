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
            <p class="h2">Projects settings</p>
            <hr class="hr"/>
            <form action="{{route('tasks.project_edit', $project->id)}}" method="POST">
                @csrf
                <div class="editButtons">
                <button id="editButton" type="button" class="btn btn-primary btn-sm float-end" onclick="enableDisabledInputs()">Edit</button>
                </div>
                <label class="h4" for="name">Project name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="{{$project->name}}" disabled/>

                <div class="status-fields">
                @foreach($statuses as $status)
                    <label for="status">Status</label>
                    <input type="text" class="form-control" id="status" name="status[]" placeholder="{{$status}}" disabled/>
                @endforeach
                </div>
            </form>
            <hr class="hr"/>
            <p class="h4">Project participants</p>
            <ul class="list-group">
            @foreach($projectUsers as $projectUser)
                <form action="{{route('tasks.project_remove_user')}}" method="POST">
                    @csrf
                    <li class="list-group-item">{{$projectUser->employee->first_name}} {{$projectUser->employee->last_name}}
                    <button type="submit" class="btn btn-danger btn-sm float-end">Remove</button>
                        <input type="hidden" name="project_id" value="{{$project->id}}">
                        <input type="hidden" name="user_id" value="{{$projectUser->employee_id}}">
                    </li>
                </form>
            @endforeach
            </ul>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addParticipants">
                Add participants
            </button>

            <div class="modal fade" id="addParticipants" tabindex="-1" aria-labelledby="addParticipantsLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addParticipantsLabel">Add Participants</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('tasks.project_add_user')}}" method="POST">
                                @csrf
                                <input type="hidden" name="project_id" value="{{$project->id}}">
                                <select class="form-select" name="participants[]" multiple>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary mt-2">Add</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>

</body>

<script>
    function enableDisabledInputs() {
        const inputs = document.getElementsByTagName('input');
        const editFields = document.getElementsByClassName('editFields');
        const editButton = document.getElementById('editButton');
        const saveButton = document.createElement('button');
        const cancelButton = document.createElement('button');
        const addFieldButton = document.createElement('button');

        saveButton.textContent = '✔';
        saveButton.class = 'btn btn-primary btn-sm mb-2';
        saveButton.type = 'submit';

        cancelButton.textContent = '𐄂';
        cancelButton.class = 'btn btn-danger btn-sm mb-2';

        addFieldButton.textContent = '+';
        addFieldButton.class = 'btn btn-primary btn-sm mb-2';
        addFieldButton.type = 'button';
        addFieldButton.onclick = addField;

        editButton.appendChild(cancelButton);
        editButton.appendChild(saveButton);
        editButton.append(addFieldButton);


        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].disabled) {
                inputs[i].value = inputs[i].placeholder;
                inputs[i].disabled = false;
            }
        }
    }


    function addField()
    {
        const projectStatusFields = document.getElementsByClassName('status-fields');
        const labelNewField = document.createElement('label');
        const inputField = document.createElement('input');

        labelNewField.for = 'status[]';
        labelNewField.textContent = 'New Status';

        inputField.type = 'text';
        inputField.name = 'status[]'
        inputField.className = 'form-control mt-2';
        inputField.placeholder = 'Status field name...';

        projectStatusFields[0].appendChild(labelNewField);
        projectStatusFields[0].appendChild(inputField);


    }
</script>
