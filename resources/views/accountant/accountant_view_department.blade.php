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
        <p class="h2">Department: {{$department->name}} summary | <a href="/accountant/settings/{{$department->id}}">Tax
                Settings</a></p>
        <div class="row" style="margin-top: 50px">
            <div class="col-sm-6">
                <p class="h3 text-center">Department Employees</p>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Employee Name</th>
                        <th scope="col">Position</th>
                        <th scope="col">Weekly Hours</th>
                        <th scope="col">Pay</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td>{{$employee->user_id}} {{$employee->user->first_name}} {{$employee->user->last_name}}</td>
                            <td>{{$employee->position}}</td>
                            <td>{{$employee->weekly_hours}}</td>
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
                <div class="row">
                    <div class="col-sm-6">
                        <p class="h3 text-center">Recent employee absences</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle float-end" type="button"
                                    data-bs-toggle="dropdown">Filter by name
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                @foreach($employees as $employee)
                                    <li style="margin-left: 15px"><input type="checkbox" class="type-checkbox"
                                                                         value="{{$employee->user->first_name}} {{$employee->user->last_name}}">{{$employee->user->first_name}} {{$employee->user->last_name}}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <table class="table" id="tableAbsences">
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
                                    <a href="/accountant/payslip/{{$department->id}}/{{$employee->user_id}}/{{\Carbon\Carbon::now()->year}}/{{$month}}"
                                       class="btn btn-primary btn-sm">Generate</a>
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
                            <td>
                                <a href="/accountant/payslip/{{$department->id}}/{{$fulfilled->employee_id}}/{{$fulfilled->year}}/{{$fulfilled->month}}/download">Download</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
            </div>
        </div>
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


    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.type-checkbox');
        const rows = document.querySelectorAll('#tableAbsences tbody tr');
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
