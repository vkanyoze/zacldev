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
            return redirect()->route('dashboards')->withSuccess('Signed in');
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
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => [
                'required', 
                'string', 
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ]);
           
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
        
        return redirect()->route('dashboards')->with('success', 'Account created successfully! Welcome to your dashboard.');
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
        $validator =  Validator::make($request->all(),[
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {  
                return back()
                    ->withErrors($validator)
                    ->withInput();
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
            // Example statistics (replace with real queries as needed)
            $paymentsCount = $user->payments()->count();
            $cardsCount = $user->cards()->count();
            $lastPayment = $user->payments()->latest()->first();
            $totalSpent = $user->payments()->sum('amount_spend');
            return view('dashboard', compact('title', 'user', 'paymentsCount', 'cardsCount', 'lastPayment', 'totalSpent'));
        }
        return redirect("login")->withSuccess('You are not allowed to access');
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
