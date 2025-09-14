<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The notification type.
     *
     * @var string
     */
    public $type;

    /**
     * The notification title.
     *
     * @var string
     */
    public $title;

    /**
     * The notification message.
     *
     * @var string
     */
    public $message;

    /**
     * Additional data for the notification.
     *
     * @var array
     */
    public $data;

    /**
     * Create a new notification instance.
     *
     * @param  string  $type
     * @param  string  $title
     * @param  string  $message
     * @param  array  $data
     * @return void
     */
    public function __construct($type, $title, $message, $data = [])
    {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->title)
                    ->line($this->message)
                    ->action('View Notification', url('/admin/notifications'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data,
            'url' => $this->data['url'] ?? null,
            'icon' => $this->getIcon(),
            'time' => now()->toDateTimeString(),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data,
            'url' => $this->data['url'] ?? null,
            'icon' => $this->getIcon(),
            'time' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Get the notification icon based on type.
     *
     * @return string
     */
    protected function getIcon()
    {
        $icons = [
            'success' => 'check-circle',
            'error' => 'exclamation-circle',
            'warning' => 'exclamation-triangle',
            'info' => 'info-circle',
            'payment' => 'credit-card',
            'user' => 'user',
            'system' => 'cog',
        ];

        return $icons[$this->type] ?? 'bell';
    }
}
