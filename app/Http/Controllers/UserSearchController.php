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
        $lookingFor = strtolower($request->name);

        $users = UserModel::query()
            ->where(function ($query) use ($lookingFor) {
                //if user is looking for somebody specific
                if (strpos($searchTerm, ' ') == true) { //if user is looking for someone specific(first and last name) then find him here
                    list($firstName, $lastName) = explode(' ', $searchTerm, 2);
                    $query->whereRaw('LOWER(first_name) LIKE ?', ['%' . $firstName . '%'])
                        ->whereRaw('LOWER(last_name) LIKE ?', ['%' . $lastName . '%']);
                } else { //if user is only looking up using first or last name
                    $query->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . $searchTerm . '%'])
                        ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . $searchTerm . '%']);
                }
            })
            ->select('first_name', 'last_name', 'id')
            ->get();

        return response()->json($users);
    }

}
