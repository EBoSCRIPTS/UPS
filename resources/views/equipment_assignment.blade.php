<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Equipment Assignment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
            @include('components.errors')
            <p class="h3">Assign equipment</p>
            <form action="{{route('equipment.assign_equipment')}}" method="POST">
                @csrf
                <label for="employee" class="form-label">Employee</label>
                <select name="employee" id="employee" class="form-select">
                    @foreach($employees as $employee)
                        <option
                            value="{{$employee->id}}">{{$employee->user->first_name}} {{$employee->user->last_name}}</option>
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
                    <select name="employee" id="employeeEquipment" class="form-select">
                        <option value="---" selected disabled>Select an employee</option>
                        @foreach($employees as $employee)
                            <option
                                value="{{$employee->id}}">{{$employee->user->first_name}} {{$employee->user->last_name}}</option>
                        @endforeach
                    </select>
                    <button id="getEquipmentButton" type="button" class="btn btn-primary mt-2">Get equipment</button>
                </div>
                <div class="col-md-6">
                    @if(isset($assignments))
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pdfModal">
                            Setup PDF agreement
                        </button>
                </div>
            </div>
            @if(sizeof($assignments) > 0)
                <p class="text-center">Equipment
                    for: {{$assignments[0]->employee->user->first_name}} {{$assignments[0]->employee->user->last_name}}</p>
            @endif
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

@if(isset($assignments))
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">PDF setup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('equipment.generate_agreement')}}" method="POST">
                        @csrf
                        <input type="hidden" name="employee" value="{{$employeeFor}}">
                        <label for="equipmentText" class="form-label">Agreement text</label>
                        <textarea name="equipmentText" id="equipmentText" cols="30" rows="10"
                                  class="form-control"></textarea>
                        <button type="submit" class="btn btn-info btn-sm mt-2 float-end">Generate PDF Agreement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    document.getElementById('getEquipmentButton').addEventListener('click', function () {
        const selectedEmployeeId = document.getElementById('employeeEquipment').value;

        if (selectedEmployeeId !== '---') {
            window.location.href = '/equipment/equipment_assignment/' + selectedEmployeeId;
        } else {
            alert('Please select an employee.');
        }
    });
</script>
