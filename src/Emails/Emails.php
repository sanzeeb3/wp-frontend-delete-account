<?php

namespace WPFrontendDeleteAccount\Emails;

/**
 * @since  1.2.0
 *
 * @todo : refactor whole class with all emails functionality including the email templates.
 *
 * @class Emails
 */
class Emails {

	/**
	 * Emails overview page.
	 *
	 * @since  1.2.0
	 *
	 * @return void.
	 */
	public static function overview() {

		foreach ( array( 'admin', 'user', 'feedback' ) as $key ) {

			$default = 'feedback' !== $key ? 'on' : 'off';

			$read_class[ $key ]   = 'on' === get_option( 'wpfda_enable_' . $key . '_email', $default ) ? 'wpfda-enable' : 'wpfda-disable';
			$tooltip_text[ $key ] = 'on' === get_option( 'wpfda_enable_' . $key . '_email', $default ) ? __( 'Disable this email', 'wp-frontend-delete-account' ) : __( 'Enable this email', 'wp-frontend-delete-account' );
			$icon[ $key ]         = '<span title="' . esc_attr( $tooltip_text[ $key ] ) . '" data-email="' . esc_attr( $key ) . '" class="wpfda-enable-disable dashicons dashicons-marker ' . esc_attr( $read_class[ $key ] ) . '">
							</span>';
		}

		?>
			<h2><?php esc_html_e( 'Email Notifications', 'wp-frontend-delete-account' ); ?></h2>
			<div id="email_notification_settings-description">
				<p><?php esc_html_e( 'Email notifications sent from WP Frontend Delete Account are listed below. Click on an email to configure it.', 'wp-frontend-delete-account' ); ?></p>
				<p><?php echo sprintf( esc_html__( 'If you are having issue with email delivery in your site, I recommend setting up SMTP in your site using SMTP plugins such as %1s.', 'wp-frontend-delete-account' ), '<a href="https://wordpress.org/plugins/wp-mail-smtp/" target="_blank"><strong>WP Mail SMTP</strong></a>' ); ?></p>
			</div>
		<tr valign="top">
		<td class="wc_emails_wrapper" colspan="3">
			<table style="width:75%" class="wc_emails widefat" cellspacing="0">
				<thead>
					<tr>
						<?php
						$columns = apply_filters(
							'wp_frontend_delete_account_email_setting_columns',
							array(
								'status'    => '',
								'name'      => esc_html__( 'Email', 'wp-frontend-delete-account' ),
								'recipient' => esc_html__( 'Recipient(s)', 'wp-frontend-delete-account' ),
								'actions'   => esc_html__( 'Actions', 'wp-frontend-delete-account' ),
							)
						);
						foreach ( $columns as $key => $column ) {
							echo '<th class="wc-email-settings-table-' . esc_attr( $key ) . '">' . esc_html( $column ) . '</th>';
						}
						?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="email-status"><?php echo $icon['admin']; ?> </td>
							<td class="email-name"><?php esc_html_e( 'Admin Email', 'wp-frontend-delete-account' ); ?> </td>
							<td class="email-receipent"><?php echo get_option( 'wpfda_email_receipent', get_option( 'admin_email' ) ); ?> </td>
							<td class="wc-email-settings-table-actions">
								<a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=wp-frontend-delete-account&section=emails&email=admin' ), 'wp-frontend-delete-account-emails' ) ); ?>"><?php esc_html_e( 'Manage', 'wp-frontend-delete-account' ); ?></a>
							</td>
						</tr>

						<tr>
							<td class="email-status"><?php echo $icon['user']; ?> </td>
							<td class="email-name"><?php esc_html_e( 'User Email', 'wp-frontend-delete-account' ); ?> </td>
							<td class="email-receipent"> <?php esc_html_e( 'User\'s Email Address', 'wp-frontend-delete-account' ); ?> </td>
							<td class="wc-email-settings-table-actions">
								<a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=wp-frontend-delete-account&section=emails&email=user' ), 'wp-frontend-delete-account-emails' ) ); ?>"><?php esc_html_e( 'Manage', 'wp-frontend-delete-account' ); ?></a>
							</td>
						</tr>

						<tr>
							<td class="email-status"><?php echo $icon['feedback']; ?> </td>
							<td class="email-name"><?php esc_html_e( 'Feedback Email', 'wp-frontend-delete-account' ); ?> </td>
							<td class="email-receipent"><?php echo get_option( 'wpfda_feedback_email_receipent', get_option( 'admin_email' ) ); ?> </td>
							<td class="wc-email-settings-table-actions">
								<a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=wp-frontend-delete-account&section=emails&email=feedback' ), 'wp-frontend-delete-account-emails' ) ); ?>"><?php esc_html_e( 'Manage', 'wp-frontend-delete-account' ); ?></a>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>

		<?php

			if ( defined( 'WC_VERSION' ) ) {
				echo '<br/>';
				echo sprintf( esc_html__( 'The email sender options, email templates etc. can be customized from %1s.', 'wp-frontend-delete-account' ), '<a href="'. esc_url( admin_url( 'admin.php?page=wc-settings&tab=email' ) ) .'">WooCommerce</a>' );
			}
	}

