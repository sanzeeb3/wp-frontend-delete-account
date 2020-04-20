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
		<tr valign="top">
		<td class="wc_emails_wrapper" colspan="3">
			<table style="width:75%" class="wc_emails widefat" cellspacing="0">
				<thead>
					<tr>
						<?php
						$columns = apply_filters(
							'wp_frontend_delete_account_email_setting_columns',
							array(
								'status'     => '',
								'name'       => __( 'Email', 'woocommerce' ),
								'recipient'  => __( 'Recipient(s)', 'woocommerce' ),
								'actions'    => __( 'Actions', 'woocommerce' ),
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
							<td class="email-status">ON</td>
							<td class="email-name"><?php esc_html_e( 'Admin Email', 'wp-frontend-delete-account' );?> </td>
							<td class="email-receipent"><?php echo get_option( 'admin_email' ); ?> </td>
							<td class="wc-email-settings-table-actions">
								<a class="button" href=""><?php esc_html_e( 'Manage', 'wp-frontend-delete-account') ;?></a>
							</td>
						</tr>

						<tr>
							<td class="email-status">OFF</td>
							<td class="email-name"><?php esc_html_e( 'User Email', 'wp-frontend-delete-account' );?> </td>
							<td class="email-receipent"> <?php esc_html_e( 'User\'s Email Address', 'wp-frontend-delete-account' );?> </td>
							<td class="wc-email-settings-table-actions">
								<a class="button" href=""><?php esc_html_e( 'Manage', 'wp-frontend-delete-account') ;?></a>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<?php
	}
}
