# Activate 2FA Redirection Guide

## Overview
This guide will help you activate 2FA (Two-Factor Authentication) so that users are redirected to the 2FA verification page after successful login, before accessing the dashboard.

## ✅ **Current Status**
The 2FA system is already implemented and ready to activate. Here's what's been set up:

### **1. 2FA System Components**
- ✅ **Database**: `two_factor_auths` table created
- ✅ **Model**: `TwoFactorAuth` model with code generation
- ✅ **Controller**: `TwoFactorAuthController` with verification logic
- ✅ **Middleware**: `TwoFactorAuthMiddleware` for route protection
- ✅ **Email**: `TwoFactorCodeMail` with Mailtrap integration
- ✅ **Views**: 2FA verification form ready
- ✅ **Routes**: 2FA routes configured

### **2. Route Protection**
- ✅ **Dashboard**: Protected with `['auth', '2fa']` middleware
- ✅ **Account Routes**: Protected with `['auth', '2fa']` middleware  
- ✅ **Payment Routes**: Protected with `['auth', '2fa']` middleware

## 🚀 **How to Activate 2FA**

### **Step 1: Enable 2FA in Environment**
Add this to your `.env` file:
```env
2FA_ENABLED=true
```

### **Step 2: Configure Mailtrap (if not done already)**
Add these to your `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@zacl.co.zm"
MAIL_FROM_NAME="ZACL Payment System"
```

### **Step 3: Clear Configuration Cache**
```bash
php artisan config:clear
```

### **Step 4: Test the 2FA Flow**
```bash
# Test email sending
php artisan test:mailtrap-2fa

# Check your Mailtrap inbox at https://mailtrap.io/inboxes
```

## 🔄 **How 2FA Flow Works**

### **1. User Login Process**
1. User enters credentials on login page
2. System authenticates user
3. **If 2FA is enabled**: User is redirected to `/2fa/verify`
4. **If 2FA is disabled**: User goes directly to dashboard

### **2. 2FA Verification Process**
1. User sees 2FA verification form
2. System automatically sends 6-digit code to user's email
3. User enters the code
4. System validates the code
5. If valid: User is redirected to dashboard
6. If invalid: User sees error message

### **3. Protected Routes**
Once 2FA is enabled, these routes require 2FA verification:
- `/dashboard` - Main dashboard
- `/account/update/` - Update email
- `/account/passwords/` - Change password
- `/payments/*` - All payment-related pages

## 🧪 **Testing 2FA**

### **Test 1: Enable 2FA**
1. Set `2FA_ENABLED=true` in `.env`
2. Run `php artisan config:clear`
3. Try to login
4. You should be redirected to 2FA verification page

### **Test 2: Verify Email Sending**
1. Check your Mailtrap inbox
2. You should see the 2FA email with 6-digit code
3. Enter the code to complete login

### **Test 3: Test Protected Routes**
1. Try accessing `/dashboard` directly
2. You should be redirected to 2FA verification
3. Complete 2FA to access dashboard

## 🔧 **Troubleshooting**

### **Issue: 2FA not redirecting after login**
**Solution:**
1. Check `2FA_ENABLED=true` in `.env`
2. Run `php artisan config:clear`
3. Check logs: `storage/logs/laravel.log`

### **Issue: Email not sending**
**Solution:**
1. Verify Mailtrap credentials in `.env`
2. Test with: `php artisan test:mailtrap-2fa`
3. Check Mailtrap inbox

### **Issue: 2FA code not working**
**Solution:**
1. Check code expiry (10 minutes)
2. Verify code format (6 digits)
3. Check database: `two_factor_auths` table

## 📊 **2FA Configuration Options**

### **Environment Variables**
```env
# Enable/disable 2FA
2FA_ENABLED=true

# Email settings
MAIL_FROM_ADDRESS="noreply@zacl.co.zm"
MAIL_FROM_NAME="ZACL Payment System"
```

### **Code Settings** (in `config/2fa.php`)
- **Length**: 6 digits
- **Expiry**: 10 minutes
- **Max Attempts**: 3
- **Session Validity**: 24 hours

## 🎯 **Next Steps After Activation**

1. **Test Complete Flow**: Login → 2FA → Dashboard
2. **Monitor Logs**: Check for any errors
3. **User Training**: Inform users about 2FA process
4. **Production Setup**: Configure real email service for production

## 🚨 **Important Notes**

- ✅ **Development**: 2FA can be skipped in local environment
- ✅ **Production**: 2FA cannot be skipped
- ✅ **Security**: Codes expire in 10 minutes
- ✅ **Session**: 2FA verification valid for 24 hours

## 📝 **Quick Commands**

```bash
# Enable 2FA
echo "2FA_ENABLED=true" >> .env
php artisan config:clear

# Test 2FA email
php artisan test:mailtrap-2fa

# Check logs
tail -f storage/logs/laravel.log

# Disable 2FA (if needed)
echo "2FA_ENABLED=false" >> .env
php artisan config:clear
```

The 2FA system is now ready to activate! Just set `2FA_ENABLED=true` in your `.env` file and clear the config cache.
