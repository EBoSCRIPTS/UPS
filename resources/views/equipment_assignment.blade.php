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
            <select name="equipment" id="equipment" class="form-select">
                @foreach($equipments as $equipment)
                    <option value="{{$equipment->id}}">{{$equipment->name}}</option>
            @endforeach
            </select>

            <button type="submit" class="btn btn-primary mt-2">Assign</button>
        </form>
    </div>

</div>
</body>
