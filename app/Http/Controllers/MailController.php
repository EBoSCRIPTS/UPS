<?php

namespace App\Http\Controllers;

use App\Mail\SendToAll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Models\UserModel;

class MailController extends Controller
{
    public function sendMailToAll(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
            'attachments' => 'file',
        ]);

        $getAllEmails = UserModel::query()->select('email')->get()->toArray(); //get all user emails

        if ($request->hasFile('attachments')) { //check if we request has an attachment, if yes, store it for the sending process
            $file = $request->file('attachments');
            $localUpload = app_path() . '/public/uploads/mail_all';
            $ext = $file->getClientOriginalExtension();
            $file->move($localUpload, $file->getClientOriginalName() . '.' . $ext);
        }

        $content = [
            'subject' => $request->input('subject'),
            'body' => $request->input('message'),
            'attachments' => $localUpload . '/' . $file->getClientOriginalName() . '.' . $ext,
        ];

        foreach ($getAllEmails as $email) {
            Mail::to($email['email'])->send(new SendToAll($content));
            sleep(3); // sleep for 3 seconds after sending out each mail, otherwise we might overwhelm the mail server
        }

        File::delete($localUpload . '/' . $file->getClientOriginalName() . '.' . $ext); //get rid of the attached email file

        return back()->with('success', 'Email sent successfully!');
    }
}
