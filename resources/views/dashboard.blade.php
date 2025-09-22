@extends('new-app')
@section('content')
@include('new-header')
<div class="flex-grow flex">
    @include('new-side-bar', ['activeNavItem' => 'dashboard'])
    <main class="w-screen text-gray-700 pt-16 md:pt-0 lg:pt-0">
        <div class="w-full sm:w-full lg:w-11/12 md:w-full p-6 sm:p-6 md:p-8 lg:p-12 lg:pt-3 mt-10 lg:mt-0 md:mt-0">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-custom-gray mb-2">Welcome back, {{ explode('@', $user->email)[0] }}!</h1>
                <p class="text-lighter-gray text-lg">Here's what's happening with your account today.</p>
            </div>

            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Payments Card -->
                <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-white mb-1">{{ $paymentsCount }}</div>
                            <div class="text-emerald-100 text-sm font-medium">Payments Made</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-credit-card text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-emerald-100 text-sm">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>Active account</span>
                    </div>
                </div>

                <!-- Cards Saved -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-white mb-1">{{ $cardsCount }}</div>
                            <div class="text-indigo-100 text-sm font-medium">Cards Saved</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-wallet text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-indigo-100 text-sm">
                        <i class="fas fa-shield-alt mr-1"></i>
                        <span>Securely stored</span>
                    </div>
                </div>

                <!-- Total Spent -->
                <div class="bg-gradient-to-br from-rose-500 to-rose-700 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-white mb-1">${{ number_format($totalSpent, 2) }}</div>
                            <div class="text-rose-100 text-sm font-medium">Total Spent</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-rose-100 text-sm">
                        <i class="fas fa-trending-up mr-1"></i>
                        <span>Lifetime total</span>
                    </div>
                </div>

                <!-- Last Payment -->
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-white mb-1">
                                @if($lastPayment)
                                    ${{ number_format($lastPayment->amount_spend, 2) }}
                                @else
                                    $0.00
                                @endif
                            </div>
                            <div class="text-amber-100 text-sm font-medium">Last Payment</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-amber-100 text-sm">
                        @if($lastPayment)
                            <i class="fas fa-calendar mr-1"></i>
                            <span>{{ $lastPayment->created_at->format('M d') }}</span>
                        @else
                            <i class="fas fa-info-circle mr-1"></i>
                            <span>No payments yet</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Predictive Analytics Section -->
            <div class="mb-8">
                @include('components.simple-analytics')
            </div>

            <!-- Recent Activity Section -->
            <div class="mb-8">
                <!-- Recent Activity Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-custom-gray">Recent Activity</h2>
                        <a href="{{ route('payments.index') }}" class="text-custom-green hover:text-dark-green text-sm font-medium">View All</a>
                    </div>
                    
                    @if($lastPayment)
                        <div class="space-y-4">
                            <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border-l-4 border-custom-green">
                                <div class="bg-custom-green rounded-full p-2 mr-4">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-custom-gray">Payment Processed</div>
                                    <div class="text-sm text-gray-600">${{ number_format($lastPayment->amount_spend, 2) }} • {{ $lastPayment->created_at->format('M d, Y') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-custom-green">{{ $lastPayment->status }}</div>
                                    <div class="text-xs text-gray-500">Card #{{ $lastPayment->card_id }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-inbox text-gray-400 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-600 mb-2">No recent activity</h3>
                            <p class="text-gray-500 text-sm">Start making payments to see your activity here.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('payments.index') }}" class="w-full sm:w-auto bg-gradient-to-r from-emerald-400 to-emerald-600 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold text-center">
                    <i class="fas fa-credit-card mr-2"></i>
                    Manage Payments
                </a>
                <a href="{{ route('payments.create') }}" class="w-full sm:w-auto bg-gradient-to-r from-indigo-500 to-indigo-700 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold text-center">
                    <i class="fas fa-plus mr-2"></i>
                    New Payment
                </a>
            </div>
        </div>
    </main>
</div>
@endsection