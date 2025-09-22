<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;

class FirebaseAuthController extends Controller
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * Verify Firebase ID token and authenticate user
     */
    public function verifyToken(Request $request)
    {
        try {
            $request->validate([
                'idToken' => 'required|string',
                'provider' => 'nullable|string'
            ]);

            $idToken = $request->input('idToken');
            $provider = $request->input('provider', 'email');

            // Verify the Firebase ID token using Google's public keys
            $decodedToken = $this->verifyFirebaseToken($idToken);
            $uid = $decodedToken['sub'] ?? null;
            $email = $decodedToken['email'] ?? null;
            $name = $decodedToken['name'] ?? null;
            $picture = $decodedToken['picture'] ?? null;

            if (!$uid || !$email) {
                throw new \Exception('Invalid token: missing required claims');
            }

            // Check if user exists in Laravel database
            $user = User::where('email', $email)->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $name ?? 'Firebase User',
                    'email' => $email,
                    'password' => Hash::make(Str::random(24)), // Random password for Firebase users
                    'is_email_verified' => 1, // Firebase emails are verified
                    'firebase_uid' => $uid,
                    'firebase_provider' => $provider,
                    'avatar' => $picture
                ]);
            } else {
                // Update existing user with Firebase data
                $user->update([
                    'firebase_uid' => $uid,
                    'firebase_provider' => $provider,
                    'avatar' => $picture,
                    'is_email_verified' => 1
                ]);
            }

            // Log the user in
            Auth::login($user);

            // Set 2FA as verified for Firebase users (bypass 2FA)
            session()->put('2fa_verified', true);
            session()->put('2fa_verified_at', now());

            return response()->json([
                'success' => true,
                'message' => 'Authentication successful',
                'user' => $user,
                'redirect' => route('dashboards')
            ]);

        } catch (\Exception $e) {
            \Log::error('Firebase token verification failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Verify Firebase ID token using Google's public keys
     */
    private function verifyFirebaseToken($idToken)
    {
        try {
            // Get Google's public keys
            $response = $this->httpClient->get('https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com');
            $keys = json_decode($response->getBody(), true);

            // Decode the token header to get the key ID
            $tokenParts = explode('.', $idToken);
            $header = json_decode(base64_decode($tokenParts[0]), true);
            $kid = $header['kid'] ?? null;

            if (!$kid || !isset($keys[$kid])) {
                throw new \Exception('Invalid token: key ID not found');
            }

            // Get the public key
            $publicKey = $keys[$kid];

            // Verify and decode the token
            $decoded = JWT::decode($idToken, new Key($publicKey, 'RS256'));

            // Convert to array
            return (array) $decoded;

        } catch (\Exception $e) {
            \Log::error('Firebase token verification error: ' . $e->getMessage());
            throw new \Exception('Token verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle Firebase webhook for user events
     */
    public function webhook(Request $request)
    {
        try {
            $payload = $request->all();
            $eventType = $payload['eventType'] ?? null;

            switch ($eventType) {
                case 'providers/delete':
                    $this->handleUserDelete($payload);
                    break;
                case 'providers/update':
                    $this->handleUserUpdate($payload);
                    break;
                default:
                    \Log::info('Unhandled Firebase webhook event: ' . $eventType);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Firebase webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle user deletion from Firebase
     */
    private function handleUserDelete($payload)
    {
        $uid = $payload['data']['uid'] ?? null;
        
        if ($uid) {
            $user = User::where('firebase_uid', $uid)->first();
            if ($user) {
                // Optionally delete or deactivate the user
                // $user->delete(); // Uncomment to delete user
                $user->update(['is_active' => false]); // Or just deactivate
            }
        }
    }

    /**
     * Handle user updates from Firebase
     */
    private function handleUserUpdate($payload)
    {
        $uid = $payload['data']['uid'] ?? null;
        $email = $payload['data']['email'] ?? null;
        $displayName = $payload['data']['displayName'] ?? null;
        
        if ($uid) {
            $user = User::where('firebase_uid', $uid)->first();
            if ($user) {
                $user->update([
                    'email' => $email,
                    'name' => $displayName
                ]);
            }
        }
    }

    /**
     * Get Firebase configuration for frontend
     */
    public function getConfig()
    {
        return response()->json([
            'apiKey' => config('firebase.api_key'),
            'authDomain' => config('firebase.auth_domain'),
            'projectId' => config('firebase.project_id'),
            'storageBucket' => config('firebase.storage_bucket'),
            'messagingSenderId' => config('firebase.messaging_sender_id'),
            'appId' => config('firebase.app_id')
        ]);
    }
}
