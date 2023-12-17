<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $receiver;
    protected $sender;
    protected $subjects;
    protected $text;

    public function __construct($receiver, $sender, $subjects, $text) {
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->subjects = $subjects;
        $this->text = $text;
    }

    public function build() {
        return $this
            ->subject($this->subjects)
            ->to($this->receiver)
            ->from($this->sender)
            ->with(['text' => $this->text])
            ->markdown('emails.offer_request_mail');
    }
}
