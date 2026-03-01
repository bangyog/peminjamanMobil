<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class LoanNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string  $title,
        public string  $message,
        public string  $url,
        public string  $type = 'info',   // info | success | warning | danger
        public ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
            'url'     => $this->url,
            'type'    => $this->type,
            'reason'  => $this->reason,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title'   => $this->title,
            'message' => $this->message,
            'url'     => $this->url,
            'type'    => $this->type,
            'reason'  => $this->reason,
        ]);
    }
}
