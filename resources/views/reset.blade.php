<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/app.js')
    <title>Sign In</title>
</head>
<body class="bg-white md:bg-custom-gray lg:bg-custom-gray  min-h-screen flex flex-col items-center justify-center">
    <div class="bg-white shadow-0 md:shadow-lg lg:shadow-lg lg:bg-white  w-full max-w-lg p-8 mb-8">
        <div class="flex flex-col items-center">
            <div class="flex justify-center">
                <a href="https://www.zacl.co.zm/"><img src="/front-logo.png" alt="logo" /></a>
            </div>
            <h3 class="mt-9 text-2xl font-bold text-center text-custom-gray lg:text-custom-gray">Reset password</h3>
            <p class="mt-2 text-center text-custom-gray lg:text-custom-gray">
                Securely reset your password and regain access to your account.
            </p>
        </div>
        <form method="POST" action="{{ route('password.request') }}">
            @csrf
            <div class="mt-6">
                <div>
                    <label class="block text-white md:text-custom-gray lg:text-custom-gray font-bold" for="email">Email<label>
                            <input type="email" placeholder="Email" name="email" class="mt-2 w-full p-3 rounded border-2 md:border lg:border {{ $errors->has('email') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600  lg:focus:border-blue-600 font-normal">
                        @if ($errors->has('email'))
                            <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('email') }}</span>
                        @endif
                </div>
                <div class="mt-9">
                    <button class="px-6 py-4 focus:ring-4 focus:ring-blue-300 rounded mt-4 w-full text-white bg-custom-green text-lg font-bold">Continue</button>
                </div>
                <div class="mt-4 w-full justify-center text-sm text-center text-custom-gray font-normal">
                Don't have an account? <a href="{{ route('register-user') }}" class="text-blue-600 hover:underline font-bold">Create Account</a> if you already have an account <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-bold">Sign in here</a>
                </div>
            </div>
        </form>

    </div>
    <footer class="text-cent text-custom-gray md:text-gray-200 lg:text-gray-200 text-xs mb-4">
        &copy; <span id="year">2024</span> Zambia Airports Corporation&trade;. All Rights Reserved.
    </footer>
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

