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
        @include('components.tasks_navbar')
        <div class="container" style="width: 80%">
            @include('components.errors')
            <p class="h3">Create performance report</p>
            <p class="h5">In the month {{Carbon\Carbon::now()->monthName}}</p>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Actions</th>
                        <th scope="col">Average rating</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projectMembers as $employee)
                        <div class="modal fade" id="performanceReportModal{{$employee->employee_id}}" tabindex="-1" aria-labelledby="performanceReportModal" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="performanceReportModal{{$employee->employee_id}}">{{$employee->employee->user->first_name}} {{$employee->employee->user->last_name}} performance report</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('tasks.performance_report_create', ['project_id' => $projectId]) }}" method="post">
                                            @Csrf
                                            <input type="hidden" name="date" value="{{Carbon\Carbon::now()}}">

                                            <textarea class="form-control" name="performance_report" id="performance_report" col="30" rows="10"></textarea>
                                            <input class="form-select mt-3" type="number" name="performance_rating" id="performance_rating" min="1" max="100" placeholder="1-100">

                                            <input type="hidden" name="employee_id" id="employee_id" value="{{$employee->employee_id}}">
                                            <button type="submit" class="btn btn-primary float-end mt-3">Save changes</button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <tr>
                            <td>{{$employee->employee->user->first_name}} {{$employee->employee->user->last_name}}</td>
                            <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#performanceReportModal{{$employee->employee_id}}">Create a performance report</button></td>
                            @if(isset($userPerformance[$employee->employee->user->id]))
                            <td>{{$userPerformance[$employee->employee->user->id]}}</td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="/tasks/projects/{{$projectId}}/performance_report/generate_xlsx" class="btn btn-success">Generate XLSX</a>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h3>Previous ratings</h3>
                </div>
                <div class="col-md-6">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle float-end" type="button" data-bs-toggle="dropdown">Filter by name
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            @foreach($projectMembers as $employee)
                                <li style="margin-left: 15px"><input type="checkbox" class="type-checkbox" value="{{$employee->employee->user->first_name}} {{$employee->employee->user->last_name}}">{{$employee->employee->user->first_name}} {{$employee->employee->user->last_name}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <table class="table table-striped" id="tableRatings">
                 <thead>
                    <tr>
                        <th scope="col">Employee Name</th>
                        <th scope="col">Rating description</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Month(year)</th>
                        <th scope="col">Action</th>
                    </tr>
                 </thead>
                <tbody>
                    @foreach($reportedMonthPerformance as $performance)
                        <tr>
                            <td>{{$performance->user->first_name}} {{$performance->user->last_name}}</td>
                            <td>{{$performance->rating_text}}</td>
                            <td>{{$performance->rating}}</td>
                            <td>{{$performance->month}} ({{$performance->year}})</td>
                            <td><a href="/tasks/projects/{{$projectId}}/performance_report/soft_delete/{{$performance->id}}" class="btn btn-danger">Delete</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.type-checkbox');
        const rows = document.querySelectorAll('#tableRatings tbody tr');
        console.log(rows);

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const checkedTypes = Array.from(checkboxes)
                    .filter(c => c.checked)
                    .map(c => c.value);

                rows.forEach(function (row) {
                    const typeCell = row.querySelector('td:first-child');
                    const typeId = typeCell.textContent.trim();

                    if (checkedTypes.length === 0 || checkedTypes.includes(typeId)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    });

</script>
