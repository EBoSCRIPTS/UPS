<?php

namespace App\Http\Controllers;

use App\Mail\SendToAll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Mail;
use App\Models\UserModel;

class MailController extends Controller
{
    public function sendMailToAll(Request $request): RedirectResponse
    {
        $getAllEmails = UserModel::query()->select('email')->get()->toArray();

        $file = $request->file('attachments');
        $localUpload = app_path() . '/public/mail_all';
        $ext = $file->getClientOriginalExtension();
        $file->move($localUpload, $file->getClientOriginalName() . '.' . $ext);

        $content = [
            'subject' => $request->input('subject'),
            'body' => $request->input('message'),
            'attachments' => $localUpload . '/' . $file->getClientOriginalName() . '.' . $ext,
        ];

        foreach ($getAllEmails as $email) {
            Mail::to($email['email'])->send(new SendToAll($content));
            sleep(3); // sleep for 3 seconds after sending out each mail, otherwise we might overwhelm the mail server
        }

        return back()->with('success', 'Email sent successfully!');
    }
}
