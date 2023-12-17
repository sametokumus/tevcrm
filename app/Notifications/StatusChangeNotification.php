<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StatusChangeNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;

    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->title)
            ->from('no-reply@semybrothers.com','SEMY BROTHERS')
            ->greeting($this->title)
            ->markdown('emails.status_change_notification', [
                'title' => $this->title,
                'message' => $this->message,
                // Add other variables as needed
            ]);
//            ->action('Şifrenizi değiştirmek için tıklayınız!', $url)
//            ->line('Alışverişlerinizde bizi tercih ettiğiniz için teşekkür ederiz!');
    }
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
