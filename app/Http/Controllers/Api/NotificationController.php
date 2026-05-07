<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends ApiController
{
    /**
     * Get all notifications for the authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()->notifications;
        return $this->success($notifications);
    }

    /**
     * Get unread notifications
     */
    public function unread(Request $request): JsonResponse
    {
        $notifications = $request->user()->unreadNotifications;
        return $this->success($notifications);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id, Request $request): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return $this->success(null, 'Notification marquée comme lue');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();
        return $this->success(null, 'Toutes les notifications ont été marquées comme lues');
    }
}
