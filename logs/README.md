# CodeReview.pl - Logging System

## Overview
Centralized logging system with rotation and different log levels.

## Security
- **Log files are NOT accessible via internet** - protected by .htaccess
- Admin interface requires authentication
- CLI tools available for local access

## Log Files
- `app.log` - General application logs (INFO, WARNING, ERROR, CRITICAL)
- `error.log` - Error and critical logs only
- `access.log` - HTTP access logs with IP, method, URI, user agent

## Log Levels
- `INFO` - General information (page views, user actions)
- `WARNING` - Non-critical issues (validation failures, missing data)
- `ERROR` - Errors that don't crash the app (DB connection failures)
- `CRITICAL` - Critical errors (app crashes, security issues)
- `DB` - Database operations
- `API` - API calls and responses

## Usage

### In PHP Code
```php
// Simple logging
Logger::info('User logged in', ['user_id' => 123]);
Logger::error('Payment failed', ['amount' => 100, 'error' => 'Card declined']);

// Access logging
Logger::access('Form submission', ['form' => 'contact']);

// Database logging
Logger::db('Query executed', ['query' => 'SELECT * FROM users']);
```

### Quick Functions
```php
log_info('Something happened');
log_error('Something broke');
log_warning('Something looks wrong');
log_access('Page view');
```

## Viewing Logs

### CLI (Recommended)
```bash
# View recent logs
./logs/view.php app 50
./logs/view.php error 100
./logs/view.php access 20

# Real-time monitoring
tail -f logs/app.log
tail -f logs/error.log
```

### Web Admin Interface (Protected)
Access via browser with authentication:
```
/admin/view_logs.php
```

Options:
- `?type=app` - Application logs (default)
- `?type=error` - Error logs only
- `?type=access` - Access logs
- `?lines=100` - Number of lines to show
- `?clear=all` - Clear all logs
- `?clear=error` - Clear error logs only

**Note:** Requires .htpasswd authentication setup

### Command Line Alternative
```bash
# Clear logs
> logs/app.log
> logs/error.log
> logs/access.log
```

## Log Rotation
- Automatic rotation when files exceed 10MB
- Keeps 5 rotated files per log type
- Format: `app.log.1`, `app.log.2`, etc.

## Security Features
- **.htaccess** blocks all web access to `/logs/` directory
- **Admin area** protected by Basic Authentication
- **Log files** excluded from git (see .gitignore)
- **CLI tools** for secure local access

## Setting up Admin Authentication
```bash
# Create .htpasswd file
cd admin/
htpasswd -c .htpasswd adminuser

# Or use online generator for .htpasswd
```

## Database Integration
Logs can be stored in database table `app_logs`:
```sql
CREATE TABLE app_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    level ENUM('DEBUG', 'INFO', 'WARNING', 'ERROR', 'CRITICAL'),
    message TEXT,
    context JSON,
    ip VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Performance
- File locking prevents concurrent write issues
- Lazy loading - logs written immediately
- Minimal overhead for logging operations
- JSON context for structured data

## Troubleshooting
- Check file permissions if logs aren't writing
- Ensure `logs/` directory is writable by web server
- Monitor disk space for large log files
- Use log rotation to prevent disk full issues
- **Never expose log files to public internet**
