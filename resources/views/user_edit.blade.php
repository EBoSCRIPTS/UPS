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
@if(Auth::user()->role_id ==1)

    @foreach ($users as $user)

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
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

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <ul class="list-group" style="padding: 10px">
            <li class="list-group-item">{{ $user->id }}
                ) {{ $user->first_name }} {{ $user->last_name }} {{ $user->email }}</li>
            @if(Auth::user()->id != $user->id)
                <form action="{{ route('user.delete') }}" method="POST" style="display: inline">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>

                <form style="display: inline">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Edit
                    </button>
                </form>
            @endif
        </ul>
    @endforeach

@endif
</body>
