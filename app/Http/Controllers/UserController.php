<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $user = new UserModel([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'password' => $request->input('password'),
        ]);

        $user->save();

        return redirect('/home')->with('success', 'User created!');
    }

    public function showAll()
{
    $users = UserModel::all();
    return view('user_all', ['users' => $users]);
}
}
