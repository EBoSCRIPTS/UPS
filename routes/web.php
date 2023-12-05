<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\LogHoursController;
use App\Http\Controllers\ViewLoggedHoursController;

/* Home view */
Route::get('/', function () {
    return view('home');
});

/* Manager views */
Route::get('/mng/register', function () {
    return view('user_create');
});
Route::get('/mng/allusers', [UserController::class, 'showAll'])->name('users');
Route::get('/mng/edit', [UserController::class, 'showAll'])->name('users');

/* Profile views */
Route::get('/login', function () {
    return view('login');
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', function () {
    return view('profile');
});
Route::post('/logging_in', [AuthController::class, 'auth'])->name('logging_in');

/* Absence views */
Route::get('/absence', [AbsenceController::class, 'userAbsences'])->name('absence');
Route::get('/absence/review', [AbsenceController::class, 'showAbsenceReview'])->name('absence.review');
Route::post('/absence/create', [AbsenceController::class, 'addAbsence'])->name('absence.create');
Route::post('/absence/update', [AbsenceController::class, 'updateAbsence'])->name('absence.update');
Route::post('/absence/delete', [AbsenceController::class, 'deleteAbsence'])->name('absence.delete');

/* User create views */
Route::post('/user/create', [UserController::class, 'register'])->name('user.create');
Route::post('/user/delete', [UserController::class, 'deleteUser'])->name('user.delete');
Route::post('/user/edit', [UserController::class, 'editUser'])->name('user.edit');

/* Log hours */
Route::get('/loghours', [LogHoursController::class, 'getCurrentMonth'])->name('loghours');
Route::post('/loghours/create', [LogHoursController::class, 'insertLoggedHours'])->name('loghours.create');

Route::get('/loghours/view', [ViewLoggedHoursController::class, 'ViewLogged'])->name('loghours.view');
Route::post('/loghours/view/user', [ViewLoggedHoursController::class, 'showUserLoggedHours'])->name('loghours.view.user');
Route::post('/loghours/view/delete', [LogHoursController::class, 'deleteLoggedHours'])->name('loghours.view.delete');
