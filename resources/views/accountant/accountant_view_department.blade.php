<!DOCTYPE html>

<home>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</home>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <p class="h2">Department: {{$department->name}} ({{$month}}) summary</p>
        <div class="row" style="margin-top: 50px">
            <div class="col-sm-6">
                <p class="h3 text-center">Department Employees</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Position</th>
                            <th scope="col">Monthly Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{$employee->user_id}} {{$employee->user->first_name}} {{$employee->user->last_name}}</td>
                                <td>{{$employee->position}}</td>
                                <td>{{$employeeReports[$employee->user_id]}}/{{$employee->monthly_hours}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="col-sm-6">

            </div>

        </div>


    </div>
</div>
</body>
