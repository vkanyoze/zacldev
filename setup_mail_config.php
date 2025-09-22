<?php
/**
 * Mail Configuration Setup Script
 * 
 * This script helps you configure the mail settings for 2FA.
 */

echo "🔧 ZACL Mail Configuration Setup\n";
echo "================================\n\n";

// Check if .env file exists
if (!file_exists('.env')) {
    echo "❌ .env file not found!\n";
    echo "Please create a .env file first.\n";
    echo "You can copy .env.example to .env\n\n";
    exit(1);
}

echo "📧 Current Mail Configuration:\n";
echo "==============================\n";

// Read .env file
$envContent = file_get_contents('.env');
$lines = explode("\n", $envContent);

$mailSettings = [
    'MAIL_MAILER' => 'not set',
    'MAIL_HOST' => 'not set', 
    'MAIL_PORT' => 'not set',
    'MAIL_USERNAME' => 'not set',
    'MAIL_PASSWORD' => 'not set',
    'MAIL_ENCRYPTION' => 'not set',
    'MAIL_FROM_ADDRESS' => 'not set',
    'MAIL_FROM_NAME' => 'not set',
    '2FA_ENABLED' => 'not set'
];

foreach ($lines as $line) {
    if (strpos($line, '=') !== false && !empty(trim($line))) {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        if (isset($mailSettings[$key])) {
            $mailSettings[$key] = $value;
        }
    }
}

foreach ($mailSettings as $key => $value) {
    $status = $value === 'not set' ? '❌' : '✅';
    $displayValue = $key === 'MAIL_PASSWORD' && $value !== 'not set' ? '***hidden***' : $value;
    echo "  {$status} {$key}: {$displayValue}\n";
}

echo "\n🔧 Required Configuration for Mailtrap:\n";
echo "=====================================\n";
echo "Add these lines to your .env file:\n\n";

echo "# Mailtrap Configuration for 2FA\n";
echo "MAIL_MAILER=smtp\n";
echo "MAIL_HOST=sandbox.smtp.mailtrap.io\n";
echo "MAIL_PORT=587\n";
echo "MAIL_USERNAME=your_mailtrap_username\n";
echo "MAIL_PASSWORD=your_mailtrap_password\n";
echo "MAIL_ENCRYPTION=tls\n";
echo "MAIL_FROM_ADDRESS=\"noreply@zacl.co.zm\"\n";
echo "MAIL_FROM_NAME=\"ZACL Payment System\"\n";
echo "2FA_ENABLED=true\n\n";

echo "📝 Steps to Fix:\n";
echo "================\n";
echo "1. Get your Mailtrap credentials from https://mailtrap.io/inboxes\n";
echo "2. Add the configuration above to your .env file\n";
echo "3. Replace 'your_mailtrap_username' and 'your_mailtrap_password' with your actual credentials\n";
echo "4. Run: php artisan config:clear\n";
echo "5. Test with: php artisan test:mailtrap-2fa\n\n";

echo "🧪 Test Commands:\n";
echo "=================\n";
echo "php artisan config:clear\n";
echo "php artisan test:mailtrap-2fa\n";
echo "php artisan config:show mail\n\n";

echo "📚 Documentation:\n";
echo "=================\n";
echo "- Mailtrap Setup: MAILTRAP_2FA_SETUP.md\n";
echo "- 2FA Activation: ACTIVATE_2FA_GUIDE.md\n\n";

echo "✨ After configuring, your 2FA emails will be sent to your Mailtrap inbox!\n";
?>
