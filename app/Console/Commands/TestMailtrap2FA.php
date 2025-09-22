<?php

namespace App\Console\Commands;

use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailtrap2FA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:mailtrap-2fa {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test 2FA email sending with Mailtrap';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if (!$email) {
            // Get first user's email if no email provided
            $user = User::first();
            if (!$user) {
                $this->error('No users found in database. Please create a user first.');
                return;
            }
            $email = $user->email;
        }
        
        $this->info("Testing 2FA email to: {$email}");
        
        try {
            // Create a test user object
            $user = new \stdClass();
            $user->name = 'Test User';
            $user->email = $email;
            
            // Generate a test code
            $testCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            $this->info("Sending 2FA email with code: {$testCode}");
            
            // Send the email
            Mail::to($email)->send(new TwoFactorCodeMail($user, $testCode));
            
            $this->info('✅ 2FA email sent successfully!');
            $this->line('');
            $this->line('📧 Check your Mailtrap inbox at: https://mailtrap.io/inboxes');
            $this->line('🔐 Test code: ' . $testCode);
            $this->line('');
            $this->line('If you don\'t see the email:');
            $this->line('1. Check your Mailtrap SMTP credentials in .env');
            $this->line('2. Verify MAIL_MAILER=smtp in .env');
            $this->line('3. Run: php artisan config:clear');
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to send 2FA email: ' . $e->getMessage());
            $this->line('');
            $this->line('Troubleshooting:');
            $this->line('1. Check your .env file for correct Mailtrap credentials');
            $this->line('2. Verify MAIL_MAILER=smtp');
            $this->line('3. Run: php artisan config:clear');
            $this->line('4. Check storage/logs/laravel.log for detailed errors');
        }
    }
}
