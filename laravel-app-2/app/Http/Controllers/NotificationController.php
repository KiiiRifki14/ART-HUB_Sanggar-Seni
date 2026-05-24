<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark a specific notification as read and redirect to its target URL.
     */
    public function markAsRead($id)
    {
        // Pastikan hanya mencari notifikasi milik user yang sedang login (Mencegah IDOR)
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            
            // Redirect to the URL provided in the notification data, if it exists
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }

        // Fallback redirect
        return redirect()->back();
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}
