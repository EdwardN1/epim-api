<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', "c76atcostli" );

/** Database username */
define( 'DB_USER', "c76atcostli" );

/** Database password */
define( 'DB_PASSWORD', "AC?841241ed" );

/** Database hostname */
define( 'DB_HOST', "127.0.0.1" );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'uU}+Fv)4m|=*D_65%d& <!. (?JTRB n3]IDFm.DHwxO`})8D>UAe5N$d>2VQ@@X' );
define( 'SECURE_AUTH_KEY',   'u#apx)M>.K@4k/N*<]wA+WvB9*=(N7tqE =v;Hr>k[|}/.--_+g.&oz7x~n<m02A' );
define( 'LOGGED_IN_KEY',     ']@TRsP4[_]}k3TXh6lI;7:6<e;Q[p{`^r]dV}c8fIXURnj@CW< CR>0LP9dC4YS,' );
define( 'NONCE_KEY',         '7ST]@]SiU.D;aBcXG:51s#TiQl?XbUecAVg5$+Ro3-2FC|d}zj7!RPR$Lk4PX`lw' );
define( 'AUTH_SALT',         'a+)(WG?9hCN[s?Q!5(qLa2j)a$wL}ENv6hyIH=l!X$^52NaPf%@H.*ceAgrO3rky' );
define( 'SECURE_AUTH_SALT',  '=S.cBewG$&?O})U]m)7%d^6edaB83E}PClfw+6hd,?0p*QU5b}[qKB@#.z1e[m!5' );
define( 'LOGGED_IN_SALT',    '>nE2tMCOt-qE.}kaCkfe5e7,c|sz,%w:Umi]+-T;lj71tJ#E3ttFAJT8X4ngW*qp' );
define( 'NONCE_SALT',        'P*szH*S >UO }$q^uc3%Y#2J2ywoCyh^`35ETXOh=uIAF>2|Q]9t=P$~_Y#X/j_k' );
define( 'WP_CACHE_KEY_SALT', '+{fh/[,-Pg0QLnz?]jWgID,njqwF_|<^sZKn`Lm9:b77E*OdAOwqxv=-Qii#r_ZT' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );



/* Add any custom values between this line and the "stop editing" line. */



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
define( 'DUPLICATOR_AUTH_KEY', '7pi_MO:N+e)C;me0eiqP{eG4gjwXZGTyjUY;kFY6;R4D`/BPv_^Wt$._bt^#1MG4' );
define( 'WP_MEMORY_LIMIT', '2048M' );
define( 'DISABLE_WP_CRON', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';