<?php
/**
 * Plugin Name: Woo Delete Account
 * Description: Lets customers delete thier account by own from their myaccount page.
 * Version: 1.0.0
 * Author: Sanjeev Aryal
 * Author URI: http://www.sanjeebaryal.com.np
 * Text Domain: woo-delete-account
 * Domain Path: /languages/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define WOO_DELETE_ACCOUNT_PLUGIN_FILE.
if ( ! defined( 'WOO_DELETE_ACCOUNT_PLUGIN_FILE' ) ) {
	define( 'WOO_DELETE_ACCOUNT_PLUGIN_FILE', __FILE__ );
}

// Include the main Delete Account class.
if ( ! class_exists( 'Woo_Delete_Account' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-woo-delete-account.php';
}

// Initialize the plugin.
add_action( 'plugins_loaded', array( 'Woo_Delete_Account', 'get_instance' ) );
