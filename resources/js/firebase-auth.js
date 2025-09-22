// Firebase Authentication Service
import { 
    signInWithPopup, 
    signInWithEmailAndPassword, 
    createUserWithEmailAndPassword,
    signOut,
    onAuthStateChanged,
    updateProfile,
    sendEmailVerification,
    sendPasswordResetEmail
} from 'firebase/auth';
import { auth, googleProvider, facebookProvider, twitterProvider, githubProvider } from './firebase-config.js';

class FirebaseAuthService {
    constructor() {
        this.currentUser = null;
        this.authStateListeners = [];
        
        // Listen for auth state changes
        onAuthStateChanged(auth, (user) => {
            this.currentUser = user;
            this.notifyAuthStateListeners(user);
        });
    }

    // Add auth state listener
    onAuthStateChanged(callback) {
        this.authStateListeners.push(callback);
        // Call immediately if user is already loaded
        if (this.currentUser !== null) {
            callback(this.currentUser);
        }
    }

    // Notify all listeners of auth state changes
    notifyAuthStateListeners(user) {
        this.authStateListeners.forEach(callback => callback(user));
    }

    // Get current user
    getCurrentUser() {
        return this.currentUser;
    }

    // Check if user is authenticated
    isAuthenticated() {
        return this.currentUser !== null;
    }

    // Sign in with Google
    async signInWithGoogle() {
        try {
            const result = await signInWithPopup(auth, googleProvider);
            return await this.handleAuthResult(result);
        } catch (error) {
            console.error('Google sign-in error:', error);
            throw error;
        }
    }

    // Sign in with Facebook
    async signInWithFacebook() {
        try {
            const result = await signInWithPopup(auth, facebookProvider);
            return await this.handleAuthResult(result);
        } catch (error) {
            console.error('Facebook sign-in error:', error);
            throw error;
        }
    }

    // Sign in with Twitter
    async signInWithTwitter() {
        try {
            const result = await signInWithPopup(auth, twitterProvider);
            return await this.handleAuthResult(result);
        } catch (error) {
            console.error('Twitter sign-in error:', error);
            throw error;
        }
    }

    // Sign in with GitHub
    async signInWithGitHub() {
        try {
            const result = await signInWithPopup(auth, githubProvider);
            return await this.handleAuthResult(result);
        } catch (error) {
            console.error('GitHub sign-in error:', error);
            throw error;
        }
    }

    // Sign in with email and password
    async signInWithEmail(email, password) {
        try {
            const result = await signInWithEmailAndPassword(auth, email, password);
            return await this.handleAuthResult(result);
        } catch (error) {
            console.error('Email sign-in error:', error);
            throw error;
        }
    }

    // Create account with email and password
    async createAccountWithEmail(email, password, displayName) {
        try {
            const result = await createUserWithEmailAndPassword(auth, email, password);
            
            // Update profile with display name
            if (displayName) {
                await updateProfile(result.user, { displayName });
            }
            
            // Send email verification
            await sendEmailVerification(result.user);
            
            return await this.handleAuthResult(result);
        } catch (error) {
            console.error('Account creation error:', error);
            throw error;
        }
    }

    // Sign out
    async signOut() {
        try {
            await signOut(auth);
            return { success: true };
        } catch (error) {
            console.error('Sign out error:', error);
            throw error;
        }
    }

    // Send password reset email
    async sendPasswordReset(email) {
        try {
            await sendPasswordResetEmail(auth, email);
            return { success: true };
        } catch (error) {
            console.error('Password reset error:', error);
            throw error;
        }
    }

    // Handle authentication result
    async handleAuthResult(result) {
        const user = result.user;
        const idToken = await user.getIdToken();
        
        // Send token to Laravel backend for verification
        const response = await fetch('/api/firebase/verify-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                idToken: idToken,
                provider: this.getProviderFromUser(user)
            })
        });

        if (!response.ok) {
            throw new Error('Backend verification failed');
        }

        const data = await response.json();
        
        return {
            user: {
                uid: user.uid,
                email: user.email,
                displayName: user.displayName,
                photoURL: user.photoURL,
                emailVerified: user.emailVerified,
                provider: this.getProviderFromUser(user)
            },
            token: idToken,
            laravelUser: data.user
        };
    }

    // Get provider from user
    getProviderFromUser(user) {
        if (user.providerData && user.providerData.length > 0) {
            const provider = user.providerData[0].providerId;
            switch (provider) {
                case 'google.com':
                    return 'google';
                case 'facebook.com':
                    return 'facebook';
                case 'twitter.com':
                    return 'twitter';
                case 'github.com':
                    return 'github';
                default:
                    return 'email';
            }
        }
        return 'email';
    }

    // Get user ID token
    async getIdToken() {
        if (this.currentUser) {
            return await this.currentUser.getIdToken();
        }
        return null;
    }
}

// Create and export singleton instance
const firebaseAuth = new FirebaseAuthService();
export default firebaseAuth;
