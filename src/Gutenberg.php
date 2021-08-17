<?php

namespace WPFrontendDeleteAccount;

/**
 * Class for adding gutenberg block.
 *
 * @since  1.0.0
 *
 * @since 1.2.0, changed class name from WPFDA_Gutenberg_Block to Gutenberg with Namespace.
 *
 * @class Gutenberg
 */
class Gutenberg {

	/**
	 * Initialize.
	 *
	 * @since  1.3.0 Change Constructor to init.
	 */
	public function init() {

		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'load_assets' ) );
	}

	/**
	 * Load assets on gutenberg area.
	 *
	 * @return void.
	 */
	public function load_assets() {

		wp_enqueue_script(
			'wpfda-gutenberg-block',
			plugins_url( 'assets/js/admin/gutenberg.min.js', WPFDA_PLUGIN_FILE ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			WPFDA_VERSION,
			true
		);

		wp_localize_script(
		'wpfda-gutenberg-block',
		'wpfda_plugins_params',
			array(
				'ajax_url'            => admin_url( 'admin-ajax.php' ),
				'wpfda_nonce'         => wp_create_nonce( 'wpfda_nonce' ),
				'is_feedback_enabled' => get_option( 'wpfda_enable_feedback_email', 'no' ),
				'redirect_url'        => get_option( 'wpfda_redirect_url' ),
				'incorrect_answer'    => esc_html__( 'Incorrect Answer. Please try again.', 'wp-frontend-delete-account' ),
				'empty_password'      => esc_html__( 'Empty Password.', 'wp-frontend-delete-account' ),
				'processing'          => esc_html__( 'Processing...', 'wp-frontend-delete-account' ),
				'deleting'            => esc_html__( 'Deleting...', 'wp-frontend-delete-account' ),
				'wrong'               => esc_html__( 'Oops! Something went wrong', 'wp-frontend-delete-account' ),
				'current_user_email'  => wp_get_current_user()->user_email,
				'button'              => get_option( 'wpfda_button_label', 'Confirm' ),
				'user_id'             => get_current_user_id(),
				'security'            => get_option( 'wpfda_security', 'password' ),
				'captcha_question'    => get_option( 'wpfda_security_custom_captcha_question', 'Enter PERMANENTLY DELETE to confirm:' ),
				'captcha_answer'      => get_option( 'wpfda_security_custom_captcha_answer', 'PERMANENTLY DELETE' ),
				'password_text'       => get_option( 'wpfda_security_password_text', esc_html__( 'Enter password to confirm:', 'wp-frontend-delete-account' ) ),
				'is_administrator'    => current_user_can( 'administrator' )
			)
		);
	}

	/**
	 * Register gutenber block.
	 *
	 * @return Void.
	 */
	public function register_block() {

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'wp-frontend-delete-account/delete-account-content',
			array(
				'editor_script'   => 'wpfda-gutenberg-block',
				'render_callback' => 'wpf_delete_account_content',
			)
		);
	}
}
