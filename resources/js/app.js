import './bootstrap';
import '../css/app.css';

// Import Firebase Authentication
import firebaseAuth from './firebase-auth.js';

// Import admin predictive analytics
import AdminPredictiveAnalytics from './admin-predictive-analytics.js';

// Import ML prediction services
import { mlPredictionService } from './ml-predictions.js';
import { adminMLAnalytics } from './admin-ml-analytics.js';

// Make services available globally
window.AdminPredictiveAnalytics = AdminPredictiveAnalytics;
window.mlPredictionService = mlPredictionService;
window.adminMLAnalytics = adminMLAnalytics;
window.firebaseAuth = firebaseAuth;

// Debug logging
console.log('App.js loaded - Services available:', {
    AdminPredictiveAnalytics: typeof window.AdminPredictiveAnalytics,
    mlPredictionService: typeof window.mlPredictionService,
    adminMLAnalytics: typeof window.adminMLAnalytics
});