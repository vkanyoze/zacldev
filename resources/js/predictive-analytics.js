// Predictive Analytics with TensorFlow.js
import * as tf from '@tensorflow/tfjs';
import * as tfvis from '@tensorflow/tfjs-vis';

class PredictiveAnalytics {
    constructor() {
        this.models = {};
        this.isInitialized = false;
    }

    async initialize() {
        if (this.isInitialized) return;
        
        try {
            // Initialize TensorFlow.js
            await tf.ready();
            console.log('TensorFlow.js initialized successfully');
            this.isInitialized = true;
        } catch (error) {
            console.error('Failed to initialize TensorFlow.js:', error);
        }
    }

    // Create a simple linear regression model for predictions
    createLinearModel(inputShape = 1) {
        const model = tf.sequential({
            layers: [
                tf.layers.dense({
                    inputShape: [inputShape],
                    units: 10,
                    activation: 'relu'
                }),
                tf.layers.dense({
                    units: 5,
                    activation: 'relu'
                }),
                tf.layers.dense({
                    units: 1,
                    activation: 'linear'
                })
            ]
        });

        model.compile({
            optimizer: 'adam',
            loss: 'meanSquaredError',
            metrics: ['mae']
        });

        return model;
    }

    // Generate sample data for training
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
            
            data.push([x]);
            labels.push([y]);
        }
        
        return { data, labels };
    }

    // Train a model for revenue prediction
    async trainRevenueModel(historicalData) {
        if (!this.isInitialized) await this.initialize();
        
        const model = this.createLinearModel(1);
        
        // Convert data to tensors
        const xs = tf.tensor2d(historicalData.map((_, i) => [i]));
        const ys = tf.tensor2d(historicalData.map(val => [val]));
        
        // Train the model
        const history = await model.fit(xs, ys, {
            epochs: 100,
            validationSplit: 0.2,
            verbose: 0,
            callbacks: {
                onEpochEnd: (epoch, logs) => {
                    if (epoch % 20 === 0) {
                        console.log(`Epoch ${epoch}: loss = ${logs.loss.toFixed(4)}`);
                    }
                }
            }
        });
        
        // Clean up tensors
        xs.dispose();
        ys.dispose();
        
        this.models.revenue = model;
        return model;
    }

    // Train a model for user growth prediction
    async trainUserGrowthModel(historicalData) {
        if (!this.isInitialized) await this.initialize();
        
        const model = this.createLinearModel(1);
        
        // Convert data to tensors
        const xs = tf.tensor2d(historicalData.map((_, i) => [i]));
        const ys = tf.tensor2d(historicalData.map(val => [val]));
        
        // Train the model
        await model.fit(xs, ys, {
            epochs: 100,
            validationSplit: 0.2,
            verbose: 0
        });
        
        // Clean up tensors
        xs.dispose();
        ys.dispose();
        
        this.models.userGrowth = model;
        return model;
    }

    // Make predictions for the next N periods
    async predict(modelName, periods = 7) {
        if (!this.models[modelName]) {
            console.error(`Model ${modelName} not found`);
            return [];
        }
        
        const model = this.models[modelName];
        const predictions = [];
        
        // Get the last known data point index
        const lastIndex = 30; // Assuming we have 30 historical data points
        
        for (let i = 0; i < periods; i++) {
            const input = tf.tensor2d([[lastIndex + i]]);
            const prediction = model.predict(input);
            const value = await prediction.data();
            predictions.push(Math.max(0, value[0])); // Ensure non-negative values
            
            // Clean up tensors
            input.dispose();
            prediction.dispose();
        }
        
        return predictions;
    }

    // Create a chart using tfjs-vis
    createChart(containerId, data, title) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        const surface = tfvis.visor().surface({
            name: title,
            tab: 'Predictions'
        });
        
        const chartData = {
            values: data.map((value, index) => ({ x: index, y: value }))
        };
        
        tfvis.render.linechart(surface, chartData, {
            xLabel: 'Time Period',
            yLabel: 'Value',
            height: 300
        });
    }

    // Generate insights based on predictions
    generateInsights(predictions, currentValue, metric) {
        const avgPrediction = predictions.reduce((a, b) => a + b, 0) / predictions.length;
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
        // Simple confidence calculation based on prediction variance
        const mean = predictions.reduce((a, b) => a + b, 0) / predictions.length;
        const variance = predictions.reduce((a, b) => a + Math.pow(b - mean, 2), 0) / predictions.length;
        const stdDev = Math.sqrt(variance);
        const coefficient = stdDev / mean;
        
        // Lower coefficient of variation = higher confidence
        return Math.max(0, Math.min(100, (1 - coefficient) * 100));
    }

    // Main method to run all predictions
    async runPredictions(historicalData) {
        if (!this.isInitialized) await this.initialize();
        
        try {
            // Train models
            await this.trainRevenueModel(historicalData.revenue || []);
            await this.trainUserGrowthModel(historicalData.users || []);
            
            // Make predictions
            const revenuePredictions = await this.predict('revenue', 7);
            const userPredictions = await this.predict('userGrowth', 7);
            
            return {
                revenue: {
                    predictions: revenuePredictions,
                    insights: this.generateInsights(revenuePredictions, historicalData.revenue[historicalData.revenue.length - 1] || 0, 'revenue')
                },
                users: {
                    predictions: userPredictions,
                    insights: this.generateInsights(userPredictions, historicalData.users[historicalData.users.length - 1] || 0, 'users')
                }
            };
        } catch (error) {
            console.error('Error running predictions:', error);
            return null;
        }
    }
}

// Export for use in other modules and global scope
window.PredictiveAnalytics = PredictiveAnalytics;

// Also export as default for ES6 modules
export default PredictiveAnalytics;
