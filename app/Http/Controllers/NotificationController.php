<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        return back();
    }

    public function markAllRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function index()
    {
        $notifications = Notification::latest('created_at')->paginate(20);

        return view('notifications.index', compact('notifications'));
    }
}