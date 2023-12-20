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
                            <p>VP balance: {{$vps}}</p>
                            <p>Date: {{$request->start_date}} ->  {{$request->end_date}}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="h2 text-center">Previous Vacations</p>

                                @foreach($previous as $prev)
                                    <p>From {{$prev->date_from}} till {{$prev->date_to}} | Status:
                                        @if($prev->is_paid == '1')
                                        Paid</p>
                                        @else
                                            Unpaid
                                   @endif
                                @endforeach


                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <small>One day = 0.20 VP</small>
                        <br>
                       <div class="row">
                        <div class="col-sm-5">
                            <label for="calculate_balance">Calculate Spending Points:</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" id="calculate_balance" name="calculate_balance" placeholder="Workdays spent">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" onclick="calculatePoints()">Calculate</button>
                        </div>
                       </div>


                        <p id="result_points"></p>

                        <form action="{{route('loghours.update_balance', $employeeDetails->user_id)}}" method="POST">
                            @csrf
                            <input type="hidden" id="employee_id" name="employee_id" value="{{$employeeDetails->user_id}}">
                            <label for="balance">Revoke points</label>
                            <input type="text" id="balance" name="balance" placeholder="revoke_points">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('By revoking you approve the vacation')">Revoke</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
</body>

<script>
    function calculatePoints(){
        const balance = document.getElementById('calculate_balance').value;
        console.log(parseInt(balance));
        const result = document.getElementById('result_points');
        result.innerText = 'To spend: ' + balance * 0.2 + ' points'

        const revokeField = document.getElementById('balance');
        revokeField.value = balance * 0.2


    }
</script>
