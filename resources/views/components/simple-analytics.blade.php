<!-- Simple Analytics Component (Fallback) -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-custom-gray">
            <i class="fas fa-chart-line text-indigo-600 mr-2"></i>
            Analytics Dashboard
        </h2>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Data Insights</span>
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
        </div>
    </div>

    <!-- Analytics Content -->
    <div id="simple-analytics-content">
        <!-- Revenue Forecast -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-custom-gray">Revenue Forecast</h3>
                <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full">85% confidence</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-emerald-700 font-medium">Next 7 Days</p>
                            <p class="text-2xl font-bold text-emerald-800">$1,250</p>
                        </div>
                        <i class="fas fa-chart-line text-emerald-600 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-700 font-medium">Trend</p>
                            <p class="text-lg font-semibold text-blue-800">Increasing</p>
                        </div>
                        <i class="fas fa-arrow-up text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                    Based on your spending patterns, you're likely to spend $1,250 in the next 7 days. 
                    Consider setting up a budget to optimize your expenses.
                </p>
            </div>
        </div>

        <!-- Payment Activity -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-custom-gray">Payment Activity</h3>
                <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">92% confidence</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-700 font-medium">Expected Payments</p>
                            <p class="text-xl font-bold text-purple-800">8</p>
                        </div>
                        <i class="fas fa-credit-card text-purple-600 text-lg"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-orange-700 font-medium">Peak Day</p>
                            <p class="text-lg font-semibold text-orange-800">Wednesday</p>
                        </div>
                        <i class="fas fa-calendar text-orange-600 text-lg"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-teal-50 to-teal-100 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-teal-700 font-medium">Success Rate</p>
                            <p class="text-lg font-semibold text-teal-800">95%</p>
                        </div>
                        <i class="fas fa-check-circle text-teal-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Insights -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-custom-gray mb-3">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                Key Insights
            </h3>
            <div class="space-y-2">
                <p class="text-sm text-gray-700 flex items-start">
                    <i class="fas fa-chevron-right text-indigo-500 mr-2 mt-1 text-xs"></i>
                    Your spending has increased by 12% compared to last month.
                </p>
                <p class="text-sm text-gray-700 flex items-start">
                    <i class="fas fa-chevron-right text-indigo-500 mr-2 mt-1 text-xs"></i>
                    Most payments occur on Wednesdays between 2-4 PM.
                </p>
                <p class="text-sm text-gray-700 flex items-start">
                    <i class="fas fa-chevron-right text-indigo-500 mr-2 mt-1 text-xs"></i>
                    Consider setting up automatic payments for recurring bills.
                </p>
                <p class="text-sm text-gray-700 flex items-start">
                    <i class="fas fa-chevron-right text-indigo-500 mr-2 mt-1 text-xs"></i>
                    Your payment success rate is excellent at 95%.
                </p>
            </div>
        </div>
    </div>
</div>
