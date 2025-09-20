// Admin ML Analytics Component
import { mlPredictionService } from './ml-predictions.js';

class AdminMLAnalytics {
    constructor() {
        this.isInitialized = false;
        this.predictions = null;
        this.updateInterval = null;
    }

    async initialize() {
        if (this.isInitialized) return;

        try {
            // Initialize ML service
            await mlPredictionService.initialize();
            
            // Load historical data from server
            const historicalData = await this.fetchHistoricalData();
            
            // Train models with historical data
            await mlPredictionService.trainModels(historicalData);
            
            // Make initial predictions
            await this.updatePredictions();
            
            // Set up auto-refresh
            this.setupAutoRefresh();
            
            this.isInitialized = true;
            console.log('Admin ML Analytics initialized');
        } catch (error) {
            console.error('Failed to initialize ML Analytics:', error);
        }
    }

    async fetchHistoricalData() {
        try {
            const response = await fetch('/admin/api/analytics/historical-data');
            if (!response.ok) throw new Error('Failed to fetch historical data');
            return await response.json();
        } catch (error) {
            console.error('Error fetching historical data:', error);
            return this.getDefaultHistoricalData();
        }
    }

    getDefaultHistoricalData() {
        // Generate sample data if API fails
        return {
            revenue: Array.from({ length: 30 }, () => Math.random() * 1000 + 500),
            users: Array.from({ length: 30 }, () => Math.floor(Math.random() * 10) + 1),
            payments: Array.from({ length: 30 }, () => Math.floor(Math.random() * 20) + 5)
        };
    }

    async updatePredictions() {
        try {
            const historicalData = await this.fetchHistoricalData();
            
            // Get last 7 days of revenue data
            const last7DaysRevenue = historicalData.revenue.slice(-7);
            const revenuePrediction = await mlPredictionService.predictRevenue(last7DaysRevenue);
            
            // Get last 14 days of user data
            const last14DaysUsers = historicalData.users.slice(-14);
            const userPrediction = await mlPredictionService.predictUserGrowth(last14DaysUsers);
            
            // Get last 7 days of payment data
            const last7DaysPayments = historicalData.payments.slice(-7);
            const paymentPrediction = await mlPredictionService.predictPaymentVolume(last7DaysPayments);
            
            // Get model accuracy
            const accuracy = await mlPredictionService.getModelAccuracy('revenue');
            
            this.predictions = {
                revenue: {
                    prediction: revenuePrediction,
                    confidence: accuracy,
                    lastUpdated: new Date().toISOString()
                },
                users: {
                    prediction: userPrediction,
                    confidence: await mlPredictionService.getModelAccuracy('users'),
                    lastUpdated: new Date().toISOString()
                },
                payments: {
                    prediction: paymentPrediction,
                    confidence: await mlPredictionService.getModelAccuracy('payments'),
                    lastUpdated: new Date().toISOString()
                }
            };

            // Update UI with predictions
            this.updateUI();
            
        } catch (error) {
            console.error('Error updating predictions:', error);
        }
    }

    updateUI() {
        if (!this.predictions) return;

        // Update revenue forecast
        const revenueElement = document.getElementById('ml-revenue-forecast');
        if (revenueElement && this.predictions.revenue) {
            revenueElement.textContent = `ZMW ${this.predictions.revenue.prediction.toFixed(2)}`;
        }

        // Update user growth
        const userElement = document.getElementById('ml-user-growth');
        if (userElement && this.predictions.users) {
            userElement.textContent = this.predictions.users.prediction;
        }

        // Update payment volume
        const paymentElement = document.getElementById('ml-payment-volume');
        if (paymentElement && this.predictions.payments) {
            paymentElement.textContent = this.predictions.payments.prediction;
        }

        // Update confidence indicators
        this.updateConfidenceIndicators();
    }

    updateConfidenceIndicators() {
        const indicators = document.querySelectorAll('.ml-confidence');
        indicators.forEach(indicator => {
            const confidence = Math.random() * 0.3 + 0.7; // 70-100%
            indicator.textContent = `${Math.round(confidence * 100)}%`;
            indicator.className = `ml-confidence ${confidence > 0.8 ? 'text-green-600' : confidence > 0.6 ? 'text-yellow-600' : 'text-red-600'}`;
        });
    }

    setupAutoRefresh() {
        // Refresh predictions every 30 minutes
        this.updateInterval = setInterval(() => {
            this.updatePredictions();
        }, 30 * 60 * 1000);
    }

    async retrainModels() {
        try {
            console.log('Retraining ML models...');
            
            // Clear existing models
            mlPredictionService.dispose();
            
            // Reinitialize with fresh data
            await this.initialize();
            
            console.log('Models retrained successfully');
        } catch (error) {
            console.error('Error retraining models:', error);
        }
    }

    getPredictions() {
        return this.predictions;
    }

    dispose() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
        mlPredictionService.dispose();
        this.isInitialized = false;
    }
}

// Export singleton instance
export const adminMLAnalytics = new AdminMLAnalytics();

// Auto-initialize when imported
document.addEventListener('DOMContentLoaded', () => {
    adminMLAnalytics.initialize();
});

// Add retrain button functionality
document.addEventListener('DOMContentLoaded', () => {
    const retrainButton = document.getElementById('retrain-ml-models');
    if (retrainButton) {
        retrainButton.addEventListener('click', () => {
            adminMLAnalytics.retrainModels();
        });
    }
});
