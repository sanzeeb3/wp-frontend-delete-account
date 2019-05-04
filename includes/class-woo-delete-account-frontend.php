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

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 15 );
		add_action( 'wp_ajax_confirm_delete', array( $this, 'confirm_delete' ) );
	}

	/**
	 * Perform Delete Action.
	 *
	 * @since  1.0.0
	 *
	 * @return Void.
	 */
	public function confirm_delete() {
		if ( isset( $_POST['security'] ) ) {
			if( ! wp_verify_nonce( $_POST['security'], 'wda_nonce' ) ) {
				error_log( print_r( 'Nonce Error! ' ) );
			}

			$security 	= get_option( 'wda_security', 'password' );
			$pass 		= isset( $_POST['value'] ) ? $_POST['value'] : '';
			$captcha_answer 	= get_option( 'wda_security_custom_captcha_answer', '33' );

			if( 'password' === $security ) {
				if( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID ) ) {
					$this->delete_user();
				} else {
					wp_send_json_error( array(
						'message' => __( 'Invalid Password!', 'entries-for-wpforms' ),
					) );
				}
			} elseif( 'custom_captcha' === $security ) {
				$value = sanitize_text_field( $_POST['value' ] );
				if( $value === $captcha_answer ) {
					$this->delete_user();
				} else {
					wp_send_json_error( array(
						'message' =>	esc_html__( 'Incorrect Answer. Please try again.', 'woo-delete-account' ),
					) );
				}
			} elseif( 'recaptcha_v2' === $security ) {

			}

		}
	}

	/**
	 * Delete user by ID.
	 *
	 * @return void.
	 */
	public function delete_user() {
		$attribute 	= ( int ) get_option( 'wda_attribute' );
		$user_id 	= get_current_user_id();
		$user 		= get_user_by( 'id', $user_id );

		echo "<pre>"; print_r('NoW DELETE'); echo "</pre>";
		// require_once( ABSPATH.'wp-admin/includes/user.php' );
		// wp_delete_user( $user_id, $attribute );
	}

	/**
	 * Register scripts for frontend.
	 *
	 * @since  1.0.0
	 * @return void.
	 */
	public function enqueue_assets() {
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

		wp_enqueue_script( 'wda-delete-account-frontend', plugins_url( 'assets/js/frontend.js', WOO_DELETE_ACCOUNT_PLUGIN_FILE ), array(), WDA_VERSION, false );

		$security 			= get_option( 'wda_security', 'password' );
		$captcha_answer 	= get_option( 'wda_security_custom_captcha_answer', '33' );
		$site_key			= get_option( 'wda_security_recaptcha_site_key' );
		$site_secret		= get_option( 'wda_security_recaptcha_site_secret' );

		wp_localize_script( 'wda-delete-account-frontend', 'wda_plugins_params', array(
			'ajax_url'           => admin_url( 'admin-ajax.php' ),
			'wda_nonce' 		 => wp_create_nonce( 'wda_nonce' ),
			'security' 			 => $security,
			'captcha_answer'     => $captcha_answer,
			'site_key' 			 => $site_key,
			'site_secret' 		 => $site_secret,
			'recaptcha_required' => esc_html__( 'reCaptcha is required.', 'woo-delete-account' ),
			'incorrect_answer'   => esc_html__( 'Incorrect Answer. Please try again.', 'woo-delete-account' ),
			'empty_password'   => esc_html__( 'Empty Password.', 'woo-delete-account' ),
		) );
	}
}

new Woo_Delete_Account_frontend();
