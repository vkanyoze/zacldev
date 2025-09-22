# Firebase Authentication Setup Guide

## Overview
This guide will help you set up Firebase Authentication for social login in your Laravel application.

## Prerequisites
- Firebase project created
- Firebase Authentication enabled
- Social providers configured in Firebase Console

## Step 1: Create Firebase Project

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Click "Create a project" or "Add project"
3. Enter project name (e.g., "zacldev-auth")
4. Enable Google Analytics (optional)
5. Click "Create project"

## Step 2: Enable Authentication

1. In Firebase Console, go to "Authentication" > "Sign-in method"
2. Enable the following providers:
   - **Email/Password**: Enable
   - **Google**: Enable and configure
   - **Facebook**: Enable and configure
   - **GitHub**: Enable and configure (optional)

## Step 3: Configure Social Providers

### Google Setup
1. In Firebase Console > Authentication > Sign-in method > Google
2. Click "Enable"
3. Add your domain to authorized domains
4. Note down the Web SDK configuration

### Facebook Setup
1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app
3. Add Facebook Login product
4. Configure OAuth redirect URIs
5. In Firebase Console > Authentication > Sign-in method > Facebook
6. Enter your Facebook App ID and App Secret

## Step 4: Get Firebase Configuration

1. In Firebase Console, go to Project Settings (gear icon)
2. Scroll down to "Your apps" section
3. Click "Web app" icon (</>) to add a web app
4. Register your app with a nickname
5. Copy the Firebase configuration object

## Step 5: Update Environment Variables

Add these to your `.env` file:

```env
# Firebase Configuration
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_API_KEY=your-api-key
FIREBASE_AUTH_DOMAIN=your-project-id.firebaseapp.com
FIREBASE_STORAGE_BUCKET=your-project-id.appspot.com
FIREBASE_MESSAGING_SENDER_ID=your-sender-id
FIREBASE_APP_ID=your-app-id

# Firebase Authentication Settings
FIREBASE_AUTH_ENABLED=true
FIREBASE_AUTO_CREATE_USERS=true
FIREBASE_SYNC_USER_DATA=true
FIREBASE_WEBHOOK_SECRET=your-webhook-secret

# Social Providers
FIREBASE_GOOGLE_ENABLED=true
FIREBASE_FACEBOOK_ENABLED=true
FIREBASE_TWITTER_ENABLED=false
FIREBASE_GITHUB_ENABLED=true
```

## Step 6: Update Frontend Configuration

Update `resources/js/firebase-config.js` with your Firebase configuration:

```javascript
const firebaseConfig = {
    apiKey: "your-api-key",
    authDomain: "your-project-id.firebaseapp.com",
    projectId: "your-project-id",
    storageBucket: "your-project-id.appspot.com",
    messagingSenderId: "your-sender-id",
    appId: "your-app-id"
};
```

## Step 7: Run Database Migration

```bash
php artisan migrate
```

## Step 8: Build Frontend Assets

```bash
npm run build
```

## Step 9: Test the Integration

1. Visit your login page
2. Click on Google or Facebook login buttons
3. Complete the OAuth flow
4. Verify user is created in your Laravel database

## Features Included

✅ **Social Login Providers**: Google, Facebook, GitHub, Twitter  
✅ **Email/Password Authentication**  
✅ **User Synchronization**: Firebase users sync with Laravel database  
✅ **Backward Compatibility**: Existing Laravel Socialite still works  
✅ **Security**: JWT token verification  
✅ **User Management**: Automatic user creation and updates  

## Troubleshooting

### Common Issues

1. **"Firebase Auth not available"**
   - Check if Firebase SDK is properly loaded
   - Verify environment variables are set
   - Check browser console for errors

2. **"Token verification failed"**
   - Ensure Firebase project ID is correct
   - Check if authentication is enabled in Firebase Console
   - Verify social providers are configured

3. **"CORS errors"**
   - Add your domain to Firebase authorized domains
   - Check if HTTPS is required for your domain

### Debug Mode

Enable debug logging by adding to your `.env`:
```env
LOG_LEVEL=debug
```

## Security Notes

- Firebase handles OAuth security
- JWT tokens are verified using Google's public keys
- User data is synchronized securely
- No sensitive data is stored in frontend

## Support

For issues with Firebase setup, refer to:
- [Firebase Documentation](https://firebase.google.com/docs/auth)
- [Firebase Console](https://console.firebase.google.com/)
- [Laravel Documentation](https://laravel.com/docs)

## Cost Information

Firebase Authentication is **FREE** for:
- Up to 50,000 Monthly Active Users (MAUs)
- All social login providers
- Email/password authentication
- Multi-factor authentication

Only phone/SMS authentication is billed separately.
