<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/app.js')
    <title>Create Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white md:bg-custom-gray lg:bg-custom-gray min-h-screen flex flex-col items-center justify-center">
    <div class="bg-white shadow-lg rounded-xl w-full max-w-md p-8 mb-8">
        <div class="flex flex-col items-center">
            <a href="https://www.zacl.co.zm/"><img src="/front-logo.png" alt="logo" class="h-12 mb-4" /></a>
            <h3 class="text-2xl font-bold text-center text-custom-gray">Create Your Account</h3>
            <p class="mt-2 text-center text-custom-gray text-sm">
                Sign up to manage your payments securely.
            </p>
        </div>
        <form method="POST" action="{{ route('register.custom') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label class="block text-custom-gray font-bold mb-1" for="email">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-custom-green"></i>
                    </div>
                    <input type="email" placeholder="Email" name="email" autofocus
                        class="w-full p-3 pl-10 rounded border-2 {{ $errors->has('email') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                </div>
                @if ($errors->has('email'))
                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div>
                <label class="block text-custom-gray font-bold mb-1" for="password">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-custom-green"></i>
                    </div>
                    <input id="password" name="password" type="password" autocomplete="off" placeholder="Password"
                        class="w-full p-3 pl-10 pr-10 rounded border-2 {{ $errors->has('password') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal js-password">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" onclick="togglePassword()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i id="togglePasswordIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                @if ($errors->has('password'))
                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="text-xs text-custom-gray">
                <p>Password must be at least 8 characters long and include:</p>
                <ul class="list-disc pl-5 mt-1">
                    <li>Uppercase and lowercase letters</li>
                    <li>At least one number</li>
                    <li>At least one special character</li>
                </ul>
            </div>
            <button type="submit" class="w-full py-3 rounded bg-custom-green text-white font-bold text-lg hover:bg-green-700 transition focus:ring-4 focus:ring-blue-300">
                Create Account
            </button>
            <div class="text-center text-sm text-custom-gray">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-bold">Sign In</a>
            </div>
        </form>
    </div>
    <footer class="text-center text-custom-gray text-xs mb-4">
        &copy; <span id="year">2024</span> Zambia Airports Corporation&trade;. All Rights Reserved.
    </footer>
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
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
