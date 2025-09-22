<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserVerify;
use App\Http\Controllers\PaymentsController;
use App\Models\EmailVerify;
use App\Jobs\SendEmailJob;
use App\Mail\ResetEmail;
use App\Mail\SendWelcomeEmail;
use Illuminate\Support\Facades\Validator;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class CustomAuthController extends Controller
{
    public function index(string $subHeading=null)
    {
        $title = 'Login';
        if ($subHeading == null){

            $subHeading = 'Access your account and manage your payments conveniently <br> and securely.';
        }

        return view('sign-in', compact('subHeading', 'title'));
    }  

    public function forgot()
    {
        $title = 'Login';
        return view('reset', compact('title'));

    }  

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
        ]);


        #Match The Old Password
        if(!Hash::check($request->old_password, auth()->user()->password)){
            return back()->with("error", "Changing passwords failed , old Password Doesn't match!");
        }

        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with("success", "Password changed successfully!");
    }

    public function updatePassword()
    {
        $title = 'Update password';
        return view('account.passwords', compact('title'));

    }  


    public function updateEmail()
    {
        $title = 'Update email';
        return view('account.emails', compact('title'));
    }  
      
    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Debug: Log the request data
        \Log::info('Login attempt', [
            'email' => $request->email,
            'has_remember' => $request->has('remember'),
            'all_data' => $request->all()
        ]);

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();
        
        // Debug: Log user lookup result
        \Log::info('User lookup result', [
            'user_found' => $user ? true : false,
            'user_id' => $user ? $user->id : null,
            'is_email_verified' => $user ? $user->is_email_verified : null
        ]);
        
        if (!$user){
           return redirect("login")->withInput()->withErrors(['email' => 'Login details are not valid']);
        }

        // Email verification check - commented out to allow login without verification
        // if (!$user->is_email_verified) {
        //     return redirect("login")->withInput()->withErrors(['email' => 'Email not verified']);
        // }

        // Check if "Remember Me" is checked
        $remember = $request->has('remember');

        // Debug: Log authentication attempt
        \Log::info('Authentication attempt', [
            'credentials' => $credentials,
            'remember' => $remember
        ]);

        if (Auth::attempt($credentials, $remember)) {
            \Log::info('Authentication successful', ['user_id' => Auth::id()]);
            
            // Check if 2FA is enabled
            if (config('2fa.enabled', false)) {
                // Clear any existing 2FA session
                session()->forget(['2fa_verified', '2fa_verified_at']);
                // Redirect to 2FA verification
                return redirect()->route('2fa.verify');
            } else {
                // 2FA is disabled, set as verified and go to dashboard
                session()->put('2fa_verified', true);
                session()->put('2fa_verified_at', now());
                return redirect()->route('dashboards')->withSuccess('Signed in successfully');
            }
        }
  
        \Log::warning('Authentication failed', ['email' => $request->email]);
        return redirect("login")->withInput()->withErrors(['email' => 'Login details are not valid']);
    }


    public function registration()
    {
        $title = 'Create Account';
        return view('sign-up', compact('title'));
    }
      
    public function customRegistration(Request $request)
    {  
        // Get password policy settings
        $passwordPolicy = \App\Services\PasswordPolicyService::getSettings();
        
        // Build validation rules based on password policy
        $passwordRules = ['required', 'string'];
        
        if ($passwordPolicy->enabled) {
            $passwordRules[] = 'min:' . $passwordPolicy->min_length;
            
            if ($passwordPolicy->require_uppercase) {
                $passwordRules[] = 'regex:/[A-Z]/';
            }
            
            if ($passwordPolicy->require_lowercase) {
                $passwordRules[] = 'regex:/[a-z]/';
            }
            
            if ($passwordPolicy->require_numbers) {
                $passwordRules[] = 'regex:/[0-9]/';
            }
            
            if ($passwordPolicy->require_special_characters) {
                $passwordRules[] = 'regex:/[^A-Za-z0-9]/';
            }
        } else {
            // Default validation if policy is disabled
            $passwordRules[] = Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        }
        
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => $passwordRules,
        ]);
        
        // Additional server-side password policy validation
        if ($passwordPolicy->enabled) {
            $validation = \App\Services\PasswordPolicyService::validatePassword($request->password);
            if (!$validation['valid']) {
                return back()->withErrors(['password' => $validation['errors']])->withInput();
            }
        }
           
        $data = $request->all();
        $createUser = $this->create($data);
  
        $token = Str::random(64);
  
        UserVerify::create([
              'user_id' => $createUser->id, 
              'token' => $token
            ]);
  
        // Email verification is now optional - commented out for immediate access
        // SendEmailJob::dispatch($request->email, new SendWelcomeEmail($token));

        // Auto-login the user after registration
        Auth::login($createUser);
        
        return redirect()->route('login')->with('success', 'Account created successfully! Please login to continue.');
    }

    public function resendEmail()
    {
        $title = 'Resend Verification';
        return view('resend', compact('title'));
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Invalid email address']);
        }

        if ($user->is_email_verified) {
            return redirect()->back()->withErrors(['email' => 'Email address already verified']);
        }

        $token = UserVerify::where('user_id', $user->id)->first()->token;
        SendEmailJob::dispatch($request->email, new SendWelcomeEmail($token));

        return redirect()->back()->with('success', 'Verification email sent');
    }

    public function resetEmailLink(Request $request)
    {  
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
           
        $data = $request->all();
  
        $token = Str::random(64);
        $user = User::where('email', $data['email'])->first();
        $verify = UserVerify::create([
              'user_id' => $user->id, 
              'token' => $token
        ]);

        SendEmailJob::dispatch($request->email, new VerifyEmail($token));
         
         return redirect()->route('forgot')->with('success', 'We have send a link to your email. Check your email address');
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        #Match The Old Password
        if(!Hash::check($request->password, auth()->user()->password)){
            return back()->with("error", "Failed to verify your account");
        }

        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->password)
        ]);

        $token = Str::random(64);
        EmailVerify::create([
              'user_id' => auth()->user()->id,
              'token' => $token,
              'email' => $request->email
        ]);

        SendEmailJob::dispatch($request->email, new ResetEmail($token));

        return back()->with("success", "Email has been sent for you to verify");
    }

    public function resetPasswordLink(Request $request)
    {  
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
           
        $data = $request->all();
  
        $token = Str::random(64);
        $user = User::where('email', $data['email'])->first();
        $verify = UserVerify::create([
              'user_id' => $user->id, 
              'token' => $token
        ]);

        SendEmailJob::dispatch($request->email, new VerifyEmail($token));
         
        return redirect()->route('forgot')->with('success', 'We have send a link to your email. Check your email address');
    }

    public function showResetPasswordForm($token) { 
        $title = 'Reset Password';
         return view('account.resets',  ['token' => $token], compact('token' , 'title'));
    }

    public function showResetEmailForm($token) { 
        $title = 'Reset Password';
        $verifyUser = EmailVerify::where('token', $token)->first();
  
        $message = 'Sorry your email cannot be identified.';
        $user = User::find($verifyUser->user_id);
        if(!is_null($verifyUser)  && !is_null($user)){
            $user->email = $verifyUser->email;
            $user->save();
            $verifyUser->delete();
            return redirect()->route('login')->withSuccess('Email Changed succefully');
        }

        return redirect()->route('login')->with('error', $message);
    }

    public function submitResetPasswordForm(Request $request)
      {
        // Get password policy settings
        $passwordPolicy = \App\Services\PasswordPolicyService::getSettings();
        
        // Build validation rules based on password policy
        $passwordRules = ['required', 'string'];
        
        if ($passwordPolicy->enabled) {
            $passwordRules[] = 'min:' . $passwordPolicy->min_length;
            
            if ($passwordPolicy->require_uppercase) {
                $passwordRules[] = 'regex:/[A-Z]/';
            }
            
            if ($passwordPolicy->require_lowercase) {
                $passwordRules[] = 'regex:/[a-z]/';
            }
            
            if ($passwordPolicy->require_numbers) {
                $passwordRules[] = 'regex:/[0-9]/';
            }
            
            if ($passwordPolicy->require_special_characters) {
                $passwordRules[] = 'regex:/[^A-Za-z0-9]/';
            }
        } else {
            $passwordRules[] = 'min:6';
        }
        
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users',
            'password' => $passwordRules,
        ]);

        if ($validator->fails()) {  
                return back()
                    ->withErrors($validator)
                    ->withInput();
        }
        
        // Additional server-side password policy validation
        if ($passwordPolicy->enabled) {
            $validation = \App\Services\PasswordPolicyService::validatePassword($request->password);
            if (!$validation['valid']) {
                return back()->withErrors(['password' => $validation['errors']])->withInput();
            }
        }

        $data = $request->all();
        $user = User::where('email', $data['email'])->first();
        $updatePassword = UserVerify::where('user_id', $user->id)->where('token', $request->token)->first();
        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        $updatePassword->delete();

        return redirect('login')->with('success', 'Your password has been changed!');
    }

    public function create(array $data)
    {
      return User::create([
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'is_email_verified' => 1  // Auto-verify users for immediate login
      ]);
    }    
    
    public function dashboard()
    {
        if(Auth::check()){
            $title = 'Dashboard';
            $user = Auth::user();
            
            // Basic statistics
            $paymentsCount = $user->payments()->count();
            $cardsCount = $user->cards()->count();
            $lastPayment = $user->payments()->latest()->first();
            $totalSpent = $user->payments()->sum('amount_spend');
            
            // Analytics data
            $analytics = $this->getUserAnalytics($user);
            
            return view('dashboard', compact('title', 'user', 'paymentsCount', 'cardsCount', 'lastPayment', 'totalSpent', 'analytics'));
        }
        return redirect("login")->withSuccess('You are not allowed to access');
    }
    
    private function getUserAnalytics($user)
    {
        $now = now();
        $lastWeek = $now->copy()->subWeek();
        $lastMonth = $now->copy()->subMonth();
        
        // Recent payments for trend analysis
        $recentPayments = $user->payments()
            ->where('created_at', '>=', $lastWeek)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Payment trends
        $weeklySpending = $recentPayments->sum('amount_spend');
        $monthlySpending = $user->payments()
            ->where('created_at', '>=', $lastMonth)
            ->sum('amount_spend');
            
        // Calculate growth
        $previousWeekSpending = $user->payments()
            ->whereBetween('created_at', [$lastWeek->copy()->subWeek(), $lastWeek])
            ->sum('amount_spend');
            
        $spendingGrowth = $previousWeekSpending > 0 
            ? round((($weeklySpending - $previousWeekSpending) / $previousWeekSpending) * 100, 1)
            : 0;
            
        // Payment frequency analysis
        $paymentFrequency = $recentPayments->count();
        $avgPaymentAmount = $recentPayments->count() > 0 
            ? $recentPayments->avg('amount_spend') 
            : 0;
            
        // Success rate
        $totalPayments = $user->payments()->count();
        $successfulPayments = $user->payments()->where('status', 'completed')->count();
        $successRate = $totalPayments > 0 
            ? round(($successfulPayments / $totalPayments) * 100, 1)
            : 0;
            
        // Peak day analysis
        $dayOfWeekCounts = $recentPayments->groupBy(function($payment) {
            return $payment->created_at->format('l');
        })->map->count();
        
        $peakDay = $dayOfWeekCounts->isNotEmpty() 
            ? $dayOfWeekCounts->sortDesc()->keys()->first()
            : 'No data';
            
        // Next 7 days prediction (simple linear projection)
        $dailyAverage = $weeklySpending / 7;
        $nextWeekPrediction = $dailyAverage * 7;
        
        // Payment patterns
        $hourlyPatterns = $recentPayments->groupBy(function($payment) {
            return $payment->created_at->format('H');
        })->map->count();
        
        $peakHour = $hourlyPatterns->isNotEmpty() 
            ? $hourlyPatterns->sortDesc()->keys()->first() . ':00'
            : 'No data';
            
        return [
            'revenue_forecast' => [
                'next_7_days' => round($nextWeekPrediction, 2),
                'confidence' => min(95, max(60, 100 - abs($spendingGrowth))),
                'trend' => $spendingGrowth > 5 ? 'Increasing' : ($spendingGrowth < -5 ? 'Decreasing' : 'Stable'),
                'growth_percentage' => $spendingGrowth
            ],
            'payment_activity' => [
                'expected_payments' => max(1, round($paymentFrequency * 1.1)), // Slight increase prediction
                'peak_day' => $peakDay,
                'success_rate' => $successRate,
                'avg_amount' => round($avgPaymentAmount, 2)
            ],
            'insights' => [
                'spending_growth' => $spendingGrowth,
                'peak_hour' => $peakHour,
                'total_this_week' => round($weeklySpending, 2),
                'total_this_month' => round($monthlySpending, 2)
            ]
        ];
    }
    
    public function signOut() {
        Session::flush();
        Auth::logout();
  
        return redirect()->route('login')->with('success', 'You are logged out we hope to see you again soon');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $createUser = $this->create($data);
  
        $token = Str::random(64);
  
        UserVerify::create([
              'user_id' => $createUser->id, 
              'token' => $token
            ]);
  
        SendEmailJob::dispatch($request->email, new SendWelcomeEmail($token));
         
        return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');
    }

    /**
      * Write code on Method
     *
     * @return response()
     */
    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();
  
        $message = 'Sorry your email cannot be identified.';
        $title = 'error';
  
        if(!is_null($verifyUser) ){
            $user = $verifyUser->user;
            $title = 'success';
              
            if(!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login into your <br> account and do your payments.";
                $verifyUser->delete();
            } else {
                $message = "Your e-mail is already verified. You can now login into your <br> account and do your payments.";
                $verifyUser->delete();
            }
        }
  
      return redirect()->route('login')->with($title, $message);
    }
}
