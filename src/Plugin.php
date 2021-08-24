<?php

namespace WPFrontendDeleteAccount;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly.
}

/**
 * Plugin Class.
 *
 * @since 1.0.0
 *
 * @since 1.2.0 Changed class "WPF_Delete_Account" to "Main" with namespace.
 *
 * @since 1.3.0 Changed class "Main" to "Plugin" for better naming.
 */
final class Plugin {

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
	 * Delete Account Initialize.
	 */
	public function init() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'load_classes' ) );
	}

	/**
	 * Load classes.
	 *
	 * @since 1.5.8.
	 */
	public function load_classes() {

		$classes = array( 'Backend', 'Frontend', 'WooCommerce', 'Gutenberg', 'Summary' );

		foreach ( $classes as $class ) {
			if ( \class_exists( __NAMESPACE__ . '\\' . $class ) ) {
				$class = __NAMESPACE__ . '\\' . $class;
				$obj   = new $class();
				$obj->init();
			}
		}

		// Load Emails\Summary class.
		$summary = new \WPFrontendDeleteAccount\Emails\Summary();
		$summary->init();

		do_action( 'wp_frontend_delete_account_loaded' );
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
