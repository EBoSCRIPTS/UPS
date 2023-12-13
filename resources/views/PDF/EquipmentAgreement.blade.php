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
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
    Aliquam non sollicitudin turpis, a condimentum quam.
    Maecenas eget suscipit enim.
    Aliquam ac orci et metus consectetur venenatis.
    Vestibulum auctor quam a nulla ullamcorper pellentesque.
    Nam vehicula turpis felis.
    Nunc nisi enim, lacinia et imperdiet vitae, efficitur quis orci.
    Nunc nulla quam, fringilla sed commodo non, rutrum hendrerit elit.
    Nunc lacinia, mauris ut varius blandit, nulla ex bibendum dolor,
    et convallis leo lacus sed massa. Donec convallis quam erat,
    a rutrum sapien tincidunt placerat. Suspendisse efficitur,
    purus vel ultrices pretium, leo eros mollis mauris,
    ut faucibus neque purus faucibus sapien.
    Nullam maximus consequat tellus, in viverra tellus cursus id.
    Morbi et ullamcorper nibh.
    Nam ipsum velit, pharetra ac congue vitae,
    blandit id metus. Nunc risus odio, dignissim in accumsan vitae,
    tempor et sapien. Proin est nibh,
    commodo non turpis egestas, suscipit vehicula lorem.
    Morbi rhoncus finibus elit, vitae pharetra purus ullamcorper ac.</p>

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
