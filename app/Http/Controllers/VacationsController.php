<?php

namespace App\Http\Controllers;

use App\Models\AbsenceModel;
use App\Models\EmployeeInformationModel;
use App\Models\VacationPointsModel;
use Illuminate\Http\Request;
use App\Models\EmployeeVacationsModel;

class VacationsController extends Controller
{
    public function getUserVacationInfo(Request $request)
    {
        $getRequest = AbsenceModel::query()->where('id', $request->absence_id)->first();
        $employeeDetails = EmployeeInformationModel::query()->where('user_id', $getRequest->user_id)->first();
        $vps = VacationPointsModel::query()->where('user_id', $getRequest->user_id)->pluck('vacation_points')->first();
        $previous = $this->getPreviousVacations($employeeDetails->user_id);

        return view('vacation_review', ['request' => $getRequest, 'employeeDetails' => $employeeDetails, 'previous' => $previous, 'vps' => $vps]);
    }

    public function updateBalance(Request $request)
    {
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
    }

    private function getPreviousVacations($employee_id)
    {
        $vacations = EmployeeVacationsModel::query()->where('employee_id', $employee_id)->get();

        if (sizeof($vacations) == 0) {
            return 'No previous vacations';
        }

        return $vacations;
    }
}
