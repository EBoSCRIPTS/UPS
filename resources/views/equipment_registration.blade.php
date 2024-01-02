<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Equipment Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
            @include('components.errors')
            <div class="row">
            <div class="col-md-6">
        <p class="h4">Add equipment type</p>
        <form action="{{route('equipment.add_equipment_type')}}" method="POST">
            @csrf
            <label for="type" class="form-label">Type</label>
            <input type="text" name="type" id="type" class="form-control">
            <div class="btn-group">
                <button type="submit" class="btn btn-primary mt-3">Add type</button>
                <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#deleteEquipmentTypeModal">Delete type</button>
            </div>
        </form>
            </div>

        <div class="col-md-6">
        <p class="h4">Register equipment</p>
        <form action="{{route('equipment.add_equipment')}}" method="POST">
            @csrf
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-select">
            @foreach($types as $type)
                <option value="{{$type->id}}">{{$type->name}}</option>
            @endforeach
            </select>

            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control">

            <label for="serial_number" class="form-label">Serial Number</label>
            <input type="text" name="serial_number" id="serial_number" class="form-control">

            <button type="submit" class="btn btn-primary mt-3">Add equipment</button>
        </form>
        </div>
            </div>
        <hr class="hr"/>
        <div class="row">
            <div class="col-md-6">
                <p class="h4">List of assigned equipment</p>
            </div>
            <div class="col-md-6">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle float-end" type="button" data-bs-toggle="dropdown">Filter by type
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                        @foreach($types as $type)
                            <li style="margin-left: 15px"><input type="checkbox" class="type-checkbox" value="{{$type->name}}"> {{$type->name}}</li>
                        @endforeach
                        </ul>
                    </div>
            </div>
        </div>
        <table class="table" id="equipment-table">
            <thead>
                <tr>
                    <th scope="col">Type</th>
                    <th scope="col">Name</th>
                    <th scope="col">Assigned To</th>
                    <th scope="col">Serial Number</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipments as $equipment)
                    <tr>
                        <td>{{$equipment->type->name}}</td>
                        <td>{{$equipment->name}}</td>
                        <td>{{$assignedEquipment[$equipment->id]}}</td>
                        <td>{{$equipment->serial_number}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr class="hr"/>
        <p class="h4">List of available equipment</p>

        <table class="table" id="equipment-table">
            <thead>
                <tr>
                    <th scope="col">Type</th>
                    <th scope="col">Name</th>
                    <th scope="col">Serial Number</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                 @foreach($availableEquipments as $availableEq)
                     <tr>
                         <td>{{$availableEq->type->name}}</td>
                         <td>{{$availableEq->name}}</td>
                         <td>{{$availableEq->serial_number}}</td>
                         <td>
                             <form action="{{route('equipment.delete_equipment')}}" method="POST">
                                 @csrf
                                 <input type="hidden" name="id" value="{{$availableEq->id}}">
                             <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                             </form>
                         </td>
                     </tr>
                 @endforeach
            </tbody>

        </table>
        </div>
    </div>
</div>
</body>

<!-- Modal -->
<div class="modal fade" id="deleteEquipmentTypeModal" tabindex="-1" aria-labelledby="deleteEquipmentTypeModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEquipmentTypeModal">Delete equipment type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('equipment.delete_equipment_type')}}" method="POST">
                    @csrf
                @foreach($types as $type)
                    <input type="hidden" name="id" value="{{$type->id}}">
                    {{$type->name}}
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    <br>
                @endforeach
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.type-checkbox');
        const rows = document.querySelectorAll('.table tbody tr');

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
