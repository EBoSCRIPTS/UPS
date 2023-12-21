<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project Statistics</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>


<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        <div class="container" style="width: 80%">
        <p class="h2 text-center">Project statistics</p>
        <hr class="hr"/>
        <p class="h5 text-center">Statistics for the month of {{$month}}</p>
        <div class="d-flex flex-wrap justify-content-center">
            <div class="p-2">
                <p class="h5">Tasks statistics</p>
                <canvas id="doughnutChart" style="width: 100%; margin-right: 100px"></canvas>
            </div>
            <div class="d-flex">
                <div class="vr"></div>
            </div>

            <div class="p-2">
                <p class="h5">Points statistics</p>
                <canvas id="barChart" style="width: 100%; height: 100%; margin-left: 100px"></canvas>
            </div>
        </div>
            <hr class="hr"/>
            <p class="h3 text-center">Summary</p>
            <p class="lead"> The team has registered {{$createdTasksCount}} tasks in the month of {{$month}}, out of which {{$completedThisMonth}} have been completed</p>
            <p class="lead"> Total task points {{$allTasksPoints}}, completed task points {{$completedTaskPoints}}</p>

            <small>Drafted tasks don't count towards the statistics</small>

            <hr>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="h4">Generate statistics for a different time period</p>
                <form action="{{route('tasks.projects.statistics.generate_for_period',  $project_id)}}" method="POST">
                        @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="startDate">Start Date:</label>
                            <input style="width: 200px" type="date" class="form-control" name="startDate" required>
                        </div>
                        <div class="col-sm-4">
                            <label for="startDate">End Date:</label>
                            <input style="width: 200px" type="date" class="form-control" name="endDate" required>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="project_id" value="{{$project_id}}">

                            <button type="submit" class="btn btn-primary mt-4">Generate for period</button>
                        </div>
                    </div>
                </form>
                </div>
                <div class="col-md-6">
                    <p class="h4">Generate excel</p>
                    <form action="{{route('tasks.project.generate_xlsx',  $project_id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="startDate">Start Date:</label>
                                <input type="date" class="form-control" id="startDate" name="startDate" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="endDate">End Date:</label>
                                <input type="date" class="form-control" id="endDate" name="endDate" required>
                            </div>
                            <div class="col-sm-4">
                                <input type="hidden" name="project_id" value="{{$project_id}}">
                                <button type="submit" class="btn btn-success mt-4">Generate XLSX</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
</body>

<script>
    let tasksDoughnutValues = [{{$createdTasksCount}}, {{$completedThisMonth}}];
    let tasksDoughnutLabels = ['All tasks', 'Completed tasks'];
    let colorsDoughnut = ['#FF0000', '#0000FF'];

    const doughnutChart = new Chart('doughnutChart', {
        type: 'doughnut',
        data: {
            labels: tasksDoughnutLabels,
            datasets: [{
                backgroundColor: colorsDoughnut,
                data: tasksDoughnutValues,
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Tasks statistics'
            }
        }
    });

    let tasksBarValues = [{{$allTasksPoints}}, {{$completedTaskPoints}}, {{$allTasksPoints - $completedTaskPoints}}];
    let tasksBarLabels = ['All TP', 'Completed TP', 'Remaining TP'];
    let colorsBar = ['#FF0000', '#0000FF', '#008000'];

    const barChart = new Chart('barChart', {
        type: 'bar',
        data: {
            labels: tasksBarLabels,
            datasets: [{
                backgroundColor: colorsBar,
                data: tasksBarValues,
                barPercentage: 0.5,
            }]
        },
        options: {
            legend: {display: false},
            scales: {
                y: { minBarLength: 0 }
            }
        }
    });

</script>
