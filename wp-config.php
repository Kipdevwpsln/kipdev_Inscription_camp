<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
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
define('DB_NAME', 'youcanlmm2246929');

/** MySQL database username */
define('DB_USER', 'youcanlmm2246929');

/** MySQL database password */
define('DB_PASSWORD', 'Mambojambo20222');

/** MySQL hostname */
define('DB_HOST', 'youcanlmm2246929.mysql.db:3306');

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
define('AUTH_KEY',         '1TU1l3k1vnaqV0QubA8dYt0HKSZYqQCyMPZ2E21/1C2DfrSLSfBYdRSstbkD');
define('SECURE_AUTH_KEY',  'nWvc+Q6w6trnINJZSHucxcKwFZFN1E6478itGWFnbRG0ubutOGYCoQCnhhIG');
define('LOGGED_IN_KEY',    '9wUUwdSkCEEh+LPzHz5/eO/WBk7JdNSpCt7VHUmUeCPMRW7ZbN7HVOcfkYuw');
define('NONCE_KEY',        'UVE2rjIMJB13kjV+PVmzmlQtvL6/Vz8R/D2wOzZ5xr3nunEjl/bYVjwvFCC0');
define('AUTH_SALT',        'bleHbSMtdXR9fXUN8SGawjDeKfVn9/oXl4DXlSxY94UO+413HF3i2YFIdat5');
define('SECURE_AUTH_SALT', 'LPg6/XiSTShCxH2d2LdPbnO/0nv1nYPvpHJd9k//+4LhhYlJYEYPhgxv01QC');
define('LOGGED_IN_SALT',   '2BnFMUGjFHtAlCpeutgAtBDE3ACyx3wm+DVIHibVVrhlt3Ks8qjPlj9b0DT6');
define('NONCE_SALT',       'UgaeeJoCfczkntZgw7fibypLyoVa4geGyzgR/YhuVH1kTvcTiC0WG2CzJVmI');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wor5375_';

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

/* Fixes "Add media button not working", see http://www.carnfieldwebdesign.co.uk/blog/wordpress-fix-add-media-button-not-working/ */
define('CONCATENATE_SCRIPTS', false );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
