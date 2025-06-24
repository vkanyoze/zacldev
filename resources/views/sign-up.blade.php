<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/app.js')
    <title>Create Account</title>
</head>
<body class="bg-white md:bg-custom-gray lg:bg-custom-gray min-h-screen flex flex-col items-center justify-center">
    <div class="bg-white shadow-0 md:shadow-lg lg:shadow-lg lg:bg-white  w-full max-w-lg p-8 mb-8">
        <div class="flex flex-col items-center">
            <div class="flex justify-center">
                <a href="https://www.zacl.co.zm/"><img src="/front-logo.png" alt="logo" /></a>
            </div>
            <h3 class="mt-9 text-2xl font-bold text-center text-custom-gray lg:text-custom-gray">Create Account</h3>
            <p class="mt-2 text-center text-custom-gray">
                Access an efficient and secure platform for managing your aeronautical service fees
               
            </p>
        </div>
        <div class="w-full justify-center hidden">
        <div role="status" class="w-8 h-8">
            <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
            <span class="sr-only">Loading...</span>
        </div>
        </div>
        <form method="POST" action="{{ route('register.custom') }}">
            @csrf
            <div class="mt-6">
                <div>
                    <label class="block text-custom-gray font-bold" for="email">Email<label>
                            <input type="email" placeholder="Email" name="email" class="mt-2 w-full p-3 rounded border-2 md:border lg:border {{ $errors->has('email') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600  lg:focus:border-blue-600 font-normal">
                        @if ($errors->has('email'))
                            <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('email') }}</span>
                        @endif
                </div>
                <label class="mt-4 block text-custom-gray font-bold" for="email">Password</label>
                <div class="relative w-full mt-1">
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 py-2">
                        <input class="hidden js-password-toggle" id="toggle" type="checkbox" />
                        <label class="mt-1.5 bg-gray-300 hover:bg-gray-400 rounded px-2 py-1 text-sm text-gray-600 cursor-pointer js-password-label " for="toggle">show</label>
                    </div> <input id="password" name="password" type="password" autocomplete="off" placeholder="password" class="js-password mt-2 w-full p-3 rounded border-2 md:border lg:border  {{ $errors->has('password') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600  lg:focus:border-blue-600 font-normal">
                </div>
                @if ($errors->has('password'))
                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('password') }}</span>
                @endif
                <div class="mt-9">
                    <button class="px-6 py-4 focus:ring-4 focus:ring-blue-300 rounded mt-4 w-full text-white bg-custom-green text-lg font-bold">Continue</button>
                </div>
                <div class="mt-4 w-full justify-center text-sm text-center text-custom-gray font-normal">
                    Forgot your password ? <a href="{{ route('forgot') }}" class="text-blue-600 lg:text-blue-600 hover:underline font-bold">Reset Password</a> if you already  have an account <a href="{{ route('login') }}" class="text-blue-600 lg:text-blue-600 hover:underline font-bold">Sign In here</a> .Resend your email verification <a href="{{ route('resend') }}" class="text-blue-600 lg:text-blue-600 hover:underline font-bold">Here</a>
                </div>
            </div>
        </form>

    </div>
    <footer class="text-center  text-custom-gray md:text-gray-200 lg:text-gray-200 text-xs mb-4">
        &copy; <span id="year">2024</span> Zambia Airports Corporation&trade;. All Rights Reserved.
    </footer>
</body>
<script>
const passwordToggle = document.querySelector('.js-password-toggle')
passwordToggle.addEventListener('change', function() {
  const password = document.querySelector('.js-password'),
    passwordLabel = document.querySelector('.js-password-label')

  if (password.type === 'password') {
    password.type = 'text'
    passwordLabel.innerHTML = 'hide'
  } else {
    password.type = 'password'
    passwordLabel.innerHTML = 'show'
  }

  password.focus()
})

document.getElementById('year').textContent = new Date().getFullYear();
</script>
</html>
