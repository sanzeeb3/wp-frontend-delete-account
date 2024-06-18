<?php
/**
 * Core Functions of the plugin for both frontend and backend.
 *
 * @since 1.0.0
 *
 * @since 1.2.0 Rename filename from functions-wp-frontend-delete-account.php to Functions.php
 *
 * @since 1.3.0 Move from includes/ dir to root dir.
 */

/**
 * Delete Account content.
 *
 * @return string
 */
function wpf_delete_account_content() {

	if ( ! is_user_logged_in() || wpfda_is_excluded() ) {
		return;
	}

	wp_enqueue_style( 'wpfda-frontend-css' );
	wp_enqueue_script( 'wpfda-delete-account-frontend' );

	ob_start();

	?>
			<div class='wpfda-delete-account-container'>
			</div>

			<script id='wpfda-delete-account-frontend-js'>
				var wpfda_plugins_params = <?php echo wp_json_encode( wpfda_i10n_data() ); ?>
			</script>
	<?php

		return ob_get_clean();
}

/**
 * The i10n data to pass to JavaScript files.
 *
 * @since 1.5.7
 *
 * @return array An array of data
 */
function wpfda_i10n_data() {

	return array(
		'pricing_page_url'    => admin_url('options-general.php?page=wp-frontend-delete-account-pricing'),
		'ajax_url'            => admin_url( 'admin-ajax.php' ),
		'wpfda_nonce'         => wp_create_nonce( 'wpfda_nonce' ),
		'is_feedback_enabled' => get_option( 'wpfda_enable_feedback_email', 'no' ),
		'redirect_url'        => get_option( 'wpfda_redirect_url', '' ),
		'incorrect_answer'    => esc_html__( 'Incorrect Answer. Please try again.', 'wp-frontend-delete-account' ),
		'empty_password'      => esc_html__( 'Empty Password.', 'wp-frontend-delete-account' ),
		'processing'          => esc_html__( 'Processing...', 'wp-frontend-delete-account' ),
		'deleting'            => esc_html__( 'Deleting...', 'wp-frontend-delete-account' ),
		'wrong'               => esc_html__( 'Oops! Something went wrong', 'wp-frontend-delete-account' ),
		'current_user_email'  => wp_get_current_user()->user_email,
		'button'              => get_option( 'wpfda_button_label', esc_html__( 'Confirm', 'wp-frontend-delete-account' ) ),
		'user_id'             => get_current_user_id(),
		'security'            => get_option( 'wpfda_security', 'password' ),
		'captcha_question'    => get_option( 'wpfda_security_custom_captcha_question', 'Enter PERMANENTLY DELETE to confirm:' ),
		'captcha_answer'      => get_option( 'wpfda_security_custom_captcha_answer', 'PERMANENTLY DELETE' ),
		'password_text'       => get_option( 'wpfda_security_password_text', esc_html__( 'Enter password to confirm:', 'wp-frontend-delete-account' ) ),
		'is_administrator'    => current_user_can( 'administrator' ),
	);
}

/**
 * Is User Role excluded.
 *
 * @since 2.0.0
 *
 * @return bool
 */
function wpfda_is_excluded() {

	if ( ! is_user_logged_in() ) {
		return true;
	}

	$excluded = ! empty( get_option( 'wpfda_exclude_roles' ) ) ? (array) get_option( 'wpfda_exclude_roles' ) : [];

	$user  = wp_get_current_user();
	$roles = (array) $user->roles;

	foreach ( $roles as $role ) {
		if ( in_array( $role, $excluded ) ) { //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			return true;
		}
	}

	return false;
}

/**
 * Delete all plugin data upon uninstallation.
 *
 * @since 2.0.0
 */
function wfda_fs_uninstall_cleanup() {

	global $wpdb;

	// Delete all options set by WP Frontend Delete Account.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'wpfda\_%';" );
}

wfda_fs()->add_action('after_uninstall', 'wfda_fs_uninstall_cleanup');

function wpfda_backend_sidebar() {

	ob_start();
	?>
	<div class="wp-frontend-delete-account-sidebar">
		<div class="wp-frontend-delete-account-widget wp-frontend-delete-account-box">
			<h2 class="wp-frontend-delete-account-widget_title"><?php esc_html_e( 'Account', 'wp-frontend-delete-account' ); ?></h2>
			<p><?php esc_html_e( 'You\'re using WP Frontend Delete Account PRO Version ❤️', 'wp-frontend-delete-account' ); ?></p>
			
			<p><a href="<?php echo admin_url( 'options-general.php?page=wp-frontend-delete-account-account' ); ?>"><strong><?php esc_html_e( 'Account',  'wp-frontend-delete-account' );?></strong></a> - <?php esc_html_e( 'your account information.', 'wp-frontend-delete-account' ); ?></p>
			<p><a href="https://miniplugins.com/how-to-allow-users-to-delete-their-account-from-frontend/" target="_blank"><strong><?php esc_html_e( 'Documentation',  'wp-frontend-delete-account' );?></strong></a> - <?php esc_html_e( 'docs & articles.', 'wp-frontend-delete-account' ); ?></p>
			<p><a href="<?php echo admin_url( 'options-general.php?page=wp-frontend-delete-account-contact' ); ?>"><strong><?php esc_html_e( 'Support',  'wp-frontend-delete-account' );?></strong></a> - <?php esc_html_e( 'need help or have questions?', 'wp-frontend-delete-account' ); ?></p>
			<p><a href="<?php echo admin_url( 'options-general.php?page=wp-frontend-delete-account-pricing' ); ?>"><strong><?php esc_html_e( 'Upgrade',  'wp-frontend-delete-account' );?></strong></a> - <?php esc_html_e( 'need more benifits?', 'wp-frontend-delete-account' ); ?></p>

		</div><br/>

		<div class="wp-frontend-delete-account-widget wp-frontend-delete-account-box">
			<h2 class="wp-frontend-delete-account-widget_title"><?php esc_html_e( 'Our WordPress Plugins', 'wp-frontend-delete-account' ); ?></h2>
			<p><?php esc_html_e( 'Like this plugin? Check out our other WordPress plugins:', 'wp-frontend-delete-account' ); ?></p>
			<p><a href="https://miniplugins.com/customer-journey-for-woocommerce/" target="_blank"><strong>WooCommerce Customer Journey</strong></a> - <?php esc_html_e( 'A simple yet powerful plugin that records every step of the visitor until the order of the product.', 'wp-frontend-delete-account' ); ?></p>
			<p><a href="https://miniplugins.com/wp-force-logout/" target="_blank"><strong>WPForce Logout</strong></a> - <?php esc_html_e( 'A user activity management plugin. See who\'s online, last login activity & more.', 'wp-frontend-delete-account' ); ?></p>
		</div>
	</div>
	<?php

	return ob_get_clean();
}