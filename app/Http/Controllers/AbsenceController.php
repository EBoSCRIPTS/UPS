<?php

namespace App\Http\Controllers;

use App\Models\EmployeeVacationsModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\AbsenceModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    //loads in the absence review page
    public function showAbsenceReview(Request $request): \Illuminate\View\View
    {
        $absences = $this->reviewAbsence($request);
        $reviewedAbsences = $this->getReviewedAbsence($request);

        return view('absence.absence_review', ['absences' => $absences, 'reviewedAbsences' => $reviewedAbsences]);
    }

    //returns absences for user himself
    public function userAbsences(): \Illuminate\View\View
    {
        $showSent = $this->getUserAbsence();
        $showReviewed = $this->getUserAbsenceReviewed();

        return view('absence.absence', ['showSent' => $showSent, 'showReviewed' => $showReviewed]);
    }

    public function addAbsence(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'mimes:pdf,docx,jpg,jpeg,png|max:10240',
        ]);

        if ($request->input('status') == null) {
            $request->merge(['status' => 'Sent']);
        }

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $fileName = time() . '.' . $request->user()->first_name . '-' . $request->user()->last_name . '.' . $attachment->getClientOriginalExtension();
            $attachment->move(storage_path('app/public/abs_req'), $fileName);
            $fileName = storage_path('/app/public/abs_req/' . $fileName);

            $fileName = basename($fileName);
        }

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $duration = $startDate->diffInDays($endDate);

        $absence = new AbsenceModel([
            'user_id' => $request->user()->id,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'duration' => $duration,
            'type' => $request->input('reason'),
            'reason' => $request->input('comment'),
            'status' => 'Sent',
            'attachment' => $fileName ?? null,
            'approver_id' => $request->input('approver_id'),
            'date_approved' => $request->input('date_approved'),
            'created_at' => Carbon::now(),
        ]);
        $absence->save();

        return redirect('/absence')->with('success', 'Absence added!');
    }

    /* users own requests */
    public function getUserAbsence(): Collection
    {
        return AbsenceModel::query()->where('user_id', Auth::user()->id)->where('status', 'Sent')->orderBy('created_at', 'desc')->get();
    }

    public function getUserAbsenceReviewed(): Collection
    {
        return AbsenceModel::query()->where('user_id', Auth::user()->id)->whereIn('status', ['Approve', 'Deny'])->orderBy('created_at', 'desc')->get();
    }

    /* absence review page(for all) */
    public function reviewAbsence(): Collection
    {
        return AbsenceModel::query()->where('status', 'Sent')->orderBy('created_at', 'desc')->get();
    }

    public function getReviewedAbsence(): Collection
    {
        return AbsenceModel::query()->where('status', 'Approve')->orWhere('status', 'Deny')->orderBy('created_at', 'desc')->get();
    }

    public function updateAbsence(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'is_paid' => 'sometimes|integer|in:1,2'
        ]);

        $absence = AbsenceModel::query()->where('id', $request->input('id'))->first();

        if ($absence->type == 'Vacation') { //if we approve the vacation lets put it in a separate table for vacations
            $logVacation = new EmployeeVacationsModel([
                'user_id' => $absence->user_id,
                'date_from' => $absence->start_date,
                'date_to' => $absence->end_date,
                'is_paid' => $request->input('is_paid') ?? 1,
                'absence_req_id' => $absence->id,
            ]);

            $logVacation->save();
        }

        $absence->update([
            'status' => $request->input('status'),
            'approver_id' => Auth::user()->id,
            'date_approved' => Carbon::now(),
        ]);

        return redirect('/absence/review')->with('success', 'Absence updated!');
    }

    public function deleteAbsence(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:req_absence,id',
        ]);

        if ($this->checkIfAbsenceReviewed($request->id)) {
            return redirect('/absence/review')->with('error', 'Absence cannot be deleted, it has already been reviewed!');
        }

        $absence = AbsenceModel::query()->find($request->id);

        $absence->delete();
        return redirect('/absence/')->with('success', 'Absence deleted!');
    }

    public function downloadAttachment(Request $request)
    {
        $absence = AbsenceModel::query()->find($request->absence_id);
        $attachmentLocation = $absence->attachment;

        return response()->download(storage_path('app/public/abs_req/' . $attachmentLocation));
    }

    public function checkIfAbsenceReviewed($absenceId): bool
    {
        $absence = AbsenceModel::query()->find($absenceId);

        if ($absence->status != 'Sent') {
            return true;
        }

        return false;
    }
}
