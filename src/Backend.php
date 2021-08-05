<?php

namespace WPFrontendDeleteAccount;

use WPFrontendDeleteAccount\Emails\Emails;

/**
 * Handles the Settings page for WP Frontend Delete Account.
 *
 * @since  1.0.0
 *
 * @since  1.2.0 changed classname "WPFDA_Backend" to "Backend" with namespace.
 *
 * @class Backend
 */
class Backend {

	/**
	 * Initialize.
	 *
	 * @since  1.3.0 Change Constructor to init.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'wpfda_register_setting_menu' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'wp_ajax_wpfda_deactivation_notice', array( $this, 'deactivation_notice' ) );
		add_action( 'wp_ajax_wpfda_deactivation_email', array( $this, 'deactivation_email' ) );
		add_action( 'wp_ajax_wpfda_email_status', array( $this, 'email_status' ) );
		add_action( 'admin_print_scripts', array( $this, 'remove_notices' ) );
	}

	/**
	 * Load assets for backend.
	 *
	 * @since  1.0.0
	 * @return void.
	 */
	public function load_assets() {

		global $pagenow;
		global $current_screen;

		$params = array(
			'ajax_url'           => admin_url( 'admin-ajax.php' ),
			'deactivation_nonce' => wp_create_nonce( 'deactivation-notice' ),
			'wpfda_general_settings_nonce' => wp_create_nonce( 'wpfda-general-settings-save' ),
			'status_nonce'       => wp_create_nonce( 'email-status' ),
			'deactivating'       => esc_html__( 'Deactivating...', 'wp-frontend-delete-account' ),
			'wrong'              => esc_html__( 'Oops! Something went wrong', 'wp-frontend-delete-account' ),
			'enable_email'       => esc_html__( 'Enable this email', 'wp-frontend-delete-account' ),
			'disable_email'      => esc_html__( 'Disable this email', 'wp-frontend-delete-account' ),
			'title'              => get_option( 'wpfda_title', 'Delete Account' ),
			'button'             => get_option( 'wpfda_button_label', 'Confirm' ),
			'attribute'          => get_option( 'wpfda_attribute' ),
			'security'           => get_option( 'wpfda_security', 'password' ),
			'password_text'      => get_option( 'wpfda_security_password_text', 'Enter password to confirm:' ),
			'captcha_question'   => get_option( 'wpfda_security_custom_captcha_question', 'Enter PERMANENTLY DELETE to confirm:' ),
			'captcha_answer'     => get_option( 'wpfda_security_custom_captcha_answer', 'PERMANENTLY DELETE' ),
			'load_assets'        => get_option( 'wpfda_load_assets_globally' ),
			'delete_comments'    => get_option( 'wpfda_delete_comments' ),
			'redirect_url'       => get_option( 'wpfda_redirect_url' ),
			'users'              => get_users()
		);

		if ( 'plugins.php' === $pagenow || 'settings_page_wp-frontend-delete-account' === $current_screen->id ) {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'wpfda-backend', plugins_url( 'assets/css/backend.css', WPFDA_PLUGIN_FILE ), array(), WPFDA_VERSION, $media = 'all' );

			wp_enqueue_script( 'wpf-delete-account-settings-js', plugins_url( 'assets/js/admin/settings.min.js', WPFDA_PLUGIN_FILE ), array( 'wp-element', 'wp-i18n' ), WPFDA_VERSION, false );
			wp_localize_script(
				'wpf-delete-account-settings-js',
				'wpfda_plugins_params',
				$params
			);

			wp_enqueue_script( 'wpf-delete-account-js', plugins_url( 'assets/js/admin/backend' . $suffix . '.js', WPFDA_PLUGIN_FILE ), array( 'wp-element', 'wp-i18n' ), WPFDA_VERSION, false );
			wp_localize_script(
				'wpf-delete-account-js',
				'wpfda_plugins_params',
				$params
			);
		}

	}

	/**
	 * Add WP Frontend Delete Account Submenu
	 *
	 * @since  1.0.0
	 *
	 * @return void.
	 */
	public function wpfda_register_setting_menu() {
		add_options_page( 'WP Frontend Delete Account Settings', 'WP Frontend Delete Account', 'manage_options', 'wp-frontend-delete-account', array( $this, 'wpfda_settings_page' ) );
	}

	/**
	 * Creates settings page.
	 *
	 * @since  1.0.0
	 *
	 * @return void.
	 */
	public function wpfda_settings_page() {

		$emails_active  = isset( $_GET['section'] ) && 'emails' === $_GET['section'] ? 'nav-tab-active' : '';
		$general_active = empty( $email_active ) ? 'nav-tab-active' : '';
		$template       = '<h2 class="nav-tab-wrapper">
			<a href="' . esc_url( admin_url( 'admin.php?page=wp-frontend-delete-account' ) ) . '" class="nav-tab ' . $general_active . '">' . esc_html__( 'General', 'wp-frontend-delete-account' ) . '</a>
			<a href="' . esc_url( wp_nonce_url( admin_url( 'admin.php?page=wp-frontend-delete-account&section=emails' ), 'wp-frontend-delete-account-emails' ) ) . '" class="nav-tab ' . $emails_active . '">' . esc_html__( 'Emails', 'wp-frontend-delete-account' ) . '</a>
			</h2>';

		// phpcs:ignore
		echo $template;

		if ( isset( $_GET['section'] ) && 'emails' === $_GET['section'] ) {

			check_admin_referer( 'wp-frontend-delete-account-emails' );

			if ( ! isset( $_GET['email'] ) ) {
				Emails::overview();
			} else {
				// Manage Emails.
				if ( isset( $_GET['email'] ) ) {
					Emails::emails();
				}
			}

			return;
		}

		$title            = get_option( 'wpfda_title', 'Delete Account' );
		$button           = get_option( 'wpfda_button_label', 'Confirm' );
		$attribute        = get_option( 'wpfda_attribute' );
		$security         = get_option( 'wpfda_security', 'password' );
		$password_text    = get_option( 'wpfda_security_password_text', 'Enter password to confirm:' );
		$captcha_question = get_option( 'wpfda_security_custom_captcha_question', 'Enter PERMANENTLY DELETE to confirm:' );
		$captcha_answer   = get_option( 'wpfda_security_custom_captcha_answer', 'PERMANENTLY DELETE' );
		$load_assets      = get_option( 'wpfda_load_assets_globally' );
		$delete_comments  = get_option( 'wpfda_delete_comments' );
		$redirect_url     = get_option( 'wpfda_redirect_url' );
		$users            = get_users();

		?>
		<h2><?php esc_html_e( 'General Settings', 'wp-frontend-delete-account' ); ?></h2>

			<div id="wp-frontend-delete-account-settings-page">
			</div>
		<?php
	}

	/**
	 * Save settings.
	 *
	 * @since  1.0.0
	 *
	 * @return void.
	 */
	public function save_settings() {//phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		if ( isset( $_POST['wp_frontend_delete_account_settings_nonce'] ) ) {

			// phpcs:ignore
			if ( ! wp_verify_nonce( $_POST['wp_frontend_delete_account_settings_nonce'], 'wpfda-general-settings-save' )
				) {
				print 'Nonce Failed!';
				exit;
			}

			$options = array( 'wpfda_title', 'wpfda_button_label', 'wpfda_redirect_url', 'wpfda_attribute', 'wpfda_security', 'wpfda_security_password_text', 'wpfda_security_custom_captcha_question', 'wpfda_security_custom_captcha_answer', 'wpfda_load_assets_globally', 'wpfda_delete_comments', 'wpfda_email_receipent', 'wpfda_feedback_email_receipent', 'wpfda_enable_user_email', 'wpfda_enable_admin_email', 'wpfda_user_email_subject', 'wpfda_admin_email_subject', 'wpfda_enable_feedback_email', 'wpfda_feedback_email_subject' );

			foreach ( $options as $option ) {
				if ( isset( $_POST[ $option ] ) ) {
					$value = sanitize_text_field( wp_unslash( $_POST[ $option ] ) );
					update_option( $option, $value );
				}
			}

			$email = isset( $_GET['email'] ) ? sanitize_text_field( wp_unslash( $_GET['email'] ) ) : '';

			if ( isset( $_POST [ 'wpfda_' . $email . '_email_message' ] ) ) {
				$editor = wp_kses(
					wp_unslash( $_POST[ 'wpfda_' . $email . '_email_message' ] ),
					array(
						'a'      => array(
							'href'  => array(),
							'title' => array(),
						),
						'b'      => array(),
						'br'     => array(),
						'p'      => array(),
						'pre'    => array(),
						'ul'     => array(),
						'li'     => array(),
						'em'     => array(),
						'strong' => array(),
						'img'    => array(
							'src' => array(),
							'alt' => array(),
						),
					)
				);

				update_option( 'wpfda_' . $email . '_email_message', $editor );
			}//end if
		}//end if
	}

	/**
	 * Email status change from emails overview.
	 *
	 * @since  1.2.0
	 */
	public function email_status() {

		check_admin_referer( 'email-status', 'security' );

		$email  = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		$enable = ! empty( $_POST['enable'] ) ? 'on' : 'off';

		update_option( 'wpfda_enable_' . $email . '_email', $enable );
	}

	/**
	 * Popup feedback on plugin deactivation.
	 *
	 *  @since  1.0.1
	 */
	public static function deactivation_notice() {

		check_ajax_referer( 'deactivation-notice', 'security' );

		ob_start();
		global $status, $page, $s;
		$deactivate_url = wp_nonce_url( 'plugins.php?action=deactivate&amp;plugin=' . WPFDA_PLUGIN_FILE . '&amp;plugin_status=' . $status . '&amp;paged=' . $page . '&amp;s=' . $s, 'deactivate-plugin_' . WPFDA_PLUGIN_FILE );

		?>
			<!-- The Modal -->
			<div id="wp-frontend-delete-account-modal" class="wp-frontend-delete-account-modal">

				<!-- Modal content -->
				<div class="wp-frontend-delete-account-modal-content">
					<div class="wp-frontend-delete-account-modal-header">
					</div>

					<div class="wp-frontend-delete-account-modal-body">
						<div class="container">
							<form method="post" id="wp-frontend-delete-account-send-deactivation-email">

								<div class="row">
										<h3 for=""><?php echo esc_html__( 'Hey, would you care to provide a deactivation feedback? This is completely anonymous. ', 'wp-frontend-delete-account' ); ?></h3>
									<div class="col-75">
										<textarea id="message" name="message" placeholder="Deactivation Reason?" style="height:150px"></textarea>
									</div>
								</div>
								<div class="row">
										<?php wp_nonce_field( 'wpfda_deactivation_email', 'wpfda_deactivation_email' ); ?>
										<a href="<?php echo esc_url( $deactivate_url ); ?>"><?php echo esc_html__( 'Skip and deactivate', 'wp-frontend-delete-account' ); ?>
										<input type="submit" id="wpfda-send-deactivation-email" value="Deactivate">
								</div>
							</form>
						</div>

					<div class="wp-frontend-delete-account-modal-footer">
					</div>
				</div>
			</div>

		<?php

		$content = ob_get_clean();
		wp_send_json( $content );
		// WPCS: XSS OK.
	}

	/**
	 * Deactivation Email.
	 *
	 * @since  1.0.1
	 *
	 * Collecting feedback in site @since 1.4.0
	 *
	 * @return void
	 */
	public function deactivation_email() {

		check_ajax_referer( 'wpfda_deactivation_email', 'security' );

		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

		if ( ! empty( $message ) ) {

			$headers = array( 'Accept: application/json', 'Content-Type: application/json' );
			$args    = array(
				'method'  => 'POST',
				'headers' => $headers,
				'body'    => array(
					// Will do better one day!
					'deactivation_feedback_secret_key' => 'deactivation_feedback_secret_key',
					'message'                          => $message,
				),
			);

			$result = wp_remote_post( esc_url_raw( 'https://sanjeebaryal.com.np' ), $args );

			do_action( 'wp_frontend_delete_account_deactivation_feedback_sent', $result );
		}

		deactivate_plugins( WPFDA_PLUGIN_FILE );
	}

	/**
	 * Removes the admin notices on WP Frontend Delete Account settings page.
	 *
	 * @since 1.0.0
	 */
	public function remove_notices() { //phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		global $wp_filter;

		if ( ! isset( $_REQUEST['page'] ) || 'wp-frontend-delete-account' !== $_REQUEST['page'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		foreach ( array( 'user_admin_notices', 'admin_notices', 'all_admin_notices' ) as $wp_notice ) {
			if ( ! empty( $wp_filter[ $wp_notice ]->callbacks ) && is_array( $wp_filter[ $wp_notice ]->callbacks ) ) {
				foreach ( $wp_filter[ $wp_notice ]->callbacks as $priority => $hooks ) {
					foreach ( $hooks as $name => $arr ) {
						unset( $wp_filter[ $wp_notice ]->callbacks[ $priority ][ $name ] );
					}
				}
			}
		}
	}
}
