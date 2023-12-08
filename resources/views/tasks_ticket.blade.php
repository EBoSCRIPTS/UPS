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
    <div class="col-md-9 ms-sm-auto col-lg-4 px-md-4 mt-3">
        <p class="h2">{{$ticket->title}}</p>
        <hr class="hr"/>
        <p class="h5">Task description</p>
        <p>{{$ticket->description}}</p>
        <hr class="hr"/>
    </div>
    <div class="col-md-9 ms-sm-auto col-lg-4 px-md-4 mt-3">
        <p class="h2">Details</p>
        <hr class="hr"/>
        <p class="h5">Status: {{$ticket->ticketStatus->status_name}}</p>
        @if($ticket->priority === 'low')
        <p class="h5" style="color: green">Priority: {{$ticket->priority}}</p>
        @elseif($ticket->priority === 'medium')
            <p class="h5" style="color: yellow">Priority: {{$ticket->priority}}</p>
        @elseif ($ticket->priority === 'high')
            <p class="h5" style="color: orange">Priority: {{$ticket->priority}}</p>
        @elseif ($ticket->priority === 'critical')
            <p class="h5" style="color: red">Priority: {{$ticket->priority}}</p>
        @endif
        <hr class="hr"/>
        <p class="h5">Assigned to: {{$ticket->userTo->first_name}} {{$ticket->userTo->last_name}}</p>
        <p class="h5">Created by: {{$ticket->userMade->first_name}} {{$ticket->userMade->last_name}}</p>

        <small>Created at: {{$ticket->created_at}}</small>
        <br>
        <small>Updated at: {{$ticket->updated_at}}</small>
    </div>

</div>
</body>
