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
        $previous = $this->getPreviousVacations($employeeDetails->id);

        return view('vacation_review', ['request' => $getRequest, 'employeeDetails' => $employeeDetails, 'previous' => $previous, 'vps' => $vps]);
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
