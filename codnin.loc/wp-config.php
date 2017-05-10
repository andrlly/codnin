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
define('DB_NAME', 'codnin');

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
define('AUTH_KEY',         '507jnHRZVv0P<!?zARpRt0C%{6/kz;l/0v6nG&,7rjUG/QqQ1/fcU2&m-3c[qGhu');
define('SECURE_AUTH_KEY',  'M-l{MzmYM-R%X|Q7*22/a](cuk=t=IG}~rn$2jXIQ!rc_vHg@]%`#aPL+NHgn_9@');
define('LOGGED_IN_KEY',    'b0CXEqCa4&{a8WkS;TvIHaA$#Q}mSI!OwN24y/8#oTr2lBk>6-=kn+tM6EsbPH(7');
define('NONCE_KEY',        'DB$?f~zg3n*HL28jR#,WR>e`9WrJs;X u);YSea_zt: XX7ZYwlzrRwy~Z~.V_&&');
define('AUTH_SALT',        'E(n7L{1hHcE-o-P:r8U{vSq%YwF1<{zgqf@x<ASLc@l#*(,968K1X_|aOoXmZMz&');
define('SECURE_AUTH_SALT', 'EE},?f0L&q&#C=kDHX]xA!71l9i3Nw=SPTh[3DZ$@yn2fEw{HNCw6BDc#)~Kyak4');
define('LOGGED_IN_SALT',   'dR6S5M+|)DJ1[eV2kcg8Ow9wh[E$t7U U~wWLn<NlF][m)jGm&um8~BLDv&O#JeD');
define('NONCE_SALT',       '34B}5B-pk{H3La0aGw2VO{bfVdh7kp#@MKe`lrwW9HZ3p(4H=A%<Z5SZA8FMMte,');

define('FS_METHOD', 'direct');
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
