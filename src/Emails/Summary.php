<?php
/**
 * Action Scheduler: https://actionscheduler.org/api/
 *
 * The weekely summary email is sent using Action Scheduler.
 */

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

		if ( ! class_exists( 'ActionScheduler' ) ) {
			return;
		}

		add_filter( 'wp_frontend_delete_account_emails', [ $this, 'add_summary_email' ] );
		add_action( 'init', [ $this, 'schedule_summary_email'] );
		add_action( 'as_next_scheduled_action', [ $this, 'initiate_email_sending' ] );
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

	/**
	 * Schedule weekly summary email.
	 *
	 * @since 1.5.8
	 *
	 * @return integer|void The actions'd ID or void.
	 */
	public function schedule_summary_email() {
		if ( false === as_next_scheduled_action( 'wpfda_weekly_summary_email' ) ) {
			as_schedule_recurring_action( strtotime( '+ 7 days' ), WEEK_IN_SECONDS, 'wpfda_weekly_summary_email', array(), 'wp_frontend_delete_account' );
		}
	}

	/**
	 * Initiate email sending.
	 *
	 * @since 1.5.8
	 *
	 * @return bool
	 */
	public function initiate_email_sending() {

	}
}
