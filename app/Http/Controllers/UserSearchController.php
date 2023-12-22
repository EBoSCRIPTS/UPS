<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\UserModel;

class UserSearchController extends Controller
{
    public function userSpecific(Request $request): JsonResponse
    {
        $users = UserModel::query()
            ->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($request->first_name) . '%'])
            ->select('first_name', 'last_name', 'id')
            ->get();

        $usersLastName = UserModel::query()
            ->whereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($request->first_name) . '%'])
            ->select('first_name', 'last_name', 'id')
            ->get();

        return response()->json($users->merge($usersLastName)); //merge both so we can search both first and last name
    }
}
