<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TwoFactorAuth extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
        'used',
        'used_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'used' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isValid()
    {
        return !$this->used && !$this->isExpired();
    }

    public function markAsUsed()
    {
        $this->update([
            'used' => true,
            'used_at' => now()
        ]);
    }

    public static function generateCode($userId)
    {
        // Invalidate any existing codes for this user
        self::where('user_id', $userId)
            ->where('used', false)
            ->update(['used' => true]);

        // Generate new 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return self::create([
            'user_id' => $userId,
            'code' => $code,
            'expires_at' => now()->addMinutes(10) // Code expires in 10 minutes
        ]);
    }
}
