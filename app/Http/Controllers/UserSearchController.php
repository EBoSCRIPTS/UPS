<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;

class UserSearchController extends Controller
{
    public function allUsers(){
        $users = UserModel::query()->select('id', 'first_name', 'last_name')->get();
        return response()->json($users);
    }
    public function userSpecific(Request $request)
    {
        $users = UserModel::query()
            ->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($request->first_name) . '%'])
            ->select('first_name', 'last_name', 'id')
            ->get();
        return response()->json($users);
    }
}