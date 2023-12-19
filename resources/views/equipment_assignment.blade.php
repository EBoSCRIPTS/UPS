<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
    <p class="h3">Assign equipment</p>
        <form action="{{route('equipment.assign_equipment')}}" method="POST">
            @csrf
            <label for="employee" class="form-label">Employee</label>
            <select name="employee" id="employee" class="form-select">
                @foreach($employees as $employee)
                    <option value="{{$employee->id}}">{{$employee->user->first_name}} {{$employee->user->last_name}}</option>
            @endforeach
            </select>

            <label for="equipment" class="form-label">Equipment</label>
            <select name="equipment" id="equipment" class="form-select" multiple size="10">
                @foreach($equipments as $equipment)
                    <option value="{{$equipment->id}}">{{$equipment->name}} ({{$equipment->serial_number}})</option>
            @endforeach
            </select>

            <button type="submit" class="btn btn-primary mt-2">Assign</button>

            <hr class="hr"/>

        </form>
        <p class="h3">Get equipment for user</p>
        <div class="row">
            <div class="col-md-6">
        <form action="{{route('equipment.get_equipment_for_user')}}" method="POST">
            @csrf
            <select name="employee" id="employee" class="form-select">
                <option value="---" selected>Select an employee</option>
                @foreach($employees as $employee)
                    <option value="{{$employee->id}}">{{$employee->user->first_name}} {{$employee->user->last_name}}</option>
            @endforeach
            </select>
            <button type="submit" class="btn btn-primary mt-2">Get equipment</button>
        </form>
            </div>
                <div class="col-md-6">
        @if(isset($assignments))
            <form action="{{route('equipment.generate_agreement')}}" method="POST">
                @csrf
                <input type="hidden" name="employee" value="{{$employeeFor}}">
                <button type="submit" class="btn btn-info btn-sm">Generate PDF Agreement</button>
            </form>
                </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Serial Number</th>
                    <th scope="col">Assigned on</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td>{{$assignment->equipment->name}} </td>
                    <td>{{$assignment->equipment->serial_number}}</td>
                    <td>{{$assignment->date_given}}</td>
                    <td>
                        <form action="{{route('equipment.return_equipment')}}" method="POST">
                            @csrf
                            <input type="hidden" name="assignment_id" value="{{$assignment->id}}">
                            <input type="hidden" name="id" value="{{$assignment->equipment->id}}">
                        <button type="submit" class="btn btn-danger btn-sm">Return</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif
        </div>
    </div>

</div>
</body>
