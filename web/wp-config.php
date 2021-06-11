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
define('DB_NAME', "wordpress");

/** MySQL database username */
define('DB_USER', "wordpress");

/** MySQL database password */
define('DB_PASSWORD', "32710dcd19e92e3caf30f9429fb0fcb575d1a179c96ddf32");

/** MySQL hostname */
define('DB_HOST', "localhost");

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
/*
define( 'AUTH_KEY',         'xc<)fDjlAWz1OQ3fqDGMmnck )vF!ha4FQo64aHT:@e?|h),iGC+>ySyf6`@d*CX' );
define( 'SECURE_AUTH_KEY',  'OD2?7{z~7R0Td K8$,WRTl~pT(y+rweGin9Rjr>MD#/MyOO_6J4#6 J|4WvpBfVY' );
define( 'LOGGED_IN_KEY',    'o`n8Szzjzr jdFh~:M)#N`sAjlC<StP,Nyk@AiQb&VildY&t}{M)P}}15jY#5!WK' );
define( 'NONCE_KEY',        'JwhisJ-,}oNGekFmZ@JNoSXCicBPG94)ID@oROr;?rcS}2,ms#&&-a6Vt/P&FB(Y' );
define( 'AUTH_SALT',        ')9N0ps1`_;rz>G[3o.n;<  !$nC[.gYmj{1lqx)i4r_L8]S^wx!#AY`C:63ybf20' );
define( 'SECURE_AUTH_SALT', '*a!lMqOvUjO qIq}Nx#UR6|.yF]qypNWT$-[N;}^cRCgW#r_q#7W*yNGe9<6ShNT' );
define( 'LOGGED_IN_SALT',   ',.d}[oe^$x<)}A74K:GCXYu_m.,V?I}lm&CRGpU a]1i4er(egSrIE`)ZElcD=2^' );
define( 'NONCE_SALT',       'ua|%9{?.K;sw`8I%bN[hw[&;j_3@8&/U9;e66sGkqfHaya!{08BD?c,n<E`27Ga]' );
*/
define('AUTH_KEY',         'Z|T(`]gR++/|.+?0w>w7`9# e_5 (N?THoZ- r%+h*of(-;~~K;v&$4N~K.VppP-');
define('SECURE_AUTH_KEY',  'tBS~I|m~JwqbUE[6H@(!uLDI:0|%ez]<TI!=Q.|EP-q+#kbD{&GN(4Fk8O3ST.CT');
define('LOGGED_IN_KEY',    ':DgAmB7# :Q!>S4+1B`QW6{xUSh<!HUq<4}5Q2>u#g92+6/i53!=PLA4*!R<;@-Y');
define('NONCE_KEY',        ']cm.dAazJrIu*kF|B{A^vBWR<+u?J1*Fg3$*khr@>OF1Ciz|%Dd<}x&6^M!PDj0^');
define('AUTH_SALT',        'y$6 m+JTTEqQs|+Z- k}./Qp2o9$s^kB>NMEbz3QVN@AX{[_oykmJ]]l<-h-qZvd');
define('SECURE_AUTH_SALT', 'E3R4;}4vV5*R@2|*T3e?Q9Z@6+T8RA-8r9cnj{7U[2zsNGxA+ql`j&N*k5Q N!/O');
define('LOGGED_IN_SALT',   '?*LAs+XGS/LS2P-`o/g-R|-aXsf/CvU5e(=WL6)BK+k#R?:&A[ee,>9q}beG*L+Q');
define('NONCE_SALT',       '4}M96;*W8z[P7S.|vD1;jgQ^l. (>@6|4aglc5C3_uMRrwmoG++xWzL1n`GxE%/>');

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
