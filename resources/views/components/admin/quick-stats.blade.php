@props([
    'stats' => [
        'total_users' => 0,
        'active_users' => 0,
        'total_payments' => 0,
        'revenue' => 0,
        'growth' => [
            'users' => 0,
            'revenue' => 0
        ]
    ]
])

<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Total Users -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 truncate">Total Users</p>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ number_format($stats['total_users']) }}
                    </div>
                </div>
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            @if(isset($stats['growth']['users']))
            <div class="mt-2">
                <span class="inline-flex items-center text-sm font-medium {{ $stats['growth']['users'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    @if($stats['growth']['users'] >= 0)
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @endif
                    {{ abs($stats['growth']['users']) }}% from last month
                </span>
            </div>
            @endif
        </div>
    </div>

    <!-- Active Users -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 truncate">Active Users</p>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ number_format($stats['active_users']) }}
                    </div>
                </div>
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $activePercentage = $stats['total_users'] > 0 ? ($stats['active_users'] / $stats['total_users']) * 100 : 0;
                    @endphp
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $activePercentage }}%"></div>
                </div>
                <p class="mt-1 text-xs text-gray-500">{{ number_format($activePercentage, 1) }}% of total users</p>
            </div>
        </div>
    </div>

    <!-- Total Payments -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 truncate">Total Payments</p>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ number_format($stats['total_payments']) }}
                    </div>
                </div>
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-4 w-4 text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="ml-2 text-sm text-gray-500">
                        <span class="font-medium text-gray-900">12%</span> from last month
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 truncate">Total Revenue</p>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">
                        ZMW {{ number_format($stats['revenue'], 2) }}
                    </div>
                </div>
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            @if(isset($stats['growth']['revenue']))
            <div class="mt-2">
                <span class="inline-flex items-center text-sm font-medium {{ $stats['growth']['revenue'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    @if($stats['growth']['revenue'] >= 0)
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @endif
                    {{ abs($stats['growth']['revenue']) }}% from last month
                </span>
            </div>
            @endif
        </div>
    </div>
</div>
