<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInformationModel;
use App\Models\VacationPointsModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\LogHoursModel;
use App\Models\LoggedHoursSubmittedModel;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogHoursController extends Controller
{
    public function getCurrentMonth(): \Illuminate\View\View
    {
        $month = Carbon::now()->monthName;
        $day = Carbon::now()->day;

        $userLogs = LogHoursModel::query()
            ->where('user_id', Auth::user()->id)
            ->where('date', '<=', Carbon::now())
            ->where('date', '>=', Carbon::now()->startOfMonth())
            ->get();

        if ($this->checkIfClosedMonth(Auth::user()->id, $month)) {
            $datesToFill = [];

            return view('log_worked_hours', ['dates' => $datesToFill, 'month' => $month, 'userLogs' => $userLogs, 'closed' => true]);
        }


        for ($i = 0; $i < $day; $i++) {
            $dates[] = Carbon::now()->startOfMonth()->addDays($i)->format('Y-m-d');
        }

        //display logged hours
        $getDates = $this->getUserAlreadyLoggedHours();

        $datesToFill = array_diff($dates, $getDates);


        return view('log_worked_hours', ['dates' => $datesToFill, 'month' => $month, 'userLogs' => $userLogs]);
    }

    public function insertLoggedHours(Request $request): RedirectResponse
    {
        if ($this->checkIfClosedMonth(Auth::user()->id, $request->input('month'))) {
            return redirect('/');
        }

        if ($request->input('month') == Carbon::now()->subMonth()->monthName) {
            for ($i = 0; $i < Carbon::now()->subMonth()->daysInMonth; $i++) {
                $dates[] = Carbon::now()->subMonth()->startOfMonth()->addDays($i)->format('Y-m-d');
            }
        } else {
            for ($i = 0; $i < Carbon::now()->day; $i++) {
                $dates[] = Carbon::now()->startOfMonth()->addDays($i)->format('Y-m-d');
            }
        }

        $nightHoursStart = strtotime('22:00');
        $midnight = strtotime('24:00');
        $sameDay = strtotime('00:00');
        $nightHoursEnd = strtotime('06:00');

        foreach ($dates as $date) {
            if ($request->input($date . '_start_time') != null && $request->input($date . '_end_time') != null) {

                $firstPart = 0; //variables for calculating night hours
                $secondPart = 0;
                $difference = 0;

                $shiftStartTime = strtotime($request->input($date . '_start_time'));
                $shiftEndTime = strtotime($request->input($date . '_end_time'));

                //if the employee starts shift after 22:00 and ends the next day
                if($shiftStartTime >= $nightHoursStart){
                    $firstPart = $midnight - $shiftStartTime;
                    $secondPart = ($shiftEndTime - $midnight) + 86400; //add 24hours, otherwise next days are considered as todays

                    if($shiftEndTime > $nightHoursEnd) {
                        $difference = $shiftEndTime - $nightHoursEnd;
                    }

                    $nightHoursCalculate = ($firstPart + $secondPart - $difference) / 3600;
                }

                //if starts and ends on the same night(before midnight)
                else if($shiftStartTime <= $nightHoursStart && $shiftEndTime < $midnight)
                {
                    $nightHoursCalculate = ($shiftEndTime - $nightHoursStart) / 3600;
                }

                //if the employee starts past midnight and ends any time next day
                else if($shiftStartTime < $sameDay && $shiftEndTime >= $nightHoursEnd)
                {
                    $nightHoursCalculate = ($nightHoursEnd - $shiftStartTime)/3600;
                }

                //if the employee starts earlier than 22:00 and ends before or at 06:00
                else if($shiftStartTime < $nightHoursStart){
                    $firstPart = $midnight - $nightHoursStart;
                    $secondPart = ($shiftEndTime - $midnight) + 86400;

                    if($shiftEndTime > $nightHoursEnd) {
                        $difference = $shiftEndTime - $nightHoursEnd;
                    }

                    $nightHoursCalculate = ($firstPart + $secondPart - $difference) / 3600;
                }
                $nightHoursCalculate = round($nightHoursCalculate);

                if ($nightHoursCalculate < 0) {
                    $nightHoursCalculate = 0; //if we mysterically get lower than 0(doesn't happen when dealing with normal nighthours)
                }

                $loggedHours = new LogHoursModel([
                    'user_id' => $request->input('user_id'),
                    'start_time' => $request->input($date . '_start_time'),
                    'end_time' => $request->input($date . '_end_time'),
                    'total_hours' => $request->input($date . '_total_hours'),
                    'night_hours' => $nightHoursCalculate,
                    'date' => $request->input($date . '_date'),
                ]);

                $loggedHours->save();
            }
    }
        return redirect('/loghours')->with('success', 'Hours inserted!');
    }

    public function deleteLoggedHours(Request $request): RedirectResponse
    {
        $loggedHours = LogHoursModel::query()
            ->where('id', $request->input('id'))
            ->where('user_id', Auth::user()->id)->first();

        $loggedHours->delete();

        return redirect('/loghours')->with('success', 'Hours deleted!');
    }

    private function getUserAlreadyLoggedHours(): array
    {
        return LogHoursModel::query()->where('user_id', Auth::user()->id)->pluck('date')->toArray();
    }

    public function getPreviousMonth(): \Illuminate\View\View
    {
        if ($this->checkIfClosedMonth(Auth::user()->id, Carbon::now()->subMonth()->monthName)) {
            return redirect('/');
        }

        $month = Carbon::now()->subMonth()->monthName;
        $day = Carbon::now()->subMonth()->daysInMonth;

        for ($i = 0; $i < $day; $i++) {
            $dates[] = Carbon::now()->subMonth()->startOfMonth()->addDays($i)->format('Y-m-d');
        }

        $getDates = $this->getUserAlreadyLoggedHours();

        $datesToFill = array_diff($dates, $getDates);

        $hide = true;

        $userLogs = LogHoursModel::query()->where('user_id', Auth::user()->id)
            ->where('date', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->where('date', '<=', Carbon::now()->subMonth()->endOfMonth())
            ->get();

        return view('log_worked_hours', ['dates' => $datesToFill, 'month' => $month, 'userLogs' => $userLogs, 'hide' => $hide]);
    }

    public function closeMonthlyReport(Request $request): RedirectResponse
    {
        if ($request->input('month') == Carbon::now()->monthName) { //check for which month we close the report for
            $logHours = LogHoursModel::query()
                ->where('user_id', Auth::user()->id)
                ->where('date', '<=', Carbon::now())
                ->where('date', '>=', Carbon::now()->startOfMonth())
                ->select('total_hours', 'night_hours')
                ->get()->toArray();
        } else if ($request->input('month') == Carbon::now()->subMonth()->monthName) {
            $logHours = LogHoursModel::query()
                ->where('user_id', Auth::user()->id)
                ->where('date', '<=', Carbon::now()->subMonth()->endOfMonth())
                ->where('date', '>=', Carbon::now()->subMonth()->startOfMonth())
                ->select('total_hours', 'night_hours')
                ->get();
        }

        $totalHours = [];
        $nightHours = [];

        for ($i = 0; $i < sizeof($logHours); $i++) {      //get hours from rows
            $nightHours[$i] = $logHours[$i]['night_hours'];

            $time = $logHours[$i]['total_hours'];
            $time = explode(':', $time);
            $hoursToSeconds = $time[0] * 3600;
            $minutesToSeconds = $time[1] * 60;
            $time = ceil(($hoursToSeconds + $minutesToSeconds) / 3600);
            $totalHours[$i] = $time;
        }

        $totalHours = array_sum($totalHours);
        $nightHours = array_sum($nightHours);


        $insertHours = new LoggedHoursSubmittedModel([
            'user_id' => Auth::user()->id,
            'total_hours' => $totalHours,
            'night_hours' => $nightHours,
            'month_name' => $request->input('month'),
            'created_at' => Carbon::now(),
        ]);

        $insertHours->save();

        return back()->with('success', 'Monthly report submitted!');
    }

    public function checkIfClosedMonth($user_id, $month): bool
    {
        $findUser = LoggedHoursSubmittedModel::query()
            ->where('user_id', $user_id)
            ->where('month_name', $month)
            ->first();

        if ($findUser == null) {
            return false;
        }

        return true;
    }

    public function getSubmittedHours(Request $request): \Illuminate\View\View //if user has closed their hours report we call this method
    {
        $submittedHours = LoggedHoursSubmittedModel::query()
            ->where('is_confirmed', 1)
            ->get();

        $monthlyHours = [];

        foreach ($submittedHours as $submittedHour) {
            $monthlyExpected = EmployeeInformationModel::query()->where('user_id', $submittedHour->user_id)->pluck('monthly_hours')->first();
            $monthlyHours[$submittedHour->user_id] = $monthlyExpected;
        };

        return view('loghours_submit_review', ['submits' => $submittedHours, 'monthlyHours' => $monthlyHours]);
    }

    public function submitHoursReview(Request $request): RedirectResponse
    {
        $submittedHours = LoggedHoursSubmittedModel::query()
            ->where('id', $request->input('id'))
            ->first();


        if ($request->has('Approve')) {
            $employeeVacation = VacationPointsModel::query() //on confirm we automatically add vacation points
                ->where('user_id', $submittedHours->user_id)
                ->first();
            //idea is that one day costs 0.2VP, in the span of 3 worked months averaging 160hours employee will be able to automatically earn 1 month of paid vacation
            $vp = $submittedHours->total_hours * 0.0021;

            $employeeVacation->update([
                'vacation_points' => $employeeVacation->vacation_points + $vp
            ]);

            $submittedHours->update([
                'is_confirmed' => 2
            ]);
        }

        if ($request->has('Reject')) {
            $submittedHours->delete();
        }

        return back();
    }

    public function getSubmitedAndConfirmed($user_id, $month): object //for accountant view
    {
        $submittedHours = LoggedHoursSubmittedModel::query()
            ->where('is_confirmed', 2)
            ->where('month_name', $month)
            ->where('user_id', $user_id)
            ->first();

        return $submittedHours;
    }
}
