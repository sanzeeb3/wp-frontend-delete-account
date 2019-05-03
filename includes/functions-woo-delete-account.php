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
function woo_delete_account_content() {
	$button  = get_option( 'wda_button_label', 'Confirm' );
	$user_id = get_current_user_id();
	$link    = add_query_arg(
				array(
						'woo-delete' => $user_id
					),
				esc_url( wp_nonce_url( home_url(), 'woo-delete-account' ) )
			);
	$security 			= get_option( 'wda_security', 'password' );
	$password_text  	= get_option( 'wda_security_password_text', esc_html__( 'Enter password to confirm:', 'woo-delete-account' ) );
	$captcha_question 	= get_option( 'wda_security_custom_captcha_question', 'What is 11*3?' );
	$captcha_answer 	= get_option( 'wda_security_custom_captcha_answer', '33' );
	$site_key			= get_option( 'wda_security_recaptcha_site_key' );
	$site_secret		= get_option( 'wda_security_recaptcha_site_secret' );

	?>
		<div class="wda-delete-account-container">
			<?php
				$html = '';
				if( 'password' === $security ) {
					$html =  '<label>'. $password_text . '</label>';
					$html .= '<input type="password" name="wda-password" />';

				} else if( 'custom_captcha' === $security && $captcha_question != '' ) {
					$html = '<label>' . $captcha_question . '</label>';
					$html .= '<input type="text" name="wda-custom-captcha-answer" />';
				} else if( 'recaptcha_v2' === $security ) {
					wp_enqueue_script( 'wda-recaptcha');
					add_inline_recaptcha_script();
				}

				echo $html;
			?>

			<div class="wda-submit">
				<a class="woo-delete-account-button" href="<?php echo $link;?>"><button><?php echo $button;?></button></a>
			</div>
		</div>
		<style>
			.woo-delete-account-button button{
    			background-color: red;
    			border-color: red;
    			color: #fff;
			}
		</style>
	<?php
}

/**
 * Add reCaptcha script inline.
 *
 * @since  1.0.0
 */
function add_inline_recaptcha_script() {
	$recaptch_inline  = 'var wdaRecaptchaLoad = function(){jQuery(".g-recaptcha").each(function(index, el){var recaptchaID = grecaptcha.render(el,{callback:function(){wdaRecaptchaCallback(el);}},true);jQuery(el).closest("form").find("button[type=submit]").get(0).recaptchaID = recaptchaID;});};';
	$recaptch_inline .= 'var wdaRecaptchaCallback = function(el){var $form = jQuery(el).closest("form");$form.find("button[type=submit]").get(0).recaptchaID = false;$form.submit();};';

	wp_add_inline_script( 'wda-recaptcha', $recaptch_inline );
}
