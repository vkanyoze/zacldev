# API Documentation

This directory contains comprehensive API documentation for the Payment Portal system.

## Files

- **`swagger.json`** - Complete OpenAPI 3.0 specification for all API endpoints
- **`swagger-ui.html`** - Interactive Swagger UI documentation
- **`API_ENDPOINTS.md`** - Human-readable API documentation
- **`index.html`** - Custom API documentation page

## Accessing Documentation

### Interactive Swagger UI
Visit: `https://yourdomain.com/api/swagger`

Features:
- Interactive API testing
- Request/response examples
- Authentication testing
- Schema validation
- Try-it-out functionality

### Custom Documentation
Visit: `https://yourdomain.com/api/docs`

Features:
- Custom styled documentation
- Mobile-friendly design
- Quick reference guide

## API Endpoints Overview

### Authentication
- `POST /api/login` - User login
- `POST /api/register` - User registration
- `POST /api/logout` - User logout
- `GET /api/user` - Get authenticated user
- `POST /api/forgot-password` - Request password reset
- `POST /api/reset-password` - Reset password
- `POST /api/verify-email` - Verify email

### Profile Management
- `GET /api/profile` - Get user profile
- `PUT /api/profile` - Update user profile
- `POST /api/profile/change-password` - Change password
- `POST /api/profile/change-email` - Request email change
- `POST /api/profile/verify-email-change` - Verify email change
- `GET /api/profile/stats` - Get user statistics
- `GET /api/profile/summary` - Get comprehensive user summary

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

## Authentication

All protected endpoints require a Bearer token in the Authorization header:

```
Authorization: Bearer your_token_here
```

## Rate Limiting

API requests are limited to 60 requests per minute per user.

## Error Handling

All API responses follow a consistent format:

### Success Response
```json
{
    "success": true,
    "data": { ... }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description"
}
```

### Validation Error Response
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "field": ["Error message"]
    }
}
```

## Mobile App Integration

The API is specifically designed for mobile applications with:

- **User Summary Endpoint** (`/api/profile/summary`) - Comprehensive dashboard data
- **Card-based Data Structure** - Perfect for mobile UI components
- **Recent Activity** - Latest transactions and activities
- **Spending Analytics** - Financial insights and trends
- **Quick Stats** - Account overview information

## Development

To update the Swagger documentation:

1. Modify `swagger.json` with new endpoints
2. Update `API_ENDPOINTS.md` with human-readable documentation
3. Test endpoints using the interactive Swagger UI
4. Validate JSON schema compliance

## Support

For API support or questions, contact the development team or refer to the interactive documentation at `/api/swagger`.
