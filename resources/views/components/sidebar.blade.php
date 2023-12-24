<script src="{{asset('js/userSearch.js')}}"></script>
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
                        id="menu">
                        <li class="nav-item">
                            <a href="/" class="nav-link align-middle px-0">
                                <i class="fs-4 bi-house"></i> <span class="ms-1" style="color: white">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                <i class="fs-4 bi-speedometer2"></i> <span
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
                                <i class="fs-4 bi-speedometer2"></i> <span class="ms-1 " style="color: white">TASKS</span>
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
                        @if(isset(Auth::user()->role_id) && Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                            <li>
                                <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                                    <i class="fs-4 bi-bootstrap"></i> <span class="ms-1 ">MANAGER VIEWS</span></a>
                                <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a href="/absence/review" class="nav-link px-0"> <span
                                                class="">Absence Review </span></a>
                                    </li>
                                    <li>
                                        <a href="/loghours/view" class="nav-link px-0"> <span
                                                class="">View Logged Hours</span></a>
                                    </li>
                                    <li>
                                        <a href="/loghours/review" class="nav-link px-0"> <span
                                                class="">Review Submitted Log Hours</span></a>
                                    </li>

                                    <li>
                                        <a href="/departments" class="nav-link px-0"> <span class="">Departments</span></a>
                                    </li>
                                    <li>
                                        <a href="/employee_information" class="nav-link px-0"> <span
                                                class="">Employee Information</span></a>
                                    </li>
                                    <li>
                                        <a href="/tasks/project_settings" class="nav-link px-0"> <span
                                                class="">Project Settings</span></a>
                                    </li>
                                    <li>
                                        <a href="/send_mail" class="nav-link px-0"> <span
                                                class="">Send Email To All Users</span></a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->is_writer == 1)
                            <li>
                                <a href="#submenu7" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                                    <i class="fs-4 bi-bootstrap"></i> <span class="ms-1 ">NEWS</span></a>
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
                                    <i class="fs-4 bi-bootstrap"></i> <span class="ms-1 ">EQUIPMENT</span></a>
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
                        @endif
                        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                            <li>
                                <a href="#submenu4" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-grid"></i> <span
                                        class="ms-1 ">ACCOUNTANT VIEWS</span> </a>
                                <ul class="collapse nav flex-column ms-1" id="submenu4" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a href="/accountant" class="nav-link px-0"> <span class="">Accountant Panel</span></a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->role_id == 1)
                            <li>
                                <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-grid"></i> <span
                                        class="ms-1 ">ADMIN VIEWS</span> </a>
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
                @media(max-width: 756px) {
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
