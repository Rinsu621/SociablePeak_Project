<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function storeTimeSpentNotification(Request $request)
    {
        $userId = auth()->id();

        // Check if the user has spent more than 5 hours (18000 seconds)
        if ($request->input('elapsed_time') >= 18000) {
            Notification::create([
                'user_id' => $userId,
                'type' => 'time_spent',
                'message' => 'You have spent more than 5 hours on the website!',
                'is_read' => false,
            ]);
        }

        // Retrieve notifications and unread count
        $notifications = Notification::where('user_id', $userId)
                                     ->orderBy('created_at', 'desc')
                                     ->limit(10)
                                     ->get();

        $unreadCount = Notification::where('user_id', $userId)
                                   ->where('is_read', false)
                                   ->count();

        return view('layout', compact('notifications', 'unreadCount'));
    }

    public function storeLikeNotification($postOwnerId, $likerName)
{
    if (auth()->id() !== $postOwnerId) {
        Notification::create([
            'user_id' => $postOwnerId,
            'type' => 'like',
            'message' => "$likerName liked your post.",
            'is_read' => false,
        ]);
    }
}

public function storeCommentNotification($postOwnerId, $commenterName)
{
    if (auth()->id() !== $postOwnerId) {
        Notification::create([
            'user_id' => $postOwnerId,
            'type' => 'comment',
            'message' => "$commenterName commented on your post.",
            'is_read' => false,
        ]);
    }
}

}
