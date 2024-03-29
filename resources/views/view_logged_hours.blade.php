<!Doctype HTML>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Logged Hours Logs</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
            <p class="h2 text-center">Logged Hours</p>
            <div class="row">
                <div class="col-md-4">
                    <select name="user_id" class="form-select" id="userHours">
                        @if(isset($loggedHours) && sizeof($loggedHours) > 0)
                            <option value="{{$loggedHours}}"
                                    selected>{{$loggedHours[0]->user->first_name}} {{$loggedHours[0]->user->last_name}}</option>
                        @endif
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{($user->first_name)}} {{$user->last_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-primary" id="checkHours">Check hours</button>
                </div>
            </div>
            </form>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">User</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">Night Hours</th>
                    <th scope="col">Total Hours</th>
                </tr>
                </thead>
                <tbody>
                @foreach($loggedHours as $loggedHour)
                    <tr>
                        @if(\Carbon\Carbon::parse($loggedHour->date)->isWeekend())
                            <td class="table-danger">{{$loggedHour->date}}</td>
                        @else
                            <td>{{$loggedHour->date}}</td>
                        @endif

                        <td>{{$loggedHour->user->first_name}} {{$loggedHour->user->last_name}}</td>
                        <td>{{$loggedHour->start_time}}</td>
                        <td>{{$loggedHour->end_time}}</td>
                        <td>{{$loggedHour->night_hours}}</td>
                        <td>{{$loggedHour->total_hours}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>

<script>
    document.getElementById('checkHours').addEventListener('click', function () {
        const selectedEmployeeId = document.getElementById('userHours').value;

        if (selectedEmployeeId !== '---') {
            window.location.href = '/loghours/view/' + selectedEmployeeId;
        } else {
            alert('Please select an employee.');
        }
    });
</script>
