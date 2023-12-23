<!DOCTYPE html>

<home>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Accountant Department</title>

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
        <p class="h2">Department: {{$department->name}} summary || <a href="/accountant/settings/{{$department->id}}">Settings</a></p>
        <div class="row" style="margin-top: 50px">
            <div class="col-sm-6">
                <p class="h3 text-center">Department Employees</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Position</th>
                            <th scope="col">Monthly Hours</th>
                            <th scope="col">Pay</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{$employee->user_id}} {{$employee->user->first_name}} {{$employee->user->last_name}}</td>
                                <td>{{$employee->position}}</td>
                                <td>{{$employee->monthly_hours}}</td>
                                @if($employee->hour_pay != null)
                                    <td>{{$employee->hour_pay}}/hr</td>
                                @else
                                    <td>{{$employee->salary}}</td>
                                    @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <p class="h3 text-center">Recent employee absences</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Start date</th>
                            <th scope="col">End date</th>
                            <th scope="col">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allAbsences as $absences)
                        <tr>
                            <td>{{$absences[0]['user']->first_name}} {{$absences[0]['user']->last_name}}</td>
                            <td>{{$absences[0]['start_date']}}</td>
                            <td>{{$absences[0]['end_date']}}</td>
                            <td>{{$absences[0]['type']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-sm-6">
                <p class="h3 text-center">Requested payouts</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            @if($status[$employee->user_id] != null)
                                <tr>
                                    <td>{{$employee->user->first_name}} {{$employee->user->last_name}}</td>
                                    <td>
                                        <a href="/accountant/payslip/{{$department->id}}/{{$employee->user_id}}/{{\Carbon\Carbon::now()->year}}/{{$month}}" class="btn btn-primary btn-sm">Generate</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <p class="h3 text-center">Fulfilled payouts</p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Fulfilled by</th>
                                <th scope="col">Employee Name</th>
                                <th scope="col">For period</th>
                                <th scope="col">Fulfilled on</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allFulfilled as $fulfilled)
                                    <tr>
                                        <td>{{$fulfilled->fulfilledBy->first_name}} {{$fulfilled->fulfilledBy->last_name}}</td>
                                        <td>{{$fulfilled->employee->user->first_name}} {{$fulfilled->employee->user->last_name}}</td>
                                        <td>{{$fulfilled->year}}-{{$fulfilled->month}}</td>
                                        <td>{{$fulfilled->created_at}}</td>
                                        <td><a href="/accountant/payslip/{{$department->id}}/{{$fulfilled->employee->user_id}}/{{$fulfilled->year}}/{{$fulfilled->month}}">Go to</a></td>
                                    </tr>
                            @endforeach
                        </tbody>
            </div>


        </div>

{{--        <div class="row" style="margin-top: 50px">--}}
{{--            <div class="col-sm-6">--}}
{{--                <p class="h3 text-center">Expected expenses</p>--}}
{{--                <div class="d-flex" style="height: 90%">--}}
{{--                    <canvas id="pieChart"></canvas>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


    </div>
</div>
</body>

<script>
    const pieChart = document.getElementById('pieChart');

    new Chart(pieChart, {
        type: 'pie',
        data: {
            labels: ['Expected', 'Actual'],
            datasets: [{
                data: [{{$expectedPay}}, {{$realPay}}],
            }]
        }
    })
</script>
