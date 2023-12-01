<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenceModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function addAbsence(Request $request)
    {
        if($request->input('status') == null) {
            $request->merge(['status' => 'Sent']);
        }

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $duration = $startDate->diffInDays($endDate);

        $absence = new AbsenceModel([
            'user_id' => $request->input('user_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'duration' => $duration,
            'type' => $request->input('reason'),
            'reason' => $request->input('comment'),
            'status' => $request->input('status'),
            'sent_by' => $request->input('user_id'),
            'attachment' => $request->input('attachment'),
            'approver_id' => $request->input('approver_id'),
            'date_approved' => $request->input('date_approved'),
            'created_at' => Carbon::now(),
        ]);
        $absence->save();

        return redirect('/absence')->with('success', 'Absence added!');
    }

    public function getUserAbsence(Request $request)
    {
        $absences = AbsenceModel::query()->where('user_id', Auth::id())->get();
        return view('absence', ['absences' => $absences]);
    }
}
