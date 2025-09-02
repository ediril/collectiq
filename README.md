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
    <!-- Put this before the app CSS in case you need to override its values -->
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

The component uses CSS classes prefixed with `collectiq-` to avoid conflicts with your existing styles. You can override these styles in your own CSS:

```css
/* Override the main form container */
.collectiq-waitlist-form {
    max-width: 400px;
    gap: 0.5rem;
    /* Your custom styles */
}

/* Override the input container */
.collectiq-input-container {
    border: 2px solid #your-color;
    border-radius: 10px;
    /* Custom input styling */
}

/* Override the submit button */
.collectiq-submit-btn {
    background: linear-gradient(45deg, #your-color1, #your-color2);
    border-radius: 25px;
    font-size: 1.2rem;
    /* Custom button styling */
}

/* Override button hover state */
.collectiq-submit-btn:hover {
    background: linear-gradient(45deg, #your-hover-color1, #your-hover-color2);
    transform: translateY(-2px);
}

/* Override button active state */
.collectiq-submit-btn:active {
    background: linear-gradient(45deg, #your-active-color1, #your-active-color2);
    transform: translateY(0px);
}

/* Override button text */
.collectiq-submit-btn span {
    font-weight: bold;
    text-transform: uppercase;
}

/* Override input field */
.collectiq-waitlist-form input[type="email"] {
    font-size: 1.1rem;
    color: #your-text-color;
    /* Custom input field styling */
}

/* Override success message */
.collectiq-input-container.collectiq-thank-you {
    background-color: #your-success-color;
    color: #your-success-text-color;
    /* Custom success message styling */
}
```

#### Key CSS Classes Available for Override:

- `.collectiq-waitlist-form` - Main form container
- `.collectiq-input-container` - Input wrapper with border effects
- `.collectiq-waitlist-form input` - Email input field
- `.collectiq-waitlist-form button` - Submit button
- `.collectiq-shimmer-container` - Button shimmer effect container
- `.collectiq-shimmer` - Animated shimmer effect
- `.collectiq-highlight` - Button highlight effect
- `.collectiq-backdrop` - Button backdrop
- `.collectiq-thank-you` - Success message styling

#### Example: Custom Button Colors

```css
.collectiq-waitlist-form button {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
}

.collectiq-waitlist-form button:hover .collectiq-highlight {
    box-shadow: inset 0 -6px 10px rgba(255, 255, 255, 0.4);
}
```

#### Example: Different Input Styling

```css
.collectiq-input-container {
    border: none;
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.collectiq-waitlist-form input {
    padding: 1.2rem;
    font-size: 1.1rem;
}
```

#### Example: Disable Button Shimmer Effect

```css
.collectiq-submit-btn .collectiq-shimmer-container {
    display: none;
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
