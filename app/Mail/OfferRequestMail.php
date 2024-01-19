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
    protected $attachment_name;
    protected $attachment_url;

    public function __construct($receiver, $sender, $subjects, $text, $signature, $attachment_name, $attachment_url) {
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->subjects = $subjects;
        $this->text = $text;
        $this->signature = $signature;
        $this->attachment_name = $attachment_name;
        $this->attachment_url = $attachment_url;
    }

    public function build() {
        return $this
            ->subject($this->subjects)
            ->to($this->receiver)
            ->from($this->sender)
            ->replyTo($this->sender)
            ->with(['text' => $this->text, 'signature' => $this->signature])
            ->attach($this->attachment_url, [
                'as' => $this->attachment_name, // Specify the name for the attachment
                'mime' => 'application/pdf', // Specify the MIME type of the attachment
            ])
            ->markdown('emails.offer_request_mail2');
    }
}
