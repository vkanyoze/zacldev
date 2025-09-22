<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // User exists, log them in
                Auth::login($user);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(24)), // Random password since they'll use social login
                    'is_email_verified' => 1, // Google emails are verified
                    'google_id' => $googleUser->getId(),
                ]);
                
                Auth::login($user);
            }
            
            // Set 2FA as verified for now (bypass 2FA temporarily)
            session()->put('2fa_verified', true);
            session()->put('2fa_verified_at', now());
            
            // Redirect directly to dashboard
            return redirect()->route('dashboards')->withSuccess('Successfully signed in with Google!');
            
        } catch (\Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['error' => 'Unable to login with Google. Please try again.']);
        }
    }

    /**
     * Redirect to Facebook OAuth
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook OAuth callback
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            // Check if user already exists
            $user = User::where('email', $facebookUser->getEmail())->first();
            
            if ($user) {
                // User exists, log them in
                Auth::login($user);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'password' => Hash::make(Str::random(24)), // Random password since they'll use social login
                    'is_email_verified' => 1, // Facebook emails are verified
                    'facebook_id' => $facebookUser->getId(),
                ]);
                
                Auth::login($user);
            }
            
            // Set 2FA as verified for now (bypass 2FA temporarily)
            session()->put('2fa_verified', true);
            session()->put('2fa_verified_at', now());
            
            // Redirect directly to dashboard
            return redirect()->route('dashboards')->withSuccess('Successfully signed in with Facebook!');
            
        } catch (\Exception $e) {
            \Log::error('Facebook OAuth error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['error' => 'Unable to login with Facebook. Please try again.']);
        }
    }
}
