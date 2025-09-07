# CollectIQ Examples

This folder contains interactive examples for both components.

## Running the Examples

The easiest way to run the examples is from within the examples folder:

```bash
# Navigate to the examples folder
cd /path/to/collectiq/examples

# Start PHP development server
php -S localhost:8000
```

Then visit:
- **http://localhost:8000/** - Main examples landing page (index.php)
- **http://localhost:8000/waitlist.php** - Waitlist component demo
- **http://localhost:8000/ratelimit.php** - RateLimit component demo

## What's Included

### `index.php`
Main landing page that showcases both components and links to individual examples.

### `waitlist.php` 
Complete waitlist component demonstration with:
- Multiple form styles and customizations
- Real email collection functionality
- Built-in rate limiting
- Usage code examples

### `ratelimit.php`
Interactive rate limiting demonstration with:
- Real-time testing of rate limits
- Configurable time windows (10s, 60s, 2min, etc.)
- IP-specific testing
- Live status updates
- Usage code examples

## Database Files

When you test the examples, SQLite database files will be automatically created:
- `../components/waitlist/data/waitlist.db` - Stores collected emails
- `../components/ratelimit/data/rate_limit.db` - Stores rate limiting data

These files are safe to delete if you want to reset the examples.

## Requirements

- PHP 7.4+ with SQLite extension
- No other dependencies required