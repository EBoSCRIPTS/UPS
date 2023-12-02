<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsenceController;

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
Route::get('/absence', [AbsenceController::class, 'getUserAbsence'])->name('absence');
Route::get('/absence/review', [AbsenceController::class, 'showAbsenceReview'])->name('absence.review');
Route::post('/absence/create', [AbsenceController::class, 'addAbsence'])->name('absence.create');
Route::post('/absence/update', [AbsenceController::class, 'updateAbsence'])->name('absence.update');

/* User create views */
Route::post('/user/create', [UserController::class, 'register'])->name('user.create');
Route::post('/user/delete', [UserController::class, 'deleteUser'])->name('user.delete');
Route::post('/user/edit', [UserController::class, 'editUser'])->name('user.edit');

