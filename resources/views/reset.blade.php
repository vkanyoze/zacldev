<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/app.js')
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-indigo-600 to-indigo-800 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 sm:p-8">
        <div class="flex flex-col items-center">
            <a href="https://www.zacl.co.zm/" class="flex justify-center mb-6">
                <img src="/front-logo.png" alt="Zambia AIRPORTS Corporation Limited" class="h-16 w-auto" />
            </a>
            <h2 class="text-2xl sm:text-3xl font-bold text-center text-black mb-2">Reset Password</h2>
            <p class="text-center text-black text-sm mb-6 sm:mb-8">Securely reset your password and regain access to your account.</p>
        </div>
        <form method="POST" action="{{ route('password.request') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-black font-medium mb-2" for="email">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-indigo-600"></i>
                    </div>
                    <input type="email" placeholder="your email address linked to account" name="email" autofocus
                        class="w-full p-3 pl-10 rounded-lg border-2 {{ $errors->has('email') ? 'border-red-500' : 'border-indigo-300' }} focus:outline-none focus:border-indigo-600 text-gray-900 placeholder-gray-400"
                        value="{{ old('email') }}">
                </div>
                @if ($errors->has('email'))
                    <span class="text-sm text-red-600 mt-1">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <button type="submit" class="w-full py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium text-lg hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105">
                Continue
            </button>
            <div class="text-center text-sm text-black">
                You did not see anything in your email? <a href="#" class="text-indigo-600 hover:underline">Resend</a>, if you don't have an account <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Log in</a>
            </div>
        </form>
    </div>
</body>
@if(session('success'))
    <div id="alert" class="text-white fixed drop-shadow-md top-4 right-4 w-96 z-20 flex p-4 mb-4 border-l-4 bg-custom-green" role="alert">
        <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
        <div class="ml-3 font-medium">
            {{ session('success') }} 
        </div>
        <button type="button" onclick="closeAlert()" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-3" aria-label="Close">
        <span class="sr-only">Dismiss</span>
        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    </div>
    <script>
        const alertBox = document.getElementById('alert');
        close.addEvent
        if (alertBox) {
            setTimeout(() => {
                alertBox.remove();
            }, 5000);
        }
        function closeAlert() {
            var alertBox = document.getElementById("alert");
            alertBox.style.display = "none";
        }

        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
@endif
</html>

