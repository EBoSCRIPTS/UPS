<!DOCTYPE html>

<home>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Departments</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</home>



<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
            <div class="col-md-6">
                <h1 class="h2">Night Hours</h1>
                <p>Current night hours: {{ $nightHoursStart }} - {{ $nightHoursEnd }}</p>
                <form action="{{ route('settings.night_hours_update') }}" method="post">
                    @csrf
                    <select name="nightHoursStart" id="nightHoursStart">
                        @foreach ($hoursArray as $nightHours)
                            <option value="{{ $nightHours }}" name="nightHoursStart">{{ $nightHours }}</option>
                        @endforeach
                    </select>

                    <select name="nightHoursEnd" id="nightHoursEnd">
                        @foreach ($hoursArray as $nightHours)
                            <option value="{{ $nightHours }}" name="nightHoursEnd">{{ $nightHours }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>

    </div>

</div>
