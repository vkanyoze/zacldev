<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordPolicy extends Model
{
    use HasFactory;

    protected $table = 'password_policy_settings';

    protected $fillable = [
        'require_uppercase',
        'require_lowercase',
        'require_numbers',
        'require_special_characters',
        'min_length',
        'enabled'
    ];

    protected $casts = [
        'require_uppercase' => 'boolean',
        'require_lowercase' => 'boolean',
        'require_numbers' => 'boolean',
        'require_special_characters' => 'boolean',
        'enabled' => 'boolean'
    ];

    /**
     * Get the current password policy settings
     */
    public static function getCurrentPolicy()
    {
        return self::first() ?? self::create([
            'require_uppercase' => false,
            'require_lowercase' => false,
            'require_numbers' => false,
            'require_special_characters' => false,
            'min_length' => 8,
            'enabled' => false
        ]);
    }

    /**
     * Update password policy settings
     */
    public static function updatePolicy($data)
    {
        $policy = self::getCurrentPolicy();
        $policy->update($data);
        return $policy;
    }
}
