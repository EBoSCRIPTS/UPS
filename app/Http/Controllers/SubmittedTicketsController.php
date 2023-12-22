<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubmittedTicketsModel;

class SubmittedTicketsController extends Controller
{

    public function submitTicket(Request $request): RedirectResponse
    {
        $ticket = new SubmittedTicketsModel([
            'user_id' => Auth::user()->id,
            'ticket_title' => $request->input('title'),
            'ticket_text' => $request->input('description'),
            'department_id' => $request->input('department_id'),
            'is_registered' => 0,
        ]);

        $ticket->save();

        return back()->with('success', 'Ticket submitted!');
    }

    public function ticketRegistrationUpdate(Request $request): RedirectResponse
    {
        $ticket = SubmittedTicketsModel::query()->where('id', $request->ticket_id)->first();

        $ticket->update([
            'is_registered' => 1,
            'registered_by_user_id' => Auth::user()->id,
        ]);

        return back()->with('success', 'Ticket registered!');
    }
}
