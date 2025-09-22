<?php

namespace App\Http\Controllers;

use App\Models\TwoFactorAuth;
use App\Models\User;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class TwoFactorAuthController extends Controller
{
    /**
     * Show the 2FA verification form
     */
    public function showVerificationForm()
    {
        return view('auth.two-factor-verify');
    }

    /**
     * Send 2FA code to user's email
     */
    public function sendCode(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in first.']);
        }

        try {
            // Generate new 2FA code
            $twoFactorAuth = TwoFactorAuth::generateCode($user->id);
            
            \Log::info('Sending 2FA code', [
                'user_id' => $user->id,
                'email' => $user->email,
                'code' => $twoFactorAuth->code,
                'mail_driver' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host')
            ]);
            
            // Send email with code
            Mail::to($user->email)->send(new TwoFactorCodeMail($user, $twoFactorAuth->code));
            
            \Log::info('2FA code sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your email address.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('2FA Code sending failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code. Please check your email configuration.'
            ], 500);
        }
    }

    /**
     * Verify the 2FA code
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Please log in first.']);
        }

        // Find the most recent valid code for this user
        $twoFactorAuth = TwoFactorAuth::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$twoFactorAuth) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        // Mark code as used
        $twoFactorAuth->markAsUsed();

        // Store 2FA verification in session
        Session::put('2fa_verified', true);
        Session::put('2fa_verified_at', now());

        return redirect()->route('dashboards')->withSuccess('Two-factor authentication successful!');
    }

    /**
     * Resend 2FA code
     */
    public function resendCode()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in first.'
            ], 401);
        }

        try {
            // Generate new 2FA code
            $twoFactorAuth = TwoFactorAuth::generateCode($user->id);
            
            \Log::info('Resending 2FA code', [
                'user_id' => $user->id,
                'email' => $user->email,
                'code' => $twoFactorAuth->code
            ]);
            
            // Send email with code
            Mail::to($user->email)->send(new TwoFactorCodeMail($user, $twoFactorAuth->code));
            
            \Log::info('2FA code resent successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'New verification code sent to your email address.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('2FA Code resending failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code. Please check your email configuration.'
            ], 500);
        }
    }

    /**
     * Skip 2FA (for development/testing)
     */
    public function skip2FA()
    {
        if (app()->environment('local')) {
            Session::put('2fa_verified', true);
            Session::put('2fa_verified_at', now());
            
            return redirect()->route('dashboards')->withSuccess('2FA skipped (development mode)');
        }
        
        return back()->withErrors(['error' => '2FA cannot be skipped in production.']);
    }
}