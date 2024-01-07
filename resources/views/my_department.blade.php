<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create a project</title>

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
            <div class="row">
                <div class="col-md-6">
                    <h3 class="text-center">{{$department->name}} department employees</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>
                                    <a href="/profile/{{$employee->user_id}}">{{$employee->user->first_name}} {{$employee->user->last_name}}</a>
                                </td>
                                <td>{{$employee->position}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-md-6">
                    <h3 class="text-center">Received tickets</h3>
                    @if(sizeof($tickets) == 0)
                        <p class="text-center">NO UNREGISTERED TICKETS</p>
                    @else
                        @foreach($tickets as $ticket)
                            <a class="" data-bs-toggle="collapse" href="#ticketInfo{{$ticket->id}}" role="button"
                               aria-expanded="false" aria-controls="collapseExample" style="text-decoration: none">
                                <div class="card d-flex" style="max-width: 100%">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="card-title text-center">{{$ticket->ticket_title}}</h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="card-title text-center">{{$ticket->user->first_name}} {{$ticket->user->last_name}}</h6>
                                            </div>
                                        </div>
                                        <div class="collapse" id="ticketInfo{{$ticket->id}}">
                                            <p>{{$ticket->ticket_text}}</p>
                                            <a href="/departments/my/ticket_register/{{$ticket->id}}"
                                               class="btn btn-primary float-end">Seen, registered</a>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                </div>
                @endif
            </div>
        </div>


    </div>
</div>
</body>
