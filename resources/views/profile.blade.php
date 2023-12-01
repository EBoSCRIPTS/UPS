<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
    @include('components.sidebar')
        <div class="grid">
        @if(isset(Auth::user()->email))
            <div class="row">
                <div class="col-5 col-md-3">
                <h2> <img class="img-responsive float-start" src="data:image/png;base64,{{ base64_encode(Auth::user()->profile_picture) }}" alt="Profile Picture"> </h2>
                </div>
                <div class="col-6 col-md-4">
                <p class="fs-3">User: {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                <p class="fs-3">Email: {{ Auth::user()->email }}</p>
                <p class="fs-3">Phone Number: {{ Auth::user()->phone_number }}</p>
                @if(Auth::user()->role_id == 1)
                    <p class="fs-3">Role: SuperAdmin</p>
                @endif
            </div>
            </div>
            @else
            {{redirect('/login')}}
        @endif
        </div>
    </div>

</body>
