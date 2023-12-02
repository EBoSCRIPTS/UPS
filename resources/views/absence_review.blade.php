<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>


<body>
<div class="row">
@include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        @foreach($absences as $absence)
            <form action="{{route('absence.update')}}" method="POST">
                @csrf
                <div class="card mt-3">
                    <div class="card-box">
                        <h5 class="card-header">Created at: {{ $absence->created_at }} | Status: {{ $absence->status }} </h5>
                        <div class="card-body">
                        <input type="hidden" name="id" value="{{ $absence->id }}">
                        <p>Requested by: {{ $absence->user->first_name }} {{ $absence->user->last_name }}  {{ $absence->user->email }} {{ $absence->user->phone_number }}</p>
                        <p>Type: {{$absence->type}}</p>
                        <p>Comment: {{ $absence->reason }}</p>
                        <input type="submit" class="btn btn-danger" name="status" value="DENY">
                        <input type="submit" class="btn btn-success" name="status" value="APPROVE">
                        <input type="hidden" name="approver_id" value="{{ Auth::user()->id }}">
                        </div>
                    </div>
                </div>
            </form>
        @endforeach
            <hr class="hr" />
            <p class="h2">Reviewed Requests</p>
            @foreach($reviewedAbsences as $reviewedAbsence)
                <div class="card mt-3">
                    <div class="card-box">
                        @if($reviewedAbsence->status == 'APPROVE')
                        <h5 class="card-header" style="color: green">Created at: {{ $reviewedAbsence->created_at }} |
                            Status: {{ $reviewedAbsence->status }}
                        | Approved by: {{ $reviewedAbsence->approver->first_name }} {{ $reviewedAbsence->approver->last_name }}
                        </h5>
                            @else
                            <h5 class="card-header" style="color: red">Created at: {{ $reviewedAbsence->created_at }} |
                                Status: {{ $reviewedAbsence->status }}
                                | Approved by: {{ $reviewedAbsence->approver->first_name }} {{ $reviewedAbsence->approver->last_name }}
                            </h5>
                        @endif
                        <div class="card-body">
                        <input type="hidden" name="id" value="{{ $reviewedAbsence->id }}">
                        <p>Requested by: {{ $reviewedAbsence->user->first_name }} {{ $reviewedAbsence->user->last_name }}  {{ $reviewedAbsence->user->email }} {{ $reviewedAbsence->user->phone_number }}</p>
                        <p>Type: {{$reviewedAbsence->type}}</p>
                        <p>Comment: {{ $reviewedAbsence->reason }}</p>
                        <input type="hidden" name="approver_id" value="{{ Auth::user()->id }}">
                        </div>
                    </div>
                </div>
            @endforeach
    </div>



</div>
</body>
