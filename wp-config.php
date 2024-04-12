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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', "c76forumlive" );

/** Database username */
define( 'DB_USER', "c76forumlive" );

/** Database password */
define( 'DB_PASSWORD', "FO?841241ed" );

/** Database hostname */
define( 'DB_HOST', "127.0.0.1" );

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
define( 'AUTH_KEY',         'B}$;(%Y3@zS:H5azPp+BQ]VU{hXkS!~nF 1pH=g7N|&$D`Er4Qbz<[N_[:PI+aVl' );
define( 'SECURE_AUTH_KEY',  'uQP3zueU30GTDi?;4C?AX/[ZOK{-*an;sH.ZaHB97sWNYGv^{E%+-TMbF|jbuS@t' );
define( 'LOGGED_IN_KEY',    '|_QQ>){vKR&=4:E&k.+^IY9+?D=eOhD Yk2R@)6mYz}R,l!xgtM_`kx=aktWQ`YD' );
define( 'NONCE_KEY',        '2)IsQ]z,~G<GPdA=Air4Wj*>CWlXvD(IR6f*&Cid.Ka1K<4]fafW$8R+xn@nlf`t' );
define( 'AUTH_SALT',        'GECnJQy5qIiU@i]|r0qVJiGE w~UD;e6Mz,bB{&W/v{S@5^tF#4nJ+t:s@O&>{ne' );
define( 'SECURE_AUTH_SALT', 'X0::0{Xbb1x7^s_}[Hz:U)V+#V!7UBt:M:83$&1;PP@Pm/<bVNa,W1{$kYAnH2?Z' );
define( 'LOGGED_IN_SALT',   'qzLXkhhX[6<uR3r-kdGK59&!5yoj2<[cjdUfAh0 VIU}S!bg$~L%&NftZ(Vtlo1&' );
define( 'NONCE_SALT',       'Sz*u3036mR6:EOkj;4=K91$%3g>2W&Lca;|vp$uPI04PF>a<TJw}-1hr(BlV2RcV' );

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

/* Add any custom values between this line and the "stop editing" line. */

define( 'DISABLE_WP_CRON', true );

define( 'DUPLICATOR_AUTH_KEY', '7;Tz<)*.|+[9e5@FsnS!4/.}SR?1];O`t8SU{:o^cQrC)t,.tY9xF1Unx7Us0pIs' );
/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
