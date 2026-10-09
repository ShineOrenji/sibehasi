<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifPembayaran extends Notification
{
    use Queueable;

    protected $judul;
    protected $pesan;

    public function __construct($judul, $pesan)
    {
        $this->judul = $judul;
        $this->pesan = $pesan;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Hanya simpan ke database, tidak via email
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul' => $this->judul,
            'pesan' => $this->pesan,
            'waktu' => now()->diffForHumans(),
        ];
    }
}
