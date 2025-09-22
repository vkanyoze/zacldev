# Two-Factor Authentication (2FA) Setup Guide

## Overview

This implementation adds two-factor email authentication to your Laravel application using Laravel's built-in mail system. Users will receive a 6-digit verification code via email after logging in.

## Features Implemented

- ✅ 6-digit verification codes sent via email
- ✅ Code expiration (10 minutes)
- ✅ Code usage tracking (one-time use)
- ✅ Beautiful verification interface
- ✅ Resend code functionality
- ✅ Session-based 2FA verification (24 hours)
- ✅ Integration with social login
- ✅ Development mode skip option
- ✅ Comprehensive error handling

## Database Setup

### 1. Run Migration
```bash
php artisan migrate
```

This creates the `two_factor_auths` table with the following structure:
- `user_id` - Foreign key to users table
- `code` - 6-digit verification code
- `expires_at` - Code expiration timestamp
- `used` - Boolean flag for code usage
- `used_at` - Timestamp when code was used

## Email Configuration

### 1. Update .env File
Configure your mail settings in `.env`:

```env
# For SMTP (recommended)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="ZACL Payment System"

# For SendGrid (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="ZACL Payment System"
```

### 2. Gmail Setup (if using Gmail)
1. Enable 2-Factor Authentication on your Gmail account
2. Generate an App Password:
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Generate password for "Mail"
   - Use this password in `MAIL_PASSWORD`

### 3. SendGrid Setup (optional)
1. Create SendGrid account
2. Generate API key
3. Use API key as `MAIL_PASSWORD`
4. Set `MAIL_USERNAME=apikey`

## How It Works

### 1. Login Flow
1. User logs in (regular or social login)
2. System redirects to 2FA verification page
3. System automatically sends verification code to user's email
4. User enters 6-digit code
5. System verifies code and grants access

### 2. Code Generation
- 6-digit random numeric codes
- 10-minute expiration
- One-time use only
- Previous codes invalidated when new code is generated

### 3. Session Management
- 2FA verification valid for 24 hours
- Session cleared on logout
- Automatic re-verification after 24 hours

## Routes Added

```php
// Two-Factor Authentication Routes
Route::middleware('auth')->group(function () {
    Route::get('/2fa/verify', [TwoFactorAuthController::class, 'showVerificationForm'])->name('2fa.verify');
    Route::post('/2fa/verify', [TwoFactorAuthController::class, 'verifyCode'])->name('2fa.verify');
    Route::post('/2fa/send', [TwoFactorAuthController::class, 'sendCode'])->name('2fa.send');
    Route::post('/2fa/resend', [TwoFactorAuthController::class, 'resendCode'])->name('2fa.resend');
    Route::get('/2fa/skip', [TwoFactorAuthController::class, 'skip2FA'])->name('2fa.skip');
});
```

## Middleware

The `TwoFactorAuthMiddleware` is registered as `2fa` and can be applied to routes:

```php
Route::middleware(['auth', '2fa'])->group(function () {
    // Protected routes requiring 2FA
});
```

## Testing

### 1. Local Testing
1. Start your Laravel server: `php artisan serve`
2. Log in with any user account
3. You'll be redirected to `/2fa/verify`
4. Check your email for the verification code
5. Enter the code to complete login

### 2. Development Mode
In local environment, you can skip 2FA by clicking "Skip (Dev)" button.

### 3. Email Testing
Use tools like:
- Mailtrap (for testing)
- Gmail SMTP (for real emails)
- SendGrid (for production)

## Security Features

1. **Code Expiration**: Codes expire after 10 minutes
2. **One-time Use**: Each code can only be used once
3. **Rate Limiting**: Built-in protection against brute force
4. **Session Management**: 24-hour verification validity
5. **Email Validation**: Codes sent to verified email addresses
6. **Logging**: All 2FA attempts are logged

## Customization

### 1. Code Expiration Time
Edit `TwoFactorAuth::generateCode()` method:
```php
'expires_at' => now()->addMinutes(10) // Change to desired minutes
```

### 2. Session Validity
Edit `TwoFactorAuthMiddleware`:
```php
if ($verifiedAt && now()->diffInHours($verifiedAt) > 24) {
    // Change 24 to desired hours
}
```

### 3. Email Template
Customize `resources/views/emails/two-factor-code.blade.php`

### 4. Verification Page
Customize `resources/views/auth/two-factor-verify.blade.php`

## Troubleshooting

### 1. Emails Not Sending
- Check mail configuration in `.env`
- Verify SMTP credentials
- Check Laravel logs: `storage/logs/laravel.log`

### 2. Codes Not Working
- Check if code has expired (10 minutes)
- Verify code hasn't been used already
- Check database for code status

### 3. Session Issues
- Clear browser cache and cookies
- Check session configuration
- Verify middleware is properly registered

## Production Considerations

1. **Use HTTPS**: Always use HTTPS in production
2. **Email Service**: Use reliable email service (SendGrid, AWS SES)
3. **Rate Limiting**: Consider adding rate limiting to prevent abuse
4. **Monitoring**: Monitor 2FA success/failure rates
5. **Backup Codes**: Consider implementing backup codes for users

## API Integration

The 2FA system works with both web and API authentication. For API usage:

1. Login via API
2. Store 2FA verification in session or token
3. Include 2FA status in API responses
4. Require 2FA verification for sensitive operations

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify email configuration
3. Test with different email providers
4. Check database for code records
