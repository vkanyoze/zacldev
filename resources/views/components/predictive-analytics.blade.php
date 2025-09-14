<!-- Predictive Analytics Component -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-custom-gray">
            <i class="fas fa-brain text-indigo-600 mr-2"></i>
            Predictive Analytics
        </h2>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">AI-Powered Insights</span>
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="analytics-loading" class="text-center py-8">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mb-4"></div>
        <p class="text-gray-600">Analyzing your data patterns...</p>
    </div>

    <!-- Analytics Content -->
    <div id="analytics-content" class="hidden">
        <!-- Revenue Prediction -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-custom-gray">Revenue Forecast</h3>
                <span id="revenue-confidence" class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full"></span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-emerald-700 font-medium">Next 7 Days</p>
                            <p id="revenue-prediction" class="text-2xl font-bold text-emerald-800">$0.00</p>
                        </div>
                        <i class="fas fa-chart-line text-emerald-600 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-700 font-medium">Trend</p>
                            <p id="revenue-trend" class="text-lg font-semibold text-blue-800">Stable</p>
                        </div>
                        <i id="revenue-trend-icon" class="fas fa-arrow-right text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <p id="revenue-insight" class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg"></p>
            </div>
        </div>

        <!-- Payment Activity Prediction -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-custom-gray">Payment Activity</h3>
                <span id="payment-confidence" class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full"></span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-700 font-medium">Expected Payments</p>
                            <p id="payment-prediction" class="text-xl font-bold text-purple-800">0</p>
                        </div>
                        <i class="fas fa-credit-card text-purple-600 text-lg"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-orange-700 font-medium">Peak Day</p>
                            <p id="peak-day" class="text-lg font-semibold text-orange-800">Monday</p>
                        </div>
                        <i class="fas fa-calendar text-orange-600 text-lg"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-teal-50 to-teal-100 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-teal-700 font-medium">Success Rate</p>
                            <p id="success-rate" class="text-lg font-semibold text-teal-800">95%</p>
                        </div>
                        <i class="fas fa-check-circle text-teal-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Container -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-custom-gray mb-4">Trend Visualization</h3>
            <div id="prediction-chart" class="bg-gray-50 rounded-xl p-4 h-64 flex items-center justify-center">
                <p class="text-gray-500">Chart will appear here</p>
            </div>
        </div>

        <!-- Insights -->
        <div class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-custom-gray mb-3">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                AI Insights
            </h3>
            <div id="ai-insights" class="space-y-2">
                <!-- Insights will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Error State -->
    <div id="analytics-error" class="hidden text-center py-8">
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
            <p class="text-red-700 font-medium">Unable to load analytics</p>
            <p class="text-red-600 text-sm">Please try refreshing the page</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    // Wait for both PredictiveAnalytics and TensorFlow to be available
    if (typeof PredictiveAnalytics === 'undefined') {
        console.error('PredictiveAnalytics class not found. Make sure the JavaScript is properly loaded.');
        document.getElementById('analytics-loading').classList.add('hidden');
        document.getElementById('analytics-error').classList.remove('hidden');
        return;
    }
    
    if (typeof tf === 'undefined') {
        console.error('TensorFlow.js not found. Make sure the JavaScript is properly loaded.');
        document.getElementById('analytics-loading').classList.add('hidden');
        document.getElementById('analytics-error').classList.remove('hidden');
        return;
    }
    
    // Wait for TensorFlow to be ready
    try {
        await tf.ready();
        console.log('TensorFlow.js is ready for analytics');
    } catch (error) {
        console.error('TensorFlow.js failed to initialize:', error);
        document.getElementById('analytics-loading').classList.add('hidden');
        document.getElementById('analytics-error').classList.remove('hidden');
        return;
    }
    
    const analytics = new PredictiveAnalytics();
    
    try {
        // Show loading state
        document.getElementById('analytics-loading').classList.remove('hidden');
        
        // Simulate historical data (in real app, this would come from your API)
        const historicalData = {
            revenue: [120, 135, 142, 158, 165, 172, 180, 185, 192, 200, 210, 220, 230, 240, 250],
            users: [45, 48, 52, 55, 58, 62, 65, 68, 72, 75, 78, 82, 85, 88, 92]
        };
        
        // Run predictions
        const results = await analytics.runPredictions(historicalData);
        
        if (results) {
            // Update revenue predictions
            const revenueAvg = results.revenue.predictions.reduce((a, b) => a + b, 0) / results.revenue.predictions.length;
            document.getElementById('revenue-prediction').textContent = `$${revenueAvg.toFixed(2)}`;
            document.getElementById('revenue-trend').textContent = results.revenue.insights.trend;
            document.getElementById('revenue-confidence').textContent = `${results.revenue.insights.confidence.toFixed(0)}% confidence`;
            document.getElementById('revenue-insight').textContent = results.revenue.insights.recommendation;
            
            // Update trend icon
            const trendIcon = document.getElementById('revenue-trend-icon');
            if (results.revenue.insights.trend === 'increasing') {
                trendIcon.className = 'fas fa-arrow-up text-green-600 text-xl';
            } else if (results.revenue.insights.trend === 'decreasing') {
                trendIcon.className = 'fas fa-arrow-down text-red-600 text-xl';
            }
            
            // Update payment predictions
            const paymentAvg = results.users.predictions.reduce((a, b) => a + b, 0) / results.users.predictions.length;
            document.getElementById('payment-prediction').textContent = Math.round(paymentAvg);
            document.getElementById('payment-confidence').textContent = `${results.users.insights.confidence.toFixed(0)}% confidence`;
            
            // Generate AI insights
            const insights = [
                `Based on your spending patterns, you're likely to make ${Math.round(paymentAvg)} payments in the next week.`,
                `Your revenue trend is ${results.revenue.insights.trend} with ${results.revenue.insights.changePercent}% change expected.`,
                `Peak payment activity typically occurs on ${['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'][Math.floor(Math.random() * 5)]}.`,
                results.revenue.insights.recommendation
            ];
            
            const insightsContainer = document.getElementById('ai-insights');
            insights.forEach(insight => {
                const insightElement = document.createElement('p');
                insightElement.className = 'text-sm text-gray-700 flex items-start';
                insightElement.innerHTML = `<i class="fas fa-chevron-right text-indigo-500 mr-2 mt-1 text-xs"></i>${insight}`;
                insightsContainer.appendChild(insightElement);
            });
            
            // Hide loading, show content
            document.getElementById('analytics-loading').classList.add('hidden');
            document.getElementById('analytics-content').classList.remove('hidden');
        } else {
            throw new Error('Failed to generate predictions');
        }
        
    } catch (error) {
        console.error('Analytics error:', error);
        document.getElementById('analytics-loading').classList.add('hidden');
        document.getElementById('analytics-error').classList.remove('hidden');
    }
});
</script>
