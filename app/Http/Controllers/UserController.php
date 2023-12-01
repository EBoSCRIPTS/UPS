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
        if ($request->input('profile_picture') == null) {
            $img = $request->merge(['profile_picture' => storage_path('app\public\default_pfp.png')]);
        }
        else{
            $img = $request->input('profile_picture');
        }
        $role = $request->input('role_id');
        switch($role) {
            case 'Superadmin':
                $role = 1;
                break;
            case 'Manager':
                $role = 3;
                break;
            case 'Employee':
                $role = 2;
                break;
            default:
                $role = 2;
        }
        $request->merge(['role_id' => $role]);

        $user = new UserModel([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'profile_picture' => $img,
            'password' => bcrypt($request->input('password')),
            'role_id' => $request->input('role_id'),
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

    public function editUser(Request $request)
    {
        $user = UserModel::query()->find($request->input('id'));
        if($request->input('first_name') == null) {
            $request->merge(['first_name' => $user->first_name]);
        }
        if($request->input('last_name') == null) {
            $request->merge(['last_name' => $user->last_name]);
        }
        if($request->input('email') == null) {
            $request->merge(['email' => $user->email]);
        }
        if($request->input('phone_number') == null) {
            $request->merge(['phone_number' => $user->phone_number]);
        }
        if($request->input('profile_picture') == null) {
            $request->merge(['profile_picture' => $user->profile_picture]);
        }
        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'profile_picture' => $request->input('profile_picture'),
        ]);

        return redirect('/mng/edit')->with('success', 'User edited!');
    }

}
