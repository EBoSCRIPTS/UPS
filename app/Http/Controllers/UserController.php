<?php


namespace App\Http\Controllers;

use App\Models\DepartmentsModel;
use App\Models\EmployeeInformationModel;
use App\Models\Equipment\EquipmentAssignmentModel;
use App\Models\PerformanceReportsModel;
use App\Models\RolesModel;
use App\Models\Tasks\TasksParticipantsModel;
use App\Models\EmployeeVacationsModel;
use App\Models\VacationPointsModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function createUserView(Request $request): View
    {
        return view('user_manage.user_create', ['roles' => RolesModel::all()]);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'string|required|max:50',
            'last_name' => 'string|required|max:50',
            'email' => 'string|required|email|unique:users,email|max:255',
            'password' => 'required|min:8',
            'phone_number' => 'required|unique:users,phone_number|max:15',
            'role_id' => 'required',
        ]);

        $isWriter = $this->changeWriter($request->input('writer') ?? 'off');

        if ($request->hasFile('profile_picture')) { //store image in server storage
            $image = $request->file('profile_picture');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile_pictures/'), $imageName);
            $imageName = public_path('uploads/profile_pictures/' . $imageName);

            $uploadFolder = 'uploads/profile_pictures/';
            $imageName = baseName($imageName);
            $imageName = $uploadFolder . $imageName;
        } else {
            $imageName = 'uploads/default_pfp.png';
        }

        $user = new UserModel([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'profile_picture' => $imageName,
            'password' => bcrypt($request->input('password')),
            'role_id' => $request->input('role_id'),
            'is_writer' => $isWriter,
        ]);

        $user->save();

        return redirect('/mng/register')->with('success', 'User created!');
    }

    public function showAll(): View
    {
        $users = UserModel::query()->where('soft_deleted', 0)->get();
        return view('user_manage.user_edit', ['users' => $users]);
    }

    public function deleteUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $user = UserModel::query()->find($request->input('id'));
        $isEmployee = EmployeeInformationModel::query()->where('user_id', $user->id)->first();

        if ($isEmployee) {
            return redirect('/mng/edit')->withErrors(['error' => 'Cannot delete, user is still as an employee!']);
        }

        if ($user->role_id == 1) //admins cant delete admins
        {
            return redirect('/mng/edit')->withErrors(['error', 'Cannot delete superadmin!']);
        }

        if ($user->created_at > Carbon::now()->subMinutes(1)) {
            $user->delete();
        } //if the user is not 'fresh' we assume that he has most likely made comments or posts and should not be fully wiped to avoid data conflicts
        else {
            $user->update([
                'soft_deleted' => 1
            ]);
        }

        return redirect('/mng/edit')->with('success', 'User deleted!');
    }

    public function editUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'sometimes|nullable|email|string|unique:users,email|max:255',
            'phone_number' => 'sometimes|nullable|numeric|unique:users,phone_number|max:15',
            'profile_picture' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $user = UserModel::query()->find($request->input('id'));

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile_pictures/'), $imageName);
            $imageName = public_path('uploads/profile_pictures/' . $imageName);

            $uploadFolder = 'uploads/profile_pictures/';
            $imageName = baseName($imageName);
            $imageName = $uploadFolder . $imageName;
        }

        $isWriter = $this->changeWriter($request->input('writer') ?? 'off');

        $user->update([
            'first_name' => $request->input('first_name') ?? $user->first_name, //we POST everything so its important to keep fallbacks
            'last_name' => $request->input('last_name') ?? $user->last_name,
            'email' => $request->input('email') ?? $user->email,
            'phone_number' => $request->input('phone_number') ?? $user->phone_number,
            'profile_picture' => $imageName ?? $user->profile_picture,
            'is_writer' => $isWriter ?? $user->is_writer,
        ]);

        return back()->with('success', 'User edited!');
    }

    public function getUserInfo(Request $request): View
    {
        $user = UserModel::query()->where('id', $request->id)->first();
        $employeeId = EmployeeInformationModel::query()->where('user_id', $request->id)->pluck('id')->first();
        $projects = TasksParticipantsModel::query()->where('employee_id', $employeeId)->get();


        if ($request->id == Auth::user()->id) { //only return this sensitive info if the user profile is user himself
            $employeeInformation = EmployeeInformationModel::query()->where('user_id', Auth::user()->id)->first();
            $performanceReport = PerformanceReportsModel::query()
                ->where('user_id', Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->first();
            $departments = DepartmentsModel::query()->select('id', 'name')->get();
            $employeeEquipment = EquipmentAssignmentModel::query()->where('employee_id', $employeeId)->get();
            $vp = VacationPointsModel::query()->where('user_id', Auth::user()->id)->pluck('vacation_points')->first();
        }

        if ($user == null) {
            abort(404);
        }

        return view('profile', ['user' => $user,
            'projects' => $projects,
            'employeeInformation' => $employeeInformation ?? null,
            'employeeEquipment' => $employeeEquipment ?? null,
            'performanceReport' => $performanceReport ?? null,
            'departments' => $departments ?? null,
            'vp' => $vp ?? null]);
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_new_password' => 'required|same:new_password',
        ]);

        $user = UserModel::query()->where('id', $request->id)->select('id', 'password')->first();

        if (!Hash::check($request->input('old_password'), $user->password)) { //if passwords don't match force the user back
            return redirect('/home')->with('error', 'Wrong password!');
        }

        $user->update([
            'password' => bcrypt($request->input('new_password')),
        ]);

        return back()->with('success', 'Password changed!');
    }

    public function updateBanking(Request $request): RedirectResponse
    {
        $employee = EmployeeInformationModel::query()->where('user_id', Auth::user()->id)->first();

        if ($employee == null) { //as we store this in employee table we can't update info if users not registered as an employee yet
            return back()->withInput()->withErrors([
                'employee_error' => 'You aren\'t registered as an employee yet!'
            ]);
        }

        $validated = $request->validate([
            'bank_name' => 'required',
            'account_name' => 'required|unique:employee_information,bank_account_name',
            'account_number' => 'required|unique:employee_information,bank_account',
        ]);

        $employee->update([
            'bank_name' => $request->input('bank_name'),
            'bank_account_name' => $request->input('account_name'),
            'bank_account' => $request->input('account_number'),
        ]);

        return back()->with('success', 'Banking details updated!');
    }

    private function changeWriter($value){
        return $value == 'on' ? 2 : 3; //due to enum, 3 would be considered as off
    }
}
