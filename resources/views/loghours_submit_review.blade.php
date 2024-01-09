<!DOCTYPE html>

<home>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Hour Submission Review</title>

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
        <div class="container align-items-center" style="width: 80%">
            @include('components.errors')
            <div class="row">
                <div class="col-lg-8">
                    <p class="h3 text-center">Review Hours</p>

                    @if(count($submits) > 0)
                        <table class="table table-striped" style="max-width: 800px">
                            <thead>
                            <tr>
                                <th scope="col">Employee name</th>
                                <th scope="col">Total Hours</th>
                                <th scope="col">Night Hours</th>
                                <th scope="col">Month Name</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($submits as $submit)
                                <tr>
                                    <td><a href="/loghours/view/{{$submit->user->id}}"
                                           onclick="window.open(this.href, 'window', 'width=800,height=600'); return false;">{{$submit->user->first_name}} {{$submit->user->last_name}}</a>
                                    </td>
                                    <td>{{$submit->total_hours}}</td>
                                    <td>{{$submit->night_hours}}</td>
                                    <td>{{$submit->month_name}}</td>
                                    <div class="btn-group">
                                        <td>
                                            <form action="{{route('loghours.review')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$submit->id}}">
                                                <label for="overtimeHours" class="form-label">Overtime hours</label>
                                                <input type="text" class="form-control mb-2" name="overtime_hours">
                                                <button type="submit" class="btn btn-success" name="Approve"
                                                        value="confirm">Approve
                                                </button>
                                                <button type="submit" class="btn btn-danger" name="Reject"
                                                        value="reject">Reject
                                                </button>
                                            </form>
                                        </td>

                                    </div>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Nothing to review</p>
                    @endif
                </div>
                <div class="col-lg-4">
                    <p class="h3 text-center">Reviewed Hours</p>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Employee name</th>
                            <th scope="col">Total Hours</th>
                            <th scope="col">Night Hours</th>
                            <th scope="col">Month Name</th>
                            <th scope="col">Confirmed on</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($confirmedHours as $ch)
                            <tr>
                                <td>{{$ch->user->first_name}} {{$ch->user->last_name}}</td>
                                <td>{{$ch->total_hours}}</td>
                                <td>{{$ch->night_hours}}</td>
                                <td>{{$ch->month_name}}</td>
                                <td>{{$ch->created_at}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
