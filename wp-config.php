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
define('DB_NAME', "erfelect_wp_khmde");

/** MySQL database username */
define('DB_USER', "erfelect_wp_fwfml");

/** MySQL database password */
define('DB_PASSWORD', "a#d4L1Eey~^\$Us2v");

/** MySQL hostname */
define('DB_HOST', "localhost:3306");

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
Changed by Josh 10:47 - Wed 18th August // Need to force logout all users
define('AUTH_KEY',         'Z|T(`]gR++/|.+?0w>w7`9# e_5 (N?THoZ- r%+h*of(-;~~K;v&$4N~K.VppP-');
define('SECURE_AUTH_KEY',  'tBS~I|m~JwqbUE[6H@(!uLDI:0|%ez]<TI!=Q.|EP-q+#kbD{&GN(4Fk8O3ST.CT');
define('LOGGED_IN_KEY',    ':DgAmB7# :Q!>S4+1B`QW6{xUSh<!HUq<4}5Q2>u#g92+6/i53!=PLA4*!R<;@-Y');
define('NONCE_KEY',        ']cm.dAazJrIu*kF|B{A^vBWR<+u?J1*Fg3$*khr@>OF1Ciz|%Dd<}x&6^M!PDj0^');
define('AUTH_SALT',        'y$6 m+JTTEqQs|+Z- k}./Qp2o9$s^kB>NMEbz3QVN@AX{[_oykmJ]]l<-h-qZvd');
define('SECURE_AUTH_SALT', 'E3R4;}4vV5*R@2|*T3e?Q9Z@6+T8RA-8r9cnj{7U[2zsNGxA+ql`j&N*k5Q N!/O');
define('LOGGED_IN_SALT',   '?*LAs+XGS/LS2P-`o/g-R|-aXsf/CvU5e(=WL6)BK+k#R?:&A[ee,>9q}beG*L+Q');
define('NONCE_SALT',       '4}M96;*W8z[P7S.|vD1;jgQ^l. (>@6|4aglc5C3_uMRrwmoG++xWzL1n`GxE%/>');
*/
define('AUTH_KEY',         '1$Q^Xm-++wAZi*`p6|FEx@-h<O+qS^Ku^+L2rN>*9t Myu{y}l}M**|7+7&g%|J/');
define('SECURE_AUTH_KEY',  '4/+|W!NSEL|p `wk#q.zjc 3*7ZC;)]q8j!jwl7|-9d9d&!;=W_MXk@6~|wWg!>d');
define('LOGGED_IN_KEY',    'mN<Y&&4?+VNCw!p34?-q7)amku^YQ2<(zB-f|+CmR/vk^dO;dh/z|%Vk>HRS ;vI');
define('NONCE_KEY',        '}j>^3d7aXHd~LlANud$z!%Zg#c/xN+w_4u,(,n6%Ijt2uu({M>kH-of!bLa6S&2Z');
define('AUTH_SALT',        'xkq|:PuLytA#_ES-L<INfM01=~=z$:[_)~q|(UqL|yOpqMB=>[pag6#d&=-f8HP ');
define('SECURE_AUTH_SALT', '/)_rFd:;iQ.+IDxglZ@+Ft/%W*LEszCGieY+_!ZdU{/mO1#zCNV$|G:uQSKb#J=J');
define('LOGGED_IN_SALT',   'E0d:GgMmbE6<M}%?NSyOVE+K|0.5R{,2nh/97poW-Tor9{|$STz+GSv,C|JMjO(d');
define('NONCE_SALT',       'I>Dn[TS6#Gx`cVy[T?lt>}PnnfWU4LLe(+7fQ[){$Kv+*Zl|v*w#K>1TmyV!D !N');


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
define('DISABLE_WP_CRON', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

define( 'WP_MEMORY_LIMIT', '40M' );

define('WP_FAIL2BAN_ADDON_BLOCKLIST_IGNORE_IPS', [
	'195.224.67.34',
    '176.251.222.95',
    '185.75.213.31'
]);

