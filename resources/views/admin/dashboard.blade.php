<x-admin-layout>
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-custom-gray mb-2">Welcome back, {{ Auth::guard('admin')->user()->name }}!</h1>
        <p class="text-gray-600 text-lg">Here's what's happening with your admin panel today.</p>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-white mb-1">{{ $stats['total_users'] }}</div>
                    <div class="text-emerald-100 text-sm font-medium">Total Users</div>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-emerald-100 text-sm">
                <i class="fas fa-arrow-up mr-1"></i>
                <span>{{ $stats['growth']['users'] }}% from last month</span>
            </div>
        </div>

        <!-- Active Users Card -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-white mb-1">{{ $stats['active_users'] }}</div>
                    <div class="text-indigo-100 text-sm font-medium">Active Users</div>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-user-check text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-indigo-100 text-sm">
                <i class="fas fa-shield-alt mr-1"></i>
                <span>Online now</span>
            </div>
        </div>

        <!-- Total Payments Card -->
        <div class="bg-gradient-to-br from-rose-500 to-rose-700 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-white mb-1">{{ $stats['total_payments'] }}</div>
                    <div class="text-rose-100 text-sm font-medium">Total Payments</div>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-credit-card text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-rose-100 text-sm">
                <i class="fas fa-chart-line mr-1"></i>
                <span>{{ $stats['payment_success_rate'] }}% success rate</span>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-white mb-1">ZMW {{ number_format($stats['revenue'], 2) }}</div>
                    <div class="text-amber-100 text-sm font-medium">Total Revenue</div>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-amber-100 text-sm">
                <i class="fas fa-trending-up mr-1"></i>
                <span>{{ $stats['growth']['revenue'] }}% from last month</span>
            </div>
        </div>
    </div>

    <!-- Predictive Analytics Section -->
    <div class="mb-8">
        @include('components.simple-admin-analytics')
    </div>

    <!-- Recent Activity Section -->
    <div class="mb-8">
        <!-- Recent Activity Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-custom-gray">Recent Activity</h2>
                <a href="{{ route('admin.payments.index') }}" class="text-custom-green hover:text-green-600 text-sm font-medium">View All</a>
            </div>
            
            @if($recentPayments->count() > 0)
                <div class="space-y-4">
                    @foreach($recentPayments->take(5) as $payment)
                        <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border-l-4 border-custom-green">
                            <div class="bg-custom-green rounded-full p-2 mr-4">
                                <i class="fas fa-credit-card text-white text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-custom-gray">Payment Processed</div>
                                <div class="text-sm text-gray-600">ZMW {{ number_format($payment->amount_spend, 2) }} • {{ $payment->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-custom-green">{{ ucfirst($payment->status) }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->user->name ?? 'Unknown User' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-inbox text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-600 mb-2">No recent activity</h3>
                    <p class="text-gray-500 text-sm">No payments have been processed yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Bottom Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
        <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto bg-gradient-to-r from-emerald-400 to-emerald-600 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold text-center">
            <i class="fas fa-users mr-2"></i>
            Manage Users
        </a>
        <a href="{{ route('admin.payments.index') }}" class="w-full sm:w-auto bg-gradient-to-r from-indigo-500 to-indigo-700 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold text-center">
            <i class="fas fa-credit-card mr-2"></i>
            Manage Payments
        </a>
    </div>

    @push('scripts')
    <script>
        // Auto-refresh dashboard every 5 minutes
        document.addEventListener('DOMContentLoaded', function() {
            const refreshInterval = setInterval(() => {
                window.location.reload();
            }, 5 * 60 * 1000);

            // Clean up on page navigation
            window.addEventListener('beforeunload', () => {
                clearInterval(refreshInterval);
            });
        });
    </script>
    @endpush
</x-admin-layout>
