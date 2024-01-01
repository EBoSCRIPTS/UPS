<!DOCTYPE html>

<home>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Log Worked Hours</title>

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
        <div class="container">
        <p class="h-4"> Month of {{ $month }}
        @if(!isset($hide))
        <form action="{{route('loghours.previous_month')}}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-info">Go back to previous month</button>
        </form>
        @endif
        </p>
        @if(!isset($closed))
        <form action="{{ route('loghours.create') }}" method="POST">
            @csrf
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Date 'n' Day</th>
                    <th scope="col">Start</th>
                    <th scope="col">Finish</th>
                    <th scope="col">Break</th>
                    <th scope="col">Worked today</th>
                </tr>
                </thead>

                <tbody>
                @foreach($dates as $date)
                    <tr>
                        <input type="hidden" id="date{{$date}}" name="{{$date}}_date" value="{{$date}}"/>
                        <td>{{$date}} {{ Carbon\Carbon::parse($date)->locale('en')->dayName }}</td>
                        <td>
                            <input class="form-control" type="time" id="start_time{{$date}}" name="{{$date}}_start_time" min="00:00" max="24:00" onchange="calculateWorkedHours('{{$date}}')"/>
                        </td>
                        <td>
                            <input class="form-control" type="time" id="end_time{{$date}}" name="{{$date}}_end_time" min="00:00" max="24:00" onchange="calculateWorkedHours('{{$date}}')"/>
                        </td>
                        <td>
                            <select class="form-select" id="break_time{{$date}}" name="{{$date}}_break_time" onchange="calculateWorkedHours('{{$date}}')">
                                <option value="0">No break</option>
                                <option value="30">30min</option>
                                <option value="60">1hr</option>
                                <option value="90">1hr30</option>
                                <option value="120">2hr</option>
                            </select>
                        </td>
                        <td>
                            <p id="result{{$date}}"></p>
                            <input type="hidden" id="total_hours{{$date}}" name="{{$date}}_total_hours"/>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="month" value="{{ $month }}"/>
            </table>
            <input type="submit" class="btn btn-primary" value="Submit" onclick="return confirm('Are you sure?')"/>
        </form>
        <hr>
        <form action="{{route('loghours.close_month')}}" method="POST">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}"/>
            <button type="submit" class="btn btn-primary">Close monthly report and submit</button>
        </form>

        <hr class="hr"/>
            @else
            <p class="h5">You have closed this month.</p>
            @endif
        <p class="h3">My logs this month</p>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Start</th>
                    <th scope="col">Finish</th>
                    <th scope="col">Hours</th>
                    @if(!isset($closed))
                    <th scope="col">Delete</th>
                    @endif
                </tr>

                <tbody>
                @foreach($userLogs as $userLog)
                    <form action="{{route('loghours.view.delete')}}" method="POST">
                        @csrf
                        <tr>
                            <td>{{$userLog->date}}</td>
                            <td>{{$userLog->start_time}}</td>
                            <td>{{$userLog->end_time}}</td>
                            <td>{{$userLog->total_hours}}</td>
                            @if(!isset($closed))
                            <td>
                                <button class="btn btn-danger">Delete</button>
                                <input type="hidden" name="id" value="{{$userLog->id}}"/>
                            </td>
                            @endif
                        </tr>
                    </form>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function calculateWorkedHours(date) {
        let startTime = document.getElementById('start_time' + date).valueAsDate;
        let endTime = document.getElementById('end_time' + date).valueAsDate;
        let breakTime = parseFloat(document.getElementById('break_time' + date).value) * 60 * 1000; // Convert break time to milliseconds

        if (!startTime || !endTime) {
            return;
        }

        let totalMilliseconds = endTime - startTime - breakTime;

        //if employee is on a nightshift
        if (totalMilliseconds < 0) {
            totalMilliseconds = (24 * 60 * 60 * 1000) - (startTime - endTime) - breakTime;
        }

        let totalSeconds = totalMilliseconds / 1000;

        let hours = Math.floor(totalSeconds / 3600);
        let minutes = Math.floor((totalSeconds % 3600) / 60);

        if(minutes < 10){
            minutes = "0" + minutes;
        }

        document.getElementById('result' + date).innerText = "Worked Hours: " + hours + " hours " + minutes + " minutes";
        document.getElementById('total_hours' + date).value = hours + ":" + minutes;
    }


    function validateTime(date)
    {
        let startTime = document.getElementById('start_time' + date).valueAsDate;
        let endTime = document.getElementById('end_time' + date).valueAsDate;

        if(startTime > endTime){
            alert("Start time cannot be greater than end time");
            document.getElementById('end_time' + date).valueAsDate = "";
        }
    }
</script>

</body>
