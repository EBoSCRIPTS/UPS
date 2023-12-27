<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{$ticket->title}}</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
        @include('components.tasks_navbar')
    @include('components.errors')
    <div class="row" style="margin: 50px;">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-md-8">
                    <p id="ticket_title" class="h2">{{$ticket->title}}</p>
                </div>
                <div class="col-md-4">
                    <button type="button" id="edit_title" class="btn float-end" onclick="editTitle()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                        </svg>
                    </button>
                </div>
            </div>
        <hr class="hr"/>
        <form action="{{route('tasks.update_description')}} " method="POST">
            @csrf
            <p class="h5">Task description</p>
            <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
            <textarea class="form-control bg-light" id="ticket_description" name="ticket_description" style="height: 14rem"></textarea>
            <br>
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </form>
        <hr class="hr"/>

        <p class="h3">Comment section</p>
        @foreach($comments as $comment)
            <div class="card bg-light mt-3">
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
                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                <button type="submit" class="btn btn-primary btn-sm mt-2">Submit</button>
            </div>

        </form>
    </div>

        <div class="col-lg-6">
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
            <div class="row">
                <form action="{{route('tasks.update_priority')}}" method="POST">
                    @csrf
                <p class="h5">Task priority: {{$ticket->priority}}
                    <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                    <button type="submit" class="btn btn-success btn-sm" name="back" value="back">←</button>
                    <button type="submit" class="btn btn-success btn-sm" name="next" value="next">→</button>
                </p>
                </form>
            </div>

        <p class="h5">Task value: {{$ticket->task_points}} (TP)</p>

            <div class="row">
                <div class="col-sm-1">
        <label for="draft">Draft</label>
                </div>
        <div class="col-sm-1">
        <form action="{{route('tasks.update_draft')}}" method="POST">
            @csrf
            <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
        @if($ticket->is_draft == '1')
            <input type="checkbox" id="draft" name="draft" value="draft" checked onclick="this.form.submit()">
        @else
            <input type="checkbox" id="draft" name="draft" value="draft" onclick="this.form.submit()">
        @endif
        </form>
        </div>
            </div>

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
</div>
</div>
</body>

<script>
    function populateTextArea()
    {
        document.getElementById('ticket_description').value = {!! json_encode($ticket->description) !!};
    }

    function editTitle()
    {
        document.getElementById('ticket_title').contentEditable = true;

        const ticketTitle = document.getElementById('ticket_title');
        const editTitleButton = document.getElementById('edit_title');
        const acceptEdit = document.createElement('button');

        acceptEdit.setAttribute('type', 'submit');
        acceptEdit.setAttribute('class', 'btn btn-success btn-sm');
        acceptEdit.setAttribute('onclick', 'acceptEdit()');
        acceptEdit.innerHTML = '✅';
        editTitleButton.innerText = '';

        editTitleButton.append(acceptEdit);
    }

    function acceptEdit()
    {
        document.getElementById('ticket_title').contentEditable = false;
        const formCreate = document.createElement('form');
        const editTitleButton = document.getElementById('edit_title');

        formCreate.append(editTitleButton)
        formCreate.setAttribute('method', 'POST');
        formCreate.setAttribute('action', "{{route('tasks.update_title', ['ticket_id' => $ticket->id])}}");

        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', '_token');
        hiddenInput.setAttribute('value', '{{ csrf_token() }}');

        const titleValue = document.createElement('input');
        titleValue.setAttribute('type', 'text');
        titleValue.setAttribute('name', 'title');
        titleValue.setAttribute('value', document.getElementById('ticket_title').innerText);
        formCreate.append(hiddenInput);
        formCreate.append(titleValue);

        document.body.append(formCreate);
        formCreate.submit();
    }

    window.onload = function()
    {
        populateTextArea();
    }

</script>
