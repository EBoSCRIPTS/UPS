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
use App\Http\Controllers\TasksBoardController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProjectPerformanceController;
use App\Http\Controllers\VacationsController;
use App\Http\Controllers\SubmittedTicketsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TestController;


/* Home view */
Route::get('/', [NewsController::class, 'loadAllTopics'])->middleware('loggedIn');

/* Manager views */
Route::middleware('admin')->group(function () {
    Route::get('/mng/register', [UserController::class, 'createUserView']);
    Route::get('/mng/edit', [UserController::class, 'showAll'])->name('users');
});

/* Profile views */
Route::get('/login', function () {
    return view('login');
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logging_in', [AuthController::class, 'auth'])->name('logging_in');

Route::middleware('loggedIn')->group(function () {
    Route::get('/profile/{id}', [UserController::class, 'getUserInfo'])->name('profile');
    Route::post('/profile/{id}/change_password', [UserController::class, 'changePassword'])->name('user.change_password');
    Route::post('/profile/{id}/update_banking', [UserController::class, 'updateBanking'])->name('user.update_banking');
    Route::post('/profile/send_ticket', [SubmittedTicketsController::class, 'submitTicket'])->name('user.send_ticket');
});

/* Absence views */
Route::middleware('employee')->group(function () {
    Route::get('/absence', [AbsenceController::class, 'userAbsences'])->name('absence')->middleware('employee');
    Route::get('/absence/vacation/{absence_id}', [VacationsController::class, 'getUserVacationInfo'])->name('absence.vacation')->middleware('employee');;
    Route::post('/absence/create', [AbsenceController::class, 'addAbsence'])->name('absence.create')->middleware('employee');;
    Route::post('/absence/update', [AbsenceController::class, 'updateAbsence'])->name('absence.update')->middleware('employee');
    Route::post('/absence/delete', [AbsenceController::class, 'deleteAbsence'])->name('absence.delete')->middleware('employee');
});

Route::middleware('manager')->group(function () {
    Route::get('/absence/attachment/download/{absence_id}', [AbsenceController::class, 'downloadAttachment'])->name('absence.attachment.download')->middleware('manager');
    Route::get('/absence/review', [AbsenceController::class, 'showAbsenceReview'])->name('absence.review')->middleware('manager');
});

/* User create views */
Route::middleware('admin')->group(function () {
    Route::post('/user/create', [UserController::class, 'register'])->name('user.create')->middleware('admin');
    Route::post('/user/delete', [UserController::class, 'deleteUser'])->name('user.delete')->middleware('admin');
    Route::post('/user/edit', [UserController::class, 'editUser'])->name('user.edit')->middleware('admin');
});

/* Log hours */
Route::middleware('employee')->group(function () {
    Route::get('/loghours', [LogHoursController::class, 'getCurrentMonth'])->name('loghours');
    Route::post('/loghours/create', [LogHoursController::class, 'insertLoggedHours'])->name('loghours.create');
    Route::post('/loghours/previous_month', [LogHoursController::class, 'getPreviousMonth'])->name('loghours.previous_month');
    Route::get('/loghours/view', [ViewLoggedHoursController::class, 'ViewLogged'])->name('loghours.view');
    Route::get('/loghours/view/{user_id}', [ViewLoggedHoursController::class, 'showUserLoggedHours'])->name('loghours.view.user');
    Route::post('/loghours/view/delete', [LogHoursController::class, 'deleteLoggedHours'])->name('loghours.view.delete');
    Route::post('/loghours/close_month', [LoghoursController::class, 'closeMonthlyReport'])->name('loghours.close_month');
});


Route::get('/loghours/review', [LogHoursController::class, 'getSubmittedHours'])->middleware('manager');
Route::post('/loghours/review/update', [LogHoursController::class, 'submitHoursReview'])->name('loghours.review')->middleware('manager');
Route::post('/loghours/review/update_balance/{user_id}', [VacationsController::class, 'updateBalance'])->name('loghours.update_balance')->middleware('manager');

/* Departaments */
Route::get('/departments', [DepartmentsController::class, 'showAllDepartments'])->name('showAllDepartments')->middleware('manager');
Route::post('/departments/create', [DepartmentsController::class, 'addDepartment'])->name('departments.create')->middleware('manager');
Route::post('/departments/delete', [DepartmentsController::class, 'deleteDepartment'])->name('departments.delete')->middleware('manager');
Route::get('/departments/my', [DepartmentsController::class, 'loadUserDepartment'])->name('my_department')->middleware('employee');
Route::get('/departments/my/ticket_register/{ticket_id}', [SubmittedTicketsController::class, 'ticketRegistrationUpdate'])->name('register_ticket');


/* Employee information  */
Route::middleware('manager')->group(function () {
    Route::get('/employee_information', [EmployeeInformationController::class, 'getEmployeeInformationData'])->name('employee_information')->middleware('manager');
    Route::post('/employee_information/create', [EmployeeInformationController::class, 'insertEmployeeInformation'])->name('employee_information.create')->middleware('manager');
    Route::post('/employee_information/delete', [EmployeeInformationController::class, 'deleteEmployee'])->name('employee_information.delete')->middleware('manager');
    Route::post('/employee_information/update', [EmployeeInformationController::class, 'editEmployee'])->name('employee_information.update')->middleware('manager');
});


/* Accountant views */
Route::middleware('accountant')->group(function () {
    Route::get('/accountant', [AccountantController::class, 'showAll'])->name('accountant');
    Route::get('/accountant/{id}', [AccountantController::class, 'showDept'])->name('accountant_view_department');
    Route::post('/accountant/user', [AccountantController::class, 'loadEmployeeInformation'])->name('accountant_view_department');
    Route::get('/accountant/settings/{department_id}', [AccountantController::class, 'getDepartmentSettings'])->name('department_settings');
    Route::get('/accountant/payslip/{department_id}/{user_id}/{year}/{month}', [AccountantController::class, 'getEmployeePayslipDetails']);
    Route::post('/accountant/payslip/{department_id}/{employee_id}/{year}/{month}/{hours_id}/fulfill', [AccountantController::class, 'employeePayslipFulfill'])->name('accountant.payslip_fulfill');
    Route::get('/accountant/payslip/{department_id}/{employee_id}/{year}/{month}/download', [AccountantController::class, 'downloadPayslip']);
    Route::post('/accountant/settings/{department_id}/add_tax', [AccountantController::class, 'addTax'])->name('accountant.add_tax');
    Route::get('/accountant/settings/{department_id}/delete_tax/{tax_id}', [AccountantController::class, 'deleteTax'])->name('accountant.delete_tax');
});


/* Tasks view */
Route::middleware('loggedIn')->group(function () {
    Route::get('/tasks', [TasksController::class, 'loadMyTasks'])->name('tasks.show')->middleware('loggedIn');
    Route::get('/tasks/projects/{project_id}', [TasksController::class, 'loadProjectTasks'])->name('tasks.projects');
    Route::get('/tasks/projects/{project_id}/all_tasks', [TasksBoardController::class, 'loadAllProjectTasks'])->name('tasks.projects.all_tasks');
    Route::get('/tasks/projects/{project_id}/statistics', [TasksBoardController::class, 'getProjectStatistics'])->name('tasks.projects.statistics');
    Route::post('/tasks/projects/{project_id}/statistics/generate_period', [TasksBoardController::class, 'getStatisticsForPeriod'])->name('tasks.projects.statistics.generate_for_period');
    Route::post('/tasks/projects/{project_id}/statistics/generate_excel', [TasksBoardController::class, 'generateExcelForProjectStatistics'])->name('tasks.project.generate_xlsx');
});

Route::get('/tasks/create_new_project', [TasksController::class, 'loadNewProjectsPage'])->middleware('manager');
Route::get('/tasks/project_settings', [TasksController::class, 'loadAvailableProjects'])->middleware('manager');

Route::middleware('loggedIn')->group(function () {
    Route::get('/tasks/project_settings/{project_id}', [TasksBoardController::class, 'getProjectSettings'])->name('project_settings');
    Route::post('/tasks/project_settings/add_user', [TasksBoardController::class, 'addUserToProject'])->name('tasks.project_add_user');
    Route::post('/tasks/project_settings/remove_user', [TasksBoardController::class, 'removeUserFromProject'])->name('tasks.project_remove_user');
    Route::post('/tasks/project_settings/project_edit/{project_id}', [TasksBoardController::class, 'editProject'])->name('tasks.project_edit');
    Route::post('/tasks/project_settings/update_leader/{project_id}', [TasksBoardController::class, 'updateProjectLeader'])->name('tasks.change_project_leader');
    Route::post('/tasks/project_settings/delete/{project_id}', [TasksBoardController::class, 'deleteProject'])->name('tasks.project_delete');
    Route::get('/tasks/projects/{project_id}/performance_report', [ProjectPerformanceController::class, 'loadProjectPerformance']);
    Route::post('/tasks/projects/{project_id}/performance_report/make', [ProjectPerformanceController::class, 'makeReport'])->name('tasks.performance_report_create');
    Route::get('/tasks/projects/{project_id}/performance_report/generate_xlsx', [ProjectPerformanceController::class, 'generatePerformanceReportXlsx']);
    Route::get('/tasks/projects/{project_id}/performance_report/soft_delete/{report_id}', [ProjectPerformanceController::class, 'softDeleteReport']);
});


Route::post('/tasks/create_new_project/insert', [TasksController::class, 'createNewProject'])->name('create_new_project')->middleware('manager');


Route::get('/tasks/create_new_task', function () {
    view('tasks_create_task');
});

Route::middleware('loggedIn')->group(function () {
    Route::get('/tasks/ticket/{ticket_id}', [TasksController::class, 'loadTicket'])->name('tasks.ticket');
    Route::post('/tasks/create_new_task/create', [TasksController::class, 'newTask'])->name('create_new_task');
    Route::post('/tasks/ticket/update_ticket/', [TasksController::class, 'updateStatus'])->name('tasks.update_status');
    Route::post('/tasks/ticket/add_comment', [TasksController::class, 'addComment'])->name('tasks.add_comment');
    Route::post('/tasks/ticket/update', [TasksController::class, 'updateTaskDescription'])->name('tasks.update_description');
    Route::post('/tasks/ticket/update_assignee/{ticket_id}', [TasksController::class, 'updateAssignee'])->name('tasks.update_assignee');
    Route::post('/tasks/ticket/update_title/{ticket_id}', [TasksController::class, 'updateTitle'])->name('tasks.update_title');
    Route::post('/tasks/ticket/update_draft/', [TasksController::class, 'updateTaskDraftStatus'])->name('tasks.update_draft');
    Route::post('/tasks/ticket/change_priority', [TasksController::class, 'updatePriority'])->name('tasks.update_priority');
    Route::post('/tasks/ticket/delete', [TasksController::class, 'deleteTicket'])->name('tasks.delete_ticket');
    Route::post('/tasks/ticket/complete', [TasksController::class, 'completeTicket'])->name('tasks.complete_ticket');
});

/* Equipment views */
Route::middleware('manager')->group(function () {
    Route::get('/equipment/register', [EquipmentController::class, 'showRegistered'])->name('equipment.register');

    Route::post('/equipment/register/insert', [EquipmentController::class, 'addEquipmentType'])->name('equipment.add_equipment_type');
    Route::post('/equipment/register/add', [EquipmentController::class, 'addEquipment'])->name('equipment.add_equipment');

    Route::get('/equipment/equipment_assignment/', [EquipmentController::class, 'loadAssignables'])->name('equipment.assignment');
    Route::post('/equipment/equipment_assignment/assign', [EquipmentController::class, 'assignEquipment'])->name('equipment.assign_equipment');
    Route::get('/equipment/equipment_assignment/{id}', [EquipmentController::class, 'loadAssignables'])->name('equipment.get_equipment_for_user');
    Route::post('/equipment/delete_equipment_item', [EquipmentController::class, 'deleteEquipment'])->name('equipment.delete_equipment');
    Route::post('/equipment/return_equipment_item', [EquipmentController::class, 'returnEquipment'])->name('equipment.return_equipment');
    Route::post('/equipment/delete_type/', [EquipmentController::class, 'deleteEquipmentType'])->name('equipment.delete_equipment_type');
});

/* Topic creation views */
Route::middleware('writer')->group(function () {
    Route::get('/news/create_topic', [NewsController::class, 'createTopic'])->name('news.create_topic')->middleware('writer');
    Route::post('/news/create_topic/new', [NewsController::class, 'insertNewTopic'])->name('news.create_new_topic')->middleware('writer');
    Route::get('/news/topic/edit/{topic_id}', [NewsController::class, 'loadEditNewsTopic'])->name('news.edit_topic')->middleware('writer');
    Route::post('/news/topic/edit/update/', [NewsController::class, 'updateTopic'])->name('news.edit_save')->middleware('writer');
    Route::post('/news/topic/delete/{topic_id}', [NewsController::class, 'deleteNewsTopic'])->name('news.delete_topic')->middleware('writer');
});

Route::middleware('loggedIn')->group(function () {
    Route::get('/news/view_topic/{topic_id}', [NewsController::class, 'loadNewsTopic'])->name('news.view_topic');
    Route::post('/news/view_topic/add_comment', [NewsController::class, 'postTopicComment'])->name('news.add_comment');
    Route::get('/news/view_topic/{topic_id}/{comment_id}/{uprate}', [NewsController::class, 'rateTopicComment']);
    Route::get('/news/view_topic/{topic_id}/{comment_id}/{downrate}', [NewsController::class, 'rateTopicComment']);
    Route::get('/news/topics', [NewsController::class, 'loadNewsPageTopics'])->name('news.topics');
});

Route::middleware('admin')->group(function(){
   Route::get('/settings/night_hours', [SettingsController::class, 'nightHours']);
   Route::post('/settings/night_hours/update', [SettingsController::class, 'nightHoursUpdate'])->name('settings.night_hours_update');
});

/* MAIL Related views */
Route::get('/send_mail', function () {
    return view('send_mail');
})->middleware('manager');
Route::post('/send_mail/submit', [MailController::class, 'sendMailToAll'])->name('send_mail')->middleware('manager');

/* PDF creation views */
Route::post('/equipment/generate_agreement', [PDFController::class, 'generateEquipmentAgreement'])->name('equipment.generate_agreement')->middleware('manager');


/* REST API routes */
Route::middleware('loggedIn')->group(function () {
    Route::get('/api/all_users_json/{name}', [UserSearchController::class, 'userSpecific'])->middleware('loggedIn');
    Route::get('/api/get_all_projects', [TasksController::class, 'projectsApi'])->middleware('loggedIn');
});


/* TEST PAGES */
Route::get('/test_page', [TestController::class, 'toastrNot'])->middleware('admin');
