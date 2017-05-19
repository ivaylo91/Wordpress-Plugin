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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'eXgzcMpdDeI<=<38!*$seo/lMq}m$l--3Pk1MZ/#>x<ld<tQ! .}l}l;2!/4H7(=');
define('SECURE_AUTH_KEY',  '3zyA$lU!}UI)XeTBr,7p<X<{SzjjPu2qd5ALH3#1t*4odbnL@qlRZ%7N@WnLCjm^');
define('LOGGED_IN_KEY',    'u,el=IC3YU?xNb:H$_ZTv!j;FMfTtu[2Bzb&}Q->Yd( ,j2cC7hw /xA-w32oMSE');
define('NONCE_KEY',        'Y$/L/]K(5ITY2+E0@P:h^Q$Ui$]RGed0!f7lms,1=Ts Gptt~z,Lb6!Xdb/Q XBS');
define('AUTH_SALT',        '(1%(em5b;.tiIl4Sd]OtM~p{K]w+f/USKkB/hHWJ`+09A5a)_H:=lqv^OFcrfhw{');
define('SECURE_AUTH_SALT', 'q^ANO e_oQ?dEy-DE?kM()Z:P+-Tpuv%ZXXriJ37J`>}Z(Y42sg^F=fc7uI@>K8_');
define('LOGGED_IN_SALT',   '84{Yi$.>kgQ.!;LS.(t3@_gyCd5|zBG0lq.}bP~[#0Niu)/8/s<$C$!WHC2w}l@1');
define('NONCE_SALT',       'lQ@a@e48nYt5i(v_xZUb!&JTJM.E1;OVRb|5M3qN=c9QusXy_F@,xW<hD%sS>)Sq');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
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

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
