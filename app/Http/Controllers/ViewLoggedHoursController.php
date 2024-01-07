<?php

namespace App\Http\Controllers;

use App\Models\LogHoursModel;
use App\Models\UserModel;
use FontLib\TrueType\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ViewLoggedHoursController extends Controller
{
    public function ViewLogged(Request $request): View
    {
        return view('view_logged_hours', ['loggedHours' => LogHoursModel::all(), 'users' => UserModel::query()->where('soft_deleted', 0)->get()]);
    }

    public function showUserLoggedHours(Request $request) //when loading user specific hours
    {
        $loggedHours = LogHoursModel::query()
            ->where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        $users = UserModel::query()->where('soft_deleted', 0)->get();

        return view('view_logged_hours', ['loggedHours' => $loggedHours, 'users' => $users]);
    }
}
