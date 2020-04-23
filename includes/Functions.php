<?php
/**
 * Core Functions of the plugin for both frontend and backend.
 *
 * @since  1.0.0
 *
 * @since 1.2.0 Rename filename from functions-wp-frontend-delete-account.php to Functions.php
 */

/**
 * Delete Account content.
 *
 * @return Void.
 */
function wpf_delete_account_content() {

	if ( ! is_user_logged_in() ) {
		return;
	}

	$button           = get_option( 'wpfda_button_label', 'Confirm' );
	$user_id          = get_current_user_id();
	$security         = get_option( 'wpfda_security', 'password' );
	$password_text    = get_option( 'wpfda_security_password_text', esc_html__( 'Enter password to confirm:', 'wp-frontend-delete-account' ) );
	$captcha_question = get_option( 'wpfda_security_custom_captcha_question', 'What is 11*3?' );
	$captcha_answer   = get_option( 'wpfda_security_custom_captcha_answer', '33' );
	$site_key         = get_option( 'wpfda_security_recaptcha_site_key' );
	$site_secret      = get_option( 'wpfda_security_recaptcha_site_secret' );
	$class            = apply_filters( 'wpfda_container_class', 'wpfda-delete-account-container' );

	do_action( 'wp_frontend_delete_account_before_content' );

	$html = '<div class="' . $class . '">';

	if ( current_user_can( 'administrator' ) ) {
		$html .= '<i style="color:red">';
		$html .= esc_html__( 'Just a heads up! You are the site administrator and processing further will delete yourself.', 'wp-frontend-delete-account' );
		$html .= '</i>';
	}

	if ( 'password' === $security ) {
		$html .= '<div class="wpfda-password-confirm">';
		$html .= '<label>' . $password_text . '</label>';
		$html .= ' <input type="password" name="wpfda-password" />';
		$html .= '</div>';
	} elseif ( 'custom_captcha' === $security && $captcha_question != '' ) {
		$html .= '<div class="wpfda-custom-captcha">';
		$html .= '<label>' . $captcha_question . '</label>';
		$html .= '<input type="text" name="wpfda-custom-captcha-answer" />';
		$html .= '</div">';
	}

		$html .= '<div class="wpfda-error">';
		$html .= '<span style="color:red"></span>';
		$html .= '</div>';

		$html .= '<div class="wpfda-submit">';
		$html .= '<a class="wpf-delete-account-button" href="#">';
		$html .= '<button>' . $button . '</button></a>';
		$html .= '</div>';

	$html .= '</div>';

	do_action( 'wp_frontend_delete_account_after_content' );

	return $html;
}
