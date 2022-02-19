<?php
/**
 * Plugin Name: WP Frontend Delete Account
 * Description: Lets customers delete their account by their own.
 * Version: 1.6.1
 * Author: Sanjeev Aryal
 * Author URI: https://www.sanjeebaryal.com.np
 * Text Domain: wp-frontend-delete-account
 * Domain Path: /languages/
 *
 * @package    WP Frontend Delete Account
 * @author     Sanjeev Aryal
 * @since      1.0.0
 * @license    GPL-3.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly.
}

// Define WPFDA_PLUGIN_FILE.
if ( ! defined( 'WPFDA_PLUGIN_FILE' ) ) {
	define( 'WPFDA_PLUGIN_FILE', __FILE__ );
}

// Plugin Folder Path.
if ( ! defined( 'WPFDA_PLUGIN_DIR' ) ) {
	define( 'WPFDA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * Plugin version.
 *
 * @var string
 */
const WPFDA_VERSION = '1.6.1';

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Return the main instance of Plugin Class.
 *
 * @since  1.2.0
 *
 * @return Plugin.
 */
function wp_frontend_delete_account() {
		$instance = \WPFrontendDeleteAccount\Plugin::get_instance();
		$instance->init();

		return $instance;
}

wp_frontend_delete_account();
