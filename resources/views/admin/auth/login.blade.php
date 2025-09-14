@extends('admin.layouts.auth')

@section('content')
<div class="flex flex-col items-center">
    <h2 class="text-2xl sm:text-3xl font-bold text-center text-black mb-2">Admin Login</h2>
    <p class="text-center text-black text-sm mb-6 sm:mb-8">Sign in to your admin account</p>
</div>

        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Error
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

<form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
    @csrf
    <div>
        <label class="block text-black font-medium mb-2" for="email">Email Address</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-slate-600"></i>
            </div>
            <input type="email" placeholder="Email address" name="email" autofocus
                class="w-full p-3 pl-10 rounded-lg border-2 {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }} focus:outline-none focus:border-slate-600 text-gray-900 placeholder-gray-400"
                value="{{ old('email') }}">
        </div>
        @if ($errors->has('email'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('email') }}</span>
        @endif
    </div>
    <div>
        <label class="block text-black font-medium mb-2" for="password">Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-slate-600"></i>
            </div>
            <input id="password" name="password" type="password" autocomplete="off" placeholder="Password"
                class="w-full p-3 pl-10 pr-10 rounded-lg border-2 {{ $errors->has('password') ? 'border-red-500' : 'border-slate-300' }} focus:outline-none focus:border-slate-600 text-gray-900 placeholder-gray-400">
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
            <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300 text-slate-600 focus:ring-slate-600">
            Remember me
        </label>
        <a href="{{ route('admin.password.request') }}" class="text-slate-600 hover:underline text-sm">Forgot password?</a>
    </div>
    <button type="submit" class="w-full py-3 rounded-lg bg-gradient-to-r from-slate-600 to-slate-700 text-white font-medium text-lg hover:from-slate-700 hover:to-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105">
        Sign in to admin
    </button>
</form>

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
</script>
@endsection
