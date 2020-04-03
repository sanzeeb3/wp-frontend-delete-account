<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main WPF_Delete_Account Class.
 *
 * @class   WPF_Delete_Account
 * @version 1.0.0
 */
final class WPF_Delete_Account {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version = '1.1.2';


	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $_instance = null;

	/*
	 * Return an instance of this class.
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-frontend-delete-account' ), '1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-frontend-delete-account' ), '1.0' );
	}

	/**
	 * Delete Account Constructor.
	 */
	public function __construct() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		$this->define_constants();
		$this->includes();

		do_action( 'wp_frontend_delete_account_loaded' );
	}

	/**
	 * Define WPFDA Constants.
	 */
	private function define_constants() {
		$this->define( 'WPFDA', dirname( WPFDA_PLUGIN_FILE ) . '/' );
		$this->define( 'WPFDA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'WPFDA_VERSION', $this->version );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name
	 * @param string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 *
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
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

	/**
	 * Includes.
	 */
	private function includes() {
		include_once WPFDA . '/includes/functions-wp-frontend-delete-account.php';
		include_once WPFDA . '/includes/class-wp-frontend-delete-account-frontend.php';
		include_once WPFDA . '/includes/class-wp-frontend-delete-account-woocommerce-my-account-tab.php';
		include_once WPFDA . '/includes/class-wp-frontend-delete-account-gutenberg-block.php';

		if ( is_admin() ) {
			include_once WPFDA . '/includes/class-wp-frontend-delete-account-backend.php';
		}
	}
}
