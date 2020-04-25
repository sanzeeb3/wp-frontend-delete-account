<?php

namespace WP_Frontend_Delete_Account;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Class.
 *
 * @since 1.0.0
 *
 * @since 1.2.0 Changed class "WPF_Delete_Account" to "Main" with namespace.
 */
final class Main {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Delete Account Constructor.
	 */
	public function __construct() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		do_action( 'wp_frontend_delete_account_loaded' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name   Name of the constant.
	 * @param string|bool $value  Value of the constant.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/wp-frontend-delete-account/wp-frontend-delete-account-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/wp-frontend-delete-account-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-frontend-delete-account' );

		load_textdomain( 'wp-frontend-delete-account', WP_LANG_DIR . '/wp-frontend-delete-account/wp-frontend-delete-account-' . $locale . '.mo' );
		load_plugin_textdomain( 'wp-frontend-delete-account', false, plugin_basename( dirname( WPFDA_PLUGIN_FILE ) ) . '/languages' );
	}
}
