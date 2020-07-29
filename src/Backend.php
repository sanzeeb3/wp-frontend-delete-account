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

		if ( 'plugins.php' === $pagenow || 'settings_page_WP Frontend Delete Account' === $current_screen->id ) {

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
					'deactivating'       => __( 'Deactivating...', 'wp-frontend-delete-account' ),
					'wrong'              => __( 'Oops! Something went wrong', 'wp-frontend-delete-account' ),
					'enable_email'       => __( 'Enable this email', 'wp-frontend-delete-account' ),
					'disable_email'      => __( 'Disable this email', 'wp-frontend-delete-account' ),
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
		add_options_page( 'WP Frontend Delete Account Settings', 'WP Frontend Delete Account', 'manage_options', 'WP Frontend Delete Account', array( $this, 'wpfda_settings_page' ) );
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
			<a href="' . esc_url( admin_url( 'admin.php?page=WP+Frontend+Delete+Account' ) ) . '" class="nav-tab ' . $general_active . '">' . esc_html__( 'General', 'wp-frontend-delete-account' ) . '</a>
			<a href="' . esc_url( wp_nonce_url( admin_url( 'admin.php?page=WP+Frontend+Delete+Account&section=emails' ), 'wp-frontend-delete-account-emails' ) ) . '" class="nav-tab ' . $emails_active . '">' . esc_html__( 'Emails', 'wp-frontend-delete-account' ) . '</a>
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
		$captcha_question = get_option( 'wpfda_security_custom_captcha_question', 'What is 11*3?' );
		$captcha_answer   = get_option( 'wpfda_security_custom_captcha_answer', '33' );
		$load_assets      = get_option( 'wpfda_load_assets_globally' );
		$wc_data 	      = get_option( 'wpfda_wc_data' );
		$users            = get_users();
		$wc_defined 		  = defined( 'WC_VERSION' );

		?>
		<h2 class="wp-heading-inline"><?php esc_html_e( 'General Settings', 'wp-frontend-delete-account' ); ?></h2>
		<hr class="wp-header-end">
		<hr/>

		<form method="post">
			<table class="form-table">

				<tr valign="top" class="wp-frontend-delete-account-load-assets-globally">
					<th scope="row"><?php echo esc_html__( 'Load Assets Globally', 'wp-frontend-delete-account' ); ?></th>
						<td>
							<input type="hidden" name="wpfda_load_assets_globally" value="off" />
							<input style="width:auto" type="checkbox" name="wpfda_load_assets_globally" class="wp-frontend-delete-account-load-assets-globally-inline" <?php checked( 'on', $load_assets ); ?> />
							<i><?php echo esc_html__( 'Check this only if you are having any compatiblitiy issues.', 'wp-frontend-delete-account' ); ?> </i>
						</td>
				</tr>

				<tr valign="top" class="wp-frontend-delete-account-wc-data">
					<th scope="row"><?php echo esc_html__( 'Remove WooCommerce Personal data', 'wp-frontend-delete-account' ); ?></th>
						<td>
							<input type="hidden" name="wpfda_wc_data" value="off" />
							<input style="width:auto" type="checkbox" name="wpfda_wc_data" class="wp-frontend-delete-account-wc-data-inline" <?php echo ! $wc_defined ? 'disabled': '' ;?> <?php checked( 'on', $wc_data ); ?> />
							<i><?php echo esc_html__( 'This option removes all personal data of the deleted user from WooCommerce orders, download data, user token etc.', 'wp-frontend-delete-account' ); ?> </i>
							<?php if ( ! $wc_defined ) {
									?> <br/><br/><i style="color:grey;"> <?php echo esc_html( 'WooCommerce is not installed on your site, currently.' ); ?> </i> <?php
								}
							?>
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

			$options = array( 'wpfda_title', 'wpfda_button_label', 'wpfda_attribute', 'wpfda_security', 'wpfda_security_password_text', 'wpfda_security_custom_captcha_question', 'wpfda_security_custom_captcha_answer', 'wpfda_load_assets_globally', 'wpfda_email_receipent', 'wpfda_enable_user_email', 'wpfda_enable_admin_email', 'wpfda_user_email_subject', 'wpfda_admin_email_subject', 'wpfda_user_email_message', 'wpfda_admin_email_message' );
			foreach ( $options as $option ) {
				if ( isset( $_POST[ $option ] ) ) {
					$value = sanitize_text_field( $_POST[ $option ] );
					update_option( $option, $value );
				}
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
										<h3 for=""><?php echo __( 'Would you care to let me know the deactivation reason so that I can improve it for you?', 'wp-frontend-delete-account' ); ?></h3>
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
	 * @return void
	 */
	public function deactivation_email() {

		check_ajax_referer( 'wpfda_deactivation_email', 'security' );

		$message = sanitize_textarea_field( $_POST['message'] );

		if ( ! empty( $message ) ) {
			wp_mail( 'sanzeeb.aryal@gmail.com', 'WP Frontend Delete Account Deactivation', $message );
		}

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
