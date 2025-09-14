<?php

namespace App\Traits;

use App\Models\Admin;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;

/**
 * Trait HasAdminNotifications
 * 
 * This trait provides methods to send notifications to admin users.
 */
trait HasAdminNotifications
{
    /**
     * Send a notification to all admins.
     *
     * @param  mixed  $notification
     * @return void
     */
    public static function notifyAll($notification)
    {
        $admins = Admin::all();
        Notification::send($admins, $notification);
    }

    /**
     * Send a notification to specific admin roles.
     *
     * @param  string|array  $roles
     * @param  mixed  $notification
     * @return void
     */
    public static function notifyRoles($roles, $notification)
    {
        $admins = Admin::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', (array) $roles);
        })->get();

        Notification::send($admins, $notification);
    }

    /**
     * Send a success notification.
     *
     * @param  string  $title
     * @param  string  $message
     * @param  array  $data
     * @param  Admin|array|null  $admins
     * @return void
     */
    public static function notifySuccess($title, $message, $data = [], $admins = null)
    {
        $notification = AdminNotification::success($title, $message, $data);
        self::sendNotification($notification, $admins);
    }

    /**
     * Send an error notification.
     *
     * @param  string  $title
     * @param  string  $message
     * @param  array  $data
     * @param  Admin|array|null  $admins
     * @return void
     */
    public static function notifyError($title, $message, $data = [], $admins = null)
    {
        $notification = AdminNotification::error($title, $message, $data);
        self::sendNotification($notification, $admins);
    }

    /**
     * Send a payment notification.
     *
     * @param  string  $title
     * @param  string  $message
     * @param  array  $data
     * @param  Admin|array|null  $admins
     * @return void
     */
    public static function notifyPayment($title, $message, $data = [], $admins = null)
    {
        $notification = AdminNotification::payment($title, $message, $data);
        self::sendNotification($notification, $admins);
    }

    /**
     * Send a user-related notification.
     *
     * @param  string  $title
     * @param  string  $message
     * @param  array  $data
     * @param  Admin|array|null  $admins
     * @return void
     */
    public static function notifyUser($title, $message, $data = [], $admins = null)
    {
        $notification = AdminNotification::user($title, $message, $data);
        self::sendNotification($notification, $admins);
    }

    /**
     * Send a system notification.
     *
     * @param  string  $title
     * @param  string  $message
     * @param  array  $data
     * @param  Admin|array|null  $admins
     * @return void
     */
    public static function notifySystem($title, $message, $data = [], $admins = null)
    {
        $notification = AdminNotification::system($title, $message, $data);
        self::sendNotification($notification, $admins);
    }

    /**
     * Send the notification to the specified admins or all admins if none specified.
     *
     * @param  mixed  $notification
     * @param  Admin|array|null  $admins
     * @return void
     */
    protected static function sendNotification($notification, $admins = null)
    {
        if ($admins === null) {
            self::notifyAll($notification);
            return;
        }

        if (!is_array($admins)) {
            $admins = [$admins];
        }

        foreach ($admins as $admin) {
            if ($admin instanceof Admin) {
                $admin->notify($notification);
            }
        }
    }

    /**
     * Mark all notifications as read for the admin.
     *
     * @return void
     */
    public function markAllNotificationsAsRead()
    {
        $this->unreadNotifications->markAsRead();
    }

    /**
     * Get the unread notifications count for the admin.
     *
     * @return int
     */
    public function getUnreadNotificationsCount()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Get the admin's notifications.
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNotifications($limit = 15)
    {
        return $this->notifications()
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get the admin's unread notifications.
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnreadNotifications($limit = 10)
    {
        return $this->unreadNotifications()
            ->latest()
            ->take($limit)
            ->get();
    }
}
