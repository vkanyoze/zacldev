// Lightweight Predictive Analytics for Admin Dashboard
import Chart from 'chart.js/auto';
import * as ss from 'simple-statistics';

class AdminPredictiveAnalytics {
    constructor() {
        this.charts = {};
        this.isInitialized = false;
    }

    async initialize() {
        if (this.isInitialized) return;
        
        try {
            console.log('Admin Predictive Analytics initialized');
            this.isInitialized = true;
        } catch (error) {
            console.error('Failed to initialize analytics:', error);
        }
    }

    // Generate sample data for predictions
    generateSampleData(dataPoints, trend = 'upward') {
        const data = [];
        const labels = [];
        
        for (let i = 0; i < dataPoints; i++) {
            const x = i;
            let y;
            
            switch (trend) {
                case 'upward':
                    y = x * 0.5 + Math.random() * 10 + 50;
                    break;
                case 'downward':
                    y = -x * 0.3 + Math.random() * 10 + 100;
                    break;
                case 'seasonal':
                    y = Math.sin(x * 0.1) * 20 + x * 0.2 + Math.random() * 5 + 50;
                    break;
                default:
                    y = Math.random() * 100;
            }
            
            data.push(y);
            labels.push(`Day ${i + 1}`);
        }
        
        return { data, labels };
    }

    // Linear regression prediction
    predictLinearRegression(historicalData, periods = 7) {
        if (historicalData.length < 2) return [];
        
        const x = historicalData.map((_, i) => i);
        const y = historicalData;
        
        // Calculate linear regression
        const regression = ss.linearRegression(x.map((xi, i) => [xi, y[i]]));
        
        const predictions = [];
        for (let i = 0; i < periods; i++) {
            const nextX = historicalData.length + i;
            const prediction = regression.m * nextX + regression.b;
            predictions.push(Math.max(0, prediction)); // Ensure non-negative
        }
        
        return predictions;
    }

    // Moving average prediction
    predictMovingAverage(historicalData, periods = 7, window = 7) {
        if (historicalData.length < window) return [];
        
        const predictions = [];
        const recentData = historicalData.slice(-window);
        const average = ss.mean(recentData);
        const trend = this.calculateTrend(historicalData);
        
        for (let i = 0; i < periods; i++) {
            const prediction = average + (trend * (i + 1));
            predictions.push(Math.max(0, prediction));
        }
        
        return predictions;
    }

    // Calculate trend from historical data
    calculateTrend(data) {
        if (data.length < 2) return 0;
        
        const firstHalf = data.slice(0, Math.floor(data.length / 2));
        const secondHalf = data.slice(Math.floor(data.length / 2));
        
        const firstAvg = ss.mean(firstHalf);
        const secondAvg = ss.mean(secondHalf);
        
        return (secondAvg - firstAvg) / firstHalf.length;
    }

    // Generate insights based on predictions
    generateInsights(predictions, currentValue, metric) {
        const avgPrediction = ss.mean(predictions);
        const trend = avgPrediction > currentValue ? 'increasing' : 'decreasing';
        const changePercent = ((avgPrediction - currentValue) / currentValue * 100).toFixed(1);
        
        return {
            trend,
            changePercent: Math.abs(changePercent),
            recommendation: this.getRecommendation(trend, changePercent, metric),
            confidence: this.calculateConfidence(predictions)
        };
    }

    getRecommendation(trend, changePercent, metric) {
        if (trend === 'increasing') {
            if (metric === 'revenue') {
                return 'Consider scaling up operations to capitalize on growth';
            } else if (metric === 'users') {
                return 'Prepare infrastructure for increased user load';
            }
        } else {
            if (metric === 'revenue') {
                return 'Review pricing strategy and user engagement';
            } else if (metric === 'users') {
                return 'Focus on user retention and acquisition campaigns';
            }
        }
        return 'Monitor trends closely and adjust strategy accordingly';
    }

    calculateConfidence(predictions) {
        const mean = ss.mean(predictions);
        const variance = ss.variance(predictions);
        const stdDev = Math.sqrt(variance);
        const coefficient = stdDev / mean;
        
        return Math.max(0, Math.min(100, (1 - coefficient) * 100));
    }

    // Create a chart
    createChart(canvasId, data, title, type = 'line') {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return;
        
        const config = {
            type: type,
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Historical Data',
                    data: data.historical,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }, {
                    label: 'Predictions',
                    data: data.predictions,
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderDash: [5, 5],
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: title
                    },
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };
        
        if (this.charts[canvasId]) {
            this.charts[canvasId].destroy();
        }
        
        this.charts[canvasId] = new Chart(ctx, config);
    }

    // Main method to run all predictions
    async runPredictions(historicalData) {
        if (!this.isInitialized) await this.initialize();
        
        try {
            // Generate predictions for different metrics
            const revenuePredictions = this.predictLinearRegression(historicalData.revenue || [], 7);
            const userPredictions = this.predictMovingAverage(historicalData.users || [], 7);
            const paymentPredictions = this.predictLinearRegression(historicalData.payments || [], 7);
            
            return {
                revenue: {
                    predictions: revenuePredictions,
                    insights: this.generateInsights(revenuePredictions, historicalData.revenue[historicalData.revenue.length - 1] || 0, 'revenue')
                },
                users: {
                    predictions: userPredictions,
                    insights: this.generateInsights(userPredictions, historicalData.users[historicalData.users.length - 1] || 0, 'users')
                },
                payments: {
                    predictions: paymentPredictions,
                    insights: this.generateInsights(paymentPredictions, historicalData.payments[historicalData.payments.length - 1] || 0, 'payments')
                }
            };
        } catch (error) {
            console.error('Error running predictions:', error);
            return null;
        }
    }
}

// Export for use in other modules and global scope
window.AdminPredictiveAnalytics = AdminPredictiveAnalytics;

// Also export as default for ES6 modules
export default AdminPredictiveAnalytics;

// Ensure it's available immediately
console.log('AdminPredictiveAnalytics class loaded:', typeof AdminPredictiveAnalytics);
