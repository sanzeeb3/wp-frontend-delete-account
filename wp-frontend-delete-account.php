<?php
/**
 * Plugin Name: WP Frontend Delete Account
 * Description: Lets customers delete their account by their own.
 * Version: 1.2.0
 * Author: Sanjeev Aryal
 * Author URI: http://www.sanjeebaryal.com.np
 * Text Domain: wp-frontend-delete-account
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define WPFDA_PLUGIN_FILE.
if ( ! defined( 'WPFDA_PLUGIN_FILE' ) ) {
	define( 'WPFDA_PLUGIN_FILE', __FILE__ );
}

require_once 'vendor/autoload.php';

/**
 * Return the main instance of Main Class.
 *
 * @since  1.2.0
 *
 * @return Main.
 */
function wp_frontend_delete_account() {
		return \WP_Frontend_Delete_Account\Main::get_instance();
}

wp_frontend_delete_account();
