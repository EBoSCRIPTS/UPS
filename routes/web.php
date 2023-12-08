<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\LogHoursController;
use App\Http\Controllers\ViewLoggedHoursController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\EmployeeInformationController;
use App\Http\Controllers\AccountantController;
use App\Http\Controllers\UserSearchController;
use App\Http\Controllers\TasksController;

/* Home view */
Route::get('/', function () {
    return view('home');
});

/* Manager views */
Route::get('/mng/register', function () {
    return view('user_create');
});
Route::get('/mng/allusers', [UserController::class, 'showAll'])->name('users')->middleware('admin');
Route::get('/mng/edit', [UserController::class, 'showAll'])->name('users')->middleware('admin');

/* Profile views */
Route::get('/login', function () {
    return view('login');
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile/{id}', [UserController::class, 'getUserInfo'])->name('profile');
Route::post('/logging_in', [AuthController::class, 'auth'])->name('logging_in');

/* Absence views */
Route::get('/absence', [AbsenceController::class, 'userAbsences'])->name('absence');
Route::get('/absence/review', [AbsenceController::class, 'showAbsenceReview'])->name('absence.review')->middleware('manager');
Route::post('/absence/create', [AbsenceController::class, 'addAbsence'])->name('absence.create');
Route::post('/absence/update', [AbsenceController::class, 'updateAbsence'])->name('absence.update');
Route::post('/absence/delete', [AbsenceController::class, 'deleteAbsence'])->name('absence.delete');

/* User create views */
Route::post('/user/create', [UserController::class, 'register'])->name('user.create')->middleware('admin');
Route::post('/user/delete', [UserController::class, 'deleteUser'])->name('user.delete')->middleware('admin');
Route::post('/user/edit', [UserController::class, 'editUser'])->name('user.edit')->middleware('admin');

/* Log hours */
Route::get('/loghours', [LogHoursController::class, 'getCurrentMonth'])->name('loghours');
Route::post('/loghours/create', [LogHoursController::class, 'insertLoggedHours'])->name('loghours.create');

Route::get('/loghours/view', [ViewLoggedHoursController::class, 'ViewLogged'])->name('loghours.view');
Route::post('/loghours/view/user', [ViewLoggedHoursController::class, 'showUserLoggedHours'])->name('loghours.view.user');
Route::post('/loghours/view/delete', [LogHoursController::class, 'deleteLoggedHours'])->name('loghours.view.delete');

/* Departaments */
Route::get('/departments', [DepartmentsController::class, 'showAllDepartments'])->name('showAllDepartments')->middleware('manager');
Route::post('/departments/create', [DepartmentsController::class, 'addDepartment'])->name('departments.create')->middleware('manager');
Route::post('/departments/delete', [DepartmentsController::class, 'deleteDepartament'])->name('departments.delete')->middleware('manager');


/* Employee information  */
Route::get('/employee_information', [EmployeeInformationController::class, 'getData'])->name('employee_information')->middleware('manager');
Route::post('/employee_information/create', [EmployeeInformationController::class, 'insertEmployeeInformation'])->name('employee_information.create')->middleware('manager');
Route::post('/employee_information/delete', [EmployeeInformationController::class, 'deleteEmployee'])->name('employee_information.delete')->middleware('manager');

/* Accountant views */
Route::get('/accountant', [AccountantController::class, 'showAll'])->name('accountant');
Route::get('/accountant/{id}', [AccountantController::class, 'showDept'])->name('accountant_view_department');
Route::post('/accountant/user', [AccountantController::class, 'loadEmployeeInformation'])->name('accountant_view_department');

/* Tasks view */
Route::get('/tasks', function(){
   return view('tasks_landing');
});
Route::get('/tasks/create_new_project', function(){
   return view('tasks_create_project');
});
Route::get('/tasks/project_settings', [TasksController::class, 'loadAvailableProjects']);
Route::get('/tasks/project_settings/{project_id}', [TasksController::class, 'getProjectSettings'])->name('project_settings');

Route::post('/tasks/create_new_project/insert', [TasksController::class, 'createNewProject'])->name('create_new_project');
Route::get('/tasks/create_new_task', function(){
    view('tasks_create_task');
});
Route::post('/tasks/create_new_task/create', [TasksController::class, 'newTask'])->name('create_new_task');


/* REST API routes */
Route::get('/api/all_users_json/{first_name}', [UserSearchController::class, 'index'])->middleware('admin');
Route::get('/api/get_all_departments', [DepartmentsController::class, 'departmentsApi'])->middleware('manager');
Route::get('/api/get_all_projects', [TasksController::class, 'projectsApi'])->middleware('manager');
