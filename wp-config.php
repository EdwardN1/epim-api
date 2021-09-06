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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'c76gosparkystage');

/** MySQL database username */
define('DB_USER', 'c76gosparkystage');

/** MySQL database password */
define('DB_PASSWORD', 'GS?841241ed');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         ':]rESS--7<=2Ti61o|+hv}OGTa%zB11C%)&z8J=--/60FZUIDsR.EX->,XwKaroc' );
define( 'SECURE_AUTH_KEY',  'HWz(Hj3.@VKlk3ON2D>lGL2,Lq0m~|;20VP9gY@^x;nQaWEy`A0LtZ/7nn MN<JN' );
define( 'LOGGED_IN_KEY',    '5^zhX1DCvay.?%r/a)buV~2c[41c1u_1qdWGGwbQu5wNdO`8O7u6X>Ai>l=}0cOo' );
define( 'NONCE_KEY',        'VVyhqp{=v1E,+;,,<Qg=Xe[:86C4:hmbGE>-JW#xm3I>W;NdUrYc^_%|M{d N=7)' );
define( 'AUTH_SALT',        'Y@%<T_1SX~um:aPhl1E-^]iS~8rEF`p LdRv!8^}@$%*p$|`y6rVju5v@;UzCrB<' );
define( 'SECURE_AUTH_SALT', 'wfot|)c&+RCh[OR@,$thl/4})_Q;q$W%WfEYP`]DL;btHq;1#SW@b&DSvp9QHH3^' );
define( 'LOGGED_IN_SALT',   '>W;aVkJMfFd^:Fu>wgoowX|@9_lpr]A8Jk09^0Ew}-&m1@<28H1F+.ndR^JSo_N-' );
define( 'NONCE_SALT',       'AIq~0|V=5ZVlay;E!2ZhLPl<zm>gS1=XAGzC=_|/:Z[6s }NsQ9RYHocob&K_{bB' );

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

define( 'WP_MEMORY_LIMIT', '2048M' );
define('DISABLE_WP_CRON', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
