<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
        <div class="modal fade" id="exampleModal{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel{{$user->id}}">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.edit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <input type="text" name="first_name" placeholder="{{$user->first_name}}">
                            <input type="text" name="last_name" placeholder="{{$user->last_name}}">
                            <input type="text" name="email" placeholder="{{$user->email}}">
                            <input type="text" name="phone_number" placeholder="{{$user->phone_number}}">
                            <input type="file" name="profile_picture">

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
            @endforeach
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <ul class="list-group">
                @foreach ($users as $user)
                    <li class="list-group-item">
                        {{ $user->id }}) {{ $user->first_name }} {{ $user->last_name }} {{ $user->email }} {{ $user->role_id }}
                        <form action="{{ route('user.delete') }}" method="POST" style="display: inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>

                        <form style="display: inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal{{$user->id}}">
                                Edit
                            </button>
                        </form>
                    </li>
                @endforeach
        </ul>
        </div>
@else
    <noscript>You are not supposed to be here :(, go back!</noscript>
    <script>window.location = "/"</script>
@endif
</div>
</body>
