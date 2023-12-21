<!DOCTYPE html>

<home>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Accountant Department Settings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</home>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="row">

            <div class="col-sm-4">
                <p class="h4">Add Tax</p>
                <form action="{{route('accountant.add_tax', $department)}}" method="POST">
                    @csrf
                    <label for="tax_name" class="form-label">Tax name</label>
                    <input type="text" class="form-control" id="tax_name" name="tax_name">

                    <label for="tax_rate" class="form-label">Tax rate</label>
                    <input type="text" class="form-control" id="tax_rate" name="tax_rate">

                    <label for="tax_salary_from" class="form-label">Tax from salary that starts from: </label>
                    <input type="text" class="form-control" id="tax_salary_from" name="tax_salary_from">

                    <button type="submit" class="btn btn-primary mt-3 float-end">Submit</button>
                </form>
            </div>
            <div class="col-sm-4">
                @if(isset($settings))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tax Name</th>
                            <th>Tax Rate</th>
                            <th>Starts from</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settings as $setting)
                        <tr>
                            <td>{{$setting->tax_name}}</td>
                            <td>{{$setting->tax_rate}}%</td>
                            <td>{{$setting->tax_salary_from}}</td>
                            <td><a href="/accountant/settings/{{$department}}/delete_tax/{{$setting->id}}">Delete</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

        </div>
    </div>

</div>
</body>
