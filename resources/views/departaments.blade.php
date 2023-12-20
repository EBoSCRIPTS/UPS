<!DOCTYPE html>

<home>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Departments</title>

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
        <div class="container" style="width: 80%">
        <form action="{{route('departments.create')}}" method="POST">
            @csrf
            <label name="departament" for="departament" class="form-label">Departament</label>
            <input type="text" class="form-control" id="departament" name="departament"/>

            <label name="description" for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>

            <button type="submit" class="btn btn-primary mt-2">Add</button>
        </form>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">Dept name</th>
                <th scope="col">Description</th>
                <th scope="col">Action</th>
            </tr>
            </thead>

            <tbody>
                @foreach($departments as $department)
                <tr>
                    <td>{{$department->name}}</td>
                    <td>{{$department->description}}</td>
                    <td>
                        <form action="{{route('departments.delete')}}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{$department->id}}">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
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
