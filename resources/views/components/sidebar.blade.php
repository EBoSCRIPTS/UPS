@include('components.scripts_user_search')
@if(isset(Auth::user()->id))
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="/"
                       class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline">UPS</span>
                    </a>
                    <div class="searchbar">
                        <input id="searchBarInput" name="first_name" type="text" class="form-control"
                               placeholder="Search for user" oninput="userSearchDebounced()">
                    </div>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start"
                        id="menu">
                        <li class="nav-item">
                            <a href="/" class="nav-link align-middle px-0">
                                <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                <i class="fs-4 bi-speedometer2"></i> <span
                                        class="ms-1 d-none d-sm-inline">DASHBOARD</span> </a>
                            <ul class="collapse show nav flex-column ms-1" id="submenu1" data-bs-parent="#menu">
                                <li class="w-100">
                                    <a href="/absence" class="nav-link px-0"> <span class="d-none d-sm-inline">Request Absence</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/loghours" class="nav-link px-0"> <span class="d-none d-sm-inline">Log Hours</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#submenu5" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                <i class="fs-4 bi-speedometer2"></i> <span class="ms-1 d-none d-sm-inline">TASKS</span>
                            </a>
                            <ul class="collapse show nav flex-column ms-1" id="submenu5" data-bs-parent="#menu">
                                <li>
                                    <a href="/tasks" class="nav-link px-0"> <span
                                                class="d-none d-sm-inline">See Tasks</span> </a>
                                </li>
                                <li>
                                    <a href="/tasks/create_new_project" class="nav-link px-0"> <span
                                                class="d-none d-sm-inline">Create</span> </a>
                                </li>
                            </ul>
                        </li>
                        @if(isset(Auth::user()->role_id) && Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                            <li>
                                <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                                    <i class="fs-4 bi-bootstrap"></i> <span class="ms-1 d-none d-sm-inline">MANAGER VIEWS</span></a>
                                <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a href="/absence/review" class="nav-link px-0"> <span
                                                    class="d-none d-sm-inline">Absence Review </span></a>
                                    </li>
                                    <li>
                                        <a href="/loghours/view" class="nav-link px-0"> <span
                                                    class="d-none d-sm-inline">View Logged Hours</span></a>
                                    </li>
                                    <li>
                                        <a href="/departments" class="nav-link px-0"> <span class="d-none d-sm-inline">Departments</span></a>
                                    </li>
                                    <li>
                                        <a href="/employee_information" class="nav-link px-0"> <span
                                                    class="d-none d-sm-inline">Employee Information</span></a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                            <li>
                                <a href="#submenu4" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-grid"></i> <span
                                            class="ms-1 d-none d-sm-inline">ACCOUNTANT VIEWS</span> </a>
                                <ul class="collapse nav flex-column ms-1" id="submenu4" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a href="/accountant" class="nav-link px-0"> <span class="d-none d-sm-inline">Accountant Panel</span></a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->role_id == 1)
                            <li>
                                <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-grid"></i> <span
                                            class="ms-1 d-none d-sm-inline">ADMIN VIEWS</span> </a>
                                <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a href="/mng/register" class="nav-link px-0"> <span class="d-none d-sm-inline">Register New User</span></a>
                                    </li>
                                    <li>
                                        <a href="/mng/edit" class="nav-link px-0"> <span class="d-none d-sm-inline">Edit Users</span></a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                    <hr>
                    <div class="dropdown pb-4">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                           id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset(Auth::user()->profile_picture) }}" alt="Profile Picture"
                                 class="rounded-circle" width="30" height="30">
                            <span class="d-none d-sm-inline mx-1">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="/profile/{{Auth::user()->id}}">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="/logout">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
@else
    {{redirect('/login')}}
@endif
