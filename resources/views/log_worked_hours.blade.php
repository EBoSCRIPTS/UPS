<!DOCTYPE html>

<home>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
</home>

<body>
@include('components.sidebar')
<div class="row">
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <form action="" method="POST">
            @csrf
            <table>
                <thead>
                <tr>
                    <th>Date + Day</th>
                    <th>Start</th>
                    <th>Finish</th>
                    <th>Break</th>
                    <th>Worked today</th>
                </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>DATE</td>
                        <td>DAY</td>
                        <td>
                            <input type="time" id="start" name="start" min="00:00" max="24:00" onchange="calculateWorkedHours()" required/>
                        </td>
                        <td>
                            <input type="time" id="end" name="end" min="00:00" max="24:00" onchange="calculateWorkedHours()" required/>
                        </td>
                        <td>
                            <select id="break" name="break" onchange="calculateWorkedHours()">
                                <option value="30">30min</option>
                                <option value="60">1hr</option>
                                <option value="90">1hr30</option>
                                <option value="120">2hr</option>
                            </select>
                        </td>
                        <td>
                            <p id="result"></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

<script>
    function calculateWorkedHours() {
        let startTime = document.getElementById('start').valueAsDate;
        let endTime = document.getElementById('end').valueAsDate;
        let breakTime = parseFloat(document.getElementById('break').value) * 60 * 1000; // Convert break time to milliseconds

        if (!startTime || !endTime) {
            // Handle invalid input
            return;
        }

        let totalMilliseconds = endTime - startTime - breakTime;
        let totalSeconds = totalMilliseconds / 1000;

        let hours = Math.floor(totalSeconds / 3600);
        let minutes = Math.floor((totalSeconds % 3600) / 60);

        document.getElementById('result').innerText = "Worked Hours: " + hours + " hours " + minutes + " minutes";
    }
</script>

</body>
