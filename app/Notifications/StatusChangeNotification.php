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
    protected $action_link;
    protected $notify_logo;

    public function __construct($title, $message, $action_link, $notify_logo)
    {
        $this->title = $title;
        $this->message = $message;
        $this->action_link = $action_link;
        $this->notify_logo = $notify_logo;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->message)
            ->from('mail-sender@sametokumus.com','CRM-X')
            ->line($this->message)
            ->action('Satış Detayına Git', $this->action_link)
            ->markdown('emails.status_change_notification-copy-old', [
                'title' => $this->title,
                'message' => $this->message,
                'notify_logo' => $this->notify_logo,
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
