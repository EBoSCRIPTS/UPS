<!doctype html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
<h1>{{$title}}</h1>
<p class="h4">{{$date}}</p>
<p class="h4">Employee assigned to {{$employee}}</p>
<hr class="hr"/>
<p class="h5">Agreement</p>
<p>{{$text}}.</p>

<p class="h4"> List of equipment given to the employee</p>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Serial Number</th>
        </tr>
    </thead>
    <tbody>
    @foreach($equipment as $eq)
    <tr>
            <td>{{$eq['name']}}</td>
            <td>{{$eq['serial_number']}}</td>
    </tr>
    @endforeach
    </tbody>
</table>


<p class="h4" style="bottom: 0; margin-top: 50px">Employee Signature</p>

<p style="margin-top: 50px">__________________</p>
</body>
