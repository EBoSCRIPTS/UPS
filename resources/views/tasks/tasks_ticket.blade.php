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
        <form action="{{route('tasks.update_description')}} " method="POST">
            @csrf
            <p class="h5">Task description</p>
            <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
            <textarea id="ticket_description" name="ticket_description" style="width: 18rem; height:8rem"></textarea>
            <br>
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </form>
        <hr class="hr"/>

        <p class="h3">Comment section</p>
        @foreach($comments as $comment)
            <div class="card">
                <div class="card-body">
                    <p class="h5 card-title">{{$comment->author->first_name}} {{$comment->author->last_name}}
                    <small>{{$comment->created_at}}</small>
                    </p>
                    <p class="card-text">{{$comment->comment_text}}</p>
                </div>
            </div>
            @endforeach

        <form action="{{route('tasks.add_comment')}}" method="POST">
            @csrf
            <input type="hidden" name="task_id" value="{{$ticket->id}}">
            <input type="hidden" name="comment_author" value="{{Auth::user()->id}}">
            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                <button type="submit" class="btn btn-primary btn-sm mt-2">Submit</button>
            </div>

        </form>
    </div>

    <div class="col-md-9 ms-sm-auto col-lg-4 px-md-4 mt-3">
        <p class="h2">Details</p>
        <hr class="hr"/>
        <div class="row">
            @if($ticket->is_completed == '0')
            <form action="{{route('tasks.update_status')}}" method="POST">
                @csrf
            <p class="h5">Status: {{$currentStatus}}
                <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                <button type="submit" class="btn btn-info btn-sm" name="back" value="back">←</button>
                <button type="submit" class="btn btn-info btn-sm" name="next" value="next">→</button>
            </p>
            </form>
        </div>
        @if($ticket->priority === 'low')
        <p class="h5" style="color: green">Priority: {{$ticket->priority}}</p>
        @elseif($ticket->priority === 'medium')
            <p class="h5" style="color: blue">Priority: {{$ticket->priority}}</p>
        @elseif ($ticket->priority === 'high')
            <p class="h5" style="color: orange">Priority: {{$ticket->priority}}</p>
        @elseif ($ticket->priority === 'critical')
            <p class="h5" style="color: red">Priority: {{$ticket->priority}}</p>
        @endif
        <hr class="hr"/>
        @endif
        <p class="h5">Assigned to:
            <form action="{{ route('tasks.update_assignee', ['ticket_id' => $ticket->id]) }}" method="POST" name="assignee_change">
            @csrf
            <select class="form-select" style="width: 50%" id="selectField" name="assignee_select" onchange="assignee_change.submit()">
                @if(isset($ticket->userTo->first_name))
                <option selected>{{$ticket->userTo->first_name}} {{$ticket->userTo->last_name}}</option>
                @else
                    <option selected disabled>Unassigned</option>
                @endif
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                @endforeach
            </select>
            </form>
        </p>
        <p class="h5">Created by: {{$ticket->userMade->first_name}} {{$ticket->userMade->last_name}}</p>

        <small>Created at: {{$ticket->created_at}}</small>
        <br>
        <small>Updated at: {{$ticket->updated_at}}</small>

        <hr class="hr"/>
        <div class="row">
            <div class="col-md-6">
                <form action="{{route('tasks.delete_ticket')}}" method="POST">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this ticket?')">Delete Ticket</button>
                </form>
            </div>

            <div class="col-md-6">
                @if($ticket->is_completed == '0')
                <form action="{{route('tasks.complete_ticket')}}" method="POST">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                    <button type="submit" class="btn btn-success btn-sm float-end" onclick="return confirm('Are you sure you want to complete this ticket?')">Complete Ticket</button>
                </form>
                @else
                <form action="{{route('tasks.complete_ticket')}}" method="POST">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                    <button type="submit" class="btn btn-warning btn-sm float-end" onclick="return confirm('Are you sure you want to return this ticket?')">Return Ticket</button>
                </form>
                @endif
            </div>
        </div>
    </div>

</div>
</body>

<script>
    function populateTextArea()
    {
        document.getElementById('ticket_description').value = '{{$ticket->description}}';
    }

    window.onload = function()
    {
        populateTextArea();
    }

</script>
