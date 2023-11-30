<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function(){
    return view('test');
});

Route::get('/mng/register', function(){
    return view('user_create');
});

Route::get('/mng/allusers', [UserController::class, 'showAll'])->name('users');

Route::post('/user/create', [UserController::class, 'register'])->name('user.create');