	/**
	 * Emails section.
	 *
	 * @since  1.2.0
	 *
	 * @return void.
	 */
	public static function emails() {

		$email       = sanitize_text_field( $_GET['email'] );
		$default     = 'feedback' !== $email ? 'on' : 'off';
		$option_name = 'admin' === $email ? 'wpfda_email_receipent' : 'wpfda_feedback_email_receipent';

		$enable    = get_option( 'wpfda_enable_' . $email . '_email', $default );
		$recipient = get_option( $option_name, get_option( 'admin_email' ) );

		switch ( $email ) {
			case 'admin':
				$default_subject = esc_html__( 'Heads up! A user deleted their account.', 'wp-frontend-delete-account' );
				break;
			case 'user':
				$default_subject = esc_html__( 'Your account has been deleted successfully.', 'wp-frontend-delete-account' );
				break;
			case 'feedback':
				$default_subject = esc_html__( 'A user - {user_email} provided a feedback on account deletion.', 'wp-frontend-delete-account' );
				break;
		}

		$default_message = 'admin' === $email ? esc_html__( 'A user {user_name} - {user_email} has deleted their account.', 'wp-frontend-delete-account' ) : esc_html__( 'Your account has been deleted. In case this is a mistake, please contact the site administrator at ' . site_url() . '', 'wp-frontend-delete-account' );
		$subject         = get_option( 'wpfda_' . $email . '_email_subject', $default_subject );
		$message         = get_option( 'wpfda_' . $email . '_email_message', $default_message );

		?>
		  <h2 class="wp-heading-inline">
		  <?php
			switch ( $email ) {
				case 'admin':
					esc_html_e( 'Admin Email', 'wp-frontend-delete-account' );
					break;
				case 'user':
					esc_html_e( 'User Email', 'wp-frontend-delete-account' );
					break;
				case 'feedback':
					esc_html_e( 'Feedback Email', 'wp-frontend-delete-account' );
					break;

			}
			?>
			 <a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=wp-frontend-delete-account&section=emails' ), 'wp-frontend-delete-account-emails' ); ?> "> ⤴ </a>
			</h2>			  <div id="email_notification_settings-description">

				<?php if ( 'user' === $email ) : ?>
					<p><?php esc_html_e( 'Email notification sent to the user after their account has been deleted.', 'wp-frontend-delete-account' ); ?></p>
				<?php endif; ?>

				<?php if ( 'admin' === $email ) : ?>
					<p><?php esc_html_e( 'Email notification sent to the admin if user deleted their account by their own.', 'wp-frontend-delete-account' ); ?></p>
				<?php endif; ?>

				<?php if ( 'feedback' === $email ) : ?>
					<p><?php esc_html_e( 'Email notification sent to the admin if user leaves a feedback upon deleting their account.', 'wp-frontend-delete-account' ); ?></p>
				<?php endif; ?>
			</div>

		<hr class="wp-header-end">
		<hr/>

		<form method="post">
			<table class="form-table">
				<tr valign="top" class="wp-frontend-delete-account-enable-this-email">
					<th scope="row"><?php echo esc_html__( 'Enable this email', 'wp-frontend-delete-account' ); ?></th>
						<td>
							<input type="hidden" name="wpfda_enable_<?php echo $email; ?>_email" value="off" />
							<input style="width:auto" type="checkbox" name="wpfda_enable_<?php echo $email; ?>_email" class="wp-frontend-delete-account-enable-<?php echo $email; ?>-email-inline" <?php checked( 'on', $enable ); ?> />
						</td>
				</tr>

				<?php if ( 'admin' === $email || 'feedback' === $email ) : ?>
					<?php $name = 'admin' === $email ? 'wpfda_email_receipent' : 'wpfda_feedback_email_receipent'; ?>
					<tr valign="top">
					<th scope="row"><?php echo esc_html__( 'Email Recipient', 'wp-frontend-delete-account' ); ?></th>
						<td><input style="width:50%" type="text" name="<?php echo $name; ?>" value ="<?php echo esc_html( $recipient ); ?>" class="wp-frontend-delete-account-receipent" />
						</td>
				</tr>
			<?php endif; ?>

				<tr valign="top">
					<th scope="row"><?php echo esc_html__( 'Email Subject', 'wp-frontend-delete-account' ); ?></th>
						<td><input style="width:50%" type="text" name="wpfda_<?php echo $email; ?>_email_subject" value ="<?php echo esc_html( $subject ); ?>" class="wp-frontend-delete-account-<?php echo $email; ?>-email-subject" />
						</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php echo esc_html__( 'Email Message', 'wp-frontend-delete-account' ); ?></th>
							<td>

								<?php

								if ( 'feedback' !== $email ) {

									$editor_id = 'wpfda_' . $email . '_email_message';
									$args      = array(
										'media_buttons' => false,
									);

									wp_editor( $message, $editor_id, $args );
								} else {
									echo '<i>' . esc_html__( 'Feedback provided by the user.', 'wp-frontend-delete-account' ) . '</i> 😀';
								}
								?>

							</td>

						<style>
							#wp-wpfda_admin_email_message-wrap, #wp-wpfda_user_email_message-wrap {
								width: 80%;
							}
						</style>
				</tr>
				<?php do_action( 'wp_frontend_delete_account_email_settings' ); ?>
				<?php wp_nonce_field( 'wp_frontend_delete_account_settings', 'wp_frontend_delete_account_settings_nonce' ); ?>
			</table>

			<?php submit_button(); ?>

		</form>
		<?php
	}
}
