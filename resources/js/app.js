import './bootstrap';
import '../css/app.css';

// Import admin predictive analytics
import AdminPredictiveAnalytics from './admin-predictive-analytics.js';

// Make AdminPredictiveAnalytics available globally
window.AdminPredictiveAnalytics = AdminPredictiveAnalytics;

// Debug logging
console.log('App.js loaded - AdminPredictiveAnalytics available:', typeof window.AdminPredictiveAnalytics);