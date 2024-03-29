<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#taskModal"> CREATE A
        NEW TASK
    </button>
    <select id="project_id" class="form-select" style="width: 10%; margin-left: 10px;">
        <option disabled selected>PROJECTS</option>
    </select>
</nav>

<div class="modal" id="taskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{route('create_new_task')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <input type="text" class="form-control" id="task_name" name="task_name" placeholder="TASK NAME"
                           maxlength="255" required/>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="project" class="form-label">Project</label>
                            <select id="project" name="project" class="form-select">
                            </select>

                            <label for="text" class="form-label">Text</label>
                            <textarea id="description" name="description" class="form-control" required>
                        </textarea>

                            <label for="priority" class="form-label">Priority</label>
                            <select id="priority" name="priority" class="form-select">
                                <option value="low">LOW</option>
                                <option value="medium">MEDIUM</option>
                                <option value="high">HIGH</option>
                                <option value="critical">CRITICAL</option>
                            </select>

                            <label for="task_points" class="form-label">Task points(TP)</label>
                            <input type="number" class="form-select" id="task_points" name="task_points" required
                                   min="0" max="100">
                        </div>
                        <div class="col-md-6">
                            <div class="searchAssignee">
                                <label for="assignTo" class="form-label">Assign To</label>
                                <input id="assign_to" name="assign_to" type="text" class="form-control"
                                       placeholder="Search for user" oninput="userSearchDebounced()">
                                <a href="#" id="updateAssignees"></a>
                            </div>
                            <a href="#" id="assignToMe" onclick="assignToMe()"> <small>Assign to me</small></a>
                            <br>
                            <label for="draft">Draft</label>
                            <input class="form-check-input" type="checkbox" id="draft" name="draft">
                            <br>
                            <hr>
                            <small>If the task is draft, it won't be placed in the board but can be found under
                                project-tasks section and undrafted</small>
                            <hr>
                            <div class="row">
                                <small>Task Labels</small>
                                <div class="col-sm-4">
                                    <input type="radio" id="label_feature" name="task_label" value="feature">
                                    <label for="feature"><span class="badge bg-success">Feature</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="radio" id="label_bug" name="task_label" value="bug">
                                    <label for="bug"><span class="badge bg-warning">Bug</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="radio" id="label_ticket" name="task_label" value="Ticket">
                                    <label for="ticket"><span class="badge bg-info">Ticket</span></label>
                                </div>
                            </div>

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

<script src="{{asset('js/userSearch.js')}}"></script>
<script src="{{asset('js/projectSearch.js')}}"></script>
<script>
    function assignToMe() {
        const assignToMe = document.getElementById('assign_to');
        assignToMe.value = '{{ Auth::user()->id }} {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}';
    }

    const label_feature = document.getElementById('label_feature');
    const label_bug = document.getElementById('label_bug');
    const label_ticket = document.getElementById('label_ticket');
</script>
