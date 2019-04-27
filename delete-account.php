<?php
/**
 * Plugin Name: Delete Account
 * Description: Lets customers delete thier account by own from their myaccount page.
 * Version: 1.4.7
 * Author: Sanjeev Aryal
 * Author URI: http://www.sanjeebaryal.com.np
 * Text Domain: delete-account
 * Domain Path: /languages/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define DELETE_ACCOUNT_PLUGIN_FILE.
if ( ! defined( 'DELETE_ACCOUNT_PLUGIN_FILE' ) ) {
	define( 'DELETE_ACCOUNT_PLUGIN_FILE', __FILE__ );
}

// Include the main Delete Account class.
if ( ! class_exists( 'Delete_Account' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-delete-account.php';
}

// Initialize the plugin.
add_action( 'plugins_loaded', array( 'Delete_Account', 'get_instance' ) );
