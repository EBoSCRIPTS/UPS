<?php

namespace App\Http\Controllers;

use App\Models\AbsenceModel;
use App\Models\EmployeeInformationModel;
use App\Models\VacationPointsModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\EmployeeVacationsModel;
use Illuminate\View\View;

class VacationsController extends Controller
{
    public function getUserVacationInfo(Request $request): View //used for vacations review page(only accessible when user requests a vacation)
    {
        $getRequest = AbsenceModel::query()->where('id', $request->absence_id)->first();
        $employeeDetails = EmployeeInformationModel::query()->where('user_id', $getRequest->user_id)->first();
        $vps = VacationPointsModel::query()->where('user_id', $getRequest->user_id)->pluck('vacation_points')->first();
        $previous = $this->getPreviousVacations($employeeDetails->user_id);

        return view('vacation_review', ['request' => $getRequest, 'employeeDetails' => $employeeDetails, 'previous' => $previous, 'vps' => $vps]);
    }

    public function updateBalance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:users,id',
            'balance' => 'required|numeric',
        ]);

        $getBalance = VacationPointsModel::query()
            ->where('user_id', $request->input('employee_id'))
            ->first();
        $vp = floatval($request->input('balance'));

        if ($getBalance->vacation_points < $vp) {
            return back()->with('error', 'Insufficient balance');
        }

        $getBalance->update([
            'vacation_points' => $getBalance->vacation_points - $vp
        ]);

        return back()->with('success', 'Balance updated');
    }

    private function getPreviousVacations($user_id): Collection|string
    {
        $vacations = EmployeeVacationsModel::query()->where('user_id', $user_id)->get();

        if (sizeof($vacations) == 0) { //return a string (will be displayed) if no previous vacations
            return 'No previous vacations';
        }

        return $vacations;
    }
}
