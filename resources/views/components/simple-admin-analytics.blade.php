<!-- Simple Admin Analytics Component (Fallback) -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-custom-gray">
            <i class="fas fa-chart-line text-slate-600 mr-2"></i>
            Business Intelligence Dashboard
        </h2>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Analytics & Insights</span>
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
        </div>
    </div>

    <!-- Analytics Content -->
    <div id="simple-admin-analytics-content">
        <!-- Business Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Revenue Forecast -->
            <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-white mb-1">ZMW 15,250</div>
                        <div class="text-emerald-100 text-sm font-medium">7-Day Revenue Forecast</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-emerald-100 text-sm">
                    <i class="fas fa-arrow-up mr-1 text-green-400"></i>
                    <span>+8.5% growth</span>
                </div>
            </div>

            <!-- User Growth -->
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-white mb-1">23</div>
                        <div class="text-indigo-100 text-sm font-medium">Expected New Users</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-indigo-100 text-sm">
                    <i class="fas fa-arrow-up mr-1 text-green-400"></i>
                    <span>+12% growth</span>
                </div>
            </div>

            <!-- Payment Volume -->
            <div class="bg-gradient-to-br from-rose-500 to-rose-700 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-white mb-1">156</div>
                        <div class="text-rose-100 text-sm font-medium">Expected Payments</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-credit-card text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-rose-100 text-sm">
                    <i class="fas fa-arrow-up mr-1 text-green-400"></i>
                    <span>+15% growth</span>
                </div>
            </div>

            <!-- System Load -->
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-white mb-1">68%</div>
                        <div class="text-amber-100 text-sm font-medium">Peak Load Expected</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <i class="fas fa-server text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-amber-100 text-sm">
                    <i class="fas fa-minus mr-1 text-yellow-400"></i>
                    <span>Stable</span>
                </div>
            </div>
        </div>

        <!-- Detailed Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Revenue Analysis -->
            <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Revenue Analysis
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-emerald-700">Current Monthly Revenue:</span>
                        <span class="font-semibold text-emerald-800">ZMW 45,750</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-emerald-700">Predicted Next Month:</span>
                        <span class="font-semibold text-emerald-800">ZMW 52,100</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-emerald-700">Growth Rate:</span>
                        <span class="font-semibold text-emerald-800">+13.9%</span>
                    </div>
                    <div class="mt-4 p-3 bg-white bg-opacity-50 rounded-lg">
                        <p class="text-sm text-emerald-700">
                            Revenue is trending upward with strong growth potential. Consider scaling operations to capitalize on this growth.
                        </p>
                    </div>
                </div>
            </div>

            <!-- User Growth Analysis -->
            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-indigo-800 mb-4">
                    <i class="fas fa-user-plus mr-2"></i>
                    User Growth Analysis
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-indigo-700">Current Active Users:</span>
                        <span class="font-semibold text-indigo-800">220</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-indigo-700">Predicted New Users:</span>
                        <span class="font-semibold text-indigo-800">23</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-indigo-700">Retention Rate:</span>
                        <span class="font-semibold text-indigo-800">87%</span>
                    </div>
                    <div class="mt-4 p-3 bg-white bg-opacity-50 rounded-lg">
                        <p class="text-sm text-indigo-700">
                            User growth is strong with excellent retention. Prepare infrastructure for increased user load.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Intelligence Insights -->
        <div class="bg-gradient-to-r from-slate-50 to-gray-50 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                Business Intelligence Insights
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-chart-line text-emerald-600 text-lg mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-emerald-800 mb-2">Revenue Optimization</h4>
                            <p class="text-sm text-emerald-700">Based on current trends, consider implementing dynamic pricing to maximize the 13.9% growth potential.</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-users text-indigo-600 text-lg mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-indigo-800 mb-2">User Acquisition</h4>
                            <p class="text-sm text-indigo-700">With 23 new users expected, prepare your infrastructure and customer support team.</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-rose-50 border border-rose-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-credit-card text-rose-600 text-lg mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-rose-800 mb-2">Payment Processing</h4>
                            <p class="text-sm text-rose-700">Expected 156 payments next week. Ensure payment gateway capacity is optimized.</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-server text-amber-600 text-lg mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-amber-800 mb-2">System Performance</h4>
                            <p class="text-sm text-amber-700">Monitor server load during peak hours. Consider auto-scaling for optimal performance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="mt-6 bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">
                <i class="fas fa-tachometer-alt mr-2"></i>
                Performance Metrics
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-slate-800">94%</div>
                    <div class="text-sm text-gray-600">Model Accuracy</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-slate-800">91%</div>
                    <div class="text-sm text-gray-600">Data Quality Score</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-slate-800">89%</div>
                    <div class="text-sm text-gray-600">Prediction Confidence</div>
                </div>
            </div>
        </div>
    </div>
</div>
