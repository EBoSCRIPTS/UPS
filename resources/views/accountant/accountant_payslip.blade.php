<!DOCTYPE html>

<home>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Accountant Payslips</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</home>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Payslip</h5>
                    <p class="card-text">
                        For: <strong>{{$employee->user->first_name}} {{ $employee->user->last_name }}</strong>
                        <br>
                        Department: <strong>{{$employee->department->name}}</strong>
                        Position: <strong>{{$employee->position}}</strong>
                        <br>
                        Monthly Hours: <strong>{{$employee->monthly_hours}}</strong>
                    </p>
            </div>
        </div>
    </div>

</div>
</body>
