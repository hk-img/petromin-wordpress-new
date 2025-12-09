<?php
/**
 * DEBUG VERSION FOR LIVE SERVER TROUBLESHOOTING
 * 
 * IMPORTANT: 
 * 1. Backup your current wp-config.php first!
 * 2. Rename this file to wp-config.php on live server
 * 3. Update database credentials for live server
 * 4. After fixing the issue, disable debug mode again
 * 5. DO NOT leave debug mode enabled on production!
 */

// ** Database settings - UPDATE THESE FOR LIVE SERVER ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'your_live_database_name' );

/** Database username */
define( 'DB_USER', 'your_live_db_username' );

/** Database password */
define( 'DB_PASSWORD', 'your_live_db_password' );

/** Database hostname */
define( 'DB_HOST', 'localhost' ); // Usually 'localhost' but check with your host

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 * Copy these from your original wp-config.php
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
 */
$table_prefix = 'wp_';

/**
 * ENABLE DEBUG MODE FOR TROUBLESHOOTING
 * This will show errors and log them to debug.log
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true ); // Log errors to wp-content/debug.log
define( 'WP_DEBUG_DISPLAY', false ); // Don't display errors on frontend (security)
@ini_set( 'display_errors', 0 ); // Hide errors from visitors

/**
 * Increase memory limit if needed
 */
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );

/**
 * Disable file editing for security
 */
define( 'DISALLOW_FILE_EDIT', true );

/* Add any custom values between this line and the "stop editing" line. */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

