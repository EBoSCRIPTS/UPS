<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"> CREATE A NEW TASK</button>
    <select id="project_id" class="form-select" style="width: 10%; margin-left: 10px;">
        <option disabled selected>PROJECTS</option>
    </select>
</nav>

<div class="modal" id="exampleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{route('create_new_task')}}" method="POST">
                @csrf
            <div class="modal-header">
                <input type="text" class="form-control" id="task_name" name="task_name" placeholder="TASK NAME"/>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="project" class="form-label">Project</label>
                        <select id="project" name="project" class="form-select">
                        </select>

                        <label for="text" class="form-label">Text</label>
                        <textarea id="description" name="description" class="form-control">
                        </textarea>

                        <label for="priority" class="form-label">Priority</label>
                        <select id="priority" name="priority" class="form-select">
                            <option value="low">LOW</option>
                            <option value="medium">MEDIUM</option>
                            <option value="high">HIGH</option>
                            <option value="critical">CRITICAL</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="assignTo" class="form-label">Assign To</label>
                        <input id="assign_to" name="assign_to" type="text" class="form-control" placeholder="Search for user" oninput="userSearchDebounced">
                        <a href="#" id="assignToMe" onclick="assignToMe()"> <small>Assign to me</small></a>
                        <input type="hidden" id="madeBy" name="made_by" value="{{ Auth::user()->id }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create a ticket</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>
    async function getProjects(field_id) {
        const response = await fetch('/api/get_all_projects');
        let selectField = document.getElementById(field_id);
        let data = await response.json();

        console.log(data);

        for (let i = 0; i < data.length; i++) {
            let projectOption = document.createElement('option');
            projectOption.value = data[i].id;
            projectOption.text = data[i].name;
            projectOption.href = data[i].id;
            selectField.append(projectOption);
        }

        selectField.addEventListener('change', function(){
            const selectedValue = this.value;
            window.location.href='/tasks/projects/' + selectedValue;
        })
    }

    function assignToMe()
    {
        const assignToMe = document.getElementById('assign_to');
        assignToMe.value = '{{ Auth::user()->id }}';
    }

    window.onload = function() {
        getProjects('project_id');
        getProjects('project');
    }


</script>
