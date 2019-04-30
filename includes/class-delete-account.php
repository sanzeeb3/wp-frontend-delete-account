<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Delete_Account Class.
 *
 * @class   Delete_Account
 * @version 1.0.0
 */
final class Delete_Account {

	/**
	 * Plugin version.
	 * @var string
	 */
	public $version = '1.0.0';


	/**
	 * Instance of this class.
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
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'delete-account' ), '1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'delete-account' ), '1.0' );
	}

	/**
	 * Delete Account Constructor.
	 */
	public function __construct() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		$this->define_constants();
		$this->includes();

		do_action( 'delete_account_loaded' );
	}

	/**
	 * Define DA Constants.
	 */
	private function define_constants() {
		$this->define( 'DA_ABSPATH', dirname( DELETE_ACCOUNT_PLUGIN_FILE ) . '/' );
		$this->define( 'DA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'DA_VERSION', $this->version );
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
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/delete-account/delete-account-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/delete-account-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'delete-account' );

		load_textdomain( 'delete-account', WP_LANG_DIR . '/delete-account/delete-account-' . $locale . '.mo' );
		load_plugin_textdomain( 'delete-account', false, plugin_basename( dirname( DELETE_ACCOUNT_PLUGIN_FILE ) ) . '/languages' );
	}

	/**
	 * Includes.
	 */
	private function includes() {
		include_once DA_ABSPATH . 'includes/functions-delete-account.php';
		include_once DA_ABSPATH . '/includes/class-delete-account-frontend.php';
		include_once DA_ABSPATH . '/includes/class-woocommerce-my-account-tab.php';

		if( is_admin() ) {
			include_once DA_ABSPATH . '/includes/class-delete-account-backend.php';
		}
	}
}
