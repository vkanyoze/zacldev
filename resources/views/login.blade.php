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
            
            <div class="mt-6 grid grid-cols-2 gap-3">
                <!-- Google Login -->
                <a href="{{ route('auth.google') }}" 
                   class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span class="ml-2">Google</span>
                </a>
                
                <!-- Facebook Login -->
                <a href="{{ route('auth.facebook') }}" 
                   class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-all duration-300 transform hover:scale-105">
                    <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span class="ml-2">Facebook</span>
                </a>
            </div>
        </div>

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