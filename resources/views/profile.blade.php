<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{$user->first_name}} {{$user->last_name}}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
<div class="row">
    @include('components.sidebar')
        @if(isset(Auth::user()->email))
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-3">
            <div class="container mt-3" style="width: 80%">
                @include('components.errors')
                <div class="row justify-content-center">
                    <div class="col-sm-4 bg-light" style="padding-top: 50px; padding-bottom: 50px"">
                        <img src="{{ asset($user->profile_picture) }}" alt="Profile Picture" class="rounded-circle mb-3 d-block mx-auto" width="150" height="150">
                        @if(isset($user->employee->department->name))
                            <p class="text-center">{{$user->employee->department->name}}</p>
                        @endif
                        <form action="mailto:{{$user->email}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary mt-3 d-block mx-auto">Send email</button>
                        </form>
                    </div>

                    <div class="col-sm-4 bg-light" style="padding-top: 50px; padding-bottom: 50px">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0"><strong>Full name:</strong></p>
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

                <div class="row flex-nowrap justify-content-center">
                    <div class="col-sm-8">
                        <hr>
                        <p class="h3 text-center">Projects user belongs to</p>
                        @foreach($projects as $project)
                            <p class="h4">{{$project->projectName->name}}</p>
                        @endforeach
                        <hr>
                        @if(Auth::user()->id == $user->id)
                            <p class="h3 text-center">Actions</p>
                            <div class="btn-group d-flex justify-content-center">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#changePassword">Change password</button>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bankDetails">Bank details</button>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#employmentInformation" onclick="return confirmAccess()">Employment information</button>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal", data-bs-target="#equipmentInformation">My equipment</button>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#submitTicket">Submit ticket</button>

                                <div class="modal fade" id="changePassword" tabindex="-1" aria-labelledby="changePassword" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-center" id="changePassword">Change password</h5>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('user.change_password', Auth::user()->id)}}" method="POST" id="changePasswordForm">
                                                    @csrf
                                                    <label for="old_password" class="form-label">Old Password</label>
                                                    <input type="password" id="old_password" name="old_password" class="form-control" required>

                                                    <label for="new_password" class="form-label">New Password</label>
                                                    <input type="password" id="new_password" name="new_password" class="form-control" required>

                                                    <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                                                    <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required>

                                                    <button type="button" class="btn btn-danger mt-3 float-end" onclick="checkNewPasswords()">Save changes</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="bankDetails" tabindex="-1" aria-labelledby="bankDetails" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-center" id="bankDetails">Bank Details</h5>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('user.update_banking', Auth::user()->id)}}" method="POST" id="bankDetailsForm">
                                                    @csrf
                                                    <label for="bank_name" class="form-label">Bank Name</label>
                                                    <input type="text" id="bank_name" name="bank_name" class="form-control" required placeholder="{{$employeeInformation->bank_name ?? 'No bank details'}}">

                                                    <label for="account_name" class="form-label">Account Name</label>
                                                    <input type="text" id="account_name" name="account_name" class="form-control" required placeholder="{{$employeeInformation->bank_account_name ?? 'No bank details'}}">

                                                    <label for="account_number" class="form-label">Account Number</label>
                                                    <input type="text" id="account_number" name="account_number" class="form-control" required placeholder="{{$employeeInformation->bank_account ?? 'No bank details'}}">

                                                    <button type="submit" class="btn btn-success mt-3 float-end">Save changes</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal fade" id="employmentInformation" tabindex="-1" aria-labelledby="employmentInformation" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-center" id="employmentInformation">Employment information</h5>
                                            </div>
                                            <div class="modal-body">
                                                <p class="h5">Department: {{$employeeInformation->department->name ?? 'Not registered yet'}}</p>
                                                <hr>
                                                <p class="h5">Job title: {{$employeeInformation->position ?? 'Not registered yet'}}</p>
                                                <hr>
                                                <p class="h5">Weekly Hours: {{$employeeInformation->weekly_hours ?? 'Not registered yet'}}</p>
                                                <hr>
                                                @if(isset($employeeInformation->hour_pay) && $employeeInformation->hour_pay != null)
                                                    <p class="h5">Hourly rate: ${{$employeeInformation->hour_pay ?? 'Not registered yet'}}</p>
                                                @else
                                                    <p class="h5">Salary: {{$employeeInformation->salary ?? 'Not registered yet'}}</p>
                                                @endif
                                                <hr>
                                                <p class="h5">VP balance (1 day = 0.2VP): {{$vp ?? 'Not registered yet'}}</p>
                                                <hr>
                                                @if($performanceReport != null)
                                                    <p class="h5 text-center">Performance report</p>
                                                    <p><strong>Current rating score: {{$performanceReport->rating ?? 'No performance ratings yet'}}%</strong></p>
                                                    <p><strong>Rating description: {{$performanceReport->rating_text ?? 'No performance ratings yet'}}</strong></p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="submitTicket" tabindex="-1" aria-labelledby="submitTicket" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-center" id="submitTicket">Employment information</h5>
                                            </div>
                                            <div class="modal-body">
                                               <form action="{{route('user.send_ticket', Auth::user()->id)}}" method="POST">
                                                    @csrf
                                                   <label for="department_id" class="form-label">Send to department:</label>
                                                   <select id="department_id" name="department_id" class="form-select">
                                                       <option disabled selected>Pick a department</option>
                                                       @foreach($departments as $department)
                                                           <option value="{{$department->id}}">{{$department->name}}</option>
                                                       @endforeach
                                                   </select>

                                                   <label for="title" class="form-label">Title</label>
                                                   <input type="text" id="title" name="title" class="form-control" required>

                                                   <label for="description" class="form-label">Describe the issue</label>
                                                   <textarea name="description" class="form-control" required></textarea>

                                                   <button type="submit" class="btn btn-success mt-3 float-end" onclick="return confirm('Make sure all the info you input is correct!')">Submit a ticket</button>
                                               </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal modal-lg fade" id="equipmentInformation" tabindex="-1" aria-labelledby="equipmentInformation" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-center" id="equipmentInformation">Equipment Information</h5>
                                            </div>
                                            <div class="modal-body">
                                                @foreach($employeeEquipment as $equipment)
                                                    <p><strong>*) {{$equipment->equipment->name}} | ISSUED ON: {{$equipment->date_given}}</strong></p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
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

<script>
    function checkNewPasswords()
    {
        if(document.getElementById("new_password").value != document.getElementById("confirm_new_password").value)
        {
            alert("Passwords don't match!");
        }
        else
        {
            document.getElementById("changePasswordForm").submit();
        }
    }

    function confirmAccess() {
        if(confirm('You are about to access sensitive information, continue?') === false){
            window.location.reload();
        }
    }
</script>
