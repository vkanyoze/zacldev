<?php

/**
 * Analytics Status Checker
 * Run this script to verify your analytics setup
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Card;
use App\Models\Payment;
use App\Models\Setting;

echo "🔍 ZACL Analytics Status Check\n";
echo "==============================\n\n";

// Check database connection
try {
    $userCount = User::count();
    $cardCount = Card::count();
    $paymentCount = Payment::count();
    $settingCount = Setting::count();
    
    echo "✅ Database Connection: OK\n";
    echo "📊 Data Summary:\n";
    echo "   - Users: {$userCount}\n";
    echo "   - Cards: {$cardCount}\n";
    echo "   - Payments: {$paymentCount}\n";
    echo "   - Settings: {$settingCount}\n\n";
    
    // Check if we have enough data for analytics
    if ($userCount >= 5 && $paymentCount >= 50) {
        echo "✅ Analytics Ready: Sufficient data available\n";
    } else {
        echo "⚠️  Analytics Limited: Consider adding more demo data\n";
    }
    
    // Check recent payments
    $recentPayments = Payment::where('created_at', '>=', now()->subDays(30))->count();
    echo "📈 Recent Activity: {$recentPayments} payments in last 30 days\n\n";
    
    // Sample data preview
    echo "📋 Sample Data Preview:\n";
    $sampleUser = User::first();
    if ($sampleUser) {
        echo "   - Sample User: {$sampleUser->email}\n";
    }
    
    $samplePayment = Payment::latest()->first();
    if ($samplePayment) {
        echo "   - Latest Payment: \${$samplePayment->amount_spend} ({$samplePayment->status})\n";
    }
    
    echo "\n🚀 Ready to view analytics at:\n";
    echo "   - User Dashboard: http://localhost:8000/dashboard\n";
    echo "   - Admin Dashboard: http://localhost:8000/admin/dashboard\n";
    
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "💡 Make sure to run: php artisan migrate\n";
}

echo "\n📚 For detailed instructions, see: ANALYTICS_GUIDE.md\n";
echo "⚡ For quick start, see: QUICK_START.md\n";
