<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsenceController;

Route::get('/', function () {
    return view('home');
});

Route::get('/mng/register', function () {
    return view('user_create');
});

Route::get('/mng/allusers', [UserController::class, 'showAll'])->name('users');
Route::get('/login', function () {
    return view('login');
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', function () {
    return view('profile');
});

Route::get('/mng/edit', [UserController::class, 'showAll'])->name('users');

Route::get('/absence', [AbsenceController::class, 'getUserAbsence'])->name('absence');



Route::post('/logging_in', [AuthController::class, 'auth'])->name('logging_in');

Route::post('/user/create', [UserController::class, 'register'])->name('user.create');
Route::post('/user/delete', [UserController::class, 'deleteUser'])->name('user.delete');
Route::post('/user/edit', [UserController::class, 'editUser'])->name('user.edit');

Route::post('/absence/create', [AbsenceController::class, 'addAbsence'])->name('absence.create');
