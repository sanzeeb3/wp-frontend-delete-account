<?php
/**
 * @since  1.0.0
 *
 * Class for frontend tasks.
 *
 * @class Woo_Delete_Account_frontend
 */
Class Woo_Delete_Account_frontend {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'perform_delete_action' ) );
		add_action( 'wp_footer', array( $this, 'assets_footer' ), 15 );
	}

	/**
	 * Perform Delete Action.
	 *
	 * @since  1.0.0
	 *
	 * @return Void.
	 */
	public function perform_delete_action() {

		if ( isset( $_REQUEST['_wpnonce'] ) && isset( $_REQUEST['woo-delete'] ) ) {
			if( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'woo-delete-account' ) ) {
				return;
			}

			$user_id 	= ( int ) $_REQUEST['woo-delete'];
			$attribute 	= ( int ) get_option( 'wda_attribute' );

			require_once( ABSPATH.'wp-admin/includes/user.php' );

			wp_delete_user( $user_id, $attribute );

		}
	}

	/**
	 * Register scripts for frontend.
	 *
	 * @since  1.0.0
	 * @return void.
	 */
	public function assets_footer() {
		$site_key			= get_option( 'wda_security_recaptcha_site_key' );
		$site_secret		= get_option( 'wda_security_recaptcha_site_secret' );
		$security 			= get_option( 'wda_security', 'password' );

		if ( $site_key && $site_secret && 'recaptcha_v2' === $security ) {
			$recaptcha_api = apply_filters( 'woo_delete_account_recaptcha_url', 'https://www.google.com/recaptcha/api.js?onload=wdaRecaptchaLoad&render=explicit' );
			wp_register_script(
				'wda-recaptcha',
				$recaptcha_api,
				array( 'jquery' ),
				'2.0.0',
				true
			);
		}
	}
}

new Woo_Delete_Account_frontend();
