@extends('admin.layouts.auth')

@section('content')
<div class="flex flex-col items-center">
    <h2 class="text-2xl sm:text-3xl font-bold text-center text-black mb-2">Admin Registration</h2>
    <p class="text-center text-black text-sm mb-6 sm:mb-8">Create a new admin account</p>
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

        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">
                            Success
                        </h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

<form method="POST" action="{{ route('admin.register') }}" class="space-y-6" enctype="multipart/form-data">
    @csrf
    <div>
        <label class="block text-black font-medium mb-2" for="name">Full Name</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-user text-slate-600"></i>
            </div>
            <input type="text" placeholder="Full name" name="name" autofocus
                class="w-full p-3 pl-10 rounded-lg border-2 {{ $errors->has('name') ? 'border-red-500' : 'border-slate-300' }} focus:outline-none focus:border-slate-600 text-gray-900 placeholder-gray-400"
                value="{{ old('name') }}">
        </div>
        @if ($errors->has('name'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('name') }}</span>
        @endif
    </div>

    <div>
        <label class="block text-black font-medium mb-2" for="email">Email Address</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-slate-600"></i>
            </div>
            <input type="email" placeholder="Email address" name="email"
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

    <div>
        <label class="block text-black font-medium mb-2" for="password_confirmation">Confirm Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-slate-600"></i>
            </div>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="off" placeholder="Confirm password"
                class="w-full p-3 pl-10 pr-10 rounded-lg border-2 {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-slate-300' }} focus:outline-none focus:border-slate-600 text-gray-900 placeholder-gray-400">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" onclick="togglePasswordConfirmation()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i id="togglePasswordConfirmationIcon" class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        @if ($errors->has('password_confirmation'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('password_confirmation') }}</span>
        @endif
    </div>

    <div>
        <label class="block text-black font-medium mb-2" for="avatar">Profile Picture (Optional)</label>
        <div class="relative">
            <input type="file" name="avatar" accept="image/*" 
                class="w-full p-3 rounded-lg border-2 {{ $errors->has('avatar') ? 'border-red-500' : 'border-slate-300' }} focus:outline-none focus:border-slate-600 text-gray-900">
        </div>
        @if ($errors->has('avatar'))
            <span class="text-sm text-red-600 mt-1">{{ $errors->first('avatar') }}</span>
        @endif
    </div>

    <button type="submit" class="w-full py-3 rounded-lg bg-gradient-to-r from-slate-600 to-slate-700 text-white font-medium text-lg hover:from-slate-700 hover:to-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105">
        Create Admin Account
    </button>
</form>

<div class="mt-6 text-center">
    <p class="text-sm text-gray-600">
        Already have an admin account? 
        <a href="{{ route('admin.login') }}" class="text-slate-600 hover:underline font-medium">Sign in here</a>
    </p>
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
        password.focus();
    }

    function togglePasswordConfirmation() {
        const password = document.getElementById('password_confirmation');
        const icon = document.getElementById('togglePasswordConfirmationIcon');
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
