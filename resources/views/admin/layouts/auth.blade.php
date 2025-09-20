<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=circular:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-blue-600 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <!-- Logo and Branding -->
        <div class="flex items-center justify-center mb-8">
            <div class="flex items-center space-x-3">
                <!-- Logo with green triangles -->
                <div class="relative">
                    <div class="w-8 h-8 bg-green-500 rounded-sm transform rotate-45"></div>
                    <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-sm transform rotate-45"></div>
                </div>
                <div class="text-left">
                    <div class="text-sm text-gray-600">Zambia</div>
                    <div class="text-2xl font-bold text-green-600">AIRPORTS</div>
                    <div class="text-sm text-gray-600">Corporation Limited</div>
                </div>
            </div>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>
