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
        <form action="{{route('employee_information.create')}}" method="POST">
            @csrf
            <label for="employee_name" class="form-label">Employee Name</label>
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

            <label name="hour_pay" for="hour_play" class="form-label">Hour Pay</label>
            <input type="text" class="form-control" id="hour_pay" name="hour_pay"/>

            <label name="salary" for="salary" class="form-label">Salary</label>
            <input type="text" class="form-control" id="salary" name="salary"/>


            <label name="position" for="position" class="form-label">Position</label>
            <input type="text" class="form-control" id="position" name="position"/>

            <button type="submit" class="btn btn-primary mt-3">Add</button>

        </form>
        <hr class="hr"/>

        @foreach($departments as $department)
            <table class="table mt-3">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">{{$department->name}}</th>
                        <th scope="col">Hour Pay</th>
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
                        <td>{{$employee->position}}</td>
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
        @endforeach

    </div>
</div>
</body>
