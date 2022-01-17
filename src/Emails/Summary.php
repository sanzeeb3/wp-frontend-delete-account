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
	 * @since 1.5.8
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

		$summary_message = 'Oh, hi there, <br><br><h1>{number}</h1><br/><br/>users deleted their account this past week. <br><br><b>Previous week:</b> {previous_number} <br> <b>Total:</b> {total_number}';

		$emails['summary'] = array(
			'enable'    => get_option( 'wpfda_enable_summary_email', 'off' ),
			'label'     => esc_html__( 'Weekly Summary Email', 'wp-frontend-delete-account' ),
			'desc'      => esc_html__( 'Email notification sent to the admin about the total number of users deleted in a week. {number}, {previous_number} and {total} represents the number of users deleted this past week, previous week and the total number respectively.', 'wp-frontend-delete-account' ),
			'receipent' => get_option( 'wpfda_summary_email_receipent', get_option( 'admin_email' ) ),
			'subject'   => get_option( 'wpfda_summary_email_subject', esc_html__( 'WP Frontend Delete Account Weekly Summary', 'wp-frontend-delete-account' ) ),
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

		$options = $this->add_summary_email();

		if ( 'on' === $options['summary']['enable'] ) {
			if ( false === as_next_scheduled_action( 'wpfda_weekly_summary_email' ) ) {
				as_schedule_recurring_action( strtotime( '+ 7 days' ), WEEK_IN_SECONDS, 'wpfda_weekly_summary_email', array(), 'wp_frontend_delete_account' );
			}
		}
	}

	/**
	 * Initiate email sending.
	 *
	 * @since 1.5.8
	 *
	 * @return void
	 */
	public function initiate_email_sending() {

		$header = array( 'Content-Type: text/html; charset=UTF-8' );

		$options = $this->add_summary_email();

		if ( 'on' === $options['summary']['enable'] ) {

			$message         = $options['summary']['message'];
			$this_number     = $this->get_deleted_users_count()['this'];
			$previous_number = $this->get_deleted_users_count()['previous'];
			$total_number    = $this->get_deleted_users_count()['total'];

			$message = str_replace( '{number}', $this_number, $message );
			$message = str_replace( '{previous_number}', $previous_number, $message );
			$message = nl2br( str_replace( '{total_number}', $total_number, $message ) );

			// Add the WC footer text filter.
			add_filter( 'woocommerce_email_footer_text', array( $this, 'get_woocommerce_email_footer_text' ), 10, 1 );

			$sent = \WPFrontendDeleteAccount\Frontend::now_send( $options['summary']['receipent'], $options['summary']['subject'], $message, $header );

			// Remove the WC footer text filter.
			remove_filter( 'woocommerce_email_footer_text', array( $this, 'filter_woocommerce_email_footer_text' ), 10, 1 );
		}
	}

	/**
	 * Filter WooCommerce Footer Text.
	 *
	 * @param  string $get_option The footer text stored in database. It isn't in use, just for refrence.
	 *
	 * @since 1.5.8
	 *
	 * @return string.
	 */
	public function get_woocommerce_email_footer_text( $get_option ) {

		$site_url     = get_bloginfo( 'url' );
		$site_name    = get_bloginfo( 'name' );
		$did_you_know = $this->did_you_know();

		return sprintf(
			wp_kses(
				apply_filters(
					'wp_frontend_delete_account_summary_email_footer_text',
					/* translators: %1$s - blog link; %2$s - blog name; %3$s - did you know link; %4$s - did you know text;   */
					__( 'This email was auto-generated and sent from <a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>.<br><br>Did you know? You can <a href="%3$s">%4$s</a>', 'wp-frontend-delete-account' )
				),
				array(
					'strong' => true,
					'a'      => array(
						'href'   => true,
						'target' => true,
						'rel'    => true,
					),
					'br'     => true,
				)
			),
			$site_url,
			$site_name,
			$did_you_know['link'],
			$did_you_know['text']
		);
	}

	/**
	 * Did you know?
	 *
	 * @since 1.5.8.
	 *
	 * @return array
	 */
	public function did_you_know() {

		$did_you_know = wp_remote_post( 'https://sanjeebaryal.com.np/did-you-know.json' );

		if ( ! empty( $did_you_know['body'] ) ) {
			$did_you_know_in_array = json_decode( $did_you_know['body'], true );
		}

		if ( is_wp_error( $did_you_know ) || ! is_array( $did_you_know_in_array ) ) {
			return array(
				'link' => '',
				'text' => esc_html__( 'win!', 'wp-frontend-delete-account' ),
			);
		}

		$random_key = array_rand( $did_you_know_in_array );

		return apply_filters(
			'wp_frontend_delete_account_selected_did_you_know',
			array(
				'link' => $random_key,
				'text' => $did_you_know_in_array[ $random_key ],
			)
		);
	}

	/**
	 * Get the number of users deleted this week and previous week.
	 *
	 * @since 1.5.8
	 *
	 * @return array The number of users deleted this week and previous week.
	 */
	public function get_deleted_users_count() {

		$options   = get_option( 'wpfda_deleted_users_date' );
		$one_week  = strtotime( ' -1 week' );
		$two_weeks = strtotime( ' -2 week' );

		$number = array(
			'this'     => 0,
			'previous' => 0,
			'total'    => count( $options ),
		);

		foreach ( $options as $option ) {

			// Count the number of users deleteed this past week.
			if ( $option > $one_week ) {
				$number['this']++;
			}

			// Count the number of users deleteed the previous week.
			if ( $option < $one_week && $option > $two_weeks ) {
				$number['previous']++;
			}
		}

		return $number;
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
