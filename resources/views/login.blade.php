@extends('app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-gray-100 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl">
        <div class="text-center">
            <a href="https://www.zacl.co.zm/" class="flex justify-center">
                <img src="/front-logo.png" alt="ZACL Logo" class="h-16 w-auto">
            </a>
            <h2 class="mt-8 text-3xl font-extrabold text-gray-900">
                Welcome Back
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                @if (Session::has('subHeading'))
                    {!! Session::get('subHeading') !!}
                @else
                    {!! $subHeading ?? 'Please sign in to your account' !!}
                @endif
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login.custom') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email-address" class="sr-only">Email address</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required
                        class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 
                               placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 
                               focus:border-blue-500 focus:z-10 sm:text-sm {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}"
                        placeholder="Email address">
                    @if ($errors->has('email'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('email') }}</p>
                    @endif
                </div>
                <div class="relative">
                    <label for="password" class="sr-only">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 
                                   placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   focus:border-blue-500 focus:z-10 sm:text-sm {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                            placeholder="Password">
                        <button type="button" onclick="togglePassword()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <svg class="h-5 text-gray-500" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" fill="currentColor"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" 
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" 
                                    fill="currentColor"/>
                            </svg>
                        </button>
                    </div>
                    @if ($errors->has('password'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('password') }}</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember" type="checkbox" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <a href="{{ route('forgot') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent 
                           text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Sign in
                </button>
            </div>
        </form>

        <div class="text-center text-sm mt-6">
            <p class="text-gray-600">
                Don't have an account? 
                <a href="{{ route('register-user') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Create Account
                </a>
            </p>
        </div>
    </div>
</div>

@if(session('success'))
<div id="alert" class="fixed top-4 right-4 z-50 flex items-center p-4 mb-4 text-white bg-green-500 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out" role="alert">
    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
    </svg>
    <div>
        <span class="font-medium">Success!</span> {{ session('success') }}
    </div>
    <button type="button" onclick="document.getElementById('alert').remove()" class="ml-auto -mx-1.5 -my-1.5 text-white hover:text-gray-200 rounded-lg p-1.5 inline-flex h-8 w-8">
        <span class="sr-only">Close</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
</div>
@endif

<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
    }

    // Auto-hide success message after 5 seconds
    const alert = document.getElementById('alert');
    if (alert) {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }
</script>
@endsection