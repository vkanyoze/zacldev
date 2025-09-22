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
                
                <!-- Password Requirements -->
                <div id="passwordRequirements" class="mt-2 text-sm text-gray-600 hidden">
                    <p class="font-medium mb-1">Password must contain:</p>
                    <ul id="requirementsList" class="space-y-1">
                        <!-- Requirements will be populated by JavaScript -->
                    </ul>
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
        // Password policy configuration
        const passwordPolicy = @json(\App\Services\PasswordPolicyService::getSettings());
        
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
        
        function validatePassword(password) {
            const errors = [];
            
            if (passwordPolicy.enabled) {
                if (password.length < passwordPolicy.min_length) {
                    errors.push(`At least ${passwordPolicy.min_length} characters`);
                }
                
                if (passwordPolicy.require_uppercase && !/[A-Z]/.test(password)) {
                    errors.push('One uppercase letter (A-Z)');
                }
                
                if (passwordPolicy.require_lowercase && !/[a-z]/.test(password)) {
                    errors.push('One lowercase letter (a-z)');
                }
                
                if (passwordPolicy.require_numbers && !/[0-9]/.test(password)) {
                    errors.push('One number (0-9)');
                }
                
                if (passwordPolicy.require_special_characters && !/[^A-Za-z0-9]/.test(password)) {
                    errors.push('One special character (!@#$%^&*)');
                }
            }
            
            return errors;
        }
        
        function updatePasswordRequirements() {
            const password = document.getElementById('password').value;
            const requirementsDiv = document.getElementById('passwordRequirements');
            const requirementsList = document.getElementById('requirementsList');
            
            if (passwordPolicy.enabled && password.length > 0) {
                const errors = validatePassword(password);
                
                if (errors.length > 0) {
                    requirementsDiv.classList.remove('hidden');
                    requirementsList.innerHTML = errors.map(error => 
                        `<li class="text-red-600"><i class="fas fa-times mr-1"></i>${error}</li>`
                    ).join('');
                } else {
                    requirementsDiv.classList.add('hidden');
                }
            } else {
                requirementsDiv.classList.add('hidden');
            }
        }
        
        // Add event listener for password input
        document.getElementById('password').addEventListener('input', updatePasswordRequirements);
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            
            if (passwordPolicy.enabled) {
                const errors = validatePassword(password);
                if (errors.length > 0) {
                    e.preventDefault();
                    alert('Password does not meet the requirements:\n\n' + errors.join('\n'));
                    return false;
                }
            }
        });
        
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
