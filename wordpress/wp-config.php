<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'sneakers');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'RMa[suK5DDlRn!a@)?.+^_k%,+*;lHeZVR0|iZJv7D!0]|97Fx-+Dhl*HjA.qd1c');
define('SECURE_AUTH_KEY',  'Q]4%A~W3!:L8H.G50Wb1WAd*8yap6-ZuZ-x-aY4&19y[E< n4qupG]g-Y:>}Rb|v');
define('LOGGED_IN_KEY',    'j]DF$c-X816BRGi|%xS0fa-:CD)qhV/)4GWVd+ j;Qk{Qq?_KcS1iee79%4LC~dJ');
define('NONCE_KEY',        '7+bC:7H](eFe~d aI!9/<sz3^}MK~-34siIz.S<&P6$f1|VtZY=,>Swtqe]g9m_|');
define('AUTH_SALT',        'Ca[!j9FCN-8w0uzG|XS>zt9s960nD*B[)+`oDTb|<TlzSYh+>c|.yD|A4o]0WFpS');
define('SECURE_AUTH_SALT', 'muPpJI=TB8vx-wAp/YVc%dHM%CB[m2Ac>1bx,&<D_/%AS&BjJD?[Z;jVGmq?yJ;(');
define('LOGGED_IN_SALT',   ')sabAAIi/=LmtS7kRj-2R$zD3/[7d-C_$ bmc6<X _4A=;+;Njx[Y1W&{}ji)$xS');
define('NONCE_SALT',       'U$1c<n_?$W m;pwoEYif/V9KTW`e?ejV:qKB,<M`No{38*v>*MOK>-:6%0ir;`/!');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
