# Password Change Logout Middleware

## Overview
The `CheckLoginDetailsChanged` middleware automatically detects when a user's password (or other important details) has been changed and immediately logs them out for security purposes.

## How It Works

### 1. Password Monitoring
The middleware monitors these critical user fields:
- **Password hash** - Detects password changes
- **Email** - Detects email changes
- **Name** - Detects name changes
- **Status/Active status** - Detects account status changes
- **Updated timestamp** - Tracks when the user was last modified

### 2. Automatic Detection
- On every request, the middleware generates a hash of the user's current details
- Compares this with a stored hash from the user's session
- If the hashes don't match, it means something important has changed

### 3. Immediate Logout
When changes are detected:
- **API users**: Token is invalidated, returns JSON response with logout flag
- **Web users**: Session is cleared, redirected to login page
- **Security message**: User sees a message explaining they were logged out for security

## Implementation

### Middleware Registration
The middleware is now registered in `app/Http/Kernel.php`:
- Added to both `web` and `api` middleware groups for automatic protection
- Also available as named middleware: `check.user.details`

### File Location
- **Middleware**: `app/Http/Middleware/CheckLoginDetailsChanged.php`
- **Registration**: `app/Http/Kernel.php`

## Use Cases

### 1. Password Changed by Admin
If an admin changes a user's password:
1. User is automatically logged out on their next request
2. Must login with the new password
3. Prevents unauthorized access with old session

### 2. Password Reset
When a user resets their password:
1. All existing sessions are invalidated
2. User must login with new password on all devices
3. Prevents session hijacking after password reset

### 3. Account Compromise
If suspicious activity is detected and password is changed:
1. All sessions are immediately invalidated
2. User must re-authenticate everywhere
3. Provides immediate security response

## Testing

### Test Password Change Logout
1. Login to the application
2. In another browser/tool, change your password in the database
3. Make any request in the original browser
4. You should be automatically logged out with a security message

### Expected Responses

#### API Response
```json
{
  "status": 401,
  "message": "Account details have been modified. Please login again for security.",
  "logout": true,
  "reason": "details_changed"
}
```

#### Web Response
- Redirected to login page
- Error message: "Account details have been modified. Please login again for security."

## Benefits

1. **Immediate Security**: Users are logged out instantly when password changes
2. **Multi-Device Protection**: All sessions across all devices are invalidated
3. **Automatic Detection**: No manual intervention required
4. **Transparent Operation**: Works silently in the background
5. **Dual Authentication**: Supports both web and API authentication

## Customization

To monitor additional fields, edit the `generateUserHash()` method:

```php
private function generateUserHash($user)
{
    $details = [
        'id' => $user->id,
        'email' => $user->email,
        'password' => $user->password,
        'name' => $user->name,
        'updated_at' => $user->updated_at ? $user->updated_at->timestamp : null,
        // Add more fields as needed
        'role' => $user->role ?? null,
        'department' => $user->department ?? null,
    ];

    return hash('sha256', serialize($details));
}
```

This middleware provides robust security by ensuring users are automatically logged out whenever their critical account details change, preventing potential security breaches from unauthorized account modifications.
