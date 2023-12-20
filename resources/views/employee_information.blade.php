<!DOCTYPE html>

<home>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Employee Information</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</home>

<body>
<div class="row">

    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
        <form action="{{route('employee_information.create')}}" method="POST">
            @csrf
            <label for="employee_name" class="form-label">Unassigned Employee Name</label>
            <select class="form-select" name="employee_id">
                @foreach($users as $employee)
                <option name="employee_id" value="{{ $employee->id }}">{{$employee->first_name}} {{$employee->last_name}}</option>
                @endforeach
            </select>

            <label for="departament" class="form-label">Departament</label>
            <select class="form-select" name="department_id">
                @foreach($departments as $department)
                    <option name="department_id" value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>

            <label for="typeOfPay" class="form-label">Salary type</label>
            <select class="form-select" name="typeOfPay" id="typeOfPay">
                <option value="disabled" disabled selected>---</option>
                <option value="hour">Hour pay</option>
                <option value="fixed">Set salary</option>
            </select>

            <input type="text" class="form-control" id="hour_pay" name="hour_pay" placeholder="Hourly"/>

            <input type="text" class="form-control" id="salary" name="salary" placeholder="Fixed"/>


            <label for="position" class="form-label">Position</label>
            <input type="text" class="form-control" id="position" name="position"/>

            <label for="hours" class="form-label">Hours per Month</label>
            <input type="text" class="form-control" id="hours" name="hours"/>

            <button type="submit" class="btn btn-primary mt-3">Add</button>

        </form>
        <hr class="hr"/>
        @foreach($departments as $department)
        <div class="btn-group">
            <button class="btn btn-primary btn-sm"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse{{$department->name}}" aria-expanded="false" aria-controls="collapse{{$department->name}}">
                {{$department->name}}
            </button>
        </div>
            @endforeach


        @foreach($departments as $department)
            <div id="collapse{{$department->name}}" class="collapse">
                <table class="table mt-3">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">{{$department->name}}</th>
                        <th scope="col">Hour Pay</th>
                        <th scope="col">Salary</th>
                        <th scope="col">Position</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
            <tbody>
            @foreach($employees as $employee)
                @if($employee->department_id == $department->id)
                    <tr>
                        <td>{{$employee->user->first_name}} {{$employee->user->last_name}} </td>
                        <td>{{$employee->hour_pay}}</td>
                        <td>{{$employee->salary}}</td>
                        <td style="max-width: 20px">{{$employee->position}}</td>
                        <td>
                        <form action="{{route('employee_information.delete')}}" method="POST">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        </td>
                    </tr>
               @endif
             @endforeach
            </tbody>
                </table>
            </div>
        @endforeach

        </div>
    </div>
</div>

<script>
    $('#hour_pay').hide();
    $('#salary').hide();
$('#typeOfPay').change(function(){
    selection = $(this).val();

    switch (selection){
        case 'hour':
            $('#hour_pay').show();
            $('#salary').hide();
            break;
        case 'fixed':
            $('#hour_pay').hide();
            $('#salary').show();
            break;
    }
})
</script>
</body>
