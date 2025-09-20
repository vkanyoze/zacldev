# API Endpoints Documentation

## Base URL
```
https://yourdomain.com/api
```

## Authentication
All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer your_token_here
```

## Public Endpoints (No Authentication Required)

### Authentication
- `POST /api/login` - User login
- `POST /api/register` - User registration
- `POST /api/forgot-password` - Request password reset
- `POST /api/reset-password` - Reset password with token
- `POST /api/verify-email` - Verify email address

## Protected Endpoints (Authentication Required)

### User Management
- `GET /api/user` - Get authenticated user information
- `POST /api/logout` - User logout

### Profile Management
- `GET /api/profile` - Get user profile
- `PUT /api/profile` - Update user profile
- `POST /api/profile/change-password` - Change password
- `POST /api/profile/change-email` - Request email change
- `POST /api/profile/verify-email-change` - Verify email change
- `GET /api/profile/stats` - Get user statistics
- `GET /api/profile/summary` - Get comprehensive user summary for mobile dashboard

### Payment Management
- `GET /api/payments` - List user's payments
- `POST /api/payments` - Create a new payment
- `GET /api/payments/{id}` - Get payment details
- `POST /api/payments/{payment}/refund` - Refund a payment

### Card Management
- `GET /api/cards` - List user's cards
- `POST /api/cards` - Create a new card
- `GET /api/cards/{id}` - Get card details
- `PUT /api/cards/{id}` - Update card
- `DELETE /api/cards/{id}` - Delete card
- `GET /api/cards/search` - Search cards
- `POST /api/cards/{card}/set-default` - Set card as default

### Notifications
- `GET /api/notifications` - Get all notifications
- `GET /api/notifications/unread-count` - Get unread count
- `POST /api/notifications/{notification}/read` - Mark notification as read
- `POST /api/notifications/mark-all-read` - Mark all notifications as read

## Request/Response Examples

### Login Request
```json
POST /api/login
{
    "email": "user@example.com",
    "password": "password123",
    "remember": false
}
```

### Login Response
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "email": "user@example.com",
            "is_email_verified": 1,
            "created_at": "2023-01-01T00:00:00.000000Z",
            "updated_at": "2023-01-01T00:00:00.000000Z"
        },
        "token": "1|abc123..."
    }
}
```

### User Summary Response (Mobile Dashboard)
```json
GET /api/profile/summary
{
    "success": true,
    "data": {
        "user_info": {
            "id": 1,
            "email": "user@example.com",
            "is_email_verified": 1,
            "member_since": "Jan 2023",
            "last_login": "2 hours ago"
        },
        "summary_cards": [
            {
                "title": "Total Spent",
                "value": "$1,250.00",
                "subtitle": "All time",
                "icon": "dollar-sign",
                "color": "green",
                "trend": "up"
            },
            {
                "title": "Payment Cards",
                "value": 3,
                "subtitle": "2 active",
                "icon": "credit-card",
                "color": "blue",
                "trend": "neutral"
            },
            {
                "title": "Transactions",
                "value": 15,
                "subtitle": "14 successful",
                "icon": "receipt",
                "color": "purple",
                "trend": "up"
            },
            {
                "title": "This Month",
                "value": "$450.00",
                "subtitle": "Last 30 days",
                "icon": "calendar",
                "color": "orange",
                "trend": "up"
            }
        ],
        "recent_activity": [
            {
                "id": 123,
                "amount": 50.00,
                "status": "completed",
                "description": "Coffee Shop",
                "date": "Dec 15, 2023",
                "time_ago": "2 hours ago"
            }
        ],
        "spending_analytics": {
            "total_spent": 1250.00,
            "monthly_spending": 450.00,
            "weekly_spending": 120.00,
            "today_spending": 25.00,
            "average_transaction": 83.33,
            "spending_breakdown": {
                "successful": 14,
                "failed": 1,
                "pending": 0
            }
        },
        "quick_stats": {
            "account_age_days": 45,
            "last_payment_date": "Dec 15, 2023",
            "default_card_last4": "****4242",
            "email_verified": true,
            "notifications_count": 3
        }
    }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

## Rate Limiting
API requests are limited to 60 requests per minute per user.

## Error Codes
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `422` - Validation Error
- `429` - Too Many Requests
- `500` - Internal Server Error
