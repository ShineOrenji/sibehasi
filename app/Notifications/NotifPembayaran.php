<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifPembayaran extends Notification
{
    use Queueable;

    protected $title;
    protected $message;

    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['database']; 
    }

    // Ganti toArray menjadi toDatabase, atau pastikan key-nya 'title' & 'message'
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,      // <--- Kunci utamanya di sini
            'message' => $this->message,  // <--- Kunci utamanya di sini
        ];
    }
}