<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        $stats = [
            'total' => Notification::where('user_id', auth()->id())->count(),
            'unread' => Notification::where('user_id', auth()->id())->where('is_read', false)->count(),
            'high' => Notification::where('user_id', auth()->id())->where('priority', 'high')->count(),
            'read' => Notification::where('user_id', auth()->id())->where('is_read', true)->count(),
        ];

        return view('notifications.index', compact('notifications', 'stats'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }
}