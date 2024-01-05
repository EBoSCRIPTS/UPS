<script src="{{asset('js/userSearch.js')}}"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css");

    body{
        font-family: 'Roboto', sans-serif;
        overflow-x: hidden
    }
</style>

@if(isset(Auth::user()->id))
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark" id="sidebar" style="position: fixed">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <p class="h2">UPS</p>
                    </a>
                    <div class="searchbar">
                        <input id="searchBarInput" name="first_name" type="text" class="form-control" placeholder="Search for user" oninput="userSearchDebounced()">
                    </div>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start"
                        id="menu" style="color: white">
                        <li class="nav-item">
                            <a href="/" class="nav-link align-middle px-0 mt-2">
                                <i class="fs-4 bi-house" style="color: white"></i> <span class="ms-1" style="color: white">HOME</span>
                            </a>
                        </li>
                        <li>
                            @if(Auth::user()->role_id != 5)
                            <a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                <i class="fs-4 bi-speedometer2" style="color: white"></i> <span
                                    class="ms-1" style="color: white">DASHBOARD</span> </a>
                            <ul class="collapse nav flex-column ms-1" id="submenu1" data-bs-parent="#menu">
                                <li class="w-100">
                                    <a href="/absence" class="nav-link px-2"> <span class="" style="color: white">• Request Absence</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/loghours" class="nav-link px-2"> <span class="" style="color: white">• Log Hours</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/departments/my" class="nav-link px-2"> <span class="" style="color: white">• My department</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#submenu6" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                <i class="fs-4 bi-clipboard" style="color: white"></i> <span class="ms-1 " style="color: white">TASKS</span>
                            </a>
                            <ul class="collapse nav flex-column ms-1" id="submenu6" data-bs-parent="#menu">
                                <li>
                                    <a href="/tasks" class="nav-link px-2"> <span
                                            class="" style="color: white">• See Tasks</span> </a>
                                </li>
                                @if(isset(Auth::user()->role_id) && Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                                    <li>
                                        <a href="/tasks/create_new_project" class="nav-link px-2"> <span
                                                class="" style="color: white">• Create new project</span> </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(isset(Auth::user()->role_id) && Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                            <li>
                                <a href="#submenuReview" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                                    <i class="fs-4 bi-search"></i> <span class="ms-1 ">REVIEW REQUESTS</span></a>
                                <ul class="collapse nav flex-column ms-1" id="submenuReview" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a href="/absence/review" class="nav-link px-0"> <span
                                                class="">Absence Review
                                                @if(\App\Models\AbsenceModel::query()->where('status', 'sent')->count() > 0)
                                                <span class="badge bg-danger rounded-pill">
                                                    {{\App\Models\AbsenceModel::query()->where('status', 'sent')->count()}}
                                                </span>
                                                @endif
                                            </span></a>
                                    </li>
                                    <li>
                                        <a href="/loghours/review" class="nav-link px-0"> <span
                                                class="">Review Submitted Log Hours</span>
                                            @if(\App\Models\LoggedHoursSubmittedModel::query()->where('is_confirmed', '0')->count() > 0)
                                            <span class="badge bg-danger rounded-pill">
                                                {{\App\Models\LoggedHoursSubmittedModel::query()->where('is_confirmed', '0')->count()}}
                                            </span>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/loghours/view" class="nav-link px-0"> <span
                                                class="">View Logged Hours</span></a>
                                    </li>
                                </ul>
                            </li>


                            <li>
                                <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                                    <i class="fs-4 bi-list"></i> <span class="ms-1 ">MANAGER VIEWS</span></a>
                                <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">
                                    <li>
                                        <a href="/employee_information" class="nav-link px-0"> <span
                                                class="">Employee Information</span></a>
                                    </li>
                                    <li>
                                        <a href="/departments" class="nav-link px-0"> <span class="">Departments</span></a>
                                    </li>
                                    <li>
                                        <a href="/tasks/project_settings" class="nav-link px-0"> <span
                                                class="">Project Settings</span></a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->is_writer == 1)
                            <li>
                                <a href="#submenu7" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                                    <i class="fs-4 bi-newspaper"></i> <span class="ms-1 ">NEWS</span></a>
                                <ul class="collapse nav flex-column ms-1" id="submenu7" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a href="/news/create_topic" class="nav-link px-0"> <span
                                                class="">Create new topic </span></a>
                                    </li>
                                </ul>
                        @endif

                        @if(isset(Auth::user()->role_id) && Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                            <li>
                                <a href="#submenu5" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                                    <i class="fs-4 bi-bag"></i> <span class="ms-1 ">EQUIPMENT</span></a>
                                <ul class="collapse nav flex-column ms-1" id="submenu5" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a href="/equipment/register" class="nav-link px-0"> <span
                                                class="">Register new Equipment </span></a>
                                    </li>

                                    <li class="w-100">
                                        <a href="/equipment/equipment_assignment" class="nav-link px-0"> <span
                                                class="">Assign Equipment </span></a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="/send_mail" class="nav-link px-0 align-middle ">
                                    <i class="fs-4 bi-envelope"></i> SEND MAIL
                                </a>
                           </li>
                        @endif
                        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                            <li>
                                <a href="/accountant" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-calculator"></i> <span
                                        class="ms-1 ">ACCOUNTANT PANEL</span> </a>
                            </li>
                        @endif

                        @if(Auth::user()->role_id == 1)
                            <li>
                                <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-grid"></i> <span
                                        class="ms-1 ">USER CREATION</span> </a>
                                <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a href="/mng/register" class="nav-link px-0"> <span class="">Register New User</span></a>
                                    </li>
                                    <li>
                                        <a href="/mng/edit" class="nav-link px-0"> <span class="">Edit Users</span></a>
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
                            <span class=" mx-1">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span>
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

            <script>
                // Function to toggle the sidebar on smaller screens
                function toggleSidebar() {
                    const sidebar = document.getElementById('sidebar');
                    if(sidebar.style.display === 'none') {
                        sidebar.classList.toggle('collapsed');
                        sidebar.style.display = 'block';
                    }
                    sidebar.classList.toggle('collapsed');
                }

                document.addEventListener('DOMContentLoaded', function() {
                    if (window.innerWidth <= 576) {
                        document.getElementById('sidebar').style.display = 'none';
                    }
                });


                // Add event listener for the toggle button
                const toggleButton = document.createElement('button');
                toggleButton.innerHTML = '. . .';
                toggleButton.classList.add('btn', 'btn-dark', 'd-block', 'd-sm-none', 'position-fixed', 'top-0', 'start-0', 'm-3');
                toggleButton.addEventListener('click', toggleSidebar);
                toggleButton.style.zIndex = '101';
                toggleButton.style.position = 'fixed';
                document.body.appendChild(toggleButton);
            </script>

            <style>
                /* Add some custom styles for the collapsed sidebar */

                @media(max-width: 756px), (max-height: 756px) {
                    #sidebar {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 250px;
                        height: 100vh;
                        background-color: #f1f1f1;
                        z-index: 100;
                        transition: transform 0.3s ease-in-out;
                    }

                    #sidebar.open {
                        transform: translateX(0);
                    }

                    #content {
                        margin-left: 250px;
                        transition: margin 0.3s ease-in-out;
                    }

                    .sidebar.open + .content {
                        margin-left: 0;
                    }
                    #sidebar.collapsed {
                        width: 0;
                        overflow: hidden;
                    }

                    #sidebar.collapsed .nav-link span {
                        display: none;
                    }

                    #sidebar.collapsed .bi-house {
                        display: none;
                    }

                }
            </style>
