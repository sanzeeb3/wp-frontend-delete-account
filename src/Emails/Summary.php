<?php
/**
 * The weekely summary email is sent using Action Scheduler.
 *
 * Action Scheduler Homepage: https://actionscheduler.org/api/
 *
 * Github: https://github.com/woocommerce/action-scheduler
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

		add_filter( 'wp_frontend_delete_account_emails', array( $this, 'add_summary_email' ) );
		add_action( 'admin_init', array( $this, 'schedule_summary_email' ) );
		add_action( 'wpfda_weekly_summary_email', array( $this, 'initiate_email_sending' ) );
		add_action( 'wp_frontend_delete_account_process_complete', array( $this, 'update_deleted_users_date' ) );
	}

	/**
	 * Add Summary Email and it's options.
	 *
	 * @param array $emails An array of emails and their options.
	 *
	 * @since 1.5.8
	 */
	public function add_summary_email( $emails = array() ) {

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

		$header = array( 'Content-Type: text/html; charset=UTF-8' );

		$options = $this->add_summary_email();

		if ( $options['summary']['enable'] === 'on' ) {

			$message         = $options['summary']['message'];
			$this_number     = $this->get_deleted_users_count()['this'];
			$previous_number = $this->get_deleted_users_count()['previous'];

			$message = str_replace( '{number}', $this_number, $message );
			$message = nl2br( str_replace( '{previous_number}', $previous_number, $message ) );

			$sent = \WPFrontendDeleteAccount\Frontend::now_send( $options['summary']['receipent'], $options['summary']['subject'], $message, $header );
		}
	}

	/**
	 * Get the number of users deleted this week and previous week.
	 *
	 * @return array The number of users deleted this week and previous week.
	 */
	public function get_deleted_users_count() {

	}

	/**
	 * Store the deleted users date in array.
	 *
	 * @since 1.5.8
	 *
	 * @return void.
	 */
	public function update_deleted_users_date() {

		$options   = get_option( 'wpfda_deleted_users_date', array() );
		$options[] = strtotime( 'now' );

		update_option( 'wpfda_deleted_users_date', $options );
	}
}
