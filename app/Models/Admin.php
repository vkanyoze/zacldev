<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasAdminNotifications;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasAdminNotifications;

    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the admin's avatar URL.
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : $this->defaultAvatarUrl();
    }

    /**
     * Get the default avatar URL.
     *
     * @return string
     */
    protected function defaultAvatarUrl()
    {
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        return [$this->email => $this->name];
    }

    /**
     * Route notifications for the database channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array
     */
    public function routeNotificationForDatabase($notification)
    {
        return $notification->toArray($this);
    }

    /**
     * Route notifications for the broadcast channel.
     *
     * @return array
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'admin.notifications.' . $this->id;
    }

    /**
     * Get the admin's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Check if the admin has unread notifications.
     *
     * @return bool
     */
    public function hasUnreadNotifications()
    {
        return $this->unreadNotifications()->exists();
    }

    /**
     * Mark the admin as online.
     *
     * @return void
     */
    public function markAsOnline()
    {
        $this->update([
            'last_seen_at' => now(),
        ]);
    }

    /**
     * Check if the admin is online.
     *
     * @return bool
     */
    public function isOnline()
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }
}
