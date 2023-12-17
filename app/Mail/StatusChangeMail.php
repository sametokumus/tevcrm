<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $title;
    protected $message;

    public function __construct($email, $title, $message) {
        $this->email = $email;
        $this->title = $title;
        $this->message = $message;
    }

    public function build() {
        return $this
            ->subject($this->title)
            ->to($this->email)
            ->with(['title' => $this->title, 'message' => $this->message])
            ->markdown('emails.welcome');
    }
}
