# Social Login Setup Guide

## Environment Variables

Add the following environment variables to your `.env` file:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://your-domain.com/auth/google/callback

# Facebook OAuth Configuration
FACEBOOK_CLIENT_ID=your_facebook_app_id_here
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret_here
FACEBOOK_REDIRECT_URI=http://your-domain.com/auth/facebook/callback
```

## Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Google+ API
4. Go to "Credentials" and create OAuth 2.0 Client IDs
5. Add authorized redirect URIs:
   - `http://localhost:8000/auth/google/callback` (for local development)
   - `https://your-domain.com/auth/google/callback` (for production)
6. Copy the Client ID and Client Secret to your `.env` file

## Facebook OAuth Setup

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app
3. Add Facebook Login product to your app
4. In Facebook Login settings, add Valid OAuth Redirect URIs:
   - `http://localhost:8000/auth/facebook/callback` (for local development)
   - `https://your-domain.com/auth/facebook/callback` (for production)
5. Copy the App ID and App Secret to your `.env` file

## Database Migration

Run the migration to add social login fields to the users table:

```bash
php artisan migrate
```

## Features Implemented

- ✅ Google OAuth login
- ✅ Facebook OAuth login
- ✅ Automatic user creation for new social logins
- ✅ Existing user login with social accounts
- ✅ Beautiful UI with social login buttons
- ✅ Error handling and logging
- ✅ Email verification bypass for social logins

## Testing

1. Make sure your environment variables are set correctly
2. Run the migration: `php artisan migrate`
3. Visit your login page
4. Click on Google or Facebook login buttons
5. Complete the OAuth flow
6. You should be redirected to the dashboard upon successful login

## Troubleshooting

- Check Laravel logs in `storage/logs/laravel.log` for any OAuth errors
- Ensure your redirect URIs match exactly in both Google and Facebook console
- Make sure your domain is accessible and not blocked by firewalls
- Verify that the OAuth apps are in "Live" mode for production use
