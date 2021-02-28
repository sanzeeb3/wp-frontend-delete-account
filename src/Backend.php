<?php

namespace WPFrontendDeleteAccount;

use WPFrontendDeleteAccount\Emails\Emails;

/**
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
		add_action( 'in_admin_header', array( $this, 'review_notice' ) );
		add_action( 'wp_ajax_wp_frontend_delete_account_dismiss_review_notice', array( $this, 'dismiss_review_notice' ) );
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

		if ( 'plugins.php' === $pagenow || 'settings_page_wp-frontend-delete-account' === $current_screen->id ) {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'wpfda-backend', plugins_url( 'assets/css/backend.css', WPFDA_PLUGIN_FILE ), array(), WPFDA_VERSION, $media = 'all' );
			wp_enqueue_script( 'wpf-delete-account-js', plugins_url( 'assets/js/admin/backend' . $suffix . '.js', WPFDA_PLUGIN_FILE ), array(), WPFDA_VERSION, false );
			wp_localize_script(
				'wpf-delete-account-js',
				'wpfda_plugins_params',
				array(
					'ajax_url'           => admin_url( 'admin-ajax.php' ),
					'deactivation_nonce' => wp_create_nonce( 'deactivation-notice' ),
					'status_nonce'       => wp_create_nonce( 'email-status' ),
					'review_nonce'       => wp_create_nonce( 'review-notice' ),
					'deactivating'       => __( 'Deactivating...', 'wp-frontend-delete-account' ),
					'wrong'              => esc_html__( 'Oops! Something went wrong', 'wp-frontend-delete-account' ),
					'enable_email'       => esc_html__( 'Enable this email', 'wp-frontend-delete-account' ),
					'disable_email'      => esc_html__( 'Disable this email', 'wp-frontend-delete-account' ),
				)
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
		$redirect_url     = get_option( 'wpfda_redirect_url' );
		$users            = get_users();

		?>
		<h2><?php esc_html_e( 'General Settings', 'wp-frontend-delete-account' ); ?></h2>

		<div id="wp-frontend-delete-account-settings-page">
			<div class="wp-frontend-delete-account-settings">
				<form  method="post">
					<table class="form-table">

						<tr valign="top" class="wp-frontend-delete-account-load-assets-globally">
							<th scope="row"><?php echo esc_html__( 'Load Assets Globally', 'wp-frontend-delete-account' ); ?></th>
								<td>
									<input type="hidden" name="wpfda_load_assets_globally" value="off" />
									<input style="width:auto" type="checkbox" name="wpfda_load_assets_globally" class="wp-frontend-delete-account-load-assets-globally-inline" <?php checked( 'on', $load_assets ); ?> />
								</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php echo esc_html__( 'Title', 'wp-frontend-delete-account' ); ?></th>
								<td><input type="text" name="wpfda_title" value ="<?php echo esc_html( $title ); ?>" class="wp-frontend-delete-account-title" />
								</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php echo esc_html__( 'Button Label', 'wp-frontend-delete-account' ); ?></th>
								<td><input type="text" name="wpfda_button_label" value ="<?php echo esc_html( $button ); ?>" class="wp-frontend-delete-account-button-label" />
								</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php echo esc_html__( 'Redirect URL', 'wp-frontend-delete-account' ); ?></th>
								<td><input type="url" name="wpfda_redirect_url" value ="<?php echo esc_html( $redirect_url ); ?>" class="wp-frontend-delete-account-redirect-url" /><br/>
								<i><?php echo esc_html__( 'Leave empty for same page redirect.' ); ?></i>
								</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php echo esc_html__( 'Attribute all contents to:', 'wp-frontend-delete-account' ); ?></th>
								<td>
									<select style="width:17%;" name="wpfda_attribute">
										<option><?php echo esc_html__( '--None--', 'wp-frontend-delete-account' ); ?></option>
										<?php
										foreach ( $users as $user ) {
												echo '<option value="' . absint( $user->ID ) . '" ' . selected( $user->ID, $attribute, true ) . '>' . $user->data->user_login . '</option>';
										}
										?>
									</select>
								</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php echo esc_html__( 'Security method before deleting:', 'wp-frontend-delete-account' ); ?></th>
								<td>
									<select style="width:17%;" name="wpfda_security" class="wpfda-security">
										<option value="none" <?php selected( 'none', $security, true ); ?>><?php echo esc_html__( '--None--', 'wp-frontend-delete-account' ); ?></option>
										<option value="password" <?php selected( 'password', $security, true ); ?>><?php echo esc_html__( 'Password', 'wp-frontend-delete-account' ); ?></option>
										<option value="custom_captcha" <?php selected( 'custom_captcha', $security, true ); ?>><?php echo esc_html__( 'Custom Captcha', 'wp-frontend-delete-account' ); ?></option>
									</select><br/>
								</td>
						</tr>
						<tr valign="top" class="wp-frontend-delete-account-security-password">
							<th scope="row"><?php echo esc_html__( 'Confirmation Text', 'wp-frontend-delete-account' ); ?></th>
								<td>
									<input style="width:50%" type="text" name="wpfda_security_password_text" value ="<?php echo esc_html( $password_text ); ?>" class="wp-frontend-delete-account-security-password-inline" />
								</td>
						</tr>

						<tr valign="top" class="wp-frontend-delete-account-security-custom-captcha-question">
							<th scope="row"><?php echo esc_html__( 'Captcha Question', 'wp-frontend-delete-account' ); ?></th>
								<td>
									<input style="width:50%" type="text" name="wpfda_security_custom_captcha_question" value ="<?php echo esc_html( $captcha_question ); ?>" class="wp-frontend-delete-account-security-custom-captcha-question-inline" />
								</td>
						</tr>

						<tr valign="top" class="wp-frontend-delete-account-security-custom-captcha-answer">
							<th scope="row"><?php echo esc_html__( 'Captcha Answer', 'wp-frontend-delete-account' ); ?></th>
								<td>
									<input style="width:50%" type="text" name="wpfda_security_custom_captcha_answer" value ="<?php echo esc_html( $captcha_answer ); ?>" class="wp-frontend-delete-account-security-custom-captcha-answer-inline" />
								</td>
						</tr>

						<?php do_action( 'wp_frontend_delete_account_settings' ); ?>
						<?php wp_nonce_field( 'wp_frontend_delete_account_settings', 'wp_frontend_delete_account_settings_nonce' ); ?>

					</table>

					<?php submit_button(); ?>

				</form>
			</div>

			<div class="wp-frontend-delete-account-recommended-plugins">
				<a class="wpfda-recommended-plugins-dismiss" href="#">Dismiss</a>
				<?php include_once WPFDA_PLUGIN_DIR . '/recommended-plugins.php'; ?>
			</div>
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
	public function save_settings() {

		if ( isset( $_POST['wp_frontend_delete_account_settings_nonce'] ) ) {

			if ( ! wp_verify_nonce( $_POST['wp_frontend_delete_account_settings_nonce'], 'wp_frontend_delete_account_settings' )
				) {
				   print 'Nonce Failed!';
				   exit;
			}

			$options = array( 'wpfda_title', 'wpfda_button_label', 'wpfda_redirect_url', 'wpfda_attribute', 'wpfda_security', 'wpfda_security_password_text', 'wpfda_security_custom_captcha_question', 'wpfda_security_custom_captcha_answer', 'wpfda_load_assets_globally', 'wpfda_email_receipent', 'wpfda_feedback_email_receipent', 'wpfda_enable_user_email', 'wpfda_enable_admin_email', 'wpfda_user_email_subject', 'wpfda_admin_email_subject', 'wpfda_enable_feedback_email', 'wpfda_feedback_email_subject' );

			foreach ( $options as $option ) {
				if ( isset( $_POST[ $option ] ) ) {
					$value = sanitize_text_field( $_POST[ $option ] );
					update_option( $option, $value );
				}
			}

			$email = isset( $_GET['email'] ) ? sanitize_text_field( $_GET['email'] ) : '';

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
			}
		}
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
										<h3 for=""><?php echo __( 'Hey, would you care to provide a deactivation feedback? This is completely anonymous. ', 'wp-frontend-delete-account' ); ?></h3>
									<div class="col-75">
										<textarea id="message" name="message" placeholder="Deactivation Reason?" style="height:150px"></textarea>
									</div>
								</div>
								<div class="row">
										<?php wp_nonce_field( 'wpfda_deactivation_email', 'wpfda_deactivation_email' ); ?>
										<a href="<?php echo $deactivate_url; ?>"><?php echo __( 'Skip and deactivate', 'wp-frontend-delete-account' ); ?>
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
		wp_send_json( $content ); // WPCS: XSS OK.
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

		$message = sanitize_textarea_field( $_POST['message'] );

		$headers = array( 'Accept: application/json', 'Content-Type: application/json' );
		$args    = array(
			'method'  => 'POST',
			'headers' => $headers,
			'body'    => array(
				'deactivation_feedback_secret_key' => 'deactivation_feedback_secret_key',    // Will do better one day!
				'message'                          => $message,
			),
		);

		$result = wp_remote_post( esc_url_raw( 'https://sanjeebaryal.com.np' ), $args );

		do_action( 'wp_frontend_delete_account_deactivation_feedback_sent', $result );

		deactivate_plugins( WPFDA_PLUGIN_FILE );
	}

	/**
	 * Email status change from emails overview.
	 *
	 * @since  1.2.0
	 *
	 * @return
	 */
	public function email_status() {

		check_admin_referer( 'email-status', 'security' );

		$email  = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
		$enable = ! empty( $_POST['enable'] ) ? 'on' : 'off';

		update_option( 'wpfda_enable_' . $email . '_email', $enable );
	}

	/**
	 * Outputs the Review notice on admin header.
	 *
	 * @since 1.3.2
	 */
	function review_notice() {

		global $current_screen;

		// Show only to Admins
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$notice_dismissed = get_option( 'wpfda_review_notice_dismissed', 'no' );

		if ( 'yes' == $notice_dismissed ) {
			return;
		}

		if ( ! empty( $current_screen->id ) && $current_screen->id !== 'settings_page_wp-frontend-delete-account' ) {
			return;
		}

		?>
			<div id="wp-frontend-delete-account-review-notice" class="notice notice-info wp-frontend-delete-account-review-notice">
				<div class="wp-frontend-delete-account-review-thumbnail">
					<img src="<?php echo plugins_url( 'assets/logo.png', WPFDA_PLUGIN_FILE ); ?>" alt="">
				</div>
				<div class="wp-frontend-delete-account-review-text">

						<h3><?php _e( 'Whoopee! 😀', 'wp-frontend-delete-account' ); ?></h3>
						<p><?php _e( 'How\'s it going? I hope that you\'ve found WP Frontend Delete Account helpful. Would you do me some favour and leave a <a href="https://wordpress.org/support/plugin/wp-frontend-delete-account/reviews/?filter=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> review on <a href="https://wordpress.org/support/plugin/wp-frontend-delete-account/reviews/?filter=5#new-post" target="_blank"><strong>WordPress.org</strong></a> to help us spread the word and boost my motivation?', 'wp-frontend-delete-account' ); ?></p>

					<ul class="wp-frontend-delete-account-review-ul">
						<li><a class="button button-primary" href="https://wordpress.org/support/plugin/wp-frontend-delete-account/reviews/?filter=5#new-post" target="_blank"><span class="dashicons dashicons-external"></span><?php _e( 'Sure, I\'d love to!', 'wp-frontend-delete-account' ); ?></a></li>
						<li><a class="button button-link" target="_blank" href="http://sanjeebaryal.com.np/contact"><span class="dashicons dashicons-sos"></span><?php _e( 'I need help!', 'wp-frontend-delete-account' ); ?></a></li>
						<li><a href="#" class="button button-link notice-dismiss"><span class="dashicons dashicons-dismiss"></span><?php _e( 'Dismiss Forever.', 'wp-frontend-delete-account' ); ?></a></li>
					 </ul>
				</div>
			</div>
		<?php
	}

	/**
	 * Dismiss the reveiw notice on dissmiss click
	 *
	 * @since 1.3.2
	 */
	function dismiss_review_notice() {

		check_admin_referer( 'review-notice', 'security' );

		if ( ! empty( $_POST['dismissed'] ) ) {
			update_option( 'wpfda_review_notice_dismissed', 'yes' );
		}
	}

	/**
	 * Removes the admin notices on WP Frontend Delete Account settings page.
	 *
	 * @since 1.0.0
	 */
	public function remove_notices() {

		global $wp_filter;

		if ( ! isset( $_REQUEST['page'] ) || 'WP Frontend Delete Account' !== $_REQUEST['page'] ) {
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
