<?php


namespace App\Http\Controllers;

use App\Models\Tasks\TasksParticipantsModel;
use App\Models\Tasks\TasksProjectModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request): RedirectResponse
    {
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $imageName = public_path('uploads/'.$imageName);

            $uploadFolder = 'uploads/';
            $imageName = baseName($imageName);
            $imageName = $uploadFolder.$imageName;
        }
        else{
            $imageName = 'uploads/default_pfp.png';
        }

        $role = $request->input('role_id');

        $request->merge(['role_id' => $role]);

        $user = new UserModel([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'profile_picture' => $imageName,
            'password' => bcrypt($request->input('password')),
            'role_id' => $request->input('role_id'),
        ]);

        $user->save();

        return redirect('/mng/register')->with('success', 'User created!');
    }

    public function showAll(): View
    {
        $users = UserModel::all();
        return view('user_manage.user_edit', ['users' => $users]);
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
        if($request->hasFile('profile_picture'))
        {
            $image = $request->file('profile_picture');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $imageName = public_path('uploads/'.$imageName);

            $uploadFolder = 'uploads/';
            $imageName = baseName($imageName);
            $imageName = $uploadFolder.$imageName;
        }


        $user->update([
            'first_name' => $request->input('first_name') ?? $user->first_name,
            'last_name' => $request->input('last_name') ?? $user->last_name,
            'email' => $request->input('email') ?? $user->email,
            'phone_number' => $request->input('phone_number') ?? $user->phone_number,
            'profile_picture' => $imageName ?? $user->profile_picture,
        ]);

        return redirect('/mng/edit')->with('success', 'User edited!');
    }

    public function getUserInfo(Request $request)
    {
        $user = UserModel::query()->where('id', $request->id)->first();
        $projects = TasksParticipantsModel::query()->where('employee_id', $request->id)->get();

        if($user == null) {
            abort(404);
        }

        return view('profile', ['user' => $user, 'projects' => $projects]);
    }

    public function changePassword(Request $request)
    {
        $user = UserModel::query()->where('id', $request->id)->select('id', 'password')->first();

        if (!Hash::check($request->input('old_password'), $user->password)) {
            return redirect('/home')->with('error', 'Wrong password!');
        }

        $user->update([
            'password' => bcrypt($request->input('new_password')),
        ]);

        return back()->with('success', 'Password changed!');
    }

}
