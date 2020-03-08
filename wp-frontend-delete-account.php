<?php
/**
 * Plugin Name: WP Frontend Delete Account
 * Description: Lets customers delete their account by their own.
 * Version: 1.1.1
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

// Include the main Delete Account class.
if ( ! class_exists( 'WPF_Delete_Account' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-wp-frontend-delete-account.php';
}

// Initialize the plugin.
add_action( 'plugins_loaded', array( 'WPF_Delete_Account', 'get_instance' ) );
