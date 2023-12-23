<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Users Edit</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

</head>

<body>
<div class="row">
@include('components.sidebar')
@if(isset(Auth::user()->email) && Auth::user()->role_id == 1)
    @foreach ($users as $user)
        <div class="modal fade" id="editModal{{$user->id}}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{$user->id}}">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.edit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">

                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" id="first_name" name="first_name" placeholder="{{$user->first_name}}" class="form-control">

                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" id="last_name" name="last_name" placeholder="{{$user->last_name}}" class="form-control">

                            <label for="email" class="form-label">Email</label>
                            <input type="text" id="email" name="email" placeholder="{{$user->email}}" class="form-control">

                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" id="phone_number" name="phone_number" placeholder="{{$user->phone_number}}" class="form-control">

                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" id="profile_picture" name="profile_picture" class="form-control">

                            <button type="submit" class="btn btn-primary float-end mt-3">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            @endforeach
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
            <div class="container" style="width: 80%">
                <h1 class="display-3">Edit user</h1>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>{{$user->first_name}} {{$user->last_name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->role->name}}</td>
                                <td>
                                    <div class="btn-group">
                                        <form action="{{ route('user.delete') }}" method="POST" style="display: inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                        <form style="display: inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{$user->id}}">
                                                Edit
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
@else
    <noscript>You are not supposed to be here :(, go back!</noscript>
    <script>window.location = "/"</script>
@endif
</div>
</body>
