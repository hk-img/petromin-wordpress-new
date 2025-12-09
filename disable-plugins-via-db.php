<?php
/**
 * EMERGENCY PLUGIN DISABLER
 * 
 * Use this file to disable all plugins via database when you can't access WordPress admin
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your WordPress root directory (same folder as wp-config.php)
 * 2. Access it via browser: https://your-site.com/disable-plugins-via-db.php
 * 3. It will disable all plugins and show a success message
 * 4. DELETE THIS FILE immediately after use for security!
 * 
 * SECURITY WARNING: Delete this file after using it!
 */

// Load WordPress
require_once('wp-load.php');

// Security check - only allow if user is logged in as admin
if (!is_user_logged_in() || !current_user_can('manage_options')) {
    die('Access denied. You must be logged in as an administrator.');
}

// Get database connection details from wp-config
global $wpdb;

// Disable all plugins
$result = $wpdb->query("UPDATE {$wpdb->options} SET option_value = '' WHERE option_name = 'active_plugins'");

// Clear any cache
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Plugins Disabled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #0073aa;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="success">
        <h2>✓ All Plugins Disabled Successfully!</h2>
        <p>All active plugins have been disabled. Your site should now be accessible.</p>
    </div>
    
    <div class="warning">
        <h3>⚠ Important Security Notice</h3>
        <p><strong>DELETE THIS FILE IMMEDIATELY!</strong></p>
        <p>This file can be used to disable plugins, so it's a security risk if left on your server.</p>
        <p>Delete: <code>disable-plugins-via-db.php</code></p>
    </div>
    
    <p>
        <a href="<?php echo home_url(); ?>" class="button">Visit Your Site</a>
        <a href="<?php echo admin_url(); ?>" class="button">Go to Admin</a>
    </p>
    
    <hr>
    <p><small>If your site is still not working, try:</small></p>
    <ul>
        <li>Switch to default theme</li>
        <li>Check error logs</li>
        <li>Verify database connection</li>
        <li>Check PHP version compatibility</li>
    </ul>
</body>
</html>

