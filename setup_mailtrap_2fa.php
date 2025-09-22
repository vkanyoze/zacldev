<?php
/**
 * Mailtrap 2FA Setup Script
 * 
 * This script helps configure Mailtrap for 2FA email sending.
 * Run this script to test your Mailtrap configuration.
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;

echo "🔐 ZACL 2FA Mailtrap Setup Script\n";
echo "==================================\n\n";

// Check if .env file exists
if (!file_exists('.env')) {
    echo "❌ .env file not found. Please create one first.\n";
    echo "Copy .env.example to .env and configure your settings.\n";
    exit(1);
}

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "📧 Checking Mailtrap Configuration...\n";

// Check mail configuration
$mailConfig = [
    'MAIL_MAILER' => $_ENV['MAIL_MAILER'] ?? 'not set',
    'MAIL_HOST' => $_ENV['MAIL_HOST'] ?? 'not set',
    'MAIL_PORT' => $_ENV['MAIL_PORT'] ?? 'not set',
    'MAIL_USERNAME' => $_ENV['MAIL_USERNAME'] ?? 'not set',
    'MAIL_PASSWORD' => $_ENV['MAIL_PASSWORD'] ?? 'not set',
    'MAIL_ENCRYPTION' => $_ENV['MAIL_ENCRYPTION'] ?? 'not set',
    '2FA_ENABLED' => $_ENV['2FA_ENABLED'] ?? 'not set',
];

echo "\n📋 Current Configuration:\n";
foreach ($mailConfig as $key => $value) {
    $status = $value === 'not set' ? '❌' : '✅';
    $displayValue = $key === 'MAIL_PASSWORD' && $value !== 'not set' ? '***hidden***' : $value;
    echo "  {$status} {$key}: {$displayValue}\n";
}

echo "\n🔧 Required Configuration for Mailtrap:\n";
echo "  ✅ MAIL_MAILER=smtp\n";
echo "  ✅ MAIL_HOST=sandbox.smtp.mailtrap.io\n";
echo "  ✅ MAIL_PORT=587\n";
echo "  ✅ MAIL_USERNAME=your_mailtrap_username\n";
echo "  ✅ MAIL_PASSWORD=your_mailtrap_password\n";
echo "  ✅ MAIL_ENCRYPTION=tls\n";
echo "  ✅ 2FA_ENABLED=true\n";

echo "\n📝 Next Steps:\n";
echo "1. Get your Mailtrap credentials from https://mailtrap.io/inboxes\n";
echo "2. Update your .env file with the credentials above\n";
echo "3. Run: php artisan config:clear\n";
echo "4. Test with: php artisan test:mailtrap-2fa\n";
echo "5. Check your Mailtrap inbox for the test email\n";

echo "\n🧪 Test Commands:\n";
echo "  php artisan test:mailtrap-2fa your-email@example.com\n";
echo "  php artisan tinker\n";
echo "  Mail::to('test@example.com')->send(new TwoFactorCodeMail(\$user, '123456'));\n";

echo "\n📚 Documentation:\n";
echo "  - Mailtrap Setup Guide: MAILTRAP_2FA_SETUP.md\n";
echo "  - 2FA Implementation: PASSWORD_POLICY_IMPLEMENTATION.md\n";

echo "\n✨ Setup complete! Follow the steps above to configure Mailtrap.\n";
?>
