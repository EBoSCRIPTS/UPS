<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Vacation Review</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="h2 text-center">Vacation review page</p>
                            {{$employeeDetails->user->first_name}} {{$employeeDetails->user->last_name}}
                            <br>
                            VP: {{$vps}}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="h2 text-center">Previous Vacations</p>
                            @if(isset($previous->date_from) && isset($previous->date_to))
                                @foreach($previous as $prev)
                                    <p>From {{$prev->date_from}} till {{$prev->date_to}} | Status: {{$prev->is_paid}}</p>
                                @endforeach
                            @endif

                            {{$previous}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p>One day = 0.25VP</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
</body>

<script>
    function calculatePoints(){

    }
</script>
