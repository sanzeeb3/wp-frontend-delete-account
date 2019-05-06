<?php
/**
 * @since  1.0.0
 *
 * Class for frontend tasks.
 *
 * @class WPFDA_Frontend
 */
Class WPFDA_Frontend {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 15 );
		add_action( 'wp_ajax_confirm_delete', array( $this, 'confirm_delete' ) );
		add_shortcode( 'wp_frontend_delete_account', 'wpf_delete_account_content' );
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
			if( ! wp_verify_nonce( $_POST['security'], 'wpfda_nonce' ) ) {
				error_log( print_r( 'Nonce Error! ' ) );
			}

			$security 			= get_option( 'wpfda_security', 'password' );
			$captcha_answer 	= get_option( 'wpfda_security_custom_captcha_answer', '33' );

			if( 'password' === $security ) {
				$user_id 	= get_current_user_id();
				$user 		= get_user_by( 'id', $user_id );
				$pass 		= isset( $_POST['value'] ) ? $_POST['value'] : '';

				if( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID ) ) {
					$this->delete_user();
				} else {
					wp_send_json_error( array(
						'message' => __( 'Invalid Password!', 'wp-frontend-delete-account' ),
					) );
				}
			} elseif( 'custom_captcha' === $security ) {
				$value = sanitize_text_field( $_POST['value' ] );

				if( $value === $captcha_answer ) {
					$this->delete_user();
				} else {
					wp_send_json_error( array(
						'message' =>	esc_html__( 'Incorrect Answer. Please try again.', 'wp-frontend-delete-account' ),
					) );
				}
			} elseif( 'recaptcha_v2' === $security ) {

				if ( ! empty( $_POST['value'] ) ) {
					$site_secret	= get_option( 'wpfda_security_recaptcha_site_secret' );
					$data 			= wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $site_secret . '&response=' . $_POST['value'] );
					$data 			= json_decode( wp_remote_retrieve_body( $data ) );
					if ( empty( $data->success ) ) {
						wp_send_json_error( array(
							'message' =>	esc_html__( 'Incorrect reCaptcha. Contact your site administrator.', 'wp-frontend-delete-account' ),
						) );
					} else {
						$this->delete_user();
					}
				} else {
					wp_send_json_error( array(
						'message' =>	esc_html__( 'reCaptcha is required.', 'wp-frontend-delete-account' ),
					) );
				}
			}

		}
	}

	/**
	 * Delete user by ID.
	 *
	 * @return void.
	 */
	public function delete_user() {
		$attribute 	= ( int ) get_option( 'wpfda_attribute' );
		$user_id 	= get_current_user_id();
		$user 		= get_user_by( 'id', $user_id );

		do_action( 'wp_frontend_delete_account_process' );

		require_once( ABSPATH.'wp-admin/includes/user.php' );
		wp_delete_user( $user_id, $attribute );

		wp_send_json( array(
				'success' => true,
				'message' => esc_html__( 'Deleting...', 'wp-frontend-delete-account' ),
		) );

		do_action( 'wp_frontend_delete_account_process_complete' );
	}

	/**
	 * Register scripts for frontend.
	 *
	 * @since  1.0.0
	 * @return void.
	 */
	public function enqueue_assets() {
		$site_key			= get_option( 'wpfda_security_recaptcha_site_key' );
		$site_secret		= get_option( 'wpfda_security_recaptcha_site_secret' );
		$security 			= get_option( 'wpfda_security', 'password' );

		if ( $site_key && $site_secret && 'recaptcha_v2' === $security ) {
			$recaptcha_api = apply_filters( 'wp_frontend_delete_account_recaptcha_url', 'https://www.google.com/recaptcha/api.js?onload=wpfdaRecaptchaLoad&render=explicit' );
			wp_register_script(
				'wpfda-recaptcha',
				$recaptcha_api,
				array( 'jquery' ),
				'2.0.0',
				true
			);
		}

		wp_enqueue_script( 'wpfda-delete-account-frontend', plugins_url( 'assets/js/frontend.js', WPFDA_PLUGIN_FILE ), array(), WPFDA_VERSION, false );
		wp_enqueue_style( 'wpfda-frontend-css', plugins_url( 'assets/css/wpfda-frontend.css', WPFDA_PLUGIN_FILE ), array(), WPFDA_VERSION, false );

		$security 			= get_option( 'wpfda_security', 'password' );
		$captcha_answer 	= get_option( 'wpfda_security_custom_captcha_answer', '33' );
		$site_key			= get_option( 'wpfda_security_recaptcha_site_key' );
		$site_secret		= get_option( 'wpfda_security_recaptcha_site_secret' );

		wp_localize_script( 'wpfda-delete-account-frontend', 'wpfda_plugins_params', array(
			'ajax_url'           => admin_url( 'admin-ajax.php' ),
			'wpfda_nonce' 		 => wp_create_nonce( 'wpfda_nonce' ),
			'security' 			 => $security,
			'captcha_answer'     => $captcha_answer,
			'site_key' 			 => $site_key,
			'site_secret' 		 => $site_secret,
			'recaptcha_required' => esc_html__( 'reCaptcha is required.', 'wp-frontend-delete-account' ),
			'incorrect_answer'   => esc_html__( 'Incorrect Answer. Please try again.', 'wp-frontend-delete-account' ),
			'empty_password'   	 => esc_html__( 'Empty Password.', 'wp-frontend-delete-account' ),
			'processing'   	 	 => esc_html__( 'Processing...', 'wp-frontend-delete-account' ),
		) );
	}
}

new WPFDA_Frontend();
