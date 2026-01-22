<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */
// define('WP_HOME', 'http://192.168.1.52/petromin-wordpress-new');
// define('WP_SITEURL', 'http://192.168.1.52/petromin-wordpress-new');
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'petromin-wordpress-new' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'f?(G:o}^9cYH7z{.kG%w1Q$(grA68aUK^E~OnDzQ^.`toj_kp[kw{kO8/fj6J#8A' );
define( 'SECURE_AUTH_KEY',  'Rd-@A&!eL/(`I8C/c m!SOuK~Qhgk/R}{i?h%OQ5O$kDv;0%1[NFp_f#W4m14-P:' );
define( 'LOGGED_IN_KEY',    'j0ahYF7t<c@>lm4,:>B|T[B;Q4$}kU)jR1jFq?[UA>d#gWV<Kh9(&]yHhfrhb9^H' );
define( 'NONCE_KEY',        ']/emebHn:Z$d0NFTvK>o(,1idK84[%|TH8hLNh]e&/65uJ7e`t7N 8#bK!Wd$d4z' );
define( 'AUTH_SALT',        '{_[sZyE]Q1G;<&B@8WcqUHO/1y>,gU..f/<A-[AAI9eD8,XE`CoYtGl[r;r6/x(G' );
define( 'SECURE_AUTH_SALT', 'jwDmFTcMA.;])FZ-ri*q&9KFN(!9)zOkoK3Jg<AtAE~A5{xW!LM46VhZZ&)43R^r' );
define( 'LOGGED_IN_SALT',   '3@[q562R[(sBwnD2<%go~l=78%h|j?O8nn_<]#=U>n4LsBQBbu#,vUk{dx/~N.Q,' );
define( 'NONCE_SALT',       ' ?azp+W1@hz-% PZOf{cKdt{XZr60}onfiM$fRG&pu7&T[Tj+)!V^F5CUZm,,v_9' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

// Increase WordPress Memory Limit to prevent plugin deactivation
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// Disable automatic plugin deactivation on updates
define('AUTOMATIC_UPDATER_DISABLED', false);
define('WP_AUTO_UPDATE_CORE', true);

// Enable proper cron job execution
define('DISABLE_WP_CRON', false);

// Enable error logging for debugging
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

define('PETROMIN_ENABLE_FALLBACKS', false);
/**
 * Third-party API Keys and Credentials
 * 
 * IMPORTANT: Never expose these keys in client-side code (JavaScript).
 * All API calls should be made server-side through WordPress AJAX endpoints.
 */

// Supabase API Key
if ( ! defined( 'SUPABASE_API_KEY' ) ) {
	define( 'SUPABASE_API_KEY', 'sb_publishable_YqO5Tv3YM4BquKiCgHqs3w_8Wd7-trp' );
}

// Google Maps API Key
if ( ! defined( 'GOOGLE_MAPS_API_KEY' ) ) {
	define( 'GOOGLE_MAPS_API_KEY', 'AIzaSyDC3RCcvMaCHd7VOf7hRhgceXDQ5cSFyGU' );
}

// MSG91 API Credentials
if ( ! defined( 'MSG91_AUTH_KEY' ) ) {
	define( 'MSG91_AUTH_KEY', '481217AuDxkyUp693ab3c1P1' );
}
if ( ! defined( 'MSG91_TEMPLATE_ID' ) ) {
	define( 'MSG91_TEMPLATE_ID', '693ab28155f110055e100886' );
}
if ( ! defined( 'MSG91_SENDER_ID' ) ) {
	define( 'MSG91_SENDER_ID', 'ATOMCS' );
}

// LeadSquared API Credentials
if ( ! defined( 'LEADSQUARED_ACCESS_KEY' ) ) {
	define( 'LEADSQUARED_ACCESS_KEY', 'u$r0413f07ac8b67751e2a996372c279214' );
}
if ( ! defined( 'LEADSQUARED_SECRET_KEY' ) ) {
	define( 'LEADSQUARED_SECRET_KEY', '9d4ba5b88cb6f3ab35dd03290f691fb391de9ac7' );
}

// MSG91 Template IDs
define('MSG91_TEMPLATE_ID_IPHONE', '693ab28155f110055e100886');
define('MSG91_TEMPLATE_ID_ANDROID', '693ab28155f110055e100886');
define('MSG91_TEMPLATE_ID_DESKTOP', '693ab28155f110055e100886');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
