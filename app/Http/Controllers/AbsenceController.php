<?php

namespace App\Http\Controllers;

use App\Models\EmployeeVacationsModel;
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

    public function showAbsenceReview(Request $request)
    {
        $absences = $this->reviewAbsence($request);
        $reviewedAbsences = $this->getReviewedAbsence($request);

        return view('absence.absence_review', ['absences' => $absences, 'reviewedAbsences' => $reviewedAbsences]);
    }

    public function userAbsences()
    {
        $showSent = $this->getUserAbsence();
        $showReviewed = $this->getUserAbsenceReviewed();

        return view('absence.absence', ['showSent' => $showSent, 'showReviewed' => $showReviewed]);
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

    /* users own requests */
    public function getUserAbsence()
    {
        return AbsenceModel::query()->where('user_id', Auth::id())->where('status', 'Sent')->orderBy('created_at', 'desc')->get();
    }

    public function getUserAbsenceReviewed()
    {
        return AbsenceModel::query()->where('user_id', Auth::id())->where('status', 'Approve')->orWhere('status', 'Deny')->orderBy('created_at', 'desc')->get();
    }

    /* absence review page */
    public function reviewAbsence()
    {
        return AbsenceModel::query()->where('status', 'Sent')->orderBy('created_at', 'desc')->get();
    }

    public function getReviewedAbsence()
    {
        return AbsenceModel::query()->where('status', 'Approve')->orWhere('status', 'Deny')->orderBy('created_at', 'desc')->get();
    }

    public function updateAbsence(Request $request)
    {
        $absence = AbsenceModel::query()->where('id', $request->input('id'))->first();

        if ($absence->type == 'Vacation'){
            $logVacation = new EmployeeVacationsModel([
                'employee_id' => $absence->user_id,
                'date_from' => $absence->start_date,
                'date_to' => $absence->end_date,
                'is_paid' => $request->input('is_paid') ?? 1,
                'absence_req_id' => $absence->id,
            ]);

            $logVacation->save();
        }

        $absence->update([
            'status' => $request->input('status'),
            'approver_id' => $request->input('approver_id'),
            'date_approved' => Carbon::now(),
        ]);

        return redirect('/absence/review')->with('success', 'Absence updated!');
    }

    public function deleteAbsence(Request $request)
    {
        $absence = AbsenceModel::query()->find($request->id);
        $absence->delete();
        return redirect('/absence/review')->with('success', 'Absence deleted!');
    }
}
