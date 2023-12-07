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

        @if(isset(Auth::user()->email))
            <div class="col-lg-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <img src="{{ asset($user->profile_picture) }}" alt="Profile Picture" class="rounded-circle mb-3" width="150" height="150">
                        @if(isset($user->employee->department->name))
                        <p class="text-muted">{{$user->employee->department->name}}</p>
                        @endif
                        <form action="mailto:{{$user->email}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary mt-3">Send email</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Full name:</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{$user->first_name}} {{$user->last_name}}</p>
                            </div>
                        </div>
                        <hr class="hr"/>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email:</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{$user->email}}</p>
                            </div>
                        </div>
                        <hr class="hr">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Phone number:</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{$user->phone_number}}</p>
                            </div>
                        </div>
                        <hr class="hr"/>

                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Position:</p>
                            </div>
                            <div class="col-sm-9">
                                @if(isset($user->employee->position))
                                <p class="text-muted mb-0">{{$user->employee->position}}</p>
                                @else
                                <p class="text-muted mb-0">No position</p>
                                @endif

                            </div>
                        </div>
                </div>
            </div>
            @else
            {{redirect('/login')}}
        @endif
</div>

</body>
