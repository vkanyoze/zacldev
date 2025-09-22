# Mailtrap 2FA Setup Guide

## Overview
This guide will help you configure 2FA (Two-Factor Authentication) to use Mailtrap for sending verification codes via email.

## Step 1: Mailtrap Account Setup

### 1.1 Create Mailtrap Account
1. Go to [Mailtrap.io](https://mailtrap.io)
2. Sign up for a free account
3. Verify your email address

### 1.2 Create Inbox
1. Log into your Mailtrap dashboard
2. Click "Add Inbox" or "Create Inbox"
3. Name it "ZACL 2FA Testing" or similar
4. Note down the inbox credentials

## Step 2: Get Mailtrap SMTP Credentials

### 2.1 Access SMTP Settings
1. In your Mailtrap dashboard, go to your inbox
2. Click on "SMTP Settings" tab
3. Select "Laravel 9+" from the integration dropdown
4. Copy the credentials provided

### 2.2 Mailtrap SMTP Credentials
```
Host: sandbox.smtp.mailtrap.io
Port: 587
Username: [Your Mailtrap Username]
Password: [Your Mailtrap Password]
Encryption: TLS
```

## Step 3: Configure Laravel Environment

### 3.1 Update .env File
Add these settings to your `.env` file:

```env
# Mailtrap Configuration for 2FA
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@zacl.co.zm"
MAIL_FROM_NAME="ZACL Payment System"

# 2FA Configuration
2FA_ENABLED=true
```

### 3.2 Update .env.example
Add the same configuration to `.env.example` for reference.

## Step 4: Test Mailtrap Configuration

### 4.1 Test Email Sending
Run this command to test if emails are being sent to Mailtrap:

```bash
php artisan tinker
```

Then run:
```php
use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

$user = User::first();
Mail::to($user->email)->send(new TwoFactorCodeMail($user, '123456'));
```

### 4.2 Check Mailtrap Inbox
1. Go to your Mailtrap dashboard
2. Check your inbox
3. You should see the 2FA email with the code

## Step 5: Enable 2FA in Application

### 5.1 Update 2FA Configuration
The 2FA system is already configured. To enable it:

1. Set `2FA_ENABLED=true` in your `.env` file
2. Clear config cache: `php artisan config:clear`
3. Restart your application

### 5.2 Test 2FA Flow
1. Log in to your application
2. You should be redirected to the 2FA verification page
3. Check your Mailtrap inbox for the verification code
4. Enter the code to complete login

## Step 6: Production Setup (Optional)

### 6.1 For Production Environment
When ready for production, you can:

1. **Use Real SMTP Provider**: Replace Mailtrap with a real email service like:
   - SendGrid
   - Mailgun
   - Amazon SES
   - Gmail SMTP

2. **Update Environment Variables**:
```env
MAIL_HOST=your_production_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_production_username
MAIL_PASSWORD=your_production_password
MAIL_ENCRYPTION=tls
```

## Troubleshooting

### Common Issues:

1. **Emails not appearing in Mailtrap**:
   - Check SMTP credentials
   - Verify MAIL_MAILER=smtp in .env
   - Clear config cache: `php artisan config:clear`

2. **2FA not working**:
   - Check 2FA_ENABLED=true in .env
   - Verify middleware is applied
   - Check logs: `storage/logs/laravel.log`

3. **Email template issues**:
   - Verify email template exists: `resources/views/emails/two-factor-code.blade.php`
   - Check mail configuration

### Debug Commands:
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Check mail configuration
php artisan tinker
config('mail')
```

## Security Notes

1. **Never commit .env file** with real credentials
2. **Use environment-specific settings** for different environments
3. **Monitor email delivery** in production
4. **Set up proper logging** for email failures

## Next Steps

1. Test the complete 2FA flow
2. Customize email templates if needed
3. Set up monitoring for email delivery
4. Plan for production email service migration

The 2FA system is now ready to use with Mailtrap!
