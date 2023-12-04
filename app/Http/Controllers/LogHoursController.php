<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogHoursModel;
use App\Models\UserModel;
use Carbon\Carbon;

class LogHoursController extends Controller
{
    public function getCurrentMonth()
    {
       $month = Carbon::now()->monthName;
       $day = Carbon::now()->day;

       for($i = 0; $i < $day; $i++) {
           $dates[] = Carbon::now()->startOfMonth()->addDays($i)->format('Y-m-d');
       }

        return view('log_worked_hours', ['dates' => $dates, 'month' => $month]);
    }

    public function insertLoggedHours(Request $request)
    {
        for($i = 0; $i < 7; $i++) {
            $dates[] = Carbon::now()->startOfWeek()->addDays($i)->format('Y-m-d');
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

        return redirect('/loghours')->with('success', 'User created!');
    }
}
