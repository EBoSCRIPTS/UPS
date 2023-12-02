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
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <form action="{{ route('absence.create') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
            <label for="reason" class="form-label">Reason</label>
            <select name="reason" id="reason" class="form-control">
                <option value="Disabled" disabled selected>SELECT HERE</option>
                <option value="Sick">Sick</option>
                <option value="Vacation">Vacation</option>
                <option value="Personal">Personal</option>
                <option value="Other">Other</option>
            </select>

            <label for="comment" class="form-label">Comment</label>
            <textarea name="comment" id="comment" cols="30" rows="10" class="form-control"></textarea>

            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control">

            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control">

                <label for="Attachment" class="form-label">Attachment</label>
                <input type="file" name="attachment" id="attachment" class="form-control">

            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <p class="h2 text-center">My Requests</p>
        @foreach($showSent as $absence)
            <div class="card mt-3">
                <div class="card-box">
                    <h5 class="card-header">Created at: {{ $absence->created_at }} | Status: {{ $absence->status }} </h5>
                    <p>Type: {{$absence->type}}</p>
                    <p>Comment: {{ $absence->reason }}</p>
                    <p>Duration: {{ $absence->duration }} days </p>
                </div>
                <form action="{{ route('absence.delete', $absence->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{ $absence->id }}">
                <input type="submit" class="btn btn-danger" value="DELETE">
                </form>
            </div>
        @endforeach

        <hr class="hr" />
        <p class="h2 text-center">Reviewed Requests</p>
        @foreach($showReviewed as $absence)
            <div class="card mt-3">
                <div class="card-box">
                    <h5 class="card-header">Created at: {{ $absence->created_at }} | Status: {{ $absence->status }} </h5>
                    <p>Type: {{$absence->type}}</p>
                    <p>Comment: {{ $absence->reason }}</p>
                    <p>Duration: {{ $absence->duration }} days </p>
                </div>
            </div>
        @endforeach
</div>

</div>

</body>
