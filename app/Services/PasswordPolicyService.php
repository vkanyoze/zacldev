<?php

namespace App\Services;

use App\Models\PasswordPolicy;

class PasswordPolicyService
{
    /**
     * Validate password against current policy
     */
    public static function validatePassword($password)
    {
        $policy = PasswordPolicy::getCurrentPolicy();
        
        if (!$policy->enabled) {
            return ['valid' => true, 'errors' => []];
        }

        $errors = [];

        // Check minimum length
        if (strlen($password) < $policy->min_length) {
            $errors[] = "Password must be at least {$policy->min_length} characters long.";
        }

        // Check for uppercase letters
        if ($policy->require_uppercase && !preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }

        // Check for lowercase letters
        if ($policy->require_lowercase && !preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }

        // Check for numbers
        if ($policy->require_numbers && !preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number.";
        }

        // Check for special characters
        if ($policy->require_special_characters && !preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = "Password must contain at least one special character.";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Get password policy requirements for display
     */
    public static function getRequirements()
    {
        $policy = PasswordPolicy::getCurrentPolicy();
        
        if (!$policy->enabled) {
            return [];
        }

        $requirements = [];

        if ($policy->min_length > 0) {
            $requirements[] = "At least {$policy->min_length} characters";
        }

        if ($policy->require_uppercase) {
            $requirements[] = "One uppercase letter (A-Z)";
        }

        if ($policy->require_lowercase) {
            $requirements[] = "One lowercase letter (a-z)";
        }

        if ($policy->require_numbers) {
            $requirements[] = "One number (0-9)";
        }

        if ($policy->require_special_characters) {
            $requirements[] = "One special character (!@#$%^&*)";
        }

        return $requirements;
    }

    /**
     * Get password policy settings
     */
    public static function getSettings()
    {
        return PasswordPolicy::getCurrentPolicy();
    }

    /**
     * Update password policy settings
     */
    public static function updateSettings($data)
    {
        return PasswordPolicy::updatePolicy($data);
    }
}
