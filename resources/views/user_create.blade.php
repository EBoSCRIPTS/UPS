<!DOCTYPE html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>

    <form action="{{ route('user.create') }}" method="POST">
        @csrf 
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" required>

        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="phone_number">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Submit</button>
    </form>


</body>