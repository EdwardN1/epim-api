<?php
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
define( 'DB_NAME', "c76kosnicold" );

/** MySQL database username */
define( 'DB_USER', "c76kosnicold" );

/** MySQL database password */
define( 'DB_PASSWORD', "KO?841241ed" );

/** MySQL hostname */
define( 'DB_HOST', "127.0.0.1" );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', 'utf8_unicode_ci' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'yT?7f{U:i%>b!lz=u3wA<gofsK;pQMHLM%y{TYo9mwz6@luX95:r=YXu k)1vZI8' );
define( 'SECURE_AUTH_KEY',  '$ %RxhP#yX}rf)d|I0tp>QLr6N:CiGr^MdMVV,mPYbPfl:.q`5B(b*W_<gr^J^::' );
define( 'LOGGED_IN_KEY',    'I/X?>c5XH!pCv}<Mb_2x<0{TNRVpG.s0G`=}|HoG2E}C0PY@f>HIw6H9nn+`NnU?' );
define( 'NONCE_KEY',        'azB0^rZ{UA,1[Wc28M5*8@TT[|stTcK+au|z16U[e/^C_)EQz`D9?BAF!;^bI/~H' );
define( 'AUTH_SALT',        'I$IjJR.b<Iq,iCRAM]JjFO$1+2>M 7];WGXZd2Y|S4?6E}N2w1~+JvkkQk^Tn{D)' );
define( 'SECURE_AUTH_SALT', 'qaYoR-,g1}5]e1)!xdDF6(AQ6;&|v/%]BeBj+CSJ+g5,R8X/tXu+|Kn|{jcdV)i/' );
define( 'LOGGED_IN_SALT',   'S,9e> qv _V#>t,qe~:Buvg)|az0~J^&`ep,]h/7 s|yIv*a~,RndX2%;L<&o;w:' );
define( 'NONCE_SALT',       'mAXz]?G[e#i=o_f E1Ardr(e]&n8,k?tKjt&mazbM}Sp!=@+iD 8-CtOXmK bYcc' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'DISALLOW_FILE_EDIT', false );
define( 'DISALLOW_FILE_MODS', false );
define( 'WP_POST_REVISIONS', 0 );
define( 'DISABLE_WP_CRON', false );
define( 'WP_AUTO_UPDATE_CORE', false );
define( 'DUP_SECURE_KEY', '854c368a13b6f2a5a58fd5e0556d2698' );
define('DISABLE_WP_CRON', true);
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
