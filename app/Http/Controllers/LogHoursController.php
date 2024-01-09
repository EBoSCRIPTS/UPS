<?php

namespace App\Http\Controllers;

use App\Models\AbsenceModel;
use App\Models\AccountantFulfilledPayslipsModel;
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
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        if ($this->checkIfClosedMonth(Auth::user()->id, $request->input('month'))) { //stop user from inserting data if month is confirmed as closed
            return redirect('/');
        }

        if ($request->input('month') == Carbon::now()->subMonth()->monthName) { //check if user submitted last month data
            for ($i = 0; $i < Carbon::now()->subMonth()->daysInMonth; $i++) {
                $dates[] = Carbon::now()->subMonth()->startOfMonth()->addDays($i)->format('Y-m-d');
            }
        } else {
            for ($i = 0; $i < Carbon::now()->day; $i++) {
                $dates[] = Carbon::now()->startOfMonth()->addDays($i)->format('Y-m-d');
            }
        }

        foreach ($dates as $date) { //loops through all the passed dates and inserts them into the database
            if ($request->input($date . '_start_time') != null && $request->input($date . '_end_time') != null) {
                $shiftStartTime = strtotime($request->input($date . '_start_time'));
                $shiftEndTime = strtotime($request->input($date . '_end_time'));
                $breakTime = $request->input($date . '_break_time') * 60;

                $calculated = $this->calculateHours($shiftStartTime, $shiftEndTime, $breakTime);

                $loggedHours = new LogHoursModel([
                    'user_id' => $request->input('user_id'),
                    'start_time' => $request->input($date . '_start_time'),
                    'end_time' => $request->input($date . '_end_time'),
                    'total_hours' => $calculated[0],
                    'night_hours' => $calculated[1],
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

        if ($loggedHours->user_id != Auth::user()->id) {
            return redirect('/');
        }

        $loggedHours->delete();

        return redirect('/loghours')->with('success', 'Hours deleted!');
    }

    private function getUserAlreadyLoggedHours(): array
    {
        return LogHoursModel::query()->where('user_id', Auth::user()->id)->pluck('date')->toArray();
    }

    public function getPreviousMonth(): \Illuminate\View\View|RedirectResponse
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

        //get confirmed
        $confirmedHours = LoggedHoursSubmittedModel::query()
            ->where('is_confirmed', 2) //2 because of enum
            ->get();

        $weeklyHours = [];

        foreach ($submittedHours as $submittedHour) {
            $weeklyExpected = EmployeeInformationModel::query()->where('user_id', $submittedHour->user_id)->pluck('weekly_hours')->first();
            $weeklyHours[$submittedHour->user_id] = $weeklyExpected;
        };

        return view('loghours_submit_review', ['submits' => $submittedHours, 'weeklyHours' => $weeklyHours, 'confirmedHours' => $confirmedHours]);
    }

    public function submitHoursReview(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => 'required',
        ]);

        $submittedHours = LoggedHoursSubmittedModel::query()
            ->where('id', $request->input('id'))
            ->first();

        if ($request->has('Approve')) {
            $employeeVacation = VacationPointsModel::query() //on confirm we automatically add vacation points
            ->where('user_id', $submittedHours->user_id)
                ->first();
            //idea is that one day costs 0.2VP, in the span of 3 worked months averaging 160hours employee will be able to automatically earn 1 month of paid vacation
            $vp = $submittedHours->total_hours * 0.00265;

            $employeeVacation->update([
                'vacation_points' => $employeeVacation->vacation_points + $vp
            ]);
            $submittedHours->update([
                'overtime_hours' => $request->input('overtime_hours'),
                'is_confirmed' => 2,
            ]);
        }

        if ($request->has('Reject')) {
            $submittedHours->delete();
        }

        return back();
    }

    //returns confirmed hours that accountant can review
    public function getSubmittedAndConfirmed($user_id, $month): object|null //for accountant view
    {
        $hrs = LoggedHoursSubmittedModel::query()
            ->where('is_confirmed', 2)
            ->where('month_name', $month)
            ->where('user_id', $user_id)
            ->first();

        if (!AccountantFulfilledPayslipsModel::query()->where('loghours_submitted_id', $hrs->id ?? null)->exists()) {
            return $hrs;
        }
        else{
            return null;
        }
    }

    public function calculateHours($shiftStartTime, $shiftEndTime, $breakTime): array
    {
        $nightHoursStart = strtotime('22:00');
        $nightHoursEnd = strtotime('06:00');
        $midnight = strtotime('24:00'); //get the midnight ("next day") time

        $difference = 0;

        $totalTime = ($shiftEndTime - $shiftStartTime - $breakTime) / 3600;
        //important to keep the same IF order, otherwise the code will not calculate properly.
        if ($totalTime < 0) {
            $totalTime = $totalTime + 24;
        }

        if ($shiftStartTime > $shiftEndTime) { //if start time is bigger than endtime, we consider that endtime is the next day
            $shiftEndTime += 86400;
        }

        //if the employee starts shift after 22:00 and ends the next day //ir 22 - 06
        if ($shiftStartTime >= $nightHoursStart) {
            $firstPart = $midnight - $shiftStartTime;
            $secondPart = $shiftEndTime - $midnight;
            $nightHoursCalculate = ($firstPart + $secondPart) / 3600;
        } //if starts and ends on the same night(before midnight)
        else if ($shiftStartTime <= $nightHoursStart && $shiftEndTime >= $nightHoursStart) {
            $nightHoursCalculate = ($shiftEndTime - $nightHoursStart) / 3600;
            if ($nightHoursCalculate < 0) {
                $nightHoursCalculate = 0;
            }
        } //if the employee starts past midnight and ends any time next day
        else if ($shiftStartTime <= $nightHoursEnd && $shiftEndTime <= $midnight) {
            if ($shiftEndTime > $nightHoursEnd) {
                $nightHoursCalculate = ($nightHoursEnd - $shiftStartTime) / 3600;
            } else {
                $nightHoursCalculate = ($shiftEndTime - $shiftStartTime) / 3600;
            }
        } //if the employee starts earlier than 22:00 and ends before or at 06:00
        else if ($shiftStartTime < $nightHoursStart && $shiftEndTime <= $nightHoursEnd) {
            $firstPart = $midnight - $nightHoursStart;
            $secondPart = ($shiftEndTime - $midnight) + 86400;

            if ($shiftEndTime > $nightHoursEnd) {
                $difference = $shiftEndTime - $nightHoursEnd;
            }

            $nightHoursCalculate = ($firstPart + $secondPart - $difference) / 3600;
        }

        if (isset($nightHoursCalculate)) {
            $nightHoursCalculate = round($nightHoursCalculate);
            $nightHoursCalculate = $nightHoursCalculate - $breakTime / 3600;
            if ($nightHoursCalculate < 0) {
                $nightHoursCalculate = 0; //if we mysterically get lower than 0(doesn't happen when dealing with normal nighthours)
            }
        } else {
            $nightHoursCalculate = 0;
        }

        $totalTime = round($totalTime, 2);
        $timeConvert = explode('.', $totalTime);
        if (sizeof($timeConvert) < 2) { //if we technically get a round value we add '0' so it looks better when displayed
            $timeConvert[1] = '0';
        }

        $timeConvert[1] = $timeConvert[1] * 0.6;

        if ($timeConvert[1] == 0) { //add '00' so it looks better when displayed
            $timeConvert[1] = '00';
        }

        $totalTime = $timeConvert[0] . ':' . $timeConvert[1];

        return [$totalTime, $nightHoursCalculate];
    }
}
