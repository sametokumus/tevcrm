<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email) {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
//        return $this
//            ->subject('Aramıza Hoşgeldin!')
//            ->to($this->email)
//            ->with(['name' => $this->name])
//            ->markdown('emails.welcome');
        return $this
            ->subject('Thank you for subscribing to our newsletter')
            ->markdown('emails.welcome');
    }
}
