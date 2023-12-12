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
        <p class="h4">Add equipment type</p>
        <form action="{{route('equipment.add_equipment_type')}}" method="POST">
            @csrf
            <label for="type" class="form-label">Type</label>
            <input type="text" name="type" id="type" class="form-control">
            <button type="submit" class="btn btn-primary mt-3">Add type</button></button>
        </form>

    <hr class="hr"/>
        <p class="h4">Registered equipment</p>
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
            <input type="text" name="serial_number" id="serial_number">

            <button type="submit" class="btn btn-primary mt-3">Add equipment</button>
        </form>
        <hr class="hr"/>
        <p class="h4">List of registered equipment</p>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Type</th>
                    <th scope="col">Name</th>
            </thead>
        </table>

    </div>
</div>
</body>
