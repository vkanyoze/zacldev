<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\Card;
use App\Models\Admin;
use App\Models\ActivityLog;
use App\Services\MLPredictionService;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_payments' => Payment::count(),
            'revenue' => Payment::where('status', 'completed')->sum('amount_spend'),
            'active_users' => User::where('last_seen', '>=', now()->subMinutes(30))->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'payment_success_rate' => $this->getPaymentSuccessRate(),
            'growth' => [
                'users' => $this->getUserGrowth(),
                'revenue' => $this->getRevenueGrowth(),
            ],
        ];

        $systemStatus = [
            'status' => 'operational',
            'version' => '1.0.0',
            'environment' => config('app.env'),
            'maintenance' => false, // You can set this to true when in maintenance mode
            'maintenance_message' => null,
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'server_os' => php_uname('s') . ' ' . php_uname('r'),
            'database_connection' => config('database.default'),
            'queue_connection' => config('queue.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'storage_disk' => config('filesystems.default'),
            'timezone' => config('app.timezone'),
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
            'disk_usage' => $this->getDiskUsage(),
            'lastCronRun' => $this->getLastCronRun(),
            'queueStatus' => $this->getQueueStatus(),
            'storage' => [
                'used' => 0,
                'total' => 0,
                'percentage' => 0,
            ],
            'memory' => [
                'used' => round(memory_get_usage(true) / 1024 / 1024, 2),
                'total' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
                'percentage' => 0,
            ],
            'database' => [
                'size' => 0,
                'tables' => 0,
                'connection' => 'connected',
            ],
            'lastBackup' => null,
        ];

        $recentActivities = ActivityLog::with('causer')
            ->latest()
            ->take(10)
            ->get();

        $recentPayments = Payment::with('user')
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Get unread notifications count and recent notifications
        $admin = auth('admin')->user();
        $unreadNotificationsCount = $admin->unreadNotifications()->count();
        $notifications = $admin->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? '',
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->toIso8601String(),
                    'data' => $notification->data
                ];
            });

        // ML-powered Analytics data
        $mlService = new MLPredictionService();
        $analytics = $mlService->getPredictions();

        return view('admin.dashboard', compact(
            'stats',
            'systemStatus',
            'recentActivities',
            'recentPayments',
            'recentUsers',
            'notifications',
            'unreadNotificationsCount',
            'analytics'
        ));
    }

    protected function getPaymentSuccessRate()
    {
        $total = Payment::count();
        if ($total === 0) return 0;
        
        $successful = Payment::where('status', 'completed')->count();
        return round(($successful / $total) * 100, 2);
    }

    protected function getUserGrowth()
    {
        $currentMonth = User::whereMonth('created_at', now()->month)->count();
        $lastMonth = User::whereMonth('created_at', now()->subMonth()->month)->count();
        
        if ($lastMonth === 0) return $currentMonth > 0 ? 100 : 0;
        
        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2);
    }

    protected function getAdminAnalytics()
    {
        $now = now();
        $lastWeek = $now->copy()->subWeek();
        $lastMonth = $now->copy()->subMonth();
        $nextWeek = $now->copy()->addWeek();
        
        // Revenue analytics
        $currentMonthRevenue = Payment::where('status', 'completed')
            ->where('created_at', '>=', $lastMonth)
            ->sum('amount_spend');
            
        $previousMonthRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$lastMonth->copy()->subMonth(), $lastMonth])
            ->sum('amount_spend');
            
        $weeklyRevenue = Payment::where('status', 'completed')
            ->where('created_at', '>=', $lastWeek)
            ->sum('amount_spend');
            
        $revenueGrowth = $previousMonthRevenue > 0 
            ? round((($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 1)
            : 0;
            
        // User analytics
        $currentMonthUsers = User::where('created_at', '>=', $lastMonth)->count();
        $previousMonthUsers = User::whereBetween('created_at', [$lastMonth->copy()->subMonth(), $lastMonth])->count();
        $userGrowth = $previousMonthUsers > 0 
            ? round((($currentMonthUsers - $previousMonthUsers) / $previousMonthUsers) * 100, 1)
            : 0;
            
        // Payment analytics
        $weeklyPayments = Payment::where('created_at', '>=', $lastWeek)->count();
        $monthlyPayments = Payment::where('created_at', '>=', $lastMonth)->count();
        $paymentGrowth = $weeklyPayments > 0 
            ? round((($monthlyPayments / 4) - $weeklyPayments) / $weeklyPayments * 100, 1)
            : 0;
            
        // Predictions based on trends
        $nextWeekRevenuePrediction = $weeklyRevenue * 1.1; // 10% growth assumption
        $nextWeekUsersPrediction = max(1, round($currentMonthUsers / 4 * 1.1));
        $nextWeekPaymentsPrediction = max(1, round($weeklyPayments * 1.1));
        
        // System load estimation
        $avgPaymentsPerDay = $weeklyPayments / 7;
        $peakLoadPercentage = min(95, max(20, ($avgPaymentsPerDay / 10) * 100));
        
        // Retention rate calculation
        $totalUsers = User::count();
        $activeUsers = User::where('last_seen', '>=', now()->subDays(30))->count();
        $retentionRate = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0;
        
        return [
            'revenue_forecast' => [
                'next_7_days' => round($nextWeekRevenuePrediction, 2),
                'current_month' => round($currentMonthRevenue, 2),
                'growth_percentage' => $revenueGrowth,
                'confidence' => min(95, max(60, 100 - abs($revenueGrowth)))
            ],
            'user_growth' => [
                'expected_new_users' => $nextWeekUsersPrediction,
                'current_active' => $activeUsers,
                'growth_percentage' => $userGrowth,
                'retention_rate' => $retentionRate
            ],
            'payment_volume' => [
                'expected_payments' => $nextWeekPaymentsPrediction,
                'current_weekly' => $weeklyPayments,
                'growth_percentage' => $paymentGrowth
            ],
            'system_performance' => [
                'peak_load' => round($peakLoadPercentage, 1),
                'status' => $peakLoadPercentage > 80 ? 'High' : ($peakLoadPercentage > 60 ? 'Medium' : 'Low')
            ],
            'business_insights' => [
                'revenue_trend' => $revenueGrowth > 5 ? 'Growing' : ($revenueGrowth < -5 ? 'Declining' : 'Stable'),
                'user_trend' => $userGrowth > 5 ? 'Growing' : ($userGrowth < -5 ? 'Declining' : 'Stable'),
                'payment_trend' => $paymentGrowth > 5 ? 'Growing' : ($paymentGrowth < -5 ? 'Declining' : 'Stable')
            ]
        ];
    }

    protected function getRevenueGrowth()
    {
        $currentMonth = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount_spend');
            
        $lastMonth = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('amount_spend');
            
        if ($lastMonth == 0) return $currentMonth > 0 ? 100 : 0;
        
        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2);
    }

    protected function getDiskUsage()
    {
        $total = disk_total_space(base_path());
        $free = disk_free_space(base_path());
        $used = $total - $free;
        
        return [
            'total' => round($total / 1024 / 1024 / 1024, 2) . ' GB',
            'used' => round($used / 1024 / 1024 / 1024, 2) . ' GB',
            'free' => round($free / 1024 / 1024 / 1024, 2) . ' GB',
            'percentage' => round(($used / $total) * 100, 2)
        ];
    }

    protected function getLastCronRun()
    {
        $lastRun = Cache::get('last_cron_run');
        
        if (!$lastRun) {
            return null;
        }
        
        return Carbon::parse($lastRun);
    }

    protected function getQueueStatus()
    {
        if (config('queue.default') === 'sync') {
            return [
                'status' => 'warning',
                'message' => 'Queue is set to sync (not recommended for production)'
            ];
        }
        
        try {
            $connection = DB::connection(config('queue.connections.' . config('queue.default') . '.connection'));
            $connection->getPdo();
            
            // Check if queue table exists
            $tableExists = $connection->getSchemaBuilder()->hasTable('jobs');
            
            if (!$tableExists) {
                return [
                    'status' => 'danger',
                    'message' => 'Queue table not found'
                ];
            }
            
            $pendingJobs = $connection->table('jobs')->count();
            
            if ($pendingJobs > 100) {
                return [
                    'status' => 'warning',
                    'message' => $pendingJobs . ' pending jobs in queue'
                ];
            }
            
            return [
                'status' => 'success',
                'message' => 'Queue is running with ' . $pendingJobs . ' pending jobs'
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'danger',
                'message' => 'Queue connection failed: ' . $e->getMessage()
            ];
        }
    }

    public function users()
    {
        $users = User::with(['payments', 'cards'])
            ->withCount(['payments', 'cards'])
            ->latest()
            ->paginate(20);
            
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'country' => $validated['country'] ?? null,
            'is_email_verified' => 1, // Admin-created users are auto-verified
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function payments()
    {
        $payments = Payment::with('user')
            ->latest()
            ->paginate(20);
            
        return view('admin.payments.index', compact('payments'));
    }

    public function getHistoricalData()
    {
        $mlService = new MLPredictionService();
        $predictions = $mlService->getPredictions();
        
        return response()->json([
            'revenue' => $predictions['historical_data']['revenue'],
            'users' => $predictions['historical_data']['users'],
            'payments' => $predictions['historical_data']['payments'],
            'last_updated' => $predictions['last_updated']
        ]);
    }

    public function retrainModels()
    {
        $mlService = new MLPredictionService();
        $mlService->retrainModels();
        
        return response()->json([
            'success' => true,
            'message' => 'ML models retrained successfully',
            'timestamp' => now()->toISOString()
        ]);
    }

    public function showPayment(Payment $payment)
    {
        $payment->load(['user', 'card']);
        
        return view('admin.payments.show', compact('payment'));
    }

    public function exportPayments()
    {
        $payments = Payment::with('user')
            ->latest()
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=payments_export_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'ID', 'Date', 'Reference', 'User', 'Amount', 'Status', 'Payment Method'
        ];

        $callback = function() use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($payments as $payment) {
                $row = [
                    $payment->id,
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->reference,
                    $payment->user ? $payment->user->name : 'N/A',
                    number_format($payment->amount, 2),
                    ucfirst($payment->status),
                    ucfirst($payment->payment_method),
                ];

                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function showUser(User $user)
    {
        $user->load(['payments' => function($query) {
            $query->latest()->take(10);
        }, 'cards']);
        
        return view('admin.users.show', compact('user'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User updated successfully');
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'suspension_reason' => 'required_if:is_active,false|string|max:255|nullable'
        ]);

        try {
            $updateData = [
                'is_active' => $validated['is_active']
            ];

            // Only update suspension reason if it exists in the request
            if (array_key_exists('suspension_reason', $validated)) {
                $updateData['suspension_reason'] = $validated['is_active'] ? null : $validated['suspension_reason'];
            }

            $user->update($updateData);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User status updated successfully.',
                    'user' => $user->fresh()
                ]);
            }

            return back()->with('success', 'User status updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Error updating user status: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update user status.'
                ], 500);
            }

            return back()->with('error', 'Failed to update user status. Please try again.');
        }
    }

    public function destroyTwoFactor(User $user)
    {
        if (!$user->two_factor_secret) {
            return back()->with('error', 'Two-factor authentication is not enabled for this user');
        }

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return back()->with('success', 'Two-factor authentication has been disabled for this user');
    }

    public function destroyUser(User $user)
    {
        // Prevent deleting your own account
        if ($user->id === auth('admin')->id()) {
            return back()->with('error', 'You cannot delete your own account');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User has been deleted successfully');
    }

    public function refundPayment(Payment $payment)
    {
        if ($payment->status !== 'succeeded') {
            return back()->with('error', 'Only succeeded payments can be refunded.');
        }

        DB::beginTransaction();
        try {
            // Process refund logic here
            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now()
            ]);

            DB::commit();
            return back()->with('success', 'Payment refunded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }

    public function settings()
    {
        $settings = [
            'site_name' => \App\Models\Setting::get('site_name', 'ZACL Payment System'),
            'contact_email' => \App\Models\Setting::get('contact_email', 'admin@zacl.co.zm'),
            'maintenance_mode' => \App\Models\Setting::get('maintenance_mode', false),
            'min_payment_amount' => \App\Models\Setting::get('min_payment_amount', 1.00),
            'max_payment_amount' => \App\Models\Setting::get('max_payment_amount', 10000.00),
            'currency' => \App\Models\Setting::get('currency', 'ZMW'),
            'payment_timeout' => \App\Models\Setting::get('payment_timeout', 30),
            'email_notifications' => \App\Models\Setting::get('email_notifications', true),
            'sms_notifications' => \App\Models\Setting::get('sms_notifications', false),
            'auto_approve_payments' => \App\Models\Setting::get('auto_approve_payments', false),
            'require_email_verification' => \App\Models\Setting::get('require_email_verification', true),
            'session_timeout' => \App\Models\Setting::get('session_timeout', 120),
        ];

        // Get password policy settings
        $passwordPolicy = \App\Services\PasswordPolicyService::getSettings();

        return view('admin.settings', compact('settings', 'passwordPolicy'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'maintenance_mode' => 'boolean',
            'min_payment_amount' => 'required|numeric|min:0',
            'max_payment_amount' => 'required|numeric|min:' . $request->min_payment_amount,
            'currency' => 'required|string|max:3',
            'payment_timeout' => 'required|integer|min:1|max:300',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'auto_approve_payments' => 'boolean',
            'require_email_verification' => 'boolean',
            'session_timeout' => 'required|integer|min:1|max:1440',
            // Password policy validation
            'password_policy_enabled' => 'boolean',
            'password_policy_min_length' => 'required|integer|min:4|max:50',
            'password_policy_require_uppercase' => 'boolean',
            'password_policy_require_lowercase' => 'boolean',
            'password_policy_require_numbers' => 'boolean',
            'password_policy_require_special_characters' => 'boolean',
        ]);

        // Save general settings to database
        $generalSettings = [
            'site_name', 'contact_email', 'maintenance_mode', 'min_payment_amount',
            'max_payment_amount', 'currency', 'payment_timeout', 'email_notifications',
            'sms_notifications', 'auto_approve_payments', 'require_email_verification', 'session_timeout'
        ];

        foreach ($generalSettings as $key) {
            if (isset($validated[$key])) {
                $type = 'string';
                if (is_bool($validated[$key])) {
                    $type = 'boolean';
                } elseif (is_numeric($validated[$key])) {
                    $type = 'integer';
                }
                
                \App\Models\Setting::set($key, $validated[$key], $type);
            }
        }

        // Save password policy settings
        $passwordPolicyData = [
            'enabled' => $validated['password_policy_enabled'] ?? false,
            'min_length' => $validated['password_policy_min_length'],
            'require_uppercase' => $validated['password_policy_require_uppercase'] ?? false,
            'require_lowercase' => $validated['password_policy_require_lowercase'] ?? false,
            'require_numbers' => $validated['password_policy_require_numbers'] ?? false,
            'require_special_characters' => $validated['password_policy_require_special_characters'] ?? false,
        ];

        \App\Services\PasswordPolicyService::updateSettings($passwordPolicyData);

        return back()->with('success', 'Settings updated successfully.');
    }
}
