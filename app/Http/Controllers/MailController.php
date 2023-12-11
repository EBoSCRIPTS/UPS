<?php

namespace App\Http\Controllers;

use App\Mail\SendToAll;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Mail;
use App\Models\UserModel;

class MailController extends Controller
{
    public function sendMail(Request $request)
    {
        $getAllEmails = UserModel::query()->select('email')->get()->toArray();

        $content = [
            'subject' => $request->input('subject'),
            'body' => $request->input('message'),
            'attachments' => $request->input('attachments'),
        ];

        foreach ($getAllEmails as $email) {
            Mail::to($email['email'])->send(new SendToAll($content));
        }
        return back()->with('success', 'Email sent successfully!');
    }
}
