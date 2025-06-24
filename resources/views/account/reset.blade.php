@extends('app')
@section('content')
<div class="flex items-center justify-center min-h-screen bg-custom-gray">
    <div class="px-10 py-8 text-left bg-white shadow-lg">
        <div class="flex justify-center">
            <a href="https://www.zacl.co.zm/"><img src="/front-logo.png" alt="logo" /></a>
        </div>
        <h3 class="mt-9 text-2xl font-bold text-center text-custom-gray">Update your password</h3>
        <p class="mt-2 text-center text-custom-gray">
            set your password with a new secure one
        </p>
        <form method="POST" action="{{ route('reset.password.post') }}">
            @csrf
            <input type="text" placeholder="Email" name="token" value="{{ $token }}" class="mt-2 w-full p-3 rounded border hidden focus:outline-none focus:border-blue-600 font-normal">

            <div class="mt-6">
                <div>
                    <label class="block text-custom-gray font-bold" for="email">Email<label>
                            <input type="email" placeholder="Email" name="email" class="mt-2 w-full p-3 rounded border {{ $errors->has('email') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                            @if ($errors->has('email'))
                            <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('email') }}</span>
                            @endif
                </div>
                <label class="mt-4 block text-custom-gray font-bold" for="email">Password</label>
                <div class="relative w-full mt-1">
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 py-2">
                        <input class="hidden js-password-toggle" id="toggle" type="checkbox" />
                        <label class="mt-1.5 bg-gray-300 hover:bg-gray-400 rounded px-2 py-1 text-sm text-gray-600 cursor-pointer js-password-label " for="toggle">show</label>
                    </div> <input id="password" name="password" type="password" autocomplete="off" placeholder="password" class="js-password mt-2 w-full p-3 rounded border {{ $errors->has('password') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                </div>
                @if ($errors->has('password'))
                <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('password') }}</span>
                @endif
                <div class="mt-9">
                    <button class="px-6 py-4 focus:ring-4 focus:ring-blue-300 rounded mt-4 w-full text-white bg-custom-green text-lg font-bold">Continue</button>
                </div>
                <div class="mt-4 w-full justify-center text-sm text-center text-custom-gray font-normal">
                    Forgot your password ? <a href="{{ route('forgot') }}" class="text-blue-900 hover:underline font-bold">Reset Password</a> if you don't have an account <br> <a href="{{ route('register-user') }}" class="text-blue-900 hover:underline font-bold">Create Account</a>
                </div>
            </div>
        </form>
    </div>
</div>
@include('footer')
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
</script>
@endsection