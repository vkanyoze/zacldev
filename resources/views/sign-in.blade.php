<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/app.js')
    <title>Sign In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-indigo-600 to-indigo-800 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 sm:p-8">
        <div class="flex flex-col items-center">
            <a href="https://www.zacl.co.zm/" class="flex justify-center mb-6">
                <img src="/front-logo.png" alt="Zambia AIRPORTS Corporation Limited" class="h-16 w-auto" />
            </a>
            <h2 class="text-2xl sm:text-3xl font-bold text-center text-black mb-2">Welcome Back</h2>
            <p class="text-center text-black text-sm mb-6 sm:mb-8">Sign in to manage your payments securely.</p>
        </div>
        <form method="POST" action="{{ route('login.custom') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-black font-medium mb-2" for="email">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-indigo-600"></i>
                    </div>
                    <input type="email" placeholder="Email" name="email" autofocus
                        class="w-full p-3 pl-10 rounded-lg border-2 {{ $errors->has('email') ? 'border-red-500' : 'border-indigo-300' }} focus:outline-none focus:border-indigo-600 text-gray-900 placeholder-gray-400">
                </div>
                @if ($errors->has('email'))
                    <span class="text-sm text-red-600 mt-1">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div>
                <label class="block text-black font-medium mb-2" for="password">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-indigo-600"></i>
                    </div>
                    <input id="password" name="password" type="password" autocomplete="off" placeholder="Password"
                        class="w-full p-3 pl-10 pr-10 rounded-lg border-2 {{ $errors->has('password') ? 'border-red-500' : 'border-indigo-300' }} focus:outline-none focus:border-indigo-600 text-gray-900 placeholder-gray-400">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" onclick="togglePassword()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i id="togglePasswordIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                @if ($errors->has('password'))
                    <span class="text-sm text-red-600 mt-1">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-black">
                    <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    Remember me
                </label>
                <a href="{{ route('forgot') }}" class="text-indigo-600 hover:underline text-sm">Forgot password?</a>
            </div>
            <button type="submit" class="w-full py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium text-lg hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105">
                Continue
            </button>
            
            <!-- Social Login Section -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue with</span>
                    </div>
                </div>
                
                <!-- Firebase Social Login -->
                <div class="mt-6 grid grid-cols-2 gap-3" id="firebase-social-login">
                    <!-- Google Login (Firebase) -->
                    <button type="button" id="firebase-google-login" 
                       class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="ml-2">Google</span>
                    </button>
                    
                    <!-- Facebook Login (Firebase) -->
                    <button type="button" id="firebase-facebook-login" 
                       class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span class="ml-2">Facebook</span>
                    </button>
                </div>

            </div>
            
            <div class="text-center text-sm text-black">
                Don't have an account? 
                <a href="{{ route('register-user') }}" class="text-indigo-600 hover:underline">Create Account</a>
            </div>
        </form>
    </div>
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
            password.focus();
        }

        // Firebase Authentication
        document.addEventListener('DOMContentLoaded', function() {
            // Google Login
            document.getElementById('firebase-google-login').addEventListener('click', async function() {
                try {
                    const result = await window.firebaseAuth.signInWithGoogle();
                    console.log('Google login successful:', result);
                    // Redirect will be handled by the backend response
                    if (result.redirect) {
                        window.location.href = result.redirect;
                    }
                } catch (error) {
                    console.error('Google login failed:', error);
                    alert('Google login failed: ' + error.message);
                }
            });

            // Facebook Login
            document.getElementById('firebase-facebook-login').addEventListener('click', async function() {
                try {
                    const result = await window.firebaseAuth.signInWithFacebook();
                    console.log('Facebook login successful:', result);
                    // Redirect will be handled by the backend response
                    if (result.redirect) {
                        window.location.href = result.redirect;
                    }
                } catch (error) {
                    console.error('Facebook login failed:', error);
                    alert('Facebook login failed: ' + error.message);
                }
            });
        });
    </script>
</body>
</html>

