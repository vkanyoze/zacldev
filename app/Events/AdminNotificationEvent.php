<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The notification data.
     *
     * @var array
     */
    public $notification;

    /**
     * The admin user ID.
     *
     * @var int
     */
    protected $adminId;

    /**
     * Create a new event instance.
     *
     * @param  array  $notification
     * @param  int  $adminId
     * @return void
     */
    public function __construct($notification, $adminId)
    {
        $this->notification = $notification;
        $this->adminId = $adminId;
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('admin.notifications.' . $this->adminId);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'notification.received';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'notification' => $this->notification,
            'unread_count' => $this->unreadCount()
        ];
    }

    /**
     * Get the unread notifications count.
     *
     * @return int
     */
    protected function unreadCount()
    {
        return \App\Models\Admin::find($this->adminId)
            ->unreadNotifications()
            ->count();
    }
}
