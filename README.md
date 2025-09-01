A complete, self-contained waitlist component with email collection, rate limiting, spam protection, and database management.

## Features

- ✅ **Email Validation** - Client and server-side validation
- ✅ **Rate Limiting** - Prevents abuse with configurable time windows
- ✅ **Spam Protection** - Honeypot field to catch bots
- ✅ **Database Management** - SQLite databases for waitlist and rate limiting
- ✅ **IP Detection** - Handles proxies and load balancers
- ✅ **Responsive Design** - Mobile-friendly styling
- ✅ **Animation Effects** - Smooth shimmer and fade animations
- ✅ **Self-Contained** - All assets and data within component folder


## Quick Start

### 1. Add the component to your project as a git submodule:
```
$ git submodule add https://github.com/ediril/collectiq.git
```

### 2. Basic Integration

```php
<?php require_once 'collectiq/component/WaitlistComponent.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/collectiq/component/assets/waitlist.css">
</head>
<body>
    <?php 
    $waitlist = new WaitlistComponent();
    echo $waitlist->renderForm(); 
    ?>
    
    <script src="/collectiq/component/assets/waitlist.js"></script>
</body>
</html>
```

### 3. Ensure Endpoint is Accessible

Make sure `/collectiq/component/endpoint.php` is accessible from your web root.

### 4. How to Update
```
$ git submodule update --remote
```

## Advanced Usage

### Custom Form Options

```php
$waitlist = new WaitlistComponent();

// Custom form with different text and ID
echo $waitlist->renderForm(
    'newsletter-signup',           // Form ID
    'Enter your email address...',  // Placeholder text
    'Subscribe Now'                // Button text
);
```

### Custom Database Paths

```php
$waitlist = new WaitlistComponent(
    '/custom/path/waitlist.db',     # Waitlist database
    '/custom/path/rate_limit.db',   # Rate limit database  
    120                             # Rate limit window (seconds)
);
```

### Multiple Forms on Same Page

```html
<!-- Form 1: Newsletter -->
<?php echo $waitlist->renderForm('newsletter', 'Newsletter signup...', 'Subscribe'); ?>

<!-- Form 2: Beta Access -->
<?php echo $waitlist->renderForm('beta-signup', 'Get beta access...', 'Join Beta'); ?>

<script>
// Initialize custom handlers
new WaitlistHandler('newsletter', '/collectiq/component/endpoint.php');
new WaitlistHandler('beta-signup', '/collectiq/component/endpoint.php');
</script>
```

### Standalone Endpoint

Use the component as a pure API:

```javascript
fetch('/collectiq/component/endpoint.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email: 'user@example.com' })
})
.then(response => response.json())
.then(data => console.log(data));
```

## Configuration Options

### Rate Limiting

**Default: 1 request per 60 seconds per IP address**

The rate limit prevents spam and abuse while allowing legitimate users to retry if needed.

#### Adjusting Rate Limits

```php
// Constructor: new WaitlistComponent($dbPath, $rateLimitDbPath, $windowSeconds)

// 30 seconds (more permissive)
$waitlist = new WaitlistComponent(null, null, 30);

// 2 minutes (default recommended)  
$waitlist = new WaitlistComponent(null, null, 120);

// 5 minutes (stricter)
$waitlist = new WaitlistComponent(null, null, 300);

// 10 minutes (very strict)
$waitlist = new WaitlistComponent(null, null, 600);
```

#### In Your Project

Update your component initialization:

```php
// In index.php or wherever you create the component
// Change from:
$waitlist = new WaitlistComponent();

// To (for 2-minute rate limit):
$waitlist = new WaitlistComponent(null, null, 120);
```

#### Rate Limit Behavior
- Tracks requests per IP address
- Returns `{"success": false, "message": "too many requests"}` when exceeded
- Automatically cleans up old entries
- Works with proxy headers (X-Forwarded-For)

### Database Location

By default, databases are stored in `component/collectiq/data/`. To use custom paths:

```php
$waitlist = new WaitlistComponent(
    __DIR__ . '/custom/waitlist.db',
    __DIR__ . '/custom/rate_limit.db'
);
```

## API Responses

### Success Response
```json
{
    "success": true
}
```

### Error Responses
```json
// Rate limited
{
    "success": false,
    "message": "too many requests"
}

// Invalid email
{
    "success": false,
    "message": "Invalid email address"
}

// Bot detected (honeypot)
{
    "success": true,
    "message": "not human"
}
```

## Database Schema

### Waitlist Table
```sql
CREATE TABLE waitlist (
    email TEXT PRIMARY KEY,
    ip TEXT,
    ts INTEGER DEFAULT (unixepoch()),
    dt TEXT GENERATED ALWAYS AS (datetime(ts, 'unixepoch')) STORED
);
```

### Rate Limit Table
```sql
CREATE TABLE rate_limit (
    ip TEXT PRIMARY KEY,
    window_start INTEGER
);
```

## Customization

### Styling

Edit `assets/waitlist.css` or override CSS classes:

```css
.waitlist-form {
    /* Your custom styles */
}

.input-container {
    /* Custom input styling */
}

.waitlist-form button {
    /* Custom button styling */
}
```

### JavaScript Behavior

Extend the `WaitlistHandler` class:

```javascript
class CustomWaitlistHandler extends WaitlistHandler {
    showSuccessMessage(form) {
        // Custom success message
        alert('Welcome to our waitlist!');
    }
}

// Use custom handler
new CustomWaitlistHandler('my-form');
```

## Security Features

1. **Rate Limiting** - Prevents spam and abuse
2. **Honeypot Protection** - Hidden field catches bots
3. **Email Validation** - Server-side validation
4. **IP Tracking** - Logs IP addresses for monitoring
5. **SQL Injection Protection** - Prepared statements
6. **Proxy Support** - Handles X-Forwarded-For headers

## Deployment Notes

1. Ensure PHP SQLite extension is installed
2. Make `component/collectiq/data/` writable by web server
3. Database files are created automatically on first use
4. Consider backing up the `data/` folder regularly

## Troubleshooting

### Form Not Submitting
- Check that `waitlist.js` is loaded
- Verify endpoint URL is correct
- Check browser console for JavaScript errors

### Database Errors
- Ensure `data/` directory is writable
- Check PHP SQLite extension is installed
- Verify file permissions

### Rate Limiting Issues
- Check server system time is correct
- Verify IP detection is working properly
- Consider adjusting rate limit window
