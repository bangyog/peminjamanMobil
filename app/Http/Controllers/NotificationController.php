<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\LoanNotification;
use App\Models\User;


class NotificationController extends Controller
{
    // Fetch untuk bell icon (AJAX)
    public function fetch()
    {
            /** @var \App\Models\User $user */
        $user = Auth::user();

        $notifications = $user->notifications()
            ->latest()
            ->take(15)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'data'       => $n->data,
                'read_at'    => $n->read_at,
                'created_at' => $n->created_at,
            ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $user->unreadNotifications()->count(),
        ]);
    }

    // Mark satu notif sebagai dibaca
    public function markAsRead(string $id)
    {
            /** @var \App\Models\User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    // Mark semua dibaca
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    // Halaman semua notifikasi
    public function index()
    {
            /** @var \App\Models\User $user */
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(20);

        // Mark semua sebagai dibaca saat buka halaman
        Auth::user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }
}
