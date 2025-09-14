<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Get all notifications for the authenticated admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $notifications = $request->user('admin')
            ->notifications()
            ->latest()
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $request->user('admin')->unreadNotifications()->count(),
        ]);
    }

    /**
     * Get unread notifications for the authenticated admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unread(Request $request)
    {
        $notifications = $request->user('admin')
            ->unreadNotifications()
            ->latest()
            ->take($request->input('limit', 10))
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $request->user('admin')->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark a notification as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user('admin')
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read.',
            'unread_count' => $request->user('admin')->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark all notifications as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(Request $request)
    {
        $request->user('admin')->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read.',
            'unread_count' => 0,
        ]);
    }

    /**
     * Delete a notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $request->user('admin')
            ->notifications()
            ->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'Notification deleted successfully.',
            'unread_count' => $request->user('admin')->unreadNotifications()->count(),
        ]);
    }

    /**
     * Clear all notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearAll(Request $request)
    {
        $request->user('admin')->notifications()->delete();

        return response()->json([
            'message' => 'All notifications cleared.',
            'unread_count' => 0,
        ]);
    }

    /**
     * Get notification statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $user = $request->user('admin');

        return response()->json([
            'total' => $user->notifications()->count(),
            'unread' => $user->unreadNotifications()->count(),
            'read' => $user->readNotifications()->count(),
            'recent' => $user->notifications()->latest()->take(5)->get(),
        ]);
    }

    /**
     * Get notification preferences for the authenticated admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function preferences(Request $request)
    {
        $preferences = $request->user('admin')
            ->preferences
            ->notification_preferences ?? config('notifications.default_preferences');

        return response()->json([
            'preferences' => $preferences,
        ]);
    }

    /**
     * Update notification preferences for the authenticated admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'preferences' => 'required|array',
            'preferences.email' => 'sometimes|boolean',
            'preferences.database' => 'sometimes|boolean',
            'preferences.browser' => 'sometimes|boolean',
            'preferences.sound' => 'sometimes|boolean',
        ]);

        $user = $request->user('admin');
        $preferences = $user->preferences ?? [];
        $preferences['notification_preferences'] = array_merge(
            $preferences['notification_preferences'] ?? [],
            $validated['preferences']
        );

        $user->preferences = $preferences;
        $user->save();

        return response()->json([
            'message' => 'Notification preferences updated successfully.',
            'preferences' => $preferences['notification_preferences'],
        ]);
    }
}
