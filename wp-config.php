<<<<<<< HEAD
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
define('DB_NAME', 'wp_test');

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
define('AUTH_KEY',         '*x%mOk/&l^T|e[@jXf4mep*s=igf0L~<},V0ZgcBJ=n&4g2cfR3y>-EdV6j|t7d?');
define('SECURE_AUTH_KEY',  '<`:Qq#.jq-;sKRM9B0zK_jYB)Zs^*26V=@;7R3!?!8HMtEeuk=Wm/%QX}mGva}5=');
define('LOGGED_IN_KEY',    'nG&|o/h;/ZIphW2eFQ=g+*:|ej}.r;*mU&#JmwDj-~Dy:{c]R}hkNu?NEZ8l;?dg');
define('NONCE_KEY',        'Bq)UF?lCjmv#DcJ|4=~xB_?1-|s5tH7@v7R[?pw^W5Wzt[It{s~Uu&aLv6?YbS[k');
define('AUTH_SALT',        '|^zfe6a%lxl:q00|6VsB61e{T $d,v`crfe{CQdGTbyMFp}SnK@OV8snvRusU7iG');
define('SECURE_AUTH_SALT', 'Z{?tQ9eewf(#CD#G?%9NADENKh&bhZ&A&CfH0Vk{W^}rec)=.H0@J95ZHKu|*$HN');
define('LOGGED_IN_SALT',   'WA3G}!YehH-+2be|xZu^<233*%Uld_n{Q%1arn.N%4Y^^`][!^vI3`GBB!$s^gDy');
define('NONCE_SALT',       '0SDO}M,mhG-U8>0~r#=E!3QnsoYn&>OL.I+Q?AC{ >T>5E90<>[bRjBXFFk_`L6L');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'tv_';

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
=======
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
define('DB_NAME', 'wp_dev');

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
define('AUTH_KEY',         '6 O5mo]P}#*(sIl|-RE=18w^?rIA-YqZBNdH?x;h}v9@[um)H0zN? Gm!weR6x9Y');
define('SECURE_AUTH_KEY',  '#(YiN&Txa<6}EsC`Ah^((s6PW]g%R_L?dr)u/nk%4l[?AF/E^=z:MbP*aG=9I+iN');
define('LOGGED_IN_KEY',    'k2:YoC>x.oV0YBrN.pa0T#rW,0cZ] &c_yYxZv@u*Ec.;8I1MsUn4(RrXH2^G,G!');
define('NONCE_KEY',        '.CcNFz:5bOk.D3_m]OL)/v5JR3t]6ImyunI_YyxMw}?t-+Ld-Xe-6jg6w R#~eJ(');
define('AUTH_SALT',        'M7.T<QUaX/R:gp*x(5qHkvu^Hlbf7&p/ U*wBzI:B7Pv5uT7dB,HC?o$h;zS:p_B');
define('SECURE_AUTH_SALT', 'lcfhHaxvE/vt,yOHz%Gp|ZEAKs%{{_fJdRP~?sfaN}]6K0KMr_SnV/kz/^::AT@t');
define('LOGGED_IN_SALT',   'Mh8RqNRwCQxHthkIJiFp(58~eXm99Y0MXWW:K Sn]JK-^pX,,AB}rDaIW6&E(RJK');
define('NONCE_SALT',       'Ngr1*]f3~Ivcse-o}^J^Nu_OJ_{TlAvc1M${,:82Y6e9#EyKL!pzX=[WU:@3:e?v');

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
define( 'SCRIPT_DEBUG', true );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
