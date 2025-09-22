<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip 2FA for guests
        if (!Auth::check()) {
            return $next($request);
        }

        // Skip 2FA for 2FA-related routes
        if ($request->routeIs('2fa.*') || $request->routeIs('login') || $request->routeIs('register*')) {
            return $next($request);
        }

        // Check if 2FA is enabled
        if (!config('2fa.enabled', false)) {
            // 2FA is disabled, set as verified
            if (!Session::get('2fa_verified', false)) {
                Session::put('2fa_verified', true);
                Session::put('2fa_verified_at', now());
            }
            return $next($request);
        }

        // 2FA is enabled, check verification
        if (!Session::get('2fa_verified', false)) {
            return redirect()->route('2fa.verify');
        }

        // Check if 2FA verification is still valid
        $verifiedAt = Session::get('2fa_verified_at');
        $validityHours = config('2fa.session.validity_hours', 24);
        if ($verifiedAt && now()->diffInHours($verifiedAt) > $validityHours) {
            Session::forget(['2fa_verified', '2fa_verified_at']);
            return redirect()->route('2fa.verify');
        }

        return $next($request);
    }
}
