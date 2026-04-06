<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress4602' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'i}nC)*_q7Yi{GFB^r,^P|6pT-+F+JY_[Q$}X.=s&Q}&QA@86RyK?U TaG@alHEpw' );
define( 'SECURE_AUTH_KEY',  'SRwRYEu;W3JCG67aaQL`t>#n<ICIhOxIQx)#Z1PEJ<_2@7gCP#l)CQKk~:te;ePf' );
define( 'LOGGED_IN_KEY',    '$QM1c3P08`(l~0DAy=*h+EBC`ROBw[e~9GCcR7CA?SAPxF#ZEB+z%42bis:wXt:2' );
define( 'NONCE_KEY',        'l{jm0%$&Wn8[5]hJ@<,C(60.U_rOTUk3w^mr0W=*fW9)s0s$!.JjvaHM|S6+Ha()' );
define( 'AUTH_SALT',        'emW,UvE`,h8!gOJz:dQU{2H+`3Y1n:(1PQCmv owe`Hw5V)@i5&kUX|z$KDVs]*j' );
define( 'SECURE_AUTH_SALT', '}Z$1UfHUNQ{T@?[bY-T77s0OZ9U[Y<$9IM:@wCCDran^rM^tu72qwJ`Qc7PG-oB~' );
define( 'LOGGED_IN_SALT',   'RkB. cZ)[H_U$4T[>vH)(&^4+Zai!00]2#M$TJs-:/dx_5:_|aH2Z*r^q<jH6&h:' );
define( 'NONCE_SALT',       'o7370JswjPa<l,vuE_m?DKf4T8YrMshd].2%Gx_,ZR7m3`u1@D[h10Wx7]?Ml,Y,' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
