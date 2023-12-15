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
            <p class="h3">Project settings</p>
                <div class="project-status-fields">
                <p class="h4">Project status fields: </p>
                    <div class="row">
                    <small>The status order must be from top-to-bottom</small>
                    </div>
                     <button type="button" class="btn btn-primary mt-2" onclick="addField()">Add field</button>
                </div>
            <button type="submit" class="btn btn-primary mt-2" onclick="return confirm('Are you sure?')">Submit</button>
        </div>
            </div>

        </form>
    </div>

</div>
</body>

<script>
    async function getDepartments() {
        const response = await fetch('/api/get_all_departments');
        const selectField = document.getElementById('department_id');
        let data = await response.json();

        console.log(data);

        for (let i = 0; i < data.length; i++) {
            let departmentOption = document.createElement('option');
            departmentOption.value = data[i].id;
            departmentOption.text = data[i].name;
            selectField.append(departmentOption);
        }
    }

    window.onload = function() {
        getDepartments();
        sessionStorage.clear()
        sessionStorage.setItem('count', 0);
    }

    function addField()
    {
        let count = sessionStorage.getItem('count');
        count = Number(count);
        count = count + 1;
        sessionStorage.setItem('count', count);
        const projectStatusFields = document.getElementsByClassName('project-status-fields');
        const inputField = document.createElement('input');
        const counter = document.createElement('input');
        inputField.type = 'text';
        inputField.name = 'project_status_field' + count;
        inputField.className = 'form-control mt-2';
        inputField.placeholder = 'Status field name...';

        projectStatusFields[0].appendChild(inputField);

        counter.type = 'hidden';
        counter.name = 'counter';
        counter.value = count;
        projectStatusFields[0].appendChild(counter);

    }

</script>
