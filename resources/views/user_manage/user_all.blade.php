<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>

@foreach ($users as $user)
    <li>{{ $user->first_name }} {{ $user->last_name }}</li>
@endforeach

</body>
