<?php

namespace WPFrontendDeleteAccount;

/**
 * @since  1.0.0
 *
 * @since  1.2.0 changed classname "WPFDA_Frontend" to "Frontend" with namespace.
 *
 * Class for frontend tasks.
 *
 * @class Frontend
 */
class Frontend {

	/**
	 * Constructor.
	 *
	 * @since  1.3.0 Change Constructor to init.
	 */
	public function init() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
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
			if ( ! wp_verify_nonce( $_POST['security'], 'wpfda_nonce' ) ) {
				error_log( print_r( 'Nonce Error! ' ) );
			}

			$security       = get_option( 'wpfda_security', 'password' );
			$captcha_answer = get_option( 'wpfda_security_custom_captcha_answer', 'PERMANENTLY DELETE' );

			if ( 'password' === $security || 'recaptcha_v2' === $security ) { // Backwards compatibility. Removing reCAPTCHA support since 1.1.0.
				$user_id = get_current_user_id();
				$user    = get_user_by( 'id', $user_id );
				$pass    = isset( $_POST['value'] ) ? $_POST['value'] : '';

				if ( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID ) ) {
					$this->delete_user();
				} else {
					wp_send_json_error(
						array(
							'message' => __( 'Invalid Password!', 'wp-frontend-delete-account' ),
						)
					);
				}
			} elseif ( 'custom_captcha' === $security ) {
				$value = sanitize_text_field( $_POST['value'] );

				if ( $value === $captcha_answer ) {
					$this->delete_user();
				} else {
					wp_send_json_error(
						array(
							'message' => esc_html__( 'Incorrect Answer. Please try again.', 'wp-frontend-delete-account' ),
						)
					);
				}
			} elseif ( 'none' === $security ) {
				$this->delete_user();
			}
		}
	}

	/**
	 * Delete user by ID.
	 *
	 * @return void.
	 */
	public function delete_user() {
		$attribute = (int) get_option( 'wpfda_attribute' );
		$user_id   = get_current_user_id();
		$user      = get_user_by( 'id', $user_id );

		do_action( 'wp_frontend_delete_account_process', $user );

		require_once ABSPATH . 'wp-admin/includes/user.php';
		wp_delete_user( $user_id, $attribute );

		$this->send_emails( $user );

		do_action( 'wp_frontend_delete_account_process_complete', $user );

		wp_send_json(
			array(
				'success' => true,
				'message' => esc_html__( 'Deleting...', 'wp-frontend-delete-account' ),
			)
		);
	}

	/**
	 * Register scripts for frontend.
	 *
	 * @since  1.0.0
	 *
	 * @return void.
	 */
	public function enqueue_assets() {

		global $post;

		$has_shortcode = is_singular() && is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'wp_frontend_delete_account' );

		if ( ( defined( 'WC_VERSION' ) && is_account_page() ) || ( function_exists( 'has_block' ) && has_block( 'wp-frontend-delete-account/delete-account-content' ) ) || $has_shortcode || 'on' === get_option( 'wpfda_load_assets_globally' ) ) {

			$suffix   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$security = get_option( 'wpfda_security', 'password' );

			wp_enqueue_script( 'wpfda-delete-account-frontend', plugins_url( 'assets/js/frontend' . $suffix . '.js', WPFDA_PLUGIN_FILE ), array( 'jquery' ), WPFDA_VERSION, false );
			wp_enqueue_style( 'wpfda-frontend-css', plugins_url( 'assets/css/frontend.css', WPFDA_PLUGIN_FILE ), array(), WPFDA_VERSION, false );

			$security       = get_option( 'wpfda_security', 'password' );
			$captcha_answer = get_option( 'wpfda_security_custom_captcha_answer', 'PERMANENTLY DELETE' );

			wp_localize_script(
				'wpfda-delete-account-frontend',
				'wpfda_plugins_params',
				array(
					'ajax_url'         => admin_url( 'admin-ajax.php' ),
					'wpfda_nonce'      => wp_create_nonce( 'wpfda_nonce' ),
					'security'         => $security,
					'captcha_answer'   => $captcha_answer,
					'incorrect_answer' => esc_html__( 'Incorrect Answer. Please try again.', 'wp-frontend-delete-account' ),
					'empty_password'   => esc_html__( 'Empty Password.', 'wp-frontend-delete-account' ),
					'processing'       => esc_html__( 'Processing...', 'wp-frontend-delete-account' ),
				)
			);
		}
	}

	/**
	 * Send emails to admin and user.
	 *
	 * @since  1.0.0
	 *
	 * @return void.
	 */
	public function send_emails( $user ) {

		// Send email to admin.
		if ( 'on' === get_option( 'wpfda_enable_admin_email', 'on' ) ) {
			$to      = get_option( 'wpfda_email_receipent', get_option( 'admin_email' ) );
			$subject = get_option( 'wpfda_admin_email_subject', 'Heads up! A user deleted their account.' );
			$message = get_option( 'wpfda_admin_email_message', 'A user {user_name} - {user_email} has deleted their account.' );

			$message = str_replace( '{user_name}', $user->data->user_login, $message );
			$message = str_replace( '{user_email}', $user->data->user_email, $message );

			$sent = wp_mail( $to, $subject, $message );

			do_action( 'wp_frontend_delete_account_admin_email_sent', $sent );
		}

		// Send email to user.
		if ( 'on' === get_option( 'wpfda_enable_user_email', 'on' ) ) {
			$subject = get_option( 'wpfda_user_email_subject', 'Your account has been deleted successfully.' );
			$message = get_option( 'wpfda_user_email_message', 'Your account has been deleted. In case this is a mistake, please contact the site administrator at ' . site_url() . '' );

			$sent = wp_mail( $user->data->user_email, $subject, $message );

			do_action( 'wp_frontend_delete_account_admin_email_sent', $sent );
		}
	}
}
