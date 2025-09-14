<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/app.js')
    <title>Create Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-indigo-600 to-indigo-800 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 sm:p-8">
        <div class="flex flex-col items-center">
            <a href="https://www.zacl.co.zm/" class="flex justify-center mb-6">
                <img src="/front-logo.png" alt="Zambia AIRPORTS Corporation Limited" class="h-16 w-auto" />
            </a>
            <h2 class="text-2xl sm:text-3xl font-bold text-center text-black mb-2">Create Account</h2>
            <p class="text-center text-black text-sm mb-6 sm:mb-8">Access an efficient and secure platform for managing your aeronautical service fees</p>
        </div>
        <form method="POST" action="{{ route('register.custom') }}" class="space-y-6">
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
            <button type="submit" class="w-full py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium text-lg hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105">
                Continue
            </button>
            <div class="text-center text-sm text-black">
                Forgot your password? <a href="{{ route('forgot') }}" class="text-indigo-600 hover:underline">Reset Password</a> if you don't have an account <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Log in</a>
            </div>
            <div class="text-center text-xs text-gray-600">
                By clicking "Continue" on the create account page, you agree to the terms & conditions of zacl corporation.
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
        }
    </script>
</body>
</html>
