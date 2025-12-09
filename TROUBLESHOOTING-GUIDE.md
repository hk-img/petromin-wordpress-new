# WordPress Live Server Troubleshooting Guide

## Problem
Site was working yesterday but now showing "There has been a critical error on this website" on live server. Local site works perfectly.

## Quick Fixes (Try in Order)

### 1. Check Error Logs
**First, check these locations for error logs:**

- **WordPress Debug Log**: `wp-content/debug.log` (if WP_DEBUG_LOG is enabled)
- **Server Error Log**: Check your hosting control panel (cPanel/Plesk) for error logs
- **PHP Error Log**: Usually in `/var/log/php-errors.log` or check hosting panel

**To enable WordPress debug logging:**
1. Use the `wp-config-live-debug.php` file provided
2. Rename it to `wp-config.php` (backup original first!)
3. Update database credentials
4. Check `wp-content/debug.log` for errors

### 2. Check Database Connection
**Common issues:**
- Wrong database credentials on live server
- Database server is down
- Database user doesn't have proper permissions

**Fix:**
- Verify database credentials in `wp-config.php` match your live server
- Check if database is accessible from hosting control panel
- Test database connection using phpMyAdmin

### 3. Disable All Plugins (Plugin Conflict Check)

**Method 1: Via FTP/File Manager**
1. Connect to your live server via FTP
2. Rename `wp-content/plugins` to `wp-content/plugins-disabled`
3. Create new empty `plugins` folder
4. Check if site loads
5. If it works, rename plugins back and disable them one by one

**Method 2: Via Database (if you have access)**
```sql
UPDATE wp_options SET option_value = '' WHERE option_name = 'active_plugins';
```
(Replace `wp_` with your table prefix if different)

**Method 3: Via functions.php (Temporary)**
Add this to `wp-content/themes/petromin/functions.php` temporarily:
```php
add_filter('option_active_plugins', function($plugins) {
    return array(); // Disables all plugins
});
```

### 4. Switch to Default Theme (Theme Conflict Check)
1. Rename `wp-content/themes/petromin` to `wp-content/themes/petromin-disabled`
2. WordPress will automatically switch to default theme
3. Check if site loads
4. If it works, the issue is in your theme

### 5. Check .htaccess File
**Issue:** Your `.htaccess` has `RewriteBase /petromin-wordpress-new/` which is local path.

**For live server, it should be:**
```
RewriteBase /petromin/
```
or just:
```
RewriteBase /
```

**Fix:**
1. Backup current `.htaccess`
2. Update `RewriteBase` to match your live server path
3. Also update the RewriteRule path if needed

### 6. Check PHP Version
**WordPress requires PHP 7.2.24+ (recommended: PHP 8.0+)**

**Check:**
- In hosting control panel, check PHP version
- Create a `phpinfo.php` file with `<?php phpinfo(); ?>` to check version

**Fix:**
- Update PHP version in hosting control panel to PHP 8.0 or 8.1

### 7. Check File Permissions
**Required permissions:**
- Folders: 755
- Files: 644
- `wp-config.php`: 600 (more secure) or 644

**Fix via FTP:**
- Right-click files/folders → Change Permissions
- Set accordingly

### 8. Check Memory Limit
**Add to wp-config.php:**
```php
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );
```

### 9. Check for Corrupted Files
**Common causes:**
- Incomplete file upload
- File transfer corruption
- Server disk space full

**Fix:**
1. Re-upload core WordPress files (except wp-config.php and wp-content)
2. Check server disk space in hosting panel

### 10. Check for Recent Changes
**Since it was working yesterday:**
- Check hosting panel for any automatic updates
- Check if any plugins auto-updated
- Check server logs for any changes
- Check if hosting provider made any changes

## Step-by-Step Recovery Process

### Step 1: Enable Debug Mode
1. Backup current `wp-config.php`
2. Use `wp-config-live-debug.php` (rename to `wp-config.php`)
3. Update database credentials
4. Check `wp-content/debug.log` for specific error

### Step 2: Identify the Issue
Based on error log:
- **Fatal error in plugin**: Disable that plugin
- **Fatal error in theme**: Switch to default theme
- **Database error**: Check database connection
- **Memory error**: Increase memory limit
- **PHP version error**: Update PHP version

### Step 3: Fix the Issue
Follow the specific fix for the error found.

### Step 4: Disable Debug Mode
Once fixed, disable debug mode:
```php
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
```

## Common Error Messages & Fixes

### "Call to undefined function"
- **Cause**: Plugin/theme using function that doesn't exist
- **Fix**: Update plugin/theme or disable it

### "Maximum execution time exceeded"
- **Cause**: Script taking too long
- **Fix**: Increase `max_execution_time` in php.ini or add to wp-config.php:
```php
@ini_set('max_execution_time', 300);
```

### "Allowed memory size exhausted"
- **Cause**: Not enough PHP memory
- **Fix**: Increase memory limit (see #8 above)

### "Cannot modify header information"
- **Cause**: Output before headers
- **Fix**: Check for whitespace before `<?php` or after `?>` in PHP files

### Database connection errors
- **Cause**: Wrong credentials or database down
- **Fix**: Verify database credentials and connection

## Prevention for Future

1. **Always backup before updates**
2. **Test updates on staging first**
3. **Keep plugins/themes updated**
4. **Monitor error logs regularly**
5. **Use staging environment for testing**

## Need More Help?

If none of these work:
1. Check hosting provider's support documentation
2. Contact hosting support with error logs
3. Check WordPress support forums
4. Review server error logs in hosting panel

