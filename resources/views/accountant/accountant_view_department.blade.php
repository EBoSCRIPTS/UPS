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
        <form action="{{route('accountant_view_department')}}" method="POST">
            @csrf
        <select class="form-select" name="employee">
            <option selected disabled>---</option>
        @foreach($employees as $employee)
                <option value="{{$employee->user_id}}">{{$employee->user->first_name}} {{$employee->user->last_name}}</option>
        @endforeach
        </select>
        <button type="submit" class="btn btn-primary">LOAD</button>
        </form>
    </ul>

        @if($loadEmployee)
            <div class="card">
                <div class="card-body">
                    <p><b>{{$employeeInformation->user->first_name}} {{$employeeInformation->user->last_name}}</b></p>
                    <p>{{$employeeInformation->hour_pay}}</p>
                    <p>Worked hours this month: {{$hours}} / {{$employeeInformation->monthly_hours}} of expected</p>
                    <p>Expected payout: ${{$expectedPay}}</p>
            </div>
        @endif
    </div>

</div>
</body>
