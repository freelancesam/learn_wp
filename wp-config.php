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
define('DB_NAME', 'wordpress_sitedev');

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
define('AUTH_KEY',         'xy9YPjnmbe:^[>#%YQGsjHQY7V4Y !Dg&f6rf|J`n07xUwVDJf*<]%>to>gHlb H');
define('SECURE_AUTH_KEY',  'ez/xhn;hYxP3~tQA- X+z-S#Egq;a[t3WJ<ccZW,Xb/X)T/vs-hs{l}wI=I%^1F@');
define('LOGGED_IN_KEY',    'TNlFdP29p>&ft&t?qC]K{-f:fAc%!t^>EY.ZUN6yMS1{.buN~4kHEXB+>2zbg])<');
define('NONCE_KEY',        'azM08|Iu1%A/G1X-^$9Wohrc*9Tha7L[(vlqBn`>6/ZF{8kRvKc}tKJKW]-hnr?4');
define('AUTH_SALT',        '-Re:ck,Y0:Ygf.CNvHi;:K$`PSV0YFh&~wHXFZ{4nv?H !Wf#p]G9U[PLXDUF)&}');
define('SECURE_AUTH_SALT', 'KQ9]o}``6NXnyT@]@=.)N3vYu!<nRS)i2 A:h9^rP%TIdj(P3{(mnPePX)O84.O,');
define('LOGGED_IN_SALT',   'JR7/5%no,Ro]d(% uDnqN4w(,n!TI>Mu->)s!1i]aRYne;d`KV]i0GoCGNW2o3k+');
define('NONCE_SALT',       '9J?276vk5?qV/Q)<H)#`lq @^-vxq|jX#~>%mo?}Ng?t~7v)^7x?>N}7oYY`ix@S');

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
