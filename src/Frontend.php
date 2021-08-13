<?php

namespace WPFrontendDeleteAccount;

/**
 * Class for frontend tasks.
 *
 *  @since  1.0.0
 *
 * @since  1.2.0 changed classname "WPFDA_Frontend" to "Frontend" with namespace.
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
		add_action( 'wp_ajax_nopriv_delete_feedback', array( $this, 'delete_feedback' ) );
		add_action( 'wp_ajax_nopriv_delete_feedback_email', array( $this, 'delete_feedback_email' ) );
		add_shortcode( 'wp_frontend_delete_account', 'wpf_delete_account_content' );
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

			$security = get_option( 'wpfda_security', 'password' );

			wp_enqueue_script( 'wpfda-delete-account-frontend', plugins_url( 'assets/js/frontend.min.js', WPFDA_PLUGIN_FILE ), array( 'wp-element', 'wp-i18n' ), WPFDA_VERSION, false );
			wp_enqueue_style( 'wpfda-frontend-css', plugins_url( 'assets/css/frontend.css', WPFDA_PLUGIN_FILE ), array(), WPFDA_VERSION, false );

			wp_localize_script(
				'wpfda-delete-account-frontend',
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
		}//end if
	}

	/**
	 * Perform Delete Action.
	 *
	 * @since  1.0.0
	 *
	 * @return Void.
	 */
	public function confirm_delete() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		if ( isset( $_POST['security'] ) ) {
			if ( ! wp_verify_nonce( wp_unslash( $_POST['security'] ), 'wpfda_nonce' ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				return;
			}

			$security       = get_option( 'wpfda_security', 'password' );
			$captcha_answer = get_option( 'wpfda_security_custom_captcha_answer', 'PERMANENTLY DELETE' );

			if ( 'password' === $security || 'recaptcha_v2' === $security ) {
				// Backwards compatibility. Removing reCAPTCHA support since 1.1.0.
				$user_id = get_current_user_id();
				$user    = get_user_by( 'id', $user_id );
				$pass    = isset( $_POST['value'] ) ? wp_unslash( $_POST['value'] ) : ''; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

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
				$value = sanitize_text_field( wp_unslash( $_POST['value'] ) );

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
			}//end if
		}//end if
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
		$deleted = wp_delete_user( $user_id, $attribute );

		if ( $deleted ) {
			$this->send_emails( $user );
			$this->delete_comments( $user_id );
		}

		do_action( 'wp_frontend_delete_account_process_complete', $user );

		wp_send_json(
			array(
				'success' => true,
				'message' => esc_html__( 'Deleting...', 'wp-frontend-delete-account' ),
			)
		);
	}

	/**
	 * Delete user's comments if the settings is enabled.
	 *
	 * @param int $user_id User ID.
	 *
	 * @since  1.5.3
	 */
	public function delete_comments( $user_id ) {

		if ( 'on' === get_option( 'wpfda_delete_comments' ) ) {
			include_once ABSPATH . 'wp-admin/includes/comment.php';

			$comments = get_comments( array( 'user_id' => $user_id ) );

			foreach ( $comments as $comment ) {

				// Delete comments.
				wp_delete_comment( $comment, true );
			}
		}
	}

	/**
	 * Send emails to admin and user.
	 *
	 * @param obj $user An instance of user.
	 *
	 * @since  1.0.0
	 *
	 * @return void.
	 */
	public function send_emails( $user ) {

		$header = array( 'Content-Type: text/html; charset=UTF-8' );

		// Send email to admin.
		if ( 'on' === get_option( 'wpfda_enable_admin_email', 'on' ) ) {
			$to      = get_option( 'wpfda_email_receipent', get_option( 'admin_email' ) );
			$subject = get_option( 'wpfda_admin_email_subject', 'Heads up! A user deleted their account.' );
			$message = get_option( 'wpfda_admin_email_message', 'A user {user_name} - {user_email} has deleted their account.' );

			$message = str_replace( '{user_name}', $user->data->user_login, $message );
			$message = nl2br( str_replace( '{user_email}', $user->data->user_email, $message ) );

			$sent = $this->now_send( $to, $subject, $message, $header );

			do_action( 'wp_frontend_delete_account_admin_email_sent', $sent );
		}

		// Send email to user.
		if ( 'on' === get_option( 'wpfda_enable_user_email', 'on' ) ) {
			$subject = get_option( 'wpfda_user_email_subject', 'Your account has been deleted successfully.' );
			$message = nl2br( get_option( 'wpfda_user_email_message', 'Your account has been deleted. In case this is a mistake, please contact the site administrator at ' . site_url() . '' ) );

			$sent = $this->now_send( $user->data->user_email, $subject, $message, $header );

			do_action( 'wp_frontend_delete_account_admin_email_sent', $sent );
		}
	}

	/**
	 * Actually send emails.
	 *
	 * Using WooCommerce sending options and templates for sites using WooCommerce.
	 *
	 * @param string $to      Receipent's email address.
	 * @param string $subject Subject of the email.
	 * @param string $message Email content.
	 * @param array  $header  Email header.
	 *
	 * @since  1.5.0
	 */
	private function now_send( $to, $subject, $message, $header = array( 'Content-Type: text/html; charset=UTF-8' ) ) {

		if ( defined( 'WC_VERSION' ) ) {

			// Get woocommerce mailer from instance.
			$mailer = \WC()->mailer();

			// Header Title.
			$heading = \get_bloginfo( 'name' );

			// Wrap message using woocommerce html email template.
			$wrapped_message = $mailer->wrap_message( $heading, $message );

			$wc_email = new \WC_Email();

			// Style the wrapped message with woocommerce inline styles.
			$message = $wc_email->style_inline( $wrapped_message );

			$sent = $mailer->send( $to, $subject, $message );

		} else {

			$sent = \wp_mail( $to, $subject, $message, $header );
		}//end if
	}

	/**
	 * Render popup feedback form on account deletion.
	 *
	 *  @since  1.4.0
	 */
	public static function delete_feedback() {

		ob_start();

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
										<h3 for=""><?php echo apply_filters( 'wp_frontend_delete_account_delete_feedback_label', esc_html__( 'Hey, would you care to provide feedback on your account deletion?', 'wp-frontend-delete-account' ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
									<div class="col-75">
										<textarea id="message" name="message" placeholder="<?php echo apply_filters( 'wp_frontend_delete_account_delete_feedback_placeholder', esc_html__( 'Account deletion reason?', 'wp-frontend-delete-account' ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" style="height:150px"></textarea>
									</div>
								</div>
								<div class="row">
										<?php wp_nonce_field( 'wpfda_delete_feedback_email', 'wpfda_delete_feedback_email' ); ?>
										<a href=""><?php echo esc_html__( 'Skip and delete', 'wp-frontend-delete-account' ); ?>
										<input type="submit" id="wpfda-send-deactivation-email" value="<?php echo esc_html__( 'Delete Account', 'wp-frontend-delete-account' ); ?> ">
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
	 * Feedback from the popup form.
	 *
	 * @since  1.4.0
	 *
	 * @return void
	 */
	public function delete_feedback_email() {

		if ( ! isset( $_POST['security'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( wp_unslash( $_POST['security'] ), 'wpfda_delete_feedback_email' ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		if ( empty( $_POST['message'] ) ) {
			return;
		}

		$message    = sanitize_textarea_field( wp_unslash( $_POST['message'] ) );
		$user_email = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';

		$default_subject = esc_html__( 'A user - {user_email} provided a feedback on account deletion.', 'wp-frontend-delete-account' );
		$subject         = get_option( 'wpfda_feedback_email_subject', $default_subject );
		$subject         = str_replace( '{user_email}', $user_email, $subject );

		$sent = $this->now_send( get_option( 'wpfda_feedback_email_receipent', get_option( 'admin_email' ) ), $subject, $message );

		do_action( 'wp_frontend_delete_account_feedback_email_sent', $sent );
	}
}
