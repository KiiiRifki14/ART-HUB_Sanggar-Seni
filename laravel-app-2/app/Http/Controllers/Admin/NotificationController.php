<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark a specific notification as read and redirect to its target URL.
     */
    public function markAsRead($id)
    {
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
}
