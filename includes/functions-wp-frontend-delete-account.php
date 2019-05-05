<?php
/**
 * Core Functions of the plguin for both frontend and backend.
 *
 * @since  1.0.0
 */

/**
 * Delete Account content.
 *
 * @return Void.
 */
function wpf_delete_account_content() {

	if( ! is_user_logged_in() ) {
		return;
	}

	$button  			= get_option( 'wpfda_button_label', 'Confirm' );
	$user_id 			= get_current_user_id();
	$security 			= get_option( 'wpfda_security', 'password' );
	$password_text  	= get_option( 'wpfda_security_password_text', esc_html__( 'Enter password to confirm:', 'wp-frontend-delete-account' ) );
	$captcha_question 	= get_option( 'wpfda_security_custom_captcha_question', 'What is 11*3?' );
	$captcha_answer 	= get_option( 'wpfda_security_custom_captcha_answer', '33' );
	$site_key			= get_option( 'wpfda_security_recaptcha_site_key' );
	$site_secret		= get_option( 'wpfda_security_recaptcha_site_secret' );
	$class 				= apply_filters( 'wpfda_container_class', 'wpfda-delete-account-container' );

	do_action( 'wp_frontend_delete_account_before_content' );

	?>
		<div class="<?php echo $class;?>">
			<?php
				$html = '';
				if( 'password' === $security ) {
					$html = '<div class="wpfda-password-confirm">';
					$html .=  '<label>'. $password_text . '</label>';
					$html .= '<input type="password" name="wpfda-password" />';
					$html .= '</div>';

				} else if( 'custom_captcha' === $security && $captcha_question != '' ) {
					$html = '<div class="wpfda-custom-captcha">';
					$html .= '<label>' . $captcha_question . '</label>';
					$html .= '<input type="text" name="wpfda-custom-captcha-answer" />';
					$html .= '</div">';
				} else if( 'recaptcha_v2' === $security ) {
					wp_enqueue_script( 'wpfda-recaptcha');
					add_inline_recaptcha_script();

					echo '<div class="wpfda-recaptcha-container" style="margin-bottom:10px">';
					echo '<div class="g-recaptcha" data-sitekey="'. esc_attr( $site_key ) .'"></div>';
					echo '<input type="text" name="g-recaptcha-hidden" class="wpfda-recaptcha-hidden" style="position:absolute!important;clip:rect(0,0,0,0)!important;height:1px!important;width:1px!important;border:0!important;overflow:hidden!important;padding:0!important;margin:0!important;" required>';

					echo '</div>';
				}

				echo $html;
			?>
			<div class="wpfda-error">
				<span style="color:red"></span>
			</div>
			<div class="wpfda-submit">
				<a class="wpf-delete-account-button" href="#"><button><?php echo $button;?></button></a>
			</div>
		</div>

		<style>
			.wpf-delete-account-button button{
    			background-color: red;
    			border-color: red;
    			color: #fff;
			}
		</style>
	<?php

	do_action( 'wp_frontend_delete_account_after_content' );
}

/**
 * Add reCaptcha script inline.
 *
 * @since  1.0.0
 */
function add_inline_recaptcha_script() {
	$recaptcha_inline  = 'var wpfdaRecaptchaLoad = function(){jQuery(".g-recaptcha").each(function(index, el){grecaptcha.render(el,{callback:function(){wpfdaRecaptchaCallback(el);}},true);});};';
	$recaptcha_inline .= 'var wpfdaRecaptchaCallback = function(el){jQuery(el).find(".wpfda-recaptcha-hidden").val("1").valid();};';

	wp_add_inline_script( 'wpfda-recaptcha', $recaptcha_inline );
}
