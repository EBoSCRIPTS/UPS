<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Users Create</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="container" style="width: 80%">
            @include('components.errors')
            <h1 class="display-2 text-center">Add new user </h1>
            <form action="{{ route('user.create') }}" method="POST">
                <div class="form-group">
                    @csrf
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" required>

                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" required>

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>

                    <label for="phone_number">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" required>

                    <label for="profile_picture">Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control">

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control width" required>

                    <label for="role_id">Role</label>
                    <select name="role_id" id="role_id" class="form-control">
                        <option selected disabled>Pick a role..</option>
                        @foreach($roles as $role)
                            @if($role->id == 2)
                            @else
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endif
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary" style="margin-top: 10px">Submit</button>
                </div>
            </form>
        </div>
    </div>

</div>
</body>
