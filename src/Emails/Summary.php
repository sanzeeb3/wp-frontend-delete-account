<?php

namespace WPFrontendDeleteAccount\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Class for weekly summary emails.
 *
 * @since 1.5.8
 */
class Summary {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_filter( 'wp_frontend_delete_account_emails', [ $this, 'add_summary_email' ] ); //phpcs:ignore Generic.Arrays.DisallowShortArraySyntax.Found
	}

	/**
	 * Add Summary Email and it's options.
	 *
	 * @param array $emails An array of emails and their options.
	 *
	 * @since 1.5.8
	 */
	public function add_summary_email( $emails ) {

		$summary_message = 'Oh, hi there, <br><br><h1>{number}</h1><br/><br/>users deleted their account this past week. Previous week: {previous_number}';

		$emails['summary'] = array(
			'enable'    => get_option( 'wpfda_enable_summary_email', 'off' ),
			'label'     => esc_html__( 'Weekly Summary Email', 'wp-frontend-delete-account' ),
			'desc'      => esc_html__( 'Email notification sent to the admin about the total number of users deleted in a week.', 'wp-frontend-delete-account' ),
			'receipent' => get_option( 'wpfda_summary_email_receipent', get_option( 'admin_email' ) ),
			'subject'   => get_option( 'wpfda_summary_email_subject', esc_html__( 'WP Frontend Delete Account Email Summary', 'wp-frontend-delete-account' ) ),
			'message'   => get_option( 'wpfda_summary_email_message', $summary_message ),
		);

		return $emails;
	}
}
