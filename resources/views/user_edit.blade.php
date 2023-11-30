<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
@if(Auth::user()->role_id ==1)

@foreach ($users as $user)
    <li>{{ $user->id }} {{ $user->first_name }} {{ $user->last_name }} {{ $user->email }}</li>
    <form action="{{ route('user.delete') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <button type="submit">Delete</button>
    </form>
@endforeach


    @endif
</body>
