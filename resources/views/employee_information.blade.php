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
            @include('components.errors')
        <form action="{{route('employee_information.create')}}" method="POST">
            @csrf
            <label for="employee_name" class="form-label">Unassigned Employee Name</label>
            <select class="form-select" name="employee_id">
                @foreach($users as $employee)
                <option name="employee_id" value="{{ $employee->id }}">{{$employee->first_name}} {{$employee->last_name}}</option>
                @endforeach
            </select>

            <label for="departament" class="form-label">Department</label>
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

            <label for="hours" class="form-label">Hours per Week</label>
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
                        <th scope="col">Bank Details</th>
                        <th scope="col">Weekly Hours</th>
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
                        <td style="max-width: 20px;overflow-x: auto">
                            @if(isset($employee->bank_account))
                                 {{$employee->bank_account}}
                                {{$employee->bank_account_name}}
                            @endif
                        </td>
                        <td>{{$employee->weekly_hours}}</td>
                        <td>
                            <div class="btn-group">
                            <form action="{{route('employee_information.delete')}}" method="POST">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                            </form>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{$employee->id}}">Edit</button>
                            </div>
                        </td>
                    </tr>
               @endif
                <div class="modal fade" id="editModal{{$employee->id}}" tabindex="-1" aria-labelledby="editModal{{$employee->id}}_label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModal{{$employee->id}}">Edit employee information</h5>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('employee_information.update')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                                    <label for="dept_name" class="form-label">Department</label>
                                    <select name="dept_name" id="dept_name" class="form-select">
                                        <option selected disabled>Pick a new department</option>
                                        @foreach($departments as $dept)
                                            <option value={{$dept->id}}>{{$dept->name}}</option>
                                        @endforeach
                                    </select>

                                    <label for="hour_pay" class="form-label">Hour Pay</label>
                                    <input type="text" class="form-control" id="hour_pay" name="hour_pay" placeholder="{{$employee->hour_pay}}"/>

                                    <label for="salary" class="form-label">Salary</label>
                                    <input type="text" class="form-control" id="salary" name="salary" placeholder="{{$employee->salary}}"/>

                                    <label for="position" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="position" name="position" placeholder="{{$employee->position}}"/>

                                    <label for="hours" class="form-label">Weekly Hours</label>
                                    <input type="number" class="form-control" id="hours" name="hours" placeholder="{{$employee->weekly_hours}}"/>

                                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                                </form>

                            </div>
                        </div>
                        </div>
                    </div>
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
