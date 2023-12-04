<?php

namespace App\Http\Controllers;

use App\Models\LogHoursModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class ViewLoggedHoursController extends Controller
{
    public function ViewLogged(Request $request)
    {
        $showAll = $this->showAll($request);
        $showUser = $this->showUsers($request);

        return view('view_logged_hours', ['loggedHours' => $showAll, 'users' => $showUser]);
    }
    public function showAll(Request $request)
    {
        return LogHoursModel::all();
    }

    public function showUsers(Request $request)
    {
        return UserModel::all();
    }

    public function showUserLoggedHours(Request $request)
    {
        $loggedHours = LogHoursModel::query()->where('user_id', $request->input('user_id'))->get();
        $users = $this->showUsers($request);
        return view('view_logged_hours', ['loggedHours' => $loggedHours, 'users' => $users]);
    }
}
