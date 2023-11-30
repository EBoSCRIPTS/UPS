<?php


namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\View\View;

class UserController extends Controller
{
    public function register(Request $request): RedirectResponse
    {
        $user = new UserModel([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'profile_picture' => $request->input('profile_picture'),
            'password' => bcrypt($request->input('password')),
        ]);

        $user->save();

        return redirect('/mng/register')->with('success', 'User created!');
    }

    public function showAll(): View
    {
        $users = UserModel::all();
        return view('user_edit', ['users' => $users]);
    }

    public function deleteUser(Request $request)
    {
        $user = UserModel::query()->find($request->input('id'));
        $user->delete();
        return redirect('/mng/edit')->with('success', 'User deleted!');
    }

}
