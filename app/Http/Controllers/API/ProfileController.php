<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerify;
use App\Jobs\SendEmailJob;
use App\Mail\ResetEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Get user profile",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User profile information",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'is_email_verified' => $user->is_email_verified,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/profile",
     *     summary="Update user profile",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully"
     *     )
     * )
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update([
            'email' => $request->email
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/profile/change-password",
     *     summary="Change user password",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"current_password","new_password"},
     *             @OA\Property(property="current_password", type="string", format="password"),
     *             @OA\Property(property="new_password", type="string", format="password", minLength=8)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully"
     *     )
     * )
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/profile/change-email",
     *     summary="Request email change",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"new_email","password"},
     *             @OA\Property(property="new_email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email change request sent"
     *     )
     * )
     */
    public function changeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_email' => 'required|email|unique:users,email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect'
            ], 400);
        }

        $token = Str::random(64);
        
        EmailVerify::create([
            'user_id' => $user->id,
            'token' => $token,
            'email' => $request->new_email
        ]);

        SendEmailJob::dispatch($request->new_email, new ResetEmail($token));

        return response()->json([
            'success' => true,
            'message' => 'Email change verification sent to new email address'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/profile/verify-email-change",
     *     summary="Verify email change",
     *     tags={"Profile"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"token"},
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email changed successfully"
     *     )
     * )
     */
    public function verifyEmailChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $emailVerify = EmailVerify::where('token', $request->token)->first();

        if (!$emailVerify) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification token'
            ], 400);
        }

        $user = User::find($emailVerify->user_id);
        $user->email = $emailVerify->email;
        $user->save();

        $emailVerify->delete();

        return response()->json([
            'success' => true,
            'message' => 'Email changed successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/profile/stats",
     *     summary="Get user statistics",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User statistics",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="payments_count", type="integer"),
     *                 @OA\Property(property="cards_count", type="integer"),
     *                 @OA\Property(property="total_spent", type="number"),
     *                 @OA\Property(property="last_payment", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        
        $paymentsCount = $user->payments()->count();
        $cardsCount = $user->cards()->count();
        $totalSpent = $user->payments()->sum('amount_spend');
        $lastPayment = $user->payments()->latest()->first();

        return response()->json([
            'success' => true,
            'data' => [
                'payments_count' => $paymentsCount,
                'cards_count' => $cardsCount,
                'total_spent' => $totalSpent,
                'last_payment' => $lastPayment
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/profile/summary",
     *     summary="Get comprehensive user summary for mobile dashboard",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User summary with dashboard cards and analytics",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user_info", type="object"),
     *                 @OA\Property(property="summary_cards", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="recent_activity", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="spending_analytics", type="object"),
     *                 @OA\Property(property="quick_stats", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function summary(Request $request)
    {
        $user = $request->user();
        
        // Basic user info
        $userInfo = [
            'id' => $user->id,
            'email' => $user->email,
            'is_email_verified' => $user->is_email_verified,
            'member_since' => $user->created_at->format('M Y'),
            'last_login' => $user->updated_at->diffForHumans()
        ];

        // Get payment statistics
        $payments = $user->payments();
        $totalPayments = $payments->count();
        $totalSpent = $payments->sum('amount_spend') ?? 0;
        $successfulPayments = $payments->where('status', 'completed')->count();
        $failedPayments = $payments->where('status', 'failed')->count();
        $pendingPayments = $payments->where('status', 'pending')->count();

        // Get card statistics
        $cards = $user->cards();
        $totalCards = $cards->count();
        $defaultCard = $cards->where('is_default', true)->first();
        $activeCards = $cards->where('is_active', true)->count();

        // Recent activity (last 5 payments)
        $recentPayments = $payments->latest()->take(5)->get()->map(function ($payment) {
            return [
                'id' => $payment->id,
                'amount' => $payment->amount_spend,
                'status' => $payment->status,
                'description' => $payment->description ?? 'Payment',
                'date' => $payment->created_at->format('M j, Y'),
                'time_ago' => $payment->created_at->diffForHumans()
            ];
        });

        // Spending analytics (last 30 days)
        $thirtyDaysAgo = now()->subDays(30);
        $monthlySpending = $payments->where('created_at', '>=', $thirtyDaysAgo)->sum('amount_spend');
        $weeklySpending = $payments->where('created_at', '>=', now()->subDays(7))->sum('amount_spend');
        $todaySpending = $payments->whereDate('created_at', today())->sum('amount_spend');

        // Average spending per transaction
        $avgTransactionAmount = $totalPayments > 0 ? round($totalSpent / $totalPayments, 2) : 0;

        // Summary cards for mobile dashboard
        $summaryCards = [
            [
                'title' => 'Total Spent',
                'value' => '$' . number_format($totalSpent, 2),
                'subtitle' => 'All time',
                'icon' => 'dollar-sign',
                'color' => 'green',
                'trend' => $monthlySpending > 0 ? 'up' : 'neutral'
            ],
            [
                'title' => 'Payment Cards',
                'value' => $totalCards,
                'subtitle' => $activeCards . ' active',
                'icon' => 'credit-card',
                'color' => 'blue',
                'trend' => 'neutral'
            ],
            [
                'title' => 'Transactions',
                'value' => $totalPayments,
                'subtitle' => $successfulPayments . ' successful',
                'icon' => 'receipt',
                'color' => 'purple',
                'trend' => $totalPayments > 0 ? 'up' : 'neutral'
            ],
            [
                'title' => 'This Month',
                'value' => '$' . number_format($monthlySpending, 2),
                'subtitle' => 'Last 30 days',
                'icon' => 'calendar',
                'color' => 'orange',
                'trend' => $monthlySpending > 0 ? 'up' : 'neutral'
            ]
        ];

        // Spending analytics
        $spendingAnalytics = [
            'total_spent' => $totalSpent,
            'monthly_spending' => $monthlySpending,
            'weekly_spending' => $weeklySpending,
            'today_spending' => $todaySpending,
            'average_transaction' => $avgTransactionAmount,
            'spending_breakdown' => [
                'successful' => $successfulPayments,
                'failed' => $failedPayments,
                'pending' => $pendingPayments
            ]
        ];

        // Quick stats
        $quickStats = [
            'account_age_days' => $user->created_at->diffInDays(now()),
            'last_payment_date' => $payments->latest()->first()?->created_at?->format('M j, Y') ?? 'No payments yet',
            'default_card_last4' => $defaultCard ? '****' . substr($defaultCard->card_number, -4) : 'No default card',
            'email_verified' => $user->is_email_verified,
            'notifications_count' => $user->unreadNotifications()->count()
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'user_info' => $userInfo,
                'summary_cards' => $summaryCards,
                'recent_activity' => $recentPayments,
                'spending_analytics' => $spendingAnalytics,
                'quick_stats' => $quickStats
            ]
        ]);
    }
}
