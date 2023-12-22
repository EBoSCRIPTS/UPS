<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</head>

<body>
@if(isset(Auth::user()->email))
    {{ redirect('/home') }}
@else
    <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
        @if ($errors->has('email') || $errors->has('password'))
            <div class="alert alert-danger">
                @if ($errors->has('email'))
                    <strong>| {{ $errors->first('email')}} |</strong>
                @endif

                @if ($errors->has('password'))
                    <strong>{{ $errors->first('password')}} |</strong>
                @endif
            </div>
        @endif

        <form action="{{ route('logging_in') }}" method="POST" class="col-12 col-md-6">
            @csrf
            <div class="form-group mb-3 text-center">
                <h1>UPS</h1>
                <label for="email">EMAIL</label>
                <input type="email" name="email" id="email" class="form-control" required>

                <label for="password">PASSWORD</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>
    </div>
@endif
</body>
