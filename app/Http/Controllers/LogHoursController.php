<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInformationModel;
use Illuminate\Http\Request;
use App\Models\LogHoursModel;
use App\Models\LoggedHoursSubmittedModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogHoursController extends Controller
{
    public function getCurrentMonth()
    {
       $month = Carbon::now()->monthName;
       $day = Carbon::now()->day;

        $userLogs = LogHoursModel::query()
            ->where('user_id', Auth::user()->id)
            ->where('date', '<=', Carbon::now())
            ->where('date', '>=', Carbon::now()->startOfMonth())
            ->get();

       if ($this->checkIfClosedMonth(Auth::user()->id, $month))
       {
           $datesToFill = [];

           return view('log_worked_hours', ['dates' => $datesToFill, 'month' => $month, 'userLogs' => $userLogs, 'closed' => true]);
       }


       for($i = 0; $i < $day; $i++) {
           $dates[] = Carbon::now()->startOfMonth()->addDays($i)->format('Y-m-d');
       }

        //display logged hours
        $getDates = $this->getUserAlreadyLoggedHours();

       $datesToFill = array_diff($dates, $getDates);


       return view('log_worked_hours', ['dates' => $datesToFill, 'month' => $month, 'userLogs' => $userLogs]);
    }

    public function insertLoggedHours(Request $request)
    {
        if($this->checkIfClosedMonth(Auth::user()->id, $request->input('month')))
        {
            return redirect('/');
        }

        if ($request->input('month') == Carbon::now()->subMonth()->monthName){
            for ($i = 0; $i < Carbon::now()->subMonth()->daysInMonth; $i++) {
                $dates[] = Carbon::now()->subMonth()->startOfMonth()->addDays($i)->format('Y-m-d');
            }
        }
        else {
            for ($i = 0; $i < Carbon::now()->day; $i++) {
                $dates[] = Carbon::now()->startOfMonth()->addDays($i)->format('Y-m-d');
            }
        }

        foreach ($dates as $date)
        {
            if($request->input($date.'_start_time') != null && $request->input($date.'_end_time') != null) {
                $loggedHours = new LogHoursModel([
                    'user_id' => $request->input('user_id'),
                    'start_time' => $request->input($date.'_start_time'),
                    'end_time' => $request->input($date.'_end_time'),
                    'total_hours' => $request->input($date.'_total_hours'),
                    'date' => $request->input($date.'_date'),
                    ]);
                $loggedHours->save();
            }
        }

        return redirect('/loghours')->with('success', 'Hours inserted!');
    }

    public function deleteLoggedHours(Request $request)
    {
        $loggedHours = LogHoursModel::query()->find($request->input('id'));
        $loggedHours->delete();

        return redirect('/loghours')->with('success', 'Hours deleted!');
    }

    private function getUserAlreadyLoggedHours()
    {
        return LogHoursModel::query()->where('user_id', Auth::user()->id)->pluck('date')->toArray();
    }

    public function getPreviousMonth()
    {
        if($this->checkIfClosedMonth(Auth::user()->id, Carbon::now()->subMonth()->monthName))
        {
            return redirect('/');
        }

        $month = Carbon::now()->subMonth()->monthName;
        $day = Carbon::now()->subMonth()->daysInMonth;

        for($i = 0; $i < $day; $i++) {
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

    public function closeMonthlyReport(Request $request)
    {
        if ($request->input('month') == Carbon::now()->monthName) {
            $logHours = LogHoursModel::query()
                ->where('user_id', Auth::user()->id)
                ->where('date', '<=', Carbon::now())
                ->where('date', '>=', Carbon::now()->startOfMonth())
                ->pluck('total_hours')
                ->toArray();
        }

        else if ($request->input('month') == Carbon::now()->subMonth()->monthName) {
            $logHours = LogHoursModel::query()
                ->where('user_id', Auth::user()->id)
                ->where('date', '<=', Carbon::now()->subMonth()->endOfMonth())
                ->where('date', '>=', Carbon::now()->subMonth()->startOfMonth())
                ->pluck('total_hours')
                ->toArray();
        }

            $totalHours = [];

            for ($i = 0; $i < sizeof($logHours); $i++)
            {
                $time = $logHours[$i];
                $time = explode(':', $time);
                $hoursToSeconds = $time[0] * 3600;
                $minutesToSeconds = $time[1] * 60;
                $time = ceil(($hoursToSeconds + $minutesToSeconds) / 3600);
                $totalHours[$i] = $time;
            }

            $totalHours = array_sum($totalHours);

            $insertHours = new LoggedHoursSubmittedModel([
                'user_id' => Auth::user()->id,
                'total_hours' => $totalHours,
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

        if($findUser == null) {
            return false;
        }

        return true;
    }

    public function getSubmittedHours(Request $request)
    {
        $submittedHours = LoggedHoursSubmittedModel::query()
            ->where('is_confirmed', 1)
            ->get();

        $monthlyHours = [];

        foreach($submittedHours as $submittedHour) {
            $monthlyExpected = EmployeeInformationModel::query()->where('user_id', $submittedHour->user_id)->pluck('monthly_hours')->first();
            $monthlyHours[$submittedHour->user_id] = $monthlyExpected;
        };

        return view('loghours_submit_review', ['submits' => $submittedHours, 'monthlyHours' => $monthlyHours]);
    }

    public function submitHoursReview(Request $request)
    {
        $submittedHours = LoggedHoursSubmittedModel::query()
            ->where('id', $request->input('id'))
            ->first();

        if($request->has('Approve')) {
            $submittedHours->update([
                'is_confirmed' => 2
            ]);
        }

        if($request->has('Reject')) {
            $submittedHours->delete();
        }

        return back();
    }
}
