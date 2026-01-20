<?php
/**
 * Plugin Name: Plugin Deactivation Monitor
 * Description: Monitors and logs when plugins are automatically deactivated
 * Version: 1.0
 * Author: GrowGold
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Log plugin deactivation events
 */
add_action('deactivated_plugin', 'gg_log_plugin_deactivation', 10, 2);

function gg_log_plugin_deactivation($plugin, $network_deactivating) {
    $log_file = WP_CONTENT_DIR . '/plugin-deactivation.log';
    $timestamp = current_time('mysql');
    $user = wp_get_current_user();
    $user_info = $user->ID ? $user->user_login : 'System/Auto';
    
    $message = sprintf(
        "[%s] Plugin deactivated: %s | By: %s | Network: %s\n",
        $timestamp,
        $plugin,
        $user_info,
        $network_deactivating ? 'Yes' : 'No'
    );
    
    error_log($message, 3, $log_file);
}

/**
 * Log plugin activation events for comparison
 */
add_action('activated_plugin', 'gg_log_plugin_activation', 10, 2);

function gg_log_plugin_activation($plugin, $network_activating) {
    $log_file = WP_CONTENT_DIR . '/plugin-deactivation.log';
    $timestamp = current_time('mysql');
    $user = wp_get_current_user();
    $user_info = $user->ID ? $user->user_login : 'System/Auto';
    
    $message = sprintf(
        "[%s] Plugin activated: %s | By: %s | Network: %s\n",
        $timestamp,
        $plugin,
        $user_info,
        $network_activating ? 'Yes' : 'No'
    );
    
    error_log($message, 3, $log_file);
}

/**
 * Monitor plugin errors that might cause deactivation
 */
add_action('admin_notices', 'gg_check_plugin_errors');

function gg_check_plugin_errors() {
    $error = get_transient('gg_plugin_error');
    if ($error) {
        echo '<div class="notice notice-error is-dismissible">';
        echo '<p><strong>Plugin Error Detected:</strong> ' . esc_html($error) . '</p>';
        echo '</div>';
        delete_transient('gg_plugin_error');
    }
}

/**
 * Add admin menu to view logs
 */
add_action('admin_menu', 'gg_add_plugin_monitor_menu');

function gg_add_plugin_monitor_menu() {
    add_management_page(
        'Plugin Monitor',
        'Plugin Monitor',
        'manage_options',
        'plugin-monitor',
        'gg_plugin_monitor_page'
    );
}

/**
 * Display plugin monitor page
 */
function gg_plugin_monitor_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $log_file = WP_CONTENT_DIR . '/plugin-deactivation.log';
    $debug_log = WP_CONTENT_DIR . '/debug.log';
    
    ?>
    <div class="wrap">
        <h1>Plugin Deactivation Monitor</h1>
        
        <div class="card" style="max-width:100%;">
            <h2>Recent Plugin Activity</h2>
            <?php if (file_exists($log_file)): ?>
                <pre style="background: #f5f5f5; padding: 15px; overflow-x: auto; max-height: 400px;"><?php
                    $logs = file_get_contents($log_file);
                    $lines = explode("\n", $logs);
                    $recent_lines = array_slice(array_reverse($lines), 0, 50);
                    echo esc_html(implode("\n", array_reverse($recent_lines)));
                ?></pre>
                <p>
                    <a href="<?php echo admin_url('admin-ajax.php?action=gg_clear_plugin_log'); ?>" 
                       class="button" 
                       onclick="return confirm('Are you sure you want to clear the log?');">
                        Clear Log
                    </a>
                </p>
            <?php else: ?>
                <p>No plugin activity logged yet.</p>
            <?php endif; ?>
        </div>
        
        <div class="card" style="margin-top: 20px; max-width:100%;">
            <h2>Recent Debug Errors</h2>
            <?php if (file_exists($debug_log)): ?>
                <pre style="background: #fff3cd; padding: 15px; overflow-x: auto; max-height: 400px;"><?php
                    $debug_logs = file_get_contents($debug_log);
                    $debug_lines = explode("\n", $debug_logs);
                    $recent_debug = array_slice(array_reverse($debug_lines), 0, 30);
                    echo esc_html(implode("\n", array_reverse($recent_debug)));
                ?></pre>
            <?php else: ?>
                <p>No debug errors logged. Debug logging may be disabled.</p>
            <?php endif; ?>
        </div>
        
        <div class="card" style="margin-top: 20px; max-width:100%;">
            <h2>System Information</h2>
            <table class="widefat">
                <tr>
                    <td><strong>WordPress Version:</strong></td>
                    <td><?php echo get_bloginfo('version'); ?></td>
                </tr>
                <tr>
                    <td><strong>PHP Version:</strong></td>
                    <td><?php echo PHP_VERSION; ?></td>
                </tr>
                <tr>
                    <td><strong>Memory Limit:</strong></td>
                    <td><?php echo WP_MEMORY_LIMIT; ?> (Max: <?php echo WP_MAX_MEMORY_LIMIT; ?>)</td>
                </tr>
                <tr>
                    <td><strong>Active Plugins:</strong></td>
                    <td><?php echo count(get_option('active_plugins', [])); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}

/**
 * AJAX handler to clear plugin log
 */
add_action('wp_ajax_gg_clear_plugin_log', 'gg_clear_plugin_log');

function gg_clear_plugin_log() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    
    $log_file = WP_CONTENT_DIR . '/plugin-deactivation.log';
    if (file_exists($log_file)) {
        unlink($log_file);
    }
    
    wp_redirect(admin_url('tools.php?page=plugin-monitor'));
    exit;
}
