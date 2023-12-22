<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Attachment;

class SendToAll extends Mailable
{
    use Queueable, SerializesModels;

    public $subject, $message;
    /**
     * Create a new message instance.
     */
    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function build()
    {
        return $this->subject($this->content['subject'])->view('mail_send_to_all', ['content' => $this->content])->attach($this->content['attachments']);
    }

}
