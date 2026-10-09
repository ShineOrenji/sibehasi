<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->markAsRead();
        return back();
    }

    public function destroy($id)
    {
        // Menghapus notifikasi murni HANYA menghapus data di tabel notifications.
        // Data di tabel pembayaran_iuran dan buku_kas 100% aman.
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->delete();
        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}