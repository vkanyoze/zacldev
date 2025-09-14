# Laravel Application Models Documentation

This document provides comprehensive documentation for all models in the Laravel application, including their structure, relationships, and functionality.

## Table of Contents

1. [Core Models](#core-models)
   - [User](#user)
   - [Admin](#admin)
   - [Payment](#payment)
   - [Card](#card)
   - [Setting](#setting)
2. [Verification Models](#verification-models)
   - [EmailVerify](#emailverify)
   - [UserVerify](#userverify)
3. [System Models](#system-models)
   - [ActivityLog](#activitylog)
   - [Webhook](#webhook)
4. [Traits](#traits)
   - [Uuids](#uuids)
   - [HasAdminNotifications](#hasadminnotifications)
5. [Model Relationships](#model-relationships)
6. [Database Schema Overview](#database-schema-overview)

---

## Core Models

### User

**File**: `app/Models/User.php`

The main user model for customer authentication and management.

#### Traits Used
- `HasApiTokens` - Laravel Sanctum API authentication
- `HasFactory` - Model factory support
- `Notifiable` - Notification system
- `Uuids` - UUID primary keys
- `AuditableTrait` - Activity logging

#### Fillable Attributes
```php
protected $fillable = [
    'name',
    'email', 
    'password',
    'is_email_verified'
];
```

#### Hidden Attributes
```php
protected $hidden = [
    'password',
    'remember_token',
];
```

#### Casts
```php
protected $casts = [
    'email_verified_at' => 'datetime',
];
```

#### Relationships
- `payments()` - HasMany relationship with Payment model
- `cards()` - HasMany relationship with Card model  
- `webhooks()` - HasMany relationship with Webhook model

#### Key Features
- Customer authentication
- Email verification support
- API token authentication via Sanctum
- Activity auditing
- UUID primary keys

---

### Admin

**File**: `app/Models/Admin.php`

Administrator model for admin panel authentication and management.

#### Traits Used
- `HasApiTokens` - Laravel Sanctum API authentication
- `HasFactory` - Model factory support
- `Notifiable` - Notification system
- `HasRoles` - Spatie permission roles
- `HasAdminNotifications` - Custom admin notification system

#### Guard
```php
protected $guard = 'admin';
```

#### Fillable Attributes
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'avatar',
    'last_login_at',
    'last_login_ip',
];
```

#### Hidden Attributes
```php
protected $hidden = [
    'password',
    'remember_token',
];
```

#### Casts
```php
protected $casts = [
    'email_verified_at' => 'datetime',
    'last_login_at' => 'datetime',
    'password' => 'hashed',
];
```

#### Custom Methods
- `getAvatarUrlAttribute()` - Get avatar URL or generate default
- `defaultAvatarUrl()` - Generate default avatar from initials
- `routeNotificationForMail()` - Email notification routing
- `routeNotificationForDatabase()` - Database notification routing
- `receivesBroadcastNotificationsOn()` - Broadcast notification channel
- `getFullNameAttribute()` - Get admin's full name
- `hasUnreadNotifications()` - Check for unread notifications
- `markAsOnline()` - Mark admin as online
- `isOnline()` - Check if admin is online

#### Key Features
- Admin authentication with separate guard
- Role-based permissions via Spatie
- Avatar management with fallback
- Online status tracking
- Comprehensive notification system
- Login tracking (IP and timestamp)

---

### Payment

**File**: `app/Models/Payment.php`

Payment transaction model for handling all payment-related data.

#### Traits Used
- `HasFactory` - Model factory support
- `Uuids` - UUID primary keys
- `AuditableTrait` - Activity logging

#### Table
```php
public $table = "payments";
```

#### Fillable Attributes
```php
protected $fillable = [
    'invoice_reference',
    'description',
    'service_type',
    'amount_spend',
    'payment_method',
    'transaction_reference',
    'user_id',
    'card_id',
    'status',
    'reconciliaton_reference',
    'payment',
    'currency',
    'name',
    'surname'
];
```

#### Relationships
- `user()` - BelongsTo relationship with User model
- `card()` - BelongsTo relationship with Card model

#### Payment Statuses
- `completed` - Successfully processed payment
- `pending` - Payment awaiting processing
- `failed` - Payment that failed
- `refunded` - Payment that was refunded

#### Key Features
- Payment transaction tracking
- Multiple payment statuses
- Currency support
- Invoice reference system
- Reconciliation support
- Activity auditing

---

### Card

**File**: `app/Models/Card.php`

Credit/debit card information model for storing user payment methods.

#### Traits Used
- `HasFactory` - Model factory support
- `Uuids` - UUID primary keys
- `AuditableTrait` - Activity logging

#### Table
```php
public $table = "cards";
```

#### Fillable Attributes
```php
protected $fillable = [
    'name',
    'surname',
    'card_number',
    'expiry_date',
    'type_of_card',
    'address',
    'city',
    'country',
    'postal_code',
    'email_address',
    'phone_number',
    'user_id',
    'state'
];
```

#### Relationships
- `user()` - BelongsTo relationship with User model

#### Key Features
- Card information storage
- Address information
- Contact details
- Activity auditing
- UUID primary keys

---

### Setting

**File**: `app/Models/Setting.php`

Application settings model for storing configuration values.

#### Traits Used
- `HasFactory` - Model factory support

#### Fillable Attributes
```php
protected $fillable = [
    'key',
    'value',
    'type',
    'description'
];
```

#### Casts
```php
protected $casts = [
    'value' => 'string',
];
```

#### Static Methods
- `get($key, $default = null)` - Get setting value by key
- `set($key, $value, $type = 'string', $description = null)` - Set setting value
- `castValue($value, $type)` - Cast value based on type

#### Supported Types
- `string` - String values (default)
- `boolean` - Boolean values
- `integer` - Integer values
- `float` - Float values
- `json` - JSON values

#### Key Features
- Dynamic configuration storage
- Type casting support
- Default value fallback
- Description support for documentation

---

## Verification Models

### EmailVerify

**File**: `app/Models/EmailVerify.php`

Email verification token model for user email verification.

#### Traits Used
- `HasFactory` - Model factory support
- `AuditableTrait` - Activity logging

#### Table
```php
public $table = "email_verify";
```

#### Fillable Attributes
```php
protected $fillable = [
    'user_id',
    'token',
    'email',
];
```

#### Relationships
- `user()` - BelongsTo relationship with User model

#### Key Features
- Email verification token storage
- User association
- Activity auditing

---

### UserVerify

**File**: `app/Models/UserVerify.php`

User verification token model for general user verification.

#### Traits Used
- `HasFactory` - Model factory support
- `AuditableTrait` - Activity logging

#### Table
```php
public $table = "users_verify";
```

#### Fillable Attributes
```php
protected $fillable = [
    'user_id',
    'token',
];
```

#### Relationships
- `user()` - BelongsTo relationship with User model

#### Key Features
- User verification token storage
- User association
- Activity auditing

---

## System Models

### ActivityLog

**File**: `app/Models/ActivityLog.php`

Activity logging model (read-only) for tracking system activities.

#### Special Configuration
```php
public $timestamps = false;
protected $guarded = [];
```

#### Boot Method
Prevents all database write operations:
- `creating()` - Returns false
- `updating()` - Returns false  
- `saving()` - Returns false

#### Relationships
- `user()` - Dummy relationship (returns null object)

#### Key Features
- Read-only model
- Activity tracking
- No write operations allowed
- Dummy relationships for compatibility

---

### Webhook

**File**: `app/Models/Webhook.php`

Webhook model for handling external service integrations.

#### Traits Used
- `HasFactory` - Model factory support
- `Uuids` - UUID primary keys
- `AuditableTrait` - Activity logging

#### Fillable Attributes
```php
protected $fillable = [
    'user_id',
    'reference_id',
    'transaction_id',
    'save_card',
    'service_type',
    'invoice_reference',
    'card_id',
    'currency'
];
```

#### Relationships
- `user()` - BelongsTo relationship with User model

#### Key Features
- Webhook data storage
- External service integration
- Transaction tracking
- Card saving preferences
- Activity auditing

---

## Traits

### Uuids

**File**: `app/Traits/Uuids.php`

Trait for implementing UUID primary keys instead of auto-incrementing integers.

#### Key Features
- Automatic UUID generation on model creation
- Non-incrementing primary keys
- String key type
- Laravel UUID integration

#### Methods
- `boot()` - Auto-generates UUID on creation
- `getIncrementing()` - Returns false (non-incrementing)
- `getKeyType()` - Returns 'string'

#### Usage
```php
use App\Traits\Uuids;

class Model extends Eloquent
{
    use Uuids;
}
```

---

### HasAdminNotifications

**File**: `app/Traits/HasAdminNotifications.php`

Trait for sending notifications to admin users.

#### Static Methods
- `notifyAll($notification)` - Send to all admins
- `notifyRoles($roles, $notification)` - Send to specific roles
- `notifySuccess($title, $message, $data, $admins)` - Success notification
- `notifyError($title, $message, $data, $admins)` - Error notification
- `notifyPayment($title, $message, $data, $admins)` - Payment notification
- `notifyUser($title, $message, $data, $admins)` - User notification
- `notifySystem($title, $message, $data, $admins)` - System notification

#### Instance Methods
- `markAllNotificationsAsRead()` - Mark all as read
- `getUnreadNotificationsCount()` - Get unread count
- `getNotifications($limit)` - Get notifications
- `getUnreadNotifications($limit)` - Get unread notifications

#### Key Features
- Role-based notifications
- Multiple notification types
- Batch operations
- Admin-specific functionality

---

## Model Relationships

### Relationship Diagram

```
User (1) -----> (Many) Payment
User (1) -----> (Many) Card
User (1) -----> (Many) Webhook
User (1) -----> (Many) EmailVerify
User (1) -----> (Many) UserVerify

Payment (Many) -----> (1) User
Payment (Many) -----> (1) Card

Card (Many) -----> (1) User

EmailVerify (Many) -----> (1) User
UserVerify (Many) -----> (1) User
Webhook (Many) -----> (1) User

Admin (Independent) - Role-based permissions
Setting (Independent) - Configuration storage
ActivityLog (Read-only) - System activity tracking
```

### Relationship Summary

| Model | Relationship Type | Related Model | Description |
|-------|------------------|---------------|-------------|
| User | HasMany | Payment | User can have multiple payments |
| User | HasMany | Card | User can have multiple cards |
| User | HasMany | Webhook | User can have multiple webhooks |
| User | HasMany | EmailVerify | User can have multiple email verifications |
| User | HasMany | UserVerify | User can have multiple user verifications |
| Payment | BelongsTo | User | Payment belongs to one user |
| Payment | BelongsTo | Card | Payment belongs to one card |
| Card | BelongsTo | User | Card belongs to one user |
| EmailVerify | BelongsTo | User | Email verification belongs to one user |
| UserVerify | BelongsTo | User | User verification belongs to one user |
| Webhook | BelongsTo | User | Webhook belongs to one user |

---

## Database Schema Overview

### Primary Key Strategy
- **UUID Primary Keys**: User, Payment, Card, Webhook, EmailVerify, UserVerify
- **Auto-incrementing**: Admin, Setting, ActivityLog

### Auditing
- **Auditable Models**: User, Payment, Card, Webhook, EmailVerify, UserVerify
- **Non-auditable**: Admin, Setting, ActivityLog

### Authentication
- **User Authentication**: User model with Sanctum
- **Admin Authentication**: Admin model with separate guard
- **Role-based Access**: Admin model with Spatie permissions

### Key Features
- **Activity Logging**: Most models support activity auditing
- **UUID Support**: Core business models use UUIDs
- **Notification System**: Admin model has comprehensive notifications
- **Settings Management**: Dynamic configuration via Setting model
- **Payment Processing**: Complete payment workflow support
- **User Verification**: Multiple verification mechanisms

---

## Usage Examples

### Creating a User with Payment
```php
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password'),
    'is_email_verified' => false
]);

$card = $user->cards()->create([
    'name' => 'John',
    'surname' => 'Doe',
    'card_number' => '4111111111111111',
    'type_of_card' => 'Visa',
    'email_address' => 'john@example.com'
]);

$payment = $user->payments()->create([
    'invoice_reference' => 'INV-001',
    'description' => 'Service Payment',
    'amount_spend' => 100.00,
    'currency' => 'USD',
    'status' => 'completed',
    'card_id' => $card->id
]);
```

### Admin Notifications
```php
// Send success notification to all admins
Admin::notifySuccess(
    'Payment Processed',
    'A new payment has been successfully processed',
    ['payment_id' => $payment->id]
);

// Send notification to specific admin
$admin = Admin::find(1);
$admin->notify(new AdminNotification($data));
```

### Settings Management
```php
// Set a setting
Setting::set('app_name', 'My Application', 'string', 'Application name');

// Get a setting
$appName = Setting::get('app_name', 'Default Name');

// Set boolean setting
Setting::set('maintenance_mode', true, 'boolean', 'Enable maintenance mode');
```

---

## Security Considerations

1. **Password Hashing**: All password fields are automatically hashed
2. **Hidden Attributes**: Sensitive data is hidden from serialization
3. **UUID Primary Keys**: Prevent enumeration attacks
4. **Activity Auditing**: Track all changes to sensitive models
5. **Role-based Access**: Admin permissions are role-based
6. **Email Verification**: User emails must be verified
7. **Token-based Verification**: Secure verification tokens

---

## Migration Notes

- All models with UUIDs require proper UUID column setup
- Admin model requires separate authentication guard configuration
- ActivityLog model is read-only and should not be migrated
- Setting model supports dynamic configuration without migrations
- Payment statuses should match the defined constants in the model

---

*This documentation is automatically generated and should be updated when models are modified.*

