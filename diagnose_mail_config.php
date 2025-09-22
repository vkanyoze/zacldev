<?php
/**
 * Mail Configuration Diagnostic Script
 * 
 * This script helps diagnose and fix mail configuration issues.
 */

echo "🔍 ZACL Mail Configuration Diagnostic\n";
echo "====================================\n\n";

// Check if .env file exists
if (!file_exists('.env')) {
    echo "❌ .env file not found!\n";
    echo "Please create a .env file first.\n\n";
    exit(1);
}

echo "📁 Checking .env file...\n";
$envContent = file_get_contents('.env');
$lines = explode("\n", $envContent);

$mailSettings = [];
foreach ($lines as $line) {
    $line = trim($line);
    if (strpos($line, '=') !== false && !empty($line) && !str_starts_with($line, '#')) {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        $mailSettings[$key] = $value;
    }
}

echo "📧 Current .env Mail Settings:\n";
echo "==============================\n";

$requiredSettings = [
    'MAIL_MAILER' => 'smtp',
    'MAIL_HOST' => 'sandbox.smtp.mailtrap.io',
    'MAIL_PORT' => '587',
    'MAIL_USERNAME' => 'your_mailtrap_username',
    'MAIL_PASSWORD' => 'your_mailtrap_password',
    'MAIL_ENCRYPTION' => 'tls',
    'MAIL_FROM_ADDRESS' => 'noreply@zacl.co.zm',
    'MAIL_FROM_NAME' => 'ZACL Payment System',
    '2FA_ENABLED' => 'true'
];

foreach ($requiredSettings as $key => $expectedValue) {
    $currentValue = $mailSettings[$key] ?? 'NOT SET';
    $status = '❌';
    
    if ($key === 'MAIL_USERNAME' || $key === 'MAIL_PASSWORD') {
        if ($currentValue !== 'NOT SET' && $currentValue !== $expectedValue) {
            $status = '✅';
        }
    } else {
        if ($currentValue === $expectedValue) {
            $status = '✅';
        }
    }
    
    $displayValue = $key === 'MAIL_PASSWORD' && $currentValue !== 'NOT SET' ? '***hidden***' : $currentValue;
    echo "  {$status} {$key}: {$displayValue}\n";
}

echo "\n🔧 Configuration Issues Found:\n";
echo "==============================\n";

$issues = [];
if (!isset($mailSettings['MAIL_MAILER']) || $mailSettings['MAIL_MAILER'] !== 'smtp') {
    $issues[] = "MAIL_MAILER should be 'smtp'";
}
if (!isset($mailSettings['MAIL_HOST']) || $mailSettings['MAIL_HOST'] !== 'sandbox.smtp.mailtrap.io') {
    $issues[] = "MAIL_HOST should be 'sandbox.smtp.mailtrap.io'";
}
if (!isset($mailSettings['MAIL_PORT']) || $mailSettings['MAIL_PORT'] !== '587') {
    $issues[] = "MAIL_PORT should be '587'";
}
if (!isset($mailSettings['MAIL_USERNAME']) || $mailSettings['MAIL_USERNAME'] === 'your_mailtrap_username') {
    $issues[] = "MAIL_USERNAME needs your actual Mailtrap username";
}
if (!isset($mailSettings['MAIL_PASSWORD']) || $mailSettings['MAIL_PASSWORD'] === 'your_mailtrap_password') {
    $issues[] = "MAIL_PASSWORD needs your actual Mailtrap password";
}
if (!isset($mailSettings['MAIL_ENCRYPTION']) || $mailSettings['MAIL_ENCRYPTION'] !== 'tls') {
    $issues[] = "MAIL_ENCRYPTION should be 'tls'";
}
if (!isset($mailSettings['MAIL_FROM_ADDRESS']) || empty($mailSettings['MAIL_FROM_ADDRESS'])) {
    $issues[] = "MAIL_FROM_ADDRESS should be 'noreply@zacl.co.zm'";
}
if (!isset($mailSettings['MAIL_FROM_NAME']) || empty($mailSettings['MAIL_FROM_NAME'])) {
    $issues[] = "MAIL_FROM_NAME should be 'ZACL Payment System'";
}
if (!isset($mailSettings['2FA_ENABLED']) || $mailSettings['2FA_ENABLED'] !== 'true') {
    $issues[] = "2FA_ENABLED should be 'true'";
}

if (empty($issues)) {
    echo "✅ All mail settings are correctly configured!\n";
} else {
    foreach ($issues as $issue) {
        echo "❌ {$issue}\n";
    }
}

echo "\n🔧 Fix Instructions:\n";
echo "====================\n";
echo "1. Open your .env file\n";
echo "2. Add or update these lines:\n\n";

echo "# Mailtrap Sandbox Configuration\n";
echo "MAIL_MAILER=smtp\n";
echo "MAIL_HOST=sandbox.smtp.mailtrap.io\n";
echo "MAIL_PORT=587\n";
echo "MAIL_USERNAME=your_actual_mailtrap_username\n";
echo "MAIL_PASSWORD=your_actual_mailtrap_password\n";
echo "MAIL_ENCRYPTION=tls\n";
echo "MAIL_FROM_ADDRESS=\"noreply@zacl.co.zm\"\n";
echo "MAIL_FROM_NAME=\"ZACL Payment System\"\n";
echo "2FA_ENABLED=true\n\n";

echo "3. Replace 'your_actual_mailtrap_username' and 'your_actual_mailtrap_password' with your real Mailtrap credentials\n";
echo "4. Save the .env file\n";
echo "5. Run: php artisan config:clear\n";
echo "6. Test: php artisan test:mailtrap-2fa\n\n";

echo "📚 Get Mailtrap Credentials:\n";
echo "============================\n";
echo "1. Go to https://mailtrap.io/inboxes\n";
echo "2. Click on your sandbox inbox\n";
echo "3. Click 'SMTP Settings' tab\n";
echo "4. Select 'Laravel 9+' integration\n";
echo "5. Copy the username and password\n\n";

echo "🧪 Test Commands:\n";
echo "=================\n";
echo "php artisan config:clear\n";
echo "php artisan test:mailtrap-2fa\n";
echo "php artisan config:show mail\n\n";

echo "✨ After fixing, your 2FA emails will work!\n";
?>
