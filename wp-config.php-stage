<?php
///////
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ritz2017_staging');

/** MySQL database username */
define('DB_USER', 'ritz2017');

/** MySQL database password */
define('DB_PASSWORD', 'RH?841241ed');

/** MySQL hostname */
define('DB_HOST', '192.168.1.3');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Kt/^Wt6EEA9(6rSiBj{mo#~U?`Dky|/kIt|ITUe}I!MByVCsxm`.zgJ{8gFFrzb|');
define('SECURE_AUTH_KEY',  '1 ONtN@i,bBu]D~|ES!|kmJq)0hj-!MpB*B;^{Okj?Io{vH50&tQ<at1n?{nnoTu');
define('LOGGED_IN_KEY',    'YA`-^hD;[)`>$]5+7#PN~xZ=V>j-WnY(,E@OTo_ju%yD<jvv`r:|=kYa,tBsUMk9');
define('NONCE_KEY',        'B*E8Y+(@{uXFX(py^M~$r2J;ue^bIARB|:-Mx~*jFM)~*ivSq}VzE>y:fg{J0M*9');
define('AUTH_SALT',        '+GK8&k$E*% ;zl{sXxdm|+;923]1?`}Rr+|wdky9y_}A+aih9Z>Sqx.M+hkMHs{)');
define('SECURE_AUTH_SALT', 'Y`Jl!F-^bjM0~}&1,9HH+Ws)].qC`WQJw;fVrHOfK!MdYY;D(9^f>UgUgqp0TW$&');
define('LOGGED_IN_SALT',   'T*6iNlFBdH=Dx/W82H-!Z@{_r.2a.!Yr@g7nNk2},{j9^)lZZXLzYxCLldf3O}T%');
define('NONCE_SALT',       'ZOtkHjXyAFxmrmLDRO|7ovx](m6A/SYOL|Pfjwov]%u,_K&hfRL*%.8ysV,u>pjM');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

define( 'FORCE_SSL_ADMIN', true );

// in some setups HTTP_X_FORWARDED_PROTO might contain
// a comma-separated list e.g. http,https
// so check for https existence
if( false !== strpos( $_SERVER['HTTP_X_FORWARDED_PROTO'], 'https' ) ) {
    $_SERVER['HTTPS'] = 'on';
}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('NONCE_SALT',       'h45fddsha8huj');
define('WP_CACHE_KEY_SALT', 'staging.theritzlondon.com');
define('WP_CACHE', false);
