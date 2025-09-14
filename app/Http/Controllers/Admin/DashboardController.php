<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\Card;
use App\Models\Admin;
use App\Models\ActivityLog;
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

        return view('admin.dashboard', compact(
            'stats',
            'systemStatus',
            'recentActivities',
            'recentPayments',
            'recentUsers',
            'notifications',
            'unreadNotificationsCount'
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

    public function payments()
    {
        $payments = Payment::with('user')
            ->latest()
            ->paginate(20);
            
        return view('admin.payments.index', compact('payments'));
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

        return view('admin.settings', compact('settings'));
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
        ]);

        // Save settings to database
        foreach ($validated as $key => $value) {
            $type = 'string';
            if (is_bool($value)) {
                $type = 'boolean';
            } elseif (is_numeric($value)) {
                $type = 'integer';
            }
            
            \App\Models\Setting::set($key, $value, $type);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
