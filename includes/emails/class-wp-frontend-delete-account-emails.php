<?php
/**
 * @since  1.2.0
 *
 * Class for Emails.
 *
 * @class WPFDA_Emails
 */
class WPFDA_Emails {

	/**
	 * Emails overview page.
	 *
	 * @since  1.2.0
	 *
	 * @return void.
	 */
	public static function overview() {
		?>
			<h2 class="wp-heading-inline"><?php esc_html_e( 'Email Notifications', 'wp-frontend-delete-account' ); ?></h2>
			<div id="email_notification_settings-description">
				<p><?php esc_html_e( 'Email notifications sent from WP Frontend Delete Account are listed below. Click on an email to configure it.', 'wp-frontend-delete-account'); ?></p>
				<p><?php esc_html_e( '** IMPORTANT: If you are having issue with email delivery, I recommend setting up SMTP in your site using SMTP plugins such as WP Mail SMTP.', 'wp-frontend-delete-account' ); ?></p>
			</div>
			<hr class="wp-header-end">
			<hr/>
		<?php
	}
}
