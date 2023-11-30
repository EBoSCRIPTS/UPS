<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
    @if(isset(Auth::user()->email))
        <h1>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h1>
    @endif

    @if(!isset(Auth::user()->email))
    <form action="{{ route('logging_in') }}" method="POST">
        @csrf
        <label for="email">EMAIL</label>
        <input type="email" name="email" id="email" required>

        <label for="password">PASSWORD</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>

    </form>
    @endif
</body>
