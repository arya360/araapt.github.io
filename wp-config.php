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
define( 'DB_NAME', 'arif' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '?b-[n<|y(tQ`+~q77LwA_; +*[Y80+{c309iJtYE^h5!QBdN1&Y}&.Ll.?[4<S-K' );
define( 'SECURE_AUTH_KEY',  '-?k{TA8ZmcZ7*Oh^^vt%}4MqF.5f.l6VelnUIWT2[qC#[V;#3`MQ(m&[xLv!_3.S' );
define( 'LOGGED_IN_KEY',    'zVbIcd<Wm`#j}kG-7/,:,J%[_Go+:Z|L{ooK|t(Aw5FC|lAX5ueb7u=k$y@m7 Ir' );
define( 'NONCE_KEY',        'fyE|A/yI;=1&`KS?,0}tXZ7*1I)vXcJ^&TGN_Z*xhRn`O/BcMyF.?_T(Et&qz(4+' );
define( 'AUTH_SALT',        '@mZ_<em}%Y??A:?`]]EK?n4~Qio]SgcQOsjx`=Ls[#_@7nK$fGM#c]ueu-OGLly1' );
define( 'SECURE_AUTH_SALT', 'JEt9$bv7e=t(5oAhw G@`GAv>2G1Ew!7eP-^yPbq[8*Vm>A~r5^(v9kkqkb!M08Q' );
define( 'LOGGED_IN_SALT',   'H,n}6%6JD~4Q+kvZS2gc&B{K,jo)i~9Hg+vURJm96.E$xI:i>@h#3tVhopf`?>rt' );
define( 'NONCE_SALT',       'I<!00@-kA1/};8wsZgc]^=C_l+AnD~g^>104Z6h6sr,~L2G=pTfSCRzC?X^FJ,NS' );

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
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
