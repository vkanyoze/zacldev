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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
    
    <style>
        :root {
            --primary-color: #1a365d; /* Custom gray from main interface */
            --accent-color: #3b82f6; /* Blue accent color */
        }
        
        .bg-custom-gray {
            background-color: var(--primary-color);
        }
        
        .text-custom-gray {
            color: var(--primary-color);
        }
        
        .bg-custom-green {
            background-color: #10B981;
        }
        
        .text-custom-green {
            color: #10B981;
        }
        
        .loader {
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--accent-color);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 2s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-80 bg-custom-gray shadow-md hidden md:block lg:block text-white">
            <nav class="p-0">
                <ul class="mt-2 relative">
                    <li class="mt-4 relative cursor-pointer">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center text-sm py-3 px-6 h-12 overflow-hidden {{ request()->routeIs('admin.dashboard') ? 'bg-custom-green text-white hover:bg-custom-green font-bold' : 'text-white hover:text-gray-900 hover:bg-gray-100' }} text-ellipsis whitespace-nowrap transition duration-300 ease-in-out">
                            <i class="fas fa-tachometer-alt w-4 h-4 mr-2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="mt-4 relative cursor-pointer">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center text-sm py-3 px-6 h-12 overflow-hidden {{ request()->routeIs('admin.users.*') ? 'bg-custom-green text-white hover:bg-custom-green font-bold' : 'text-white hover:text-gray-900 hover:bg-gray-100' }} text-ellipsis whitespace-nowrap transition duration-300 ease-in-out">
                            <i class="fas fa-users w-4 h-4 mr-2"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="mt-2 relative cursor-pointer">
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center text-sm py-3 px-6 h-12 overflow-hidden {{ request()->routeIs('admin.payments.*') ? 'bg-custom-green text-white hover:bg-custom-green font-bold' : 'text-white hover:text-gray-900 hover:bg-gray-100' }} text-ellipsis whitespace-nowrap transition duration-300 ease-in-out">
                            <i class="fas fa-credit-card w-4 h-4 mr-2"></i>
                            <span>Payments</span>
                        </a>
                    </li>
                    <li class="mt-2 relative cursor-pointer">
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center text-sm py-3 px-6 h-12 overflow-hidden {{ request()->routeIs('admin.settings*') ? 'bg-custom-green text-white hover:bg-custom-green font-bold' : 'text-white hover:text-gray-900 hover:bg-gray-100' }} text-ellipsis whitespace-nowrap transition duration-300 ease-in-out">
                            <i class="fas fa-cog w-4 h-4 mr-2"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
                    </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex justify-between items-center p-4">
                    <div class="flex items-center">
                        <img src="/front-logo.png" alt="logo" class="h-8 mr-4"/>
                        <div class="text-custom-gray font-bold text-lg">Admin Panel</div>
            </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Welcome, {{ Auth::guard('admin')->user()->name }}</span>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-300">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Log out
                            </button>
                            </form>
                </div>
            </div>
        </header>

        <!-- Page Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
            </div>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>
