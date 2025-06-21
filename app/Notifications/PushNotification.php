<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification
{
    use Queueable;

    private $data=array();
    public function __construct($data)
    {
        $this->data=$data;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }


    public function toDatabase(object $notifiable)
    {
        return $this->data;
    }
}
