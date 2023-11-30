<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mng/register', function(){
    return view('user_create');
});

Route::get('/mng/allusers', [UserController::class, 'showAll'])->name('users');
Route::get('/login', function(){
    return view('login');
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::post('/user/create', [UserController::class, 'register'])->name('user.create');
Route::post('/logging_in', [AuthController::class, 'auth'])->name('logging_in');
