<?php

namespace App\Services;

use App\Events\AdminNotificationEvent;
use App\Models\Admin;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send a notification to one or more admins.
     *
     * @param  mixed  $admins
     * @param  string  $type
     * @param  string  $title
     * @param  string  $message
     * @param  array  $data
     * @return void
     */
    public function notify($admins, string $type, string $title, string $message, array $data = [])
    {
        $notification = $this->createNotification($type, $title, $message, $data);
        
        if ($admins instanceof Admin) {
            $admins = [$admins];
        } elseif (!is_iterable($admins)) {
            $admins = Admin::all();
        }

        foreach ($admins as $admin) {
            if ($admin instanceof Admin) {
                $admin->notify($notification);
                $this->broadcastNotification($admin, $notification);
            }
        }
    }

    /**
     * Create a new notification instance.
     *
     * @param  string  $type
     * @param  string  $title
     * @param  string  $message
     * @param  array  $data
     * @return \App\Notifications\AdminNotification
     */
    protected function createNotification(string $type, string $title, string $message, array $data = [])
    {
        $notificationClass = 'App\\Notifications\\' . ucfirst($type) . 'Notification';
        
        if (class_exists($notificationClass)) {
            return new $notificationClass($title, $message, $data);
        }

        return new AdminNotification($type, $title, $message, $data);
    }

    /**
     * Broadcast the notification to the admin in real-time.
     *
     * @param  \App\Models\Admin  $admin
     * @param  mixed  $notification
     * @return void
     */
    protected function broadcastNotification($admin, $notification)
    {
        if (config('notifications.realtime.enabled')) {
            $notificationData = $notification->toArray($admin);
            event(new AdminNotificationEvent($notificationData, $admin->id));
        }
    }

    /**
     * Get notification statistics.
     *
     * @param  \App\Models\Admin  $admin
     * @return array
     */
    public function getStats($admin)
    {
        return [
            'total' => $admin->notifications()->count(),
            'unread' => $admin->unreadNotifications()->count(),
            'read' => $admin->readNotifications()->count(),
        ];
    }

    /**
     * Get notification preferences for an admin.
     *
     * @param  \App\Models\Admin  $admin
     * @return array
     */
    public function getPreferences($admin)
    {
        return $admin->preferences->notification_preferences ?? config('notifications.default_preferences');
    }

    /**
     * Update notification preferences for an admin.
     *
     * @param  \App\Models\Admin  $admin
     * @param  array  $preferences
     * @return void
     */
    public function updatePreferences($admin, array $preferences)
    {
        $currentPreferences = $admin->preferences ?? [];
        $currentPreferences['notification_preferences'] = array_merge(
            $currentPreferences['notification_preferences'] ?? [],
            $preferences
        );

        $admin->preferences = $currentPreferences;
        $admin->save();
    }

    /**
     * Mark a notification as read.
     *
     * @param  \App\Models\Admin  $admin
     * @param  string  $notificationId
     * @return void
     */
    public function markAsRead($admin, $notificationId)
    {
        $notification = $admin->notifications()
            ->where('id', $notificationId)
            ->firstOrFail();

        $notification->markAsRead();
    }

    /**
     * Mark all notifications as read for an admin.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function markAllAsRead($admin)
    {
        $admin->unreadNotifications->markAsRead();
    }

    /**
     * Delete a notification.
     *
     * @param  \App\Models\Admin  $admin
     * @param  string  $notificationId
     * @return void
     */
    public function delete($admin, $notificationId)
    {
        $admin->notifications()
            ->where('id', $notificationId)
            ->delete();
    }

    /**
     * Delete all notifications for an admin.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function deleteAll($admin)
    {
        $admin->notifications()->delete();
    }

    /**
     * Get recent notifications for an admin.
     *
     * @param  \App\Models\Admin  $admin
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecent($admin, $limit = 10)
    {
        return $admin->notifications()
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get unread notifications for an admin.
     *
     * @param  \App\Models\Admin  $admin
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnread($admin, $limit = 10)
    {
        return $admin->unreadNotifications()
            ->latest()
            ->take($limit)
            ->get();
    }
}
