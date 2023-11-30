<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div class="profile">
        @if(isset(Auth::user()->email))
            <h2> <img src="data:image/png;base64,{{ base64_encode(Auth::user()->profile_picture) }}" alt="Profile Picture"> </h2>
            <h2>User: {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
            <h2>Email: {{ Auth::user()->email }}</h2>
            <h2>Phone Number: {{ Auth::user()->phone_number }}</h2>
            @if(Auth::user()->role_id == 1)
                <h2>Role: SuperAdmin</h2>
            @endif
            @else
            <script>window.location="/login"</script>
        @endif
    </div>

</body>
