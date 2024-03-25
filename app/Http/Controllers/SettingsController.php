<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NightHoursModel;

class SettingsController extends Controller
{

    public function nightHours()
    {
        $hoursArray = ['18:00', '19:00', '20:00', '21:00', '22:00',
            '23:00', '00:00', '01:00', '02:00', '03:00',
            '04:00', '05:00', '06:00', '07:00', '08:00'];
        return view('settings/night_settings', ['nightHoursStart' => NightHoursModel::query()->pluck('start')->first(),
            'nightHoursEnd' => NightHoursModel::query()->pluck('end')->first(), 'hoursArray' => $hoursArray]);
    }

    public function nightHoursUpdate(Request $request){
        $validated = $request->validate([
            'nightHoursStart' => 'required',
            'nightHoursEnd' => 'required',
        ]);
        NightHoursModel::query()->update([
            'start' => $request->input('nightHoursStart'),
            'end' => $request->input('nightHoursEnd'),
        ]);
        return redirect('/settings/night_hours');
    }
}
